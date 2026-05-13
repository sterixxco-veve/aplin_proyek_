@extends('layouts.app')

@section('content')
<div class="container pb-5">
    {{-- Breadcrumb Navigation --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/organizations" class="text-decoration-none text-muted">Organisasi</a></li>
            <li class="breadcrumb-item active fw-bold text-primary" aria-current="page">{{ $org->nama_org }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        {{-- 🏢 Organization Profile Header --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm p-4 overflow-hidden" style="border-radius: 24px;">
                <div class="d-flex flex-column flex-md-row align-items-center gap-4">
                    {{-- Logo Section --}}
                    <div class="position-relative">
                        <div class="bg-white rounded-circle shadow-sm p-1 border border-light" style="width: 120px; height: 120px;">
                            @if($org->logo_path)
                                <img src="{{ asset('storage/' . $org->logo_path) }}" class="rounded-circle w-100 h-100 object-fit-cover">
                            @else
                                <div class="bg-primary-subtle text-primary rounded-circle w-100 h-100 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-building fs-1"></i>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Info Section --}}
                    <div class="text-center text-md-start flex-grow-1">
                        <h2 class="fw-bold text-dark mb-1">{{ $org->nama_org }}</h2>
                        <div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-3 mt-2">
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 border shadow-sm">
                                <i class="bi bi-patch-check-fill me-1"></i> Verified Organization
                            </span>
                            <span class="text-muted small d-flex align-items-center">
                                <i class="bi bi-people-fill me-1 text-primary"></i> {{ count($org->members) }} Anggota Terdaftar
                            </span>
                        </div>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="d-flex gap-2">
                        <a href="/divisions" class="btn btn-outline-primary rounded-pill">
                            <i class="bi bi-gear me-2"></i>Master Divisi
                        </a>
                        <a href="{{ route('organizations.edit', $org->id_org) }}" class="btn btn-light rounded-pill px-3 shadow-sm border">
                            <i class="bi bi-pencil-square me-1"></i> Edit Info
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- 🔑 Admin Actions: Invite Member --}}
        @php
            $isAdmin = $org->members->contains(function ($member) {
                return $member->id_user === auth()->user()->id_user 
                    && $member->pivot->role === 'super_admin';
            });
        @endphp

        @if($isAdmin)
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 text-dark d-flex align-items-center">
                            <div class="bg-primary text-white rounded-3 p-2 me-3 shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-person-plus-fill"></i>
                            </div>
                            Undang Anggota
                        </h5>
                        
                        <p class="text-muted small mb-4">Masukkan email anggota baru untuk memberikan akses ke organisasi ini.</p>

                        <form method="POST" action="/organizations/{{ $org->id_org }}/invite">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted ms-1">Alamat Email</label>
                                <input type="email" name="email" class="form-control bg-light border-0 py-3 rounded-4 shadow-none" placeholder="nama@email.com" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm mt-2 transition-transform">
                                Kirim Undangan <i class="bi bi-send-fill ms-2 small"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        {{-- 👥 Member List Table --}}
        <div class="{{ $isAdmin ? 'col-lg-8' : 'col-12' }}">
            <div class="card border-0 shadow-sm overflow-hidden h-100" style="border-radius: 20px;">
                <div class="card-header bg-white py-4 px-4 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark">Daftar Anggota Tim</h5>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="small text-muted text-uppercase">
                                    <th class="ps-4 py-3 border-0">Nama & Profil</th>
                                    <th class="border-0">Kontak</th>
                                    <th class="border-0">Jabatan / Role</th>
                                    <th class="text-end pe-4 border-0">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($org->members as $member)
                                    <tr class="transition-all">
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 38px; height: 38px; font-size: 14px;">
                                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark">{{ $member->name }}</div>
                                                    <small class="text-muted">ID: {{ substr($member->id_user, 0, 8) }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-muted small">
                                            {{ $member->email }}
                                        </td>
                                        <td>
                                            @php
                                                $role = $member->pivot->role;
                                                $badgeClass = ($role === 'super_admin') ? 'bg-danger-subtle text-danger' : 'bg-light text-dark';
                                            @endphp
                                            <span class="badge {{ $badgeClass }} border rounded-pill px-3 py-2 shadow-sm small">
                                                <i class="bi {{ $role === 'super_admin' ? 'bi-shield-lock-fill' : 'bi-person-fill' }} me-1"></i>
                                                {{ ucfirst($role) }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            @if($isAdmin && $member->id_user !== auth()->id())
                                                <button class="btn btn-sm btn-outline-danger border-0 rounded-circle" title="Hapus Member">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            @else
                                                <span class="text-muted small">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <div class="opacity-50">
                                                <i class="bi bi-people display-4 d-block mb-2"></i>
                                                <p class="mb-0">Belum ada member yang bergabung.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .breadcrumb-item + .breadcrumb-item::before {
        content: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E");
        vertical-align: middle;
    }

    .form-control:focus {
        background-color: #fff !important;
        box-shadow: 0 0 0 4px rgba(66, 133, 244, 0.1) !important;
    }

    .transition-all {
        transition: all 0.2s ease;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(66, 133, 244, 0.02) !important;
    }

    .transition-transform:active {
        transform: scale(0.98);
    }
</style>
@endsection