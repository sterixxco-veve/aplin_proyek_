@php
    $certificates = $event->certificates ?? collect();
    $templatePath = session('template_path') ?? null;
    $canManageCertificate = $canManageCertificate ?? false;
@endphp

<div class="d-flex justify-content-between align-items-start mb-4 gap-3 flex-wrap">
    <div>
        <h5 class="fw-bold mb-1">Certificates</h5>
        <p class="text-muted small mb-0">Upload template, bulk insert penerima, generate & send certificates.</p>
    </div>

    <div class="d-flex align-items-center gap-2 flex-wrap">
        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
            {{ $certificates->count() }} penerima
        </span>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- STEP 1: TEMPLATE UPLOAD -->

<!-- STEP 2: BULK INSERT RECIPIENTS -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-light border-0 px-4 py-3">
        <h6 class="mb-0 fw-bold">
            <span class="badge bg-primary me-2">1</span> Tambah Penerima Certificate
        </h6>
    </div>
    <div class="card-body p-4">
        <div class="d-flex gap-2 mb-4">
            <button type="button" class="btn btn-primary" id="manualBtn" onclick="showTab('manual')">
                <i class="bi bi-pencil me-2"></i>Manual Input
            </button>

            <button type="button" class="btn btn-outline-primary" id="csvBtn" onclick="showTab('csv')">
                <i class="bi bi-file-earmark-csv me-2"></i>Upload CSV
            </button>
        </div>

        <div>
            <!-- Manual Tab -->
            <div id="manual">
                @if($canManageCertificate)
                    <form method="POST" action="/events/{{ $event->id_event }}/certificates/bulk-insert" id="manualForm">
                        @csrf
                        <div id="recipientFields">
                            <div class="row g-3 recipient-row">
                                <div class="col-md-6">
                                    <input type="text" name="nama_penerima[]" class="form-control"
                                        placeholder="Nama Penerima" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="email" name="email_penerima[]" class="form-control"
                                        placeholder="Email Penerima" required>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <button type="button" class="btn btn-outline-secondary" onclick="addRecipientRow()">
                                <i class="bi bi-plus me-2"></i>Tambah Baris
                            </button>
                            <button type="submit" class="btn btn-primary ms-auto">Simpan Penerima</button>
                        </div>
                    </form>
                @else
                    <div class="alert alert-warning">
                        <i class="bi bi-lock me-2"></i>Anda tidak memiliki akses untuk mengelola certificate.
                    </div>
                @endif
            </div>

            <!-- CSV Tab -->
            <div id="csv" style="display:none;">
                @if($canManageCertificate)
                    <div class="mb-3 p-3 bg-light rounded">
                        <p class="small text-muted mb-2">Unduh template terlebih dahulu untuk format yang benar:</p>
                        <a href="/certificates/template/download" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-download me-1"></i>Download Template
                        </a>
                    </div>
                    <form method="POST" action="/events/{{ $event->id_event }}/certificates/bulk-insert"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small text-muted">File CSV atau Excel</label>
                            <input type="file" name="recipients_file" class="form-control" accept=".csv,.xlsx,.xls"
                                required>
                            <small class="text-muted d-block mt-2">
                                Format: <code>Nama Lengkap, Email Penerima</code><br>
                                Baris pertama adalah header (otomatis dilewati).
                            </small>
                        </div>
                        <button type="submit" class="btn btn-primary">Upload & Import</button>
                    </form>
                @else
                    <div class="alert alert-warning">
                        <i class="bi bi-lock me-2"></i>Anda tidak memiliki akses untuk mengelola certificate.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div
        class="card-header bg-light border-0 px-4 py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h6 class="mb-0 fw-bold">
            <span class="badge bg-primary me-2">2</span> Upload Template Background
        </h6>
        @if($templatePath && $canManageCertificate)
            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                data-bs-target="#templateEditorModal">
                <i class="bi bi-pencil-square me-1"></i>Edit Template
            </button>
        @endif
    </div>
    <div class="card-body p-4">
        @if($templatePath)
            <div class="alert alert-success mb-3">
                <i class="bi bi-check-circle me-2"></i>
                Template sudah diupload: <code>{{ $templatePath }}</code>
            </div>
        @endif

        @if($canManageCertificate)
            <form method="POST" action="/events/{{ $event->id_event }}/certificates/upload-template"
                enctype="multipart/form-data" class="d-flex gap-2 align-items-end">
                @csrf
                <div class="flex-grow-1">
                    <label class="form-label small text-muted">File Template (PNG/JPG, max 5MB)</label>
                    <input type="file" name="template_file" class="form-control" accept="image/png,image/jpeg,image/jpg"
                        required>
                    <small class="text-muted d-block mt-2">Template akan digunakan sebagai background certificate. Pastikan
                        ukuran dan resolusi sudah sesuai.</small>
                </div>
                <button type="submit" class="btn btn-primary">Upload</button>
            </form>
        @endif
    </div>
