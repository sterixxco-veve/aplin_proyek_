<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Memorandum of Understanding</title>
    <style>
        @page { margin: 28px; }
        body {
            font-family: "Times New Roman", serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
        }
        .header {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 18px;
        }
        .sub-header { text-align: center; margin-bottom: 24px; }
        .section { margin-top: 18px; text-align: justify; }
        .party-details { margin-left: 18px; margin-top: 8px; }
        .signature-container { margin-top: 42px; width: 100%; display: table; }
        .signature-box { display: table-cell; width: 50%; text-align: center; vertical-align: top; }
    </style>
</head>
<body>
@php
    $firstParty = $first_party ?? ($event->organization?->nama_org ?? '-');
    $secondParty = $second_party ?? '-';
    $firstRole = $first_party_role ?? 'Pihak Pertama';
    $secondRole = $second_party_role ?? 'Pihak Kedua';
@endphp

<div class="header">MEMORANDUM OF UNDERSTANDING</div>
<div class="sub-header">
    Antara {{ $firstParty }} dengan {{ $secondParty }}
</div>

<p>
    Pada hari ini, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l') }},
    tanggal {{ $start_date ? \Carbon\Carbon::parse($start_date)->locale('id')->translatedFormat('d F Y') : \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }},
    bertempat di {{ $venue ?? '-' }}, masing-masing pihak yang bertandatangan di bawah ini sepakat untuk mengikatkan diri dalam perjanjian kerja sama berikut:
</p>

<div class="section">
    <strong>PIHAK PERTAMA</strong>
    <div class="party-details">
        Nama : {{ $firstParty }}<br>
        Jabatan : {{ $firstRole }}
    </div>
</div>

<div class="section">
    <strong>PIHAK KEDUA</strong>
    <div class="party-details">
        Nama : {{ $secondParty }}<br>
        Jabatan : {{ $secondRole }}
    </div>
</div>

<div class="section">
    <p>{!! nl2br(e($cooperation ?? '-')) !!}</p>
    <p>
        Kesepakatan ini berlaku sejak {{ $start_date ?? '-' }} sampai {{ $end_date ?? '-' }}.
    </p>
</div>

<div class="signature-container">
    <div class="signature-box">
        PIHAK PERTAMA<br><br><br><br>
        ({{ $firstParty }})
    </div>
    <div class="signature-box">
        PIHAK KEDUA<br><br><br><br>
        ({{ $secondParty }})
    </div>
</div>
</body>
</html>
