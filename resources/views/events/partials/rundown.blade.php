@php
    $rundownItems = $event->rundownItems;
    $committeeOptions = $event->committees;
@endphp

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 d-flex align-items-center gap-2" role="alert">
        <i class="bi bi-check-circle-fill text-success"></i>
        <span>{{ session('success') }}</span>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 d-flex align-items-center gap-2" role="alert">
        <i class="bi bi-exclamation-circle-fill text-danger"></i>
        <span>{{ session('error') }}</span>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 d-flex align-items-start gap-2" role="alert">
        <i class="bi bi-exclamation-triangle-fill text-danger mt-1"></i>
        <div>
            <div class="fw-bold small">Ada kesalahan pada input rundown:</div>
            <ul class="mb-0 ps-3 mt-1">
                @foreach($errors->all() as $error)
                    <li class="small">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="d-flex justify-content-between align-items-start mb-4 gap-3 flex-wrap">
    <div>
        <h5 class="fw-bold mb-1">Rundown</h5>
        <p class="text-muted small mb-0">Susun alur acara per sesi supaya urutan pelaksanaan jelas.</p>
    </div>

    <div class="d-flex align-items-center gap-2 flex-wrap">
        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
            {{ $rundownItems->count() }} item
        </span>

        @if($canManageRundown)
            <div class="btn-group" role="group">
                <button class="btn btn-primary add-main-btn" data-bs-toggle="modal" data-bs-target="#rundownModal">
                    <i class="bi bi-plus-lg"></i> Tambah Rundown
                </button>
                <a href="/events/{{ $event->id_event }}/rundown/export" class="btn btn-outline-secondary" title="Download Excel">
                    <i class="bi bi-download"></i> Export
                </a>
                <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#importRundownModal" title="Upload Excel">
                    <i class="bi bi-upload"></i> Import
                </button>
            </div>
        @endif
    </div>
</div>
<!-- 
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="p-3 bg-light rounded-3">
                    <small class="text-muted d-block">Total Item</small>
                    <h4 class="mb-0">{{ $rundownItems->count() }}</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-success-subtle rounded-3">
                    <small class="text-muted d-block">Hari Terpakai</small>
                    <h4 class="mb-0">{{ $rundownItems->pluck('day_number')->unique()->count() }}</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-warning-subtle rounded-3">
                    <small class="text-muted d-block">Mode Akses</small>
                    <h4 class="mb-0">{{ $canManageRundown ? 'Edit' : 'Lihat Saja' }}</h4>
                </div>
            </div>
        </div>
    </div>
