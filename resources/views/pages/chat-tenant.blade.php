<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat - EventKu</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('admin/img/favicon.png') }}" rel="icon">
    <style>
        body {
            background-color: #e3f2fd;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .navbar {
            background-color: #42a5f5 !important;
            box-shadow: 0 2px 10px rgba(66, 165, 245, 0.2);
        }

        .navbar-brand {
            font-weight: 600;
            color: white !important;
        }

        .chat-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .chat-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: none;
        }

        .chat-header {
            background-color: #42a5f5;
            color: white;
            padding: 1.5rem;
            text-align: center;
        }

        .chat-header h5 {
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .online-indicator {
            width: 10px;
            height: 10px;
            background-color: #4fc3f7;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .chat-body {
            padding: 1.5rem;
        }

        .support-info {
            background-color: #f5f5f5;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            text-align: center;
        }

        .support-info h6 {
            color: #333;
            margin-bottom: 0.5rem;
        }

        .support-info small {
            color: #666;
        }

        .chat-box {
            height: 400px;
            overflow-y: auto;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            background-color: #fafafa;
        }

        .chat-box::-webkit-scrollbar {
            width: 6px;
        }

        .chat-box::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .chat-box::-webkit-scrollbar-thumb {
            background: #42a5f5;
            border-radius: 3px;
        }

        .message-item {
            margin-bottom: 1rem;
            padding: 0.8rem 1rem;
            border-radius: 12px;
            max-width: 70%;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .message-sent {
            background-color: #42a5f5;
            color: white;
            margin-left: auto;
            text-align: right;
        }

        .message-received {
            background-color: white;
            color: #333;
            border: 1px solid #e0e0e0;
        }

        .message-time {
            font-size: 0.75rem;
            opacity: 0.7;
            margin-top: 0.5rem;
        }

        .admin-badge {
            background-color: #29b6f6;
            color: white;
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 8px;
            margin-left: 0.5rem;
            font-weight: 500;
        }

        .input-area {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .message-input {
            flex: 1;
            border: 1px solid #e0e0e0;
            border-radius: 20px;
            padding: 0.8rem 1rem;
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.3s;
        }

        .message-input:focus {
            border-color: #42a5f5;
            box-shadow: 0 0 0 3px rgba(66, 165, 245, 0.1);
        }

        .send-button {
            background-color: #42a5f5;
            border: none;
            color: white;
            padding: 0.8rem 1.2rem;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .send-button:hover {
            background-color: #1e88e5;
            transform: translateY(-1px);
        }

        .send-button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .welcome-message {
            text-align: center;
            padding: 2rem;
            color: #666;
        }

        .welcome-message i {
            color: #42a5f5;
            margin-bottom: 1rem;
        }

        .welcome-message h5 {
            color: #333;
            margin-bottom: 1rem;
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            border-radius: 8px;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(100%); }
            to { opacity: 1; transform: translateX(0); }
        }

        @media (max-width: 768px) {
            .chat-container {
                margin: 1rem;
                padding: 0;
            }
            
            .chat-body {
                padding: 1rem;
            }
            
            .chat-box {
                height: 300px;
            }
            
            .message-item {
                max-width: 85%;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-comments me-2"></i>
                EventKu Chat
            </a>
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown">
                        <i class="fas fa-user me-1"></i>
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('home') }}">Kembali ke Website</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Chat Container -->
    <div class="chat-container">
        <div class="chat-card">
            <!-- Chat Header -->
            <div class="chat-header">
                <h5>
                    <i class="fas fa-headset me-2"></i>
                    Customer Support
                </h5>
                <small>
                    <span class="online-indicator"></span>
                    Tim support kami siap membantu Anda
                </small>
            </div>

            <!-- Chat Body -->
            <div class="chat-body">
                <!-- Support Info -->
                <div class="support-info">
                    <h6>
                        <i class="fas fa-users me-2 text-primary"></i>
                        Customer Support Team
                    </h6>
                    <small>Pesan Anda akan diterima oleh semua admin support</small>
                </div>

                <!-- Messages Area -->
                <div class="chat-box" id="chatBox">
                    <div class="welcome-message">
                        <i class="fas fa-comments fa-3x"></i>
                        <h5>Selamat datang di EventKu Support!</h5>
                        <p>Silakan kirim pesan Anda, tim support kami akan membantu Anda.</p>
                    </div>
                </div>

                <!-- Message Input -->
                <div class="input-area">
                    <input type="text" class="message-input" id="messageInput" 
                           placeholder="Ketik pesan Anda..." />
                    <button class="send-button" id="sendMessage">
                        <i class="fas fa-paper-plane me-1"></i>
                        Kirim
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            let messageRefreshInterval = null;
            let lastMessageCount = 0;
            let lastMessageTime = '';

            // Setup AJAX headers
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Load consolidated messages from all admins
            function loadMessages(forceUpdate = false) {
                console.log('Checking for new messages...');
                $.get('/tenant/chat/messages-all')
                    .done(function(data) {
                        // Check if there are actually new messages
                        let hasNewMessages = false;
                        let currentMessageTime = '';
                        
                        if (data.length !== lastMessageCount) {
                            hasNewMessages = true;
                        } else if (data.length > 0) {
                            currentMessageTime = data[data.length - 1].time;
                            if (currentMessageTime !== lastMessageTime) {
                                hasNewMessages = true;
                            }
                        }

                        // Only update if there are new messages or force update
                        if (hasNewMessages || forceUpdate || lastMessageCount === 0) {
                            console.log('New messages found, updating display...');
                            lastMessageCount = data.length;
                            if (data.length > 0) {
                                lastMessageTime = data[data.length - 1].time;
                            }

                            let html = '';
                            if (data.length === 0) {
                                html = `
                                    <div class="welcome-message">
                                        <i class="fas fa-comments fa-3x"></i>
                                        <h5>Selamat datang di EventKu Support!</h5>
                                        <p>Silakan kirim pesan Anda, tim support kami akan membantu Anda.</p>
                                    </div>
                                `;
                            } else {
                                data.forEach(function(message) {
                                    let messageClass = message.is_sent ? 'message-sent' : 'message-received';
                                    let senderLabel = message.is_sent ? 'Anda' : message.sender_name;
                                    
                                    // Add admin badge for received messages
                                    let adminBadge = '';
                                    if (!message.is_sent) {
                                        adminBadge = '<span class="admin-badge">Admin</span>';
                                    }
                                    
                                    html += `
                                        <div class="message-item ${messageClass}">
                                            <div>${message.message}</div>
                                            <div class="message-time">${senderLabel}${adminBadge} • ${message.time}</div>
                                        </div>
                                    `;
                                });
                            }
                            
                            // Store current scroll position
                            let chatBox = $('#chatBox')[0];
                            let wasAtBottom = chatBox.scrollHeight - chatBox.clientHeight <= chatBox.scrollTop + 1;
                            
                            $('#chatBox').html(html);
                            
                            // Scroll to bottom only if user was already at bottom or it's a new message
                            if (wasAtBottom || hasNewMessages) {
                                $('#chatBox').scrollTop($('#chatBox')[0].scrollHeight);
                            }
                        } else {
                            console.log('No new messages, skipping update');
                        }
                    })
                    .fail(function(xhr, status, error) {
                        console.error('Failed to load messages:', xhr.status, xhr.responseText);
                    });
            }

            // Send message to all admins
            function sendMessage() {
                let message = $('#messageInput').val().trim();
                if (message === '') {
                    return;
                }

                let sendButton = $('#sendMessage');
                let originalText = sendButton.html();
                sendButton.html('<i class="fas fa-spinner fa-spin me-1"></i>Mengirim...').prop('disabled', true);

                console.log('Sending message to all admins:', message);

                $.ajax({
                    url: '/tenant/chat/send-to-all-admins',
                    method: 'POST',
                    data: {
                        message: message,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log('Send success response:', response);
                        if (response.success) {
                            $('#messageInput').val('');
                            loadMessages(true); // Force update after sending
                            showNotification('Pesan berhasil dikirim', 'success');
                        } else {
                            console.log('Response indicates failure:', response);
                            showNotification('Gagal mengirim pesan: ' + (response.message || 'Unknown error'), 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Send error details:', {
                            status: xhr.status,
                            statusText: xhr.statusText,
                            responseText: xhr.responseText,
                            error: error
                        });
                        
                        let errorMessage = 'Gagal mengirim pesan. ';
                        if (xhr.status === 422) {
                            errorMessage += 'Data tidak valid.';
                        } else if (xhr.status === 500) {
                            errorMessage += 'Server error.';
                        } else if (xhr.status === 404) {
                            errorMessage += 'Tidak ada admin yang tersedia.';
                        } else {
                            errorMessage += `Error ${xhr.status}: ${xhr.statusText}`;
                        }
                        
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage += ' ' + xhr.responseJSON.message;
                        }
                        
                        showNotification(errorMessage, 'error');
                    },
                    complete: function() {
                        sendButton.html(originalText).prop('disabled', false);
                    }
                });
            }

            // Show notification
            function showNotification(message, type) {
                let alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                let iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
                
                let notification = $(`
                    <div class="alert ${alertClass} alert-dismissible fade show notification" role="alert">
                        <i class="fas ${iconClass} me-2"></i>
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);
                
                $('body').append(notification);
                
                // Auto dismiss after 4 seconds
                setTimeout(function() {
                    notification.alert('close');
                }, 4000);
            }

            // Event handlers
            $('#sendMessage').click(sendMessage);
            
            $('#messageInput').keypress(function(e) {
                if (e.which === 13) {
                    sendMessage();
                }
            });

            // Auto-focus on message input
            $('#messageInput').focus();

            // Initial setup
            loadMessages(true); // Force initial load
            
            // Auto-refresh messages every 5 seconds (increased from 3)
            messageRefreshInterval = setInterval(function() {
                loadMessages(false); // Don't force update on auto-refresh
            }, 5000);

            // Stop auto-refresh when page is not visible
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    if (messageRefreshInterval) {
                        clearInterval(messageRefreshInterval);
                        messageRefreshInterval = null;
                    }
                } else {
                    if (!messageRefreshInterval) {
                        loadMessages();
                        messageRefreshInterval = setInterval(loadMessages, 3000);
                    }
                }
            });
        });
    </script>
</body>
</html>