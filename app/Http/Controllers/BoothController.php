<?php

namespace App\Http\Controllers;

use App\Models\Booth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use App\Models\Banner;
use App\Models\Booking;
use App\Models\Event;
use Carbon\Carbon;

class BoothController extends Controller
{
    public function index(Request $request)
    {
        $eventId = $request->get('event_id');
        
        // Get main events (events that have main_title and can have booths)
        $events = Event::whereNotNull('main_title')
                      ->select('id', 'main_title', 'start_date', 'end_date', 'category')
                      ->orderBy('start_date', 'desc')
                      ->get();
        
        $booths = $eventId ? Booth::where('event_id', $eventId)->get() : collect();
        
        return view('admin.maps-booth', compact('booths', 'events', 'eventId'));
    }

    public function userIndex(Request $request)
    {
        $eventId = $request->get('event_id');
        
        // Get active main events (current or future events with exhibition category)
        $events = Event::whereNotNull('main_title')
                      ->where(function($query) {
                          $query->where('end_date', '>=', Carbon::today())
                                ->orWhere('category', 'EXHIBITION');
                      })
                      ->select('id', 'main_title', 'start_date', 'end_date', 'category')
                      ->orderBy('start_date', 'asc')
                      ->get();
        
        $booths = $eventId ? Booth::where('event_id', $eventId)->get() : collect();
        $banner = Banner::first();
        
        return view('pages.booking-booth', compact('booths', 'banner', 'events', 'eventId'));
    }

