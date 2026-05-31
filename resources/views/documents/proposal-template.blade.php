<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Proposal Kegiatan</title>
    <style>
        @page { 
            margin: 24px; 
        }
        body {
            font-family: "Times New Roman", serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            padding: 20px;
        }
        .cover { 
            text-align: center; 
            margin-top: 50px; 
            height: 100%;
        }
        
        /* Mengatur agar setiap bab otomatis mulai di halaman baru dengan kop surat */
        .bab-page {
            page-break-before: always;
        }

        .section-title {
            font-weight: bold;
            font-size: 14pt;
            margin: 18px 0 15px;
            text-transform: uppercase;
        }
        
        /* Pengaturan tabel rapi dengan border hitam tipis sesuai standar */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11pt;
            page-break-inside: avoid;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: middle;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .text-center { 
            text-align: center; 
        }
        .text-right { 
            text-align: right; 
        }
        .muted { 
            color: #444; 
        }

        /* Tabel Khusus Tanpa Border */
        .no-border, .no-border tr, .no-border td { 
            border: none !important; 
            padding: 4px;
        }
        
        /* Kop Surat (Header) */
        .header-proposal {
            width: 100%;
            border-bottom: 4px solid #000;
            padding-bottom: 8px;
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .header-table {
            width: 100%;
            border: none;
        }

        .header-table td {
            border: none;
            vertical-align: middle;
        }

        .header-logo-left {
            width: 90px;
            text-align: center;
        }

        .header-logo-right {
            width: 120px;
            text-align: center;
        }

        .header-title {
            text-align: center;
            line-height: 1.2;
        }

        .header-title h2 {
            margin: 0;
            font-size: 13pt;
            font-weight: bold;
        }

        .header-title h3 {
            margin: 3px 0;
            font-size: 11pt;
            font-weight: bold;
        }

        .header-title p {
            margin: 0;
            font-size: 8.5pt;
        }

        /* Area Tanda Tangan */
        .signature-section {
            page-break-inside: avoid;
            margin-top: 30px;
        }
    </style>
</head>
<body>
@php
    $eventName = $event_name ?? $event->nama_event ?? 'STOP COPY-PASTING, START ENGINEERING: THE "CORRECT" WAY TO USE AI TOOLS';
    $organizationName = $organization_name ?? $organization?->nama_org ?? 'Google Developer Groups On Campus institut STTS';
    
    $eventDate = $event_date ?? ($event->tgl_mulai 
        ? \Carbon\Carbon::parse($event->tgl_mulai)->format('Y-m-d') 
        : '2025-12-05'); // Default: Jumat, 5 Desember 2025

    $startTime = $start_time ?? '15:30';
    $endTime = $end_time ?? '18:00';
    $venueName = $venue ?? 'Auditorium ISTTS';
    $academicYear = $academic_year ?? 'GOOGLE DEVELOPER GROUP ON CAMPUS 2025/2026';
    
    // Background & Objectives Fallbacks
    $backgroundText = $background_text ?? "Perkembangan teknologi Artificial Intelligence (AI) kini telah mengubah cara Software Development dan Software Testing dilakukan. Berbagai alat berbasis Al mampu menghasilkan kode dan skenario pengujian dengan sangat cepat. Namun, kemudahan ini sering menyebabkan para profesional hanya melakukan copy-paste tanpa melalui proses rekayasa perangkat lunak yang benar, seperti analisis, verifikasi, dan validasi.\n\nPenggunaan Al secara pasif seperti ini dapat menimbulkan risiko besar, mulai dari celah keamanan, kegagalan integrasi sistem, hingga menurunnya kualitas arsitektur perangkat lunak. Karena itu, industri perlu segera mengubah peran Developer dan Tester agar mampu memanfaatkan Al secara strategis bukan sekadar sebagai alat pembuat kode, tetapi sebagai Co-Engineer yang mendukung proses rekayasa perangkat lunak secara profesional.\n\nSeminar ini diadakan untuk menjawab kebutuhan tersebut, memastikan bahwa inovasi Al dimanfaatkan dengan cara yang bertanggung jawab, profesional, dan sesuai dengan standar industri modern.";
    
    $objectivesText = $objectives ?? "1. Meningkatkan pemahaman peserta mengenai peran Al dalam Software Development dan Software Testing secara profesional.\n2. Mendorong peserta untuk mengembangkan pola pikir kritis dalam memanfaatkan Al, tidak hanya menerima hasil secara langsung tetapi juga mampu mengevaluasi kualitasnya.\n3. Membekali peserta dengan kemampuan melakukan analisis, verifikasi, dan validasi meskipun menggunakan Al sebagai assistive tool.\n4. Menyediakan forum diskusi dan berbagi pengalaman antarpelaku industri, akademisi, dan praktisi teknologi untuk meningkatkan kualitas praktik pengembangan perangkat lunak di era AI.";

    $descriptionText = $description_text ?? "Seminar \"Stop Copy-Pasting, Start Engineering: The 'Correct' Way to Use AI Tools\" merupakan sesi transformatif yang dirancang bagi Developer dan Tester untuk mengatasi risiko penggunaan Al secara pasif (sekadar copy-paste). Acara ini akan membahas secara mendalam perubahan peran profesional di era AI, menekankan pentingnya analisis, verifikasi, dan validasi terhadap output yang dihasilkan AI. Peserta akan dibekali best practices dalam memanfaatkan Al sebagai Co-Engineer yang bertanggung jawab, dengan fokus pada peningkatan kualitas kode, efisiensi proses testing, serta memastikan hasil pengembangan perangkat lunak tetap akurat, aman, dan sesuai standar rekayasa industri.";

    // Rundowns Fallback
    $rundownItems = $rundowns ?? [];

    // Target Peserta Fallback
    $targetText = $target_text ?? null;
    $hasTargetSma = isset($target_sma) && $target_sma > 0;
    $hasTargetMahasiswa = isset($target_mahasiswa) && $target_mahasiswa > 0;
    $hasTargetUmum = isset($target_umum) && $target_umum > 0;
    
    if (!$hasTargetSma && !$hasTargetMahasiswa && !$hasTargetUmum && empty($targetText)) {
        $targetText = "70 External (Umum)";
    }

    // Committees Fallback (All 26 members matching PDF)
    $committeeItems = $committees ?? [];
    

    $detectedChairmanName = null;
    foreach ($committeeItems as $committee) {
        $jabatan = strtolower($committee->jabatan ?? '');
        // Mencari jabatan yang mengandung kata 'ketua' tapi bukan 'wakil'
        if (str_contains($jabatan, 'ketua') && !str_contains($jabatan, 'wakil')) {
            $detectedChairmanName = $committee->user?->name ?? $committee->name ?? null;
            break;
        }
    }
    // Gabungkan dengan prioritas: Detected dari list > $chairman_name input > Default Fallback 'Gracia Krisnanda'
    $resolvedChairman = $detectedChairmanName ?? $chairman_name ?? 'Gracia Krisnanda';

    // Signature Settings
    $signatureCount = (int) ($signature_count ?? 4);
    $signatureCount = in_array($signatureCount, [2,4], true) ? $signatureCount : 4;

    $signatureDate = $signature_date ?? ($eventDate 
        ? \Carbon\Carbon::parse($eventDate)->subDays(9)->locale('id')->translatedFormat('d F Y') 
        : '26 November 2025');

    // Kita gunakan public_path() agar langsung mengarah ke folder public/ yang aman dibaca sistem
    $logoIsttsPath = public_path('logo_istts.png');
    $hasLogoIstts = file_exists($logoIsttsPath);

    // Budgets (Separating Pemasukan & Pengeluaran)
    $pemasukanItems = $pemasukan ?? [];
    $pengeluaranItems = $pengeluaran ?? [];

    if (empty($pemasukanItems) && empty($pengeluaranItems)) {
        if (!empty($budgets) && count($budgets) > 0) {
            $pemasukanItems = [];
            $pengeluaranItems = [];
            foreach ($budgets as $b) {
                if (isset($b->jenis) && strtolower($b->jenis) == 'pemasukan') {
                    $pemasukanItems[] = $b;
                } else {
                    $pengeluaranItems[] = $b;
                }
            }
            if (empty($pemasukanItems)) {
                $totalExpense = 0;
                foreach($pengeluaranItems as $p) {
                    $totalExpense += (int)($p->sub_total ?? $p->total ?? 0);
                }
                $pemasukanItems = [
                    (object)['sumber_dana' => 'ISTTS', 'qty' => 1, 'nominal' => $totalExpense, 'total' => $totalExpense]
                ];
            }
        } else {
            // Default budgets from PDF
            $pemasukanItems = [
                (object)['sumber_dana' => 'ISTTS', 'qty' => 1, 'nominal' => 1080000, 'total' => 1080000]
            ];
            $pengeluaranItems = [
                (object)['keterangan' => 'Banner 4 x 3', 'qty' => 12, 'nominal' => 15000, 'total' => 180000],
                (object)['keterangan' => 'Doorprize', 'qty' => 1, 'nominal' => 200000, 'total' => 200000],
                (object)['keterangan' => 'Konsumsi Snack', 'qty' => 70, 'nominal' => 10000, 'total' => 700000],
            ];
        }
    }
@endphp

<!-- ================= COVER PAGE ================= -->
<div class="cover">
    <h1 style="margin-top: 30px; margin-bottom: 20px; font-size: 22pt; font-weight: bold; letter-spacing: 1px;">PROPOSAL KEGIATAN</h1>
    
    <div style="margin: 40px 0; min-height: 80px;">
        <h2 style="margin: 0; font-size: 16pt; font-weight: bold; line-height: 1.4;">{{ strtoupper($eventName) }}</h2>
    </div>

    <div style="margin: 20px 0;">
        <h3 style="margin: 0; font-size: 13pt; font-weight: normal; font-style: italic;">{{ $organizationName }}</h3>
    </div>

    <!-- Center Logo / Space for Cover Image -->
    <div style="margin: 70px auto 40px; text-align: center;">
        @if(!empty($organization_logo))
            <img src="{{ storage_path('app/public/' . $organization_logo) }}" style="max-width: 220px; max-height: 140px; margin-bottom: 20px;" alt="Logo Organisasi">
        @endif
        
        <div style="margin-top: 10px;">
            @if($hasLogoIstts)
                <img src="{{ $logoIsttsPath }}" style="width: 150px; height: auto;" alt="Logo ISTTS">
            @else
                <div style="border: 2px solid #000; display: inline-block; padding: 15px 30px; font-weight: bold; border-radius: 50%; font-size: 14pt;">ISTTS</div>
            @endif
        </div>
    </div>

    <!-- Bottom Metadata -->
    <div style="margin-top: 100px; font-size: 11pt; line-height: 1.6; font-weight: bold; text-transform: uppercase;">
        <div>{{ $academicYear }}</div>
        <div>INSTITUT SAINS DAN TEKNOLOGI TERPADU SURABAYA</div>
        <div>SURABAYA</div>
        <div>2025</div>
    </div>
</div>


<!-- ================= BAB I: PENDAHULUAN ================= -->
<div class="bab-page">
    <div class="header-proposal">
        <table class="header-table">
            <tr>
                <td class="header-logo-left">
                    @if($hasLogoIstts)
                        <img src="{{ $logoIsttsPath }}" style="width:75px;">
                    @endif
                </td>
                <td class="header-title">
                    <h2>PROPOSAL ACARA</h2>
                    <h3 style="text-transform: uppercase;">{{ $eventName }}</h3>
                    <p>Jalan Ngagel Jaya Tengah 73–77, Surabaya, 60284</p>
                    <p>Tel. (031) 5021252 Fax. (031) 5041509, (031) 5031818</p>
                </td>
                <td class="header-logo-right">
                    @if(!empty($organization_logo))
                        <img src="{{ storage_path('app/public/' . $organization_logo) }}" style="max-width:100px; max-height:75px;">
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="section-title">I. PENDAHULUAN</div>
    <strong style="display: block; margin-bottom: 8px; text-transform: uppercase;">a. Latar Belakang</strong>
    <p style="text-align: justify; text-indent: 35px; margin-top: 0;">{!! nl2br(e($backgroundText)) !!}</p>

    <strong style="display: block; margin-top: 20px; margin-bottom: 8px; text-transform: uppercase;">b. Tujuan</strong>
    <div style="text-align: justify;">
        @if(is_string($objectivesText))
            {!! nl2br(e($objectivesText)) !!}
        @else
            <ol style="margin: 0; padding-left: 20px;">
                @foreach($objectivesText as $obj)
                    <li style="margin-bottom: 5px;">{{ $obj }}</li>
                @endforeach
            </ol>
        @endif
    </div>
</div>


<!-- ================= BAB II: WAKTU DAN TEMPAT ================= -->
<div class="bab-page">
    <div class="header-proposal">
        <table class="header-table">
            <tr>
                <td class="header-logo-left">
                    @if($hasLogoIstts)
                        <img src="{{ $logoIsttsPath }}" style="width:75px;">
                    @endif
                </td>
                <td class="header-title">
                    <h2>PROPOSAL ACARA</h2>
                    <h3 style="text-transform: uppercase;">{{ $eventName }}</h3>
                    <p>Jalan Ngagel Jaya Tengah 73–77, Surabaya, 60284</p>
                    <p>Tel. (031) 5021252 Fax. (031) 5041509, (031) 5031818</p>
                </td>
                <td class="header-logo-right">
                    @if(!empty($organization_logo))
                        <img src="{{ storage_path('app/public/' . $organization_logo) }}" style="max-width:100px; max-height:75px;">
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="section-title">II. WAKTU DAN TEMPAT PELAKSANAAN</div>
    <table class="no-border" style="width: 100%; margin-top: 15px; margin-bottom: 25px;">
        <tr>
            <td style="width: 25%; font-weight: bold;">Hari / Tanggal</td>
            <td style="width: 3%;">:</td>
            <td>{{ $eventDate ? \Carbon\Carbon::parse($eventDate)->locale('id')->translatedFormat('l, d F Y') : 'Jumat, 5 Desember 2025' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Waktu</td>
            <td>:</td>
            <td>{{ trim(($startTime . ' - ' . $endTime)) ?: '15:30 - 18:00' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Tempat</td>
            <td>:</td>
            <td>{{ $venueName }}</td>
        </tr>
    </table>
</div>


<!-- ================= BAB III: DESKRIPSI KEGIATAN ================= -->
<div class="bab-page">
    <div class="header-proposal">
        <table class="header-table">
            <tr>
                <td class="header-logo-left">
                    @if($hasLogoIstts)
                        <img src="{{ $logoIsttsPath }}" style="width:75px;">
                    @endif
                </td>
                <td class="header-title">
                    <h2>PROPOSAL ACARA</h2>
                    <h3 style="text-transform: uppercase;">{{ $eventName }}</h3>
                    <p>Jalan Ngagel Jaya Tengah 73–77, Surabaya, 60284</p>
                    <p>Tel. (031) 5021252 Fax. (031) 5041509, (031) 5031818</p>
                </td>
                <td class="header-logo-right">
                    @if(!empty($organization_logo))
                        <img src="{{ storage_path('app/public/' . $organization_logo) }}" style="max-width:100px; max-height:75px;">
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="section-title">III. DESKRIPSI KEGIATAN</div>
    <p style="text-align: justify; margin-bottom: 25px;">{!! nl2br(e($descriptionText)) !!}</p>

    <h4 style="margin-bottom: 10px; font-weight: bold; text-transform: uppercase; font-size: 11pt;">Rundown Kegiatan</h4>
    <table>
        <thead>
            <tr>
                <th style="width: 8%;">NO.</th>
                <th style="width: 25%;">WAKTUMULAI - SELESAI</th>
                <th style="width: 18%;">DURASI</th>
                <th>KEGIATAN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rundownItems as $index => $item)
                @php
                    $duration = '-';
                    if (!empty($item->waktu_mulai) && !empty($item->waktu_selesai)) {
                        try {
                            $start = \Carbon\Carbon::parse($item->waktu_mulai);
                            $end = \Carbon\Carbon::parse($item->waktu_selesai);
                            $diffMins = $start->diffInMinutes($end);
                            $h = floor($diffMins / 60);
                            $m = $diffMins % 60;
                            $duration = sprintf('%02d:%02d:00', $h, $m);
                        } catch (\Exception $e) {
                            $duration = $item->durasi ?? '-';
                        }
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">
                        {{ substr((string) ($item->waktu_mulai ?? ''), 0, 5) }}
                        -
                        {{ substr((string) ($item->waktu_selesai ?? ''), 0, 5) }}
                    </td>
                    <td class="text-center">{{ $duration }}</td>
                    <td>{{ $item->kegiatan ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


<!-- ================= BAB IV: TARGET PESERTA ================= -->
<div class="bab-page">
    <div class="header-proposal">
        <table class="header-table">
            <tr>
                <td class="header-logo-left">
                    @if($hasLogoIstts)
                        <img src="{{ $logoIsttsPath }}" style="width:75px;">
                    @endif
                </td>
                <td class="header-title">
                    <h2>PROPOSAL ACARA</h2>
                    <h3 style="text-transform: uppercase;">{{ $eventName }}</h3>
                    <p>Jalan Ngagel Jaya Tengah 73–77, Surabaya, 60284</p>
                    <p>Tel. (031) 5021252 Fax. (031) 5041509, (031) 5031818</p>
                </td>
                <td class="header-logo-right">
                    @if(!empty($organization_logo))
                        <img src="{{ storage_path('app/public/' . $organization_logo) }}" style="max-width:100px; max-height:75px;">
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="section-title">IV. TARGET PESERTA</div>
    @if(!empty($targetText))
        <p style="font-size: 12pt; margin-top: 15px;">Target Peserta: <strong>{{ $targetText }}</strong></p>
    @else
        <table style="margin-top: 15px;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th>Kategori Peserta</th>
                    <th style="width: 30%;">Target Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>SMA / SMK</td>
                    <td class="text-center">{{ $target_sma ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Mahasiswa</td>
                    <td class="text-center">{{ $target_mahasiswa ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Umum</td>
                    <td class="text-center">{{ $target_umum ?? 0 }}</td>
                </tr>
            </tbody>
        </table>
    @endif
</div>


<!-- ================= BAB V: KEPENGURUSAN ================= -->
<div class="bab-page">
    <div class="header-proposal">
        <table class="header-table">
            <tr>
                <td class="header-logo-left">
                    @if($hasLogoIstts)
                        <img src="{{ $logoIsttsPath }}" style="width:75px;">
                    @endif
                </td>
                <td class="header-title">
                    <h2>PROPOSAL ACARA</h2>
                    <h3 style="text-transform: uppercase;">{{ $eventName }}</h3>
                    <p>Jalan Ngagel Jaya Tengah 73–77, Surabaya, 60284</p>
                    <p>Tel. (031) 5021252 Fax. (031) 5041509, (031) 5031818</p>
                </td>
                <td class="header-logo-right">
                    @if(!empty($organization_logo))
                        <img src="{{ storage_path('app/public/' . $organization_logo) }}" style="max-width:100px; max-height:75px;">
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="section-title">V. KEPENGURUSAN</div>
    <table style="margin-top: 15px;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th style="width: 6%;">NO.</th>
                <th style="width: 28%;">NAMA</th>
                <th style="width: 15%;">NRP</th>
                <th style="width: 26%;">PRODI</th>
                <th style="width: 25%;">JABATAN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($committeeItems as $index => $committee)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $committee->user?->name ?? $committee->name ?? '-' }}</td>
                    <td class="text-center">{{ $committee->user?->nrp ?? $committee->nrp ?? '-' }}</td>
                    <td>{{ $committee->user?->prodi ?? $committee->prodi ?? '-' }}</td>
                    <td>{{ $committee->jabatan ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


<!-- ================= BAB VI: ANGGARAN DANA ================= -->
<div class="bab-page">
    <div class="header-proposal">
        <table class="header-table">
            <tr>
                <td class="header-logo-left">
                    @if($hasLogoIstts)
                        <img src="{{ $logoIsttsPath }}" style="width:75px;">
                    @endif
                </td>
                <td class="header-title">
                    <h2>PROPOSAL ACARA</h2>
                    <h3 style="text-transform: uppercase;">{{ $eventName }}</h3>
                    <p>Jalan Ngagel Jaya Tengah 73–77, Surabaya, 60284</p>
                    <p>Tel. (031) 5021252 Fax. (031) 5041509, (031) 5031818</p>
                </td>
                <td class="header-logo-right">
                    @if(!empty($organization_logo))
                        <img src="{{ storage_path('app/public/' . $organization_logo) }}" style="max-width:100px; max-height:75px;">
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="section-title">VI. ANGGARAN DANA</div>
    
    <h4 style="margin: 15px 0 8px; font-weight: bold; text-transform: uppercase; font-size: 11pt;">A. PEMASUKAN</h4>
    <table>
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th style="width: 8%;">NO.</th>
                <th>SUMBER DANA</th>
                <th style="width: 12%;">QTY</th>
                <th style="width: 25%;">NOMINAL SATUAN</th>
                <th style="width: 25%;">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @php $totalPemasukan = 0; @endphp
            @foreach($pemasukanItems as $index => $pem)
                @php 
                    $nominal = (int)($pem->nominal ?? $pem->nominal_rencana ?? 0);
                    $qty = (int)($pem->qty ?? 1);
                    $subTotal = (int)($pem->total ?? $pem->sub_total ?? ($nominal * $qty));
                    $totalPemasukan += $subTotal;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $pem->sumber_dana ?? $pem->keterangan ?? '-' }}</td>
                    <td class="text-center">{{ $qty }}</td>
                    <td>Rp {{ number_format($nominal, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($subTotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr style="background-color: #f9f9f9; font-weight: bold;">
                <td colspan="4" class="text-right">TOTAL PEMASUKAN</td>
                <td>Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <h4 style="margin: 25px 0 8px; font-weight: bold; text-transform: uppercase; font-size: 11pt;">B. PENGELUARAN</h4>
    <table>
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th style="width: 8%;">NO.</th>
                <th>KETERANGAN</th>
                <th style="width: 12%;">QTY</th>
                <th style="width: 25%;">NOMINAL SATUAN</th>
                <th style="width: 25%;">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @php $totalPengeluaran = 0; @endphp
            @foreach($pengeluaranItems as $index => $peng)
                @php 
                    $nominal = (int)($peng->nominal ?? $peng->nominal_rencana ?? 0);
                    $qty = (int)($peng->qty ?? 1);
                    $subTotal = (int)($peng->total ?? $peng->sub_total ?? ($nominal * $qty));
                    $totalPengeluaran += $subTotal;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $peng->keterangan ?? '-' }}</td>
                    <td class="text-center">{{ $qty }}</td>
                    <td>Rp {{ number_format($nominal, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($subTotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr style="background-color: #f9f9f9; font-weight: bold;">
                <td colspan="4" class="text-right">TOTAL PENGELUARAN</td>
                <td>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <h4 style="margin: 25px 0 8px; font-weight: bold; text-transform: uppercase; font-size: 11pt;">REKAPITULASI ANGGARAN</h4>
    <table style="width: 50%; margin-top: 10px;">
        <tr>
            <td style="font-weight: bold; background-color: #f2f2f2; width: 60%;">PEMASUKAN</td>
            <td style="font-weight: bold;">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; background-color: #f2f2f2;">PENGELUARAN</td>
            <td style="font-weight: bold;">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
        </tr>
        <tr style="background-color: #f9f9f9;">
            <td style="font-weight: bold; color: #000;">SALDO (TOTAL)</td>
            <td style="font-weight: bold; color: #000;">Rp {{ number_format($totalPemasukan - $totalPengeluaran, 0, ',', '.') }}</td>
        </tr>
    </table>
</div>


<!-- ================= BAB VII: PENUTUP & TANDA TANGAN ================= -->
<div class="bab-page">
    <div class="header-proposal">
        <table class="header-table">
            <tr>
                <td class="header-logo-left">
                    @if($hasLogoIstts)
                        <img src="{{ $logoIsttsPath }}" style="width:75px;">
                    @endif
                </td>
                <td class="header-title">
                    <h2>PROPOSAL ACARA</h2>
                    <h3 style="text-transform: uppercase;">{{ $eventName }}</h3>
                    <p>Jalan Ngagel Jaya Tengah 73–77, Surabaya, 60284</p>
                    <p>Tel. (031) 5021252 Fax. (031) 5041509, (031) 5031818</p>
                </td>
                <td class="header-logo-right">
                    @if(!empty($organization_logo))
                        <img src="{{ storage_path('app/public/' . $organization_logo) }}" style="max-width:100px; max-height:75px;">
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="section-title">VII. PENUTUP</div>
    <p style="text-align: justify; margin-bottom: 30px;">
        Demikianlah proposal kegiatan ini kami buat. Atas perhatiannya, kami mengucapkan terimakasih.
    </p>

    <div class="signature-section">
        @if($signatureCount == 2)
            <div class="text-right" style="margin-bottom: 20px;">
                Surabaya, {{ $signatureDate }}
            </div>
            <table class="no-border" style="width:100%;">
                <tr>
                    <td class="text-center" width="50%">
                        Menyetujui,<br><br><br><br><br><br>
                        <u><strong>Eka Rahayu Setyaningsih, S.Kom., M.Kom.</strong></u><br>
                        Kepala Biro Administrasi Kemahasiswaan
                    </td>

                    <td class="text-center" width="50%">
                        Hormat Kami,<br><br><br><br><br><br>
                        <u><strong>{{ $resolvedChairman }}</strong></u><br>
                        Ketua {{ $eventName }}
                    </td>
                </tr>
            </table>

        @elseif($signatureCount == 4)
            <table class="no-border" style="width:100%;">
                <tr>
                    <td class="text-center" width="50%" style="vertical-align: top;">
                        Mengetahui,
                    </td>
                    <td class="text-center" width="50%" style="vertical-align: top;">
                        Surabaya, {{ $signatureDate }}<br>
                        Hormat Kami,
                    </td>
                </tr>
                <tr>
                    <td class="text-center" style="padding-top:70px; vertical-align: bottom;">
                        <u><strong>Ong. Hansel Santoso, S.Si., M.Kom.</strong></u><br>
                        GDG Manager
                    </td>
                    <td class="text-center" style="padding-top:70px; vertical-align: bottom;">
                        <u><strong>{{ $resolvedChairman }}</strong></u><br>
                        <u>Ketua {{ $event_name ?? $event->nama_event }}</u>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="height:50px;"></td>
                </tr>
                <tr>
                    <td class="text-center" style="vertical-align: top;">
                        Menyetujui,
                    </td>
                    <td class="text-center" style="vertical-align: top;">
                        Menyetujui,
                    </td>
                </tr>
                <tr>
                    <td class="text-center" style="padding-top:70px; vertical-align: bottom;">
                        <u><strong>Ir. Edwin Pramana, M.AppSc., Ph.D.</strong></u><br>
                        Dekan Fakultas Sains
                    </td>
                    <td class="text-center" style="padding-top:70px; vertical-align: bottom;">
                        <u><strong>Dr. Ir. F.X. Ferdinandus, M.T.</strong></u><br>
                        Wakil Rektor III
                    </td>
                </tr>
            </table>
        @endif
    </div>
</div>

</body>
</html>