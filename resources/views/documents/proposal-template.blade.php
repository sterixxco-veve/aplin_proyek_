<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Proposal Kegiatan</title>
    <style>
       @page {
            margin: 25px;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 12pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid black;
            padding: 5px;
        }

        .section-title {
            font-weight: bold;
            font-size: 14pt;
            margin-bottom: 10px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
<!DOCTYPE html>

<html lang="id">

<head>
    <meta charset="utf-8">

```
<title>Proposal Kegiatan</title>

<style>

    @page {
        margin: 25px;
    }

    body {
        font-family: "Times New Roman", serif;
        font-size: 12pt;
        line-height: 1.5;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table th,
    table td {
        border: 1px solid black;
        padding: 5px;
    }

    .section-title {
        font-weight: bold;
        font-size: 14pt;
        margin-bottom: 10px;
    }

    .cover {
        text-align: center;
    }

    .page-break {
        page-break-after: always;
    }

    .no-border td {
        border: none;
    }

</style>
```

</head>

<body>

```
<!-- COVER -->

<div class="cover">

    <h1>
        PROPOSAL KEGIATAN
    </h1>

    <br><br>

    @if(!empty($organization_logo))
        <img
            src="{{ storage_path('app/public/' . $organization_logo) }}"
            style="width:180px;">
    @elseif(isset($organization) && !empty($organization->logo_path))
        <img
            src="{{ storage_path('app/public/' . $organization->logo_path) }}"
            style="width:180px;">
    @endif

    <br><br><br>

    <h2>
        {{ strtoupper($event_name) }}
    </h2>

    <br><br><br>

    <h3>
        {{ strtoupper($organization_name ?? $organization->nama_org ?? '') }}
    </h3>

    <h3>
        {{ $academic_year }}
    </h3>

</div>

<div class="page-break"></div>

<!-- PENDAHULUAN -->

<div class="section-title">
    I. PENDAHULUAN
</div>

<strong>A. LATAR BELAKANG</strong>

<p>
    {{ $background_text }}
</p>

<strong>B. TUJUAN</strong>

<p>
    {{ $objectives }}
</p>

<div class="page-break"></div>

<!-- WAKTU DAN TEMPAT -->

<div class="section-title">
    II. WAKTU DAN TEMPAT PELAKSANAAN
</div>

<ul>
    <li>
        Hari / Tanggal :
        {{ \Carbon\Carbon::parse($event_date)->locale('id')->translatedFormat('l, d F Y') }}
    </li>

    <li>
        Waktu :
        {{ $start_time ?? '-' }}
        -
        {{ $end_time ?? '-' }}
        WIB
    </li>

    <li>
        Tempat :
        {{ $venue }}
    </li>
</ul>

<div class="page-break"></div>

<!-- DESKRIPSI -->

<div class="section-title">
    III. DESKRIPSI KEGIATAN
</div>

<p>
    {{ $description_text }}
</p>

<br>

<h3>
    Rundown Kegiatan
</h3>

<table>

    <tr>
        <th>No</th>
        <th>Waktu</th>
        <th>Kegiatan</th>
    </tr>

    @foreach($rundowns as $index => $item)

    <tr>

        <td>{{ $index + 1 }}</td>

        <td>
            {{ substr($item->waktu_mulai,0,5) }}
            -
            {{ substr($item->waktu_selesai,0,5) }}
        </td>

        <td>
            {{ $item->kegiatan }}
        </td>

    </tr>

    @endforeach

</table>

<div class="page-break"></div>

<!-- TARGET -->

<div class="section-title">
    IV. TARGET PESERTA
</div>

<table>

    <tr>
        <th>Kategori</th>
        <th>Jumlah</th>
    </tr>

    <tr>
        <td>SMA / SMK</td>
        <td>{{ $target_sma ?? 0 }}</td>
    </tr>

    <tr>
        <td>Mahasiswa</td>
        <td>{{ $target_mahasiswa ?? 0 }}</td>
    </tr>

    <tr>
        <td>Umum</td>
        <td>{{ $target_umum ?? 0 }}</td>
    </tr>

</table>

<div class="page-break"></div>

<!-- KEPENGURUSAN -->

<div class="section-title">
    V. KEPENGURUSAN
</div>

<table>

    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Jabatan</th>
    </tr>

    @foreach($committees as $index => $committee)

    <tr>

        <td>{{ $index + 1 }}</td>

        <td>
            {{ $committee->user?->name ?? '-' }}
        </td>

        <td>
            {{ $committee->jabatan }}
        </td>

    </tr>

    @endforeach

</table>

<div class="page-break"></div>

<!-- ANGGARAN -->

<div class="section-title">
    VI. ANGGARAN DANA
</div>

<table>

    <tr>
        <th>No</th>
        <th>Keterangan</th>
        <th>Qty</th>
        <th>Nominal</th>
        <th>Total</th>
    </tr>

    @php
        $totalBudget = 0;
    @endphp

    @foreach($budgets as $index => $budget)

    <tr>

        <td>{{ $index + 1 }}</td>

        <td>{{ $budget->keterangan }}</td>

        <td>{{ $budget->qty }}</td>

        <td>
            Rp {{ number_format($budget->nominal_rencana,0,',','.') }}
        </td>

        <td>
            Rp {{ number_format($budget->sub_total,0,',','.') }}
        </td>

    </tr>

    @php
        $totalBudget += $budget->sub_total;
    @endphp

    @endforeach

    <tr>
        <th colspan="4">
            TOTAL
        </th>

        <th>
            Rp {{ number_format($totalBudget,0,',','.') }}
        </th>
    </tr>

</table>

<div class="page-break"></div>

<!-- PENUTUP -->

<div class="section-title">
    VII. PENUTUP
</div>

<p style="text-align:justify;">
    Demikian proposal kegiatan ini kami buat.
    Besar harapan kami agar kegiatan ini dapat terlaksana dengan baik
    serta memperoleh dukungan dari berbagai pihak.
    Atas perhatian dan kerja samanya kami ucapkan terima kasih.
</p>

<br><br><br>

<div style="text-align:right;">
    Surabaya,
    {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}
</div>

<br><br>

<table class="no-border">

    <tr>

        <td align="center">
            Mengetahui,
            <br><br><br><br><br>
            ______________________
            <br>
            Ketua Acara
        </td>

        <td align="center">
            Hormat Kami,
            <br><br><br><br><br>
            ______________________
            <br>
            Penanggung Jawab
        </td>

    </tr>

</table>
```

</body>

</html>

</body>
</html>