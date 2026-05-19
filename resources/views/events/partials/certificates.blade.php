@php
    $certificates = $event->certificates;
@endphp

<div class="d-flex justify-content-between align-items-start mb-4 gap-3 flex-wrap">
    <div>
        <h5 class="fw-bold mb-1">Certificates</h5>
        <p class="text-muted small mb-0">Kelola data penerima certificate dan file output-nya.</p>
    </div>

    <div class="d-flex align-items-center gap-2 flex-wrap">
        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
            {{ $certificates->count() }} certificate
        </span>

        @if($canManageCertificate)
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#certificateModal">
                Tambah Certificate
            </button>
        @endif
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="p-3 bg-light rounded-3">
                    <small class="text-muted d-block">Total Certificate</small>
                    <h4 class="mb-0">{{ $certificates->count() }}</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-success-subtle rounded-3">
                    <small class="text-muted d-block">Sudah Ada File</small>
                    <h4 class="mb-0">{{ $certificates->whereNotNull('file_url')->count() }}</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-warning-subtle rounded-3">
                    <small class="text-muted d-block">Mode Akses</small>
                    <h4 class="mb-0">{{ $canManageCertificate ? 'Edit' : 'Lihat Saja' }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr class="small text-muted text-uppercase">
                        <th class="ps-4">Penerima</th>
                        <th>Email</th>
                        <th>QR Token</th>
                        <th>File</th>
                        @if($canManageCertificate)
                            <th class="text-end pe-4">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($certificates as $cert)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-semibold">{{ $cert->nama_penerima }}</div>
                                <small class="text-muted">Certificate event</small>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $cert->email_penerima }}</div>
                            </td>
                            <td>
                                <code class="small">{{ $cert->qr_token }}</code>
                            </td>
                            <td>
                                @if($cert->file_url)
                                    <a href="{{ $cert->file_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        Buka File
                                    </a>
                                @else
                                    <span class="text-muted small">Belum ada file</span>
                                @endif
                            </td>
                            @if($canManageCertificate)
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary certificate-edit-btn"
                                            data-id="{{ $cert->id_cert }}"
                                            data-nama="{{ e($cert->nama_penerima) }}"
                                            data-email="{{ e($cert->email_penerima) }}"
                                            data-file="{{ e($cert->file_url) }}">
                                            Edit
                                        </button>

                                        <form method="POST"
                                              action="/events/{{ $event->id_event }}/certificates/{{ $cert->id_cert }}"
                                              onsubmit="return confirm('Hapus certificate ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $canManageCertificate ? 5 : 4 }}" class="p-4 text-muted">
                                Belum ada certificate untuk event ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($canManageCertificate)
    <div class="modal fade" id="certificateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-body p-4">
                    <h5 class="fw-bold mb-3">Tambah Certificate</h5>

                    <form method="POST" action="/events/{{ $event->id_event }}/certificates">
                        @csrf

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small text-muted">Nama Penerima</label>
                                <input type="text" name="nama_penerima" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted">Email Penerima</label>
                                <input type="email" name="email_penerima" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted">File URL</label>
                                <input type="text" name="file_url" class="form-control" placeholder="https://...">
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="button" class="btn btn-light flex-fill" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary flex-fill">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editCertificateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-body p-4">
                    <h5 class="fw-bold mb-3">Edit Certificate</h5>

                    <form id="editCertificateForm" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small text-muted">Nama Penerima</label>
                                <input type="text" name="nama_penerima" id="edit_certificate_nama" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted">Email Penerima</label>
                                <input type="email" name="email_penerima" id="edit_certificate_email" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted">File URL</label>
                                <input type="text" name="file_url" id="edit_certificate_file" class="form-control" placeholder="https://...">
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="button" class="btn btn-light flex-fill" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary flex-fill">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

@if($canManageCertificate)
    <script>
        document.querySelectorAll('.certificate-edit-btn').forEach((btn) => {
            btn.addEventListener('click', function () {
                const form = document.getElementById('editCertificateForm');
                form.action = `/events/{{ $event->id_event }}/certificates/${this.dataset.id}`;

                document.getElementById('edit_certificate_nama').value = this.dataset.nama;
                document.getElementById('edit_certificate_email').value = this.dataset.email;
                document.getElementById('edit_certificate_file').value = this.dataset.file || '';

                const modal = new bootstrap.Modal(document.getElementById('editCertificateModal'));
                modal.show();
            });
        });
    </script>
@endif
