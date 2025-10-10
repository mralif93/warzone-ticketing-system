<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seat;
use App\Models\PriceZone;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeatController extends Controller
{
    /**
     * Display a listing of seats
     */
    public function index(Request $request)
    {
        $query = Seat::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('seat_identifier', 'like', "%{$search}%")
                  ->orWhere('price_zone', 'like', "%{$search}%")
                  ->orWhere('seat_type', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by price zone
        if ($request->filled('price_zone')) {
            $query->where('price_zone', $request->price_zone);
        }

        // Filter by seat type
        if ($request->filled('seat_type')) {
            $query->where('seat_type', $request->seat_type);
        }

        $seats = $query->latest()->paginate(15);
        $statuses = Seat::select('status')->distinct()->pluck('status');
        $priceZones = PriceZone::active()->ordered()->pluck('name');
        $seatTypes = Seat::select('seat_type')->distinct()->pluck('seat_type');

        return view('admin.seats.index', compact('seats', 'statuses', 'priceZones', 'seatTypes'));
    }

    /**
     * Show the form for creating a new seat
     */
    public function create()
    {
        $priceZones = PriceZone::active()->ordered()->get();
        return view('admin.seats.create', compact('priceZones'));
    }

    /**
     * Store a newly created seat
     */
    public function store(Request $request)
    {
        $request->validate([
            'seat_identifier' => 'required|string|max:255|unique:seats,seat_identifier',
            'price_zone' => 'required|string|max:255',
            'seat_type' => 'required|string|max:100',
            'base_price' => 'required|numeric|min:0',
            'status' => 'required|in:Available,Occupied,Maintenance',
        ]);

        $seat = Seat::create($request->all());

        // Log the seat creation
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'CREATE',
            'table_name' => 'seats',
            'record_id' => $seat->id,
            'old_values' => null,
            'new_values' => $seat->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.seats.show', $seat)
            ->with('success', 'Seat created successfully!');
    }

    /**
     * Display the specified seat
     */
    public function show(Seat $seat)
    {
        $seat->load(['tickets.order.user', 'tickets.event']);
        return view('admin.seats.show', compact('seat'));
    }

    /**
     * Show the form for editing the specified seat
     */
    public function edit(Seat $seat)
    {
        $priceZones = PriceZone::active()->ordered()->get();
        return view('admin.seats.edit', compact('seat', 'priceZones'));
    }

    /**
     * Update the specified seat
     */
    public function update(Request $request, Seat $seat)
    {
        $request->validate([
            'seat_identifier' => 'required|string|max:255|unique:seats,seat_identifier,' . $seat->id,
            'price_zone' => 'required|string|max:255',
            'seat_type' => 'required|string|max:100',
            'base_price' => 'required|numeric|min:0',
            'status' => 'required|in:Available,Occupied,Maintenance',
        ]);

        $oldValues = $seat->toArray();
        $seat->update($request->all());

        // Log the seat update
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE',
            'table_name' => 'seats',
            'record_id' => $seat->id,
            'old_values' => $oldValues,
            'new_values' => $seat->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.seats.show', $seat)
            ->with('success', 'Seat updated successfully!');
    }

    /**
     * Remove the specified seat
     */
    public function destroy(Seat $seat)
    {
        // Check if seat has tickets
        if ($seat->tickets()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete seat with existing tickets.']);
        }

        $oldValues = $seat->toArray();
        $seatIdentifier = $seat->seat_identifier;

        $seat->delete();

        // Log the seat deletion
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'DELETE',
            'table_name' => 'seats',
            'record_id' => $seat->id,
            'old_values' => $oldValues,
            'new_values' => null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('admin.seats.index')
            ->with('success', "Seat '{$seatIdentifier}' deleted successfully!");
    }

    /**
     * Bulk create seats
     */
    public function bulkCreate(Request $request)
    {
        $request->validate([
            'price_zone' => 'required|string|max:255',
            'seat_type' => 'required|string|max:100',
            'base_price' => 'required|numeric|min:0',
            'section' => 'required|string|max:50',
            'row_start' => 'required|integer|min:1',
            'row_end' => 'required|integer|min:1|gte:row_start',
            'seats_per_row' => 'required|integer|min:1|max:50',
        ]);

        $createdSeats = [];
        $seatNumber = 1;

        for ($row = $request->row_start; $row <= $request->row_end; $row++) {
            for ($seat = 1; $seat <= $request->seats_per_row; $seat++) {
                $seatIdentifier = $request->section . '-' . $row . '-' . $seatNumber;
                
                $newSeat = Seat::create([
                    'seat_identifier' => $seatIdentifier,
                    'price_zone' => $request->price_zone,
                    'seat_type' => $request->seat_type,
                    'base_price' => $request->base_price,
                    'status' => 'Available',
                ]);

                $createdSeats[] = $newSeat;
                $seatNumber++;
            }
        }

        // Log the bulk seat creation
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'CREATE',
            'table_name' => 'seats',
            'record_id' => null,
            'old_values' => null,
            'new_values' => ['bulk_created' => count($createdSeats), 'price_zone' => $request->price_zone],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.seats.index')
            ->with('success', "Created " . count($createdSeats) . " seats successfully!");
    }

    /**
     * Update seat status
     */
    public function updateStatus(Request $request, Seat $seat)
    {
        $request->validate([
            'status' => 'required|in:Available,Occupied,Maintenance'
        ]);

        $oldValues = $seat->toArray();
        $seat->update(['status' => $request->status]);

        // Log the status update
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE',
            'table_name' => 'seats',
            'record_id' => $seat->id,
            'old_values' => $oldValues,
            'new_values' => $seat->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Seat status updated successfully!');
    }
}
