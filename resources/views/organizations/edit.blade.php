@extends('layouts.app')

@section('content')
<div class="container pb-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/organizations" class="text-decoration-none text-muted">Organisasi</a></li>
            <li class="breadcrumb-item active fw-bold text-primary">Edit {{ $org->nama_org }}</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 24px;">
                <h4 class="fw-bold mb-4">Edit Informasi Organisasi</h4>
                
                {{-- Pastikan route ini sudah didefinisikan dengan ->name('organizations.update') di web.php --}}
                <form id="editOrgForm" action="{{ route('organizations.update', $org->id_org) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @if(session('success'))
                        <div class="alert alert-success border-0 rounded-3 d-flex align-items-center gap-2 mb-3">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Nama Organisasi</label>
                        <input type="text" id="nama_org" name="nama_org"
                            class="form-control bg-light border-0 py-3 rounded-4 shadow-none @error('nama_org') is-invalid @enderror"
                            value="{{ old('nama_org', $org->nama_org) }}" autocomplete="off">
                        @error('nama_org')
                            <div class="invalid-feedback fw-semibold">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Logo Organisasi (Opsional)</label>
                        <input type="file" id="logo_org" name="logo"
                            class="form-control bg-light border-0 py-3 rounded-4 shadow-none @error('logo') is-invalid @enderror"
                            accept="image/*">
                        @error('logo')
                            <div class="invalid-feedback fw-semibold">{{ $message }}</div>
                        @enderror
                        <small class="text-muted mt-1 d-block">Biarkan kosong jika tidak ingin mengubah logo.</small>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        {{-- Tombol simpan otomatis disabled saat pertama kali dimuat --}}
                        <button type="submit" id="btnSimpan" class="btn btn-primary rounded-pill px-4 fw-bold" disabled>
                            Simpan Perubahan
                        </button>
                        {{-- Pastikan route ini sudah didefinisikan dengan ->name('organizations.show') di web.php --}}
                        <a href="{{ route('organizations.show', $org->id_org) }}" class="btn btn-light border rounded-pill px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Logic JavaScript untuk Disabled Button --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('editOrgForm');
        const inputNama = document.getElementById('nama_org');
        const inputLogo = document.getElementById('logo_org');
        const btnSimpan = document.getElementById('btnSimpan');
        
        const namaAwal = inputNama.value.trim();

        function cekPerubahan() {
            const namaSekarang = inputNama.value.trim();
            const adaFile = inputLogo.files.length > 0;
            
            if (namaSekarang !== namaAwal || adaFile) {
                btnSimpan.disabled = false;
            } else {
                btnSimpan.disabled = true;
            }
        }

        inputNama.addEventListener('input', cekPerubahan);
        inputLogo.addEventListener('change', cekPerubahan);
    });
</script>

<style>
    .form-control:focus {
        background-color: #f8f9fa !important;
        border: 1px solid #0d6efd !important;
        box-shadow: none !important;
    }
    
    .btn:disabled {
        cursor: not-allowed;
        opacity: 0.6;
    }
</style>
@endsection