<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>GDGOC EventFlow - Login</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* Menggunakan warna background abu-abu sangat muda/bersih */
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f8fafc;
            min-height: 100vh;
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .auth-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05);
            padding: 48px 40px;
            /* Padding luas agar lega */
            width: 100%;
            max-width: 460px;
            /* Lebar ideal proporsional */
        }

        .icon-wrapper {
            width: 56px;
            height: 56px;
            background-color: #eef2ff;
            /* Soft indigo/purple tint */
            color: #4f46e5;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
        }

        .auth-title {
            color: #0f172a;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
        }

        .auth-subtitle {
            color: #64748b;
            font-size: 0.925rem;
            line-height: 1.5;
            margin-bottom: 36px;
        }

        .form-group {
            margin-bottom: 24px;
            width: 100%;
        }

        .form-label {
            color: #334155;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
        }

        /* Solusi Utama: Menghindari input menyusut/cemet di dalam input group */
        .input-group {
            position: relative;
            display: flex;
            flex-wrap: nowrap;
            align-items: stretch;
            width: 100% !important;
        }

        /* Sisi icon input */
        .input-group-text {
            background-color: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-right: none;
            border-top-left-radius: 12px !important;
            border-bottom-left-radius: 12px !important;
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
            padding: 0 16px;
            color: #94a3b8;
            transition: all 0.2s ease;
        }

        /* Kolom ketik utama mengambil sisa ruang penuh */
        .input-group .form-control {
            flex: 1 1 auto;
            width: 1%;
            min-width: 0;
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
            border-top-right-radius: 12px !important;
            border-bottom-right-radius: 12px !important;
        }

        .form-control {
            background-color: #ffffff;
            border: 1.5px solid #e2e8f0;
            padding: 14px 16px;
            font-size: 0.95rem;
            color: #0f172a;
            width: 100%;
            box-sizing: border-box;
            transition: all 0.2s ease;
        }

        /* Efek fokus terintegrasi saat input aktif */
        .form-control:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            outline: none;
            z-index: 3;
        }

        .input-group:focus-within .input-group-text {
            border-color: #4f46e5;
            color: #4f46e5;
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        /* Link teks kustom */
        .auth-link {
            color: #4f46e5;
            font-size: 0.875rem;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .auth-link:hover {
            color: #4338ca;
        }

        .btn-primary {
            background-color: #4f46e5;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-size: 0.95rem;
            font-weight: 600;
            color: #ffffff;
            width: 100%;
            margin-top: 12px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #4338ca;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
        }
    </style>
</head>

<body>

    <div class="auth-wrapper">
        <div class="auth-card">

            {{-- HEADER LOGO (Rata kiri modern) --}}
            <div class="icon-wrapper">
                <i class="bi bi-intersect fs-3"></i>
            </div>

            <h3 class="auth-title">Selamat Datang Kembali</h3>
            <p class="auth-subtitle">Kelola event GDGOC dengan satu akun</p>

            @if (session('status'))
                <div class="alert alert-success border-0 rounded-3 d-flex align-items-center gap-2 mb-4"
                    style="background-color: #f0fdf4; color: #166534; font-size: 0.875rem; padding: 14px;">
                    <i class="bi bi-check-circle-fill text-success"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            {{-- FORM --}}
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Alamat Email</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            placeholder="nama@gdgoc.com" value="{{ old('email') }}" required autofocus>
                    </div>
                    @error('email')
                        <div class="text-danger small mt-2 ms-1" style="font-size: 0.8rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label mb-0">Password</label>
                        <a href="{{ route('password.request') }}" class="text-decoration-none auth-link fw-medium"
                            style="font-size: 0.825rem;">
                            Lupa Password?
                        </a>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" placeholder="••••••••"
                            required>
                    </div>
                    @error('password')
                        <div class="text-danger small mt-2 ms-1" style="font-size: 0.8rem;">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    Login Sekarang <i class="bi bi-arrow-right ms-2"></i>
                </button>

            </form>

            {{-- FOOTER REGISTER --}}
            <div class="text-center mt-4">
                <p class="text-muted small mb-0">
                    Belum punya akun?
                    <a href="/register" class="text-decoration-none auth-link ms-1">Daftar Gratis</a>
                </p>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
