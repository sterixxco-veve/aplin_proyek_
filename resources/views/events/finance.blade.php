@extends('layouts.app')

@section('content')
<div class="container pb-5">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">
            <i class="bi bi-cash-stack me-2 text-success"></i>Finance
        </h2>

        <a href="/events/{{ $eventId }}"
           class="btn btn-outline-secondary rounded-pill px-4 shadow-sm fw-bold">
            ← Kembali
        </a>
    </div>

    @php
        $financial = $summary ?? ['total_budget' => 0, 'total_expense' => 0, 'remaining' => 0];
    @endphp

    @php
        $reimbursedCount = $expenses->where('is_reimbursed', true)->count();
        $pendingReimburseCount = $expenses->where('is_reimbursed', false)->count();
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <small class="text-muted d-block mb-1">Total Proposal</small>
                    <h3 class="fw-bold mb-0">Rp {{ number_format($financial['total_budget']) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <small class="text-muted d-block mb-1">Total Realisasi</small>
                    <h3 class="fw-bold mb-0 text-danger">Rp {{ number_format($financial['total_expense']) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <small class="text-muted d-block mb-1">Sisa Anggaran</small>
                    <h3 class="fw-bold mb-0 text-success">Rp {{ number_format($financial['remaining']) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <small class="text-muted d-block mb-1">Reimburse</small>
                    <h3 class="fw-bold mb-0">{{ $reimbursedCount }}/{{ $pendingReimburseCount }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h5 class="fw-bold mb-1">Import / Export Finance</h5>
                    <p class="text-muted small mb-0">Untuk saat ini pakai CSV dulu; nanti bisa diganti ke template Excel.</p>
                </div>

                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <a href="{{ route('web.events.expenses.export', $eventId) }}" class="btn btn-outline-success rounded-pill px-4">
                        Export CSV
                    </a>

                    <form method="POST" action="{{ route('web.events.expenses.import', $eventId) }}" enctype="multipart/form-data" class="d-flex flex-wrap gap-2 align-items-center">
                        @csrf
                        <input type="file" name="finance_csv" class="form-control" accept=".csv,text/csv" required>
                        <button class="btn btn-primary rounded-pill px-4" type="submit">Import CSV</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3 gap-3 flex-wrap">
                <div>
                    <h5 class="fw-bold mb-1">LPJ & Reimbursement</h5>
                    <p class="text-muted small mb-0">Catat pengeluaran yang sudah benar-benar keluar, lalu lampirkan nota untuk LPJ.</p>
                    <small class="text-muted">Alur status: Pending → Accepted / Declined → Reimbursed.</small>
                </div>
                <span class="badge bg-success-subtle text-success rounded-pill">Realisasi</span>
            </div>

            <form id="expenseForm"
                  method="POST"
                  action="/events/{{ $eventId }}/expenses"
                  enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-12">
                        <input type="text" name="nama_pengeluaran" id="nama_pengeluaran"
                               class="form-control" placeholder="Contoh: Bayar venue" required>
                    </div>

                    <div class="col-md-4">
                        <select name="id_expense_category" id="kategori"
                                class="form-control" required>
                            <option value="">Pilih kategori expense</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id_expense_category }}">
                                    {{ $cat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <input type="number" name="nominal" id="nominal"
                               class="form-control" placeholder="Harga" required>
                    </div>

                    <div class="col-md-2">
                        <input type="number" name="qty" id="qty"
                               class="form-control" placeholder="Qty" required>
                    </div>

                    <div class="col-md-3">
                        <input type="number" name="nomor_rekening" id="rekening"
                               class="form-control" placeholder="Nomor rekening" required>
                    </div>

                    <div class="col-12">
                        <input type="file" name="bukti_nota" class="form-control">
                    </div>

                    <div class="col-12 d-flex gap-2">
                        <button id="submitBtn" class="btn btn-success px-4">
                            Catat Pengeluaran
                        </button>

                        <button type="button" id="cancelBtn"
                                class="btn btn-secondary"
                                style="display:none;">
                            Batalkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3">Daftar Realisasi</h5>

            @php $total = 0; @endphp

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-muted text-uppercase">
                            <th class="ps-4">Item</th>
                            <th>Kategori</th>
                            <th class="text-end">Harga</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Subtotal</th>
                            <th>Nota</th>
                            <th>Status</th>
                            <th>Alasan</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $exp)
                            @php
                                $total += $exp->sub_total;
                                $approvalStatus = $exp->approval_status ?? 'pending';
                                $isDeclined = in_array($approvalStatus, ['rejected', 'declined'], true);
                                $isLocked = in_array($approvalStatus, ['accepted', 'rejected', 'declined'], true);
                                $statusLabel = $isDeclined
                                    ? 'Declined'
                                    : ($exp->is_reimbursed
                                        ? 'Reimbursed'
                                        : ($approvalStatus === 'accepted' ? 'Accepted' : 'Pending'));
                                $statusClass = $isDeclined
                                    ? 'bg-danger-subtle text-danger'
                                    : ($exp->is_reimbursed
                                        ? 'bg-success-subtle text-success'
                                        : ($approvalStatus === 'accepted'
                                            ? 'bg-primary-subtle text-primary'
                                            : 'bg-warning-subtle text-warning'));
                            @endphp

                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold">{{ $exp->nama_pengeluaran }}</div>
                                    <small class="text-muted d-block">LPJ item</small>
                                    <small class="text-muted d-block">Diajukan oleh: {{ optional($exp->user)->name ?? '-' }}</small>
                                    <small class="text-muted d-block text-nowrap">Dibuat: {{ $exp->created_at?->format('d M Y H:i') ?? '-' }}</small>
                                    <small class="text-muted d-block text-nowrap">Update: {{ $exp->updated_at?->format('d M Y H:i') ?? '-' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $exp->category->nama_kategori ?? '-' }}</span>
                                </td>
                                <td class="text-end text-nowrap">Rp {{ number_format($exp->nominal) }}</td>
                                <td class="text-end text-nowrap">{{ $exp->qty }}</td>
                                <td class="text-end fw-bold text-success text-nowrap">Rp {{ number_format($exp->sub_total) }}</td>
                                <td class="text-nowrap">
                                    @if($exp->bukti_nota_path)
                                        <a href="{{ asset('storage/' . $exp->bukti_nota_path) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-semibold">
                                            Lihat nota
                                        </a>
                                    @else
                                        <span class="text-muted small">Belum ada</span>
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    <span class="badge {{ $statusClass }} rounded-pill">{{ $statusLabel }}</span>
                                </td>
                                <td>
                                    @if($isDeclined && filled($exp->rejection_reason))
                                        <small class="text-danger d-block">{{ $exp->rejection_reason }}</small>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4 text-nowrap">
                                    @if($isLocked)
                                        <span class="badge bg-secondary-subtle text-secondary rounded-pill">Terkunci</span>
                                    @else
                                        <div class="d-flex gap-2 justify-content-end">
                                            <button
                                                class="btn btn-sm btn-outline-warning edit-btn"
                                                data-id="{{ $exp->id_expense }}"
                                                data-nama="{{ $exp->nama_pengeluaran }}"
                                                data-kategori="{{ $exp->id_expense_category }}"
                                                data-nominal="{{ $exp->nominal }}"
                                                data-qty="{{ $exp->qty }}"
                                                data-rekening="{{ $exp->nomor_rekening }}">
                                                Edit
                                            </button>
                                            <form method="POST"
                                                  action="/expenses/{{ $exp->id_expense }}"
                                                  onsubmit="return confirm('Hapus expense ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                            </form>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-muted p-4">Belum ada realisasi pengeluaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <hr>

            <div class="d-flex justify-content-between fw-bold">
                <span>Total Realisasi</span>
                <span class="text-success">Rp {{ number_format($total) }}</span>
            </div>
        </div>
    </div>

</div>

{{-- PREVIEW MODAL --}}
<div id="image-preview-modal" style="
    display:none;
    position:fixed;
    top:0; left:0;
    width:100%; height:100%;
    background:rgba(0,0,0,0.8);
    justify-content:center;
    align-items:center;
    z-index:9999;
">
    <img id="preview-img"
         style="max-width:90%; max-height:90%; border-radius:12px;">
</div>

{{-- SCRIPT --}}
<script>

// EDIT CLICK
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function () {

        const exp = {
            id: this.dataset.id,
            nama_pengeluaran: this.dataset.nama,
            id_expense_category: this.dataset.kategori,
            nominal: this.dataset.nominal,
            qty: this.dataset.qty,
            nomor_rekening: this.dataset.rekening
        };

        editExpense(exp);
    });
});

function editExpense(exp) {
    document.getElementById('nama_pengeluaran').value = exp.nama_pengeluaran;
    document.getElementById('kategori').value = exp.id_expense_category;
    document.getElementById('nominal').value = exp.nominal;
    document.getElementById('qty').value = exp.qty;
    document.getElementById('rekening').value = exp.nomor_rekening;

    document.getElementById('expenseForm').action = `/expenses/${exp.id}`;

    if (!document.getElementById('methodInput')) {
        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = '_method';
        input.value = 'PUT';
        input.id = 'methodInput';
        document.getElementById('expenseForm').appendChild(input);
    }

    document.getElementById('submitBtn').innerText = 'Update';
    document.getElementById('submitBtn').classList.replace('btn-success', 'btn-warning');

    document.getElementById('cancelBtn').style.display = 'block';
}

// RESET
document.getElementById('cancelBtn').addEventListener('click', () => {
    document.getElementById('expenseForm').reset();
    document.getElementById('expenseForm').action = `/events/{{ $eventId }}/expenses`;

    let method = document.getElementById('methodInput');
    if (method) method.remove();

    document.getElementById('submitBtn').innerText = 'Tambah';
    document.getElementById('submitBtn').classList.replace('btn-warning', 'btn-success');

    document.getElementById('cancelBtn').style.display = 'none';
});

// PREVIEW
function previewImage(src) {
    document.getElementById('preview-img').src = src;
    document.getElementById('image-preview-modal').style.display = 'flex';
}

document.getElementById('image-preview-modal').addEventListener('click', () => {
    document.getElementById('image-preview-modal').style.display = 'none';
});

</script>

@endsection