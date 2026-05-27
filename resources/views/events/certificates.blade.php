@extends('layouts.app')

@section('content')

<div class="container py-4">

    @include('events.partials.certificates')

    @include('events.partials.certificate-editor-modal')

</div>

@endsection