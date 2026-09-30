<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use App\Models\Booth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    /**
     * Display statistics dashboard
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $year = $request->get('year', date('Y'));
        $eventId = $request->get('event_id');
        $section = $request->get('section');
        $city = $request->get('city');

        $stats = $this->getBasicStats($year, $eventId, $section, $city);
        $chartData = $this->getChartData($year, $eventId, $section, $city);
        
        // Get filter options
        $events = Event::select('id', 'main_title')->orderBy('main_title')->get();
        $sections = Booth::select('section')->distinct()->whereNotNull('section')->pluck('section');
        $cities = User::select('address')->distinct()->whereNotNull('address')->pluck('address');
        
        return view('admin.statistics', compact('stats', 'chartData', 'events', 'sections', 'cities', 'year', 'eventId', 'section', 'city'));
    }

    /**
     * Get basic statistics
     */
    private function getBasicStats($year = null, $eventId = null, $section = null, $city = null)
    {
        $query = Booking::query();
        
        // Filter berdasarkan tahun event (start_date), bukan created_at booking
        if ($year) {
            $query->whereHas('booth.event', function($q) use ($year) {
                $q->whereYear('start_date', $year);
            });
        }
        
        if ($eventId) {
            $query->whereHas('booth', function($q) use ($eventId) {
                $q->where('event_id', $eventId);
            });
        }
        
        if ($section) {
            $query->whereHas('booth', function($q) use ($section) {
                $q->where('section', $section);
            });
        }
        
        if ($city) {
            $query->whereHas('user', function($q) use ($city) {
                $q->where('address', 'LIKE', "%{$city}%");
            });
        }

        return [
            'total_bookings' => (clone $query)->count(),
            'confirmed_bookings' => (clone $query)->where('status', 'confirmed')->count(),
            'cancelled_bookings' => (clone $query)->where('status', 'cancelled')->count(),
            'pending_bookings' => (clone $query)->where('status', 'pending')->count(),
            'total_revenue' => (clone $query)->where('status', 'confirmed')->sum('total_price') ?? 0,
            'unique_booths' => (clone $query)->distinct('booth_id')->count('booth_id'),
        ];
    }

    /**
     * Get chart data for statistics
     */
    private function getChartData($year = null, $eventId = null, $section = null, $city = null)
    {
        $baseQuery = function() use ($year, $eventId, $section, $city) {
            $query = Booking::query();
            
            // Filter berdasarkan tahun event (start_date), bukan created_at booking
            if ($year) {
                $query->whereHas('booth.event', function($q) use ($year) {
                    $q->whereYear('start_date', $year);
                });
            }
            
            if ($eventId) {
                $query->whereHas('booth', function($q) use ($eventId) {
                    $q->where('event_id', $eventId);
                });
            }
            
            if ($section) {
                $query->whereHas('booth', function($q) use ($section) {
                    $q->where('section', $section);
                });
            }
            
            if ($city) {
                $query->whereHas('user', function($q) use ($city) {
                    $q->where('address', 'LIKE', "%{$city}%");
                });
            }
            
            return $query;
        };

        // Enhanced Event booking statistics with proper counts
        $eventQuery = DB::table('events')
            ->leftJoin('booths', 'events.id', '=', 'booths.event_id')
            ->leftJoin('bookings', 'booths.id', '=', 'bookings.booth_id')
            ->select(
                'events.id',
                'events.main_title',
                DB::raw('COUNT(DISTINCT CASE WHEN bookings.status = "confirmed" THEN booths.id END) as confirmed_booths'),
                DB::raw('COUNT(CASE WHEN bookings.status = "confirmed" THEN 1 END) as confirmed_bookings'),
                DB::raw('COUNT(CASE WHEN bookings.status = "cancelled" THEN 1 END) as cancelled_count'),
                DB::raw('COUNT(CASE WHEN bookings.status = "pending" THEN 1 END) as pending_count'),
                DB::raw('COALESCE(SUM(CASE WHEN bookings.status = "confirmed" THEN bookings.total_price ELSE 0 END), 0) as total_revenue')
            )
            ->whereNotNull('events.id')
            ->whereNotNull('events.main_title');

        // Apply filters - menggunakan start_date event untuk filter tahun
        if ($year) {
            $eventQuery->whereYear('events.start_date', $year);
        }
        if ($eventId) {
            $eventQuery->where('events.id', $eventId);
        }
        if ($section) {
            $eventQuery->where('booths.section', $section);
        }
        if ($city) {
            $eventQuery->leftJoin('users', 'bookings.user_id', '=', 'users.id')
                      ->where('users.address', 'LIKE', "%{$city}%");
        }

        $eventStats = $eventQuery->groupBy('events.id', 'events.main_title')
            ->havingRaw('(COUNT(CASE WHEN bookings.status = "confirmed" THEN 1 END) + COUNT(CASE WHEN bookings.status = "cancelled" THEN 1 END) + COUNT(CASE WHEN bookings.status = "pending" THEN 1 END)) > 0')
            ->get()
            ->map(function($item) {
                $item->event_name = $item->main_title ?? 'Event #' . $item->id;
                return $item;
            });

        // Section popularity
        $sectionStats = $baseQuery()
            ->join('booths', 'bookings.booth_id', '=', 'booths.id')
            ->select(
                'booths.section',
                DB::raw('COUNT(CASE WHEN bookings.status = "confirmed" THEN 1 END) as confirmed_bookings'),
                DB::raw('COUNT(DISTINCT booths.id) as unique_booths')
            )
            ->whereNotNull('booths.section')
            ->groupBy('booths.section')
            ->orderBy('confirmed_bookings', 'desc')
            ->get();

        // Booking time patterns - tetap menggunakan created_at untuk pola waktu booking
        $hourlyStats = $baseQuery()
            ->select(
                DB::raw('HOUR(bookings.created_at) as hour'),
                DB::raw('COUNT(*) as booking_count')
            )
            ->groupBy(DB::raw('HOUR(bookings.created_at)'))
            ->orderBy('hour')
            ->get();

        // City/address statistics
        $cityStats = $baseQuery()
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->select(
                'users.address as city',
                DB::raw('COUNT(*) as booking_count'),
                DB::raw('COUNT(CASE WHEN bookings.status = "confirmed" THEN 1 END) as confirmed_bookings')
            )
            ->whereNotNull('users.address')
            ->groupBy('users.address')
            ->orderBy('booking_count', 'desc')
            ->limit(15)
            ->get();

        return [
            'events' => $eventStats,
            'sections' => $sectionStats,
            'hourly' => $hourlyStats,
            'cities' => $cityStats,
        ];
    }

    /**
     * Get event statistics API
     */
    public function getEventStats(Request $request)
    {
        try {
            $year = $request->get('year', date('Y'));
            $eventId = $request->get('event_id');
            $section = $request->get('section');
            $city = $request->get('city');

            $query = DB::table('events')
                ->leftJoin('booths', 'events.id', '=', 'booths.event_id')
                ->leftJoin('bookings', 'booths.id', '=', 'bookings.booth_id')
                ->select(
                    'events.id',
                    'events.main_title as name',
                    DB::raw('COUNT(DISTINCT CASE WHEN bookings.status = "confirmed" THEN booths.id END) as unique_booths'),
                    DB::raw('COUNT(CASE WHEN bookings.status = "confirmed" THEN 1 END) as confirmed'),
                    DB::raw('COUNT(CASE WHEN bookings.status = "cancelled" THEN 1 END) as cancelled'),
                    DB::raw('COUNT(CASE WHEN bookings.status = "pending" THEN 1 END) as pending'),
                    DB::raw('COUNT(bookings.id) as total'),
                    DB::raw('COALESCE(SUM(CASE WHEN bookings.status = "confirmed" THEN bookings.total_price ELSE 0 END), 0) as revenue')
                )
                ->whereNotNull('events.main_title');

            // Apply filters - menggunakan start_date event
            if ($year) {
                $query->whereYear('events.start_date', $year);
            }
            if ($eventId) {
                $query->where('events.id', $eventId);
            }
            if ($section) {
                $query->where('booths.section', $section);
            }
            if ($city) {
                $query->leftJoin('users', 'bookings.user_id', '=', 'users.id')
                      ->where('users.address', 'LIKE', "%{$city}%");
            }

            $eventStats = $query->groupBy('events.id', 'events.main_title')
                ->having('total', '>', 0)
                ->get()
                ->map(function($event) {
                    return [
                        'id' => $event->id,
                        'name' => $event->name ?? 'Event #' . $event->id,
                        'confirmed' => (int) $event->confirmed,
                        'cancelled' => (int) $event->cancelled,
                        'pending' => (int) $event->pending,
                        'total' => (int) $event->total,
                        'unique_booths' => (int) $event->unique_booths,
                        'revenue' => (float) $event->revenue
                    ];
                })
                ->values();

            return response()->json($eventStats);
        } catch (\Exception $e) {
            \Log::error('Error in getEventStats: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load event statistics'], 500);
        }
    }

    /**
     * Get section popularity statistics
     */
    public function getSectionStats(Request $request)
    {
        try {
            $year = $request->get('year', date('Y'));
            $eventId = $request->get('event_id');
            $city = $request->get('city');

            $query = Booking::join('booths', 'bookings.booth_id', '=', 'booths.id')
                ->select(
                    'booths.section',
                    DB::raw('COUNT(CASE WHEN bookings.status = "confirmed" THEN 1 END) as confirmed_bookings'),
                    DB::raw('COUNT(DISTINCT booths.id) as unique_booths'),
                    DB::raw('COUNT(*) as total_bookings')
                )
                ->whereNotNull('booths.section');

            // Filter berdasarkan start_date event
            if ($year) {
                $query->join('events', 'booths.event_id', '=', 'events.id')
                      ->whereYear('events.start_date', $year);
            }
            if ($eventId) {
                $query->where('booths.event_id', $eventId);
            }
            if ($city) {
                $query->join('users', 'bookings.user_id', '=', 'users.id')
                      ->where('users.address', 'LIKE', "%{$city}%");
            }

            $sectionStats = $query->groupBy('booths.section')
                ->orderBy('confirmed_bookings', 'desc')
                ->get();

            return response()->json($sectionStats);
        } catch (\Exception $e) {
            \Log::error('Error in getSectionStats: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load section statistics'], 500);
        }
    }

    /**
     * Get hourly booking patterns
     */
    public function getHourlyStats(Request $request)
    {
        try {
            $year = $request->get('year', date('Y'));
            $eventId = $request->get('event_id');
            $section = $request->get('section');
            $city = $request->get('city');

            $query = Booking::select(
                DB::raw('HOUR(bookings.created_at) as hour'),
                DB::raw('COUNT(*) as booking_count')
            );

            // Filter berdasarkan start_date event
            if ($year) {
                $query->whereHas('booth.event', function($q) use ($year) {
                    $q->whereYear('start_date', $year);
                });
            }
            if ($eventId) {
                $query->whereHas('booth', function($q) use ($eventId) {
                    $q->where('event_id', $eventId);
                });
            }
            if ($section) {
                $query->whereHas('booth', function($q) use ($section) {
                    $q->where('section', $section);
                });
            }
            if ($city) {
                $query->whereHas('user', function($q) use ($city) {
                    $q->where('address', 'LIKE', "%{$city}%");
                });
            }

            $hourlyStats = $query->groupBy(DB::raw('HOUR(bookings.created_at)'))
                ->orderBy('hour')
                ->get()
                ->map(function($item) {
                    return [
                        'hour' => sprintf('%02d:00', $item->hour),
                        'booking_count' => (int) $item->booking_count
                    ];
                });

            return response()->json($hourlyStats);
        } catch (\Exception $e) {
            \Log::error('Error in getHourlyStats: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load hourly statistics'], 500);
        }
    }

    /**
     * Get city/address booking statistics
     */
    public function getCityStats(Request $request)
    {
        try {
            $year = $request->get('year', date('Y'));
            $eventId = $request->get('event_id');
            $section = $request->get('section');

            $query = Booking::join('users', 'bookings.user_id', '=', 'users.id')
                ->select(
                    'users.address as city',
                    DB::raw('COUNT(*) as booking_count'),
                    DB::raw('COUNT(CASE WHEN bookings.status = "confirmed" THEN 1 END) as confirmed_bookings')
                )
                ->whereNotNull('users.address');

            // Filter berdasarkan start_date event
            if ($year) {
                $query->whereHas('booth.event', function($q) use ($year) {
                    $q->whereYear('start_date', $year);
                });
            }
            if ($eventId) {
                $query->whereHas('booth', function($q) use ($eventId) {
                    $q->where('event_id', $eventId);
                });
            }
            if ($section) {
                $query->join('booths', 'bookings.booth_id', '=', 'booths.id')
                      ->where('booths.section', $section);
            }

            $cityStats = $query->groupBy('users.address')
                ->orderBy('booking_count', 'desc')
                ->limit(15)
                ->get();

            return response()->json($cityStats);
        } catch (\Exception $e) {
            \Log::error('Error in getCityStats: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load city statistics'], 500);
        }
    }
}