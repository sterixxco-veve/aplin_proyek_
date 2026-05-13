@extends('layouts.app')

@section('content')
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
                    <div class="bg-success-subtle text-success rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                        <i class="bi bi-check2-square fs-4"></i>
                    </div>
                    <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1 small">+8%</span>
                </div>
                <small class="text-muted fw-medium d-block mb-1">Active Tasks</small>
                <h3 class="fw-bold mb-0 text-dark">156</h3> {{-- Mock data from image --}}
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4 border-0 shadow-sm h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="bg-warning-subtle text-warning rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                        <i class="bi bi-currency-dollar fs-4"></i>
                    </div>
                    <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1 small">+23%</span>
                </div>
                <small class="text-muted fw-medium d-block mb-1">Budget Overview</small>
                <h3 class="fw-bold mb-0 text-dark">$45.2K</h3> {{-- Mock data from image --}}
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
    </div>

    <div class="row g-4">
        {{-- LEFT COLUMN --}}
        <div class="col-lg-7">
            {{-- UPCOMING EVENTS --}}
            <div class="card border-0 shadow-sm mb-4">
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

            {{-- TEAM ACTIVITY --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-4 px-4 border-0">
                    <h5 class="fw-bold mb-0 text-dark">Team Activity</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="d-flex align-items-start mb-4">
                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm me-3" style="width: 38px; height: 38px; font-size: 12px; min-width: 38px;">SC</div>
                        <div>
                            <p class="mb-0 text-dark"><span class="fw-bold">Sarah Chen</span> completed <span class="text-muted fw-normal">Venue booking confirmation</span></p>
                            <small class="text-muted">2h ago</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-4">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm me-3" style="width: 38px; height: 38px; font-size: 12px; min-width: 38px;">MJ</div>
                        <div>
                            <p class="mb-0 text-dark"><span class="fw-bold">Mike Johnson</span> added <span class="text-muted fw-normal">New sponsor contact</span></p>
                            <small class="text-muted">4h ago</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-start">
                        <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm me-3" style="width: 38px; height: 38px; font-size: 12px; min-width: 38px;">LW</div>
                        <div>
                            <p class="mb-0 text-dark"><span class="fw-bold">Lisa Wong</span> updated <span class="text-muted fw-normal">Event rundown draft</span></p>
                            <small class="text-muted">6h ago</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN --}}
        <div class="col-lg-5">
            {{-- RECENT TASKS --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-4 px-4 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark">Recent Tasks</h5>
                    <a href="#" class="text-primary text-decoration-none small fw-bold">View all</a>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="task-item border rounded-4 p-3 mb-3 hover-shadow transition border-light">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="fw-bold mb-0 text-dark">Finalize venue booking</h6>
                            <span class="badge bg-danger-subtle text-danger px-2 py-1 small" style="font-size: 10px;">High</span>
                        </div>
                        <div class="d-flex align-items-center small text-muted">
                            <span class="badge bg-light text-muted border border-light-subtle rounded-3 me-3 fw-normal">Logistics</span>
                            <span><i class="bi bi-clock me-1"></i> May 1</span>
                        </div>
                    </div>
                    <div class="task-item border rounded-4 p-3 mb-3 hover-shadow transition border-light">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="fw-bold mb-0 text-dark">Send sponsor proposals</h6>
                            <span class="badge bg-warning-subtle text-warning px-2 py-1 small" style="font-size: 10px;">Medium</span>
                        </div>
                        <div class="d-flex align-items-center small text-muted">
                            <span class="badge bg-light text-muted border border-light-subtle rounded-3 me-3 fw-normal">Partnership</span>
                            <span><i class="bi bi-clock me-1"></i> May 3</span>
                        </div>
                    </div>
                    <div class="task-item border rounded-4 p-3 hover-shadow transition border-light">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="fw-bold mb-0 text-dark">Design event poster</h6>
                            <span class="badge bg-success-subtle text-success px-2 py-1 small" style="font-size: 10px;">Low</span>
                        </div>
                        <div class="d-flex align-items-center small text-muted">
                            <span class="badge bg-light text-muted border border-light-subtle rounded-3 me-3 fw-normal">Creative</span>
                            <span><i class="bi bi-clock me-1"></i> May 5</span>
                        </div>
                    </div>
                </div>
            </div>

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
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL CREATE EVENT (Google Palette Version) --}}
<div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 24px;">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h5 class="fw-bold text-dark mb-0">Create New Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Organization</label>
                    <select id="id_org" class="form-control border-0 bg-light py-3 px-3 rounded-4" required>
                        <option value="">Pilih organization</option>
                        @foreach($organizations as $org)
                            <option value="{{ $org->id_org }}">{{ $org->nama_org }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Category</label>
                    <select id="id_event_category" class="form-control border-0 bg-light py-3 px-3 rounded-4" required>
                        <option value="">Pilih kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id_event_category }}">{{ $category->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-0">
                    <label class="form-label small fw-bold text-muted">Start Date</label>
                    <input type="date" id="tgl_mulai" class="form-control border-0 bg-light py-3 px-3 rounded-4" required>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold" id="saveEvent">Save Event</button>
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

{{-- SCRIPT --}}
<script>
document.getElementById('saveEvent')?.addEventListener('click', function(){
    const btn = this;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

    fetch('/events', {
        method: 'POST',
        headers: {
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':'{{ csrf_token() }}'
        },
        body: JSON.stringify({
            id_org: document.getElementById('id_org').value,
            id_event_category: document.getElementById('id_event_category').value,
            nama_event: document.getElementById('nama_event').value,
            tgl_mulai: document.getElementById('tgl_mulai').value
        })
    })
    .then(res => res.json())
    .then(data => {
        location.reload();
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = 'Save Event';
    });
});
</script>
@endsection