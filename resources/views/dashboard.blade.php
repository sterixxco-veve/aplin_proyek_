@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<div class="container pb-5">
    {{-- WELCOME HEADER --}}
    <div class="mb-5">
        <h2 class="fw-bold text-dark mb-1">Dashboard</h2>
        <p class="text-muted">Welcome back! Here's what's happening with your events.</p>
    </div>

    {{-- TOP STATS CARDS --}}
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card p-4 border-0 shadow-sm h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="bg-primary-subtle text-primary rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                        <i class="bi bi-calendar-event fs-4"></i>
                    </div>
                    <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1 small">+12%</span>
                </div>
                <small class="text-muted fw-medium d-block mb-1">Total Events</small>
                <h3 class="fw-bold mb-0 text-dark">{{ count($events) }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4 border-0 shadow-sm h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="bg-danger-subtle text-danger rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                        <i class="bi bi-graph-up-arrow fs-4"></i>
                    </div>
                    <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1 small">+2</span>
                </div>
                <small class="text-muted fw-medium d-block mb-1">Upcoming Events</small>
                <h3 class="fw-bold mb-0 text-dark">8</h3>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-4 px-4 border-0 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark">Upcoming Events</h5>
                        <a href="/events" class="text-primary text-decoration-none small fw-bold">View all</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush px-2 pb-3">
                            @forelse($events as $event)
                                <div class="list-group-item border-0 rounded-4 mb-2 hover-bg-light transition p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary rounded-3 text-white p-2 me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                            <i class="bi bi-calendar-check fs-5"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-0 text-dark">{{ $event->nama_event }}</h6>
                                            <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($event->tgl_mulai)->format('M d, Y') }}</small>
                                        </div>
                                        <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2 small fw-bold text-uppercase" style="font-size: 10px;">{{ $event->status ?? 'Planning' }}</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-muted py-4">No upcoming events found.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
    </div>



    <div class="row g-4 mb-5">

    {{-- CALENDAR --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0">Event Calendar</h5>
            </div>

            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    {{-- QUICK ACTIONS --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100 p-4">
            <h5 class="fw-bold mb-4 text-dark">
                Quick Actions
            </h5>

            <div class="row g-3">

                <div class="col-12">
                    <a href="/events/create"
                        class="btn btn-light w-100 rounded-4 border-dashed py-4 text-start text-decoration-none text-dark">

                        <i class="bi bi-calendar-plus fs-4 me-2 text-primary"></i>
                        Create Event
                    </a>
                </div>

                <div class="col-12">
                    <a href="/tasks"
                        class="btn btn-light w-100 rounded-4 border-dashed py-4 text-start text-decoration-none text-dark">

                        <i class="bi bi-check2-circle fs-4 me-2 text-success"></i>
                        Add Task
                    </a>
                </div>

                <div class="col-12">
                    <a href="/partners"
                        class="btn btn-light w-100 rounded-4 border-dashed py-4 text-start text-decoration-none text-dark">

                        <i class="bi bi-people fs-4 me-2 text-warning"></i>
                        Add Partner
                    </a>
                </div>

                <div class="col-12">
                    <a href="/organizations"
                       class="btn btn-light w-100 rounded-4 border-dashed py-4 text-start text-decoration-none text-dark">

                        <i class="bi bi-building fs-4 me-2" style="color: #9333ea;"></i>
                        Organization
                    </a>
                </div>

            </div>
        </div>
    </div>

<!-- </div>
            {{-- QUICK ACTIONS --}}
            <div class="card border-0 shadow-sm p-4">
                <h5 class="fw-bold mb-4 text-dark">Quick Actions</h5>
                <div class="row g-3">
                    <div class="col-6">
                        <button class="btn btn-light w-100 rounded-4 border-dashed py-4 text-start p-3 h-100 transition-transform active-scale" data-bs-toggle="modal" data-bs-target="#eventModal">
                            <div class="bg-primary-subtle text-primary rounded-3 p-1 d-inline-flex mb-3"><i class="bi bi-calendar-plus fs-4"></i></div>
                            <h6 class="fw-bold text-dark small mb-0">Create Event</h6>
                        </button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-light w-100 rounded-4 border-dashed py-4 text-start p-3 h-100 transition-transform active-scale">
                            <div class="bg-success-subtle text-success rounded-3 p-1 d-inline-flex mb-3"><i class="bi bi-check2-circle fs-4"></i></div>
                            <h6 class="fw-bold text-dark small mb-0">Add Task</h6>
                        </button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-light w-100 rounded-4 border-dashed py-4 text-start p-3 h-100 transition-transform active-scale">
                            <div class="bg-warning-subtle text-warning rounded-3 p-1 d-inline-flex mb-3"><i class="bi bi-people fs-4"></i></div>
                            <h6 class="fw-bold text-dark small mb-0">Add Partner</h6>
                        </button>
                    </div>
                    <div class="col-6">
                        <a href="/organizations" class="btn btn-light w-100 rounded-4 border-dashed py-4 text-start p-3 h-100 transition-transform active-scale d-block text-decoration-none">
                            <div class="bg-purple-subtle text-purple rounded-3 p-1 d-inline-flex mb-3"><i class="bi bi-building fs-4"></i></div>
                            <h6 class="fw-bold text-dark small mb-0">Organization</h6>
                        </a>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</div>


<style>
    .bg-purple-subtle { background-color: #f3e8ff; }
    .text-purple { color: #9333ea; }
    
    .border-dashed {
        border: 2px dashed #e5e7eb !important;
        background-color: transparent !important;
    }
    .border-dashed:hover {
        background-color: #f9fafb !important;
        border-color: var(--gd-blue) !important;
    }

    .transition { transition: all 0.2s ease-in-out; }
    .transition-transform:active { transform: scale(0.96); }
    
    .hover-bg-light:hover {
        background-color: #f8fafc !important;
    }
    
    .hover-shadow:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        border-color: var(--gd-blue) !important;
    }

    .shadow-sm {
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
    }

    .form-control:focus {
        background-color: #ffffff !important;
    }
</style>


<script>
    document.addEventListener('DOMContentLoaded', function() {

    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',

        height: 650,

        events: [

            @foreach($events as $event)
            {
                title: @json($event->nama_event),
                start: '{{ $event->tgl_mulai }}',
            },
            @endforeach

        ]
    });

    calendar.render();
});
</script>
@endsection