</div> -->

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <!-- Tabs untuk Hari -->
        @php
            $uniqueDays = $rundownItems->pluck('day_number')->unique()->sort()->values();
        @endphp

        @if($uniqueDays->count() > 0)
            <ul class="nav nav-tabs border-bottom" role="tablist" style="padding: 0 1rem;">
                @foreach($uniqueDays as $day)
                
                    <li class="nav-item" role="presentation">
                        <button 
                            class="nav-link {{ $loop->first ? 'active' : '' }}" 
                            id="day-{{ $day }}-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#day-{{ $day }}-content" 
                            type="button" 
                            role="tab" 
                            aria-controls="day-{{ $day }}-content"
                            aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                            Day {{ $day }}
                        </button>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content ignore-parent-tab">
                @foreach($uniqueDays as $day)
                    @php
                        $dayItems = $rundownItems->where('day_number', $day);
                    @endphp
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="day-{{ $day }}-content" role="tabpanel" aria-labelledby="day-{{ $day }}-tab">
                        <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                            <h6 class="mb-0 fw-bold">Day {{ $day }}</h6>
                            @if($canManageRundown)
                                <button 
                                    type="button"
                                    class="btn btn-sm btn-primary add-to-day-btn"
                                    data-day="{{ $day }}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#rundownModal">
                                    <i class="bi bi-plus-lg"></i> Tambah
                                </button>
                            @endif
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="small text-muted text-uppercase">
                                        <th class="ps-4">Sesi</th>
                                        <th>Waktu</th>
                                        <th>Kegiatan</th>
                                        <th>Penanggung Jawab</th>
                                        @if($canManageRundown)
                                            <th class="text-end pe-4">Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($dayItems as $item)
                                        <tr>
                                            <td class="ps-4">
                                                @if($item->session_group)
                                                    <span class="badge bg-light text-dark border">{{ $item->session_group }}</span>
                                                @else
                                                    <span class="text-muted small">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="fw-semibold">
                                                    {{ \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i') }}
                                                    -
                                                    {{ \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $item->kegiatan }}</div>
                                                <small class="text-muted d-block">Item rundown event</small>
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $item->assignedCommittee?->user?->name ?? '-' }}</div>
                                                <small class="text-muted d-block">
                                                    {{ $item->assignedCommittee?->division?->nama_divisi ?? '-' }}
                                                    @if($item->assignedCommittee?->jabatan)
                                                        • {{ ucfirst($item->assignedCommittee->jabatan) }}
                                                    @endif
                                                </small>
                                            </td>
                                            @if($canManageRundown)
                                                <td class="text-end pe-4">
                                                    <div class="d-flex justify-content-end gap-2">
                                                        <button
                                                            type="button"
                                                            class="btn btn-sm btn-outline-primary rundown-edit-btn"
                                                            data-id="{{ $item->id_rundown }}"
                                                            data-day="{{ $item->day_number }}"
                                                            data-session="{{ e($item->session_group) }}"
                                                            data-start="{{ \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i') }}"
                                                            data-end="{{ \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i') }}"
                                                            data-kegiatan="{{ e($item->kegiatan) }}"
                                                            data-assigned="{{ $item->assigned_to }}">
                                                            Edit
                                                        </button>

                                                        <form method="POST"
                                                              action="/events/{{ $event->id_event }}/rundown/{{ $item->id_rundown }}"
                                                              onsubmit="return confirm('Hapus rundown ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ $canManageRundown ? 5 : 4 }}" class="p-4 text-muted">
                                                Belum ada rundown untuk hari ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-4 text-muted text-center">
                Belum ada rundown untuk event ini.
            </div>
        @endif
    </div>
</div>

