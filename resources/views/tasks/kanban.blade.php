{{-- Bagian Atas Internal Tab Tasks --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h5 class="fw-bold mb-1">Task Management Board</h5>
        <div class="text-muted small">
            @if($canManageTasks)
                Drag task ke kolom lain untuk mengubah status pengerjaan secara berkala.
            @else
                Mode view-only. Drag task butuh akses khusus panitia event.
            @endif
        </div>
    </div>

    @if($canManageTasks)
        <button type="button" class="btn btn-primary rounded-pill px-4 btn-sm" onclick="openCreateTaskModal()">
            + Add Task
        </button>
    @endif
</div>

{{-- Papan Kanban Row --}}
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
            <div class="kanban-wrapper p-3 bg-light rounded-3 border">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-{{ $col['color'] }} mb-0">
                        {{ $col['title'] }}
                    </h6>
                    <span class="badge bg-{{ $col['color'] }} bg-opacity-10 text-{{ $col['color'] }}">
                        {{ $tasks->where('status', $status)->count() }}
                    </span>
                </div>

                <div class="kanban-column" data-status="{{ $status }}" style="min-height: 400px;">
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

                        <div class="kanban-card card p-3 mb-3 shadow-sm border-0"
                             draggable="{{ $canManageTasks ? 'true' : 'false' }}"
                             data-id="{{ $task->id_task }}">

                            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                <div class="fw-semibold text-dark small-title">
                                    {{ $task->nama_tugas }}
                                </div>
                                <span class="badge {{ $priorityClass }} rounded-pill small" style="font-size: 10px;">{{ $priorityLabel }}</span>
                            </div>

                            @if($task->brief)
                                <p class="text-muted small mb-2 text-truncate-custom">{{ $task->brief }}</p>
                            @endif

                            <div class="mb-2">
                                <span class="badge bg-light text-dark border" style="font-size: 11px;">
                                    {{ $task->division->nama_divisi ?? '-' }}
                                </span>
                            </div>

                            <div class="d-flex justify-content-between text-muted small mt-2 gap-2" style="font-size: 11px;">
                                <div class="text-nowrap">
                                    <i class="bi bi-calendar me-1"></i>
                                    {{ $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('M d') : '-' }}
                                </div>
                                <div class="text-nowrap text-end">
                                    <i class="bi bi-person me-1"></i>
                                    {{ $task->assignee->name ?? '-' }}
                                </div>
                            </div>

                            <div class="mt-2 pt-2 border-top text-end">
                                <button type="button" class="btn btn-sm btn-light py-0 px-2 border" onclick="openEditModal({{ $task->id_task }})">
                                    ✏️
                                </button>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small text-center py-3 bg-white rounded-3 border border-dashed">No tasks</p>
                    @endforelse
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- MODAL TAMBAH TASK --}}
<div class="modal fade" id="taskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-body p-4 p-md-5">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h5 class="fw-bold mb-1" id="taskModalTitle">Tambah Task</h5>
                        <p class="text-muted small mb-0">Isi detail tugas operasional event.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="taskForm" method="POST" action="/events/{{ $event->id_event }}/tasks">
                    @csrf
                    <input type="hidden" id="task_id">

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">Nama Task</label>
                            <input type="text" name="nama_tugas" id="task_nama_tugas" class="form-control bg-light border-0 py-2.5 rounded-3" placeholder="Contoh: Booking venue">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">Brief</label>
                            <textarea name="brief" id="task_brief" rows="3" class="form-control bg-light border-0 py-2.5 rounded-3" placeholder="Deskripsi singkat tugas"></textarea>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Divisi</label>
                            <select name="id_divisi" id="task_id_divisi" class="form-select bg-light border-0 py-2.5 rounded-3">
                                <option value="">Pilih divisi</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id_divisi }}">{{ $division->nama_divisi }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Priority</label>
                            <select name="priority" id="task_priority" class="form-select bg-light border-0 py-2.5 rounded-3">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Deadline</label>
                            <input type="datetime-local" name="deadline" id="task_deadline" class="form-control bg-light border-0 py-2.5 rounded-3">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Assign To</label>
                            <select name="assigned_to" id="task_assigned_to" class="form-select bg-light border-0 py-2.5 rounded-3">
                                <option value="">- Tidak di-assign -</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id_user }}">{{ $member->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 d-flex gap-2 justify-content-end mt-3">
                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold" id="taskSubmitBtn">Simpan Task</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.kanban-wrapper { min-height: 450px; }
.kanban-card { cursor: grab; transition: transform 0.2s ease, box-shadow 0.2s ease; border-radius: 12px; }
.kanban-card:hover { transform: translateY(-3px); box-shadow: 0 6px 12px rgba(0,0,0,0.08)!important; }
.kanban-card.dragging { opacity: 0.5; cursor: grabbing; }
.kanban-column.drag-over { background: rgba(13, 110, 253, 0.04); border-radius: 8px; }
.text-truncate-custom { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('taskModal');
    const taskModal = new bootstrap.Modal(modalEl);
    const form = document.getElementById('taskForm');
    const title = document.getElementById('taskModalTitle');
    const submitBtn = document.getElementById('taskSubmitBtn');
    const taskIdInput = document.getElementById('task_id');
    const csrfToken = document.querySelector('input[name="_token"]').value;
    const canManageTasks = @json($canManageTasks);

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
                        window.location.search = '?tab=tasks';
                    } else {
                        alert('Gagal update status task.');
                    }
                } catch (error) {
                    alert('Terjadi kesalahan koneksi.');
                }
            });
        });
    }

    window.openCreateTaskModal = function () {
        form.reset();
        form.action = '/events/{{ $event->id_event }}/tasks';
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
            alert('Gagal membuka data edit.');
        }
    };
});
</script>
@endpush