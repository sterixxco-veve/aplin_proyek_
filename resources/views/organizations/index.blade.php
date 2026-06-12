@extends('layouts.app')

@section('content')
    <div class="container">
        {{-- Header Section --}}
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold text-dark mb-1">Daftar Organisasi</h2>
                <p class="text-muted mb-0">Kelola seluruh unit organisasi GDGOC dalam satu tempat.</p>
            </div>
            <a href="/organizations/create" class="btn btn-primary rounded-pill shadow-sm px-4">
                <i class="bi bi-plus-lg me-2"></i>Tambah Organisasi
            </a>
        </div>

        {{-- Stats/Quick Info (Optional but makes it look professional) --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card p-3 border-0 shadow-sm bg-primary text-white" style="border-radius: 16px;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-white bg-opacity-25 rounded-3 p-2">
                            <i class="bi bi-building-check fs-4"></i>
                        </div>
                        <div>
                            <small class="d-block opacity-75">Total Organisasi</small>
                            <h4 class="fw-bold mb-0">{{ count($orgs) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Organizations Grid --}}
        <div class="row g-4">
            @forelse ($orgs as $org)
                <div class="col-md-4 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm card-hover" style="border-radius: 20px; overflow: hidden;">
                        {{-- Decorative Top Bar with Google Colors --}}
                        <div
                            style="height: 6px; background: linear-gradient(90deg, var(--gd-blue) 25%, var(--gd-red) 25% 50%, var(--gd-yellow) 50% 75%, var(--gd-green) 75%);">
                        </div>

                        <div class="card-body p-4 text-center">
                            {{-- Logo Placeholder/Avatar --}}
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 shadow-sm"
                                style="width: 70px; height: 70px;">
                                @if (isset($org->logo_path) && $org->logo_path)
                                    <img src="{{ asset('storage/' . $org->logo_path) }}"
                                        class="rounded-circle w-100 h-100 object-fit-cover">
                                @else
                                    <i class="bi bi-buildings fs-2 text-primary"></i>
                                @endif
                            </div>

                            <h5 class="fw-bold text-dark mb-1 text-truncate">{{ $org->nama_org }}</h5>
                            <p class="text-muted small mb-4">
                                <i class="bi bi-people me-1"></i> {{ count($org->members ?? []) }} Members
                            </p>

                            <a href="/organizations/{{ $org->id_org }}"
                                class="btn btn-outline-primary btn-sm rounded-pill w-100 fw-bold">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm p-5 text-center" style="border-radius: 24px;">
                        <div class="mb-4">
                            <i class="bi bi-search display-1 text-muted opacity-25"></i>
                        </div>
                        <h4 class="fw-bold text-dark">Belum ada organisasi</h4>
                        <p class="text-muted">Mulai dengan menambahkan organisasi pertama Anda untuk mengelola event.</p>
                        <div class="mt-3">
                            <a href="/organizations/create" class="btn btn-primary rounded-pill px-4 fw-bold">
                                Buat Organisasi Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <style>
        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08) !important;
        }

        .text-truncate {
            max-width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
@endsection