@if($canManageRundown)
    <div class="modal fade" id="rundownModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-body p-4">
                    <h5 class="fw-bold mb-3">Tambah Rundown</h5>

                    <form method="POST" action="/events/{{ $event->id_event }}/rundown">
                        @csrf

                        <div class="row g-3">
                            <div class="col-4">
                                <label class="form-label small text-muted">Hari</label>
                                <input type="number" id="rundown_day_input" name="day_number" class="form-control" min="1">
                            </div>
                            <div class="col-8">
                                <label class="form-label small text-muted">Group Sesi</label>
                                <input type="text" name="session_group" class="form-control" placeholder="Contoh: Pembukaan">
                            </div>
                            <div class="col-6">
                                <label class="form-label small text-muted">Waktu Mulai</label>
                                <input type="time" name="waktu_mulai" class="form-control">
                            </div>
                            <div class="col-6">
                                <label class="form-label small text-muted">Waktu Selesai</label>
                                <input type="time" name="waktu_selesai" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted">Kegiatan</label>
                                <input type="text" name="kegiatan" class="form-control" placeholder="Contoh: Registrasi peserta">
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted">Penanggung Jawab</label>
                                <select name="assigned_to" class="form-select">
                                    <option value="">Pilih committee</option>
                                    @foreach($committeeOptions as $committee)
                                        <option value="{{ $committee->id_comm }}">
                                            {{ $committee->user->name ?? '-' }} - {{ $committee->division->nama_divisi ?? '-' }} ({{ ucfirst($committee->jabatan) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="button" class="btn btn-light flex-fill" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary flex-fill">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editRundownModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-body p-4">
                    <h5 class="fw-bold mb-3">Edit Rundown</h5>

                    <form id="editRundownForm" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-4">
                                <label class="form-label small text-muted">Hari</label>
                                <input type="number" name="day_number" id="edit_rundown_day" class="form-control" min="1">
                            </div>
                            <div class="col-8">
                                <label class="form-label small text-muted">Group Sesi</label>
                                <input type="text" name="session_group" id="edit_rundown_session" class="form-control">
                            </div>
                            <div class="col-6">
                                <label class="form-label small text-muted">Waktu Mulai</label>
                                <input type="time" name="waktu_mulai" id="edit_rundown_start" class="form-control">
                            </div>
                            <div class="col-6">
                                <label class="form-label small text-muted">Waktu Selesai</label>
                                <input type="time" name="waktu_selesai" id="edit_rundown_end" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted">Kegiatan</label>
                                <input type="text" name="kegiatan" id="edit_rundown_kegiatan" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted">Penanggung Jawab</label>
                                <select name="assigned_to" id="edit_rundown_assigned" class="form-select">
                                    <option value="">Pilih committee</option>
                                    @foreach($committeeOptions as $committee)
                                        <option value="{{ $committee->id_comm }}">
                                            {{ $committee->user->name ?? '-' }} - {{ $committee->division->nama_divisi ?? '-' }} ({{ ucfirst($committee->jabatan) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="button" class="btn btn-light flex-fill" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary flex-fill">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Rundown Modal -->
    <div class="modal fade" id="importRundownModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-body p-4">
                    <h5 class="fw-bold mb-3">Import Rundown dari Excel</h5>

                    <form method="POST" action="/events/{{ $event->id_event }}/rundown/import" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label small text-muted">Pilih File Excel</label>
                            <div class="d-flex gap-2">
                                <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv">
                                <a href="/rundown/template" class="btn btn-outline-secondary" download title="Download template">
                                    <i class="bi bi-download"></i>
                                </a>
                            </div>
                            <small class="text-muted d-block mt-1">Format yang didukung: .xlsx, .xls, .csv</small>
                        </div>

                        <div class="alert alert-info small" role="alert">
                            <strong>Format File:</strong> Kolom harus berisi: Hari, Sesi, Waktu Mulai, Waktu Selesai, Kegiatan, Penanggung Jawab (nama user)
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="button" class="btn btn-light flex-fill" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary flex-fill">Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

@if($canManageRundown)
    <script>
        // Handle add-to-day buttons (from tabs)
        document.querySelectorAll('.add-to-day-btn').forEach((btn) => {
            btn.addEventListener('click', function () {
                const dayNumber = this.dataset.day;
                const dayInput = document.getElementById('rundown_day_input');
                dayInput.value = dayNumber;
                dayInput.readOnly = true;
                dayInput.style.backgroundColor = '#f0f0f0';
            });
        });

        // Handle main add button (for new days)
        document.querySelector('.add-main-btn')?.addEventListener('click', function() {
            const dayInput = document.getElementById('rundown_day_input');
            dayInput.value = '';
            dayInput.readOnly = false;
            dayInput.style.backgroundColor = '';
        });

        // Reset when modal is closed
        document.getElementById('rundownModal')?.addEventListener('hidden.bs.modal', function() {
            const dayInput = document.getElementById('rundown_day_input');
            dayInput.readOnly = false;
            dayInput.style.backgroundColor = '';
        });

        document.querySelectorAll('.rundown-edit-btn').forEach((btn) => {
            btn.addEventListener('click', function () {
                const form = document.getElementById('editRundownForm');
                form.action = `/events/{{ $event->id_event }}/rundown/${this.dataset.id}`;

                document.getElementById('edit_rundown_day').value = this.dataset.day;
                document.getElementById('edit_rundown_session').value = this.dataset.session || '';
                document.getElementById('edit_rundown_start').value = this.dataset.start;
                document.getElementById('edit_rundown_end').value = this.dataset.end;
                document.getElementById('edit_rundown_kegiatan').value = this.dataset.kegiatan;
                document.getElementById('edit_rundown_assigned').value = this.dataset.assigned || '';

                const modal = new bootstrap.Modal(document.getElementById('editRundownModal'));
                modal.show();
            });
        });

        document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function () {
                window.dispatchEvent(new Event('resize'));
            });
        });
    </script>
@endif
