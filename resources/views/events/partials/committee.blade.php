<div id="committee" class="tab-content d-none">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold mb-1">Committee</h5>
            <small class="text-muted">Tambah atau hapus panitia yang terlibat di event ini.</small>
        </div>
        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
            {{ $event->committees->count() }} committee
        </span>
    </div>

    {{-- Form Tambah Committee --}}
    @if($event->canManageCertificateBy(auth()->user()))
        <div class="card border-0 shadow-sm mb-4 bg-light">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">Tambah Committee</h6>
                <form method="POST" action="/events/{{ $event->id_event }}/assign" class="row g-3">
                    @csrf
                    <div class="col-md-4">
                        <label class="form-label small text-muted">Member</label>
                        <select name="id_user" class="form-select" required>
                            <option value="">Pilih member</option>
                            @forelse($availableMembers ?? [] as $member)
                                <option value="{{ $member->id_user }}">{{ $member->name }} ({{ $member->email }})</option>
                            @empty
                                <option value="">Tidak ada member tersedia</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small text-muted">Divisi</label>
                        <select name="id_divisi" class="form-select" required>
                            <option value="">Pilih divisi</option>
                            @foreach($divisions ?? [] as $division)
                                <option value="{{ $division->id_divisi }}">{{ $division->nama_divisi }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small text-muted">Jabatan</label>
                        <select name="jabatan" class="form-select" required>
                            <option value="">Pilih jabatan</option>
                            <option value="koordinator">Koordinator</option>
                            <option value="anggota">Anggota</option>
                        </select>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-primary w-100">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Tabel Daftar Committee --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-muted text-uppercase">
                            <th class="ps-4">Nama</th>
                            <th>Divisi</th>
                            <th>Jabatan</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($event->committees as $committee)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold">{{ $committee->user->name ?? '-' }}</div>
                                    <small class="text-muted">{{ $committee->user->email ?? '-' }}</small>
                                </td>
                                <td>
                                    <span
                                        class="badge bg-light text-dark border">{{ $committee->division->nama_divisi ?? '-' }}</span>
                                </td>
                                <td>
                                    <span
                                        class="text-muted">{{ $committee->jabatan ? ucfirst($committee->jabatan) : '-' }}</span>
                                </td>
                                <td class="text-end pe-4">
                                    @if($event->canManageCommitteeBy(auth()->user()) && $event->committees->count() > 1)
                                        <form method="POST"
                                            action="/events/{{ $event->id_event }}/committees/{{ $committee->id_comm }}"
                                            onsubmit="return confirm('Hapus committee ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    @elseif($event->committees->count() <= 1)
                                        <span class="text-muted small">Minimal 1 committee</span>
                                    @else
                                        <span class="text-muted small">Tidak punya akses</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-muted p-4 text-center">Belum ada committee di event ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>