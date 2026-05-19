@extends('layouts.app')

@section('content')
<div class="container pb-5">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Partners</h2>
            <p class="text-muted small mb-0">Daftar partner dari semua event yang bisa kamu akses.</p>
        </div>
        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
            {{ $partners->count() }} partner
        </span>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-muted text-uppercase">
                            <th class="ps-4">Event</th>
                            <th>Partner</th>
                            <th>Jenis</th>
                            <th>PIC</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($partners as $partner)
                            @php
                                $badgeClass = match ($partner->status) {
                                    'deal' => 'bg-success-subtle text-success',
                                    'follow_up', 'contacted' => 'bg-warning-subtle text-warning',
                                    'rejected', 'cancelled' => 'bg-danger-subtle text-danger',
                                    default => 'bg-light text-dark border',
                                };
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold">{{ $partner->event->nama_event ?? '-' }}</div>
                                    <small class="text-muted">{{ $partner->event->tgl_mulai ? \Carbon\Carbon::parse($partner->event->tgl_mulai)->format('d M Y') : '-' }}</small>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $partner->nama_partner }}</div>
                                    <small class="text-muted">{{ $partner->notes ?? '-' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ strtoupper($partner->jenis_partner) }}</span>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $partner->pic?->name ?? '-' }}</div>
                                    <small class="text-muted">{{ $partner->pic?->email ?? '-' }}</small>
                                </td>
                                <td>
                                    <span class="badge rounded-pill px-3 py-2 {{ $badgeClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $partner->status)) }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="/events/{{ $partner->event->id_event }}?tab=partners" class="btn btn-sm btn-outline-primary">
                                        Buka Event
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-4 text-muted">Belum ada partner yang bisa ditampilkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