</div>
<!-- STEP 3: DAFTAR PENERIMA & ACTION -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-light border-0 px-4 py-3 d-flex justify-content-between align-items-center flex-wrap">
        <h6 class="mb-0 fw-bold">
            <span class="badge bg-primary me-2">3</span> Daftar Penerima & Proses
        </h6>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <!-- <div class="small text-muted">
                <span id="selectedCount">0</span>
                recipient selected
            </div>

            <button
                type="button"
                class="btn btn-primary btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#sendEmailModal">

                <i class="bi bi-envelope-fill me-1"></i>
                Send Bulk Email
            </button> -->

        </div>
        @if($canManageCertificate && $certificates->count() > 0)
            <div class="d-flex gap-2 flex-wrap">

                @if($templatePath)
                    <form method="POST" action="/events/{{ $event->id_event }}/certificates/generate">
                        @csrf

                        <input type="hidden" name="template_path" value="{{ $templatePath }}">

                        <button type="submit" class="btn btn-sm btn-success">

                            <i class="bi bi-sparkles me-1"></i>
                            Generate Certificates
                        </button>
                    </form>
                @endif

                <form id="downloadZipForm" method="POST" action="/events/{{ $event->id_event }}/certificates/download-zip">

                    @csrf

                    <div id="downloadZipInputs"></div>
                    <div class="small text-muted">
                        <span id="selectedCount">0</span>
                        recipient selected
                    </div>

                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#sendEmailModal">

                        <i class="bi bi-envelope-fill me-1"></i>
                        Send Bulk Email
                    </button>
                    <button type="submit" class="btn btn-sm btn-warning">

                        <i class="bi bi-file-earmark-zip me-1"></i>
                        Download ZIP
                    </button>

                </form>

            </div>
        @endif
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr class="small text-muted text-uppercase">
                        <th class="ps-4" width="40">
                            <input type="checkbox" id="selectAllCertificates">
                        </th>

                        <th>No</th>
                        <th>Penerima</th>
                        <th>Email</th>
                        <th>Status File</th>
                        <th>Terkirim</th>
                        @if($canManageCertificate)
                            <th class="text-end pe-4">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($certificates as $index => $cert)
                        <tr>
                            <td class="ps-4">
                                @if($cert->file_url)
                                    <input type="checkbox" class="cert-checkbox" value="{{ $cert->id_cert }}"
                                        data-name="{{ $cert->nama_penerima }}" data-email="{{ $cert->email_penerima }}">
                                @endif
                            </td>

                            <td>
                                {{ $index + 1 }}
                            </td>

                            <td class="fw-semibold">
                                {{ $cert->nama_penerima }}
                            </td>

                            <td>
                                {{ $cert->email_penerima }}
                            </td>

                            <td>
                                @if($cert->file_url)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check me-1"></i>Generated
                                    </span>
                                    <a href="{{ asset('storage/' . $cert->file_url) }}" target="_blank"
                                        class="btn btn-xs btn-outline-primary ms-2" style="padding: 2px 6px; font-size: 11px;">
                                        Preview
                                    </a>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-hourglass-split me-1"></i>Pending
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($cert->sent_at)
                                    <span class="badge bg-success">
                                        <i class="bi bi-envelope-check me-1"></i>{{ $cert->sent_at->format('d M H:i') }}
                                    </span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            @if($canManageCertificate)
                                <td class="text-end pe-4">
                                    @if($cert->file_url)
                                        <a href="{{ asset('storage/' . $cert->file_url) }}" download
                                            class="btn btn-sm btn-outline-success">

                                            <i class="bi bi-download"></i>
                                        </a>
                                    @endif

                                    <form method="POST"
                                        action="/events/{{ $event->id_event }}/certificates/{{ $cert->id_cert }}"
                                        style="display:inline" onsubmit="return confirm('Hapus penerima ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-outline-danger">
                                            Hapus
                                        </button>
                                    </form>

                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $canManageCertificate ? 6 : 5 }}" class="p-4 text-muted text-center">
                                Belum ada penerima certificate. Silahkan tambah penerima terlebih dahulu.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- SEND EMAIL MODAL -->
