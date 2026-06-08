@extends('layouts.guest')

@section('content')

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .auth-card {
            border: none;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .15);
        }

        .auth-left {
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            color: white;
            padding: 50px;
        }

        .auth-right {
            background: white;
            padding: 50px;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px;
        }

        .btn-primary {
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
        }

        .input-group .btn {
            border-radius: 0 12px 12px 0;
        }

        @media(max-width:768px) {
            .auth-left {
                display: none;
            }
        }
    </style>

    <div class="container py-5">

        <div class="row justify-content-center align-items-center min-vh-100">

            <div class="col-lg-10">

                <div class="card auth-card">

                    <div class="row g-0">

                        <div class="col-lg-5 auth-left">

                            <h2 class="fw-bold">
                                Create New Password
                            </h2>

                            <p class="mt-3 opacity-75">
                                Your new password should be secure and easy for you to remember.
                            </p>

                        </div>

                        <div class="col-lg-7 auth-right">

                            <h3 class="fw-bold mb-4">
                                Reset Password
                            </h3>



                            <form method="POST" action="{{ route('password.store') }}">

                                @csrf

                                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                <div class="mb-3">

                                    <label class="form-label fw-semibold">
                                        Email
                                    </label>

                                    <input type="email" name="email" value="{{ old('email', $request->email) }}"
                                        class="form-control @error('email') is-invalid @enderror">

                                    @error('email')
                                        <div class="invalid-feedback fw-semibold">{{ $message }}</div>
                                    @enderror

                                </div>

                                <div class="mb-3">

                                    <label class="form-label fw-semibold">
                                        New Password
                                    </label>

                                    <div class="input-group">

                                        <input type="password" name="password" id="password"
                                            class="form-control @error('password') is-invalid @enderror">

                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="togglePassword('password')">
                                            👁
                                        </button>

                                        @error('password')
                                            <div class="invalid-feedback fw-semibold">{{ $message }}</div>
                                        @enderror

                                    </div>

                                </div>

                                <div class="mb-4">

                                    <label class="form-label fw-semibold">
                                        Confirm Password
                                    </label>

                                    <div class="input-group">

                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="form-control @error('password_confirmation') is-invalid @enderror">

                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="togglePassword('password_confirmation')">
                                            👁
                                        </button>

                                        @error('password_confirmation')
                                            <div class="invalid-feedback fw-semibold">{{ $message }}</div>
                                        @enderror

                                    </div>

                                </div>

                                <button class="btn btn-primary w-100">
                                    Reset Password
                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);

            input.type =
                input.type === 'password'
                    ? 'text'
                    : 'password';
        }
    </script>

@endsection