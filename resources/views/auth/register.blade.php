@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-center align-items-center" style="min-height:90vh;">

    <div class="card auth-card border-0 shadow-lg p-2 my-5" style="width: 480px; border-radius: 24px;">
        <div class="card-body p-4 p-md-5">

            {{-- HEADER --}}
            <div class="text-center mb-5">
                <div class="bg-success-subtle rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 64px; height: 64px;">
                    <i class="bi bi-rocket-takeoff text-success fs-2"></i>
                </div>
                <h3 class="fw-bold text-dark mb-1">Mulai Perjalanan Anda</h3>
                <p class="text-muted small">Buat akun untuk bergabung dengan EventFlow</p>
            </div>

            {{-- FORM --}}
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted ms-1">Nama Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-person text-muted"></i></span>
                        <input type="text" name="name" class="form-control bg-light border-0 py-3" placeholder="Nama Lengkap Anda" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted ms-1">Email Organisasi / Kampus</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-envelope text-muted"></i></span>
                        <input type="email" name="email" class="form-control bg-light border-0 py-3" placeholder="email@example.com" required>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted ms-1">Password</label>
                        <input type="password" name="password" class="form-control bg-light border-0 py-3" placeholder="••••••••" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted ms-1">Konfirmasi</label>
                        <input type="password" name="password_confirmation" class="form-control bg-light border-0 py-3" placeholder="••••••••" required>
                    </div>
                </div>

                <button class="btn btn-success w-100 py-3 rounded-pill fw-bold shadow mb-4 border-0" style="background-color: var(--gd-green);">
                    Daftar Akun Baru <i class="bi bi-check-lg ms-2"></i>
                </button>

            </form>

            <div class="text-center">
                <p class="text-muted small mb-0">
                    Sudah memiliki akun?
                    <a href="/login" class="text-success text-decoration-none fw-bold ms-1" style="color: var(--gd-green) !important;">Masuk Saja</a>
                </p>
            </div>

        </div>
    </div>

</div>

@endsection