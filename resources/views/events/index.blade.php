@extends('layouts.app')

@section('content')
<div class="container pb-5">
    {{-- HEADER SECTION --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Event Management</h2>
            <p class="text-muted small">Manage all your events in one place</p>
        </div>
        <a href="/events/create" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm fw-bold">
            <i class="bi bi-plus-lg me-2"></i>Create Event
        </a>
    </div>

    {{-- FILTER PILLS --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-3 d-flex align-items-center gap-2 overflow-x-auto">
            <div class="bg-light p-2 rounded-3 me-2">
                <i class="bi bi-filter text-muted"></i>
            </div>
            <a href="#" class="btn btn-primary rounded-pill px-3 py-1 small fw-bold">All Events</a>
            <a href="#" class="btn btn-light rounded-pill px-3 py-1 small fw-bold text-muted border">Planning</a>
            <a href="#" class="btn btn-light rounded-pill px-3 py-1 small fw-bold text-muted border">Ongoing</a>
            <a href="#" class="btn btn-light rounded-pill px-3 py-1 small fw-bold text-muted border">Done</a>
        </div>
    </div>

    {{-- EVENTS GRID --}}
    <div class="row g-4">
        @foreach($events as $event)
            <div class="col-md-6 col-lg-4">
                <a href="/events/{{ $event->id_event }}/details" class="text-decoration-none text-dark">
                    <div class="card h-100 border-0 shadow-sm event-card p-2" style="border-radius: 24px; transition: 0.3s ease;">
                        <div class="card-body p-3">
                            {{-- Top Header: Icon & Status --}}
                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <div class="bg-primary rounded-3 text-white p-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px;">
                                    <i class="bi bi-calendar-event fs-4"></i>
                                </div>
                                @php
                                    $status = $event->status ?? 'Planning';
                                    $badgeClass = match($status) {
                                        'Ongoing' => 'bg-primary-subtle text-primary',
                                        'Done' => 'bg-success-subtle text-success',
                                        default => 'bg-warning-subtle text-warning',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} rounded-pill px-3 py-2 small fw-bold" style="font-size: 10px;">{{ strtoupper($status) }}</span>
                            </div>

                            {{-- Event Info --}}
                            <h5 class="fw-bold mb-3 text-dark">{{ $event->nama_event }}</h5>
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-2 text-muted small">
                                    <i class="bi bi-calendar3 me-2 text-muted"></i>
                                    <span>{{ \Carbon\Carbon::parse($event->tgl_mulai)->format('M d, Y') }}</span>
                                </div>
                                <!-- <div class="d-flex align-items-center mb-2 text-muted small">
                                    <i class="bi bi-geo-alt me-2 text-muted"></i>
                                    <span>{{ $event->location ?? 'Jakarta Convention Center' }}</span>
                                </div> -->
                                <!-- <div class="d-flex align-items-center text-muted small">
                                    <i class="bi bi-people me-2 text-muted"></i>
                                    <span>{{ $event->attendees_count ?? '250' }} attendees</span>
                                </div> -->
                            </div>

                            {{-- Member Avatars --}}
                            <div class="d-flex align-items-center pt-3 border-top mt-auto">
                                <div class="avatar-group d-flex">
                                    <div class="avatar bg-primary text-white rounded-circle border border-white" style="width: 30px; height: 30px; font-size: 10px; display: flex; align-items: center; justify-content: center; margin-right: -10px;">A</div>
                                    <div class="avatar bg-success text-white rounded-circle border border-white" style="width: 30px; height: 30px; font-size: 10px; display: flex; align-items: center; justify-content: center; margin-right: -10px;">B</div>
                                    <div class="avatar bg-warning text-white rounded-circle border border-white" style="width: 30px; height: 30px; font-size: 10px; display: flex; align-items: center; justify-content: center; margin-right: -10px;">C</div>
                                    <div class="avatar bg-light text-muted rounded-circle border border-white fw-bold" style="width: 30px; height: 30px; font-size: 10px; display: flex; align-items: center; justify-content: center;">+5</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>

<style>
    .event-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 12px 24px rgba(0,0,0,0.08) !important;
        border-color: var(--gd-blue) !important;
    }
    
    .avatar-group .avatar {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .btn-light.border:hover {
        background-color: #f8fafc;
        border-color: #cbd5e1 !important;
    }

    /* Modern Scrollbar for Filter */
    .overflow-x-auto::-webkit-scrollbar {
        height: 4px;
    }
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
</style>
@endsection