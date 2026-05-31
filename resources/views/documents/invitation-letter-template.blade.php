<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Invitation Letter</title>
    <style>
        @page { margin: 28px; }
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #000;
        }
        .header {
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 18px;
        }
        .info-table td {
            padding-right: 18px;
            vertical-align: top;
        }
        .event-details {
            border: 1px solid #ddd;
            background: #f9f9f9;
            padding: 12px 14px;
            margin: 18px 0;
        }
    </style>
</head>
<body>
@php
    $eventName = $event_name ?? $event->nama_event ?? '-';
    $eventDate = $event_date ?? ($event->tgl_mulai ? \Carbon\Carbon::parse($event->tgl_mulai)->format('Y-m-d') : null);
    $subject = $subject ?? ('Undangan ' . $eventName);
@endphp

<div class="header">
    <strong>{{ $organization_name ?? ($event->organization?->nama_org ?? 'Organisasi') }}</strong><br>
    {{ $subject }}
</div>

<p>Surabaya, {{ $date_sent ?? \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</p>

<table class="info-table">
    <tr><td>Nomor</td><td>: {{ $letter_number ?? '-' }}</td></tr>
    <tr><td>Perihal</td><td>: {{ $subject }}</td></tr>
</table>

<p>
    Kepada Yth.<br>
    {{ $recipient_name ?? '-' }}<br>
    {{ $recipient_role ?? '-' }}
</p>

<p>Dengan hormat,</p>
<p>
    Sehubungan dengan kegiatan <strong>{{ $eventName }}</strong>, kami mengundang Bapak/Ibu/Saudara untuk hadir pada:
</p>

<div class="event-details">
    <table class="info-table">
        <tr><td>Hari/Tanggal</td><td>: {{ $eventDate ? \Carbon\Carbon::parse($eventDate)->locale('id')->translatedFormat('l, d F Y') : '-' }}</td></tr>
        <tr><td>Waktu</td><td>: {{ $event_time ?? '-' }}</td></tr>
        <tr><td>Tempat</td><td>: {{ $event_location ?? '-' }}</td></tr>
        <tr><td>Peserta</td><td>: {{ $participant_total ?? 0 }} orang</td></tr>
    </table>
</div>

<p>{!! nl2br(e($invitation_body_text ?? 'Kami berharap kehadiran Bapak/Ibu/Saudara. Atas perhatian dan kerja samanya kami ucapkan terima kasih.')) !!}</p>

<p>Hormat kami,<br><br><br><br>
    Organizing Committee
</p>
</body>
</html>
