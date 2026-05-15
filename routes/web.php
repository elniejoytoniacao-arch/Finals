<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', function () {
    $upcomingEvents = App\Models\Event::with('creator')
                       ->where('event_date', '>=', now()->format('Y-m-d'))
                       ->orderBy('event_date', 'asc')
                       ->take(3)
                       ->get();
    return view('welcome', compact('upcomingEvents'));
})->name('home');

// Event routes (public viewing)
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        
        $joinedEvents = $user->events()
                            ->wherePivot('status', 'approved')
                            ->orderBy('event_date', 'asc')
                            ->take(5)
                            ->get();
        
        $upcomingEvents = App\Models\Event::where('event_date', '>=', now()->format('Y-m-d'))
                            ->orderBy('event_date', 'asc')
                            ->take(5)
                            ->get();
        
        return view('dashboard', compact('joinedEvents', 'upcomingEvents'));
    })->name('dashboard');
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Volunteer Routes
    Route::post('/events/{event}/join', [VolunteerController::class, 'join'])->name('events.join');
    Route::delete('/events/{event}/cancel', [VolunteerController::class, 'cancel'])->name('events.cancel');
    Route::get('/my-events', [VolunteerController::class, 'myEvents'])->name('my.events');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Event Management
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    
    // User Management
    Route::get('/users', [AdminController::class, 'manageUsers'])->name('users');
    Route::patch('/users/{user}/role', [AdminController::class, 'changeRole'])->name('users.change-role');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
    
    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
});

require __DIR__.'/auth.php';