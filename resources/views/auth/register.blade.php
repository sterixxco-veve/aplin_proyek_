<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>GDGOC EventFlow - Register</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --gd-green: #34A853;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
    </style>
</head>

<body>
    <div class="d-flex justify-content-center align-items-center" style="min-height:100vh;">

        <div class="card auth-card border-0 shadow-lg p-2 my-5" style="width: 480px; border-radius: 24px;">
            <div class="card-body p-4 p-md-5">

                {{-- HEADER --}}
                <div class="text-center mb-5">
                    <div class="bg-success-subtle rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm"
                        style="width: 64px; height: 64px;">
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
                            <span class="input-group-text bg-light border-0"><i
                                    class="bi bi-person text-muted"></i></span>
                            <input type="text" name="name"
                                class="form-control bg-light border-0 py-3 @error('name') is-invalid @enderror"
                                placeholder="Nama Lengkap Anda" value="{{ old('name') }}">
                        </div>
                        @error('name')
                            <div class="text-danger small mt-1 ms-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted ms-1">Email Organisasi / Kampus</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i
                                    class="bi bi-envelope text-muted"></i></span>
                            <input type="email" name="email"
                                class="form-control bg-light border-0 py-3 @error('email') is-invalid @enderror"
                                placeholder="email@example.com" value="{{ old('email') }}">
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1 ms-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted ms-1">Password</label>
                            <input type="password" name="password"
                                class="form-control bg-light border-0 py-3 @error('password') is-invalid @enderror"
                                placeholder="••••••••">
                            @error('password')
                                <div class="text-danger small mt-1 ms-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted ms-1">Konfirmasi</label>
                            <input type="password" name="password_confirmation"
                                class="form-control bg-light border-0 py-3" placeholder="••••••••">
                        </div>
                    </div>

                    <button class="btn btn-success w-100 py-3 rounded-pill fw-bold shadow mb-4 border-0"
                        style="background-color: var(--gd-green);">
                        Daftar Akun Baru <i class="bi bi-check-lg ms-2"></i>
                    </button>

                </form>

                <div class="text-center">
                    <p class="text-muted small mb-0">
                        Sudah memiliki akun?
                        <a href="/login" class="text-success text-decoration-none fw-bold ms-1"
                            style="color: var(--gd-green) !important;">Masuk Saja</a>
                    </p>
                </div>

            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>