@extends('layouts.app')

@section('title', 'My Events')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-5 fw-bold">My Joined Events</h1>
            <p class="lead">Events you're participating in</p>
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
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
                            <span class="badge bg-success">Joined</span>
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
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-grid gap-2">
                            <a href="{{ route('events.show', $event) }}" class="btn btn-outline-primary">View Details</a>
                            <form action="{{ route('events.cancel', $event) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Cancel your participation?')">
                                    Cancel Participation
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center py-5">
                    <i class="fas fa-calendar-times fa-3x mb-3"></i>
                    <h4>No Events Joined</h4>
                    <p>You haven't joined any events yet.</p>
                    <a href="{{ route('events.index') }}" class="btn btn-primary mt-3">Browse Events</a>
                </div>
            </div>
        @endforelse
    </div>
    
    <div class="mt-4">
        {{ $events->links() }}
    </div>
</div>
@endsection