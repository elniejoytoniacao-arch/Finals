<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VolunteerController extends Controller
{
    /**
     * Join an event as volunteer
     */
    public function join(Event $event)
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login')
                           ->with('error', 'Please login to join events.');
        }
        
        $user = Auth::user();
        
        // Check if user has already joined
        if ($event->hasUserJoined($user->id)) {
            return redirect()->back()
                           ->with('error', 'You have already joined this event!');
        }
        
        // Check if event is full
        if ($event->isFull()) {
            return redirect()->back()
                           ->with('error', 'Sorry, this event is already full!');
        }
        
        // Join the event
        $event->volunteers()->attach($user->id, [
            'joined_at' => now(),
            'status' => 'approved'
        ]);
        
        return redirect()->back()
                       ->with('success', 'Successfully joined the event!');
    }
    
    /**
     * Cancel volunteering for an event
     */
    public function cancel(Event $event)
    {
        $user = Auth::user();
        
        // Check if user has joined
        if (!$event->hasUserJoined($user->id)) {
            return redirect()->back()
                           ->with('error', 'You have not joined this event.');
        }
        
        // Cancel the join
        $event->volunteers()->detach($user->id);
        
        return redirect()->back()
                       ->with('success', 'Successfully cancelled your participation.');
    }
    
    /**
     * Display user's joined events
     */
    public function myEvents()
    {
        $user = Auth::user();
        $events = $user->events()
                      ->wherePivot('status', 'approved')
                      ->orderBy('event_date', 'asc')
                      ->paginate(10);
        
        return view('volunteer.my-events', compact('events'));
    }
}