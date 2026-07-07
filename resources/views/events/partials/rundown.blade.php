@php
    $rundownItems = $event->rundownItems;
    $committeeOptions = $event->committees;
@endphp

{{-- ALERT NOTIFIKASI SUCCESS & ERROR --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 d-flex align-items-center gap-2 py-2 px-3 mb-3"
        style="font-size: 0.85rem; background-color: #f0fdf4; color: #166534;" role="alert">
        <i class="bi bi-check-circle-fill text-success"></i>
        <span>{{ session('success') }}</span>
        <button type="button" class="btn-close ms-auto shadow-none small" data-bs-dismiss="alert" aria-label="Close"
            style="padding: 0.8rem; font-size: 0.75rem;"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 d-flex align-items-center gap-2 py-2 px-3 mb-3"
        style="font-size: 0.85rem; background-color: #fef2f2; color: #991b1b;" role="alert">
        <i class="bi bi-exclamation-circle-fill text-danger"></i>
        <span>{{ session('error') }}</span>
        <button type="button" class="btn-close ms-auto shadow-none small" data-bs-dismiss="alert" aria-label="Close"
            style="padding: 0.8rem; font-size: 0.75rem;"></button>
    </div>
@endif

{{-- TITLE & ACTION HEADER --}}
<div class="d-flex justify-content-between align-items-center mb-4 gap-3 flex-wrap">
    <div>
        <h5 class="fw-bold text-dark mb-0" style="font-size: 1.15rem;">Rundown</h5>
        <p class="text-muted small mb-0" style="font-size: 0.825rem;">Susun alur acara per sesi supaya urutan
            pelaksanaan jelas.</p>
    </div>

    <div class="d-flex align-items-center gap-2 flex-wrap">
        <span class="badge bg-primary-subtle text-primary rounded-pill px-25 py-15 fw-bold" style="font-size: 0.8rem;">
            {{ $rundownItems->count() }} Item
        </span>

        @if ($canManageRundown)
            <div class="btn-group shadow-sm" role="group" style="border-radius: 10px; overflow: hidden;">
                <button class="btn btn-primary add-main-btn fw-semibold small" data-bs-toggle="modal"
                    data-bs-target="#rundownModal"
                    style="background-color: #4f46e5; border: none; font-size: 0.85rem; padding: 8px 14px;">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Rundown
                </button>
                <a href="/events/{{ $event->id_event }}/rundown/export"
                    class="btn btn-outline-secondary bg-white text-secondary small" title="Download Excel"
                    style="border: 1px solid #cbd5e1; font-size: 0.85rem; padding: 8px 14px;">
                    <i class="bi bi-download me-1"></i> Export
                </a>
                <button class="btn btn-outline-secondary bg-white text-secondary small" data-bs-toggle="modal"
                    data-bs-target="#importRundownModal" title="Upload Excel"
                    style="border: 1px solid #cbd5e1; border-left: none; font-size: 0.85rem; padding: 8px 14px;">
                    <i class="bi bi-upload me-1"></i> Import
                </button>
            </div>
        @endif
    </div>
</div>

{{-- BOARD UTAMA RUNDOWN PER HARI --}}
<div class="card border-0 shadow-none">
    <div class="card-body p-0">
        @php
            $uniqueDays = $rundownItems->pluck('day_number')->unique()->sort()->values();
        @endphp

        @if ($uniqueDays->count() > 0)
            {{-- Navigasi Tab Internal Hari --}}
            <ul class="nav nav-tabs custom-rundown-tabs border-bottom mb-3" role="tablist">
                @foreach ($uniqueDays as $day)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold {{ $loop->first ? 'active' : '' }}"
                            id="day-{{ $day }}-tab" data-bs-toggle="tab"
                            data-bs-target="#day-{{ $day }}-content" type="button" role="tab"
                            aria-controls="day-{{ $day }}-content"
                            aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                            style="font-size: 0.875rem; padding: 8px 16px;">
                            Day {{ $day }}
                        </button>
                    </li>
                @endforeach
            </ul>

            {{-- Isi Konten Tabel Per Hari --}}
            <div class="tab-content ignore-parent-tab">
                @foreach ($uniqueDays as $day)
                    @php
                        $dayItems = $rundownItems->where('day_number', $day);
                    @endphp
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                        id="day-{{ $day }}-content" role="tabpanel"
                        aria-labelledby="day-{{ $day }}-tab">

                        <div class="card border style-table-card">
                            <div
                                class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-2 px-3">
                                <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;"><i
                                        class="bi bi-calendar-check me-1.5 text-primary"></i> Agenda Hari
                                    Ke-{{ $day }}</h6>
                                @if ($canManageRundown)
                                    <button type="button"
                                        class="btn btn-sm btn-link text-primary p-0 text-decoration-none fw-semibold add-to-day-btn"
                                        data-day="{{ $day }}" data-bs-toggle="modal"
                                        data-bs-target="#rundownModal" style="font-size: 0.825rem;">
                                        <i class="bi bi-plus-circle-fill me-1"></i>Tambah Sesi
                                    </button>
                                @endif
                            </div>

                            <div class="table-responsive">
                                <table class="table align-middle mb-0" style="font-size: 0.85rem;">
                                    <thead class="table-light">
                                        <tr class="small text-secondary text-uppercase"
                                            style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                            <th class="ps-3 py-2">Sesi</th>
                                            <th class="py-2">Waktu</th>
                                            <th class="py-2">Kegiatan</th>
                                            <th class="py-2">Penanggung Jawab</th>
                                            @if ($canManageRundown)
                                                <th class="text-end pe-3 py-2">Aksi</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($dayItems as $item)
                                            <tr>
                                                <td class="ps-3 py-25">
                                                    @if ($item->session_group)
                                                        <span class="badge bg-white text-secondary border px-2 py-1"
                                                            style="font-size: 0.725rem;">{{ $item->session_group }}</span>
                                                    @else
                                                        <span class="text-muted small">-</span>
                                                    @endif
                                                </td>
                                                <td class="py-25 text-nowrap fw-semibold text-dark">
                                                    <i class="bi bi-clock me-1 text-primary"
                                                        style="font-size: 0.8rem;"></i>
                                                    {{ \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i') }}
                                                    -
                                                    {{ \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i') }}
                                                </td>
                                                <td class="py-25">
                                                    <div class="fw-bold text-dark">{{ $item->kegiatan }}</div>
                                                </td>
                                                <td class="py-25">
                                                    <div class="fw-semibold text-dark">
                                                        {{ $item->assignedCommittee?->user?->name ?? '-' }}</div>
                                                    <small class="text-muted d-block" style="font-size: 0.75rem;">
                                                        {{ $item->assignedCommittee?->division?->nama_divisi ?? '-' }}
                                                        @if ($item->assignedCommittee?->jabatan)
                                                            • {{ ucfirst($item->assignedCommittee->jabatan) }}
                                                        @endif
                                                    </small>
                                                </td>
                                                @if ($canManageRundown)
                                                    <td class="text-end pe-3 py-25 text-nowrap">
                                                        <div class="d-flex justify-content-end gap-2">
                                                            <button type="button"
                                                                class="btn btn-sm btn-link text-warning p-0 text-decoration-none fw-semibold rundown-edit-btn"
                                                                data-id="{{ $item->id_rundown }}"
                                                                data-day="{{ $item->day_number }}"
                                                                data-session="{{ e($item->session_group) }}"
                                                                data-start="{{ \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i') }}"
                                                                data-end="{{ \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i') }}"
                                                                data-kegiatan="{{ e($item->kegiatan) }}"
                                                                data-assigned="{{ $item->assigned_to }}"
                                                                style="font-size: 0.8rem;">
                                                                Edit
                                                            </button>

                                                            <form method="POST"
                                                                action="/events/{{ $event->id_event }}/rundown/{{ $item->id_rundown }}"
                                                                onsubmit="return confirm('Hapus rundown ini?')"
                                                                class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button
                                                                    class="btn btn-sm btn-link text-danger p-0 text-decoration-none fw-semibold"
                                                                    style="font-size: 0.8rem;">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                @endif
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ $canManageRundown ? 5 : 4 }}"
                                                    class="text-muted p-4 text-center small">
                                                    Belum ada alur rundown untuk hari ini.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty Placeholder State --}}
            <div class="text-center py-5 border rounded-4 bg-light bg-opacity-50"
                style="border-style: dashed !important; border-color: #cbd5e1 !important;">
                <i class="bi bi-calendar-x text-muted opacity-30 d-block mb-2" style="font-size: 2.5rem;"></i>
                <p class="text-muted m-0 small fw-medium">Belum ada alur rundown yang disusun untuk event ini.</p>
            </div>
        @endif
    </div>
</div>

{{-- ========================= --}}
{{-- MODAL TAMBAH RUNDOWN ITEM --}}
{{-- ========================= --}}
@if ($canManageRundown)
    <div class="modal fade" id="rundownModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="fw-bold text-dark mb-0" style="font-size: 1.15rem; letter-spacing: -0.3px;">
                                Tambah Sesi Rundown</h5>
                            <p class="text-muted small mb-0" style="font-size: 0.8rem;">Masukkan detail alur waktu dan
                                PIC pelaksana.</p>
                        </div>
                        <button type="button" class="btn-close small shadow-none" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <form method="POST" action="/events/{{ $event->id_event }}/rundown">
                        @csrf

                        <div class="row g-2">
                            <div class="col-4">
                                <label class="form-label small fw-semibold text-secondary mb-1">Hari Ke-</label>
                                <input type="number" id="rundown_day_input" name="day_number"
                                    class="form-control form-control-sm @error('day_number') is-invalid @enderror"
                                    min="1" value="{{ old('day_number') }}" placeholder="1">
                                @error('day_number')
                                    <div class="invalid-feedback fw-medium mt-1" style="font-size: 0.75rem;">
                                        {{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-8">
                                <label class="form-label small fw-semibold text-secondary mb-1">Group Sesi /
                                    Blok</label>
                                <input type="text" name="session_group"
                                    class="form-control form-control-sm @error('session_group') is-invalid @enderror"
                                    placeholder="Contoh: Opening / Isoma" value="{{ old('session_group') }}">
                                @error('session_group')
                                    <div class="invalid-feedback fw-medium mt-1" style="font-size: 0.75rem;">
                                        {{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-semibold text-secondary mb-1">Waktu Mulai</label>
                                <input type="time" name="waktu_mulai"
                                    class="form-control form-control-sm @error('waktu_mulai') is-invalid @enderror"
                                    value="{{ old('waktu_mulai') }}">
                                @error('waktu_mulai')
                                    <div class="invalid-feedback fw-medium mt-1" style="font-size: 0.75rem;">
                                        {{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-semibold text-secondary mb-1">Waktu Selesai</label>
                                <input type="time" name="waktu_selesai"
                                    class="form-control form-control-sm @error('waktu_selesai') is-invalid @enderror"
                                    value="{{ old('waktu_selesai') }}">
                                @error('waktu_selesai')
                                    <div class="invalid-feedback fw-medium mt-1" style="font-size: 0.75rem;">
                                        {{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold text-secondary mb-1">Nama Kegiatan</label>
                                <input type="text" name="kegiatan"
                                    class="form-control form-control-sm @error('kegiatan') is-invalid @enderror"
                                    placeholder="Contoh: Registrasi & Re-Check Peserta"
                                    value="{{ old('kegiatan') }}">
                                @error('kegiatan')
                                    <div class="invalid-feedback fw-medium mt-1" style="font-size: 0.75rem;">
                                        {{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold text-secondary mb-1">Penanggung Jawab
                                    (PIC)</label>
                                <select name="assigned_to"
                                    class="form-select form-select-sm @error('assigned_to') is-invalid @enderror">
                                    <option value="">Pilih panitia aktif...</option>
                                    @foreach ($committeeOptions as $committee)
                                        <option value="{{ $committee->id_comm }}"
                                            {{ old('assigned_to') == $committee->id_comm ? 'selected' : '' }}>
                                            {{ $committee->user->name ?? '-' }} -
                                            {{ $committee->division->nama_divisi ?? '-' }}
                                            ({{ ucfirst($committee->jabatan) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('assigned_to')
                                    <div class="invalid-feedback fw-medium mt-1" style="font-size: 0.75rem;">
                                        {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="button" class="btn btn-light btn-sm flex-fill fw-medium"
                                data-bs-dismiss="modal"
                                style="border-radius: 8px; background-color: #f1f5f9;">Batal</button>
                            <button type="submit" class="btn btn-primary btn-sm flex-fill fw-semibold"
                                style="border-radius: 8px; background-color: #4f46e5; border: none;">Simpan
                                Sesi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================= --}}
    {{-- MODAL EDIT RUNDOWN ITEM   --}}
    {{-- ========================= --}}
    <div class="modal fade" id="editRundownModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="fw-bold text-dark mb-0" style="font-size: 1.15rem; letter-spacing: -0.3px;">
                                Edit Sesi Rundown</h5>
                            <p class="text-muted small mb-0" style="font-size: 0.8rem;">Ubah rincian waktu pengerjaan
                                alur agenda acara.</p>
                        </div>
                        <button type="button" class="btn-close small shadow-none" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <form id="editRundownForm" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-2">
                            <div class="col-4">
                                <label class="form-label small fw-semibold text-secondary mb-1">Hari Ke-</label>
                                <input type="number" name="day_number" id="edit_rundown_day"
                                    class="form-control form-control-sm" min="1">
                            </div>
                            <div class="col-8">
                                <label class="form-label small fw-semibold text-secondary mb-1">Group Sesi</label>
                                <input type="text" name="session_group" id="edit_rundown_session"
                                    class="form-control form-control-sm">
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-semibold text-secondary mb-1">Waktu Mulai</label>
                                <input type="time" name="waktu_mulai" id="edit_rundown_start"
                                    class="form-control form-control-sm">
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-semibold text-secondary mb-1">Waktu Selesai</label>
                                <input type="time" name="waktu_selesai" id="edit_rundown_end"
                                    class="form-control form-control-sm">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold text-secondary mb-1">Nama Kegiatan</label>
                                <input type="text" name="kegiatan" id="edit_rundown_kegiatan"
                                    class="form-control form-control-sm">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold text-secondary mb-1">Penanggung Jawab
                                    (PIC)</label>
                                <select name="assigned_to" id="edit_rundown_assigned"
                                    class="form-select form-select-sm">
                                    <option value="">Pilih panitia aktif...</option>
                                    @foreach ($committeeOptions as $committee)
                                        <option value="{{ $committee->id_comm }}">
                                            {{ $committee->user->name ?? '-' }} -
                                            {{ $committee->division->nama_divisi ?? '-' }}
                                            ({{ ucfirst($committee->jabatan) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="button" class="btn btn-light btn-sm flex-fill fw-medium"
                                data-bs-dismiss="modal"
                                style="border-radius: 8px; background-color: #f1f5f9;">Batal</button>
                            <button type="submit" class="btn btn-primary btn-sm flex-fill fw-semibold"
                                style="border-radius: 8px; background-color: #4f46e5; border: none;">Update
                                Sesi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================= --}}
    {{-- MODAL IMPORT EXCEL DATA  --}}
    {{-- ========================= --}}
    <div class="modal fade" id="importRundownModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="fw-bold text-dark mb-0" style="font-size: 1.15rem; letter-spacing: -0.3px;">
                                Import Data via Excel</h5>
                            <p class="text-muted small mb-0" style="font-size: 0.8rem;">Unggah dokumen berformat
                                spreadsheet alur kegiatan.</p>
                        </div>
                        <button type="button" class="btn-close small shadow-none" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <form method="POST" action="/events/{{ $event->id_event }}/rundown/import"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary mb-1">Pilih File Master
                                Spreadsheet</label>
                            <div class="d-flex gap-2">
                                <input type="file" name="file" class="form-control form-control-sm"
                                    accept=".xlsx,.xls,.csv" required>
                                <a href="/rundown/template"
                                    class="btn btn-outline-secondary btn-sm bg-white d-flex align-items-center"
                                    download title="Download Template Excel"
                                    style="border-radius: 8px; border-color: #cbd5e1 !important;">
                                    <i class="bi bi-download text-secondary"></i>
                                </a>
                            </div>
                            <small class="text-muted d-block mt-15" style="font-size: 0.75rem;">Ekstensi file yang
                                didukung: .xlsx, .xls, .csv</small>
                        </div>

                        <div class="alert alert-info border-0 rounded-3 mb-4 py-2 px-3 style-info-alert"
                            role="alert">
                            <i class="bi bi-info-circle-fill me-1"></i>
                            <strong>Aturan Kolom:</strong> Wajib berurutan berisi: Hari, Sesi, Waktu Mulai, Waktu
                            Selesai, Kegiatan, Penanggung Jawab (Nama Panitia).
                        </div>

                        <div class="d-flex gap-2 mt-2">
                            <button type="button" class="btn btn-light btn-sm flex-fill fw-medium"
                                data-bs-dismiss="modal"
                                style="border-radius: 8px; background-color: #f1f5f9;">Batal</button>
                            <button type="submit" class="btn btn-primary btn-sm flex-fill fw-semibold"
                                style="border-radius: 8px; background-color: #4f46e5; border: none;">Import
                                Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

<style>
    /* Styling Kustom Mikro */
    .px-25 {
        padding-left: 10px !important;
        padding-right: 10px !important;
    }

    .py-15 {
        padding-top: 4px !important;
        padding-bottom: 4px !important;
    }

    .py-25 {
        padding-top: 10px !important;
        padding-bottom: 10px !important;
    }

    .mt-15 {
        margin-top: 6px !important;
    }

    .me-1.5 {
        margin-right: 6px !important;
    }

    .style-table-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
    }

    .style-info-alert {
        font-size: 0.775rem;
        background-color: #eff6ff;
        color: #1e40af;
        line-height: 1.5;
    }

    /* Navigasi Tab Internal Day 1, Day 2 Modern */
    .custom-rundown-tabs {
        gap: 4px;
    }

    .custom-rundown-tabs .nav-link {
        color: #64748b;
        border: none;
        background: none;
        position: relative;
        transition: color 0.2s ease;
    }

    .custom-rundown-tabs .nav-link:hover {
        color: #4f46e5;
        border: none;
    }

    .custom-rundown-tabs .nav-link.active {
        color: #4f46e5 !important;
        background: none !important;
        border: none !important;
    }

    .custom-rundown-tabs .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 16px;
        width: calc(100% - 32px);
        height: 2.5px;
        background-color: #4f46e5;
        border-radius: 2px;
    }

    /* Form Fields Input Ringkas */
    .form-control-sm,
    .form-select-sm {
        border-radius: 8px !important;
        font-size: 0.85rem !important;
        padding: 8px 12px !important;
        border: 1.5px solid #e2e8f0 !important;
        background-color: #ffffff !important;
    }

    .form-control-sm:focus,
    .form-select-sm:focus {
        border-color: #4f46e5 !important;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important;
    }

    .btn-link {
        transition: opacity 0.2s ease;
    }

    .btn-link:hover {
        opacity: 0.8;
    }
</style>

{{-- SCRIPT JAVASCRIPT ASLI DAN AMAN --}}
@if ($canManageRundown)
    <script>
        document.querySelectorAll('.add-to-day-btn').forEach((btn) => {
            btn.addEventListener('click', function() {
                const dayNumber = this.dataset.day;
                const dayInput = document.getElementById('rundown_day_input');
                dayInput.value = dayNumber;
                dayInput.readOnly = true;
                dayInput.style.backgroundColor = '#f1f5f9';
            });
        });

        document.querySelector('.add-main-btn')?.addEventListener('click', function() {
            const dayInput = document.getElementById('rundown_day_input');
            dayInput.value = '';
            dayInput.readOnly = false;
            dayInput.style.backgroundColor = '';
        });

        document.getElementById('rundownModal')?.addEventListener('hidden.bs.modal', function() {
            const dayInput = document.getElementById('rundown_day_input');
            dayInput.readOnly = false;
            dayInput.style.backgroundColor = '';
        });

        document.querySelectorAll('.rundown-edit-btn').forEach((btn) => {
            btn.addEventListener('click', function() {
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
            tab.addEventListener('shown.bs.tab', function() {
                window.dispatchEvent(new Event('resize'));
            });
        });

        @if (
            $errors->has('day_number') ||
                $errors->has('session_group') ||
                $errors->has('waktu_mulai') ||
                $errors->has('waktu_selesai') ||
                $errors->has('kegiatan') ||
                $errors->has('assigned_to'))
            document.addEventListener("DOMContentLoaded", function() {
                var rundownModal = new bootstrap.Modal(document.getElementById('rundownModal'));
                rundownModal.show();
            });
        @endif
    </script>
@endif
