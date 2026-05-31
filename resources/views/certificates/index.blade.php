@extends('layouts.app')

@section('content')

<div class="container py-4">

    <h2 class="fw-bold mb-2">
        Certificate Center
    </h2>

    <p class="text-muted mb-4">
        Pilih event untuk mengelola certificate.
    </p>

    <div class="card shadow-sm border-0">

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>

                    <tr>

                        <th>Event</th>

                        <th>Certificate</th>

                        <th width="150">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                @forelse($events as $event)

                    <tr>

                        <td>

                            <div class="fw-semibold">
                                {{ $event->nama_event }}
                            </div>

                            <div class="small text-muted">
                                {{ $event->tgl_mulai }}
                            </div>

                        </td>

                        <td>
                            {{ $event->certificates_count }}
                        </td>

                        <td>

                            <a
                                href="/certificates/{{ $event->id_event }}"
                                class="btn btn-primary btn-sm">

                                Kelola

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="3"
                            class="text-center text-muted">

                            Belum ada event.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection