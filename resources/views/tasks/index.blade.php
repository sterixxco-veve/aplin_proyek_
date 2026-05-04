@extends('layouts.app')

@section('content')
<div class="container">

    <h3 class="fw-bold mb-4">Pilih Event</h3>

    <div class="row">
    @foreach($events as $event)

    @php
        $total = $event->tasks->count();
        $todo = $event->tasks->where('status','todo')->count();
        $progress = $event->tasks->where('status','progress')->count();
        $done = $event->tasks->where('status','done')->count();
    @endphp

    <div class="col-md-4 mb-4">
    <a href="/tasks/event/{{ $event->id_event }}" class="text-decoration-none">

    <div class="card p-4 shadow-sm" style="border-radius:16px;">

        <div class="d-flex align-items-center gap-3 mb-3">
            <div class="bg-primary text-white rounded-3 p-3">
                <i class="bi bi-calendar"></i>
            </div>

            <div>
                <h6 class="fw-bold mb-0">{{ $event->nama_event }}</h6>
                <small class="text-muted">{{ \Carbon\Carbon::parse($event->tgl_mulai)->format('M d, Y') }}</small>
            </div>
        </div>

        <div class="border-top pt-3">
            <small class="text-muted">Total Tasks</small>
            <div class="fw-bold">{{ $total }}</div>

            <div class="d-flex gap-2 mt-2 small">
                <span class="badge bg-light text-dark">{{ $todo }} Todo</span>
                <span class="badge bg-primary">{{ $progress }} Progress</span>
                <span class="badge bg-success">{{ $done }} Done</span>
            </div>
        </div>

    </div>

    </a>
    </div>

    @endforeach
    </div>

</div>
@endsection