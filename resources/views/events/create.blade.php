@extends('layouts.app')

@section('content')
<div class="container">

    <h2>Buat Event</h2>

    <form method="POST" action="/events">
        @csrf

        {{-- Nama Event --}}
        <div style="margin-bottom: 15px;">
            <label>Nama Event</label><br>
            <input type="text" name="nama_event" required>
        </div>

        {{-- Pilih Organization --}}
        <div style="margin-bottom: 15px;">
            <label>Organization</label><br>
            <select name="id_org" required>
                <option value="">-- Pilih Organization --</option>

                @foreach ($organizations as $org)
                    <option value="{{ $org->id_org }}">
                        {{ $org->nama_org }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Kategori --}}
        <div style="margin-bottom: 15px;">
            <label>Kategori</label><br>
            <select name="kategori" required>
                <option value="study_jam">Study Jam</option>
                <option value="seminar">Seminar</option>
                <option value="lomba">Lomba</option>
                <option value="workshop">Workshop</option>
            </select>
        </div>

        {{-- Tanggal Mulai --}}
        <div style="margin-bottom: 15px;">
            <label>Tanggal Mulai</label><br>
            <input type="datetime-local" name="tgl_mulai">
        </div>

        <button type="submit">
            Buat Event
        </button>

    </form>

</div>
@endsection