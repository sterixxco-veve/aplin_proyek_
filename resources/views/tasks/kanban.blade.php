@extends('layouts.app')

@section('content')
<div class="container pb-5">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h3 class="fw-bold mb-1">Task Management</h3>
            <small class="text-muted">
                {{ $event->nama_event }} -
                {{ \Carbon\Carbon::parse($event->tgl_mulai)->format('M d, Y') }}
            </small>
            <div class="text-muted small mt-2">
                @if($canManageTasks)
                    Drag task ke kolom lain untuk mengubah status.
                @else
                    Mode view-only. Drag task butuh akses admin_org atau panitia event.
                @endif
            </div>
        </div>

        @if($canManageTasks)
            <button type="button" class="btn btn-primary rounded-pill px-4" onclick="openCreateTaskModal()">
                + Add Task
            </button>
        @endif
    </div>

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
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-{{ $col['color'] }}">
                            {{ $col['title'] }}
                        </h6>

                        <span class="badge bg-{{ $col['color'] }} bg-opacity-10 text-{{ $col['color'] }}">
                            {{ $tasks->where('status', $status)->count() }}
                        </span>
                    </div>

                    <div class="kanban-column" data-status="{{ $status }}">
                        @forelse($tasks->where('status', $status) as $task)
                            @php
                                $priority = $task->priority ?? 'medium';
                                $priorityClass = match ($priority) {
                                    'high' => 'bg-danger-subtle text-danger',
                                    'low' => 'bg-success-subtle text-success',
                                    default => 'bg-warning-subtle text-warning',
                                };
                                $priorityLabel = ucfirst($priority);
                            @endphp

                            <div class="kanban-card p-3 mb-3 shadow-sm"
                                 draggable="{{ $canManageTasks ? 'true' : 'false' }}"
                                 data-id="{{ $task->id_task }}">

                                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                    <div class="fw-semibold">
                                        {{ $task->nama_tugas }}
                                    </div>
                                    <span class="badge {{ $priorityClass }} rounded-pill">{{ $priorityLabel }}</span>
                                </div>

                                @if($task->brief)
                                    <p class="text-muted small mb-2">{{ $task->brief }}</p>
                                @endif

                                <div class="mb-2">
                                    <span class="badge bg-light text-dark border">
                                        {{ $task->division->nama_divisi ?? '-' }}
                                    </span>
                                </div>

                                <div class="d-flex justify-content-between text-muted small mt-2 gap-2">
                                    <div class="text-nowrap">
                                        <i class="bi bi-calendar me-1"></i>
                                        {{ $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('M d') : '-' }}
                                    </div>

                                    <div class="text-nowrap text-end">
                                        <i class="bi bi-person me-1"></i>
                                        {{ $task->assignee->name ?? '-' }}
                                    </div>
                                </div>

                                <div class="mt-2 text-end">
                                    <button type="button"
                                            class="btn btn-sm btn-light"
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

<div class="modal fade" id="taskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-body p-4 p-md-5">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h5 class="fw-bold mb-1" id="taskModalTitle">Tambah Task</h5>
                        <p class="text-muted small mb-0">Isi detail task untuk event ini.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="taskForm" method="POST" action="/events/{{ $event->id_event }}/tasks">
                    @csrf
                    <input type="hidden" id="task_id">

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted ms-1">Nama Task</label>
                            <input type="text" name="nama_tugas" id="task_nama_tugas"
                                   class="form-control bg-light border-0 py-3 rounded-4 shadow-none"
                                   placeholder="Contoh: Booking venue" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted ms-1">Brief</label>
                            <textarea name="brief" id="task_brief" rows="3"
                                      class="form-control bg-light border-0 py-3 rounded-4 shadow-none"
                                      placeholder="Deskripsi singkat tugas"></textarea>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted ms-1">Divisi</label>
                            <select name="id_divisi" id="task_id_divisi"
                                    class="form-select bg-light border-0 py-3 rounded-4 shadow-none" required>
                                <option value="">Pilih divisi</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id_divisi }}">{{ $division->nama_divisi }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted ms-1">Priority</label>
                            <select name="priority" id="task_priority"
                                    class="form-select bg-light border-0 py-3 rounded-4 shadow-none" required>
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted ms-1">Deadline</label>
                            <input type="datetime-local" name="deadline" id="task_deadline"
                                   class="form-control bg-light border-0 py-3 rounded-4 shadow-none">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted ms-1">Assign To</label>
                            <select name="assigned_to" id="task_assigned_to"
                                    class="form-select bg-light border-0 py-3 rounded-4 shadow-none">
                                <option value="">- Tidak di-assign -</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id_user }}">{{ $member->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 d-flex gap-2 justify-content-end mt-2">
                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold" id="taskSubmitBtn">
                                Simpan Task
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.kanban-wrapper {
    background: #ffffff;
    border-radius: 18px;
    padding: 16px;
    min-height: 500px;
    border: 1px solid #f1f3f4;
}

.kanban-column {
    min-height: 400px;
}

.kanban-card {
    border-radius: 16px;
    background: #ffffff !important;
    border: 1px solid #e5e7eb;
    cursor: grab;
    transition: all 0.2s ease;
}

.kanban-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.08);
    background: #ffffff !important;
}

.kanban-card.dragging {
    opacity: 0.92;
    cursor: grabbing;
    animation: wiggle 0.35s ease-in-out infinite;
    transform-origin: center;
    box-shadow: 0 12px 25px rgba(0,0,0,0.12);
}

.kanban-column.drag-over {
    background: rgba(66, 133, 244, 0.04);
    border-radius: 16px;
}

@keyframes wiggle {
    0% { transform: rotate(0deg) translateX(0); }
    25% { transform: rotate(1deg) translateX(1px); }
    50% { transform: rotate(0deg) translateX(0); }
    75% { transform: rotate(-1deg) translateX(-1px); }
    100% { transform: rotate(0deg) translateX(0); }
}

.badge.bg-opacity-10 {
    background-color: rgba(0,0,0,0.05) !important;
}

.bg-light {
    background: #f9fafb !important;
    opacity: 1 !important;
}

.text-muted {
    opacity: 0.7;
}

.form-control:focus,
.form-select:focus,
textarea.form-control:focus {
    background-color: #fff !important;
    box-shadow: 0 0 0 4px rgba(66, 133, 244, 0.1) !important;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('taskModal');
    const taskModal = new bootstrap.Modal(modalEl);
    const form = document.getElementById('taskForm');
    const title = document.getElementById('taskModalTitle');
    const submitBtn = document.getElementById('taskSubmitBtn');
    const taskIdInput = document.getElementById('task_id');
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const canManageTasks = @json($canManageTasks);

    if (canManageTasks) {
        document.querySelectorAll('.kanban-card').forEach((card) => {
            card.addEventListener('dragstart', (event) => {
                event.dataTransfer.setData('text/plain', card.dataset.id);
                card.classList.add('dragging');
            });

            card.addEventListener('dragend', () => {
                card.classList.remove('dragging');
            });
        });

        document.querySelectorAll('.kanban-column').forEach((column) => {
            column.addEventListener('dragover', (event) => {
                event.preventDefault();
                column.classList.add('drag-over');
            });

            column.addEventListener('dragleave', () => {
                column.classList.remove('drag-over');
            });

            column.addEventListener('drop', async (event) => {
                event.preventDefault();
                column.classList.remove('drag-over');

                const taskId = event.dataTransfer.getData('text/plain');
                const status = column.dataset.status;

                if (!taskId || !status) {
                    return;
                }

                try {
                    const response = await fetch(`/tasks/${taskId}/status`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ status })
                    });

                    if (!response.ok) {
                        const error = await response.json().catch(() => null);
                        alert(error?.message ?? 'Gagal update status task.');
                        return;
                    }

                    window.location.reload();
                } catch (error) {
                    alert('Gagal memindahkan task.');
                }
            });
        });
    }

    function removeMethodInput() {
        const methodInput = document.getElementById('task_method');
        if (methodInput) methodInput.remove();
    }

    function ensureMethodInput(value) {
        let methodInput = document.getElementById('task_method');
        if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.id = 'task_method';
            form.appendChild(methodInput);
        }
        methodInput.value = value;
    }

    function setFormValue(id, value) {
        const el = document.getElementById(id);
        if (el) el.value = value ?? '';
    }

    function resetForm() {
        form.reset();
        form.action = '/events/{{ $event->id_event }}/tasks';
        taskIdInput.value = '';
        title.textContent = 'Tambah Task';
        submitBtn.textContent = 'Simpan Task';
        removeMethodInput();
    }

    window.openCreateTaskModal = function () {
        resetForm();
        taskModal.show();
    };

    window.openEditModal = async function (taskId) {
        try {
            const response = await fetch(`/tasks/${taskId}`, {
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
            });

            if (!response.ok) {
                alert('Gagal memuat data task.');
                return;
            }

            const task = await response.json();

            form.action = `/tasks/${taskId}`;
            ensureMethodInput('PUT');
            taskIdInput.value = task.id_task;
            title.textContent = 'Edit Task';
            submitBtn.textContent = 'Update Task';

            setFormValue('task_nama_tugas', task.nama_tugas);
            setFormValue('task_brief', task.brief);
            setFormValue('task_id_divisi', task.id_divisi);
            setFormValue('task_priority', task.priority || 'medium');
            setFormValue('task_assigned_to', task.assigned_to || '');

            const deadline = task.deadline ? new Date(task.deadline) : null;
            if (deadline && !isNaN(deadline.getTime())) {
                const pad = (n) => String(n).padStart(2, '0');
                const formatted = `${deadline.getFullYear()}-${pad(deadline.getMonth() + 1)}-${pad(deadline.getDate())}T${pad(deadline.getHours())}:${pad(deadline.getMinutes())}`;
                setFormValue('task_deadline', formatted);
            } else {
                setFormValue('task_deadline', '');
            }

            taskModal.show();
        } catch (error) {
            alert('Gagal membuka form edit task.');
        }
    };

    modalEl.addEventListener('hidden.bs.modal', resetForm);
});
</script>
@endpush

@endsection
