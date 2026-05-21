@extends('layouts.app')

@section('content')
<div class="container pb-5">

    {{-- ========================= --}}
    {{-- HEADER EVENT --}}
    {{-- ========================= --}}
    <div class="card p-4 mb-4 border-0 shadow-sm" style="border-radius: 20px;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1">{{ $event->nama_event }}</h2>
                <span class="badge rounded-pill px-3 py-2" style="background:#fff3cd; color:#856404;">
                    Planning
                </span>

                <div class="d-flex gap-4 mt-3 text-muted small flex-wrap">
                    <div><i class="bi bi-calendar-event me-1"></i> {{ \Carbon\Carbon::parse($event->tgl_mulai)->format('M d, Y') }}</div>
                    <div><i class="bi bi-geo-alt me-1"></i> {{ $event->organization->nama_org ?? 'N/A' }}</div>
                    <div><i class="bi bi-people me-1"></i> {{ $event->committees->count() }} members</div>
                    <div><i class="bi bi-cash me-1"></i> Rp {{ number_format($event->financial_summary['total_budget'] ?? 0) }}</div>
                </div>
            </div>

            <a href="/events/{{ $event->id_event }}/edit" class="btn btn-primary px-4 rounded-pill">
                Edit Event
            </a>
        </div>
    </div>

    {{-- ========================= --}}
    {{-- MAIN CONTENT (TABS FRAMEWORK) --}}
    {{-- ========================= --}}
    <div class="card p-4 border-0 shadow-sm" style="border-radius: 20px;">

        {{-- TAB NAVIGASI --}}
        <div class="d-flex gap-4 border-bottom mb-4 overflow-auto pb-2" style="white-space: nowrap;">
            <button class="tab-btn active" data-tab="overview">Overview</button>
            <button class="tab-btn" data-tab="rundown">Rundown</button>
            <button class="tab-btn" data-tab="budget">Budget</button>
            <button class="tab-btn" data-tab="documents">Documents</button>
            <button class="tab-btn" data-tab="partners">Partners</button>
            <button class="tab-btn" data-tab="certificates">Certificates</button>
            <button class="tab-btn" data-tab="committee">Committee</button>
            <button class="tab-btn" data-tab="tasks">Tasks</button>
        </div>

        {{-- TAB CONTENT PLACEMENT --}}
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

            <div id="documents" class="tab-content d-none">
                @include('events.partials.documents')
            </div>

            <div id="partners" class="tab-content d-none">
                @include('events.partials.partners')
            </div>

            <div id="certificates" class="tab-content d-none">
                @include('events.partials.certificates')
            </div>

            <div id="committee" class="tab-content d-none">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">Committee</h5>
                        <small class="text-muted">Tambah atau hapus panitia yang terlibat di event ini.</small>
                    </div>
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                        {{ $event->committees->count() }} committee
                    </span>
                </div>

                {{-- Form Tambah Committee --}}
                <div class="card border-0 shadow-sm mb-4 bg-light">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Tambah Committee</h6>
                        <form method="POST" action="/events/{{ $event->id_event }}/assign" class="row g-3">
                            @csrf
                            <div class="col-md-4">
                                <label class="form-label small text-muted">Member</label>
                                <select name="id_user" class="form-select" required>
                                    <option value="">Pilih member</option>
                                    @forelse($availableMembers ?? [] as $member)
                                        <option value="{{ $member->id_user }}">{{ $member->name }} ({{ $member->email }})</option>
                                    @empty
                                        <option value="">Tidak ada member tersedia</option>
                                    @endforelse
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small text-muted">Divisi</label>
                                <select name="id_divisi" class="form-select" required>
                                    <option value="">Pilih divisi</option>
                                    @foreach($divisions ?? [] as $division)
                                        <option value="{{ $division->id_divisi }}">{{ $division->nama_divisi }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small text-muted">Jabatan</label>
                                <select name="jabatan" class="form-select" required>
                                    <option value="">Pilih jabatan</option>
                                    <option value="koordinator">Koordinator</option>
                                    <option value="anggota">Anggota</option>
                                </select>
                            </div>

                            <div class="col-md-2 d-flex align-items-end">
                                <button class="btn btn-primary w-100">Tambah</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Tabel Daftar Committee --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="small text-muted text-uppercase">
                                        <th class="ps-4">Nama</th>
                                        <th>Divisi</th>
                                        <th>Jabatan</th>
                                        <th class="text-end pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($event->committees as $committee)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-semibold">{{ $committee->user->name ?? '-' }}</div>
                                                <small class="text-muted">{{ $committee->user->email ?? '-' }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border">{{ $committee->division->nama_divisi ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $committee->jabatan ? ucfirst($committee->jabatan) : '-' }}</span>
                                            </td>
                                            <td class="text-end pe-4">
                                                @if($event->canManageCommitteeBy(auth()->user()) && $event->committees->count() > 1)
                                                    <form method="POST" action="/events/{{ $event->id_event }}/committees/{{ $committee->id_comm }}" onsubmit="return confirm('Hapus committee ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                                    </form>
                                                @else
                                                    <span class="text-muted small">Minimal 1 committee</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-muted p-4 text-center">Belum ada committee di event ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 🌟 SUB-TAB KHUSUS TASKS KANBAN BOARD --}}
            <div id="tasks" class="tab-content d-none">
                @include('tasks.kanban', ['canManageTasks' => $canManageTasks ?? false])
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Logika Switcher Menu Tab
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            document.querySelectorAll('.tab-content').forEach(c => c.classList.add('d-none'));
            document.getElementById(this.dataset.tab).classList.remove('d-none');
        });
    });

    // Otomatis deteksi tab aktif lewat parameter URL (?tab=tasks)
    const activeTab = new URLSearchParams(window.location.search).get('tab');
    if (activeTab) {
        const target = document.querySelector(`.tab-btn[data-tab="${activeTab}"]`);
        if (target) {
            target.click();
        }
    }

    // KANBAN FUNCTIONALITY
    const modalEl = document.getElementById('taskModal');
    if (modalEl) {
        const taskModal = new bootstrap.Modal(modalEl);
        const form = document.getElementById('taskForm');
        const title = document.getElementById('taskModalTitle');
        const submitBtn = document.getElementById('taskSubmitBtn');
        const taskIdInput = document.getElementById('task_id');
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const canManageTasks = @json($canManageTasks ?? false);

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
    }
});
</script>

<style>
.tab-btn {
    background: none;
    border: none;
    padding-bottom: 10px;
    color: #6c757d;
    font-weight: 500;
    transition: color 0.2s ease;
}
.tab-btn:hover {
    color: #0d6efd;
}
.tab-btn.active {
    color: #0d6efd;
    font-weight: bold;
    border-bottom: 2px solid #0d6efd;
}

/* Kanban Board Styles */
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
@endsection