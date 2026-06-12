@extends('layouts.app')

@push('styles')
    <style>
        /* Kanban Layout Grid & Components */
        .kanban-wrapper {
            background: #ffffff;
            border: 1px solid #e2e8f0 !important;
            border-radius: 16px;
            min-height: 480px;
            transition: all 0.25s ease;
        }

        .kanban-column {
            min-height: 400px;
            transition: background-color 0.2s ease;
        }

        .kanban-card {
            border-radius: 12px;
            background: #ffffff;
            border: 1px solid #e2e8f0 !important;
            cursor: grab;
            transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.2s ease;
        }

        .kanban-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05) !important;
        }

        .kanban-card.dragging {
            opacity: 0.4;
            cursor: grabbing;
        }

        /* Drag over column state modern indigo */
        .kanban-column.drag-over {
            background-color: rgba(79, 70, 229, 0.03);
            border: 2px dashed #4f46e5 !important;
            border-radius: 12px;
        }

        /* Custom Utilities */
        .text-truncate-custom {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.4;
        }

        .avatar-circle {
            width: 26px;
            height: 26px;
            font-size: 10px;
            background-color: #4f46e5;
            color: #ffffff;
            font-weight: bold;
        }

        /* Form Fields Styling Modern Compact */
        .form-control-custom,
        .form-select-custom {
            background-color: #ffffff !important;
            border: 1.5px solid #e2e8f0 !important;
            padding: 10px 14px !important;
            border-radius: 10px !important;
            color: #0f172a !important;
            font-size: 0.9rem !important;
        }

        .form-control-custom:focus,
        .form-select-custom:focus {
            background-color: #ffffff !important;
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1) !important;
        }

        .p-25 {
            padding: 16px !important;
        }

        .style-task-title {
            font-size: 0.875rem;
            letter-spacing: -0.2px;
        }

        body {
            background-color: #f8fafc;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4 pb-5"> {{-- Diganti ke container-fluid agar pas dengan space dashboard --}}



        {{-- ========================= --}}
        {{-- KANBAN BOARD CONTAINER    --}}
        {{-- ========================= --}}
        @if (isset($event))
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold text-dark mb-0" style="font-size: 1.15rem; letter-spacing: -0.3px;">
                        {{ $event->nama_event }}</h5>
                    <div class="text-secondary small mt-1" style="font-size: 0.8rem;">
                        @if ($canManageTasks)
                            <i class="bi bi-info-circle me-1 text-primary"></i> Geser kartu tugas antar kolom untuk
                            memperbarui status secara instan.
                        @else
                            <i class="bi bi-lock me-1 text-danger"></i> Mode view-only. Anda membutuhkan akses panitia untuk
                            memindahkan tugas.
                        @endif
                    </div>
                </div>

                @if ($canManageTasks)
                    <button type="button"
                        class="btn btn-primary rounded-pill px-3 py-2 fw-semibold small shadow-sm d-flex align-items-center gap-1"
                        onclick="openCreateTaskModal()"
                        style="font-size: 0.85rem; background-color: #4f46e5; border: none;">
                        <i class="bi bi-plus-lg"></i> Add Task
                    </button>
                @endif
            </div>

            <div class="row g-3">
                @php
                    $columns = [
                        'todo' => ['title' => 'To Do', 'color' => 'secondary'],
                        'progress' => ['title' => 'In Progress', 'color' => 'primary'],
                        'done' => ['title' => 'Done', 'color' => 'success'],
                    ];
                @endphp

                @foreach ($columns as $status => $col)
                    <div class="col-md-4">
                        <div class="kanban-wrapper p-25 shadow-sm border">
                            <div class="d-flex justify-content-between align-items-center mb-3 px-1">
                                <h6 class="fw-bold text-{{ $col['color'] }} mb-0" style="font-size: 0.9rem;">
                                    {{ $col['title'] }}
                                </h6>
                                <span
                                    class="badge bg-{{ $col['color'] }} bg-opacity-10 text-{{ $col['color'] }} rounded-pill px-2 py-1 small font-bold"
                                    style="font-size: 0.75rem;">
                                    {{ $tasks->where('status', $status)->count() }}
                                </span>
                            </div>

                            <div class="kanban-column d-flex flex-column gap-2" data-status="{{ $status }}">
                                @forelse($tasks->where('status', $status) as $task)
                                    @php
                                        $priority = $task->priority ?? 'medium';
                                        $priorityClass = match ($priority) {
                                            'high' => 'bg-danger-subtle text-danger border border-danger-subtle',
                                            'low' => 'bg-success-subtle text-success border border-success-subtle',
                                            default => 'bg-warning-subtle text-warning border border-warning-subtle',
                                        };
                                    @endphp

                                    <div class="kanban-card card p-3 shadow-sm border-0"
                                        draggable="{{ $canManageTasks ? 'true' : 'false' }}" data-id="{{ $task->id_task }}">

                                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                            <div class="fw-bold text-dark small-title style-task-title col-9 px-0">
                                                {{ $task->nama_tugas }}
                                            </div>
                                            <span
                                                class="badge {{ $priorityClass }} rounded-pill text-capitalize text-center col-3 px-0"
                                                style="font-size: 9px; padding: 3px 0; font-weight: 600;">
                                                {{ $priority }}
                                            </span>
                                        </div>

                                        @if ($task->brief)
                                            <p class="text-secondary mb-2 text-truncate-custom" style="font-size: 0.8rem;">
                                                {{ $task->brief }}</p>
                                        @endif

                                        <div class="mb-2">
                                            <span class="badge bg-white text-secondary border px-2 py-1"
                                                style="font-size: 10px; border-color: #e2e8f0 !important;">
                                                <i class="bi bi-tag me-1"></i>{{ $task->division->nama_divisi ?? '-' }}
                                            </span>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center text-muted mt-2 pt-2 border-top"
                                            style="font-size: 11px; border-color: #f1f5f9 !important;">
                                            <div class="text-nowrap text-secondary">
                                                <i class="bi bi-calendar3 me-1 text-primary"></i>
                                                {{ $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('M d') : '-' }}
                                            </div>

                                            <div class="d-flex align-items-center gap-15">
                                                <div class="avatar-circle rounded-circle d-flex align-items-center justify-content-center text-uppercase shadow-sm"
                                                    title="PIC: {{ $task->assignee->name ?? 'Unassigned' }}">
                                                    {{ isset($task->assignee->name) ? substr($task->assignee->name, 0, 2) : '?' }}
                                                </div>
                                                @if ($canManageTasks)
                                                    <button type="button"
                                                        class="btn btn-sm btn-link text-warning p-0 text-decoration-none fw-semibold ms-1"
                                                        style="font-size: 0.8rem;"
                                                        onclick="openEditModal({{ $task->id_task }})">
                                                        Edit
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4 text-muted small bg-white border border-dashed rounded-3 shadow-none"
                                        style="border-color: #cbd5e1 !important;">
                                        <i class="bi bi-inbox text-muted opacity-30 d-block mb-1"
                                            style="font-size: 1.5rem;"></i> Belum ada tugas
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ========================= --}}
            {{-- MODAL TAMBAH & EDIT TASK  --}}
            {{-- ========================= --}}
            <div class="modal fade" id="taskModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                        <div class="modal-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="fw-bold text-dark mb-0" id="taskModalTitle"
                                        style="font-size: 1.15rem; letter-spacing: -0.3px;">Tambah Task</h5>
                                    <p class="text-muted small mb-0" style="font-size: 0.8rem;">Isi kelengkapan instruksi
                                        tugas panitia.</p>
                                </div>
                                <button type="button" class="btn-close small shadow-none" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <form id="taskForm" method="POST" action="/events/{{ $event->id_event }}/tasks">
                                @csrf
                                <input type="hidden" id="task_id">

                                <div class="row g-2">
                                    <div class="col-12">
                                        <label class="form-label small fw-semibold text-secondary mb-1">Nama Task</label>
                                        <input type="text" name="nama_tugas" id="task_nama_tugas"
                                            class="form-control form-control-custom shadow-none @error('nama_tugas') is-invalid @enderror"
                                            placeholder="Contoh: Booking venue gedung utama">
                                        @error('nama_tugas')
                                            <div class="invalid-feedback fw-semibold mt-1" style="font-size: 0.775rem;">
                                                {{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label small fw-semibold text-secondary mb-1">Brief
                                            Deskripsi</label>
                                        <textarea name="brief" id="task_brief" rows="2" class="form-control form-control-custom shadow-none"
                                            placeholder="Tulis ringkasan detail pengerjaan tugas..."></textarea>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold text-secondary mb-1">Divisi Penanggung
                                            Jawab</label>
                                        <select name="id_divisi" id="task_id_divisi"
                                            class="form-select form-select-custom shadow-none @error('id_divisi') is-invalid @enderror">
                                            <option value="">Pilih divisi...</option>
                                            @foreach ($divisions ?? [] as $division)
                                                <option value="{{ $division->id_divisi }}">
                                                    {{ $division->nama_divisi }}</option>
                                            @endforeach
                                        </select>
                                        @error('id_divisi')
                                            <div class="invalid-feedback fw-semibold mt-1" style="font-size: 0.775rem;">
                                                {{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold text-secondary mb-1">Tingkat
                                            Prioritas</label>
                                        <select name="priority" id="task_priority"
                                            class="form-select form-select-custom shadow-none @error('priority') is-invalid @enderror">
                                            <option value="low">Low</option>
                                            <option value="medium" selected>Medium</option>
                                            <option value="high">High</option>
                                        </select>
                                        @error('priority')
                                            <div class="invalid-feedback fw-semibold mt-1" style="font-size: 0.775rem;">
                                                {{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold text-secondary mb-1">Batas
                                            Deadline</label>
                                        <input type="datetime-local" name="deadline" id="task_deadline"
                                            class="form-control form-control-custom shadow-none">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold text-secondary mb-1">Assign Ke
                                            Anggota</label>
                                        <select name="assigned_to" id="task_assigned_to"
                                            class="form-select form-select-custom shadow-none">
                                            <option value="">- Tidak di-assign (Kosong) -</option>
                                            @foreach ($members ?? [] as $member)
                                                <option value="{{ $member->id_user }}">{{ $member->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 d-flex gap-2 justify-content-end mt-3">
                                        <button type="button" class="btn btn-light btn-sm px-3 fw-medium"
                                            data-bs-dismiss="modal"
                                            style="border-radius: 8px; background-color: #f1f5f9;">Batal</button>
                                        <button type="submit" class="btn btn-primary btn-sm px-3 fw-semibold"
                                            id="taskSubmitBtn"
                                            style="border-radius: 8px; background-color: #4f46e5; border: none;">Simpan
                                            Task</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- Empty Placeholder State --}}
            <div class="card border-0 shadow-sm text-center p-5 bg-white" style="border-radius: 16px; min-height: 400px;">
                <div class="my-auto py-5">
                    <i class="bi bi-kanban text-muted opacity-20 d-block mb-2" style="font-size: 60px;"></i>
                    <h5 class="fw-bold text-dark" style="font-size: 1.15rem;">Belum Ada Event Terpilih</h5>
                    <p class="text-muted small mx-auto mb-0" style="max-width: 420px; font-size: 0.85rem;">
                        Silakan gunakan menu dropdown di atas untuk memuat data papan aktivitas tugas serta manajemen alur
                        kerja kepanitiaan.
                    </p>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        function switchKanbanEvent(eventId) {
            if (eventId) {
                window.location.href = '/tasks?event_id=' + eventId;
            } else {
                window.location.href = '/tasks';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const modalEl = document.getElementById('taskModal');
            if (!modalEl) return;

            const taskModal = new bootstrap.Modal(modalEl);

            @if ($errors->any())
                taskModal.show();
            @endif

            const form = document.getElementById('taskForm');
            const title = document.getElementById('taskModalTitle');
            const submitBtn = document.getElementById('taskSubmitBtn');
            const taskIdInput = document.getElementById('task_id');
            const csrfToken = document.querySelector('input[name="_token"]').value;
            const canManageTasks = @json($canManageTasks ?? false);

            if (canManageTasks) {
                document.querySelectorAll('.kanban-card').forEach((card) => {
                    card.addEventListener('dragstart', (event) => {
                        event.dataTransfer.setData('text/plain', card.dataset.id);
                        card.classList.add('dragging');
                    });
                    card.addEventListener('dragend', () => card.classList.remove('dragging'));
                });

                document.querySelectorAll('.kanban-column').forEach((column) => {
                    column.addEventListener('dragover', (e) => {
                        e.preventDefault();
                        column.classList.add('drag-over');
                    });
                    column.addEventListener('dragleave', () => column.classList.remove('drag-over'));
                    column.addEventListener('drop', async (event) => {
                        event.preventDefault();
                        column.classList.remove('drag-over');
                        const taskId = event.dataTransfer.getData('text/plain');
                        const status = column.dataset.status;

                        if (!taskId || !status) return;

                        try {
                            const response = await fetch(`/tasks/${taskId}/status`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    status
                                })
                            });
                            if (response.ok) {
                                window.location.reload();
                            } else {
                                alert('Gagal memperbarui status tugas.');
                            }
                        } catch (error) {
                            alert('Terjadi kesalahan jaringan.');
                        }
                    });
                });
            }

            window.openCreateTaskModal = function() {
                form.reset();
                form.action = '/events/{{ $event->id_event ?? '' }}/tasks';
                taskIdInput.value = '';
                title.textContent = 'Tambah Task';
                submitBtn.textContent = 'Simpan Task';
                taskModal.show();
            };

            window.openEditModal = async function(taskId) {
                try {
                    const response = await fetch(`/tasks/${taskId}`, {
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });
                    if (!response.ok) return alert('Gagal memuat data task.');
                    const task = await response.json();

                    form.action = `/tasks/${taskId}`;
                    taskIdInput.value = task.id_task;
                    title.textContent = 'Edit Task';
                    submitBtn.textContent = 'Update Task';

                    document.getElementById('task_nama_tugas').value = task.nama_tugas;
                    document.getElementById('task_brief').value = task.brief ?? '';
                    document.getElementById('task_id_divisi').value = task.id_divisi;
                    document.getElementById('task_priority').value = task.priority || 'medium';
                    document.getElementById('task_assigned_to').value = task.assigned_to || '';

                    if (task.deadline) {
                        document.getElementById('task_deadline').value = task.deadline.substring(0, 16);
                    }
                    taskModal.show();
                } catch (error) {
                    alert('Gagal memuat data detail tugas.');
                }
            };
        });
    </script>
@endpush
