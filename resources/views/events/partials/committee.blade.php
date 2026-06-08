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

                @if($errors->any())
                    <div class="alert alert-danger border-0 rounded-3 d-flex align-items-start gap-2 mb-3">
                        <i class="bi bi-exclamation-triangle-fill text-danger mt-1"></i>
                        <div>
                            <div class="fw-bold small">Ada kesalahan saat menambah committee:</div>
                            <ul class="mb-0 ps-3 mt-1">
                                @foreach($errors->all() as $error)
                                    <li class="small">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

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

                <form method="POST" action="/events/{{ $event->id_event }}/assign" class="row g-3">
                    @csrf
                    <div class="col-md-4">
                        <label class="form-label small text-muted">Member</label>
                        <select name="id_user" class="form-select @error('id_user') is-invalid @enderror">
                            <option value="">Pilih member</option>
                            @forelse($availableMembers ?? [] as $member)
                                <option value="{{ $member->id_user }}" {{ old('id_user') == $member->id_user ? 'selected' : '' }}>{{ $member->name }} ({{ $member->email }})</option>
                            @empty
                                <option value="">Tidak ada member tersedia</option>
                            @endforelse
                        </select>
                        @error('id_user')
                            <div class="invalid-feedback fw-semibold">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small text-muted">Divisi</label>
                        <select name="id_divisi" class="form-select @error('id_divisi') is-invalid @enderror">
                            <option value="">Pilih divisi</option>
                            @foreach($divisions ?? [] as $division)
                                <option value="{{ $division->id_divisi }}" {{ old('id_divisi') == $division->id_divisi ? 'selected' : '' }}>{{ $division->nama_divisi }}</option>
                            @endforeach
                        </select>
                        @error('id_divisi')
                            <div class="invalid-feedback fw-semibold">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small text-muted">Jabatan</label>
                        <select name="jabatan" class="form-select @error('jabatan') is-invalid @enderror">
                            <option value="">Pilih jabatan</option>
                            <option value="koordinator" {{ old('jabatan') == 'koordinator' ? 'selected' : '' }}>Koordinator</option>
                            <option value="anggota" {{ old('jabatan') == 'anggota' ? 'selected' : '' }}>Anggota</option>
                        </select>
                        @error('jabatan')
                            <div class="invalid-feedback fw-semibold">{{ $message }}</div>
                        @enderror
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