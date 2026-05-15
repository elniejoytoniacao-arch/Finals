<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of events.
     */
    public function index()
    {
        // Get all events with creator and volunteers count, paginated
        $events = Event::with('creator')
                       ->withCount('volunteers')
                       ->orderBy('event_date', 'asc')
                       ->paginate(9);
        
        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new event (Admin only).
     */
    public function create()
    {
        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('events.index')
                           ->with('error', 'Only admin can create events.');
        }
        
        return view('events.create');
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date|after_or_equal:today',
            'event_time' => 'required',
            'location' => 'required|string|max:255',
            'max_volunteers' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('events', 'public');
            $validated['image'] = $imagePath;
        }
        
        // Add created_by
        $validated['created_by'] = Auth::id();
        
        // Create event
        Event::create($validated);
        
        return redirect()->route('events.index')
                       ->with('success', 'Event created successfully!');
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        // Load relationships
        $event->load(['creator', 'volunteers' => function($query) {
            $query->wherePivot('status', 'approved');
        }]);
        
        $volunteerCount = $event->volunteers()->wherePivot('status', 'approved')->count();
        $hasJoined = false;
        
        if (Auth::check()) {
            $hasJoined = $event->hasUserJoined(Auth::id());
        }
        
        return view('events.show', compact('event', 'volunteerCount', 'hasJoined'));
    }

    /**
     * Show the form for editing the specified event (Admin only).
     */
    public function edit(Event $event)
    {
        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('events.index')
                           ->with('error', 'Only admin can edit events.');
        }
        
        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, Event $event)
    {
        // Validate the request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date|after_or_equal:today',
            'event_time' => 'required',
            'location' => 'required|string|max:255',
            'max_volunteers' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $imagePath = $request->file('image')->store('events', 'public');
            $validated['image'] = $imagePath;
        }
        
        // Update event
        $event->update($validated);
        
        return redirect()->route('events.index')
                       ->with('success', 'Event updated successfully!');
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Event $event)
    {
        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('events.index')
                           ->with('error', 'Only admin can delete events.');
        }
        
        // Delete event image if exists
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }
        
        $event->delete();
        
        return redirect()->route('events.index')
                       ->with('success', 'Event deleted successfully!');
    }
}