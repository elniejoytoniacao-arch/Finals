@extends('layouts.app')

@section('title', $event->title)

@section('content')
<div class="container py-5">
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
    
    <div class="row">
        <div class="col-lg-8">
            <!-- Event Image -->
            @if($event->image)
                <img src="{{ Storage::url($event->image) }}" class="img-fluid rounded shadow mb-4" alt="{{ $event->title }}">
            @else
                <img src="https://via.placeholder.com/800x400?text=Event+Image" class="img-fluid rounded shadow mb-4" alt="{{ $event->title }}">
            @endif
            
            <!-- Event Title -->
            <h1 class="display-5 fw-bold mb-3">{{ $event->title }}</h1>
            
            <!-- Event Details -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-calendar-alt fa-2x text-primary me-3"></i>
                        <div>
                            <strong>Date</strong><br>
                            {{ $event->event_date->format('l, F d, Y') }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-clock fa-2x text-primary me-3"></i>
                        <div>
                            <strong>Time</strong><br>
                            {{ date('h:i A', strtotime($event->event_time)) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-map-marker-alt fa-2x text-primary me-3"></i>
                        <div>
                            <strong>Location</strong><br>
                            {{ $event->location }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-users fa-2x text-primary me-3"></i>
                        <div>
                            <strong>Volunteers Needed</strong><br>
                            {{ $volunteerCount }} / {{ $event->max_volunteers }} registered
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Progress Bar -->
            @php
                $percentage = ($volunteerCount / $event->max_volunteers) * 100;
            @endphp
            <div class="mb-4">
                <div class="progress" style="height: 30px;">
                    <div class="progress-bar bg-success" style="width: {{ $percentage }}%;">
                        {{ round($percentage) }}% Filled
                    </div>
                </div>
            </div>
            
            <!-- Description -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Event Description</h5>
                </div>
                <div class="card-body">
                    <p class="lead">{{ nl2br(e($event->description)) }}</p>
                </div>
            </div>
            
            <!-- Organizer Info -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Organized By</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-circle fa-3x text-secondary me-3"></i>
                        <div>
                            <h5 class="mb-0">{{ $event->creator->name }}</h5>
                            <small class="text-muted">Event Organizer</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Join Card -->
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-body text-center">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-warning btn-lg">
                                    <i class="fas fa-edit"></i> Edit Event
                                </a>
                                <form action="{{ route('admin.events.destroy', $event) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-lg w-100" onclick="return confirm('Delete this event?')">
                                        <i class="fas fa-trash"></i> Delete Event
                                    </button>
                                </form>
                            </div>
                        @else
                            @if($hasJoined)
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle"></i> You have joined this event!
                                </div>
                                <form action="{{ route('events.cancel', $event) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-lg w-100" onclick="return confirm('Cancel your participation?')">
                                        <i class="fas fa-times"></i> Cancel Participation
                                    </button>
                                </form>
                            @else
                                @if($event->isFull())
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle"></i> This event is full!
                                    </div>
                                    <button class="btn btn-secondary btn-lg w-100" disabled>
                                        <i class="fas fa-ban"></i> Event Full
                                    </button>
                                @else
                                    <form action="{{ route('events.join', $event) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-lg w-100">
                                            <i class="fas fa-hands-helping"></i> Join This Event
                                        </button>
                                    </form>
                                @endif
                            @endif
                        @endif
                    @else
                        <div class="alert alert-info">
                            Please <a href="{{ route('login') }}">login</a> to join this event.
                        </div>
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100">Login to Join</a>
                    @endauth
                    
                    <hr class="my-4">
                    
                    <div class="text-start">
                        <h6>Event Highlights:</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Make a difference in your community
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Meet new people
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Gain valuable experience
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection