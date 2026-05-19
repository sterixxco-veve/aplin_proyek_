@extends('layouts.app')

@section('content')
<div class="container pb-5">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Certificates</h2>
            <p class="text-muted small mb-0">Daftar certificate dari semua event yang bisa kamu akses.</p>
        </div>
        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
            {{ $certificates->count() }} certificate
        </span>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-muted text-uppercase">
                            <th class="ps-4">Event</th>
                            <th>Penerima</th>
                            <th>Email</th>
                            <th>QR Token</th>
                            <th>File</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($certificates as $cert)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold">{{ $cert->event->nama_event ?? '-' }}</div>
                                    <small class="text-muted">{{ $cert->event->tgl_mulai ? \Carbon\Carbon::parse($cert->event->tgl_mulai)->format('d M Y') : '-' }}</small>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $cert->nama_penerima }}</div>
                                </td>
                                <td>{{ $cert->email_penerima }}</td>
                                <td><code class="small">{{ $cert->qr_token }}</code></td>
                                <td>
                                    @if($cert->file_url)
                                        <a href="{{ $cert->file_url }}" target="_blank" class="btn btn-sm btn-outline-primary">Buka File</a>
                                    @else
                                        <span class="text-muted small">Belum ada file</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <a href="/events/{{ $cert->event->id_event }}/details?tab=certificates" class="btn btn-sm btn-outline-primary">
                                        Buka Event
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-4 text-muted">Belum ada certificate yang bisa ditampilkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
