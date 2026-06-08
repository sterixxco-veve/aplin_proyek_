@extends('layouts.app')

@section('content')
<div class="container pb-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/organizations" class="text-decoration-none text-muted">Organisasi</a></li>
            <li class="breadcrumb-item active fw-bold text-primary" aria-current="page">Buat Organization</li>
        </ol>
    </nav>

    <div class="row g-4 align-items-start">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 24px;">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="bg-primary-subtle text-primary rounded-4 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                            <i class="bi bi-building fs-4"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1">Buat Organization</h3>
                            <p class="text-muted mb-0">Tambahkan organisasi baru untuk mulai mengelola event dan anggota.</p>
                        </div>
                    </div>

                    <div class="rounded-4 bg-light p-3 mb-4">
                        <div class="d-flex gap-3">
                            <div class="text-primary">
                                <i class="bi bi-check2-circle fs-4"></i>
                            </div>
                            <div class="small text-muted">
                                Form ini dipakai untuk membuat wadah organisasi, lalu nanti bisa dipakai untuk invite anggota dan membuat event.
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Anggota Tim</div>
                                <small class="text-muted">Kelola akses anggota organisasi</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                <i class="bi bi-calendar-event"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Event Management</div>
                                <small class="text-muted">Hubungkan organisasi ke event yang dibuat</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                <i class="bi bi-shield-lock-fill"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Akses Admin</div>
                                <small class="text-muted">Admin organisasi bisa undang member</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 24px;">
                <div class="card-body p-4 p-md-5 d-flex flex-column">
                    <h5 class="fw-bold mb-1">Form Organization</h5>
                    <p class="text-muted small mb-4">Isi nama dan logo organisasi agar mudah dikenali oleh tim.</p>

                    <form method="POST" action="/organizations" enctype="multipart/form-data" class="d-flex flex-column flex-grow-1">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted ms-1">Nama Organization</label>
                            <input type="text"
                                   name="nama_org"
                                   class="form-control form-control-lg bg-light border-0 py-3 rounded-4 shadow-none @error('nama_org') is-invalid @enderror"
                                   placeholder="Contoh: Himpunan Mahasiswa Informatika"
                                   value="{{ old('nama_org') }}">
                            @error('nama_org')
                                <div class="invalid-feedback fw-semibold">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted ms-1">Logo</label>
                            <input type="file"
                                   name="logo"
                                   class="form-control bg-light border-0 py-3 rounded-4 shadow-none @error('logo') is-invalid @enderror">
                            @error('logo')
                                <div class="invalid-feedback fw-semibold">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-2">Opsional, tapi membantu identitas organisasi lebih jelas.</small>
                        </div>

                        <div class="d-flex gap-2 flex-wrap mt-auto">
                            <a href="/organizations" class="btn btn-light px-4 py-3 rounded-pill fw-bold border">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-4 py-3 rounded-pill fw-bold shadow-sm">
                                Simpan Organization
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus {
        background-color: #fff !important;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.12) !important;
    }
</style>
@endsection
