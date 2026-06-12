@extends('layouts.guest')

@section('content')
    <style>
        /* Menggunakan font modern inter/system yang clean */
        body {
            background-color: #f8fafc;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            min-height: 100vh;
            overflow: hidden
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
            /* Padding lebih luas agar tidak terasa penuh */
            width: 100%;
            max-width: 460px;
            /* Lebar card yang ideal */
        }

        .icon-wrapper {
            width: 52px;
            height: 52px;
            background-color: #f0fdf4;
            /* Ganti ke warna hijau sukses soft atau indigo, di sini kita pakai soft indigo jika ingin senada button: #eef2ff */
            background-color: #eef2ff;
            color: #4f46e5;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 28px;
        }

        .auth-title {
            color: #0f172a;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 12px;
        }

        .auth-subtitle {
            color: #64748b;
            font-size: 0.925rem;
            line-height: 1.6;
            margin-bottom: 32px;
        }

        .form-group {
            margin-bottom: 24px;
            width: 100%;
            /* Memastikan form group mengambil ruang penuh */
        }

        .form-label {
            color: #334155;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            background-color: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 0.95rem;
            color: #0f172a;
            width: 100%;
            /* Memaksa input untuk memenuhi kotak */
            box-sizing: border-box;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            outline: none;
            background-color: #ffffff;
        }

        /* Modifikasi placeholder agar lebih clean */
        .form-control::placeholder {
            color: #94a3b8;
            opacity: 1;
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
            /* Memaksa tombol memenuhi kotak */
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #4338ca;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
        }

        .back-link {
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: color 0.2s ease;
        }

        .back-link:hover {
            color: #4f46e5;
        }
    </style>

    <div class="auth-wrapper">
        <div class="auth-card">

            <!-- Bagian Header (Rata Kiri agar lega dan clean) -->
            <div class="icon-wrapper">
                🔑
            </div>

            <h3 class="auth-title">
                Forgot Password?
            </h3>

            <p class="auth-subtitle">
                Enter your registered email address below and we'll send you a secure link to reset your password.
            </p>

            <!-- Status Alert Sukses -->
            @if (session('status'))
                <div class="alert alert-success border-0 rounded-3 d-flex align-items-center gap-2 mb-4"
                    style="background-color: #f0fdf4; color: #166534; font-size: 0.875rem; padding: 14px;">
                    <i class="bi bi-check-circle-fill text-success"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- Form Reset -->
            <form method="POST" action="{{ route('password.email') }}" style="width: 100%;">
                @csrf

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        placeholder="name@example.com" value="{{ old('email') }}" required autofocus>

                    @error('email')
                        <div class="invalid-feedback fw-medium mt-2" style="font-size: 0.8rem;">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    Send Reset Link
                </button>
            </form>

            <!-- Tombol Kembali -->
            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="text-decoration-none back-link">
                    ← Back to Login
                </a>
            </div>

        </div>
    </div>
@endsection
