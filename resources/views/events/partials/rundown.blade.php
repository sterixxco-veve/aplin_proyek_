@php
    $rundownItems = $event->rundownItems;
    $committeeOptions = $event->committees;
@endphp

<div class="d-flex justify-content-between align-items-start mb-4 gap-3 flex-wrap">
    <div>
        <h5 class="fw-bold mb-1">Rundown</h5>
        <p class="text-muted small mb-0">Susun alur acara per sesi supaya urutan pelaksanaan jelas.</p>
    </div>

    <div class="d-flex align-items-center gap-2 flex-wrap">
        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
            {{ $rundownItems->count() }} item
        </span>

        @if($canManageRundown)
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#rundownModal">
                Tambah Rundown
            </button>
        @endif
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="p-3 bg-light rounded-3">
                    <small class="text-muted d-block">Total Item</small>
                    <h4 class="mb-0">{{ $rundownItems->count() }}</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-success-subtle rounded-3">
                    <small class="text-muted d-block">Hari Terpakai</small>
                    <h4 class="mb-0">{{ $rundownItems->pluck('day_number')->unique()->count() }}</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-warning-subtle rounded-3">
                    <small class="text-muted d-block">Mode Akses</small>
                    <h4 class="mb-0">{{ $canManageRundown ? 'Edit' : 'Lihat Saja' }}</h4>
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
                        <th class="ps-4">Hari</th>
                        <th>Waktu</th>
                        <th>Kegiatan</th>
                        <th>Penanggung Jawab</th>
                        @if($canManageRundown)
                            <th class="text-end pe-4">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($rundownItems as $item)
                        <tr>
                            <td class="ps-4">
                                <span class="badge bg-light text-dark border">Day {{ $item->day_number }}</span>
                                @if($item->session_group)
                                    <small class="text-muted d-block mt-1">{{ $item->session_group }}</small>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold">
                                    {{ \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i') }}
                                    -
                                    {{ \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i') }}
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $item->kegiatan }}</div>
                                <small class="text-muted d-block">Item rundown event</small>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $item->assignedCommittee?->user?->name ?? '-' }}</div>
                                <small class="text-muted d-block">
                                    {{ $item->assignedCommittee?->division?->nama_divisi ?? '-' }}
                                    @if($item->assignedCommittee?->jabatan)
                                        • {{ ucfirst($item->assignedCommittee->jabatan) }}
                                    @endif
                                </small>
                            </td>
                            @if($canManageRundown)
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary rundown-edit-btn"
                                            data-id="{{ $item->id_rundown }}"
                                            data-day="{{ $item->day_number }}"
                                            data-session="{{ e($item->session_group) }}"
                                            data-start="{{ \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i') }}"
                                            data-end="{{ \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i') }}"
                                            data-kegiatan="{{ e($item->kegiatan) }}"
                                            data-assigned="{{ $item->assigned_to }}">
                                            Edit
                                        </button>

                                        <form method="POST"
                                              action="/events/{{ $event->id_event }}/rundown/{{ $item->id_rundown }}"
                                              onsubmit="return confirm('Hapus rundown ini?')">
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
                            <td colspan="{{ $canManageRundown ? 5 : 4 }}" class="p-4 text-muted">
                                Belum ada rundown untuk event ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($canManageRundown)
    <div class="modal fade" id="rundownModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-body p-4">
                    <h5 class="fw-bold mb-3">Tambah Rundown</h5>

                    <form method="POST" action="/events/{{ $event->id_event }}/rundown">
                        @csrf

                        <div class="row g-3">
                            <div class="col-4">
                                <label class="form-label small text-muted">Hari</label>
                                <input type="number" name="day_number" class="form-control" min="1" required>
                            </div>
                            <div class="col-8">
                                <label class="form-label small text-muted">Group Sesi</label>
                                <input type="text" name="session_group" class="form-control" placeholder="Contoh: Pembukaan">
                            </div>
                            <div class="col-6">
                                <label class="form-label small text-muted">Waktu Mulai</label>
                                <input type="time" name="waktu_mulai" class="form-control" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label small text-muted">Waktu Selesai</label>
                                <input type="time" name="waktu_selesai" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted">Kegiatan</label>
                                <input type="text" name="kegiatan" class="form-control" placeholder="Contoh: Registrasi peserta" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted">Penanggung Jawab</label>
                                <select name="assigned_to" class="form-select">
                                    <option value="">Pilih committee</option>
                                    @foreach($committeeOptions as $committee)
                                        <option value="{{ $committee->id_comm }}">
                                            {{ $committee->user->name ?? '-' }} - {{ $committee->division->nama_divisi ?? '-' }} ({{ ucfirst($committee->jabatan) }})
                                        </option>
                                    @endforeach
                                </select>
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

    <div class="modal fade" id="editRundownModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-body p-4">
                    <h5 class="fw-bold mb-3">Edit Rundown</h5>

                    <form id="editRundownForm" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-4">
                                <label class="form-label small text-muted">Hari</label>
                                <input type="number" name="day_number" id="edit_rundown_day" class="form-control" min="1" required>
                            </div>
                            <div class="col-8">
                                <label class="form-label small text-muted">Group Sesi</label>
                                <input type="text" name="session_group" id="edit_rundown_session" class="form-control">
                            </div>
                            <div class="col-6">
                                <label class="form-label small text-muted">Waktu Mulai</label>
                                <input type="time" name="waktu_mulai" id="edit_rundown_start" class="form-control" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label small text-muted">Waktu Selesai</label>
                                <input type="time" name="waktu_selesai" id="edit_rundown_end" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted">Kegiatan</label>
                                <input type="text" name="kegiatan" id="edit_rundown_kegiatan" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted">Penanggung Jawab</label>
                                <select name="assigned_to" id="edit_rundown_assigned" class="form-select">
                                    <option value="">Pilih committee</option>
                                    @foreach($committeeOptions as $committee)
                                        <option value="{{ $committee->id_comm }}">
                                            {{ $committee->user->name ?? '-' }} - {{ $committee->division->nama_divisi ?? '-' }} ({{ ucfirst($committee->jabatan) }})
                                        </option>
                                    @endforeach
                                </select>
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

@if($canManageRundown)
    <script>
        document.querySelectorAll('.rundown-edit-btn').forEach((btn) => {
            btn.addEventListener('click', function () {
                const form = document.getElementById('editRundownForm');
                form.action = `/events/{{ $event->id_event }}/rundown/${this.dataset.id}`;

                document.getElementById('edit_rundown_day').value = this.dataset.day;
                document.getElementById('edit_rundown_session').value = this.dataset.session || '';
                document.getElementById('edit_rundown_start').value = this.dataset.start;
                document.getElementById('edit_rundown_end').value = this.dataset.end;
                document.getElementById('edit_rundown_kegiatan').value = this.dataset.kegiatan;
                document.getElementById('edit_rundown_assigned').value = this.dataset.assigned || '';

                const modal = new bootstrap.Modal(document.getElementById('editRundownModal'));
                modal.show();
            });
        });
    </script>
@endif
