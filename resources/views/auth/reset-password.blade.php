@extends('layouts.guest')

@section('content')
    <style>
        /* Menggunakan font modern system yang clean */
        body {
            background-color: #f8fafc;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            min-height: 100vh;
            overflow: hidden;
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
            /* Lebar maksimal box */
        }

        .icon-wrapper {
            width: 52px;
            height: 52px;
            background-color: #eef2ff;
            /* Soft indigo tint */
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
            margin-bottom: 20px;
            width: 100%;
        }

        .form-label {
            color: #334155;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
        }

        /* Solusi Utama: Memaksa Input Group mengambil 100% lebar parent */
        .input-group {
            position: relative;
            display: flex;
            flex-wrap: nowrap;
            align-items: stretch;
            width: 100% !important;
        }

        /* Input mengambil sisa ruang penuh sebelum tombol mata */
        .input-group .form-control {
            flex: 1 1 auto;
            width: 1%;
            min-width: 0;
            border-top-left-radius: 12px !important;
            border-bottom-left-radius: 12px !important;
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
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

        /* Form control biasa (seperti email) yang tidak pakai group */
        .form-control:not(.input-group .form-control) {
            border-radius: 12px !important;
        }

        .form-control:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            outline: none;
            background-color: #ffffff;
            z-index: 3;
        }

        /* Sinkronisasi warna border saat input di-focus */
        .input-group:focus-within .form-control {
            border-color: #4f46e5;
        }

        .input-group:focus-within .btn-toggle {
            border-color: #4f46e5;
        }

        /* Styling Tombol Mata agar menempel sempurna */
        .btn-toggle {
            background-color: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-left: none;
            border-top-right-radius: 12px !important;
            border-bottom-right-radius: 12px !important;
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
            padding: 0 16px;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            z-index: 2;
        }

        .btn-toggle:hover {
            color: #4f46e5;
            background-color: #f8fafc;
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

    <div class="auth-wrapper">
        <div class="auth-card">

            <div class="icon-wrapper">
                🔒
            </div>

            <h3 class="auth-title">
                Create New Password
            </h3>

            <p class="auth-subtitle">
                Your new password should be secure and easy for you to remember.
            </p>

            <form method="POST" action="{{ route('password.store') }}" style="width: 100%;">
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $request->email) }}"
                        class="form-control @error('email') is-invalid @enderror" placeholder="name@example.com" required>

                    @error('email')
                        <div class="invalid-feedback fw-medium mt-2" style="font-size: 0.8rem; display: block;">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">New Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password"
                            class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" required>
                        <button type="button" class="btn btn-toggle" onclick="togglePassword('password')">
                            👁
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback fw-medium mt-2" style="font-size: 0.8rem; display: block;">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="••••••••"
                            required>
                        <button type="button" class="btn btn-toggle" onclick="togglePassword('password_confirmation')">
                            👁
                        </button>
                    </div>
                    @error('password_confirmation')
                        <div class="invalid-feedback fw-medium mt-2" style="font-size: 0.8rem; display: block;">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    Reset Password
                </button>
            </form>

        </div>
    </div>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            if (input.type === 'password') {
                input.type = 'text';
            } else {
                input.type = 'password';
            }
        }
    </script>
@endsection
