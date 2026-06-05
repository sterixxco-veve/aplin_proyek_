@extends('layouts.app')

@section('content')

    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">
                    Rundown Events
                </h3>

                <p class="text-muted mb-0">
                    Pilih event untuk melihat rundown acara.
                </p>
            </div>
        </div>

        <div class="row g-4">

            @forelse($events as $event)

                <div class="col-md-6 col-xl-4">

                    <a href="/events/{{ $event->id_event }}/rundown" class="text-decoration-none text-dark">

                        <div class="card border-0 shadow-sm h-100">

                            <div class="card-body">

                                <div class="d-flex justify-content-between mb-3">

                                    <span class="badge bg-primary-subtle text-primary">
                                        Event
                                    </span>

                                    <small class="text-muted">
                                        {{ $event->tanggal_mulai?->format('d M Y') }}
                                    </small>
                                </div>

                                <h5 class="fw-bold">
                                    {{ $event->nama_event }}
                                </h5>

                                <p class="text-muted small mb-0">
                                    {{ $event->deskripsi_event ?? 'Tidak ada deskripsi event.' }}
                                </p>

                            </div>
                        </div>

                    </a>

                </div>

            @empty

                <div class="col-12">
                    <div class="alert alert-light border">
                        Belum ada event tersedia.
                    </div>
                </div>

            @endforelse

        </div>

    </div>

@endsection