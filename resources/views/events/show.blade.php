@extends('layouts.app')

@section('content')
<div class="container">

    {{-- ========================= --}}
    {{-- HEADER --}}
    {{-- ========================= --}}
    <div class="card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap">

            <div>
                <h2 class="fw-bold mb-1">{{ $event->nama_event }}</h2>

                <span class="badge rounded-pill px-3 py-2"
                      style="background:#fff3cd; color:#856404;">
                    Planning
                </span>

                <div class="d-flex gap-4 mt-3 text-muted small flex-wrap">
                    <div><i class="bi bi-calendar-event me-1"></i> {{ \Carbon\Carbon::parse($event->tgl_mulai)->format('M d, Y') }}</div>
                    <div><i class="bi bi-geo-alt me-1"></i> {{ $event->organization->nama_org ?? 'N/A' }}</div>
                    <div><i class="bi bi-people me-1"></i> {{ $event->committees->count() }} members</div>
                    <div><i class="bi bi-cash me-1"></i> Rp {{ number_format($event->financial_summary['total_budget']) }}</div>
                </div>
            </div>

            <a href="/events/{{ $event->id_event }}/edit"
                class="btn btn-primary px-4">
                    Edit Event
                </a>

        </div>
    </div>

    {{-- ========================= --}}
    {{-- MAIN CONTENT --}}
    {{-- ========================= --}}
    <div class="card p-4">

        {{-- TAB NAV --}}
        <div class="d-flex gap-4 border-bottom mb-4">
            <button class="tab-btn active" data-tab="overview">Overview</button>
            <button class="tab-btn" data-tab="rundown">Rundown</button>
            <button class="tab-btn" data-tab="budget">Budget</button>
            <button class="tab-btn" data-tab="tasks">Tasks</button>
        </div>

        {{-- TAB CONTENT --}}
        <div>

            <div id="overview" class="tab-content">
                @include('events.partials.overview')
            </div>

            <div id="rundown" class="tab-content d-none">
                @include('events.partials.rundown')
            </div>

            <div id="budget" class="tab-content d-none">
                @include('events.partials.budget')
            </div>

            <div id="tasks" class="tab-content d-none">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Tasks</h5>

                    <a href="/tasks/event/{{ $event->id_event }}"
                       class="btn btn-primary rounded-pill px-4">
                        Open Task Management
                    </a>
                </div>

                <div class="p-4 bg-light rounded-3 text-muted">
                    Task board dibuka di halaman terpisah supaya detail event tetap fokus ke overview, rundown, dan budget.
                </div>
            </div>

        </div>

    </div>

</div>

{{-- ========================= --}}
{{-- MODAL EDIT TASK --}}
{{-- ========================= --}}
<div class="modal fade" id="editTaskModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:20px;">

            <div class="modal-body p-4">

                <h5 class="fw-bold mb-4">Edit Task</h5>

                <form id="editTaskForm">
                    @csrf
                    @method('PUT')

                    <input type="hidden" id="edit_task_id">

                    <div class="mb-3">
                        <label class="small text-muted">Task Name</label>
                        <input type="text" id="edit_nama"
                               class="form-control bg-light border-0 rounded-3">
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted">Deadline</label>
                        <input type="datetime-local" id="edit_deadline"
                               class="form-control bg-light border-0 rounded-3">
                    </div>

                    <button type="submit"
                            class="btn btn-primary w-100 rounded-pill py-2">
                        Update Task
                    </button>

                </form>

            </div>

        </div>
    </div>
</div>

{{-- ========================= --}}
{{-- SCRIPT (FIXED) --}}
{{-- ========================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    // TAB SWITCH
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function () {

            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            document.querySelectorAll('.tab-content').forEach(c => c.classList.add('d-none'));
            document.getElementById(this.dataset.tab).classList.remove('d-none');
        });
    });

});
</script>

@endsection