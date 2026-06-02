@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold text-dark mb-1">Master Divisi</h2>
            <p class="text-muted mb-0">Kelola daftar divisi standar untuk setiap kegiatan GDGOC ISTTS.</p>
        </div>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        {{-- Sisi Kiri: Form Tambah --}}
        <div class="col-md-4">
            <div class="card p-4 border-0 shadow-sm" style="border-radius: 20px;">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary-subtle p-2 rounded-3 me-3 text-primary">
                        <i class="bi bi-diagram-3-fill fs-4"></i>
                    </div>
                    <h5 class="fw-bold mb-0">Tambah Divisi</h5>
                </div>

                <form method="POST" action="/divisions">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted ms-1">Nama Divisi Baru</label>
                        <input type="text" name="nama_divisi" 
                               class="form-control bg-light border-0 py-3 shadow-none @error('nama_divisi') is-invalid @enderror" 
                               placeholder="Contoh: Web Development" required>
                        @error('nama_divisi')
                            <div class="invalid-feedback ms-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm transition-all">
                        Simpan Divisi <i class="bi bi-plus-lg ms-2"></i>
                    </button>
                </form>
            </div>
        </div>

        {{-- Sisi Kanan: List Divisi --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 20px;">
                <div class="card-header bg-white py-4 px-4 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark">Daftar Divisi Tersedia</h5>
                    <span class="badge bg-light text-muted border rounded-pill px-3 py-2 small fw-normal">
                        Total: {{ count($divisions) }} Divisi
                    </span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase fw-bold">
                                <tr>
                                    <th class="ps-4 py-3 border-0">Nama Divisi</th>
                                    <th class="border-0 text-center">Status Sistem</th>
                                    <th class="text-end pe-4 border-0">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($divisions as $divisi)
                                    <tr class="transition-all border-bottom border-light">
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="bg-secondary-subtle text-secondary rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 38px; height: 38px; font-size: 14px;">
                                                    {{ strtoupper(substr($divisi->nama_divisi, 0, 1)) }}
                                                </div>
                                                <div class="fw-bold text-dark">{{ $divisi->nama_divisi }}</div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if($divisi->is_default)
                                                <span class="badge bg-success-subtle text-success rounded-pill px-3 border border-success-subtle shadow-sm">
                                                    <i class="bi bi-check-circle-fill me-1 small"></i> Default System
                                                </span>
                                            @else
                                                <span class="badge bg-light text-muted border rounded-pill px-3 shadow-sm fw-normal">
                                                    Custom Division
                                                </span>
                                            @endif
                                       </td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end gap-1">
                                                {{-- Tombol Edit --}}
                                                <button class="btn btn-sm btn-outline-secondary border-0 rounded-circle p-2" 
                                                        data-bs-toggle="modal" data-bs-target="#editModal{{ $divisi->id_divisi }}" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                {{-- Tombol Hapus --}}
                                                <button class="btn btn-sm btn-outline-danger border-0 rounded-circle p-2" 
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $divisi->id_divisi }}" title="Hapus">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Modal Edit --}}
                                    <div class="modal fade" id="editModal{{ $divisi->id_divisi }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow" style="border-radius: 20px;">
                                                <div class="modal-header border-0 pt-4 px-4">
                                                    <h5 class="fw-bold">Edit Divisi</h5>
                                                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="/divisions/{{ $divisi->id_divisi }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body px-4">
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-bold text-muted">Nama Divisi</label>
                                                            <input type="text" name="nama_divisi" class="form-control bg-light border-0 py-3 rounded-4 shadow-none" value="{{ $divisi->nama_divisi }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 pb-4 px-4">
                                                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Update Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Modal Hapus Custom --}}
                                    <div class="modal fade" id="deleteModal{{ $divisi->id_divisi }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-sm">
                                            <div class="modal-content border-0 shadow" style="border-radius: 20px;">
                                                <div class="modal-body p-4 text-center">
                                                    <div class="text-danger mb-3">
                                                        <i class="bi bi-exclamation-circle-fill" style="font-size: 3rem;"></i>
                                                    </div>
                                                    <h5 class="fw-bold mb-2">Hapus Divisi?</h5>
                                                    <p class="text-muted small mb-4">Hapus <strong>{{ $divisi->nama_divisi }}</strong>? Tindakan ini permanen.</p>
                                                    <div class="d-flex gap-2">
                                                        <button type="button" class="btn btn-light w-100 rounded-pill fw-bold" data-bs-dismiss="modal">Batal</button>
                                                        <form action="/divisions/{{ $divisi->id_divisi }}" method="POST" class="w-100">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger w-100 rounded-pill fw-bold">Ya, Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5">
                                            <div class="opacity-50">
                                                <i class="bi bi-diagram-2 display-4 d-block mb-2 text-muted"></i>
                                                <p class="mb-0">Belum ada data divisi master.</p>
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

<style>
    .transition-all { transition: all 0.2s ease; }
    .table-hover tbody tr:hover { background-color: rgba(66, 133, 244, 0.02) !important; }
    .btn:active { transform: scale(0.98); }
    .form-control:focus { background-color: #fff !important; box-shadow: 0 0 0 4px rgba(66, 133, 244, 0.1) !important; }
</style>
@endsection