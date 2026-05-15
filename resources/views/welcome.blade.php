@extends('layouts.app')

@section('title', 'Welcome to VolunteerHub')

@section('content')
<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h1 class="display-4 fw-bold mb-4">Make a Difference Today</h1>
                <p class="lead mb-4">Join our community of volunteers and help create positive change in the world. Find events that match your interests and skills.</p>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg px-4">Get Started</a>
                @else
                    <a href="{{ route('events.index') }}" class="btn btn-light btn-lg px-4">Browse Events</a>
                @endguest
            </div>
            <div class="col-lg-6">
                <img src="https://via.placeholder.com/600x400?text=Volunteer+Illustration" alt="Volunteer" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Why Volunteer With Us?</h2>
            <p class="lead text-muted">We make volunteering easy and rewarding</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 text-center p-4 card-hover">
                    <div class="mb-3">
                        <i class="fas fa-calendar-alt fa-3x text-primary"></i>
                    </div>
                    <h4>Find Events</h4>
                    <p class="text-muted">Discover hundreds of volunteering opportunities near you</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center p-4 card-hover">
                    <div class="mb-3">
                        <i class="fas fa-clock fa-3x text-primary"></i>
                    </div>
                    <h4>Track Hours</h4>
                    <p class="text-muted">Keep track of your volunteering hours and impact</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center p-4 card-hover">
                    <div class="mb-3">
                        <i class="fas fa-users fa-3x text-primary"></i>
                    </div>
                    <h4>Build Community</h4>
                    <p class="text-muted">Connect with like-minded volunteers and organizers</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upcoming Events Section -->
<div class="bg-light py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Upcoming Events</h2>
            <p class="lead text-muted">Join these upcoming volunteering opportunities</p>
        </div>
        <div class="row g-4">
            @forelse($upcomingEvents as $event)
                <div class="col-md-4">
                    <div class="card event-card card-hover h-100">
                        @if($event->image)
                            <img src="{{ Storage::url($event->image) }}" class="card-img-top" alt="{{ $event->title }}">
                        @else
                            <img src="https://via.placeholder.com/400x200?text=Event+Image" class="card-img-top" alt="{{ $event->title }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $event->title }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($event->description, 100) }}</p>
                            <div class="mb-2">
                                <i class="fas fa-calendar me-2 text-primary"></i>{{ $event->event_date->format('M d, Y') }}
                            </div>
                            <div class="mb-3">
                                <i class="fas fa-map-marker-alt me-2 text-primary"></i>{{ $event->location }}
                            </div>
                            <a href="{{ route('events.show', $event) }}" class="btn btn-primary w-100">Learn More</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">No upcoming events at the moment. Check back soon!</div>
                </div>
            @endforelse
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('events.index') }}" class="btn btn-outline-primary btn-lg">View All Events</a>
        </div>
    </div>
</div>
@endsection