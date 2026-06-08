@extends('layouts.app')

@section('content')
<div class="container pb-5">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/events" class="text-decoration-none text-muted">Events</a></li>
            <li class="breadcrumb-item"><a href="/events/{{ $event->id_event }}" class="text-decoration-none text-muted">{{ $event->nama_event }}</a></li>
            <li class="breadcrumb-item active fw-bold text-primary" aria-current="page">Edit Event</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 24px;">
                <div class="card-body p-4 p-md-5">

                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="bg-warning-subtle text-warning rounded-4 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                            <i class="bi bi-pencil-square fs-4"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-1">Edit Event</h4>
                            <p class="text-muted mb-0 small">Perbarui informasi dasar event.</p>
                        </div>
                    </div>


                    @if(session('success'))
                        <div class="alert alert-success border-0 rounded-3 d-flex align-items-center gap-2 mb-4">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="/events/{{ $event->id_event }}">
                        @csrf
                        @method('PUT')

                        {{-- Nama Event --}}
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted ms-1">Nama Event <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="nama_event"
                                   class="form-control form-control-lg bg-light border-0 py-3 rounded-4 shadow-none @error('nama_event') is-invalid @enderror"
                                   placeholder="Nama event"
                                   value="{{ old('nama_event', $event->nama_event) }}">
                            @error('nama_event')
                                <div class="invalid-feedback fw-semibold">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Organization --}}
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted ms-1">Organization <span class="text-danger">*</span></label>
                            <select name="id_org"
                                    class="form-select form-select-lg bg-light border-0 py-3 rounded-4 shadow-none @error('id_org') is-invalid @enderror">
                                <option value="">-- Pilih Organization --</option>
                                @foreach ($organizations as $org)
                                    <option value="{{ $org->id_org }}"
                                        {{ old('id_org', $event->id_org) == $org->id_org ? 'selected' : '' }}>
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
                                    <option value="{{ $category->id_event_category }}"
                                        {{ old('id_event_category', $event->id_event_category) == $category->id_event_category ? 'selected' : '' }}>
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
                                   value="{{ old('tgl_mulai', \Carbon\Carbon::parse($event->tgl_mulai)->format('Y-m-d\TH:i')) }}">
                            @error('tgl_mulai')
                                <div class="invalid-feedback fw-semibold">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2 flex-wrap">
                            <a href="/events/{{ $event->id_event }}" class="btn btn-light px-4 py-3 rounded-pill fw-bold border">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-warning px-4 py-3 rounded-pill fw-bold shadow-sm text-white">
                                <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
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