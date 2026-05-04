@extends('layouts.app')

@section('content')
<div class="container">

    <h2>Edit Event</h2>

    <form method="POST" action="/events/{{ $event->id_event }}">
        @csrf
        @method('PUT')

        {{-- Nama --}}
        <div class="mb-3">
            <label>Nama Event</label>
            <input type="text" name="nama_event"
                   value="{{ $event->nama_event }}"
                   class="form-control">
        </div>

        {{-- Organization --}}
        <div class="mb-3">
            <label>Organization</label>
            <select name="id_org" class="form-control">
                @foreach ($organizations as $org)
                    <option value="{{ $org->id_org }}"
                        {{ $event->id_org == $org->id_org ? 'selected' : '' }}>
                        {{ $org->nama_org }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Kategori --}}
        <div class="mb-3">
            <label>Kategori</label>
            <select name="kategori" class="form-control">
                <option value="study_jam" {{ $event->kategori == 'study_jam' ? 'selected' : '' }}>Study Jam</option>
                <option value="seminar" {{ $event->kategori == 'seminar' ? 'selected' : '' }}>Seminar</option>
                <option value="lomba" {{ $event->kategori == 'lomba' ? 'selected' : '' }}>Lomba</option>
                <option value="workshop" {{ $event->kategori == 'workshop' ? 'selected' : '' }}>Workshop</option>
            </select>
        </div>

        {{-- Tanggal --}}
        <div class="mb-3">
            <label>Tanggal Mulai</label>
            <input type="datetime-local"
                   name="tgl_mulai"
                   value="{{ $event->tgl_mulai }}"
                   class="form-control">
        </div>

        <button class="btn btn-primary">
            Update Event
        </button>

    </form>

</div>
@endsection