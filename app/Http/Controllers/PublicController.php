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
        // Get featured events (on sale, upcoming)
        $featuredEvents = Event::where('status', 'On Sale')
            ->where('date_time', '>', now())
            ->orderBy('date_time')
            ->take(6)
            ->get();

        // Get upcoming events for the calendar
        $upcomingEvents = Event::where('status', 'On Sale')
            ->where('date_time', '>', now())
            ->orderBy('date_time')
            ->take(12)
            ->get();

        // Get event statistics
        $stats = [
            'total_events' => Event::where('status', 'On Sale')->count(),
            'upcoming_events' => Event::where('status', 'On Sale')->where('date_time', '>', now())->count(),
            'total_tickets_sold' => Event::withCount('tickets')->get()->sum('tickets_count'),
        ];

        return view('public.home', compact('featuredEvents', 'upcomingEvents', 'stats'));
    }

    /**
     * Show public events listing
     */
    public function events(Request $request)
    {
        $query = Event::query();

        // Only show events that are on sale or upcoming
        $query->whereIn('status', ['On Sale', 'Sold Out']);

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

        $events = $query->withCount('tickets')
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

        // Get availability stats
        $totalCapacity = 7000; // Assuming 7000 ticket capacity
        $ticketsSold = $event->tickets()->where('status', 'Sold')->count();
        $ticketsHeld = $event->tickets()->where('status', 'Held')->count();
        $ticketsAvailable = $totalCapacity - $ticketsSold - $ticketsHeld;

        $availabilityStats = [
            'total_capacity' => $totalCapacity,
            'tickets_sold' => $ticketsSold,
            'tickets_held' => $ticketsHeld,
            'tickets_available' => $ticketsAvailable,
            'sold_percentage' => $totalCapacity > 0 ? round(($ticketsSold / $totalCapacity) * 100, 2) : 0,
            'availability_percentage' => $totalCapacity > 0 ? round(($ticketsAvailable / $totalCapacity) * 100, 2) : 0,
        ];

        // Get zone pricing information based on Warzone categories
        // Calculate realistic availability based on total capacity and sold tickets
        $totalCapacity = 7000;
        $ticketsSold = $event->tickets()->where('status', 'Sold')->count();
        $ticketsHeld = $event->tickets()->where('status', 'Held')->count();
        $totalSold = $ticketsSold + $ticketsHeld;
        
        // Define zone capacity distribution (total should equal 7000)
        $zoneCapacities = [
            'Warzone Exclusive' => 200,      // 2.9% of total
            'Warzone VIP' => 128,            // 1.8% of total  
            'Warzone Grandstand' => 160,     // 2.3% of total
            'Warzone Premium Ringside' => 1816, // 25.9% of total
            'Level 1 Zone A/B/C/D' => 2046,  // 29.2% of total
            'Level 2 Zone A/B/C/D' => 1782,  // 25.5% of total
            'Standing Zone A/B' => 400,      // 5.7% of total
        ];
        
        // Calculate sold tickets per zone (distribute proportionally)
        $soldPerZone = [];
        foreach ($zoneCapacities as $zone => $capacity) {
            $soldInZone = $totalSold > 0 ? round(($capacity / $totalCapacity) * $totalSold) : 0;
            $soldPerZone[$zone] = min($soldInZone, $capacity); // Don't exceed capacity
        }
        
        // Build zones array with calculated availability
        $zones = [];
        foreach ($zoneCapacities as $zone => $capacity) {
            $sold = $soldPerZone[$zone];
            $available = max(0, $capacity - $sold);
            $availabilityPercentage = $capacity > 0 ? round(($available / $capacity) * 100, 1) : 0;
            
            $zones[$zone] = [
                'price' => $this->getZonePrice($zone),
                'capacity' => $capacity,
                'sold' => $sold,
                'available' => $available,
                'availability_percentage' => $availabilityPercentage
            ];
        }

        return view('public.event-show', compact('event', 'availabilityStats', 'zones'));
    }

    /**
     * Get zone price based on zone name
     */
    private function getZonePrice($zone)
    {
        $prices = [
            'Warzone Exclusive' => 350.00,
            'Warzone VIP' => 250.00,
            'Warzone Grandstand' => 220.00,
            'Warzone Premium Ringside' => 199.00,
            'Level 1 Zone A/B/C/D' => 129.00,
            'Level 2 Zone A/B/C/D' => 89.00,
            'Standing Zone A/B' => 49.00,
        ];
        
        return $prices[$zone] ?? 0;
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
