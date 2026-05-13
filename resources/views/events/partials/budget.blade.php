@php
    $financial = $event->financial_summary;
    $budgetCount = $event->budgets->count();
@endphp

<div class="d-flex justify-content-between align-items-start mb-4 gap-3 flex-wrap">
    <div>
        <h5 class="fw-bold mb-1">Budget Proposal</h5>
        <p class="text-muted small mb-0">Isi rencana kebutuhan event per item. Ini belum realisasi uang.</p>
    </div>

    <a href="/events/{{ $event->id_event }}/expenses"
       class="btn btn-primary">
        Buka Finance
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="p-3 bg-light rounded-3">
            <small class="text-muted d-block">Total Proposal</small>
            <h4 class="mb-0">Rp {{ number_format($financial['total_budget']) }}</h4>
        </div>
    </div>
    <div class="col-md-4">
        <div class="p-3 bg-success-subtle rounded-3">
            <small class="text-muted d-block">Jumlah Item</small>
            <h4 class="mb-0">{{ $budgetCount }}</h4>
        </div>
    </div>
    <div class="col-md-4">
        <div class="p-3 bg-warning-subtle rounded-3">
            <small class="text-muted d-block">Estimasi Rencana</small>
            <h4 class="mb-0">Proposal Event</h4>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">Tambah Item Proposal</h6>
            <span class="badge bg-primary-subtle text-primary rounded-pill">Rencana</span>
        </div>
        <form method="POST" action="/events/{{ $event->id_event }}/budgets" class="row g-3">
            @csrf
            <div class="col-md-3">
                <label class="form-label small text-muted">Kategori</label>
                <select name="id_category" class="form-select" required>
                    <option value="">Pilih kategori</option>
                    @foreach($budgetCategories as $category)
                        <option value="{{ $category->id_category }}">{{ $category->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label small text-muted">Keterangan</label>
                <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Sewa sound system" required>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted">Qty</label>
                <input type="number" name="qty" class="form-control" min="1" required>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted">Nominal</label>
                <input type="number" name="nominal_rencana" class="form-control" min="0" required>
            </div>
            <div class="col-12">
                <button class="btn btn-primary rounded-pill px-4">Simpan Proposal</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr class="small text-muted text-uppercase">
                        <th class="ps-4">Kategori</th>
                        <th>Keterangan</th>
                        <th class="text-end">Qty</th>
                        <th class="text-end">Nominal</th>
                        <th class="text-end pe-4">Subtotal</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($event->budgets as $budget)
                        <tr>
                            <td class="ps-4">
                                <span class="badge bg-light text-dark border">
                                    {{ $budget->category->nama_kategori ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $budget->keterangan }}</div>
                                <small class="text-muted">Proposal item</small>
                                <small class="text-muted d-block">Diajukan oleh: {{ optional($budget->user)->name ?? '-' }}</small>
                                <small class="text-muted d-block text-nowrap">Dibuat: {{ $budget->created_at?->format('d M Y H:i') ?? '-' }}</small>
                                <small class="text-muted d-block text-nowrap">Update: {{ $budget->updated_at?->format('d M Y H:i') ?? '-' }}</small>
                            </td>
                            <td class="text-end">{{ $budget->qty }}</td>
                            <td class="text-end">Rp {{ number_format($budget->nominal_rencana) }}</td>
                            <td class="text-end pe-4 fw-bold text-success">Rp {{ number_format($budget->sub_total) }}</td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-primary budget-edit-btn"
                                        data-id="{{ $budget->id_budget }}"
                                        data-category="{{ $budget->id_category }}"
                                        data-keterangan="{{ e($budget->keterangan) }}"
                                        data-qty="{{ $budget->qty }}"
                                        data-nominal="{{ $budget->nominal_rencana }}">
                                        Edit
                                    </button>

                                    <form method="POST"
                                          action="/events/{{ $event->id_event }}/budgets/{{ $budget->id_budget }}"
                                          onsubmit="return confirm('Hapus budget ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-4 text-muted">Belum ada data proposal budget.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="editBudgetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-body p-4">
                <h5 class="fw-bold mb-3">Edit Budget Proposal</h5>

                <form id="editBudgetForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label small text-muted">Kategori</label>
                        <select name="id_category" id="edit_budget_category" class="form-select" required>
                            @foreach($budgetCategories as $category)
                                <option value="{{ $category->id_category }}">{{ $category->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted">Keterangan</label>
                        <input type="text" name="keterangan" id="edit_budget_keterangan" class="form-control" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label small text-muted">Qty</label>
                            <input type="number" name="qty" id="edit_budget_qty" class="form-control" min="1" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small text-muted">Nominal</label>
                            <input type="number" name="nominal_rencana" id="edit_budget_nominal" class="form-control" min="0" required>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light flex-fill" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary flex-fill">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.budget-edit-btn').forEach((btn) => {
    btn.addEventListener('click', function () {
        const budgetId = this.dataset.id;
        const form = document.getElementById('editBudgetForm');
        form.action = `/events/{{ $event->id_event }}/budgets/${budgetId}`;

        document.getElementById('edit_budget_category').value = this.dataset.category;
        document.getElementById('edit_budget_keterangan').value = this.dataset.keterangan;
        document.getElementById('edit_budget_qty').value = this.dataset.qty;
        document.getElementById('edit_budget_nominal').value = this.dataset.nominal;

        const modal = new bootstrap.Modal(document.getElementById('editBudgetModal'));
        modal.show();
    });
});
</script>