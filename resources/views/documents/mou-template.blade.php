<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Memorandum of Understanding</title>
    <style>
        @page { 
            size: a4;
            margin: 3cm 3cm 3cm 3cm; /* Margin standar dokumen resmi */
        }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .sub-header {
            text-align: center;
            font-weight: bold;
            font-size: 12pt;
            margin-bottom: 30px;
        }
        p {
            text-align: justify;
            margin-bottom: 12px;
            text-indent: 0;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 15px 30px;
        }
        .details-table td {
            border: none;
            padding: 3px 0;
            vertical-align: top;
        }
        .details-table td.label {
            width: 150px;
        }
        .details-table td.separator {
            width: 15px;
            text-align: center;
        }
        .section-title {
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 5px;
        }
        .section-content {
            text-align: justify;
            margin-left: 20px;
            margin-bottom: 15px;
        }
        .signature-container {
            margin-top: 50px;
            width: 100%;
            page-break-inside: avoid;
        }
        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }
        .signature-table td {
            border: none;
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
    </style>
</head>
<body>
@php
    // --- Logika Helper Terbilang Bahasa Indonesia ---
    if (!function_exists('mou_penyebut')) {
        function mou_penyebut($nilai) {
            $nilai = abs($nilai);
            $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
            $temp = "";
            if ($nilai < 12) {
                $temp = " " . $huruf[$nilai];
            } else if ($nilai < 20) {
                $temp = mou_penyebut($nilai - 10) . " belas";
            } else if ($nilai < 100) {
                $temp = mou_penyebut($nilai / 10) . " puluh" . mou_penyebut($nilai % 10);
            } else if ($nilai < 200) {
                $temp = " seratus" . mou_penyebut($nilai - 100);
            } else if ($nilai < 1000) {
                $temp = mou_penyebut($nilai / 100) . " ratus" . mou_penyebut($nilai % 100);
            } else if ($nilai < 2000) {
                $temp = " seribu" . mou_penyebut($nilai - 1000);
            } else if ($nilai < 1000000) {
                $temp = mou_penyebut($nilai / 1000) . " ribu" . mou_penyebut($nilai % 1000);
            }
            return $temp;
        }
    }

    if (!function_exists('mou_terbilang')) {
        function mou_terbilang($nilai) {
            if ($nilai < 0) {
                $hasil = "minus " . trim(mou_penyebut($nilai));
            } else {
                $hasil = trim(mou_penyebut($nilai));
            }
            return $hasil;
        }
    }

    // --- Pemrosesan Data & Fallbacks ---
    $firstPartyName = $first_party_name ?? 'IEEE Student Branch Institut Sains dan Teknologi Terpadu Surabaya';
    $secondPartyName = $second_party ?? $second_party_name ?? '[pihak_kedua]';
    
    // Default Pihak Pertama dari IEEE
    $firstPartyRepresentative = $first_party_representative ?? 'Nama Perwakilan Pihak Pertama';
    $firstPartyRole = $first_party_role ?? 'Ketua IEEE Student Branch';
    $firstPartyAddress = $first_party_address ?? 'Jalan Ngagel Jaya Tengah 73–77, Surabaya, 60284';
    $firstPartyEmail = $first_party_email ?? 'ieeesb@istts.ac.id';
    $firstPartyPhone = $first_party_phone ?? '(031) 5021252';
    $firstPartyActionAs = $first_party_action_as ?? 'IEEE Student Branch Institut Sains dan Teknologi Terpadu Surabaya';

    // Pihak Kedua
    $secondPartyRepresentative = $second_party_representative ?? '[nama_pihak_kedua]';
    $secondPartyRole = $second_party_role ?? '[jabatan_pihak_kedua]';
    $secondPartyAddress = $second_party_address ?? '[alamat_pihak_kedua]';
    $secondPartyEmail = $second_party_email ?? '[email_pihak_kedua]';
    $secondPartyPhone = $second_party_phone ?? '[notelp_pihak_kedua]';
    $secondPartyActionAs = $second_party_action_as ?? '[peran_pihak_kedua]';

    // Tanggal Tanda Tangan & Konversi Terbilang
    $sigDateRaw = $signing_date ?? $start_date ?? date('Y-m-d');
    $signingCarbon = \Carbon\Carbon::parse($sigDateRaw);
    $hari = $signingCarbon->locale('id')->translatedFormat('l');
    $tanggalNum = (int)$signingCarbon->format('j');
    $bulanIndo = $signingCarbon->locale('id')->translatedFormat('F');
    $tahunNum = (int)$signingCarbon->format('Y');

    $terbilangTanggal = mou_terbilang($tanggalNum);
    $terbilangTahun = mou_terbilang($tahunNum);
    $versiTerbilang = trim("$terbilangTanggal bulan $bulanIndo tahun $terbilangTahun");

    // Detail Kerja Sama
    $signingPlace = $signing_place ?? 'Surabaya';
    $cooperationTitle = $cooperation_title ?? '[kerja_sama]';
    $cooperationTime = $cooperation_time ?? '[waktu]';
    $cooperationVenue = $cooperation_venue ?? '[tempat]';
    $cooperationPurpose = $cooperation_purpose ?? '[tujuan]';
    $cooperationScope = $cooperation_scope ?? '[lingkup]';
    
    $startDate = $start_date ? \Carbon\Carbon::parse($start_date)->locale('id')->translatedFormat('d F Y') : '[mulai]';
    $endDate = $end_date ? \Carbon\Carbon::parse($end_date)->locale('id')->translatedFormat('d F Y') : '[selesai]';

    $obligationsFirst = $obligations_first_party ?? '[...]';
    $obligationsSecond = $obligations_second_party ?? '[...]';
@endphp

<div class="header">MEMORANDUM OF UNDERSTANDING</div>
<div class="sub-header">
    Antara {{ $firstPartyName }}<br>dengan {{ $secondPartyName }}
</div>

<p>
    Pada hari ini, <strong>{{ $hari }}</strong>, tanggal <strong>{{ $tanggalNum }}</strong> bulan <strong>{{ $bulanIndo }}</strong> tahun <strong>{{ $tahunNum }}</strong> (<em>{{ $versiTerbilang }}</em>), bertempat di <strong>{{ $signingPlace }}</strong>, masing-masing pihak yang bertandatangan di bawah ini:
</p>

<!-- DATA PIHAK PERTAMA -->
<table class="details-table">
    <tr>
        <td class="label">Nama</td>
        <td class="separator">:</td>
        <td>{{ $firstPartyRepresentative }}</td>
    </tr>
    <tr>
        <td class="label">Jabatan</td>
        <td class="separator">:</td>
        <td>{{ $firstPartyRole }}</td>
    </tr>
    <tr>
        <td class="label">Alamat</td>
        <td class="separator">:</td>
        <td>{{ $firstPartyAddress }}</td>
    </tr>
    <tr>
        <td class="label">Email</td>
        <td class="separator">:</td>
        <td>{{ $firstPartyEmail }}</td>
    </tr>
    <tr>
        <td class="label">No. telp</td>
        <td class="separator">:</td>
        <td>{{ $firstPartyPhone }}</td>
    </tr>
</table>
<p>
    dalam hal ini bertindak selaku <strong>{{ $firstPartyActionAs }}</strong> yang untuk selanjutnya disebut sebagai <strong>PIHAK PERTAMA</strong>.
</p>

<!-- DATA PIHAK KEDUA -->
<table class="details-table">
    <tr>
        <td class="label">Nama</td>
        <td class="separator">:</td>
        <td>{{ $secondPartyRepresentative }}</td>
    </tr>
    <tr>
        <td class="label">Jabatan</td>
        <td class="separator">:</td>
        <td>{{ $secondPartyRole }}</td>
    </tr>
    <tr>
        <td class="label">Alamat</td>
        <td class="separator">:</td>
        <td>{{ $secondPartyAddress }}</td>
    </tr>
    <tr>
        <td class="label">Email</td>
        <td class="separator">:</td>
        <td>{{ $secondPartyEmail }}</td>
    </tr>
    <tr>
        <td class="label">No. telp</td>
        <td class="separator">:</td>
        <td>{{ $secondPartyPhone }}</td>
    </tr>
</table>
<p>
    dalam hal ini bertindak selaku <strong>{{ $secondPartyActionAs }}</strong> yang untuk selanjutnya disebut sebagai <strong>PIHAK KEDUA</strong>.
</p>

<p>
    Kedua belah pihak telah sepakat untuk menetapkan kewajiban dan tanggung jawab yang berkaitan dengan kerja sama dalam <strong>{{ $cooperationTitle }}</strong> yang akan dilaksanakan pada <strong>{{ $cooperationTime }}</strong> di <strong>{{ $cooperationVenue }}</strong> sesuai dengan ketentuan-ketentuan sebagai berikut:
</p>

<!-- POIN-POIN KERJA SAMA -->
<div class="section-title">1. Tujuan Kerja Sama</div>
<div class="section-content">
    Tujuan kerja sama ini adalah {!! nl2br(e($cooperationPurpose)) !!}.
</div>

<div class="section-title">2. Lingkup Kerja Sama</div>
<div class="section-content">
    Kerja sama ini mencakup {!! nl2br(e($cooperationScope)) !!}.
</div>

<div class="section-title">3. Waktu dan Durasi</div>
<div class="section-content">
    Kerja sama berlaku mulai tanggal <strong>{{ $startDate }}</strong> dan berakhir pada tanggal <strong>{{ $endDate }}</strong>.
</div>

<div class="section-title">4. Kewajiban dan Tanggung Jawab</div>
<div class="section-content">
    <ol style="margin: 0; padding-left: 20px; list-style-type: lower-alpha;">
        <li style="margin-bottom: 8px;">
            Pihak pertama berkewajiban dan bertanggung jawab atas {!! nl2br(e($obligationsFirst)) !!}.
        </li>
        <li>
            Pihak kedua berkewajiban dan bertanggung jawab atas {!! nl2br(e($obligationsSecond)) !!}.
        </li>
    </ol>
</div>

<p style="margin-top: 25px;">
    Demikian Memorandum of Understanding (MoU) ini dibuat dengan penuh kesepahaman dan kesepakatan dari kedua belah pihak sebagai bentuk komitmen untuk melaksanakan kerja sama antara pihak pertama dan pihak kedua.
</p>

<!-- AREA SIGNATURE -->
<div class="signature-container">
    <div style="text-align: right; margin-bottom: 30px;">
        {{ $signingPlace }}, {{ \Carbon\Carbon::parse($sigDateRaw)->locale('id')->translatedFormat('d F Y') }}
    </div>
    <table class="signature-table">
        <tr>
            <td><strong>PIHAK PERTAMA</strong></td>
            <td><strong>PIHAK KEDUA</strong></td>
        </tr>
        <tr>
            <td style="height: 100px; vertical-align: middle;">
                <!-- Ruang Kosong Tanda Tangan -->
            </td>
            <td style="height: 100px; vertical-align: middle;">
                <!-- Ruang Kosong Tanda Tangan -->
            </td>
        </tr>
        <tr>
            <td>
                <u><strong>{{ $firstPartyRepresentative }}</strong></u><br>
                {{ $firstPartyRole }}
            </td>
            <td>
                <u><strong>{{ $secondPartyRepresentative }}</strong></u><br>
                {{ $secondPartyRole }}
            </td>
        </tr>
    </table>
</div>

</body>
</html>