<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Access denied.');
        }
        
        // Get statistics
        $totalUsers = User::count();
        $totalEvents = Event::count();
        
        // Count total volunteers (unique users who joined events)
        $totalVolunteers = DB::table('event_user')
                             ->where('status', 'approved')
                             ->distinct('user_id')
                             ->count('user_id');
        
        $upcomingEvents = Event::where('event_date', '>=', now()->format('Y-m-d'))
                              ->count();
        
        // Get latest events with creator info
        $latestEvents = Event::with('creator')
                            ->latest()
                            ->take(5)
                            ->get();
        
        // Get latest users
        $latestUsers = User::latest()
                          ->take(5)
                          ->get();
        
        // Get event participation statistics for chart
        // FIXED: Use withCount on the relationship properly
        $eventStats = Event::withCount(['volunteers' => function($query) {
                            $query->where('event_user.status', 'approved');
                        }])
                        ->orderBy('event_date', 'desc')
                        ->take(6)
                        ->get();
        
        // Get monthly volunteer joins
        $monthlyJoins = DB::table('event_user')
                         ->where('status', 'approved')
                         ->select(DB::raw('MONTH(joined_at) as month'), DB::raw('COUNT(*) as count'))
                         ->whereYear('joined_at', date('Y'))
                         ->groupBy('month')
                         ->orderBy('month')
                         ->get();
        
        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalEvents', 
            'totalVolunteers', 
            'upcomingEvents',
            'latestEvents', 
            'latestUsers', 
            'eventStats', 
            'monthlyJoins'
        ));
    }
    
    /**
     * Manage all users (admin only)
     */
    public function manageUsers()
    {
        $users = User::withCount(['events' => function($query) {
                    $query->where('event_user.status', 'approved');
                }])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        
        return view('admin.users', compact('users'));
    }
    
    /**
     * Change user role
     */
    public function changeRole(User $user)
    {
        // Prevent changing own role
        if ($user->id === auth()->id()) {
            return redirect()->back()
                           ->with('error', 'You cannot change your own role.');
        }
        
        $newRole = $user->role === 'admin' ? 'user' : 'admin';
        $user->update(['role' => $newRole]);
        
        return redirect()->back()
                       ->with('success', "User role changed to {$newRole} successfully!");
    }
    
    /**
     * Delete user
     */
    public function deleteUser(User $user)
    {
        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return redirect()->back()
                           ->with('error', 'You cannot delete your own account.');
        }
        
        $user->delete();
        
        return redirect()->back()
                       ->with('success', 'User deleted successfully!');
    }
    
    /**
     * Show reports page
     */
    public function reports()
    {
        // Get total statistics
        $totalEvents = Event::count();
        $totalUsers = User::count();
        $totalParticipations = DB::table('event_user')
                                 ->where('status', 'approved')
                                 ->count();
        
        // Most active volunteers
        $topVolunteers = User::withCount(['events' => function($query) {
                            $query->where('event_user.status', 'approved');
                        }])
                        ->having('events_count', '>', 0)
                        ->orderBy('events_count', 'desc')
                        ->take(10)
                        ->get();
        
        // Events with most volunteers
        $popularEvents = Event::withCount(['volunteers' => function($query) {
                            $query->where('event_user.status', 'approved');
                        }])
                        ->orderBy('volunteers_count', 'desc')
                        ->take(10)
                        ->get();
        
        // Monthly statistics
        $monthlyStats = DB::table('event_user')
                         ->where('status', 'approved')
                         ->select(
                             DB::raw('YEAR(joined_at) as year'),
                             DB::raw('MONTH(joined_at) as month'),
                             DB::raw('COUNT(*) as total_joins')
                         )
                         ->groupBy('year', 'month')
                         ->orderBy('year', 'desc')
                         ->orderBy('month', 'desc')
                         ->take(12)
                         ->get();
        
        return view('admin.reports', compact(
            'totalEvents', 
            'totalUsers', 
            'totalParticipations',
            'topVolunteers', 
            'popularEvents', 
            'monthlyStats'
        ));
    }
}