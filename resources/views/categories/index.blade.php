@extends('layouts.app')

@section('content')
    <div class="container-fluid py-2">
        <div class="row justify-content-center">
            {{-- Membatasi lebar grid agar tabel tidak melar terlalu lebar ke kanan --}}
            <div class="col-12 col-xl-10">

                {{-- Header Section --}}
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                    <div>
                        <h3 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px;">Master Kategori Keuangan</h3>
                        <p class="text-muted small mb-0">Kelola master label alokasi dana untuk sistem Budgeting dan Expense
                            Event.</p>
                    </div>
                    <button
                        class="btn btn-primary rounded-pill shadow-sm px-4 fw-semibold d-flex align-items-center btn-sm-custom"
                        data-bs-toggle="modal" data-bs-target="#addCategoryModal"
                        style="background-color: #4f46e5; border: none; padding: 9px 20px; font-size: 0.875rem;">
                        <i class="bi bi-plus-lg me-2"></i>Tambah Kategori
                    </button>
                </div>

                {{-- Toast Alerts --}}
                @if (session('success'))
                    <div class="alert alert-success border-0 rounded-3 d-flex align-items-center gap-2 mb-4 py-2 px-3 small animate-fade-in"
                        style="background-color: #f0fdf4; color: #166534; font-size: 0.825rem;">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger border-0 rounded-3 d-flex align-items-center gap-2 mb-4 py-2 px-3 small animate-fade-in"
                        style="background-color: #fef2f2; color: #991b1b; font-size: 0.825rem;">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                {{-- Table Card Wrapper --}}
                <div class="card border-0 shadow-sm style-custom-card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light-header">
                                    <tr class="text-secondary text-uppercase fw-bold"
                                        style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                        <th class="ps-4 py-3 text-center" style="width: 8%">No</th>
                                        <th class="py-3" style="width: 32%">Nama Kategori</th>
                                        <th class="py-3" style="width: 35%">Deskripsi (Budget Only)</th>
                                        <th class="text-center py-3" style="width: 15%">Status Digunakan</th>
                                        <th class="text-center pe-4 py-3" style="width: 10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    @forelse($categories as $index => $cat)
                                        <tr class="table-row-hover">
                                            <td class="ps-4 text-center fw-medium text-secondary"
                                                style="font-size: 0.85rem;">{{ $index + 1 }}</td>
                                            <td>
                                                <div class="fw-bold text-dark d-flex align-items-center gap-2"
                                                    style="font-size: 0.875rem;">
                                                    <div class="icon-tag-wrapper">
                                                        <i class="bi bi-tag-fill style-tag-icon"></i>
                                                    </div>
                                                    {{ $cat->nama_kategori }}
                                                </div>
                                            </td>
                                            <td class="text-muted" style="font-size: 0.825rem;">
                                                {{ $cat->deskripsi ?? '-' }}
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge rounded-pill px-25 {{ $cat->event_budgets_count > 0 ? 'bg-indigo-subtle text-indigo' : 'bg-light text-muted border' }}">
                                                    {{ $cat->event_budgets_count }} Item Anggaran
                                                </span>
                                            </td>
                                            <td class="text-center pe-4">
                                                <div class="d-flex justify-content-center gap-3">
                                                    <button type="button"
                                                        class="btn btn-link text-warning p-0 text-decoration-none fw-bold small-action-btn edit-category-btn"
                                                        data-id="{{ $cat->id_category }}"
                                                        data-name="{{ e($cat->nama_kategori) }}"
                                                        data-description="{{ e($cat->deskripsi) }}">
                                                        Edit
                                                    </button>
                                                    <button type="button"
                                                        class="btn btn-link text-danger p-0 text-decoration-none fw-bold small-action-btn delete-category-btn"
                                                        data-id="{{ $cat->id_category }}"
                                                        data-name="{{ e($cat->nama_kategori) }}">
                                                        Hapus
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="p-5 text-muted text-center row-empty-state">
                                                <div class="empty-icon-box mx-auto mb-3">
                                                    <i class="bi bi-tags fs-3"></i>
                                                </div>
                                                <h6 class="fw-bold text-dark mb-1">Belum Ada Kategori</h6>
                                                <p class="text-muted small mb-0">Kategori keuangan yang Anda tambahkan akan
                                                    otomatis muncul di sini.</p>
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

    {{-- MODAL 1: TAMBAH KATEGORI --}}
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-custom-width">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="fw-bold text-dark mb-0" style="letter-spacing: -0.3px;">Tambah Kategori Baru</h5>
                            <p class="text-muted small mb-0">Label ini otomatis tersinkron ke data Budget dan Expense.</p>
                        </div>
                        <button type="button" class="btn-close small shadow-none" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="{{ route('categories.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary mb-1 ms-1">Nama Kategori <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="nama_kategori" class="form-control form-control-sm"
                                placeholder="Contoh: Operasional, Konsumsi, Perlengkapan" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary mb-1 ms-1">Deskripsi <span
                                    class="text-muted font-normal">(Hanya tersimpan di sistem Budget)</span></label>
                            <textarea name="deskripsi" class="form-control form-control-sm" rows="3"
                                placeholder="Penjelasan opsional alokasi dana..."></textarea>
                        </div>
                        <div class="d-flex gap-2 mt-4 pt-2 border-top" style="border-color: #f1f5f9 !important;">
                            <button type="button" class="btn btn-light btn-sm flex-fill fw-medium py-2"
                                data-bs-dismiss="modal"
                                style="border-radius: 8px; background-color: #f1f5f9; color: #475569;">Batal</button>
                            <button type="submit" class="btn btn-primary btn-sm flex-fill fw-semibold py-2"
                                style="background-color: #4f46e5; border: none; border-radius: 8px;">Simpan
                                Kategori</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL 2: EDIT KATEGORI --}}
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-custom-width">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="fw-bold text-dark mb-0" style="letter-spacing: -0.3px;">Edit Kategori Anggaran</h5>
                            <p class="text-muted small mb-0">Perbarui informasi master label keuangan ini.</p>
                        </div>
                        <button type="button" class="btn-close small shadow-none" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="editCategoryForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary mb-1 ms-1">Nama Kategori <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="nama_kategori" id="edit_nama_kategori"
                                class="form-control form-control-sm" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary mb-1 ms-1">Deskripsi <span
                                    class="text-muted font-normal">(Hanya tersimpan di sistem Budget)</span></label>
                            <textarea name="deskripsi" id="edit_deskripsi" class="form-control form-control-sm" rows="3"></textarea>
                        </div>
                        <div class="d-flex gap-2 mt-4 pt-2 border-top" style="border-color: #f1f5f9 !important;">
                            <button type="button" class="btn btn-light btn-sm flex-fill fw-medium py-2"
                                data-bs-dismiss="modal"
                                style="border-radius: 8px; background-color: #f1f5f9; color: #475569;">Batal</button>
                            <button type="submit" class="btn btn-primary btn-sm flex-fill fw-semibold py-2"
                                style="background-color: #4f46e5; border: none; border-radius: 8px;">Update Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL 3: CONFIRM DELETE --}}
    <div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-body p-4 text-center">
                    <div class="mb-3 text-danger">
                        <div class="alert-icon-box mx-auto">
                            <i class="bi bi-exclamation-circle-fill fs-3"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Hapus Kategori?</h5>
                    <p class="text-muted small mb-4">Apakah Anda yakin menghapus kategori <strong
                            id="delete_title_display" class="text-dark"></strong>?</p>
                    <form id="deleteCategoryForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-light btn-sm flex-fill fw-medium py-2"
                                data-bs-dismiss="modal"
                                style="border-radius: 8px; background-color: #f1f5f9; color: #475569;">Batal</button>
                            <button type="submit" class="btn btn-danger btn-sm flex-fill fw-semibold py-2"
                                style="border-radius: 8px;">Ya, Hapus</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // EVENT EDIT BUTTON CLICK
            document.querySelectorAll('.edit-category-btn').forEach((btn) => {
                btn.addEventListener('click', function() {
                    const form = document.getElementById('editCategoryForm');
                    form.action = `/categories/${this.dataset.id}`;
                    document.getElementById('edit_nama_kategori').value = this.dataset.name;
                    document.getElementById('edit_deskripsi').value = this.dataset.description ||
                    '';

                    const modal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
                    modal.show();
                });
            });

            // EVENT DELETE BUTTON CLICK
            document.querySelectorAll('.delete-category-btn').forEach((btn) => {
                btn.addEventListener('click', function() {
                    const form = document.getElementById('deleteCategoryForm');
                    form.action = `/categories/${this.dataset.id}`;
                    document.getElementById('delete_title_display').textContent = this.dataset.name;

                    const modal = new bootstrap.Modal(document.getElementById(
                        'deleteCategoryModal'));
                    modal.show();
                });
            });
        });
    </script>

    <style>
        /* HAPUS KELAS MARGIN-LEFT BERGANDA AGAR TIDAK MENJAUH */
        .style-custom-card {
            border: 1px solid #e2e8f0 !important;
            border-radius: 16px !important;
            background-color: #ffffff;
            overflow: hidden;
        }

        .table-light-header {
            background-color: #f8fafc !important;
            border-bottom: 1.5px solid #e2e8f0 !important;
        }

        .table-light-header th {
            padding-top: 14px !important;
            padding-bottom: 14px !important;
            color: #64748b !important;
        }

        .table-row-hover {
            transition: background-color 0.15s ease;
        }

        .table-row-hover:hover {
            background-color: #fcfdfe;
        }

        .icon-tag-wrapper {
            width: 28px;
            height: 28px;
            background-color: #eef2ff;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .style-tag-icon {
            color: #4f46e5;
            font-size: 0.85rem;
        }

        .bg-indigo-subtle {
            background-color: #e0e7ff !important;
        }

        .text-indigo {
            color: #4f46e5 !important;
        }

        .font-normal {
            font-weight: normal !important;
        }

        .px-25 {
            padding: 5px 12px !important;
            font-size: 11px !important;
            font-weight: 600 !important;
        }

        .small-action-btn {
            font-size: 0.825rem !important;
        }

        /* Style Input Form */
        .form-control-sm {
            border-radius: 8px !important;
            font-size: 0.85rem !important;
            padding: 10px 14px !important;
            border: 1.5px solid #e2e8f0 !important;
        }

        .form-control-sm:focus {
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important;
        }

        /* Empty State Cosmetics */
        .empty-icon-box {
            width: 56px;
            height: 56px;
            background-color: #f1f5f9;
            color: #94a3b8;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .alert-icon-box {
            width: 48px;
            height: 48px;
            background-color: #fef2f2;
            color: #ef4444;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (max-width: 576px) {
            .modal-custom-width {
                margin: 1.75rem 1rem !important;
            }
        }
    </style>
@endsection
