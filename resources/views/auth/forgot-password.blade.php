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
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            padding: 50px;
        }

        .auth-right {
            padding: 50px;
            background: white;
        }

        .icon-box {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            background: rgba(255, 255, 255, .15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
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

        @media(max-width:768px) {
            .auth-left {
                display: none;
            }

            .auth-right {
                padding: 30px;
            }
        }
    </style>

    <div class="container py-5">

        ```
        <div class="row justify-content-center align-items-center min-vh-100">

            <div class="col-lg-10">

                <div class="card auth-card">

                    <div class="row g-0">

                        <div class="col-lg-5 auth-left">

                            <div class="icon-box mb-4">
                                🔑
                            </div>

                            <h2 class="fw-bold">
                                Forgot Password?
                            </h2>

                            <p class="opacity-75 mt-3">
                                Don't worry. Enter your email and we'll send you a secure password reset link.
                            </p>

                        </div>

                        <div class="col-lg-7 auth-right">

                            <h3 class="fw-bold mb-2">
                                Reset Your Password
                            </h3>

                            <p class="text-muted mb-4">
                                Enter your registered email address.
                            </p>

                            @if(session('status'))
                                <div class="alert alert-success rounded-3">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('password.email') }}">

                                @csrf

                                <div class="mb-4">

                                    <label class="form-label fw-semibold">
                                        Email Address
                                    </label>

                                    <input type="email" name="email" class="form-control" placeholder="you@example.com"
                                        required>

                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    Send Reset Link
                                </button>

                            </form>

                            <div class="text-center mt-4">

                                <a href="{{ route('login') }}" class="text-decoration-none">
                                    ← Back to Login
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection