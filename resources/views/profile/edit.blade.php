@extends('layouts.app')

@section('content')
<div class="container px-4 pb-5">
    {{-- HEADER PROFILE --}}
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px; font-size: 1.5rem;">{{ __('Profile') }}</h2>
        <p class="text-muted small mb-0">Manage your account settings and profile information.</p>
    </div>

    <div class="row g-4">
        {{-- UPDATE PROFILE FORM --}}
        <div class="col-12">
            <div class="card p-4 border-0 shadow-sm custom-profile-card">
                <div class="card-body p-0">
                    <h5 class="fw-bold text-dark mb-1" style="font-size: 1.2rem;">Detail Profil</h5>
                    <p class="text-muted small mb-4">Isi informasi dasar profil akun Anda.</p>
                    
                    <div class="custom-form-wrapper">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>
        </div>

        {{-- CHANGE PASSWORD CARD --}}
        <div class="col-12">
            <div class="card p-4 border-0 shadow-sm custom-profile-card">
                <div class="card-body p-0 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-1" style="font-size: 1.2rem;">Keamanan Akun</h5>
                        <p class="text-muted small mb-0">Kelola dan perbarui kata sandi rahasia Anda secara berkala.</p>
                    </div>
                    <a href="{{ route('profile.password') }}" class="btn btn-custom-purple px-4 py-2 fw-semibold d-inline-flex align-items-center">
                        <i class="bi bi-key me-2"></i> Ubah Password
                    </a>
                </div>
            </div>
        </div>

        {{-- DELETE ACCOUNT FORM --}}
        <div class="col-12">
            <div class="card p-4 border-0 shadow-sm custom-profile-card">
                <div class="card-body p-0">
                    <h5 class="fw-bold text-dark text-danger mb-1" style="font-size: 1.2rem;">Hapus Akun</h5>
                    <p class="text-muted small mb-4">Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen.</p>
                    
                    <div class="custom-form-wrapper delete-section">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
  
    .custom-profile-card {
        border-radius: 20px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03) !important;
    }

    .custom-form-wrapper input[type="text"],
    .custom-form-wrapper input[type="email"],
    .custom-form-wrapper input[type="password"],
    .custom-form-wrapper select {
        width: 100%;
        padding: 12px 16px !important;
        font-size: 0.9rem !important;
        background-color: #f8fafc !important; 
        border: 1px solid #f1f5f9 !important;
        border-radius: 12px !important; 
        color: #334155 !important;
        transition: all 0.2s ease-in-out;
    }

    .custom-form-wrapper input:focus,
    .custom-form-wrapper select:focus {
        background-color: #ffffff !important;
        border-color: #4f46e5 !important;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important;
        outline: none;
    }

    .custom-form-wrapper label {
        font-weight: 600 !important;
        color: #475569 !important;
        margin-bottom: 8px !important;
        font-size: 0.875rem !important;
    }

    .btn-custom-purple, 
    .custom-form-wrapper button[type="submit"]:not(.btn-danger) {
        background-color: #4f46e5 !important;
        border-color: #4f46e5 !important;
        color: #ffffff !important;
        font-weight: 600 !important;
        font-size: 0.875rem !important;
        padding: 10px 24px !important;
        border-radius: 12px !important;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2) !important;
    }

    .btn-custom-purple:hover,
    .custom-form-wrapper button[type="submit"]:not(.btn-danger):hover {
        background-color: #4338ca !important;
        border-color: #4338ca !important;
        transform: translateY(-1px);
    }

    .custom-form-wrapper .btn-secondary,
    .custom-form-wrapper a.btn-light {
        background-color: #f1f5f9 !important;
        border: none !important;
        color: #475569 !important;
        font-weight: 600 !important;
        font-size: 0.875rem !important;
        padding: 10px 24px !important;
        border-radius: 12px !important;
    }

    .custom-form-wrapper > div {
        margin-bottom: 1.25rem !important;
    }
</style>
@endsection