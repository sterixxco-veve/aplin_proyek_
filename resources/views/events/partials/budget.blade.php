@php
    $financial = $event->financial_summary;
    $budgetCount = $event->budgets->count();
@endphp

<div class="d-flex justify-content-between align-items-center mb-3 gap-2 flex-wrap">
    <div>
        <h5 class="fw-bold text-dark mb-0" style="font-size: 1.15rem;">Budget Proposal</h5>
        <p class="text-muted small mb-0" style="font-size: 0.825rem;">Isi rencana kebutuhan event per item. Ini belum
            realisasi uang.</p>
    </div>
</div>

<div class="row g-2 mb-4">
    
    <div class="col-md-6">
        <div class="p-25 rounded-3 border shadow-sm-light"
            style="background-color: #f8fafc; border-color: #e2e8f0 !important;">
            <small class="text-secondary fw-semibold d-block mb-1" style="font-size: 0.775rem;">
                <i class="bi bi-wallet2 me-1 text-primary"></i> Total Proposal
            </small>
            <h4 class="fw-bold text-dark mb-0" style="letter-spacing: -0.5px; font-size: 1.35rem;">
                Rp {{ number_format($financial['total_budget']) }}
            </h4>
        </div>
    </div>

    <div class="col-md-6">
        <div class="p-25 rounded-3 border shadow-sm-light"
            style="background-color: #f0fdf4; border-color: #d1fae5 !important;">
            <small class="text-success fw-semibold d-block mb-1" style="font-size: 0.775rem;">
                <i class="bi bi-box-seam me-1 text-success"></i> Jumlah Item
            </small>
            <h4 class="fw-bold text-success mb-0" style="letter-spacing: -0.5px; font-size: 1.35rem;">
                {{ $budgetCount }} <span class="fw-medium" style="font-size: 0.9rem;">Item</span>
            </h4>
        </div>
    </div>
</div>


