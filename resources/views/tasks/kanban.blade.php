@extends('layouts.app')

@section('content')
<div class="container">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Task Management</h3>
            <small class="text-muted">
                {{ $event->nama_event }} - 
                {{ \Carbon\Carbon::parse($event->tgl_mulai)->format('M d, Y') }}
            </small>
        </div>

        <button class="btn btn-primary rounded-pill px-4">
            + Add Task
        </button>
    </div>

    {{-- KANBAN --}}
    <div class="row g-4">

        @php
            $columns = [
                'todo' => ['title' => 'To Do', 'color' => 'secondary'],
                'progress' => ['title' => 'In Progress', 'color' => 'primary'],
                'done' => ['title' => 'Done', 'color' => 'success'],
            ];
        @endphp

        @foreach($columns as $status => $col)
        <div class="col-md-4">

            <div class="kanban-wrapper p-3">

                {{-- HEADER --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-{{ $col['color'] }}">
                        {{ $col['title'] }}
                    </h6>

                    <span class="badge bg-{{ $col['color'] }} bg-opacity-10 text-{{ $col['color'] }}">
                        {{ $tasks->where('status', $status)->count() }}
                    </span>
                </div>

                {{-- COLUMN --}}
                <div class="kanban-column" data-status="{{ $status }}">

                    @forelse($tasks->where('status', $status) as $task)

                    <div class="kanban-card p-3 mb-3 shadow-sm relative"
                         draggable="true"
                         data-id="{{ $task->id_task }}">

                        {{-- TITLE --}}
                        <div class="fw-semibold mb-2">
                            {{ $task->nama_tugas }}
                        </div>

                        {{-- DIVISION --}}
                        <div class="mb-2">
                            <span class="badge bg-light text-dark">
                                {{ $task->division->nama_divisi ?? '-' }}
                            </span>
                        </div>

                        {{-- FOOTER --}}
                        <div class="d-flex justify-content-between text-muted small mt-2">

                            <div>
                                <i class="bi bi-calendar me-1"></i>
                                {{ $task->deadline 
                                    ? \Carbon\Carbon::parse($task->deadline)->format('M d') 
                                    : '-' }}
                            </div>

                            <div>
                                <i class="bi bi-person me-1"></i>
                                {{ $task->assignee->name ?? '-' }}
                            </div>

                        </div>

                        {{-- ACTION --}}
                        <div class="mt-2 text-end">
                            <button class="btn btn-sm btn-light"
                                    onclick="openEditModal({{ $task->id_task }})">
                                ✏️
                            </button>
                        </div>

                    </div>

                    @empty
                        <p class="text-muted small">No tasks</p>
                    @endforelse

                </div>

            </div>

        </div>
        @endforeach

    </div>

</div>
@endsection


{{-- ========================= --}}
{{-- STYLE FIX (WAJIB) --}}
{{-- ========================= --}}
@push('styles')
<style>

/* COLUMN WRAPPER */
.kanban-wrapper {
    background: #ffffff; /* FIX: solid putih */
    border-radius: 18px;
    padding: 16px;
    min-height: 500px;
    border: 1px solid #f1f3f4;
}

/* COLUMN AREA */
.kanban-column {
    min-height: 400px;
}

/* CARD */
.kanban-card {
    border-radius: 16px;
    background: #ffffff !important; /* FIX: paksa solid */
    border: 1px solid #e5e7eb;
    cursor: grab;
    transition: all 0.2s ease;
}

/* HOVER EFFECT */
.kanban-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.08);
    background: #ffffff !important;
}

/* HEADER COUNT BADGE */
.badge.bg-opacity-10 {
    background-color: rgba(0,0,0,0.05) !important;
}

/* REMOVE FADED LOOK */
.bg-light {
    background: #f9fafb !important;
    opacity: 1 !important;
}

/* TEXT FIX */
.text-muted {
    opacity: 0.7;
}

</style>
@endpush