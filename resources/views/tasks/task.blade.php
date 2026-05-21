@extends('layouts.app')

@push('styles')
<style>
    /* Kanban Layout Grid & Components */
    .kanban-wrapper {
        background: #f8f9fa;
        border-radius: 20px;
        min-height: 550px;
        transition: all 0.25s ease;
    }

    .kanban-column {
        min-height: 480px;
        transition: background-color 0.2s ease;
    }

    .kanban-card {
        border-radius: 16px;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        cursor: grab;
        transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.2s ease;
    }

    .kanban-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.06) !important;
    }

    .kanban-card.dragging {
        opacity: 0.4;
        cursor: grabbing;
    }

    /* Drag over column state */
    .kanban-column.drag-over {
        background-color: rgba(13, 110, 253, 0.05);
        border: 2px dashed #0d6efd !important;
        border-radius: 14px;
    }

    /* Custom Utilities */
    .text-truncate-custom {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .avatar-circle {
        width: 28px;
        height: 28px;
        font-size: 11px;
        background-color: #0d6efd;
        color: #ffffff;
        font-weight: bold;
    }

    /* Form Fields Styling */
    .form-control-custom, .form-select-custom {
        background-color: #f8f9fa !important;
        border: none !important;
        padding: 12px 16px !important;
        border-radius: 12px !important;
        color: #212529 !important;
    }

    .form-control-custom:focus, .form-select-custom:focus {
        background-color: #ffffff !important;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15) !important;
    }
</style>
@endpush

@section('content')
<div class="container pb-5">

    {{-- ========================= --}}
    {{-- EVENT SELECTOR HEADER --}}
    {{-- ========================= --}}
    <div class="card p-4 mb-4 border-0 shadow-sm" style="border-radius: 20px;">
        <div class="row align-items-center">
            <div class="col-md-6 mb-3 mb-md-0">
                <h4 class="fw-bold mb-1 text-dark">Task Management Board</h4>
                <p class="text-muted small mb-0">Pilih salah satu event untuk menampilkan dan mengelola papan tugas.</p>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold text-muted ms-1">Pilih Acara / Event</label>
                <select class="form-select form-select-custom fw-semibold shadow-none" 
                        id="kanbanEventSelector" onchange="switchKanbanEvent(this.value)">
                    <option value="">-- Pilih Event Terlebih Dahulu --</option>
                    @foreach($allEvents ?? [] as $e)
                        <option value="{{ $e->id_event }}" {{ (isset($event) && $event->id_event == $e->id_event) ? 'selected' : '' }}>
                            {{ $e->nama_event }} ({{ \Carbon\Carbon::parse($e->tgl_mulai)->format('M d, Y') }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- ========================= --}}
    {{-- KANBAN BOARD CONTAINER    --}}
    {{-- ========================= --}}
    @if(isset($event))
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <h5 class="fw-bold mb-1 text-dark">{{ $event->nama_event }}</h5>
                <div class="text-muted small">
                    @if($canManageTasks)
                        <i class="bi bi-info-circle me-1"></i> Geser kartu tugas antar kolom untuk memperbarui status secara instan.
                    @else
                        <i class="bi bi-lock me-1"></i> Mode view-only. Anda membutuhkan akses panitia untuk memindahkan tugas.
                    @endif
                </div>
            </div>

            @if($canManageTasks)
                <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" onclick="openCreateTaskModal()">
                    <i class="bi bi-plus-lg me-1"></i> Add Task
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
                    <div class="kanban-wrapper p-3 border border-light shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-3 px-1">
                            <h6 class="fw-bold text-{{ $col['color'] }} mb-0">
                                {{ $col['title'] }}
                            </h6>
                            <span class="badge bg-{{ $col['color'] }} bg-opacity-10 text-{{ $col['color'] }} rounded-pill px-2.5 py-1.5 small font-bold">
                                {{ $tasks->where('status', $status)->count() }}
                            </span>
                        </div>

                        <div class="kanban-column d-flex flex-column gap-3 border border-transparent" data-status="{{ $status }}">
                            @forelse($tasks->where('status', $status) as $task)
                                @php
                                    $priority = $task->priority ?? 'medium';
                                    $priorityClass = match ($priority) {
                                        'high' => 'bg-danger-subtle text-danger',
                                        'low' => 'bg-success-subtle text-success',
                                        default => 'bg-warning-subtle text-warning',
                                    };
                                @endphp

                                <div class="kanban-card card p-3 shadow-sm border-0"
                                     draggable="{{ $canManageTasks ? 'true' : 'false' }}"
                                     data-id="{{ $task->id_task }}">

                                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                        <div class="fw-bold text-dark small-title col-9 px-0">
                                            {{ $task->nama_tugas }}
                                        </div>
                                        <span class="badge {{ $priorityClass }} rounded-pill text-capitalize small px-2 py-1 col-3 text-center" style="font-size: 10px;">
                                            {{ $priority }}
                                        </span>
                                    </div>

                                    @if($task->brief)
                                        <p class="text-muted small mb-2 text-truncate-custom">{{ $task->brief }}</p>
                                    @endif

                                    <div class="mb-2">
                                        <span class="badge bg-light text-secondary border small px-2 py-1" style="font-size: 11px;">
                                            <i class="bi bi-tag me-1"></i>{{ $task->division->nama_divisi ?? '-' }}
                                        </span>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center text-muted small mt-2 pt-2 border-top border-light" style="font-size: 11px;">
                                        <div class="text-nowrap">
                                            <i class="bi bi-calendar4-event me-1"></i>
                                            {{ $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('M d') : '-' }}
                                        </div>
                                        
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-circle rounded-circle d-flex align-items-center justify-content-center text-uppercase shadow-sm" 
                                                 title="PIC: {{ $task->assignee->name ?? 'Unassigned' }}">
                                                {{ isset($task->assignee->name) ? substr($task->assignee->name, 0, 2) : '?' }}
                                            </div>
                                            @if($canManageTasks)
                                                <button type="button" class="btn btn-sm btn-light border p-1 rounded-3 ms-1" onclick="openEditModal({{ $task->id_task }})">
                                                    <i class="bi bi-pencil text-primary"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center p-4 text-muted small my-auto bg-white border border-dashed rounded-3">
                                    <i class="bi bi-inbox-fill fs-3 d-block opacity-20 mb-1"></i> Belum ada tugas
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
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 24px;">
                    <div class="modal-body p-4 p-md-5">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <h4 class="fw-bold mb-1 text-dark" id="taskModalTitle">Tambah Task</h4>
                                <p class="text-muted small mb-0">Isi kelengkapan instruksi tugas panitia.</p>
                            </div>
                            <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <form id="taskForm" method="POST" action="/events/{{ $event->id_event }}/tasks">
                            @csrf
                            <input type="hidden" id="task_id">

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted ms-1">Nama Task</label>
                                    <input type="text" name="nama_tugas" id="task_nama_tugas" class="form-control form-control-custom shadow-none" placeholder="Contoh: Booking venue gedung" required>
                                </div>

                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted ms-1">Brief Deskripsi</label>
                                    <textarea name="brief" id="task_brief" rows="3" class="form-control form-control-custom shadow-none" placeholder="Deskripsi pengerjaan tugas..."></textarea>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted ms-1">Divisi Penanggung Jawab</label>
                                    <select name="id_divisi" id="task_id_divisi" class="form-select form-select-custom shadow-none" required>
                                        <option value="">Pilih divisi</option>
                                        @foreach($divisions ?? [] as $division)
                                            <option value="{{ $division->id_divisi }}">{{ $division->nama_divisi }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted ms-1">Tingkat Prioritas</label>
                                    <select name="priority" id="task_priority" class="form-select form-select-custom shadow-none" required>
                                        <option value="low">Low</option>
                                        <option value="medium" selected>Medium</option>
                                        <option value="high">High</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted ms-1">Batas Deadline</label>
                                    <input type="datetime-local" name="deadline" id="task_deadline" class="form-control form-control-custom shadow-none">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted ms-1">Assign Ke Anggota</label>
                                    <select name="assigned_to" id="task_assigned_to" class="form-select form-select-custom shadow-none">
                                        <option value="">- Tidak di-assign (Kosong) -</option>
                                        @foreach($members ?? [] as $member)
                                            <option value="{{ $member->id_user }}">{{ $member->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 d-flex gap-2 justify-content-end mt-4">
                                    <button type="button" class="btn btn-light rounded-pill px-4 py-2" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold" id="taskSubmitBtn">Simpan Task</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Empty Placeholder State --}}
        <div class="card border-0 shadow-sm text-center p-5 bg-white" style="border-radius: 20px; min-height: 400px;">
            <div class="my-auto py-5">
                <i class="bi bi-kanban text-muted opacity-20 d-block mb-3" style="font-size: 70px;"></i>
                <h5 class="fw-bold text-dark">Belum Ada Event Terpilih</h5>
                <p class="text-muted small mx-auto" style="max-width: 420px;">
                    Silakan gunakan menu dropdown di atas untuk memuat data papan aktivitas tugas serta manajemen alur kerja kepanitiaan.
                </p>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Fungsi pengarah rute dropdown selector
function switchKanbanEvent(eventId) {
    if (eventId) {
        window.location.href = '/tasks?event_id=' + eventId;
    } else {
        window.location.href = '/tasks';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('taskModal');
    if (!modalEl) return; // Hentikan script jika event belum dipilih

    const taskModal = new bootstrap.Modal(modalEl);
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
            column.addEventListener('dragover', (e) => { e.preventDefault(); column.classList.add('drag-over'); });
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
                        body: JSON.stringify({ status })
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

    window.openCreateTaskModal = function () {
        form.reset();
        form.action = '/events/{{ $event->id_event ?? "" }}/tasks';
        taskIdInput.value = '';
        title.textContent = 'Tambah Task';
        submitBtn.textContent = 'Simpan Task';
        taskModal.show();
    };

    window.openEditModal = async function (taskId) {
        try {
            const response = await fetch(`/tasks/${taskId}`, {
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
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
            
            if(task.deadline) {
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