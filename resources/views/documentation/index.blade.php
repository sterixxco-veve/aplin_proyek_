@extends('layouts.app')

@section('content')

    <div class="container py-4">

        <div class="mb-4">

            <h2 class="fw-bold">
                Documentation
            </h2>

            <p class="text-muted mb-0">
                Select an event to manage documentation
            </p>

        </div>

        <div class="row g-4">

            @forelse($events as $event)

                @php
                    $photoCount = $event->documentationLinks
                        ->whereNotNull('file_path')
                        ->count();

                    $driveLink = $event->documentationLinks
                        ->first()?->google_drive_link;
                @endphp

                <div class="col-md-6 col-xl-4">

                    <div class="card border-0 shadow-sm h-100 rounded-4">

                        <div class="card-body d-flex flex-column">

                            <h5 class="fw-bold">
                                {{ $event->nama_event }}
                            </h5>

                            <div class="mt-2">

                                <span class="badge bg-primary">
                                    {{ $photoCount }} Photos
                                </span>

                                @if($driveLink)

                                    <span class="badge bg-success">
                                        Drive Linked
                                    </span>

                                @endif

                            </div>

                            <div class="mt-auto pt-4">

                                <a href="{{ route('documentation.show', $event->id_event) }}"
                                    class="btn btn-primary w-100 rounded-3">
                                    Manage Documentation
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            @empty

                <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 text-muted">
                        Belum ada event yang tersedia.
                    </div>
                </div>
            </div>

            @endforelse

        </div>

    </div>

@endsection