<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Proposal Kegiatan</title>
    <style>
        @page { margin: 24px; }
        body {
            font-family: "Times New Roman", serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
        }
        .cover { text-align: center; margin-top: 80px; }
        .section-title {
            font-weight: bold;
            font-size: 14pt;
            margin: 18px 0 8px;
        }
        .page-break { page-break-after: always; }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
        }
        .no-border td { border: none; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .muted { color: #444; }
    </style>
</head>
<body>
@php
    $eventName = $event_name ?? $event->nama_event ?? '-';
    $organizationName = $organization_name ?? $organization?->nama_org ?? '-';
    $eventDate = $event_date ?? ($event->tgl_mulai ? \Carbon\Carbon::parse($event->tgl_mulai)->format('Y-m-d') : null);
    $signatureCount = (int) ($signature_count ?? 2);
    $signatureCount = in_array($signatureCount, [2, 3, 4], true) ? $signatureCount : 2;
    $signatureLabels = [
        2 => ['Ketua Acara', 'Penanggung Jawab'],
        3 => ['Ketua Acara', 'Sekretaris', 'Penanggung Jawab'],
        4 => ['Ketua Acara', 'Sekretaris', 'Bendahara', 'Penanggung Jawab'],
    ];
    $signers = $signatureLabels[$signatureCount];
@endphp

<div class="cover">
    @if(!empty($organization_logo))
        <img src="{{ storage_path('app/public/' . $organization_logo) }}" style="width:140px; margin-bottom: 20px;" alt="Logo">
    @endif

    <h1 style="margin-bottom: 8px;">PROPOSAL KEGIATAN</h1>
    <h2 style="margin: 0 0 8px;">{{ strtoupper($eventName) }}</h2>
    <h3 style="margin: 0;">{{ strtoupper($organizationName) }}</h3>
    <p style="margin-top: 12px;">{{ $academic_year ?? '' }}</p>
</div>

<div class="page-break"></div>

<div class="section-title">I. PENDAHULUAN</div>
<strong>A. Latar Belakang</strong>
<p>{!! nl2br(e($background_text ?? '-')) !!}</p>

<strong>B. Tujuan</strong>
<p>{!! nl2br(e($objectives ?? '-')) !!}</p>

<div class="section-title">II. WAKTU DAN TEMPAT PELAKSANAAN</div>
<table>
    <tr><td width="30%">Hari / Tanggal</td><td>{{ $eventDate ? \Carbon\Carbon::parse($eventDate)->locale('id')->translatedFormat('l, d F Y') : '-' }}</td></tr>
    <tr><td>Waktu</td><td>{{ trim(($start_time ?? '') . ' - ' . ($end_time ?? '')) ?: '-' }}</td></tr>
    <tr><td>Tempat</td><td>{{ $venue ?? '-' }}</td></tr>
</table>

<div class="section-title">III. DESKRIPSI KEGIATAN</div>
<p>{!! nl2br(e($description_text ?? '-')) !!}</p>

<h4>Rundown Kegiatan</h4>
<table>
    <tr>
        <th style="width: 8%;">No</th>
        <th style="width: 22%;">Waktu</th>
        <th>Kegiatan</th>
    </tr>
    @forelse($rundowns as $index => $item)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td>
                {{ substr((string) ($item->waktu_mulai ?? ''), 0, 5) }}
                -
                {{ substr((string) ($item->waktu_selesai ?? ''), 0, 5) }}
            </td>
            <td>{{ $item->kegiatan ?? '-' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="3" class="text-center muted">Belum ada rundown.</td>
        </tr>
    @endforelse
</table>

<div class="section-title">IV. TARGET PESERTA</div>
<table>
    <tr><th>Kategori</th><th>Jumlah</th></tr>
    <tr><td>SMA / SMK</td><td>{{ $target_sma ?? 0 }}</td></tr>
    <tr><td>Mahasiswa</td><td>{{ $target_mahasiswa ?? 0 }}</td></tr>
    <tr><td>Umum</td><td>{{ $target_umum ?? 0 }}</td></tr>
</table>

<div class="section-title">V. KEPENGURUSAN</div>
<table>
    <tr>
        <th style="width: 8%;">No</th>
        <th>Nama</th>
        <th style="width: 35%;">Jabatan</th>
    </tr>
    @forelse($committees as $index => $committee)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td>{{ $committee->user?->name ?? '-' }}</td>
            <td>{{ $committee->jabatan ? ucfirst($committee->jabatan) : '-' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="3" class="text-center muted">Belum ada committee.</td>
        </tr>
    @endforelse
</table>

<div class="section-title">VI. ANGGARAN DANA</div>
<table>
    <tr>
        <th style="width: 8%;">No</th>
        <th>Keterangan</th>
        <th style="width: 12%;">Qty</th>
        <th style="width: 20%;">Nominal</th>
        <th style="width: 20%;">Total</th>
    </tr>
    @php $totalBudget = 0; @endphp
    @forelse($budgets as $index => $budget)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td>{{ $budget->keterangan ?? '-' }}</td>
            <td class="text-center">{{ $budget->qty ?? 0 }}</td>
            <td>Rp {{ number_format($budget->nominal_rencana ?? 0, 0, ',', '.') }}</td>
            <td>Rp {{ number_format($budget->sub_total ?? 0, 0, ',', '.') }}</td>
        </tr>
        @php $totalBudget += (int) ($budget->sub_total ?? 0); @endphp
    @empty
        <tr>
            <td colspan="5" class="text-center muted">Belum ada anggaran.</td>
        </tr>
    @endforelse
    <tr>
        <th colspan="4" class="text-right">TOTAL</th>
        <th>Rp {{ number_format($totalBudget, 0, ',', '.') }}</th>
    </tr>
</table>

<div class="section-title">VII. PENUTUP</div>
<p>
    Demikian proposal kegiatan ini kami buat sebagai bahan pertimbangan dan pedoman pelaksanaan kegiatan.
    Besar harapan kami agar kegiatan ini dapat terlaksana dengan baik.
</p>

<br><br>
<div class="text-right">
    Surabaya, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}
</div>

<table class="no-border" style="margin-top: 30px;">
    <tr>
        @foreach($signers as $label)
            <td class="text-center" width="{{ 100 / count($signers) }}%">
                {{ $label }}<br><br><br><br><br>
                ______________________
            </td>
        @endforeach
    </tr>
</table>
</body>
</html>
