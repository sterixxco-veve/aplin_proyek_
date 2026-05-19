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
            <button class="tab-btn" data-tab="documents">Documents</button>
            <button class="tab-btn" data-tab="partners">Partners</button>
            <button class="tab-btn" data-tab="certificates">Certificates</button>
            <button class="tab-btn" data-tab="committee">Committee</button>
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

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Tambah Committee</h6>

                        <form method="POST" action="/events/{{ $event->id_event }}/assign" class="row g-3">
                            @csrf

                            <div class="col-md-4">
                                <label class="form-label small text-muted">Member</label>
                                <select name="id_user" class="form-select" required>
                                    <option value="">Pilih member</option>
                                    @forelse($availableMembers as $member)
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
                                    @foreach($divisions as $division)
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

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Tambah Banyak Committee</h6>
                        <p class="text-muted small mb-3">Pilih lebih dari satu member untuk dimasukkan ke committee dengan divisi dan jabatan yang sama.</p>

                        <form method="POST" action="/events/{{ $event->id_event }}/assign-bulk" class="row g-3">
                            @csrf

                            <div class="col-md-5">
                                <label class="form-label small text-muted">Member</label>
                                <select name="id_users[]" class="form-select" multiple size="6" required>
                                    @forelse($availableMembers as $member)
                                        <option value="{{ $member->id_user }}">{{ $member->name }} ({{ $member->email }})</option>
                                    @empty
                                        <option value="" disabled>Tidak ada member tersedia</option>
                                    @endforelse
                                </select>
                                <small class="text-muted d-block mt-2">Gunakan Ctrl / Cmd untuk pilih banyak member.</small>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small text-muted">Divisi</label>
                                <select name="id_divisi" class="form-select" required>
                                    <option value="">Pilih divisi</option>
                                    @foreach($divisions as $division)
                                        <option value="{{ $division->id_divisi }}">{{ $division->nama_divisi }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small text-muted">Jabatan</label>
                                <select name="jabatan" class="form-select" required>
                                    <option value="">Pilih jabatan</option>
                                    <option value="koordinator">Koordinator</option>
                                    <option value="anggota">Anggota</option>
                                </select>
                            </div>

                            <div class="col-md-2 d-flex align-items-end">
                                <button class="btn btn-outline-primary w-100">Tambah Banyak</button>
                            </div>
                        </form>
                    </div>
                </div>

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
                                            <td colspan="4" class="text-muted p-4">Belum ada committee di event ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
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

    const activeTab = new URLSearchParams(window.location.search).get('tab');
    if (activeTab) {
        const target = document.querySelector(`.tab-btn[data-tab="${activeTab}"]`);
        if (target) {
            target.click();
        }
    }

});
</script>

@endsection