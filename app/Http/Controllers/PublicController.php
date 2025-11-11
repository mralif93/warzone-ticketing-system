<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Show the public landing page
     */
    public function home()
    {
        // Get the default event or the first upcoming event
        $mainEvent = Event::where('default', true)
            ->orWhere(function($query) {
                $query->where('status', 'on_sale')
                      ->where('date_time', '>', now());
            })
            ->with(['tickets' => function($query) {
                $query->whereIn('status', ['Active', 'active']);
            }, 'activeGalleries'])
            ->orderBy('default', 'desc')
            ->orderBy('date_time')
            ->first();

        // Featured events removed - focusing on main event tickets only
        
        // Calculate Day 1 and Day 2 totals for multi-day events
        $day1TotalAvailable = 0;
        $day2TotalAvailable = 0;
        $day1TotalSold = 0;
        $day2TotalSold = 0;
        
        if ($mainEvent && $mainEvent->isMultiDay()) {
            $eventDays = $mainEvent->getEventDays();
            $day1Name = $eventDays[0]['day_name'] ?? 'Day 1';
            $day2Name = $eventDays[1]['day_name'] ?? 'Day 2';
            
            foreach ($mainEvent->tickets as $ticket) {
                // Count sold tickets per day
                $day1Sold = \App\Models\PurchaseTicket::where('ticket_type_id', $ticket->id)
                    ->where('event_day_name', $day1Name)
                    ->whereIn('status', ['sold', 'active', 'scanned'])
                    ->count();
                    
                $day2Sold = \App\Models\PurchaseTicket::where('ticket_type_id', $ticket->id)
                    ->where('event_day_name', $day2Name)
                    ->whereIn('status', ['sold', 'active', 'scanned'])
                    ->count();
                
                // Calculate available per day
                $day1Available = $ticket->total_seats - $day1Sold;
                $day2Available = $ticket->total_seats - $day2Sold;
                
                $day1TotalAvailable += max(0, $day1Available);
                $day2TotalAvailable += max(0, $day2Available);
                $day1TotalSold += $day1Sold;
                $day2TotalSold += $day2Sold;
            }
        }

        return view('public.home', compact('mainEvent', 'day1TotalAvailable', 'day2TotalAvailable', 'day1TotalSold', 'day2TotalSold'));
    }

    /**
     * Show public events listing
     */
public function events(Request $request)
    {
        $query = Event::query();

        // Only show events that are on sale or upcoming
        $query->whereIn('status', ['on_sale', 'sold_out']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('venue', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('date_time', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date_time', '<=', $request->date_to . ' 23:59:59');
        }

        // Filter by venue
        if ($request->filled('venue')) {
            $query->where('venue', 'like', "%{$request->venue}%");
        }

        $events = $query->with(['tickets' => function($query) {
                $query->whereIn('status', ['Active', 'active']);
            }])
            ->withCount('purchaseTickets')
            ->orderBy('date_time')
            ->paginate(12);

        $venues = Event::select('venue')
            ->whereNotNull('venue')
            ->distinct()
            ->pluck('venue')
            ->filter()
            ->sort();

        return view('public.events', compact('events', 'venues'));
    }

    /**
     * Show public event details
     */
    public function showEvent(Event $event)
    {
        // Load relationships
        $event->loadCount('tickets');
        $event->load(['tickets' => function($query) {
            $query->whereIn('status', ['Active', 'active']);
        }]);

        // Get availability statistics from tickets
        $totalCapacity = $event->tickets->sum('total_seats');
        $totalSold = $event->tickets->sum('sold_seats');
        $totalAvailable = $event->tickets->sum('available_seats');
        $availabilityPercentage = $totalCapacity > 0 ? round(($totalAvailable / $totalCapacity) * 100, 1) : 0;

        $availabilityStats = [
            'total_capacity' => $totalCapacity,
            'tickets_sold' => $totalSold,
            'tickets_available' => $totalAvailable,
            'sold_percentage' => $totalCapacity > 0 ? round(($totalSold / $totalCapacity) * 100, 2) : 0,
            'availability_percentage' => $availabilityPercentage,
        ];
        
        // Build tickets array from database
        $ticketTypes = [];
        foreach ($event->tickets as $ticket) {
            $availabilityPercentage = $ticket->total_seats > 0 ? round(($ticket->available_seats / $ticket->total_seats) * 100, 1) : 0;
            
            $ticketTypes[$ticket->name] = [
                'price' => $ticket->price,
                'capacity' => $ticket->total_seats,
                'sold' => $ticket->sold_seats,
                'available' => $ticket->available_seats,
                'availability_percentage' => $availabilityPercentage,
                'description' => $ticket->description
            ];
        }

        return view('public.event-show', compact('event', 'availabilityStats', 'ticketTypes'));
    }


    /**
     * Show the About Us page
     */
    public function about()
    {
        return view('public.about');
    }

    /**
     * Show the Contact Us page
     */
    public function contact()
    {
        return view('public.contact');
    }

    /**
     * Handle contact form submission
     */
    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        // Here you would typically send an email or store the message
        // For now, we'll just redirect back with a success message
        
        return redirect()->route('public.contact')
            ->with('success', 'Thank you for your message! We will get back to you soon.');
    }

    /**
     * Show the FAQ page
     */
    public function faq()
    {
        return view('public.faq');
    }

    /**
     * Show the Refund Policy page
     */
    public function refundPolicy()
    {
        return view('public.refund-policy');
    }

    /**
     * Show the Terms of Service page
     */
    public function termsOfService()
    {
        return view('public.terms-of-service');
    }
}
