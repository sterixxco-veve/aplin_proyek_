@php
    $financial =
        $financial_summary ??
        ($summary ?? ($event->financial_summary ?? ['total_budget' => 0, 'total_expense' => 0, 'remaining' => 0]));
    $canManageFinance = $event->canManageOperationalBy(auth()->user());
    $reimbursedCount = $expenses->where('is_reimbursed', true)->count();
    $pendingReimburseCount = $expenses->where('approval_status', 'accepted')->where('is_reimbursed', false)->count();
@endphp

{{-- ========================= --}}
{{-- TITLE FINANCE --}}
{{-- ========================= --}}
<div class="d-flex justify-content-between align-items-center mb-3 gap-2 flex-wrap">
    <div>
        <h5 class="fw-bold text-dark mb-0" style="font-size: 1.15rem;">Finance & Realisasi</h5>
        <p class="text-muted small mb-0" style="font-size: 0.825rem;">Kelola proposal anggaran dan catat realisasi
            pengeluaran dengan LPJ.</p>
    </div>
</div>

{{-- ========================= --}}
{{-- METRIC SUMMARY BOXES --}}
{{-- ========================= --}}
<div class="row g-2 mb-4">
    <div class="col-md-4">
        <div class="p-25 bg-light rounded-3 border" style="border-color: #e2e8f0 !important;">
            <small class="text-secondary fw-medium d-block mb-1" style="font-size: 0.775rem;">Total Proposal</small>
            <h5 class="fw-bold text-dark mb-0" style="letter-spacing: -0.3px; font-size: 1.15rem;">Rp
                {{ number_format($financial['total_budget']) }}</h5>
        </div>
    </div>
    <div class="col-md-4">
        <div class="p-25 bg-danger-subtle rounded-3 border border-danger-subtle"
            style="background-color: #fef2f2 !important;">
            <small class="text-danger fw-medium d-block mb-1" style="font-size: 0.775rem;">Total Realisasi</small>
            <h5 class="fw-bold text-danger mb-0" style="letter-spacing: -0.3px; font-size: 1.15rem;">Rp
                {{ number_format($financial['total_expense']) }}</h5>
        </div>
    </div>
    <div class="col-md-4">
        <div class="p-25 bg-success-subtle rounded-3 border border-success-subtle"
            style="background-color: #f0fdf4 !important;">
            <small class="text-success fw-medium d-block mb-1" style="font-size: 0.775rem;">Sisa Anggaran</small>
            <h5 class="fw-bold text-success mb-0" style="letter-spacing: -0.3px; font-size: 1.15rem;">Rp
                {{ number_format($financial['remaining']) }}</h5>
        </div>
    </div>
</div>

