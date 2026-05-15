@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.events.create') }}">
                            <i class="fas fa-plus-circle"></i> Create Event
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.users') }}">
                            <i class="fas fa-users"></i> Manage Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.reports') }}">
                            <i class="fas fa-chart-bar"></i> Reports
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        
        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Admin Dashboard</h1>
            </div>
            
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50">Total Users</h6>
                                <h2 class="text-white mb-0">{{ $totalUsers }}</h2>
                            </div>
                            <i class="fas fa-users fa-3x text-white-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50">Total Events</h6>
                                <h2 class="text-white mb-0">{{ $totalEvents }}</h2>
                            </div>
                            <i class="fas fa-calendar-alt fa-3x text-white-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50">Total Volunteers</h6>
                                <h2 class="text-white mb-0">{{ $totalVolunteers }}</h2>
                            </div>
                            <i class="fas fa-hands-helping fa-3x text-white-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50">Upcoming Events</h6>
                                <h2 class="text-white mb-0">{{ $upcomingEvents }}</h2>
                            </div>
                            <i class="fas fa-clock fa-3x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Charts -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Event Participation Statistics</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="participationChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Monthly Volunteer Joins</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="monthlyChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Latest Events -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Latest Events</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Date</th>
                                    <th>Location</th>
                                    <th>Max Volunteers</th>
                                    <th>Created By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latestEvents as $event)
                                    <tr>
                                        <td>{{ $event->title }}</td>
                                        <td>{{ $event->event_date->format('M d, Y') }}</td>
                                        <td>{{ $event->location }}</td>
                                        <td>{{ $event->max_volunteers }}</td>
                                        <td>{{ $event->creator->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script>
    // Participation Chart
    const ctx1 = document.getElementById('participationChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: {!! json_encode($eventStats->pluck('title')) !!},
            datasets: [{
                label: 'Number of Volunteers',
                data: {!! json_encode($eventStats->pluck('volunteers_count')) !!},
                backgroundColor: 'rgba(102, 126, 234, 0.5)',
                borderColor: 'rgba(102, 126, 234, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
    // Monthly Chart
    const ctx2 = document.getElementById('monthlyChart').getContext('2d');
    new Chart(ctx2, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyJoins->pluck('month')->map(function($m) { return date('F', mktime(0, 0, 0, $m, 1)); })) !!},
            datasets: [{
                label: 'Volunteer Joins',
                data: {!! json_encode($monthlyJoins->pluck('count')) !!},
                fill: true,
                backgroundColor: 'rgba(102, 126, 234, 0.2)',
                borderColor: 'rgba(102, 126, 234, 1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true
        }
    });
</script>
@endpush
@endsection