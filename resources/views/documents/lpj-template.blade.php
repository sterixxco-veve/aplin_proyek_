<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>LPJ Acara</title>
    <style>
        @page { margin: 24px; }
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #000;
        }
        .header {
            text-align: center;
            font-weight: bold;
            font-size: 15pt;
            margin-bottom: 22px;
        }
        .section { margin-top: 18px; }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
        }
        .muted { color: #555; }
    </style>
</head>
<body>
@php
    $eventName = $event_name ?? $event->nama_event ?? '-';
    $realizationDate = $realization_date ?? $realized_date ?? ($event->tgl_mulai ? \Carbon\Carbon::parse($event->tgl_mulai)->format('Y-m-d') : null);
    $realizedVenue = $realized_venue ?? $venue ?? '-';
@endphp

<div class="header">
    LAPORAN PERTANGGUNGJAWABAN ACARA<br>
    {{ $eventName }}
</div>

<div class="section">
    <strong>I. WAKTU DAN TEMPAT REALISASI</strong>
    <table style="margin-top: 8px;">
        <tr><td width="30%">Tanggal</td><td>{{ $realizationDate ? \Carbon\Carbon::parse($realizationDate)->locale('id')->translatedFormat('l, d F Y') : '-' }}</td></tr>
        <tr><td>Tempat</td><td>{{ $realizedVenue }}</td></tr>
        <tr><td>Jumlah Peserta</td><td>{{ $participant_count ?? 0 }} orang</td></tr>
    </table>
</div>

<div class="section">
    <strong>II. PELAKSANAAN ACARA</strong>
    <p>{!! nl2br(e($implementation ?? '-')) !!}</p>
</div>

<div class="section">
    <strong>III. EVALUASI</strong>
    <p>{!! nl2br(e($evaluation ?? '-')) !!}</p>
</div>

<div class="section">
    <strong>IV. RUNDOWN KEGIATAN</strong>
    <table style="margin-top: 8px;">
        <thead>
            <tr>
                <th style="width: 20%;">Waktu</th>
                <th style="width: 20%;">Durasi</th>
                <th>Kegiatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rundowns as $item)
                <tr>
                    <td>{{ substr((string) ($item->waktu_mulai ?? ''), 0, 5) }} - {{ substr((string) ($item->waktu_selesai ?? ''), 0, 5) }}</td>
                    <td>{{ $item->durasi ?? '-' }}</td>
                    <td>{{ $item->kegiatan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="muted">Belum ada rundown yang tersimpan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</body>
</html>