<div class="card border mb-4 shadow-sm-light"
    style="background-color: #f8fafc; border-radius: 14px; border-color: #e2e8f0 !important;">
    <div class="card-body p-35">

        <div class="d-flex justify-content-between align-items-center mb-3 gap-2">
            <h6 class="fw-bold text-dark mb-0" style="font-size: 0.925rem;">
                <i class="bi bi-plus-circle-fill me-1.5 text-primary"></i>Tambah Item Proposal Baru
            </h6>
            <span class="badge bg-primary-subtle text-primary rounded-pill px-25 py-15 fw-bold"
                style="font-size: 0.75rem;">Rencana</span>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 rounded-3 d-flex align-items-center gap-2 mb-3 py-2 px-3"
                style="font-size: 0.85rem; background-color: #f0fdf4; color: #166534;">
                <i class="bi bi-check-circle-fill text-success"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form method="POST" action="/events/{{ $event->id_event }}/budgets">
            @csrf
            <div class="row g-2">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-secondary mb-1 ms-1">Kategori</label>
                    <select name="id_category"
                        class="form-select form-select-sm @error('id_category') is-invalid @enderror">
                        <option value="">Pilih kategori...</option>
                        @foreach ($budgetCategories as $category)
                            <option value="{{ $category->id_category }}"
                                {{ old('id_category') == $category->id_category ? 'selected' : '' }}>
                                {{ $category->nama_kategori }}</option>
                        @endforeach
                    </select>
                    @error('id_category')
                        <div class="invalid-feedback fw-medium mt-1" style="font-size: 0.775rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-5">
                    <label class="form-label small fw-semibold text-secondary mb-1 ms-1">Keterangan</label>
                    <input type="text" name="keterangan"
                        class="form-control form-control-sm @error('keterangan') is-invalid @enderror"
                        placeholder="Contoh: Sewa sound system utama" value="{{ old('keterangan') }}">
                    @error('keterangan')
                        <div class="invalid-feedback fw-medium mt-1" style="font-size: 0.775rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-secondary mb-1 ms-1">Qty</label>
                    <input type="number" name="qty"
                        class="form-control form-control-sm @error('qty') is-invalid @enderror" min="1"
                        value="{{ old('qty') }}">
                    @error('qty')
                        <div class="invalid-feedback fw-medium mt-1" style="font-size: 0.775rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-secondary mb-1 ms-1">Nominal (Rp)</label>
                    <input type="number" name="nominal_rencana"
                        class="form-control form-control-sm @error('nominal_rencana') is-invalid @enderror"
                        min="0" placeholder="Harga" value="{{ old('nominal_rencana') }}">
                    @error('nominal_rencana')
                        <div class="invalid-feedback fw-medium mt-1" style="font-size: 0.775rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mt-3 pt-1 border-top" style="border-color: #e2e8f0 !important;">
                    <button class="btn btn-primary btn-sm px-4 fw-semibold"
                        style="background-color: #4f46e5; border: none; border-radius: 8px; padding-top: 8px; padding-bottom: 8px;">
                        Simpan Proposal
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 style-table-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0" style="font-size: 0.85rem;">
                <thead class="table-light">
                    <tr class="small text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <th class="ps-3 py-2">Kategori</th>
                        <th class="py-2">Keterangan</th>
                        <th class="text-end py-2">Qty</th>
                        <th class="text-end py-2">Nominal</th>
                        <th class="text-end pe-3 py-2">Subtotal</th>
                        <th class="text-end pe-3 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($event->budgets as $budget)
                        <tr>
                            <td class="ps-3 py-25">
                                <span class="badge bg-white text-secondary border px-2 py-1"
                                    style="font-size: 0.725rem;">
                                    {{ $budget->category->nama_kategori ?? '-' }}
                                </span>
                            </td>
                            <td class="py-25">
                                <div class="fw-semibold text-dark">{{ $budget->keterangan }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">Oleh:
                                    {{ optional($budget->user)->name ?? '-' }}</div>
                                <div class="text-muted" style="font-size: 0.725rem;">Up:
                                    {{ $budget->updated_at?->format('d M Y H:i') ?? '-' }}</div>
                            </td>
                            <td class="text-end py-25 text-nowrap">{{ $budget->qty }}</td>
                            <td class="text-end py-25 text-nowrap">Rp {{ number_format($budget->nominal_rencana) }}
                            </td>
                            <td class="text-end pe-3 fw-bold text-success py-25 text-nowrap">Rp
                                {{ number_format($budget->sub_total) }}</td>
                            <td class="text-end pe-3 py-25 text-nowrap">
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button"
                                        class="btn btn-sm btn-link text-warning p-0 text-decoration-none fw-semibold budget-edit-btn"
                                        style="font-size: 0.8rem;" 
                                        data-bs-toggle="modal"
                                        data-bs-target="#editBudgetModal"
                                        data-id="{{ $budget->id_budget }}"
                                        data-category="{{ $budget->id_category }}"
                                        data-keterangan="{{ e($budget->keterangan) }}"
                                        data-qty="{{ $budget->qty }}"
                                        data-nominal="{{ $budget->nominal_rencana }}">
                                        Edit
                                    </button>

                                    <button type="button"
                                        class="btn btn-sm btn-link text-danger p-0 text-decoration-none fw-semibold budget-delete-btn"
                                        style="font-size: 0.8rem;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteBudgetModal"
                                        data-id="{{ $budget->id_budget }}"
                                        data-keterangan="{{ e($budget->keterangan) }}">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted p-4 text-center small">Belum ada data proposal
                                budget.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="editBudgetModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-0" style="font-size: 1.15rem; letter-spacing: -0.3px;">Edit
                            Budget Proposal</h5>
                        <p class="text-muted small mb-0" style="font-size: 0.8rem;">Ubah rincian anggaran kebutuhan
                            aktivitas event.</p>
                    </div>
                    <button type="button" class="btn-close small shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <form id="editBudgetForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-25">
                        <label class="form-label small fw-semibold text-secondary mb-1">Kategori</label>
                        <select name="id_category" id="edit_budget_category" class="form-select form-select-sm">
                            @foreach ($budgetCategories as $category)
                                <option value="{{ $category->id_category }}">{{ $category->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-25">
                        <label class="form-label small fw-semibold text-secondary mb-1">Keterangan</label>
                        <input type="text" name="keterangan" id="edit_budget_keterangan"
                            class="form-control form-control-sm">
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <label class="form-label small fw-semibold text-secondary mb-1">Qty</label>
                            <input type="number" name="qty" id="edit_budget_qty"
                                class="form-control form-control-sm" min="1">
                        </div>
                        <div class="col-8">
                            <label class="form-label small fw-semibold text-secondary mb-1">Nominal (Rp)</label>
                            <input type="number" name="nominal_rencana" id="edit_budget_nominal"
                                class="form-control form-control-sm" min="0">
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-2">
                        <button type="button" class="btn btn-light btn-sm flex-fill fw-medium"
                            data-bs-dismiss="modal"
                            style="border-radius: 8px; background-color: #f1f5f9;">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm flex-fill fw-semibold"
                            style="border-radius: 8px; background-color: #4f46e5; border: none;">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="deleteBudgetModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-body p-4 text-center">
                <div class="text-danger mb-3">
                    <i class="bi bi-exclamation-circle" style="font-size: 3rem;"></i>
                </div>
                <h6 class="fw-bold text-dark mb-2" style="font-size: 1.05rem;">Hapus Item Budget?</h6>
                <p class="text-muted small mb-4" id="delete_budget_text" style="font-size: 0.8rem; line-height: 1.4;"></p>

                <form id="deleteBudgetForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light btn-sm flex-fill fw-medium"
                            data-bs-dismiss="modal" style="border-radius: 8px; background-color: #f1f5f9;">Batal</button>
                        <button type="submit" class="btn btn-danger btn-sm flex-fill fw-semibold"
                            style="border-radius: 8px; border: none;">Ya, Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
   
    .p-25 {
        padding: 16px !important;
    }

    .p-35 {
        padding: 20px 24px !important;
    }

    .py-25 {
        padding-top: 8px !important;
        padding-bottom: 8px !important;
    }

    .mb-25 {
        margin-bottom: 12px !important;
    }

    .me-1.5 {
        margin-right: 6px !important;
    }

    .style-table-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
    }

    .shadow-sm-light {
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04);
    }

    .form-control-sm,
    .form-select-sm {
        border-radius: 8px !important;
        font-size: 0.85rem !important;
        padding: 8px 12px !important;
        border: 1.5px solid #e2e8f0 !important;
        background-color: #ffffff !important;
    }

    .form-control-sm:focus,
    .form-select-sm:focus {
        border-color: #4f46e5 !important;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important;
    }

    .btn-link {
        transition: opacity 0.2s ease;
    }

    .btn-link:hover {
        opacity: 0.8;
    }
</style>

<script>
    
    document.querySelectorAll('.budget-edit-btn').forEach((btn) => {
        btn.addEventListener('click', function() {
            const budgetId = this.dataset.id;
            const form = document.getElementById('editBudgetForm');
            form.action = `/events/{{ $event->id_event }}/budgets/${budgetId}`;

            document.getElementById('edit_budget_category').value = this.dataset.category;
            document.getElementById('edit_budget_keterangan').value = this.dataset.keterangan;
            document.getElementById('edit_budget_qty').value = this.dataset.qty;
            document.getElementById('edit_budget_nominal').value = this.dataset.nominal;
        });
    });

    
    document.querySelectorAll('.budget-delete-btn').forEach((btn) => {
        btn.addEventListener('click', function() {
            const budgetId = this.dataset.id;
            const keterangan = this.dataset.keterangan;
            const form = document.getElementById('deleteBudgetForm');
            
            form.action = `/events/{{ $event->id_event }}/budgets/${budgetId}`;
            document.getElementById('delete_budget_text').innerHTML = `Apakah Anda yakin ingin menghapus item <strong class="text-dark">"${keterangan}"</strong> dari proposal ini?`;
        });
    });
</script>