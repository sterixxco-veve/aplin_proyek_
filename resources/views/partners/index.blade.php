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

    <div class="row g-4">
        @forelse($partners as $partner)
            @php
                $badgeClass = match ($partner->status) {
                    'deal' => 'bg-success-subtle text-success',
                    'follow_up', 'contacted' => 'bg-warning-subtle text-warning',
                    'rejected', 'cancelled' => 'bg-danger-subtle text-danger',
                    default => 'bg-light text-dark border',
                };
            @endphp
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 d-flex flex-column">
                    <div class="card-body d-flex flex-column">
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Event</small>
                            <div class="fw-semibold">{{ $partner->event->nama_event ?? '-' }}</div>
                            <small class="text-muted">{{ $partner->event->tgl_mulai ? \Carbon\Carbon::parse($partner->event->tgl_mulai)->format('d M Y') : '-' }}</small>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Partner</small>
                            <div class="fw-semibold">{{ $partner->nama_partner }}</div>
                            @if($partner->notes)
                                <small class="text-muted">{{ $partner->notes }}</small>
                            @endif
                        </div>

                        <div class="mb-3">
                            <span class="badge bg-light text-dark border">{{ strtoupper($partner->jenis_partner) }}</span>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">PIC</small>
                            <div class="fw-semibold">{{ $partner->pic?->name ?? '-' }}</div>
                            <small class="text-muted">{{ $partner->pic?->email ?? '-' }}</small>
                        </div>

                        <div class="mb-3">
                            <span class="badge rounded-pill px-3 py-2 {{ $badgeClass }}">
                                {{ ucfirst(str_replace('_', ' ', $partner->status)) }}
                            </span>
                        </div>

                        <a href="/events/{{ $partner->event->id_event }}?tab=partners" class="btn btn-primary mt-auto">
                            Buka Event
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body p-4 text-center">
                        <p class="text-muted mb-0">Belum ada partner yang bisa ditampilkan.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
