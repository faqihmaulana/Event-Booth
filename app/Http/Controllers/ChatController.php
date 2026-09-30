<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display admin chat page
     */
    public function adminChat()
    {
        return view('admin.chat');
    }

    /**
     * Display tenant chat page
     */
    public function tenantChat()
    {
        return view('pages.chat-tenant');
    }

    /**
     * Get tenant list for admin
     */
    public function getTenantList()
    {
        $tenants = User::where('role_id', 2)->get(['id', 'name', 'email']);

        $tenantList = $tenants->map(function ($tenant) {
            $unreadCount = Chat::where('sender_id', $tenant->id)
                ->where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->count();

            $lastMessage = Chat::betweenUsers(Auth::id(), $tenant->id)
                ->orderBy('created_at', 'desc')
                ->first();

            return [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'email' => $tenant->email,
                'unread_count' => $unreadCount,
                'last_message' => $lastMessage ? $lastMessage->message : null,
                'last_message_time' => $lastMessage ? $lastMessage->created_at->format('H:i') : null
            ];
        });

        return response()->json($tenantList);
    }

    /**
     * Get admin list for tenant
     */
    public function getAdminList()
    {
        $admins = User::where('role_id', 1)->get(['id', 'name', 'email']);

        $adminList = $admins->map(function ($admin) {
            $unreadCount = Chat::where('sender_id', $admin->id)
                ->where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->count();

            $lastMessage = Chat::betweenUsers(Auth::id(), $admin->id)
                ->orderBy('created_at', 'desc')
                ->first();

            return [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'unread_count' => $unreadCount,
                'last_message' => $lastMessage ? $lastMessage->message : null,
                'last_message_time' => $lastMessage ? $lastMessage->created_at->format('H:i') : null
            ];
        });

        return response()->json($adminList);
    }

    /**
     * Get messages between users
     */
    public function getMessages($userId)
    {
        $messages = Chat::betweenUsers(Auth::id(), $userId)
            ->orderBy('created_at', 'asc')
            ->get(['id', 'sender_id', 'receiver_id', 'message', 'created_at', 'is_read']);

        // Mark messages as read
        Chat::where('sender_id', $userId)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $formattedMessages = $messages->map(function ($message) {
            return [
                'id' => $message->id,
                'message' => $message->message,
                'sender_id' => $message->sender_id,
                'receiver_id' => $message->receiver_id,
                'is_sent' => $message->sender_id == Auth::id(),
                'time' => $message->created_at->format('H:i'),
                'date' => $message->created_at->format('d/m/Y')
            ];
        });

        return response()->json($formattedMessages);
    }

    /**
     * Send message to all admins (for tenant)
     */
    public function sendToAllAdmins(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        // Get all admins
        $admins = User::where('role_id', 1)->pluck('id');

        if ($admins->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada admin yang tersedia'
            ], 404);
        }

        $chatIds = [];

        // Send message to all admins
        foreach ($admins as $adminId) {
            $chat = Chat::create([
                'sender_id' => Auth::id(),
                'receiver_id' => $adminId,
                'message' => $request->message,
                'is_read' => false
            ]);

            $chatIds[] = $chat->id;
        }

        return response()->json([
            'success' => true,
            'message' => 'Pesan berhasil dikirim',
            'data' => [
                'message' => $request->message,
                'sender_id' => Auth::id(),
                'is_sent' => true,
                'time' => now()->format('H:i'),
                'date' => now()->format('d/m/Y')
            ]
        ]);
    }

    /**
     * Send message (original method for admin to tenant)
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000'
        ]);

        $chat = Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'is_read' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pesan berhasil dikirim',
            'data' => [
                'id' => $chat->id,
                'message' => $chat->message,
                'sender_id' => $chat->sender_id,
                'receiver_id' => $chat->receiver_id,
                'is_sent' => true,
                'time' => $chat->created_at->format('H:i'),
                'date' => $chat->created_at->format('d/m/Y')
            ]
        ]);
    }

    /**
     * Get consolidated messages for tenant (from all admins) - FIXED to prevent duplicates
     */
    public function getTenantMessages()
    {
        // Get all admin IDs
        $adminIds = User::where('role_id', 1)->pluck('id');

        // Get unique messages using DISTINCT on message content, timestamp, and sender
        $messages = Chat::where(function ($query) use ($adminIds) {
            // Messages sent by tenant to any admin
            $query->where('sender_id', Auth::id())
                ->whereIn('receiver_id', $adminIds);
        })
            ->orWhere(function ($query) use ($adminIds) {
                // Messages sent by any admin to tenant
                $query->whereIn('sender_id', $adminIds)
                    ->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->with('sender:id,name')
            ->get(['id', 'sender_id', 'receiver_id', 'message', 'created_at', 'is_read']);

        // Remove duplicate messages sent by tenant (keep only one copy of each message)
        $uniqueMessages = collect();
        $processedTenantMessages = [];

        foreach ($messages as $message) {
            if ($message->sender_id == Auth::id()) {
                // For tenant messages, only keep one copy based on message content and timestamp
                $messageKey = $message->message . '|' . $message->created_at->format('Y-m-d H:i:s');

                if (!in_array($messageKey, $processedTenantMessages)) {
                    $uniqueMessages->push($message);
                    $processedTenantMessages[] = $messageKey;
                }
            } else {
                // For admin messages, keep all
                $uniqueMessages->push($message);
            }
        }

        // Mark messages from admins as read
        Chat::whereIn('sender_id', $adminIds)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $formattedMessages = $uniqueMessages->map(function ($message) {
            $isFromAdmin = in_array($message->sender_id, User::where('role_id', 1)->pluck('id')->toArray());

            return [
                'id' => $message->id,
                'message' => $message->message,
                'sender_id' => $message->sender_id,
                'receiver_id' => $message->receiver_id,
                'is_sent' => $message->sender_id == Auth::id(),
                'sender_name' => $isFromAdmin ? $message->sender->name : 'Anda',
                'time' => $message->created_at->format('H:i'),
                'date' => $message->created_at->format('d/m/Y')
            ];
        });

        return response()->json($formattedMessages);
    }

    /**
     * Get unread message count
     */
    public function getUnreadCount()
    {
        $count = Chat::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    public function getAdminNotifications()
    {
        // Only for admin users
        if (Auth::user()->role_id !== 1) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get recent unread messages from tenants to current admin
        $recentMessages = Chat::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->whereHas('sender', function ($query) {
                $query->where('role_id', 2); // Only from tenants
            })
            ->with('sender:id,name,email')
            ->orderBy('created_at', 'desc')
            ->take(5) // Last 5 unread messages
            ->get();

        $notifications = $recentMessages->map(function ($message) {
            return [
                'id' => $message->id,
                'sender_name' => $message->sender->name,
                'sender_email' => $message->sender->email,
                'message' => strlen($message->message) > 50
                    ? substr($message->message, 0, 50) . '...'
                    : $message->message,
                'time_ago' => $this->getTimeAgo($message->created_at),
                'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                'sender_id' => $message->sender_id
            ];
        });

        return response()->json([
            'notifications' => $notifications,
            'total_unread' => $recentMessages->count()
        ]);
    }

    /**
     * Get total unread count for admin notifications badge
     */
    public function getAdminUnreadCount()
    {
        // Only for admin users
        if (Auth::user()->role_id !== 1) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $count = Chat::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->whereHas('sender', function ($query) {
                $query->where('role_id', 2); // Only from tenants
            })
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Mark notification as read
     */
    public function markNotificationAsRead($messageId)
    {
        $message = Chat::where('id', $messageId)
            ->where('receiver_id', Auth::id())
            ->first();

        if ($message) {
            $message->update(['is_read' => true]);
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Message not found'], 404);
    }

    /**
     * Mark all notifications from a sender as read
     */
    public function markAllFromSenderAsRead($senderId)
    {
        Chat::where('sender_id', $senderId)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(attributes: ['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Helper function to get time ago format
     */
    private function getTimeAgo($datetime)
    {
        $time = time() - strtotime($datetime);

        if ($time < 60) {
            return 'Baru saja';
        } elseif ($time < 3600) {
            $minutes = floor($time / 60);
            return $minutes . ' menit yang lalu';
        } elseif ($time < 86400) {
            $hours = floor($time / 3600);
            return $hours . ' jam yang lalu';
        } else {
            $days = floor($time / 86400);
            return $days . ' hari yang lalu';
        }
    }
}