    // API untuk mengambil data booths berdasarkan event
    public function getBooths(Request $request): JsonResponse
    {
        $eventId = $request->get('event_id');
        
        if (!$eventId) {
            return response()->json([
                'success' => false,
                'message' => 'Event ID is required'
            ], 400);
        }

        // Validate that event exists and is a main event
        $event = Event::where('id', $eventId)
                     ->whereNotNull('main_title')
                     ->first();
        
        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Main event not found'
            ], 404);
        }

        $booths = Booth::where('event_id', $eventId)->get();
        return response()->json([
            'success' => true,
            'event' => [
                'id' => $event->id,
                'main_title' => $event->main_title,
                'start_date' => $event->start_date,
                'end_date' => $event->end_date,
                'category' => $event->category
            ],
            'booths' => $booths
        ]);
    }

    public function updatePosition(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'booth_id' => 'required|exists:booths,id',
                'position_x' => 'required|integer',
                'position_y' => 'required|integer'
            ]);

            $booth = Booth::findOrFail($request->booth_id);
            $booth->update([
                'position_x' => $request->position_x,
                'position_y' => $request->position_y
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booth position updated successfully',
                'booth' => $booth
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update position: ' . $e->getMessage()
            ], 422);
        }
    }

    // Method untuk halaman booking booth
    public function booking(Request $request)
    {
        $eventId = $request->get('event_id');
        
        // Get active main events with exhibition category
        $events = Event::whereNotNull('main_title')
                      ->where(function($query) {
                          $query->where('end_date', '>=', Carbon::today())
                                ->orWhere('category', 'EXHIBITION');
                      })
                      ->select('id', 'main_title', 'start_date', 'end_date', 'category')
                      ->orderBy('start_date', 'asc')
                      ->get();
        
        $booths = $eventId ? Booth::where('event_id', $eventId)->get() : collect();
        $banner = Banner::first();
        
        return view('pages.booking-booth', compact('booths', 'banner', 'events', 'eventId'));
    }

    // Method untuk memproses booking dari tenant
    public function processBooking(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'booth_id' => 'required|exists:booths,id',
                'company_name' => 'required|string|max:255',
                'contact_person' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'booking_date' => 'required|date|after_or_equal:today',
                'description' => 'nullable|string|max:1000'
            ]);

            // Check if booth is available
            $booth = Booth::findOrFail($request->booth_id);
            if ($booth->status !== 'available') {
                return response()->json([
                    'success' => false,
                    'message' => 'Booth sudah tidak tersedia'
                ], 422);
            }

            // Validate that the booth belongs to an active main event
            $event = Event::where('id', $booth->event_id)
                         ->whereNotNull('main_title')
                         ->where('end_date', '>=', Carbon::today())
                         ->first();
            
            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event tidak aktif atau tidak tersedia untuk booking'
                ], 422);
            }

            // Create booking
            $booking = Booking::create([
                'booth_id' => $request->booth_id,
                'company_name' => $request->company_name,
                'contact_person' => $request->contact_person,
                'phone' => $request->phone,
                'email' => $request->email,
                'booking_date' => $request->booking_date,
                'description' => $request->description,
                'total_price' => $booth->price,
                'status' => 'pending'
            ]);

            // Update booth status
            $booth->update(['status' => 'booked']);

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dikirim! Tim kami akan menghubungi Anda segera.',
                'booking' => $booking,
                'event' => $event->main_title
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses booking: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'event_id' => [
                    'required',
                    'exists:events,id',
                    // Ensure it's a main event
                    function ($attribute, $value, $fail) {
                        $event = Event::where('id', $value)->whereNotNull('main_title')->first();
                        if (!$event) {
                            $fail('Event harus berupa main event yang valid.');
                        }
                    },
                ],
                'booth_id' => [
                    'required',
                    'string',
                    // Make booth_id unique per event
                    Rule::unique('booths')->where(function ($query) use ($request) {
                        return $query->where('event_id', $request->event_id);
                    })
                ],
                'booth_name' => 'required|string',
                'section' => 'required|string|in:A,B,C,D,E,F,T',
                'price' => 'required|numeric|min:0',
                'position_x' => 'required|integer',
                'position_y' => 'required|integer',
                'width' => 'nullable|integer|min:20',
                'height' => 'nullable|integer|min:20',
                'status' => 'nullable|string|in:available,booked,maintenance'
            ]);

            // Set default values
            $data = $request->all();
            $data['width'] = $data['width'] ?? 25;
            $data['height'] = $data['height'] ?? 25;
            $data['status'] = $data['status'] ?? 'available';

            $booth = Booth::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Booth created successfully',
                'booth' => $booth
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create booth: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $booth = Booth::findOrFail($id);

            // Validate with unique rule excluding current booth
            $validated = $request->validate([
                'event_id' => [
                    'sometimes',
                    'exists:events,id',
                    // Ensure it's a main event
                    function ($attribute, $value, $fail) {
                        if ($value) {
                            $event = Event::where('id', $value)->whereNotNull('main_title')->first();
                            if (!$event) {
                                $fail('Event harus berupa main event yang valid.');
                            }
                        }
                    },
                ],
                'booth_id' => [
                    'sometimes',
                    'string',
                    Rule::unique('booths')->where(function ($query) use ($request, $booth) {
                        return $query->where('event_id', $request->get('event_id', $booth->event_id));
                    })->ignore($booth->id)
                ],
                'booth_name' => 'sometimes|string',
                'section' => 'sometimes|string|in:A,B,C,D,E,F,T',
                'price' => 'sometimes|numeric|min:0',
                'status' => 'sometimes|string|in:available,booked,maintenance',
                'width' => 'sometimes|integer|min:20',
                'height' => 'sometimes|integer|min:20',
                'position_x' => 'sometimes|nullable|integer',
                'position_y' => 'sometimes|nullable|integer',
            ]);

            // Update only validated fields
            $booth->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Booth updated successfully',
                'booth' => $booth->fresh()
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booth not found'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update booth: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $booth = Booth::findOrFail($id);
            
            // Check if booth is booked
            if ($booth->status === 'booked') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete booked booth'
                ], 422);
            }
            
            $booth->delete();

            return response()->json([
                'success' => true,
                'message' => 'Booth deleted successfully'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booth not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete booth: ' . $e->getMessage()
            ], 500);
        }
    }

    public function initializeBooths(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'event_id' => [
                    'required',
                    'exists:events,id',
                    // Ensure it's a main event
                    function ($attribute, $value, $fail) {
                        $event = Event::where('id', $value)->whereNotNull('main_title')->first();
                        if (!$event) {
                            $fail('Event harus berupa main event yang valid.');
                        }
                    },
                ]
            ]);

            $eventId = $request->event_id;

            // Check if booths already exist for this event
            if (Booth::where('event_id', $eventId)->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booths already initialized for this event'
                ]);
            }

            $defaultBooths = $this->getDefaultBoothsData($eventId);

            foreach ($defaultBooths as $boothData) {
                Booth::create($boothData);
            }

            return response()->json([
                'success' => true,
                'message' => 'Booths initialized successfully',
                'count' => count($defaultBooths)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to initialize booths: ' . $e->getMessage()
            ], 500);
        }
    }

    // Method to get default booths data
    private function getDefaultBoothsData($eventId)
    {
        $booths = [];
        $sections = ['A', 'B', 'C', 'D', 'E', 'F'];
        $boothCounter = 1;

        foreach ($sections as $section) {
            for ($i = 1; $i <= 10; $i++) {
                $booths[] = [
                    'event_id' => $eventId,
                    'booth_id' => $section . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'booth_name' => "Booth {$section}{$i}",
                    'section' => $section,
                    'price' => rand(50000, 200000),
                    'width' => 25,
                    'height' => 25,
                    'status' => 'available',
                    'position_x' => ($i - 1) * 30 + 50,
                    'position_y' => array_search($section, $sections) * 60 + 50,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        return $booths;
    }

    // Get available main events for booth management
    public function getMainEvents(): JsonResponse
    {
        try {
            $events = Event::whereNotNull('main_title')
                          ->select('id', 'main_title', 'start_date', 'end_date', 'category')
                          ->orderBy('start_date', 'desc')
                          ->get();

            return response()->json([
                'success' => true,
                'events' => $events
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get events: ' . $e->getMessage()
            ], 500);
        }
    }
}