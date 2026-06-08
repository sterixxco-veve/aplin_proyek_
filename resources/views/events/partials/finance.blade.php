@php
    $financial = $financial_summary
        ?? $summary
        ?? ($event->financial_summary ?? ['total_budget' => 0, 'total_expense' => 0, 'remaining' => 0]);
    $canManageFinance = $event->canManageOperationalBy(auth()->user());
    $reimbursedCount = $expenses->where('is_reimbursed', true)->count();
    $pendingReimburseCount = $expenses
        ->where('approval_status', 'accepted')
        ->where('is_reimbursed', false)
        ->count();
@endphp

<div class="d-flex justify-content-between align-items-start mb-4 gap-3 flex-wrap">
    <div>
        <h5 class="fw-bold mb-1">Finance & Realisasi</h5>
        <p class="text-muted small mb-0">Kelola proposal anggaran dan catat realisasi pengeluaran dengan LPJ.</p>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-3">
        <div class="p-3 bg-light rounded-3">
            <small class="text-muted d-block">Total Proposal</small>
            <h4 class="mb-0">Rp {{ number_format($financial['total_budget']) }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="p-3 bg-danger-subtle rounded-3">
            <small class="text-muted d-block">Total Realisasi</small>
            <h4 class="mb-0">Rp {{ number_format($financial['total_expense']) }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="p-3 bg-success-subtle rounded-3">
            <small class="text-muted d-block">Sisa Anggaran</small>
            <h4 class="mb-0">Rp {{ number_format($financial['remaining']) }}</h4>
        </div>
    </div>
</div>
<!-- 
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h5 class="fw-bold mb-1">Import / Export Finance</h5>
                <p class="text-muted small mb-0">Untuk saat ini pakai CSV dulu; nanti bisa diganti ke template Excel.</p>
            </div>

            <div class="d-flex flex-wrap gap-2 align-items-center">
                <a href="{{ route('web.events.expenses.export', $event->id_event) }}" class="btn btn-outline-success rounded-pill px-4">
                    Export CSV
                </a>

                <form method="POST" action="{{ route('web.events.expenses.import', $event->id_event) }}" enctype="multipart/form-data" class="d-flex flex-wrap gap-2 align-items-center">
                    @csrf
                    <input type="file" name="finance_csv" class="form-control" accept=".csv,text/csv" required>
                    <button class="btn btn-primary rounded-pill px-4" type="submit">Import CSV</button>
                </form>
            </div>
        </div>
    </div>
</div> -->

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



        @if(session('success'))
            <div class="alert alert-success border-0 rounded-3 d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-check-circle-fill text-success"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger border-0 rounded-3 d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-exclamation-circle-fill text-danger"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form id="expenseForm"
              method="POST"
              action="/events/{{ $event->id_event }}/expenses"
              enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <div class="col-12">
                    <input type="text" name="nama_pengeluaran" id="nama_pengeluaran"
                           class="form-control @error('nama_pengeluaran') is-invalid @enderror"
                           placeholder="Contoh: Bayar venue"
                           value="{{ old('nama_pengeluaran') }}">
                    @error('nama_pengeluaran')
                        <div class="invalid-feedback fw-semibold">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <select name="id_expense_category" id="kategori"
                            class="form-control @error('id_expense_category') is-invalid @enderror">
                        <option value="">Pilih kategori expense</option>
                        @foreach($expenseCategories as $cat)
                            <option value="{{ $cat->id_expense_category }}" {{ old('id_expense_category') == $cat->id_expense_category ? 'selected' : '' }}>
                                {{ $cat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_expense_category')
                        <div class="invalid-feedback fw-semibold">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <input type="number" name="nominal" id="nominal"
                           class="form-control @error('nominal') is-invalid @enderror"
                           placeholder="Harga"
                           value="{{ old('nominal') }}">
                    @error('nominal')
                        <div class="invalid-feedback fw-semibold">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2">
                    <input type="number" name="qty" id="qty"
                           class="form-control @error('qty') is-invalid @enderror"
                           placeholder="Qty"
                           value="{{ old('qty') }}">
                    @error('qty')
                        <div class="invalid-feedback fw-semibold">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <input type="number" name="nomor_rekening" id="rekening"
                           class="form-control @error('nomor_rekening') is-invalid @enderror"
                           placeholder="Nomor rekening"
                           value="{{ old('nomor_rekening') }}">
                    @error('nomor_rekening')
                        <div class="invalid-feedback fw-semibold">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <input type="file" name="bukti_nota" class="form-control @error('bukti_nota') is-invalid @enderror">
                    @error('bukti_nota')
                        <div class="invalid-feedback fw-semibold">{{ $message }}</div>
                    @enderror
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
                            // Normalisasi status lama bila pernah ada data "declined".
                            // UI menampilkan Declined, tetapi database seharusnya menyimpan rejected.
                            $approvalStatus = $exp->approval_status ?? 'pending';
                            $normalizedStatus = $approvalStatus === 'declined' ? 'rejected' : $approvalStatus;
                            $isDeclined = $normalizedStatus === 'rejected';
                            $isAccepted = $normalizedStatus === 'accepted';
                            $isReimbursed = $isAccepted && (bool) $exp->is_reimbursed;
                            $isRealisasi = $isAccepted;

                            if ($isRealisasi) {
                                $total += $exp->sub_total;
                            }

                            $displayStatus = $isDeclined
                                ? 'declined'
                                : ($isReimbursed ? 'reimbursed' : $normalizedStatus);
                            $statusLabel = $isDeclined
                                ? 'Declined'
                                : ($isReimbursed
                                    ? 'Reimbursed'
                                    : ($isAccepted ? 'Accepted' : 'Pending'));
                            $statusClass = $isDeclined
                                ? 'bg-danger-subtle text-danger'
                                : ($isReimbursed
                                    ? 'bg-success-subtle text-success'
                                    : ($isAccepted
                                        ? 'bg-primary-subtle text-primary'
                                        : 'bg-warning-subtle text-warning'));
                        @endphp

                        <tr>
                            <td class="ps-4">
                                <div class="fw-semibold">{{ $exp->nama_pengeluaran }}</div>
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
                                @if($exp->nomor_rekening)
                                    <small class="text-muted d-block mb-1">{{ $exp->nomor_rekening }}</small>
                                @endif
                                @if($exp->bukti_nota_path)
                                    <a href="{{ asset('storage/' . $exp->bukti_nota_path) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-semibold">
                                        Lihat nota
                                    </a>
                                @else
                                    <span class="text-muted small">Belum ada</span>
                                @endif
                            </td>
                            <td class="text-nowrap">
                                @if($canManageFinance)
                                    <select class="form-select form-select-sm status-dropdown" data-id="{{ $exp->id_expense }}" data-current-status="{{ $displayStatus }}" style="width: auto; min-width: 120px;">
                                        <option value="pending" {{ $normalizedStatus === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="accepted" {{ $isAccepted && !$isReimbursed ? 'selected' : '' }}>Accepted</option>
                                        <option value="declined" {{ $isDeclined ? 'selected' : '' }}>Declined</option>
                                        <option value="reimbursed" {{ $isReimbursed ? 'selected' : '' }}>Reimbursed</option>
                                    </select>
                                @else
                                    <span class="badge {{ $statusClass }} rounded-pill">{{ $statusLabel }}</span>
                                @endif
                            </td>
                            <td>
                                @if($isDeclined && filled($exp->rejection_reason))
                                    <small class="text-danger d-block">{{ $exp->rejection_reason }}</small>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-end pe-4 text-nowrap">
                                @if($canManageFinance)
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
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill">Upload only</span>
                                @endif
                            </td>
                        </tr>
                        <tr id="decline-row-{{ $exp->id_expense }}" class="decline-row d-none">
                            <td colspan="9" class="px-4 pb-4">
                                <div class="card border-danger-subtle bg-danger-subtle">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                                            <div class="flex-grow-1">
                                                <div class="fw-bold mb-1">Alasan Declined</div>
                                                <p class="text-muted small mb-2">Tuliskan alasan penolakan sebelum status disimpan.</p>
                                                <textarea
                                                    class="form-control decline-reason-input"
                                                    rows="3"
                                                    data-id="{{ $exp->id_expense }}"
                                                    placeholder="Contoh: Tidak sesuai budget proposal">{{ $exp->rejection_reason }}</textarea>
                                            </div>
                                            <div class="d-flex gap-2 ms-auto">
                                                <button type="button" class="btn btn-light cancel-decline-btn" data-id="{{ $exp->id_expense }}">
                                                    Batal
                                                </button>
                                                <button type="button" class="btn btn-danger save-decline-btn" data-id="{{ $exp->id_expense }}">
                                                    Simpan Alasan
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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

// STATUS DROPDOWN CHANGE
document.querySelectorAll('.status-dropdown').forEach(select => {
    select.addEventListener('change', function() {
        const expenseId = this.dataset.id;
        const newStatus = this.value;
        const previousStatus = this.dataset.currentStatus || 'pending';

        if (newStatus === 'declined') {
            showDeclineCard(expenseId);
            return;
        }

        hideDeclineCard(expenseId);
        updateExpenseStatus(expenseId, newStatus, null, () => {
            window.location.reload();
        }, () => {
            this.value = previousStatus;
        });
    });
});

document.querySelectorAll('.save-decline-btn').forEach(button => {
    button.addEventListener('click', function () {
        const expenseId = this.dataset.id;
        const textarea = document.querySelector(`.decline-reason-input[data-id="${expenseId}"]`);
        const reason = textarea?.value?.trim();
        const select = document.querySelector(`.status-dropdown[data-id="${expenseId}"]`);

        if (!reason) {
            alert('Alasan declined wajib diisi.');
            textarea?.focus();
            return;
        }

        updateExpenseStatus(expenseId, 'declined', reason, () => {
            window.location.reload();
        }, () => {
            if (select) {
                select.value = select.dataset.currentStatus || 'pending';
            }
        });
    });
});

document.querySelectorAll('.cancel-decline-btn').forEach(button => {
    button.addEventListener('click', function () {
        const expenseId = this.dataset.id;
        const select = document.querySelector(`.status-dropdown[data-id="${expenseId}"]`);
        if (select) {
            select.value = select.dataset.currentStatus || 'pending';
        }
        hideDeclineCard(expenseId);
    });
});

function showDeclineCard(expenseId) {
    const row = document.getElementById(`decline-row-${expenseId}`);
    if (row) {
        row.classList.remove('d-none');
    }
}

function hideDeclineCard(expenseId) {
    const row = document.getElementById(`decline-row-${expenseId}`);
    if (row) {
        row.classList.add('d-none');
    }
}

function updateExpenseStatus(expenseId, status, rejectionReason = null, onSuccess = null, onError = null) {
    fetch(`/expenses/${expenseId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
            status,
            rejection_reason: rejectionReason,
        }),
    })
    .then(async response => {
        const contentType = response.headers.get('content-type') || '';

        if (contentType.includes('application/json')) {
            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Gagal update status');
            }

            return data;
        }

        const text = await response.text();
        throw new Error(text || 'Gagal update status');
    })
    .then(data => {
        if (typeof onSuccess === 'function') {
            onSuccess(data);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(error.message || 'Gagal update status');
        if (typeof onError === 'function') {
            onError(error);
        }
    });
}

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
    document.getElementById('expenseForm').action = `/events/{{ $event->id_event }}/expenses`;

    let method = document.getElementById('methodInput');
    if (method) method.remove();

    document.getElementById('submitBtn').innerText = 'Catat Pengeluaran';
    document.getElementById('submitBtn').classList.replace('btn-warning', 'btn-success');

    document.getElementById('cancelBtn').style.display = 'none';
});

// PREVIEW
function previewImage(src) {
    document.getElementById('image-preview-modal').style.display = 'flex';
    document.getElementById('preview-img').src = src;
}

document.getElementById('image-preview-modal').addEventListener('click', function (e) {
    if (e.target === this) {
        this.style.display = 'none';
    }
});

</script>
