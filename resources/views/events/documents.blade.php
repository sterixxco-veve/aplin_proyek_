@extends('layouts.app')

@section('content')

<div class="container py-4">
    @if($event->canManageCertificateBy(auth()->user()))
        @include('events.partials.documents')
    @else
        <div class="alert alert-warning">
            <h5 class="alert-heading">Tidak punya akses</h5>
            <p>Anda tidak memiliki akses untuk melihat halaman ini.</p>
        </div>
    @endif
</div>

@endsection