@extends('layouts.app')

@section('content')
    <div class="container pb-5">

        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted small">Dashboard</a>
                </li>
                <li class="breadcrumb-item"><a href="/events" class="text-decoration-none text-muted small">Events</a></li>
                <li class="breadcrumb-item active fw-bold text-primary small" aria-current="page">Buat Event</li>
            </ol>
        </nav>

        <div class="row g-4 align-items-start">

            {{-- Left: Info card --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm" style="border-radius: 24px;">
                    <div class="card-body p-4 p-md-4">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="bg-primary-subtle text-primary rounded-4 d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px; flex-shrink: 0;">
                                <i class="bi bi-calendar-plus fs-5"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.5px; font-size: 1.25rem;">Buat
                                    Event</h4>
                                <p class="text-muted mb-0 small">Isi detail event baru untuk organisasimu.</p>
                            </div>
                        </div>

                        <div class="rounded-4 bg-light p-3 mb-4 border-0">
                            <div class="d-flex gap-2">
                                <div class="text-primary">
                                    <i class="bi bi-info-circle-fill small"></i>
                                </div>
                                <div class="small text-muted lh-base" style="font-size: 0.85rem;">
                                    Setelah event dibuat, kamu bisa mengisi detail seperti rundown, budget, committee, dan
                                    dokumentasi.
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 36px; height: 36px; flex-shrink: 0;">
                                    <i class="bi bi-list-check small"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark" style="font-size: 0.875rem;">Rundown & Agenda</div>
                                    <small class="text-muted" style="font-size: 0.775rem;">Susun sesi acara secara
                                        detail</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 36px; height: 36px; flex-shrink: 0;">
                                    <i class="bi bi-cash-coin small"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark" style="font-size: 0.875rem;">Budget & Finance</div>
                                    <small class="text-muted" style="font-size: 0.775rem;">Kelola proposal dan
                                        realisasi</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 36px; height: 36px; flex-shrink: 0;">
                                    <i class="bi bi-people small"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark" style="font-size: 0.875rem;">Committee</div>
                                    <small class="text-muted" style="font-size: 0.775rem;">Atur panitia dan divisi</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Form card --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm" style="border-radius: 24px;">
                    <div class="card-body p-4 p-md-4">

                        <h5 class="fw-bold text-dark mb-1" style="font-size: 1.15rem; letter-spacing: -0.3px;">Detail Event
                        </h5>
                        <p class="text-muted small mb-4">Isi informasi dasar event yang akan dibuat.</p>

                        <form method="POST" action="/events">
                            @csrf

                            {{-- Nama Event --}}
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-secondary ms-1">Nama Event <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="nama_event"
                                    class="form-control bg-light border-0 py-25 px-3 rounded-3 shadow-none @error('nama_event') is-invalid @enderror"
                                    placeholder="Contoh: Workshop UI/UX Design 2026" value="{{ old('nama_event') }}">
                                @error('nama_event')
                                    <div class="invalid-feedback fw-medium mt-2 ms-1" style="font-size: 0.8rem;">
                                        {{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Pilih Organization --}}
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-secondary ms-1">Organization <span
                                        class="text-danger">*</span></label>
                                <select name="id_org"
                                    class="form-select bg-light border-0 py-25 px-3 rounded-3 shadow-none @error('id_org') is-invalid @enderror">
                                    <option value="">-- Pilih Organization --</option>
                                    @foreach ($organizations as $org)
                                        <option value="{{ $org->id_org }}"
                                            {{ old('id_org') == $org->id_org ? 'selected' : '' }}>
                                            {{ $org->nama_org }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_org')
                                    <div class="invalid-feedback fw-medium mt-2 ms-1" style="font-size: 0.8rem;">
                                        {{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Kategori --}}
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-secondary ms-1">Kategori Event <span
                                        class="text-danger">*</span></label>
                                <select name="id_event_category"
                                    class="form-select bg-light border-0 py-25 px-3 rounded-3 shadow-none @error('id_event_category') is-invalid @enderror">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id_event_category }}"
                                            {{ old('id_event_category') == $category->id_event_category ? 'selected' : '' }}>
                                            {{ $category->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_event_category')
                                    <div class="invalid-feedback fw-medium mt-2 ms-1" style="font-size: 0.8rem;">
                                        {{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Tanggal Mulai --}}
                            <div class="mb-4">
                                <label class="form-label small fw-semibold text-secondary ms-1">Tanggal Mulai <span
                                        class="text-danger">*</span></label>
                                <input type="datetime-local" name="tgl_mulai"
                                    class="form-control bg-light border-0 py-25 px-3 rounded-3 shadow-none @error('tgl_mulai') is-invalid @enderror"
                                    value="{{ old('tgl_mulai') }}">
                                @error('tgl_mulai')
                                    <div class="invalid-feedback fw-medium mt-2 ms-1" style="font-size: 0.8rem;">
                                        {{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Action Buttons --}}
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="/events"
                                    class="btn btn-light px-4 py-25 rounded-pill fw-bold border-0 small text-muted hover-bg"
                                    style="font-size: 0.875rem; background-color: #f1f5f9;">
                                    Batal
                                </a>
                                <button type="submit"
                                    class="btn btn-primary px-4 py-25 rounded-pill fw-bold shadow-sm small"
                                    style="font-size: 0.875rem; background-color: #4f46e5; border: none;">
                                    <i class="bi bi-calendar-plus me-1"></i> Buat Event
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        /* Custom utility padding untuk tinggi form yang proporsional */
        .py-25 {
            padding-top: 10px !important;
            padding-bottom: 10px !important;
        }

        /* Menormalkan ukuran font input bawaan agar seimbang */
        .form-control,
        .form-select {
            font-size: 0.9rem !important;
            color: #334155 !important;
        }

        .form-control::placeholder {
            color: #94a3b8 !important;
            font-size: 0.9rem;
        }

        /* State focus yang lebih lembut mengikuti gaya minimalis sebelumnya */
        .form-control:focus,
        .form-select:focus {
            background-color: #fff !important;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12) !important;
            border: 1.5px solid #4f46e5 !important;
        }

        .form-control.is-invalid,
        .form-select.is-invalid {
            background-color: #fff5f5 !important;
            border: 1.5px solid #ef4444 !important;
        }

        .hover-bg:hover {
            background-color: #e2e8f0 !important;
            color: #0f172a !important;
        }
    </style>
@endsection
