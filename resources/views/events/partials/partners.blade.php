@php
    $partners = $event->partners;
    $members = $event->organization->members ?? collect();

    $statusLabels = [
        'approach' => 'Approach',
        'prospect' => 'Prospect',
        'contacted' => 'Contacted',
        'follow_up' => 'Follow Up',
        'negotiation' => 'Negotiation',
        'deal' => 'Deal',
        'rejected' => 'Rejected',
        'cancelled' => 'Cancelled',
    ];
@endphp

<div class="d-flex justify-content-between align-items-start mb-4 gap-3 flex-wrap">
    <div>
        <h5 class="fw-bold mb-1">Partners</h5>
        <p class="text-muted small mb-0">Kelola sponsor, media partner, dan collaboration partner untuk event.</p>
    </div>

    <div class="d-flex align-items-center gap-2 flex-wrap">
        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
            {{ $partners->count() }} partner
        </span>

        @if($canManagePartner)
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#partnerModal">
                Tambah Partner
            </button>
        @endif
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-3">
                <div class="p-3 bg-light rounded-3">
                    <small class="text-muted d-block">Total Partner</small>
                    <h4 class="mb-0">{{ $partners->count() }}</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-success-subtle rounded-3">
                    <small class="text-muted d-block">Deal</small>
                    <h4 class="mb-0">{{ $partners->where('status', 'deal')->count() }}</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-warning-subtle rounded-3">
                    <small class="text-muted d-block">Follow Up</small>
                    <h4 class="mb-0">{{ $partners->where('status', 'follow_up')->count() }}</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-danger-subtle rounded-3">
                    <small class="text-muted d-block">Rejected</small>
                    <h4 class="mb-0">{{ $partners->where('status', 'rejected')->count() }}</h4>
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
                        <th class="ps-4">Nama</th>
                        <th>Jenis</th>
                        <th>PIC</th>
                        <th>Status</th>
                        @if($canManagePartner)
                            <th class="text-end pe-4">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($partners as $partner)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-semibold">{{ $partner->nama_partner }}</div>
                                <small class="text-muted">Partner event</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ strtoupper($partner->jenis_partner) }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $partner->pic?->name ?? '-' }}</div>
                                <small class="text-muted">Assigned PIC</small>
                            </td>
                            <td>
                                @php
                                    $badgeClass = match ($partner->status) {
                                        'deal' => 'bg-success-subtle text-success',
                                        'follow_up', 'contacted' => 'bg-warning-subtle text-warning',
                                        'rejected', 'cancelled' => 'bg-danger-subtle text-danger',
                                        default => 'bg-light text-dark border',
                                    };
                                @endphp
                                <span class="badge rounded-pill px-3 py-2 {{ $badgeClass }}">
                                    {{ $statusLabels[$partner->status] ?? ucfirst($partner->status) }}
                                </span>
                                @if($partner->notes)
                                    <small class="text-muted d-block mt-1">{{ $partner->notes }}</small>
                                @endif
                            </td>
                            @if($canManagePartner)
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary partner-edit-btn"
                                            data-id="{{ $partner->id_partner }}"
                                            data-nama="{{ e($partner->nama_partner) }}"
                                            data-jenis="{{ $partner->jenis_partner }}"
                                            data-pic="{{ $partner->assigned_pic }}"
                                            data-status="{{ $partner->status }}"
                                            data-notes="{{ e($partner->notes) }}">
                                            Edit
                                        </button>

                                        <form method="POST"
                                              action="/events/{{ $event->id_event }}/partners/{{ $partner->id_partner }}"
                                              onsubmit="return confirm('Hapus partner ini?')">
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
                            <td colspan="{{ $canManagePartner ? 5 : 4 }}" class="p-4 text-muted">
                                Belum ada partner untuk event ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($canManagePartner)
    <div class="modal fade" id="partnerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-body p-4">
                    <h5 class="fw-bold mb-3">Tambah Partner</h5>


                    <form method="POST" action="/events/{{ $event->id_event }}/partners">
                        @csrf

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small text-muted">Nama Partner</label>
                                <input type="text" name="nama_partner"
                                    class="form-control @error('nama_partner') is-invalid @enderror"
                                    value="{{ old('nama_partner') }}">
                                @error('nama_partner')
                                    <div class="invalid-feedback fw-semibold">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label small text-muted">Jenis Partner</label>
                                <select name="jenis_partner" class="form-select @error('jenis_partner') is-invalid @enderror">
                                    <option value="">Pilih jenis</option>
                                    <option value="sponsor" {{ old('jenis_partner') == 'sponsor' ? 'selected' : '' }}>Sponsor</option>
                                    <option value="medpar" {{ old('jenis_partner') == 'medpar' ? 'selected' : '' }}>Media Partner</option>
                                    <option value="comrel" {{ old('jenis_partner') == 'comrel' ? 'selected' : '' }}>Community Relation</option>
                                </select>
                                @error('jenis_partner')
                                    <div class="invalid-feedback fw-semibold">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label small text-muted">Status</label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror">
                                    @foreach($statusLabels as $key => $label)
                                        <option value="{{ $key }}" {{ old('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="invalid-feedback fw-semibold">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted">PIC</label>
                                <select name="assigned_pic" class="form-select">
                                    <option value="">Pilih PIC</option>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id_user }}" {{ old('assigned_pic') == $member->id_user ? 'selected' : '' }}>{{ $member->name }} ({{ $member->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted">Notes</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Catatan singkat partner">{{ old('notes') }}</textarea>
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

    <div class="modal fade" id="editPartnerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-body p-4">
                    <h5 class="fw-bold mb-3">Edit Partner</h5>

                    <form id="editPartnerForm" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small text-muted">Nama Partner</label>
                                <input type="text" name="nama_partner" id="edit_partner_nama" class="form-control">
                            </div>
                            <div class="col-6">
                                <label class="form-label small text-muted">Jenis Partner</label>
                                <select name="jenis_partner" id="edit_partner_jenis" class="form-select">
                                    <option value="sponsor">Sponsor</option>
                                    <option value="medpar">Media Partner</option>
                                    <option value="comrel">Community Relation</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label small text-muted">Status</label>
                                <select name="status" id="edit_partner_status" class="form-select">
                                    @foreach($statusLabels as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted">PIC</label>
                                <select name="assigned_pic" id="edit_partner_pic" class="form-select">
                                    <option value="">Pilih PIC</option>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id_user }}">{{ $member->name }} ({{ $member->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted">Notes</label>
                                <textarea name="notes" id="edit_partner_notes" class="form-control" rows="3"></textarea>
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

@if($canManagePartner)
    <script>
        document.querySelectorAll('.partner-edit-btn').forEach((btn) => {
            btn.addEventListener('click', function () {
                const form = document.getElementById('editPartnerForm');
                form.action = `/events/{{ $event->id_event }}/partners/${this.dataset.id}`;

                document.getElementById('edit_partner_nama').value = this.dataset.nama;
                document.getElementById('edit_partner_jenis').value = this.dataset.jenis;
                document.getElementById('edit_partner_pic').value = this.dataset.pic || '';
                document.getElementById('edit_partner_status').value = this.dataset.status;
                document.getElementById('edit_partner_notes').value = this.dataset.notes || '';

                const modal = new bootstrap.Modal(document.getElementById('editPartnerModal'));
                modal.show();
            });
        });
    </script>
@endif
