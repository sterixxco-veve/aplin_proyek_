@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 pb-5">

        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted small">Dashboard</a>
                </li>
                <li class="breadcrumb-item"><a href="/events" class="text-decoration-none text-muted small">Events</a></li>
                <li class="breadcrumb-item active fw-bold text-primary small" aria-current="page">Detail Event</li>
            </ol>
        </nav>

        {{-- ========================= --}}
        {{-- HEADER EVENT --}}
        {{-- ========================= --}}
        <div class="card p-3 mb-3 border-0 shadow-sm" style="border-radius: 16px; background: #ffffff;">
            <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
                <div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h4 class="fw-bold text-dark mb-0" style="letter-spacing: -0.5px; font-size: 1.35rem;">
                            {{ $event->nama_event }}</h4>
                        <span class="badge rounded-pill px-25 py-1 small"
                            style="background:#fef3c7; color:#92400e; font-size: 0.75rem; font-weight: 600;">
                            Planning
                        </span>
                    </div>

                    <div class="d-flex gap-2 mt-2 text-secondary style-meta-text flex-wrap align-items-center">
                        <div><i
                                class="bi bi-calendar-event me-1 text-primary"></i>{{ \Carbon\Carbon::parse($event->tgl_mulai)->format('M d, Y') }}
                        </div>
                        <div class="text-muted px-1" style="opacity: 0.4;">|</div>
                        <div><i class="bi bi-geo-alt me-1 text-primary"></i> {{ $event->organization->nama_org ?? 'N/A' }}
                        </div>
                        <div class="text-muted px-1" style="opacity: 0.4;">|</div>
                        <div><i class="bi bi-people me-1 text-primary"></i> {{ $event->committees->count() }} Members</div>
                        <div class="text-muted px-1" style="opacity: 0.4;">|</div>
                        <div><i class="bi bi-cash-coin me-1 text-primary"></i> Rp
                            {{ number_format($event->financial_summary['total_budget'] ?? 0) }}</div>
                    </div>
                </div>

                @if ($event->canManageCertificateBy(auth()->user()))
                    <a href="/events/{{ $event->id_event }}/edit"
                        class="btn btn-primary px-3 py-2 rounded-pill fw-semibold d-flex align-items-center gap-1"
                        style="font-size: 0.85rem; background-color: #4f46e5; border: none; box-shadow: 0 4px 10px rgba(79, 70, 229, 0.15);">
                        <i class="bi bi-pencil-square"></i> Edit Event
                    </a>
                @endif
            </div>
        </div>

        {{-- ========================= --}}
        {{-- MAIN CONTENT --}}
        {{-- ========================= --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; overflow: hidden;">

            {{-- Navigasi Tab Modern (Rapat & Scrollable di Mobile) --}}
            <div class="card-header bg-transparent border-bottom pt-3 px-4 pb-0">
                <div class="tab-scroll-container">
                    <div class="d-flex horizontal-tab-nav">
                        <button class="tab-btn active" data-tab="rundown">Rundown</button>
                        @if ($event->canManageCertificateBy(auth()->user()))
                            <button class="tab-btn" data-tab="budget">Budget</button>
                        @endif
                        <button class="tab-btn" data-tab="finance">Finance</button>
                        @if ($event->canManageCertificateBy(auth()->user()))
                            <button class="tab-btn" data-tab="documents">Documents</button>
                        @endif
                        <button class="tab-btn" data-tab="partners">Partners</button>
                        @if ($event->canManageCertificateBy(auth()->user()))
                            <button class="tab-btn" data-tab="certificates">Certificates</button>
                        @endif
                        <button class="tab-btn" data-tab="committee">Committee</button>
                        <button class="tab-btn" data-tab="tasks">Tasks</button>
                        <button class="tab-btn" data-tab="documentation">Documentation</button>
                    </div>
                </div>
            </div>

            {{-- Isi Konten Di Bawah Tab --}}
            <div class="card-body p-4" style="min-height: 350px;">

                {{-- Tab Content: Rundown (KEMBALI KE ASLI) --}}
                <div id="rundown" class="tab-content">
                    @include('events.partials.rundown')
                </div>

                {{-- Tab Content: Budget --}}
                <div id="budget" class="tab-content d-none">
                    @include('events.partials.budget')
                </div>

                {{-- Tab Content: Finance --}}
                <div id="finance" class="tab-content d-none">
                    @include('events.partials.finance')
                </div>

                {{-- Tab Content: Documents --}}
                <div id="documents" class="tab-content d-none">
                    @include('events.partials.documents')
                </div>

                {{-- Tab Content: Partners --}}
                <div id="partners" class="tab-content d-none">
                    @include('events.partials.partners')
                </div>

                {{-- Tab Content: Certificates --}}
                <div id="certificates" class="tab-content d-none">
                    @include('events.partials.certificates')
                </div>

                {{-- Tab Content: Committee --}}
                <div id="committee" class="tab-content d-none">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <div>
                            <h5 class="fw-bold text-dark mb-0" style="font-size: 1.1rem;">Daftar Panitia Event</h5>
                            <small class="text-muted" style="font-size: 0.8rem;">Manajemen anggota divisi kepanitiaan
                                aktif.</small>
                        </div>
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-25 py-15 fw-semibold"
                            style="font-size: 0.8rem;">
                            {{ $event->committees->count() }} Committee
                        </span>
                    </div>

                    {{-- Form Tambah Committee --}}
                    @if ($event->canManageCertificateBy(auth()->user()))
                        <div class="card border-0 mb-3" style="background-color: #f8fafc; border-radius: 12px;">
                            <div class="card-body p-3">
                                <h6 class="fw-bold text-dark mb-2" style="font-size: 0.875rem;"><i
                                        class="bi bi-person-plus me-1 text-primary"></i>Tambah Anggota Baru</h6>
                                <form method="POST" action="/events/{{ $event->id_event }}/assign" class="row g-2">
                                    @csrf
                                    <div class="col-md-4">
                                        <select name="id_user"
                                            class="form-select form-select-sm @error('id_user') is-invalid @enderror">
                                            <option value="">Pilih member...</option>
                                            @forelse($availableMembers ?? [] as $member)
                                                <option value="{{ $member->id_user }}"
                                                    {{ old('id_user') == $member->id_user ? 'selected' : '' }}>
                                                    {{ $member->name }}</option>
                                            @empty
                                                <option value="">Tidak ada member tersedia</option>
                                            @endforelse
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <select name="id_divisi"
                                            class="form-select form-select-sm @error('id_divisi') is-invalid @enderror">
                                            <option value="">Pilih divisi...</option>
                                            @foreach ($divisions ?? [] as $division)
                                                <option value="{{ $division->id_divisi }}"
                                                    {{ old('id_divisi') == $division->id_divisi ? 'selected' : '' }}>
                                                    {{ $division->nama_divisi }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <select name="jabatan"
                                            class="form-select form-select-sm @error('jabatan') is-invalid @enderror">
                                            <option value="">Pilih jabatan...</option>
                                            <option value="koordinator"
                                                {{ old('jabatan') == 'koordinator' ? 'selected' : '' }}>Koordinator
                                            </option>
                                            <option value="anggota" {{ old('jabatan') == 'anggota' ? 'selected' : '' }}>
                                                Anggota</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <button class="btn btn-primary btn-sm w-100 fw-semibold"
                                            style="background-color: #4f46e5; border: none;">Tambah</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    {{-- Tabel Daftar Committee --}}
                    <div class="card border-0 style-table-card">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table align-middle mb-0" style="font-size: 0.875rem;">
                                    <thead class="table-light">
                                        <tr class="small text-secondary text-uppercase"
                                            style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                            <th class="ps-3 py-2">Nama Lengkap</th>
                                            <th class="py-2">Divisi</th>
                                            <th class="py-2">Jabatan</th>
                                            <th class="text-end pe-3 py-2">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($event->committees as $committee)
                                            <tr>
                                                <td class="ps-3 py-25">
                                                    <div class="fw-semibold text-dark">{{ $committee->user->name ?? '-' }}
                                                    </div>
                                                    <small class="text-muted"
                                                        style="font-size: 0.775rem;">{{ $committee->user->email ?? '-' }}</small>
                                                </td>
                                                <td class="py-25">
                                                    <span class="badge bg-white text-secondary border px-2 py-1"
                                                        style="font-size: 0.75rem; border-color: #e2e8f0 !important;">{{ $committee->division->nama_divisi ?? '-' }}</span>
                                                </td>
                                                <td class="py-25">
                                                    <span class="text-secondary fw-medium"
                                                        style="font-size: 0.825rem;">{{ $committee->jabatan ? ucfirst($committee->jabatan) : '-' }}</span>
                                                </td>
                                                <td class="text-end pe-3 py-25">
                                                    @if ($event->canManageCommitteeBy(auth()->user()) && $event->committees->count() > 1)
                                                        <form method="POST"
                                                            action="/events/{{ $event->id_event }}/committees/{{ $committee->id_comm }}"
                                                            onsubmit="return confirm('Hapus committee ini?')"
                                                            class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button
                                                                class="btn btn-sm btn-link text-danger p-0 text-decoration-none fw-semibold"
                                                                style="font-size: 0.8rem;">Hapus</button>
                                                        </form>
                                                    @else
                                                        <span class="text-muted" style="font-size: 0.75rem;">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-muted p-4 text-center small">Belum ada
                                                    committee di event ini.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tab Content: Tasks (Kanban) --}}
                <div id="tasks" class="tab-content d-none">
                    @include('tasks.kanban', ['canManageTasks' => $canManageTasks ?? false])
                </div>

                {{-- Tab Content: Documentation --}}
                <div id="documentation" class="tab-content d-none">
                    @include('events.partials.documentation')
                </div>

            </div>
        </div>
    </div>

    <style>
        /* Mengatur scrollbar halus jika tab melebihi layar di device kecil */
        .tab-scroll-container {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .tab-scroll-container::-webkit-scrollbar {
            height: 4px;
        }

        .tab-scroll-container::-webkit-scrollbar-thumb {
            background-color: #e2e8f0;
            border-radius: 4px;
        }

        /* Merapatkan jarak menu tab horizontal agar seimbang */
        .horizontal-tab-nav {
            display: flex;
            white-space: nowrap;
            gap: 8px;
        }

        .horizontal-tab-nav .tab-btn {
            background: none;
            border: none;
            padding: 8px 16px 14px 16px;
            color: #64748b;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            position: relative;
        }

        .horizontal-tab-nav .tab-btn:hover {
            color: #4f46e5;
        }

        .horizontal-tab-nav .tab-btn.active {
            color: #4f46e5;
            font-weight: 600;
        }

        /* Indikator Garis Ungu Aktif Pas di Bawah Huruf */
        .horizontal-tab-nav .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 16px;
            width: calc(100% - 32px);
            height: 2.5px;
            background-color: #4f46e5;
            border-radius: 2px;
        }

        /* Pembersihan utilitas spacing */
        .style-meta-text {
            font-size: 0.85rem !important;
            font-weight: 400;
        }

        .px-25 {
            padding-left: 10px !important;
            padding-right: 10px !important;
        }

        .py-15 {
            padding-top: 4px !important;
            padding-bottom: 4px !important;
        }

        .py-25 {
            padding-top: 8px !important;
            padding-bottom: 8px !important;
        }

        .style-table-card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
        }

        /* Form Element Ringkas */
        .form-select-sm,
        .form-control-sm {
            border-radius: 8px !important;
            font-size: 0.85rem !important;
            padding: 8px 12px !important;
            border: 1.5px solid #e2e8f0 !important;
        }

        .form-select-sm:focus,
        .form-control-sm:focus {
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important;
        }

        body {
            background-color: #f8fafc;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Logika Switcher Menu Tab Utama
            document.querySelectorAll('.horizontal-tab-nav .tab-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.horizontal-tab-nav .tab-btn').forEach(b => b
                        .classList.remove('active'));
                    this.classList.add('active');

                    document.querySelectorAll('.tab-content').forEach(c => c.classList.add(
                        'd-none'));

                    const targetContent = document.getElementById(this.dataset.tab);
                    if (targetContent) {
                        targetContent.classList.remove('d-none');
                    }
                });
            });

            // Otomatis deteksi tab aktif lewat parameter URL (?tab=tasks)
            const activeTab = new URLSearchParams(window.location.search).get('tab');
            if (activeTab) {
                const target = document.querySelector(`.horizontal-tab-nav .tab-btn[data-tab="${activeTab}"]`);
                if (target) target.click();
            }

            // Otomatis buka tab dari session flash data Laravel
            @if (session('open_tab'))
                const sessionTab = document.querySelector(
                    '.horizontal-tab-nav .tab-btn[data-tab="{{ session('open_tab') }}"]');
                if (sessionTab) sessionTab.click();
            @endif
        });
    </script>
@endsection
