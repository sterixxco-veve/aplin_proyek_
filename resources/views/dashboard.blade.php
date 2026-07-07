@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

    {{-- PROSES FILTER DATA UPCOMING DI AWAL AGAR BISA DIGUNAKAN DI KARTU STATS & LIST DETAIL --}}
    @php
        $now = \Carbon\Carbon::now();
        $upcomingItems = $events->filter(function ($event) use ($now) {
            $mulai = \Carbon\Carbon::parse($event->tgl_mulai);
            $selesai = $event->tgl_selesai ? \Carbon\Carbon::parse($event->tgl_selesai) : null;

            if ($now->lt($mulai)) {
                return true;
            } // Planning
            if ($selesai && $now->between($mulai, $selesai)) {
                return true;
            } // Ongoing
            if (!$selesai && $now->isSameDay($mulai)) {
                return true;
            } // Ongoing fallback
            return false; // Done disembunyikan dari modul upcoming
        });
    @endphp

    <div class="container-fluid px-4 pb-5">
        {{-- WELCOME HEADER --}}
        <div class="mb-4">
            <h2 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px; font-size: 1.5rem;">Dashboard</h2>
            <p class="text-muted small mb-0">Welcome back! Here's what's happening with your events.</p>
        </div>

        {{-- TOP STATS CARDS --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card p-3 border shadow-sm h-100"
                    style="border-radius: 16px; border-color: #e2e8f0 !important; background: #ffffff;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="rounded-3 p-2 d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px; background-color: #eef2ff; color: #4f46e5;">
                            <i class="bi bi-calendar-event fs-5"></i>
                        </div>
                        <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1 font-semibold"
                            style="font-size: 10px;">+12%</span>
                    </div>
                    <small class="text-secondary fw-medium d-block mb-1" style="font-size: 0.775rem;">Total Events</small>
                    <h4 class="fw-bold mb-0 text-dark" style="letter-spacing: -0.3px;">{{ count($events) }}</h4>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-3 border shadow-sm h-100"
                    style="border-radius: 16px; border-color: #e2e8f0 !important; background: #ffffff;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="bg-danger-subtle text-danger rounded-3 p-2 d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;">
                            <i class="bi bi-graph-up-arrow fs-5"></i>
                        </div>
                        <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1 font-semibold"
                            style="font-size: 10px;">+{{ count($upcomingItems) }}</span>
                    </div>
                    <small class="text-secondary fw-medium d-block mb-1" style="font-size: 0.775rem;">Upcoming Events</small>
                    {{-- DIUBAH MENJADI DINAMIS --}}
                    <h4 class="fw-bold mb-0 text-dark" style="letter-spacing: -0.3px;">{{ count($upcomingItems) }}</h4>
                </div>
            </div>

            {{-- COMPONENT: UPCOMING EVENTS LIST --}}
            <div class="col-md-6">
                <div class="card border shadow-sm h-100"
                    style="border-radius: 16px; border-color: #e2e8f0 !important; background: #ffffff;">
                    <div class="card-header bg-white py-3 px-3 border-0 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">Upcoming Events</h6>
                        <a href="/events" class="text-primary text-decoration-none small fw-bold"
                            style="color: #4f46e5 !important; font-size: 0.8rem;">View all</a>
                    </div>
                    <div class="card-body p-2" style="max-height: 250px; overflow-y: auto;">
                        <div class="list-group list-group-flush">

                            @forelse($upcomingItems as $event)
                                <div class="list-group-item border-0 rounded-3 mb-1 hover-bg-light transition p-2 px-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-3 text-white p-2 me-3 d-flex align-items-center justify-content-center"
                                            style="width: 38px; height: 38px; background-color: #4f46e5;">
                                            <i class="bi bi-calendar-check style-icon-size"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-0 text-dark style-title-text">{{ $event->nama_event }}
                                            </h6>
                                            <small class="text-secondary" style="font-size: 0.775rem;"><i
                                                    class="bi bi-clock me-1 text-primary"></i>{{ \Carbon\Carbon::parse($event->tgl_mulai)->format('M d, Y') }}</small>
                                        </div>

                                        @php
                                            $mulai = \Carbon\Carbon::parse($event->tgl_mulai);
                                            $selesai = $event->tgl_selesai
                                                ? \Carbon\Carbon::parse($event->tgl_selesai)
                                                : null;

                                            if ($now->lt($mulai)) {
                                                $status = 'Planning';
                                                $badgeClass = 'bg-warning-subtle text-warning';
                                            } else {
                                                $status = 'Ongoing';
                                                $badgeClass = 'bg-primary-subtle text-primary';
                                            }
                                        @endphp

                                        <span
                                            class="badge {{ $badgeClass }} rounded-pill px-2 py-1 fw-bold text-uppercase"
                                            style="font-size: 9px; letter-spacing: 0.3px;">{{ $status }}</span>
                                    </div>
                                </div>
                            @empty
                                {{-- State placeholder visual jika semua event sudah rampung/kosong --}}
                                <div class="text-center py-4 my-auto">
                                    <i class="bi bi-calendar-minus text-muted opacity-30 d-block mb-1"
                                        style="font-size: 2rem;"></i>
                                    <p class="text-muted m-0 small fw-medium">Semua agenda selesai. Belum ada event baru.
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            {{-- EVENT CALENDAR CARD --}}
            <div class="col-lg-8">
                <div class="card border shadow-sm h-100"
                    style="border-radius: 16px; border-color: #e2e8f0 !important; background: #ffffff;">
                    <div class="card-header bg-white border-0 py-3 px-3">
                        <h5 class="fw-bold text-dark mb-0" style="font-size: 1rem;">Event Calendar</h5>
                    </div>
                    <div class="card-body px-3 pb-3 pt-0">
                        <div id="calendar" style="font-size: 0.9rem;"></div>
                    </div>
                </div>
            </div>

            {{-- QUICK ACTIONS CARD --}}
            <div class="col-lg-4">
                <div class="card border shadow-sm h-100 p-3"
                    style="border-radius: 16px; border-color: #e2e8f0 !important; background: #ffffff;">
                    <h6 class="fw-bold mb-3 text-dark px-1 pt-1" style="font-size: 1rem;">Quick Actions</h6>

                    <div class="row g-2">
                        <div class="col-12">
                            <a href="/events/create"
                                class="btn btn-light w-100 rounded-3 border-dashed py-3 text-start text-decoration-none text-dark d-flex align-items-center px-3">
                                <i class="bi bi-calendar-plus fs-5 me-2 text-primary"></i>
                                <span class="fw-semibold text-secondary" style="font-size: 0.85rem;">Create Event</span>
                            </a>
                        </div>

                        <div class="col-12">
                            <a href="/tasks"
                                class="btn btn-light w-100 rounded-3 border-dashed py-3 text-start text-decoration-none text-dark d-flex align-items-center px-3">
                                <i class="bi bi-check2-circle fs-5 me-2 text-success"></i>
                                <span class="fw-semibold text-secondary" style="font-size: 0.85rem;">Add Task</span>
                            </a>
                        </div>

                        <div class="col-12">
                            <a href="/partners"
                                class="btn btn-light w-100 rounded-3 border-dashed py-3 text-start text-decoration-none text-dark d-flex align-items-center px-3">
                                <i class="bi bi-people fs-5 me-2 text-warning"></i>
                                <span class="fw-semibold text-secondary" style="font-size: 0.85rem;">Add Partner</span>
                            </a>
                        </div>

                        <div class="col-12">
                            <a href="/organizations"
                                class="btn btn-light w-100 rounded-3 border-dashed py-3 text-start text-decoration-none text-dark d-flex align-items-center px-3">
                                <i class="bi bi-building fs-5 me-2" style="color: #9333ea;"></i>
                                <span class="fw-semibold text-secondary" style="font-size: 0.85rem;">Organization</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- GLOBAL CUSTOM STYLE --}}
    <style>
        .bg-purple-subtle {
            background-color: #f3e8ff;
        }

        .text-purple {
            color: #9333ea;
        }

        .border-dashed {
            border: 1.5px dashed #cbd5e1 !important;
            background-color: #f8fafc !important;
            transition: all 0.2s ease;
        }

        .border-dashed:hover {
            background-color: #f1f5f9 !important;
            border-color: #4f46e5 !important;
        }

        .transition {
            transition: all 0.15s ease-in-out;
        }

        .hover-bg-light:hover {
            background-color: #f8fafc !important;
        }

        .style-icon-size {
            font-size: 0.95rem !important;
        }

        .style-title-text {
            font-size: 0.875rem !important;
            letter-spacing: -0.1px;
        }

        .fc .fc-toolbar {
            align-items: center;
            margin-bottom: 1.25rem !important;
        }

        .fc .fc-button-group {
            gap: 4px;
        }

        .fc .fc-button-primary {
            background-color: #4f46e5 !important;
            border-color: #4f46e5 !important;
            font-size: 0.8rem !important;
            padding: 6px 12px !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            box-shadow: none !important;
        }

        .fc .fc-toolbar-chunk div {
            display: flex;
            gap: 8px;
        }

        .fc .fc-button-primary:disabled {
            background-color: #94a3b8 !important;
            border-color: #94a3b8 !important;
        }

        .fc-theme-standard td,
        .fc-theme-standard th {
            border-color: #f1f5f9 !important;
        }

        .fc .fc-toolbar-title {
            font-size: 1.1rem !important;
            font-weight: 700 !important;
            color: #1e293b !important;
        }

        .fc .fc-event {
            background-color: #eef2ff !important;
            border: 1px solid #c7d2fe !important;
            color: #4f46e5 !important;
            font-weight: 600 !important;
            padding: 3px 6px !important;
            border-radius: 6px !important;
            font-size: 0.725rem !important;
        }

        body {
            background-color: #f8fafc;
        }
    </style>

    {{-- COMPONENT FULLCALENDAR ENGINE --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            if (!calendarEl) return;

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 480,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                events: [
                    @foreach ($events as $event)
                        {
                            title: @json($event->nama_event),
                            start: '{{ $event->tgl_mulai }}',
                            end: '{{ $event->tgl_selesai ?? $event->tgl_mulai }}',
                            url: '/events/{{ $event->id_event }}/details'
                        },
                    @endforeach
                ]
            });

            calendar.render();
        });
    </script>
@endsection