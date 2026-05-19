@extends('layouts.app')

@section('content')
<div class="container pb-5">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Documents</h2>
            <p class="text-muted small mb-0">Daftar document dari semua event yang bisa kamu akses.</p>
        </div>
        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
            {{ $documents->count() }} document
        </span>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-muted text-uppercase">
                            <th class="ps-4">Event</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>File</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents as $document)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold">{{ $document->event->nama_event ?? '-' }}</div>
                                    <small class="text-muted">{{ $document->event->tgl_mulai ? \Carbon\Carbon::parse($document->event->tgl_mulai)->format('d M Y') : '-' }}</small>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $document->title }}</div>
                                    <small class="text-muted">{{ $document->notes ?? '-' }}</small>
                                </td>
                                <td>{{ strtoupper(str_replace('_', ' ', $document->document_type)) }}</td>
                                <td>{{ ucfirst($document->status) }}</td>
                                <td>
                                    @if($document->file_url)
                                        <a href="{{ $document->file_url }}" target="_blank" class="btn btn-sm btn-outline-primary">Buka File</a>
                                    @else
                                        <span class="text-muted small">Belum ada file</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <a href="/events/{{ $document->event->id_event }}/details?tab=documents" class="btn btn-sm btn-outline-primary">
                                        Buka Event
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-4 text-muted">Belum ada document yang bisa ditampilkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