{{-- ========================= --}}
{{-- FORM INPUT EXPENSE --}}
{{-- ========================= --}}
<div class="card border-0 mb-4" style="background-color: #f8fafc; border-radius: 12px;">
    <div class="card-body p-3">
        <div class="d-flex justify-content-between align-items-start mb-3 gap-3 flex-wrap">
            <div>
                <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;"><i
                        class="bi bi-wallet2 me-1 text-primary"></i> LPJ & Reimbursement</h6>
                <p class="text-muted small mb-0" style="font-size: 0.8rem;">Catat pengeluaran aktif, lampirkan nota
                    untuk LPJ. <span class="text-secondary fw-medium">Status: Pending → Accepted/Declined →
                        Reimbursed.</span></p>
            </div>
            <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1"
                style="font-size: 0.75rem; font-weight: 600;">Realisasi</span>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 rounded-3 d-flex align-items-center gap-2 mb-3 py-2 px-3"
                style="font-size: 0.85rem; background-color: #f0fdf4; color: #166534;">
                <i class="bi bi-check-circle-fill text-success"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger border-0 rounded-3 d-flex align-items-center gap-2 mb-3 py-2 px-3"
                style="font-size: 0.85rem; background-color: #fef2f2; color: #991b1b;">
                <i class="bi bi-exclamation-circle-fill text-danger"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form id="expenseForm" method="POST" action="/events/{{ $event->id_event }}/expenses"
            enctype="multipart/form-data">
            @csrf

            <div class="row g-2">
                {{-- Row Atas: Nama Pengeluaran & Kategori --}}
                <div class="col-md-8">
                    <input type="text" name="nama_pengeluaran" id="nama_pengeluaran"
                        class="form-control form-control-sm @error('nama_pengeluaran') is-invalid @enderror"
                        placeholder="Nama pengeluaran (Contoh: Bayar venue)" value="{{ old('nama_pengeluaran') }}">
                    @error('nama_pengeluaran')
                        <div class="invalid-feedback fw-medium mt-1" style="font-size: 0.775rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <select name="id_expense_category" id="kategori"
                        class="form-select form-select-sm @error('id_expense_category') is-invalid @enderror">
                        <option value="">Pilih kategori...</option>
                        @foreach ($expenseCategories as $cat)
                            <option value="{{ $cat->id_expense_category }}"
                                {{ old('id_expense_category') == $cat->id_expense_category ? 'selected' : '' }}>
                                {{ $cat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_expense_category')
                        <div class="invalid-feedback fw-medium mt-1" style="font-size: 0.775rem;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Row Bawah: Harga, Qty, Rekening, File Nota --}}
                <div class="col-md-3">
                    <input type="number" name="nominal" id="nominal"
                        class="form-control form-control-sm @error('nominal') is-invalid @enderror"
                        placeholder="Harga Satuan (Rp)" value="{{ old('nominal') }}">
                    @error('nominal')
                        <div class="invalid-feedback fw-medium mt-1" style="font-size: 0.775rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2">
                    <input type="number" name="qty" id="qty"
                        class="form-control form-control-sm @error('qty') is-invalid @enderror" placeholder="Qty"
                        value="{{ old('qty') }}">
                    @error('qty')
                        <div class="invalid-feedback fw-medium mt-1" style="font-size: 0.775rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <input type="number" name="nomor_rekening" id="rekening"
                        class="form-control form-control-sm @error('nomor_rekening') is-invalid @enderror"
                        placeholder="Nomor rekening" value="{{ old('nomor_rekening') }}">
                    @error('nomor_rekening')
                        <div class="invalid-feedback fw-medium mt-1" style="font-size: 0.775rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <input type="file" name="bukti_nota"
                        class="form-control form-control-sm @error('bukti_nota') is-invalid @enderror">
                    @error('bukti_nota')
                        <div class="invalid-feedback fw-medium mt-1" style="font-size: 0.775rem;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Action Buttons Form --}}
                <div class="col-12 d-flex gap-2 mt-2">
                    <button id="submitBtn" class="btn btn-success btn-sm px-3 fw-semibold">
                        Catat Pengeluaran
                    </button>

                    <button type="button" id="cancelBtn" class="btn btn-secondary btn-sm px-3"
                        style="display:none;">
                        Batalkan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ========================= --}}
{{-- TABLE REALISASI --}}
{{-- ========================= --}}
<div class="card border-0 style-table-card mb-3">
    <div class="card-header bg-white border-bottom py-25 px-3">
        <h6 class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">Daftar Realisasi</h6>
    </div>
    <div class="card-body p-0">
        @php $total = 0; @endphp

        <div class="table-responsive">
            <table class="table align-middle mb-0" style="font-size: 0.85rem;">
                <thead class="table-light">
                    <tr class="small text-secondary text-uppercase"
                        style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <th class="ps-3 py-2">Item</th>
                        <th class="py-2">Kategori</th>
                        <th class="text-end py-2">Harga</th>
                        <th class="text-end py-2">Qty</th>
                        <th class="text-end py-2">Subtotal</th>
                        <th class="py-2">Nota / Rek</th>
                        <th class="py-2">Status</th>
                        <th class="py-2">Alasan</th>
                        <th class="text-end pe-3 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $exp)
                        @php
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
                                : ($isReimbursed
                                    ? 'reimbursed'
                                    : $normalizedStatus);
                            $statusLabel = $isDeclined
                                ? 'Declined'
                                : ($isReimbursed
                                    ? 'Reimbursed'
                                    : ($isAccepted
                                        ? 'Accepted'
                                        : 'Pending'));
                            $statusClass = $isDeclined
                                ? 'bg-danger-subtle text-danger'
                                : ($isReimbursed
                                    ? 'bg-success-subtle text-success'
                                    : ($isAccepted
                                        ? 'bg-primary-subtle text-primary'
                                        : 'bg-warning-subtle text-warning'));
                        @endphp

                        <tr>
                            <td class="ps-3 py-2">
                                <div class="fw-semibold text-dark">{{ $exp->nama_pengeluaran }}</div>
                                <div class="text-muted text-nowrap" style="font-size: 0.75rem;">Oleh:
                                    {{ optional($exp->user)->name ?? '-' }}</div>
                                <div class="text-muted text-nowrap" style="font-size: 0.725rem;">Up:
                                    {{ $exp->updated_at?->format('d M Y H:i') ?? '-' }}</div>
                            </td>
                            <td class="py-2">
                                <span class="badge bg-white text-secondary border px-2 py-1"
                                    style="font-size: 0.725rem;">{{ $exp->category->nama_kategori ?? '-' }}</span>
                            </td>
                            <td class="text-end text-nowrap py-2">Rp {{ number_format($exp->nominal) }}</td>
                            <td class="text-end text-nowrap py-2">{{ $exp->qty }}</td>
                            <td class="text-end fw-bold text-success text-nowrap py-2">Rp
                                {{ number_format($exp->sub_total) }}</td>
                            <td class="text-nowrap py-2">
                                @if ($exp->nomor_rekening)
                                    <div class="text-secondary font-monospace mb-1" style="font-size: 0.75rem;"><i
                                            class="bi bi-credit-card me-1"></i>{{ $exp->nomor_rekening }}</div>
                                @endif
                                @if ($exp->bukti_nota_path)
                                    <a href="{{ asset('storage/' . $exp->bukti_nota_path) }}" target="_blank"
                                        class="btn btn-sm btn-link text-primary p-0 text-decoration-none fw-semibold"
                                        style="font-size: 0.775rem;">
                                        <i class="bi bi-file-earmark-image me-1"></i>Lihat Nota
                                    </a>
                                @else
                                    <span class="text-muted small" style="font-size: 0.75rem;">Belum ada</span>
                                @endif
                            </td>
                            <td class="text-nowrap py-2">
                                @if ($canManageFinance)
                                    <select class="form-select form-select-sm status-dropdown"
                                        data-id="{{ $exp->id_expense }}" data-current-status="{{ $displayStatus }}"
                                        style="width: auto; min-width: 110px; font-size: 0.8rem; padding: 4px 8px;">
                                        <option value="pending"
                                            {{ $normalizedStatus === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="accepted"
                                            {{ $isAccepted && !$isReimbursed ? 'selected' : '' }}>Accepted</option>
                                        <option value="declined" {{ $isDeclined ? 'selected' : '' }}>Declined</option>
                                        <option value="reimbursed" {{ $isReimbursed ? 'selected' : '' }}>Reimbursed
                                        </option>
                                    </select>
                                @else
                                    <span class="badge {{ $statusClass }} rounded-pill px-2 py-1"
                                        style="font-size: 0.75rem;">{{ $statusLabel }}</span>
                                @endif
                            </td>
                            <td class="py-2">
                                @if ($isDeclined && filled($exp->rejection_reason))
                                    <small class="text-danger d-block lh-sm"
                                        style="font-size: 0.75rem; max-width: 130px; white-space: normal;">{{ $exp->rejection_reason }}</small>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-end pe-3 text-nowrap py-2">
                                @if ($canManageFinance)
                                    <div class="d-flex gap-2 justify-content-end">
                                        <button
                                            class="btn btn-sm btn-link text-warning p-0 text-decoration-none fw-semibold"
                                            style="font-size: 0.8rem;" data-id="{{ $exp->id_expense }}"
                                            data-nama="{{ $exp->nama_pengeluaran }}"
                                            data-kategori="{{ $exp->id_expense_category }}"
                                            data-nominal="{{ $exp->nominal }}" data-qty="{{ $exp->qty }}"
                                            data-rekening="{{ $exp->nomor_rekening }}">
                                            Edit
                                        </button>
                                        <form method="POST" action="/expenses/{{ $exp->id_expense }}"
                                            onsubmit="return confirm('Hapus expense ini?')" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                class="btn btn-sm btn-link text-danger p-0 text-decoration-none fw-semibold"
                                                style="font-size: 0.8rem;">Hapus</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-1"
                                        style="font-size: 0.725rem;">Upload only</span>
                                @endif
                            </td>
                        </tr>

                        {{-- Decline Reason Form Box Row --}}
                        <tr id="decline-row-${{ $exp->id_expense }}" class="decline-row d-none">
                            <td colspan="9" class="px-3 pb-3 bg-light">
                                <div class="card border-danger-subtle bg-white shadow-sm mt-1"
                                    style="border-radius: 10px;">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                                            <div class="flex-grow-1">
                                                <div class="fw-bold text-dark mb-1" style="font-size: 0.85rem;">Alasan
                                                    Declined</div>
                                                <p class="text-muted small mb-2" style="font-size: 0.775rem;">Tuliskan
                                                    alasan penolakan sebelum status disimpan.</p>
                                                <textarea class="form-control decline-reason-input" rows="2" data-id="{{ $exp->id_expense }}"
                                                    placeholder="Contoh: Nota tidak jelas / tidak sesuai proposal" style="font-size: 0.85rem; border-radius: 8px;">{{ $exp->rejection_reason }}</textarea>
                                            </div>
                                            <div class="d-flex gap-2 ms-auto align-self-end mt-2">
                                                <button type="button"
                                                    class="btn btn-light btn-sm cancel-decline-btn fw-semibold"
                                                    data-id="{{ $exp->id_expense }}"
                                                    style="border-radius: 6px; font-size: 0.8rem;">
                                                    Batal
                                                </button>
                                                <button type="button"
                                                    class="btn btn-danger btn-sm save-decline-btn fw-semibold"
                                                    data-id="{{ $exp->id_expense }}"
                                                    style="border-radius: 6px; font-size: 0.8rem;">
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
                            <td colspan="9" class="text-muted p-4 text-center small">Belum ada realisasi
                                pengeluaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer Summary Total Row --}}
        <div class="card-footer bg-transparent border-top py-3 px-3">
            <div class="d-flex justify-content-between align-items-center fw-bold" style="font-size: 0.95rem;">
                <span class="text-secondary">Total Realisasi Terpilih (Accepted)</span>
                <span class="text-success" style="font-size: 1.1rem;">Rp {{ number_format($total) }}</span>
            </div>
        </div>
    </div>
</div>

{{-- PREVIEW MODAL --}}
<div id="image-preview-modal"
    style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.75); justify-content:center; align-items:center; z-index:9999;">
    <img id="preview-img"
        style="max-width:85%; max-height:85%; border-radius:12px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
</div>

<style>
    /* Spacing kustom mikro agar tidak kelebaran */
    .p-25 {
        padding: 14px 16px !important;
    }

    .style-table-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
    }

    .py-25 {
        padding-top: 12px !important;
        padding-bottom: 12px !important;
    }

    /* Modifikasi Form Element agar Compact */
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

    /* Override teks link button agar clean */
    .btn-link {
        transition: opacity 0.2s ease;
    }

    .btn-link:hover {
        opacity: 0.8;
    }
</style>

{{-- JAVASCRIPT UTUH TANPA PERUBAHAN LOGIKA --}}
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
        button.addEventListener('click', function() {
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
        button.addEventListener('click', function() {
            const expenseId = this.dataset.id;
            const select = document.querySelector(`.status-dropdown[data-id="${expenseId}"]`);
            if (select) {
                select.value = select.dataset.currentStatus || 'pending';
            }
            hideDeclineCard(expenseId);
        });
    });

    function showDeclineCard(expenseId) {
        const row = document.getElementById(`decline-row-\${expenseId}`);
        if (row) {
            row.classList.remove('d-none');
        }
    }

    function hideDeclineCard(expenseId) {
        const row = document.getElementById(`decline-row-\${expenseId}`);
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
        btn.addEventListener('click', function() {

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

        document.getElementById('expenseForm').action = `/expenses/\${exp.id}`;

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

    document.getElementById('image-preview-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.style.display = 'none';
        }
    });
</script>