@if($canManageCertificate && $certificates->where('file_url', '!=', null)->count() > 0)
    <div class="modal fade" id="sendEmailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light border-0">
                    <h5 class="modal-title fw-bold">Kirim Certificate via Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form method="POST" action="/events/{{ $event->id_event }}/certificates/send" id="sendEmailForm">

                        @csrf

                        <div class="alert alert-info mb-3">
                            <div class="fw-semibold mb-1">
                                Selected Recipients
                            </div>

                            <div>
                                <span id="modalSelectedCount">0</span>
                                certificate(s) selected
                            </div>
                        </div>

                        <div id="selectedRecipientsList" class="border rounded p-3 mb-3"
                            style="max-height: 250px; overflow-y:auto;">
                        </div>

                        <div id="selectedInputsContainer"></div>

                        <div class="alert alert-warning small">
                            <i class="bi bi-envelope me-2"></i>
                            Certificate akan dikirim sebagai attachment email.
                        </div>

                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-light flex-fill" data-bs-dismiss="modal">
                                Cancel
                            </button>

                            <button type="submit" class="btn btn-primary flex-fill">
                                Send Bulk Email
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

@if($canManageCertificate)
    <script>
        function addRecipientRow() {
            const html = `
                            <div class="row g-3 recipient-row mt-3">
                                <div class="col-md-6">
                                    <input type="text" name="nama_penerima[]" class="form-control" placeholder="Nama Penerima" required>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="email" name="email_penerima[]" class="form-control" placeholder="Email Penerima" required>
                                        <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.parentElement.parentElement.remove()">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
            document.getElementById('recipientFields').insertAdjacentHTML('beforeend', html);
        }
    </script>
    <script>
        function showTab(tab) {

            const manual = document.getElementById('manual');
            const csv = document.getElementById('csv');

            const manualBtn = document.getElementById('manualBtn');
            const csvBtn = document.getElementById('csvBtn');

            if (tab === 'manual') {

                manual.style.display = 'block';
                csv.style.display = 'none';

                manualBtn.classList.remove('btn-outline-primary');
                manualBtn.classList.add('btn-primary');

                csvBtn.classList.remove('btn-primary');
                csvBtn.classList.add('btn-outline-primary');

            } else {

                manual.style.display = 'none';
                csv.style.display = 'block';

                csvBtn.classList.remove('btn-outline-primary');
                csvBtn.classList.add('btn-primary');

                manualBtn.classList.remove('btn-primary');
                manualBtn.classList.add('btn-outline-primary');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            showTab('manual');
        });
    </script>
    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const selectAll =
                document.getElementById('selectAllCertificates');

            const checkboxes =
                document.querySelectorAll('.cert-checkbox');

            const selectedCount =
                document.getElementById('selectedCount');

            const modalSelectedCount =
                document.getElementById('modalSelectedCount');

            const selectedRecipientsList =
                document.getElementById('selectedRecipientsList');

            const selectedInputsContainer =
                document.getElementById('selectedInputsContainer');

            function updateSelectedRecipients() {
                const downloadZipInputs =
                    document.getElementById('downloadZipInputs');

                if (downloadZipInputs) {
                    downloadZipInputs.innerHTML = '';
                }
                const selected =
                    Array.from(checkboxes)
                        .filter(cb => cb.checked);

                if (selectedCount) {
                    selectedCount.textContent = selected.length;
                }

                if (modalSelectedCount) {
                    modalSelectedCount.textContent = selected.length;
                }

                if (selectedRecipientsList) {
                    selectedRecipientsList.innerHTML = '';
                }

                if (selectedInputsContainer) {
                    selectedInputsContainer.innerHTML = '';
                }

                selected.forEach(cb => {

                    if (selectedRecipientsList) {

                        selectedRecipientsList.innerHTML += `
                                <div class="small border-bottom py-2">
                                    <div class="fw-semibold">
                                        ${cb.dataset.name}
                                    </div>

                                    <div class="text-muted">
                                        ${cb.dataset.email}
                                    </div>
                                </div>
                            `;
                    }

                    if (downloadZipInputs) {
                        downloadZipInputs.innerHTML += `
                                <input
                                    type="hidden"
                                    name="cert_ids[]"
                                    value="${cb.value}">
                            `;
                    }

                    if (selectedInputsContainer) {

                        selectedInputsContainer.innerHTML += `
                                <input
                                    type="hidden"
                                    name="cert_ids[]"
                                    value="${cb.value}">
                            `;
                    }
                });
            }

            if (selectAll) {

                selectAll.addEventListener('change', function () {

                    checkboxes.forEach(cb => {
                        cb.checked = selectAll.checked;
                    });

                    updateSelectedRecipients();
                });
            }

            checkboxes.forEach(cb => {

                cb.addEventListener('change', function () {

                    updateSelectedRecipients();

                    const allChecked =
                        Array.from(checkboxes)
                            .every(x => x.checked);

                    if (selectAll) {
                        selectAll.checked = allChecked;
                    }
                });
            });

            updateSelectedRecipients();
        });

    </script>

    <!-- Include Certificate Editor Modal -->
    @include('events.partials.certificate-editor-modal')
@endif