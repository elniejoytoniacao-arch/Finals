@extends('layouts.app')

@section('title', 'Edit Event')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-warning">
                    <h4 class="mb-0">Edit Event: {{ $event->title }}</h4>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Event Title *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $event->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="5" required>{{ old('description', $event->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="event_date" class="form-label">Event Date *</label>
                                <input type="date" class="form-control @error('event_date') is-invalid @enderror" 
                                       id="event_date" name="event_date" value="{{ old('event_date', $event->event_date->format('Y-m-d')) }}" required>
                                @error('event_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="event_time" class="form-label">Event Time *</label>
                                <input type="time" class="form-control @error('event_time') is-invalid @enderror" 
                                       id="event_time" name="event_time" value="{{ old('event_time', date('H:i', strtotime($event->event_time))) }}" required>
                                @error('event_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="location" class="form-label">Location *</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                   id="location" name="location" value="{{ old('location', $event->location) }}" required>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="max_volunteers" class="form-label">Maximum Volunteers *</label>
                            <input type="number" class="form-control @error('max_volunteers') is-invalid @enderror" 
                                   id="max_volunteers" name="max_volunteers" value="{{ old('max_volunteers', $event->max_volunteers) }}" min="1" required>
                            @error('max_volunteers')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        @if($event->image)
                            <div class="mb-3">
                                <label class="form-label">Current Image</label>
                                <div>
                                    <img src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}" style="max-height: 150px;" class="img-thumbnail">
                                </div>
                            </div>
                        @endif
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">New Image (Optional)</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            <small class="text-muted">Upload a new image to replace the current one (max 2MB)</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning btn-lg">
                                <i class="fas fa-save"></i> Update Event
                            </button>
                            <a href="{{ route('events.show', $event) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection