<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PriceZone;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PriceZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PriceZone::query();

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $priceZones = $query->ordered()->paginate(20);
        $categories = PriceZone::select('category')->distinct()->pluck('category');

        return view('admin.price-zones.index', compact('priceZones', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ['Premium', 'Level', 'Standing'];
        return view('admin.price-zones.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:price_zones,name',
            'code' => 'required|string|max:50|unique:price_zones,code|regex:/^[A-Z0-9_]+$/',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'category' => 'required|string|in:Premium,Level,Standing',
            'color' => 'required|string|max:20',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $priceZone = PriceZone::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'base_price' => $request->base_price,
            'category' => $request->category,
            'color' => $request->color,
            'icon' => $request->icon,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
            'metadata' => $request->metadata ? json_decode($request->metadata, true) : null,
        ]);

        return redirect()->route('admin.price-zones.index')
            ->with('success', 'Price zone created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PriceZone $priceZone)
    {
        $priceZone->load(['seats', 'tickets.event']);
        return view('admin.price-zones.show', compact('priceZone'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PriceZone $priceZone)
    {
        $categories = ['Premium', 'Level', 'Standing'];
        return view('admin.price-zones.edit', compact('priceZone', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PriceZone $priceZone)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:price_zones,name,' . $priceZone->id,
            'code' => 'required|string|max:50|unique:price_zones,code,' . $priceZone->id . '|regex:/^[A-Z0-9_]+$/',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'category' => 'required|string|in:Premium,Level,Standing',
            'color' => 'required|string|max:20',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $priceZone->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'base_price' => $request->base_price,
            'category' => $request->category,
            'color' => $request->color,
            'icon' => $request->icon,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
            'metadata' => $request->metadata ? json_decode($request->metadata, true) : null,
        ]);

        return redirect()->route('admin.price-zones.index')
            ->with('success', 'Price zone updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PriceZone $priceZone)
    {
        // Check if price zone has seats
        if ($priceZone->hasSeats()) {
            return redirect()->route('admin.price-zones.index')
                ->with('error', 'Cannot delete price zone that has seats assigned. Please reassign seats first.');
        }

        $priceZone->delete();

        return redirect()->route('admin.price-zones.index')
            ->with('success', 'Price zone deleted successfully.');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(PriceZone $priceZone)
    {
        $priceZone->update(['is_active' => !$priceZone->is_active]);

        $status = $priceZone->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.price-zones.index')
            ->with('success', "Price zone {$status} successfully.");
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'price_zones' => 'required|array|min:1',
            'price_zones.*' => 'exists:price_zones,id',
        ]);

        $priceZones = PriceZone::whereIn('id', $request->price_zones);

        switch ($request->action) {
            case 'activate':
                $priceZones->update(['is_active' => true]);
                $message = 'Selected price zones activated successfully.';
                break;
            case 'deactivate':
                $priceZones->update(['is_active' => false]);
                $message = 'Selected price zones deactivated successfully.';
                break;
            case 'delete':
                // Check if any price zone has seats
                $hasSeats = $priceZones->whereHas('seats')->exists();
                if ($hasSeats) {
                    return redirect()->route('admin.price-zones.index')
                        ->with('error', 'Cannot delete price zones that have seats assigned.');
                }
                $priceZones->delete();
                $message = 'Selected price zones deleted successfully.';
                break;
        }

        return redirect()->route('admin.price-zones.index')
            ->with('success', $message);
    }
}
