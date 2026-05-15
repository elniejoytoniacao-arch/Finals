@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
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
                        <a class="nav-link active" href="{{ route('admin.reports') }}">
                            <i class="fas fa-chart-bar"></i> Reports
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        
        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Volunteer Reports</h1>
            </div>
            
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
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
                <div class="col-md-4">
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
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50">Total Participations</h6>
                                <h2 class="text-white mb-0">{{ $totalParticipations }}</h2>
                            </div>
                            <i class="fas fa-handshake fa-3x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Top Volunteers -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>🏆 Top 10 Most Active Volunteers</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Events Participated</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topVolunteers as $index => $volunteer)
                                    <tr>
                                        <td>
                                            @if($index == 0) 🥇
                                            @elseif($index == 1) 🥈
                                            @elseif($index == 2) 🥉
                                            @else {{ $index + 1 }}
                                            @endif
                                        </td>
                                        <td>{{ $volunteer->name }}</td>
                                        <td>{{ $volunteer->email }}</td>
                                        <td><span class="badge bg-success">{{ $volunteer->events_count }}</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No volunteers found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Popular Events -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>⭐ Most Popular Events</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Event Title</th>
                                    <th>Date</th>
                                    <th>Location</th>
                                    <th>Volunteers</th>
                                    <th>Capacity</th>
                                    <th>Fill Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($popularEvents as $event)
                                    @php
                                        $fillRate = ($event->volunteers_count / $event->max_volunteers) * 100;
                                    @endphp
                                    <tr>
                                        <td>{{ $event->title }}</td>
                                        <td>{{ $event->event_date->format('M d, Y') }}</td>
                                        <td>{{ $event->location }}</td>
                                        <td>{{ $event->volunteers_count }}</td>
                                        <td>{{ $event->max_volunteers }}</td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar bg-success" style="width: {{ $fillRate }}%">
                                                    {{ round($fillRate) }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No events found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Monthly Statistics -->
            <div class="card">
                <div class="card-header">
                    <h5>📊 Monthly Participation Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Year</th>
                                    <th>Month</th>
                                    <th>Total Joins</th>
                                    <th>Daily Average</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($monthlyStats as $stat)
                                    @php
                                        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $stat->month, $stat->year);
                                        $dailyAvg = round($stat->total_joins / $daysInMonth, 1);
                                    @endphp
                                    <tr>
                                        <td>{{ $stat->year }}</td>
                                        <td>{{ date('F', mktime(0, 0, 0, $stat->month, 1)) }}</td>
                                        <td><span class="badge bg-primary">{{ $stat->total_joins }}</span></td>
                                        <td>{{ $dailyAvg }} joins/day</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No data available.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection