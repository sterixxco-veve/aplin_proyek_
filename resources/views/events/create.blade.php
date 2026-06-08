@extends('layouts.app')

@section('content')
<div class="container pb-5">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/events" class="text-decoration-none text-muted">Events</a></li>
            <li class="breadcrumb-item active fw-bold text-primary" aria-current="page">Buat Event</li>
        </ol>
    </nav>

    <div class="row g-4 align-items-start">

        {{-- Left: Info card --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 24px;">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="bg-primary-subtle text-primary rounded-4 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                            <i class="bi bi-calendar-plus fs-4"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1">Buat Event</h3>
                            <p class="text-muted mb-0 small">Isi detail event baru untuk organisasimu.</p>
                        </div>
                    </div>

                    <div class="rounded-4 bg-light p-3 mb-4">
                        <div class="d-flex gap-3">
                            <div class="text-primary">
                                <i class="bi bi-info-circle fs-5"></i>
                            </div>
                            <div class="small text-muted">
                                Setelah event dibuat, kamu bisa mengisi detail seperti rundown, budget, committee, dan dokumentasi.
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                <i class="bi bi-list-check"></i>
                            </div>
                            <div>
                                <div class="fw-semibold small">Rundown & Agenda</div>
                                <small class="text-muted">Susun sesi acara secara detail</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                <i class="bi bi-cash-coin"></i>
                            </div>
                            <div>
                                <div class="fw-semibold small">Budget & Finance</div>
                                <small class="text-muted">Kelola proposal dan realisasi</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                <i class="bi bi-people"></i>
                            </div>
                            <div>
                                <div class="fw-semibold small">Committee</div>
                                <small class="text-muted">Atur panitia dan divisi</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Form card --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 24px;">
                <div class="card-body p-4 p-md-5">

                    <h5 class="fw-bold mb-1">Detail Event</h5>
                    <p class="text-muted small mb-4">Isi informasi dasar event yang akan dibuat.</p>


                    <form method="POST" action="/events">
                        @csrf

                        {{-- Nama Event --}}
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted ms-1">Nama Event <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="nama_event"
                                   class="form-control form-control-lg bg-light border-0 py-3 rounded-4 shadow-none @error('nama_event') is-invalid @enderror"
                                   placeholder="Contoh: Workshop UI/UX Design 2025"
                                   value="{{ old('nama_event') }}">
                            @error('nama_event')
                                <div class="invalid-feedback fw-semibold">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Pilih Organization --}}
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted ms-1">Organization <span class="text-danger">*</span></label>
                            <select name="id_org"
                                    class="form-select form-select-lg bg-light border-0 py-3 rounded-4 shadow-none @error('id_org') is-invalid @enderror">
                                <option value="">-- Pilih Organization --</option>
                                @foreach ($organizations as $org)
                                    <option value="{{ $org->id_org }}" {{ old('id_org') == $org->id_org ? 'selected' : '' }}>
                                        {{ $org->nama_org }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_org')
                                <div class="invalid-feedback fw-semibold">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kategori --}}
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted ms-1">Kategori Event <span class="text-danger">*</span></label>
                            <select name="id_event_category"
                                    class="form-select form-select-lg bg-light border-0 py-3 rounded-4 shadow-none @error('id_event_category') is-invalid @enderror">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id_event_category }}" {{ old('id_event_category') == $category->id_event_category ? 'selected' : '' }}>
                                        {{ $category->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_event_category')
                                <div class="invalid-feedback fw-semibold">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tanggal Mulai --}}
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted ms-1">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="datetime-local"
                                   name="tgl_mulai"
                                   class="form-control form-control-lg bg-light border-0 py-3 rounded-4 shadow-none @error('tgl_mulai') is-invalid @enderror"
                                   value="{{ old('tgl_mulai') }}">
                            @error('tgl_mulai')
                                <div class="invalid-feedback fw-semibold">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2 flex-wrap">
                            <a href="/events" class="btn btn-light px-4 py-3 rounded-pill fw-bold border">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-4 py-3 rounded-pill fw-bold shadow-sm">
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
    .form-control:focus,
    .form-select:focus {
        background-color: #fff !important;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.12) !important;
        border-color: transparent !important;
    }
    .form-control.is-invalid,
    .form-select.is-invalid {
        background-color: #fff5f5 !important;
    }
</style>
@endsection