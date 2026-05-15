@extends('layouts.app')

@section('title', 'All Events')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="fw-bold">Volunteer Events</h1>
            <p class="lead text-muted">Find and join volunteering opportunities near you</p>
        </div>
        @auth
            @if(auth()->user()->isAdmin())
                <div class="col-md-4 text-end">
                    <a href="{{ route('admin.events.create') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus me-2"></i>Create Event
                    </a>
                </div>
            @endif
        @endauth
    </div>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <div class="row g-4">
        @forelse($events as $event)
            <div class="col-md-6 col-lg-4">
                <div class="card event-card card-hover h-100">
                    @if($event->image)
                        <img src="{{ Storage::url($event->image) }}" class="card-img-top" alt="{{ $event->title }}">
                    @else
                        <img src="https://via.placeholder.com/400x200?text=Event+Image" class="card-img-top" alt="{{ $event->title }}">
                    @endif
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title">{{ $event->title }}</h5>
                            <span class="badge bg-primary">{{ $event->volunteers_count }}/{{ $event->max_volunteers }} volunteers</span>
                        </div>
                        <p class="card-text text-muted">{{ Str::limit($event->description, 100) }}</p>
                        <div class="mb-2">
                            <i class="fas fa-calendar me-2 text-primary"></i>{{ $event->event_date->format('F d, Y') }}
                        </div>
                        <div class="mb-2">
                            <i class="fas fa-clock me-2 text-primary"></i>{{ date('h:i A', strtotime($event->event_time)) }}
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-map-marker-alt me-2 text-primary"></i>{{ $event->location }}
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            @php
                                $percentage = ($event->volunteers_count / $event->max_volunteers) * 100;
                            @endphp
                            <div class="progress-bar bg-success" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="{{ route('events.show', $event) }}" class="btn btn-outline-primary w-100">View Details</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center py-5">
                    <i class="fas fa-calendar-times fa-3x mb-3"></i>
                    <h4>No Events Found</h4>
                    <p>There are no events available at the moment. Please check back later.</p>
                </div>
            </div>
        @endforelse
    </div>
    
    <div class="mt-4">
        {{ $events->links() }}
    </div>
</div>
@endsection