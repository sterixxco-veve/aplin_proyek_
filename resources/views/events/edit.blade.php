@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 pb-5">

        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted small">Dashboard</a>
                </li>
                <li class="breadcrumb-item"><a href="/events" class="text-decoration-none text-muted small">Events</a></li>
                <li class="breadcrumb-item"><a href="/events/{{ $event->id_event }}"
                        class="text-decoration-none text-muted small">{{ $event->nama_event }}</a></li>
                <li class="breadcrumb-item active fw-bold text-primary small" aria-current="page">Edit Event</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card border-0 shadow-sm" style="border-radius: 20px; background: #ffffff;">
                    <div class="card-body p-4 p-md-5">

                        {{-- Header Form --}}
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="rounded-4 d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px; background-color: #eef2ff; color: #4f46e5;">
                                <i class="bi bi-pencil-square fs-4"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold text-dark mb-0" style="letter-spacing: -0.5px; font-size: 1.35rem;">Edit
                                    Detail Dokumen Event</h4>
                                <p class="text-muted mb-0 small">Perbarui informasi dasar dan rancangan narasi master untuk
                                    proposal / LPJ.</p>
                            </div>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success border-0 rounded-3 d-flex align-items-center gap-2 mb-4 py-2 px-3"
                                style="font-size: 0.85rem; background-color: #f0fdf4; color: #166534;">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <span>{{ session('success') }}</span>
                            </div>
                        @endif

                        <form method="POST" action="/events/{{ $event->id_event }}">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                {{-- Nama Event --}}
                                <div class="col-12">
                                    <label class="form-label small fw-semibold text-secondary mb-1 ms-1">Nama Event <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="nama_event"
                                        class="form-control form-control-sm @error('nama_event') is-invalid @enderror"
                                        placeholder="Contoh: Build With AI 2026 Surabaya"
                                        value="{{ old('nama_event', $event->nama_event) }}">
                                    @error('nama_event')
                                        <div class="invalid-feedback fw-medium mt-1" style="font-size: 0.775rem;">
                                            {{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Organization --}}
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-secondary mb-1 ms-1">Organization <span
                                            class="text-danger">*</span></label>
                                    <select name="id_org"
                                        class="form-select form-select-sm @error('id_org') is-invalid @enderror">
                                        <option value="">-- Pilih Organization --</option>
                                        @foreach ($organizations as $org)
                                            <option value="{{ $org->id_org }}"
                                                {{ old('id_org', $event->id_org) == $org->id_org ? 'selected' : '' }}>
                                                {{ $org->nama_org }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_org')
                                        <div class="invalid-feedback fw-medium mt-1" style="font-size: 0.775rem;">
                                            {{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Kategori --}}
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-secondary mb-1 ms-1">Kategori Event
                                        <span class="text-danger">*</span></label>
                                    <select name="id_event_category"
                                        class="form-select form-select-sm @error('id_event_category') is-invalid @enderror">
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id_event_category }}"
                                                {{ old('id_event_category', $event->id_event_category) == $category->id_event_category ? 'selected' : '' }}>
                                                {{ $category->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_event_category')
                                        <div class="invalid-feedback fw-medium mt-1" style="font-size: 0.775rem;">
                                            {{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Tanggal Mulai --}}
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-secondary mb-1 ms-1">Tanggal Mulai <span
                                            class="text-danger">*</span></label>
                                    <input type="datetime-local" name="tgl_mulai"
                                        class="form-control form-control-sm @error('tgl_mulai') is-invalid @enderror"
                                        value="{{ old('tgl_mulai', \Carbon\Carbon::parse($event->tgl_mulai)->format('Y-m-d\TH:i')) }}">
                                    @error('tgl_mulai')
                                        <div class="invalid-feedback fw-medium mt-1" style="font-size: 0.775rem;">
                                            {{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Tanggal Selesai --}}
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-secondary mb-1 ms-1">Tanggal Selesai
                                        <span class="text-muted">(Opsional)</span></label>
                                    <input type="datetime-local" name="tgl_selesai"
                                        class="form-control form-control-sm @error('tgl_selesai') is-invalid @enderror"
                                        value="{{ old('tgl_selesai', $event->tgl_selesai ? \Carbon\Carbon::parse($event->tgl_selesai)->format('Y-m-d\TH:i') : '') }}">
                                    @error('tgl_selesai')
                                        <div class="invalid-feedback fw-medium mt-1" style="font-size: 0.775rem;">
                                            {{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 my-2">
                                    <hr style="border-color: #cbd5e1 !important; opacity: 0.6;">
                                    <div class="fw-bold text-dark small mb-1"><i
                                            class="bi bi-file-earmark-text-fill text-primary me-1"></i> MASTER DOKUMEN
                                        NARASI</div>
                                    <small class="text-muted d-block mb-2">Teks di bawah ini akan tersimpan di database
                                        event dan otomatis mengisi draf dokumen Proposal / Surat Tugas.</small>
                                </div>

                                {{-- Latar Belakang --}}
                                <div class="col-12">
                                    <label class="form-label small fw-semibold text-secondary mb-1 ms-1">Latar Belakang
                                        Event</label>
                                    <textarea name="latar_belakang" rows="5"
                                        class="form-control form-control-sm @error('latar_belakang') is-invalid @enderror"
                                        placeholder="Tuliskan latar belakang, urgensi, atau dasar pemikiran mengapa event ini diadakan...">{{ old('latar_belakang', $event->latar_belakang) }}</textarea>
                                    @error('latar_belakang')
                                        <div class="invalid-feedback fw-medium mt-1" style="font-size: 0.775rem;">
                                            {{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Tujuan Kegiatan --}}
                                <div class="col-12">
                                    <label class="form-label small fw-semibold text-secondary mb-1 ms-1">Tujuan
                                        Kegiatan</label>
                                    <textarea name="tujuan" rows="4" class="form-control form-control-sm @error('tujuan') is-invalid @enderror"
                                        placeholder="Contoh:&#10;1. Meningkatkan pemahaman mahasiswa mengenai integrasi teknologi Cloud AI.&#10;2. Membangun jaringan kolaborasi antar developer profesional Surabaya.">{{ old('tujuan', $event->tujuan) }}</textarea>
                                    @error('tujuan')
                                        <div class="invalid-feedback fw-medium mt-1" style="font-size: 0.775rem;">
                                            {{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Action Buttons --}}
                                <div class="col-12 d-flex gap-2 mt-3 pt-3 border-top"
                                    style="border-color: #e2e8f0 !important;">
                                    <a href="/events/{{ $event->id_event }}"
                                        class="btn btn-light btn-sm px-4 fw-medium py-2"
                                        style="border-radius: 8px; background-color: #f1f5f9;">
                                        Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-sm px-4 fw-semibold py-2"
                                        style="background-color: #4f46e5; border: none; border-radius: 8px;">
                                        <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-control-sm,
        .form-select-sm {
            border-radius: 8px !important;
            font-size: 0.85rem !important;
            padding: 10px 14px !important;
            border: 1.5px solid #e2e8f0 !important;
            background-color: #ffffff !important;
        }

        .form-control-sm:focus,
        .form-select-sm:focus {
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important;
            background-color: #ffffff !important;
        }

        .form-control-sm.is-invalid,
        .form-select-sm.is-invalid {
            background-color: #fff5f5 !important;
            border-color: #ef4444 !important;
        }

        body {
            background-color: #f8fafc;
        }
    </style>
@endsection
