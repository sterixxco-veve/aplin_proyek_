@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-center align-items-center" style="min-height:85vh;">

    <div class="card auth-card border-0 shadow-lg p-2" style="width: 420px; border-radius: 24px;">
        <div class="card-body p-4 p-md-5">

            {{-- LOGO & HEADER --}}
            <div class="text-center mb-5">
                <div class="bg-primary-subtle rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 64px; height: 64px;">
                    <i class="bi bi-intersect text-primary fs-2"></i>
                </div>
                <h3 class="fw-bold text-dark mb-1">Selamat Datang Kembali</h3>
                <p class="text-muted small">Kelola event GDGOC dengan satu akun</p>
            </div>

            {{-- FORM --}}
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted ms-1">Alamat Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-envelope text-muted"></i></span>
                        <input type="email" name="email" class="form-control bg-light border-0 py-3" placeholder="nama@gdgoc.com" required autofocus>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center ms-1 mb-2">
                        <label class="form-label small fw-bold text-muted mb-0">Password</label>
                        <a href="#" class="text-decoration-none small text-primary fw-medium">Lupa Password?</a>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-lock text-muted"></i></span>
                        <input type="password" name="password" class="form-control bg-light border-0 py-3" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="form-check mb-4 ms-1">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label small text-muted" for="remember">
                        Ingat saya di perangkat ini
                    </label>
                </div>

                <button class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow mb-4">
                    Login Sekarang <i class="bi bi-arrow-right ms-2"></i>
                </button>

            </form>

            <div class="text-center mt-2">
                <p class="text-muted small mb-0">
                    Belum punya akun?
                    <a href="/register" class="text-primary text-decoration-none fw-bold ms-1">Daftar Gratis</a>
                </p>
            </div>

        </div>
    </div>

</div>

@endsection