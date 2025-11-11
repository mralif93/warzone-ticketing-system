<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * Display a listing of all galleries across all events
     */
    public function all(Request $request)
    {
        $query = Gallery::with('event');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('event', function($eventQuery) use ($search) {
                      $eventQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by event
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        // Filter by active status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active === '1');
        }

        $perPage = $request->get('limit', 20);
        $perPage = in_array($perPage, [10, 15, 20, 25, 50, 100]) ? $perPage : 20;
        $galleries = $query->ordered()->paginate($perPage);
        
        $events = Event::orderBy('name')->get();
        $totalGalleries = Gallery::count();
        $activeGalleries = Gallery::where('is_active', true)->count();
        $eventsWithGalleries = Event::has('galleries')->count();

        return view('admin.galleries.all', compact('galleries', 'events', 'totalGalleries', 'activeGalleries', 'eventsWithGalleries'));
    }

    /**
     * Display a listing of galleries for a specific event
     */
    public function index(Request $request, Event $event)
    {
        $query = $event->galleries(); // This automatically excludes trashed items

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by active status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active === '1');
        }

        $galleries = $query->ordered()->get();

        return view('admin.galleries.index', compact('event', 'galleries'));
    }

    /**
     * Show the form for creating a new gallery image
     */
    public function create(Event $event)
    {
        return view('admin.galleries.create', compact('event'));
    }

    /**
     * Store a newly created gallery image
     */
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // Max 10MB
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('galleries', 'public');
        } else {
            return back()->withErrors(['image' => 'Image is required.'])->withInput();
        }

        $gallery = Gallery::create([
            'event_id' => $event->id,
            'image_path' => $imagePath,
            'title' => $request->title,
            'description' => $request->description,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        // Log the gallery creation
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'CREATE',
            'table_name' => 'galleries',
            'record_id' => $gallery->id,
            'old_values' => null,
            'new_values' => $gallery->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'description' => "Gallery image added for event: {$event->name}"
        ]);

        return redirect()->route('admin.events.galleries.index', $event)
            ->with('success', 'Gallery image added successfully!');
    }

    /**
     * Display the specified gallery image
     */
    public function show(Event $event, Gallery $gallery)
    {
        return view('admin.galleries.show', compact('event', 'gallery'));
    }

    /**
     * Show the form for editing the specified gallery image
     */
    public function edit(Event $event, Gallery $gallery)
    {
        return view('admin.galleries.edit', compact('event', 'gallery'));
    }

    /**
     * Update the specified gallery image
     */
    public function update(Request $request, Event $event, Gallery $gallery)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // Max 10MB
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $oldValues = $gallery->toArray();

        // Handle image upload if new image is provided
        if ($request->hasFile('image')) {
            // Delete old image
            if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
                Storage::disk('public')->delete($gallery->image_path);
            }

            $image = $request->file('image');
            $imagePath = $image->store('galleries', 'public');
            $gallery->image_path = $imagePath;
        }

        $gallery->title = $request->title;
        $gallery->description = $request->description;
        $gallery->order = $request->order ?? $gallery->order;
        $gallery->is_active = $request->has('is_active') ? true : false;
        $gallery->save();

        // Log the gallery update
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE',
            'table_name' => 'galleries',
            'record_id' => $gallery->id,
            'old_values' => $oldValues,
            'new_values' => $gallery->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'description' => "Gallery image updated for event: {$event->name}"
        ]);

        return redirect()->route('admin.events.galleries.index', $event)
            ->with('success', 'Gallery image updated successfully!');
    }

    /**
     * Remove the specified gallery image (soft delete)
     */
    public function destroy(Event $event, Gallery $gallery)
    {
        $oldValues = $gallery->toArray();

        // Soft delete (don't delete the image file - keep it for potential restore)
        $gallery->delete();

        // Log the gallery deletion
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'DELETE',
            'table_name' => 'galleries',
            'record_id' => $gallery->id,
            'old_values' => $oldValues,
            'new_values' => null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'description' => "Gallery image deleted (soft) for event: {$event->name}"
        ]);

        return redirect()->route('admin.events.galleries.index', $event)
            ->with('success', 'Gallery image deleted successfully!');
    }

    /**
     * Display a listing of trashed gallery images
     */
    public function trashed(Request $request, Event $event)
    {
        $query = $event->galleries()->onlyTrashed();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $galleries = $query->ordered()->get();
        $totalTrashed = $event->galleries()->onlyTrashed()->count();
        $recentlyDeleted = $event->galleries()->onlyTrashed()->where('deleted_at', '>=', now()->subDays(7))->count();

        return view('admin.galleries.trashed', compact('event', 'galleries', 'totalTrashed', 'recentlyDeleted'));
    }

    /**
     * Restore a trashed gallery image
     */
    public function restore(Event $event, Gallery $gallery)
    {
        // Ensure the gallery is trashed
        if (!$gallery->trashed()) {
            return redirect()->route('admin.events.galleries.index', $event)
                ->with('error', 'This gallery image is not deleted.');
        }
        
        $gallery->restore();

        // Log the gallery restoration
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'RESTORE',
            'table_name' => 'galleries',
            'record_id' => $gallery->id,
            'old_values' => null,
            'new_values' => $gallery->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'description' => "Gallery image restored for event: {$event->name}"
        ]);

        return redirect()->route('admin.events.galleries.trashed', $event)
            ->with('success', 'Gallery image restored successfully!');
    }

    /**
     * Permanently delete a trashed gallery image
     */
    public function forceDelete(Event $event, Gallery $gallery)
    {
        // Ensure the gallery is trashed
        if (!$gallery->trashed()) {
            return redirect()->route('admin.events.galleries.index', $event)
                ->with('error', 'This gallery image is not deleted. Please delete it first.');
        }
        
        $oldValues = $gallery->toArray();

        // Delete image file permanently
        if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
            Storage::disk('public')->delete($gallery->image_path);
        }

        $gallery->forceDelete();

        // Log the permanent deletion
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'FORCE_DELETE',
            'table_name' => 'galleries',
            'record_id' => $gallery->id,
            'old_values' => $oldValues,
            'new_values' => null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'description' => "Gallery image permanently deleted for event: {$event->name}"
        ]);

        return redirect()->route('admin.events.galleries.trashed', $event)
            ->with('success', 'Gallery image permanently deleted!');
    }

    /**
     * Toggle active status of a gallery image
     */
    public function toggleStatus(Request $request, Event $event, Gallery $gallery)
    {
        $oldValues = $gallery->toArray();
        $gallery->is_active = !$gallery->is_active;
        $gallery->save();

        // Log the status change
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE',
            'table_name' => 'galleries',
            'record_id' => $gallery->id,
            'old_values' => $oldValues,
            'new_values' => $gallery->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'description' => "Gallery image status toggled for event: {$event->name}"
        ]);

        return back()->with('success', 'Gallery image status updated successfully.');
    }
}
