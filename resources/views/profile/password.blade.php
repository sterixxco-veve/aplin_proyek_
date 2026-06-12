@extends('layouts.app')

@section('content')
    <div class="container py-4">

        <div class="row justify-content-center">

            <div class="col-lg-6">

                <div class="card border-0 shadow-sm" style="border-radius:20px;">

                    <div class="card-body p-4">

                        <div class="mb-4">

                            <h2 class="fw-bold mb-1">
                                Change Password
                            </h2>

                            <p class="text-muted mb-0">
                                Update your account password.
                            </p>

                        </div>

                        @if (session('success'))
                            <div class="alert alert-success border-0 rounded-3 d-flex align-items-center gap-2">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <span>{{ session('success') }}</span>
                            </div>
                        @endif



                        <form method="POST" action="/profile/password">

                            @csrf
                            @method('PUT')

                            <div class="mb-3">

                                <label class="form-label fw-semibold">

                                    Current Password

                                </label>

                                <input type="password" name="current_password"
                                    class="form-control @error('current_password') is-invalid @enderror">

                                @error('current_password')
                                    <small class="text-danger">

                                        {{ $message }}

                                    </small>
                                @enderror

                            </div>

                            <div class="mb-3">

                                <label class="form-label fw-semibold">

                                    New Password

                                </label>

                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror">

                                @error('password')
                                    <small class="text-danger">

                                        {{ $message }}

                                    </small>
                                @enderror

                            </div>

                            <div class="mb-4">

                                <label class="form-label fw-semibold">

                                    Confirm New Password

                                </label>

                                <input type="password" name="password_confirmation"
                                    class="form-control @error('password_confirmation') is-invalid @enderror">

                                @error('password_confirmation')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>

                            <div class="d-flex gap-2">

                                <button type="submit" class="btn btn-primary px-4">

                                    Update Password

                                </button>

                                <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary">

                                    Cancel

                                </a>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection
