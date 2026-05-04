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

    <div class="row g-4">

        {{-- ========================= --}}
        {{-- FORM --}}
        {{-- ========================= --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    <h5 class="fw-bold mb-3">Tambah / Edit Expense</h5>

                    <form id="expenseForm"
                          method="POST"
                          action="/events/{{ $eventId }}/expenses"
                          enctype="multipart/form-data">
                        @csrf

                        <input type="text" name="nama_pengeluaran" id="nama_pengeluaran"
                               class="form-control mb-2" placeholder="Nama" required>

                        <select name="id_expense_category" id="kategori"
                                class="form-control mb-2" required>
                            <option value="">Pilih kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id_expense_category }}">
                                    {{ $cat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>

                        <div class="row">
                            <div class="col-6">
                                <input type="number" name="nominal" id="nominal"
                                       class="form-control mb-2" placeholder="Nominal" required>
                            </div>
                            <div class="col-6">
                                <input type="number" name="qty" id="qty"
                                       class="form-control mb-2" placeholder="Qty" required>
                            </div>
                        </div>

                        <input type="number" name="nomor_rekening" id="rekening"
                               class="form-control mb-2" placeholder="Nomor rekening" required>

                        <input type="file" name="bukti_nota"
                               class="form-control mb-3">

                        <button id="submitBtn" class="btn btn-success w-100">
                            Tambah
                        </button>

                        <button type="button" id="cancelBtn"
                                class="btn btn-secondary w-100 mt-2"
                                style="display:none;">
                            Cancel
                        </button>

                    </form>

                </div>
            </div>
        </div>

        {{-- ========================= --}}
        {{-- LIST --}}
        {{-- ========================= --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    <h5 class="fw-bold mb-3">Daftar Pengeluaran</h5>

                    @php $total = 0; @endphp

                    @forelse($expenses as $exp)
                        @php $total += $exp->sub_total; @endphp

                        <div class="mb-3 p-3 bg-light rounded-3">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>
                                    <div class="fw-bold">
                                        {{ $exp->nama_pengeluaran }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $exp->category->nama_kategori ?? '-' }} |
                                        {{ $exp->qty }} x Rp {{ number_format($exp->nominal) }}
                                    </small>
                                </div>

                                <div class="fw-bold text-success">
                                    Rp {{ number_format($exp->sub_total) }}
                                </div>

                            </div>

                            {{-- ACTION --}}
                            <div class="d-flex gap-2 mt-2">

                                {{-- EDIT --}}
                                <button
                                    class="btn btn-sm btn-outline-warning edit-btn"
                                    data-id="{{ $exp->id_expense }}"
                                    data-nama="{{ $exp->nama_pengeluaran }}"
                                    data-kategori="{{ $exp->id_expense_category }}"
                                    data-nominal="{{ $exp->nominal }}"
                                    data-qty="{{ $exp->qty }}"
                                    data-rekening="{{ $exp->nomor_rekening }}">
                                    ✏️ Edit
                                </button>

                                {{-- DELETE --}}
                                <form method="POST"
                                      action="/expenses/{{ $exp->id_expense }}"
                                      onsubmit="return confirm('Hapus expense ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-outline-danger">
                                        🗑 Hapus
                                    </button>
                                </form>

                            </div>

                            {{-- PREVIEW --}}
                            @if($exp->bukti_nota_path)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $exp->bukti_nota_path) }}"
                                         style="width:100px; border-radius:8px; cursor:pointer;"
                                         onclick="previewImage(this.src)">
                                </div>
                            @endif

                        </div>

                    @empty
                        <p class="text-muted">Belum ada pengeluaran</p>
                    @endforelse

                    <hr>

                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total</span>
                        <span class="text-success">
                            Rp {{ number_format($total) }}
                        </span>
                    </div>

                </div>
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