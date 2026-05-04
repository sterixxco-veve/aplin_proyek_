@php
    $todo = $tasks->where('status', 'todo');
    $progressTasks = $tasks->where('status', 'progress');
    $done = $tasks->where('status', 'done');
@endphp

<style>
    .kanban-board {
        display: flex;
        gap: 24px;
        align-items: flex-start;
        overflow-x: auto;
        padding: 5px;
    }

    .kanban-column {
        flex: 1;
        min-width: 320px;
        background-color: #f1f3f4; /* Warna abu-abu latar kolom */
        border-radius: 20px;
        padding: 20px;
        min-height: 600px;
    }

    .kanban-column h4 {
        font-weight: 700;
        font-size: 0.85rem;
        color: #5f6368;
        letter-spacing: 0.5px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 5px;
    }

    .kanban-item {
        background: white;
        padding: 18px;
        margin-bottom: 16px;
        border-radius: 16px;
        border: 1px solid transparent;
        transition: transform 0.2s, box-shadow 0.2s;
        cursor: grab;
        position: relative;
    }

    .kanban-item:hover {
        transform: translateY(-2px);
        transition: 0.2s;
    }

    .kanban-item strong {
        display: block;
        font-size: 1rem;
        color: #202124;
        margin-bottom: 12px;
    }

    .task-meta {
        display: flex;
        flex-direction: column;
        gap: 8px;
        font-size: 0.75rem;
        color: #70757a;
    }

    .meta-row {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .meta-row i {
        font-size: 1rem;
        color: #1a73e8;
    }

    .btn-delete-task {
        position: absolute;
        top: 15px;
        right: 15px;
        border: none;
        background: #f1f3f4;
        color: #bdc1c6;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        transition: 0.2s;
    }

    .btn-delete-task:hover {
        background: #d93025;
        color: white;
    }

    /* Indikator Status Warna */
    .dot {
        height: 8px;
        width: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
    }
</style>
@php
    $todo = $tasks->where('status', 'todo');
    $progressTasks = $tasks->where('status', 'progress');
    $done = $tasks->where('status', 'done');
@endphp

<div id="kanban-board" style="display:flex; gap:20px;">

    @foreach([
        'todo' => $todo,
        'progress' => $progressTasks,
        'done' => $done
    ] as $status => $list)

    <div class="kanban-column"
         data-status="{{ $status }}"
         style="flex:1; background:#f4f4f4; padding:15px; border-radius:10px; min-height:300px;">

        <h4>{{ ucfirst($status) }}</h4>

        @foreach($list as $task)
            <div class="kanban-item"
                 draggable="true"
                 data-id="{{ $task->id_task }}"
                 data-task='@json($task)'
                 style="background:white; padding:12px; margin-bottom:10px; border-radius:10px; cursor:grab; box-shadow:0 2px 6px rgba(0,0,0,0.05);">

                <div class="d-flex justify-content-between align-items-start">

                    <div>
                        <strong>{{ $task->nama_tugas }}</strong><br>

                        <small class="text-muted">
                            👤 {{ $task->assignee->name ?? '-' }}
                        </small><br>

                        <small class="text-muted">
                            ⏰ {{ $task->deadline ?? '-' }}
                        </small>
                    </div>

                    {{-- EDIT BUTTON --}}
                    <button class="btn btn-sm btn-light edit-task-btn">
                        ✏️
                    </button>

                </div>

            </div>
        @endforeach

    </div>
    @endforeach

</div>

{{-- ========================= --}}
{{-- STYLE --}}
{{-- ========================= --}}
<style>
.kanban-item:hover {
    transform: translateY(-2px);
    transition: 0.2s;
}

.kanban-item {
    cursor: grab;
}

.edit-task-btn {
    cursor: pointer;
}
</style>

{{-- ========================= --}}
{{-- SCRIPT --}}
{{-- ========================= --}}
<script>
const BASE_URL = "{{ url('/') }}";

// =========================
// DRAG & DROP
// =========================
function initDragAndDrop() {

    document.querySelectorAll('.kanban-item').forEach(item => {

        item.addEventListener('dragstart', (e) => {
            e.dataTransfer.setData('taskId', item.dataset.id);
        });

    });

    document.querySelectorAll('.kanban-column').forEach(column => {

        column.addEventListener('dragover', e => e.preventDefault());

        column.addEventListener('drop', async (e) => {
            e.preventDefault();

            const taskId = e.dataTransfer.getData('taskId');
            const newStatus = column.dataset.status;

            const draggedItem = document.querySelector(`[data-id='${taskId}']`);
            if (!draggedItem) return;

            column.appendChild(draggedItem);

            try {
                await fetch(`${BASE_URL}/tasks/${taskId}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ status: newStatus })
                });
            } catch (err) {
                alert('Gagal update status');
                console.error(err);
            }
        });

    });
}

// =========================
// EDIT TASK CLICK (FIXED)
// =========================
document.addEventListener('click', function(e) {

    const btn = e.target.closest('.edit-task-btn');
    if (!btn) return;

    const item = btn.closest('.kanban-item');
    const task = JSON.parse(item.dataset.task);

    document.getElementById('edit_task_id').value = task.id_task;
    document.getElementById('edit_nama').value = task.nama_tugas;
    document.getElementById('edit_deadline').value = task.deadline ?? '';

    const modal = new bootstrap.Modal(document.getElementById('editTaskModal'));
    modal.show();
});

// =========================
// INIT
// =========================
initDragAndDrop();
</script>