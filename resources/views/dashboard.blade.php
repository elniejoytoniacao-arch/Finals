@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-5 fw-bold">Welcome back, {{ Auth::user()->name }}!</h1>
            <p class="lead">Here's your volunteering activity summary</p>
        </div>
    </div>
    
    <div class="row mb-5">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-check me-2"></i> My Joined Events</h5>
                </div>
                <div class="card-body">
                    @if($joinedEvents->count() > 0)
                        @foreach($joinedEvents as $event)
                            <div class="mb-3 pb-3 border-bottom">
                                <h6>{{ $event->title }}</h6>
                                <div class="small text-muted">
                                    <i class="fas fa-calendar me-1"></i> {{ $event->event_date->format('M d, Y') }} |
                                    <i class="fas fa-map-marker-alt ms-2 me-1"></i> {{ $event->location }}
                                </div>
                                <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-outline-primary mt-2">View Details</a>
                            </div>
                        @endforeach
                        <a href="{{ route('my.events') }}" class="btn btn-primary">View All My Events</a>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p>You haven't joined any events yet.</p>
                            <a href="{{ route('events.index') }}" class="btn btn-primary">Browse Events</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-plus me-2"></i> Upcoming Events</h5>
                </div>
                <div class="card-body">
                    @if($upcomingEvents->count() > 0)
                        @foreach($upcomingEvents as $event)
                            <div class="mb-3 pb-3 border-bottom">
                                <h6>{{ $event->title }}</h6>
                                <div class="small text-muted">
                                    <i class="fas fa-calendar me-1"></i> {{ $event->event_date->format('M d, Y') }} |
                                    <i class="fas fa-map-marker-alt ms-2 me-1"></i> {{ $event->location }}
                                </div>
                                <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-outline-success mt-2">Join Now</a>
                            </div>
                        @endforeach
                        <a href="{{ route('events.index') }}" class="btn btn-success">View All Events</a>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-day fa-3x text-muted mb-3"></i>
                            <p>No upcoming events at the moment.</p>
                            <a href="{{ route('events.index') }}" class="btn btn-success">Check Later</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Volunteer Tips Card -->
    <div class="row">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body">
                    <h5><i class="fas fa-lightbulb text-warning me-2"></i> Volunteer Tips</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="fas fa-clock fa-2x text-primary mb-2"></i>
                                <p class="mb-0">Arrive 15 minutes early</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="fas fa-tshirt fa-2x text-primary mb-2"></i>
                                <p class="mb-0">Wear comfortable clothes</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="fas fa-smile fa-2x text-primary mb-2"></i>
                                <p class="mb-0">Bring positive energy!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection