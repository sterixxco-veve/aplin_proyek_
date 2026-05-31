@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between">

        <div>

            <h2 class="fw-bold">

                {{ $event->nama_event }}

            </h2>

            <p class="text-muted">

                Manage Certificates

            </p>

        </div>

        <div>

            <a
                href="/events/{{ $event->id_event }}"
                class="btn btn-outline-primary">

                Buka Event

            </a>

        </div>

    </div>

    @include('events.partials.certificates')

</div>

@endsection