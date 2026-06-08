<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>GDGOC EventFlow - Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
    </style>
</head>

<body>
    <div class="d-flex justify-content-center align-items-center" style="min-height:100vh;">

        <div class="card auth-card border-0 shadow-lg p-2" style="width: 420px; border-radius: 24px;">
            <div class="card-body p-4 p-md-5">

                {{-- LOGO & HEADER --}}
                <div class="text-center mb-5">
                    <div class="bg-primary-subtle rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm"
                        style="width: 64px; height: 64px;">
                        <i class="bi bi-intersect text-primary fs-2"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">Selamat Datang Kembali</h3>
                    <p class="text-muted small">Kelola event GDGOC dengan satu akun</p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success small py-2 mb-4 text-center">
                        {{ session('status') }}
                    </div>
                @endif

                {{-- FORM --}}
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted ms-1">Alamat Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i
                                    class="bi bi-envelope text-muted"></i></span>
                            <input type="email" name="email"
                                class="form-control bg-light border-0 py-3 @error('email') is-invalid @enderror"
                                placeholder="nama@gdgoc.com" value="{{ old('email') }}" autofocus>
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1 ms-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center ms-1 mb-2">
                            <label class="form-label small fw-bold text-muted mb-0">Password</label>
                            <div class="text-end mb-3">
                                <a href="{{ route('password.request') }}" class="text-decoration-none">
                                    Lupa Password?
                                </a>
                            </div>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i
                                    class="bi bi-lock text-muted"></i></span>
                            <input type="password" name="password"
                                class="form-control bg-light border-0 py-3 @error('password') is-invalid @enderror"
                                placeholder="••••••••">
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1 ms-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- <div class="form-check mb-4 ms-1">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label small text-muted" for="remember">
                            Ingat saya di perangkat ini
                        </label>
                    </div> -->

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>