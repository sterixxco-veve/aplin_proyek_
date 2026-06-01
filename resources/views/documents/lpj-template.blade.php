<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Pertanggungjawaban Acara</title>
    <style>
        @page { 
            margin: 24px; 
        }
        body {
            font-family: "Times New Roman", serif;
            font-size: 11pt;
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
    // --- STREAMING_CHUNK: Inisialisasi variabel dan fallback data LPJ dari database ---
    $eventName = $event_name ?? $event->nama_event ?? 'STOP COPY-PASTING, START ENGINEERING: THE "CORRECT" WAY TO USE AI TOOLS';
    $organizationName = $organization_name ?? $organization?->nama_org ?? 'Google Developer Groups On Campus institut STTS';
    
    $realizationDate = $realization_date ?? $realized_date ?? ($event->tgl_mulai 
        ? \Carbon\Carbon::parse($event->tgl_mulai)->format('Y-m-d') 
        : '2025-12-05'); 

    $startTime = $start_time ?? '15:30';
    $endTime = $end_time ?? '17:00';
    $venueName = $realized_venue ?? $venue ?? 'Auditorium ISTTS';
    $academicYear = $academic_year ?? 'GOOGLE DEVELOPER STUDENT CLUB ISTTS GASAL 2025/2026';
    
    // Background, Objectives & Implementation Fallbacks
    $backgroundText = $background_text ?? "Perkembangan teknologi Artificial Intelligence (AI) kini telah mengubah cara Software Development dan Software Testing dilakukan. Berbagai alat berbasis Al mampu menghasilkan kode dan skenario pengujian dengan sangat cepat. Namun, kemudahan ini sering menyebabkan para profesional hanya melakukan copy-paste tanpa melalui proses rekayasa perangkat lunak yang benar, seperti analisis, verifikasi, dan validasi.\n\nPenggunaan Al secara pasif seperti ini dapat menimbulkan risiko besar, mulai dari celah keamanan, kegagalan integrasi sistem, hingga menurunnya kualitas arsitektur perangkat lunak. Karena itu, industri perlu segera mengubah peran Developer dan Tester agar mampu memanfaatkan Al secara strategis bukan sekadar sebagai alat pembuat kode, tetapi sebagai Co-Engineer yang mendukung proses rekayasa perangkat lunak secara profesional.\n\nSeminar ini diadakan untuk menjawab kebutuhan tersebut, memastikan bahwa inovasi Al dimanfaatkan dengan cara yang bertanggung jawab, profesional, dan sesuai dengan standar industri modern.";
    $objectivesText = $objectives ?? "1. Meningkatkan pemahaman peserta mengenai peran Al dalam Software Development dan Software Testing secara profesional.\n2. Mendorong peserta untuk mengembangkan pola pikir kritis dalam memanfaatkan Al, tidak hanya menerima hasil secara langsung tetapi juga mampu mengevaluasi kualitasnya.\n3. Membekali peserta dengan kemampuan melakukan analisis, verifikasi, dan validasi meskipun menggunakan Al sebagai assistive tool.\n4. Menyediakan forum diskusi dan berbagi pengalaman antarpelaku industri, akademisi, dan praktisi teknologi untuk meningkatkan kualitas praktik pengembangan perangkat lunak di era AI.";
    
    $implementationText = $implementation ?? "Kegiatan seminar \"Stop Copy-Pasting, Start Engineering: The Correct Way to Use AI Tools\" telah terlaksana pada hari Jumat, 5 Desember 2025, bertempat di Auditorium ISTTS. Rangkaian acara dimulai dengan persiapan panitia dan registrasi peserta pada pukul 15.00 WIB. Acara dibuka secara resmi oleh pembawa acara (MC) pada pukul 15.30 WIB, yang kemudian dilanjutkan dengan sambutan pembuka dari Bu Esther. Memasuki agenda utama, materi disampaikan oleh Pak Alvin mengenai penggunaan perangkat kecerdasan buatan (Al tools) dengan pendekatan teknis yang tepat. Selanjutnya, kegiatan dilanjutkan dengan sesi interaktif berupa kuis Kahoot dan sesi tanya jawab yang dipandu oleh MC. Agenda berikutnya adalah pemberian apresiasi kepada pemenang kuis serta penyerahan penghargaan bagi peserta yang telah menyelesaikan sertifikasi Google Cloud. Sebelum acara berakhir, dilakukan sesi dokumentasi foto bersama antara narasumber dan peserta. Salah satu agenda penting dalam kegiatan ini adalah prosesi penandatanganan Nota Kesepahaman (MOU) antara pihak ISTTS dan NBS. Seluruh rangkaian acara ditutup pada pukul 17.25 WIB dan diakhiri dengan rapat evaluasi panitia.";

    // Evaluasi, Kritik, Saran
    $evaluationText = $evaluation ?? "Secara garis besar, acara \"Stop Copy-Pasting, Start Engineering\" berjalan dengan lancar dan materi yang dibawakan sebenarnya sangat diminati. Hal ini terbukti dari banyaknya pertanyaan yang masuk lewat aplikasi Slido. Namun, tantangan terbesar ada pada kedisiplinan peserta dan pengaturan waktu sesi tanya jawab.";
    
    $critiqueItems = $critiques ?? [
        "Pembukaan Acara Mundur: Acara tidak bisa dimulai tepat waktu (on-time). Alasannya, saat jam seharusnya mulai, jumlah peserta yang hadir di dalam ruangan masih sedikit. Panitia terpaksa menunda pembukaan beberapa saat untuk menunggu peserta berkumpul agar kursi terisi.",
        "Peserta Keluar-Masuk Ruangan: Suasana di dalam ruangan agak terganggu karena pergerakan peserta yang tidak tertib. Terpantau banyak mahasiswa yang masuk, duduk sebentar, lalu keluar lagi saat acara sedang berlangsung. Hal ini membuat fokus peserta lain terganggu dan bangku kembali kosong di tengah acara.",
        "Waktu Tanya Jawab Kurang Pas: Ada perbedaan sikap peserta saat sesi tanya jawab.",
        "Saat diminta bertanya langsung menggunakan mik, peserta cenderung diam dan pasif.",
        "Sebaliknya, saat menggunakan Slido (pertanyaan tertulis), antusiasme sangat tinggi dan pertanyaan yang masuk membludak. Sayangnya, karena waktu terbatas, banyak pertanyaan bagus yang tidak sempat dijawab oleh pembicara."
    ];

    $suggestionItems = $suggestions ?? [
        "Penggiringan Peserta Lebih Awal: sebaiknya peserta masuk ke ruangan 15-20 menit sebelum acara.",
        "Perketat Penjagaan Pintu: Perlu ada petugas yang menjaga pintu agar peserta tidak sembarangan keluar-masuk.",
        "Fokuskan Tanya Jawab Lewat Aplikasi: Melihat peserta lebih nyaman bertanya lewat tulisan (online), untuk acara ke depan sebaiknya durasi sesi Slido diperpanjang. Sesi tanya jawab langsung (pakai mik) bisa dikurangi atau ditiadakan saja agar waktu bisa dipakai untuk menjawab lebih banyak pertanyaan dari aplikasi."
    ];

    // Rundowns Fallback (13 items matching the LPJ)
    $rundownItems = $rundowns ?? [];
    if (empty($rundownItems) || count($rundownItems) === 0) {
        $rundownItems = [
            (object)['waktu_mulai' => '14:15:00', 'waktu_selesai' => '15:00:00', 'kegiatan' => 'Persiapan Panitia'],
            (object)['waktu_mulai' => '15:00:00', 'waktu_selesai' => '15:30:00', 'kegiatan' => 'Open Gate'],
            (object)['waktu_mulai' => '15:30:00', 'waktu_selesai' => '15:35:00', 'kegiatan' => 'Opening'],
            (object)['waktu_mulai' => '15:35:00', 'waktu_selesai' => '15:40:00', 'kegiatan' => 'Sambutan dari Bu Esther'],
            (object)['waktu_mulai' => '15:40:00', 'waktu_selesai' => '16:20:00', 'kegiatan' => 'Talk Session'],
            (object)['waktu_mulai' => '16:20:00', 'waktu_selesai' => '16:30:00', 'kegiatan' => 'Kahoot'],
            (object)['waktu_mulai' => '16:30:00', 'waktu_selesai' => '16:45:00', 'kegiatan' => 'QnA'],
            (object)['waktu_mulai' => '16:45:00', 'waktu_selesai' => '16:50:00', 'kegiatan' => 'Awarding game'],
            (object)['waktu_mulai' => '16:50:00', 'waktu_selesai' => '17:15:00', 'kegiatan' => 'Awarding untuk google cloud certified'],
            (object)['waktu_mulai' => '17:15:00', 'waktu_selesai' => '17:20:00', 'kegiatan' => 'Foto bersama'],
            (object)['waktu_mulai' => '17:20:00', 'waktu_selesai' => '17:25:00', 'kegiatan' => 'Penandatanganan MOU'],
            (object)['waktu_mulai' => '17:25:00', 'waktu_selesai' => '17:30:00', 'kegiatan' => 'Closing'],
            (object)['waktu_mulai' => '17:30:00', 'waktu_selesai' => '18:00:00', 'kegiatan' => 'Evaluation Meeting'],
        ];
    }

    // Committees Fallback (26 members)
    $committeeItems = $committees ?? [];
    if (empty($committeeItems) || count($committeeItems) === 0) {
        $committeeItems = [
            (object)['name' => 'Gracia Krisnanda', 'nrp' => '224180592', 'prodi' => 'S1-Sistem Informasi', 'jabatan' => 'Ketua'],
            (object)['name' => 'Bryan Eka Santoso', 'nrp' => '224117122', 'prodi' => 'S1-Informatika', 'jabatan' => 'Wakil Ketua'],
            (object)['name' => 'Anastasia Evelyn', 'nrp' => '224180590', 'prodi' => 'S1-Sistem Informasi', 'jabatan' => 'Bendahara'],
            (object)['name' => 'Ferlinda Tanwio', 'nrp' => '224117127', 'prodi' => 'S1-Informatika', 'jabatan' => 'Sekretaris'],
            (object)['name' => 'Han Wiguna Chandra', 'nrp' => '224117129', 'prodi' => 'S1-Informatika', 'jabatan' => 'Koordinator Penelitian dan Pengembangan'],
            (object)['name' => 'Vincentius Jason Tjendika', 'nrp' => '223117115', 'prodi' => 'S1-Informatika', 'jabatan' => 'Anggota Penelitian dan Pengembangan'],
            (object)['name' => 'Fedrian Tanwid', 'nrp' => '224117126', 'prodi' => 'S1-Informatika', 'jabatan' => 'Anggota Penelitian dan Pengembangan'],
            (object)['name' => 'Alfonsus Edo Sebastian', 'nrp' => '224180589', 'prodi' => 'S1-Sistem Informasi', 'jabatan' => 'Anggota Penelitian dan Pengembangan'],
            (object)['name' => 'Albert Manzo', 'nrp' => '225117147', 'prodi' => 'S1-Informatika', 'jabatan' => 'Anggota Penelitian dan Pengembangan'],
            (object)['name' => 'Darren Edwardo Santoso', 'nrp' => '225117156', 'prodi' => 'S1-Informatika', 'jabatan' => 'Anggota Penelitian dan Pengembangan'],
            (object)['name' => 'Jesselyn Christie Santoso', 'nrp' => '224117132', 'prodi' => 'S1-Informatika', 'jabatan' => 'Koordinator Acara'],
            (object)['name' => 'Matthew Aprilian', 'nrp' => '224117137', 'prodi' => 'S1-Informatika', 'jabatan' => 'Anggota Acara'],
            (object)['name' => 'Shannon Imogen Happy', 'nrp' => '224117139', 'prodi' => 'S1-Informatika', 'jabatan' => 'Anggota Acara'],
            (object)['name' => 'Deni Wijaya', 'nrp' => '225117158', 'prodi' => 'S1-Informatika', 'jabatan' => 'Anggota Acara'],
            (object)['name' => 'Siemen Juan Manuel', 'nrp' => '225117186', 'prodi' => 'S1-Informatika', 'jabatan' => 'Anggota Acara'],
            (object)['name' => 'Steven Liu', 'nrp' => '225117188', 'prodi' => 'S1-Informatika', 'jabatan' => 'Anggota Acara'],
            (object)['name' => 'Valeri Efram Theodore Purbadi', 'nrp' => '225380018', 'prodi' => 'S1-Manajemen Bisnis Digital', 'jabatan' => 'Anggota Acara'],
            (object)['name' => 'Jefferson Adrian Surjadjaja', 'nrp' => '224180593', 'prodi' => 'S1-Sistem Informasi', 'jabatan' => 'Koordinator Publikasi, Dekorasi dan Dokumentasi'],
            (object)['name' => 'Michael Fritz Gerald Joviaal', 'nrp' => '223117097', 'prodi' => 'S1-Informatika', 'jabatan' => 'Anggota Publikasi, Dekorasi dan Dokumentasi'],
            (object)['name' => 'Xylona Marcella', 'nrp' => '223170658', 'prodi' => 'S1-Desain Komunikasi Visual', 'jabatan' => 'Anggota Publikasi, Dekorasi dan Dokumentasi'],
            (object)['name' => 'Joel Imanuel', 'nrp' => '224170680', 'prodi' => 'S1-Desain Komunikasi Visual', 'jabatan' => 'Anggota Publikasi, Dekorasi dan Dokumentasi'],
            (object)['name' => 'Christian Andrew Santoso', 'nrp' => '225117151', 'prodi' => 'S1-Informatika', 'jabatan' => 'Anggota Publikasi, Dekorasi dan Dokumentasi'],
            (object)['name' => 'Hansen Immanuel Gondo Kusuma', 'nrp' => '225117165', 'prodi' => 'S1-Informatika', 'jabatan' => 'Anggota Publikasi, Dekorasi dan Dokumentasi'],
            (object)['name' => 'Irene Olivia Irawan', 'nrp' => '225170701', 'prodi' => 'S1-Desain Komunikasi Visual', 'jabatan' => 'Anggota Publikasi, Dekorasi dan Dokumentasi'],
            (object)['name' => 'Jennifer Graziella Wahyudi', 'nrp' => '225170703', 'prodi' => 'S1-Desain Komunikasi Visual', 'jabatan' => 'Anggota Publikasi, Dekorasi dan Dokumentasi'],
            (object)['name' => 'Jessica Ashley Chandra Suwignyo', 'nrp' => '225170706', 'prodi' => 'S1-Desain Komunikasi Visual', 'jabatan' => 'Anggota Publikasi, Dekorasi dan Dokumentasi'],
        ];
    }

    // Poin Permohonan (Merge Committees and list of participants matching LPJ)
    $pointItems = $points ?? [];
    if (empty($pointItems) || count($pointItems) === 0) {
        $pointItems = [];
        // First add the 26 committees
        foreach ($committeeItems as $comm) {
            $pointItems[] = (object)[
                'name' => $comm->user?->name ?? $comm->name ?? '-',
                'nrp' => $comm->user?->nrp ?? $comm->nrp ?? '-',
                'prodi' => $comm->user?->prodi ?? $comm->prodi ?? '-',
                'jabatan' => strtoupper($comm->user?->jabatan ?? $comm->jabatan ?? '-'),
                'poin' => '100%'
            ];
        }
        // Then add extra participants from PDF page 10-14
        $participantItems = $participants ?? [];
        foreach ($participantItems as $p) {
            $pointItems[] = (object)[
                'name' => $p->name ?? '',
                'nrp' => $p->nrp ?? '',
                'prodi' => $p->prodi ?? '',
                'jabatan' => 'Peserta',
                'poin' => '100%',
            ];
        }
    }

    // Target Realisasi
    $targetSma = $target_sma ?? 1;
    $targetMahasiswa = $target_mahasiswa ?? 50;
    $targetUmum = $target_umum ?? 13;

    // Signature Settings
    $signatureCount = (int) ($signature_count ?? 4);
    $signatureCount = in_array($signatureCount, [2,4], true) ? $signatureCount : 4;

    $signatureDate = $signature_date ?? ($realizationDate 
        ? \Carbon\Carbon::parse($realizationDate)->addDays(14)->locale('id')->translatedFormat('d F Y') 
        : '19 Desember 2025');

    // Budgets Realisasi Fallback
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
        } else {
            // Default budgets dari LPJ PDF
            $pemasukanItems = [
                (object)['sumber_dana' => 'ISTTS', 'qty' => 1, 'nominal' => 1082064, 'total' => 1082064]
            ];
            $pengeluaranItems = [
                (object)['keterangan' => 'Hadiah', 'qty' => 1, 'nominal' => 202064, 'total' => 202064],
                (object)['keterangan' => 'Konsumsi', 'qty' => 70, 'nominal' => 10000, 'total' => 700000],
                (object)['keterangan' => 'Print Banner', 'qty' => 1, 'nominal' => 180000, 'total' => 180000],
            ];
        }
    }

    // Resolving Ketua Acara dynamically
    $resolvedChairmanName = null;
    foreach ($committeeItems as $comm) {
        $jabatan = strtolower($comm->jabatan ?? '');
        if (str_contains($jabatan, 'ketua') && !str_contains($jabatan, 'wakil')) {
            $resolvedChairmanName = $comm->name ?? null;
            break;
        }
    }
    $resolvedChairman = $resolvedChairmanName ?? $chairman_name ?? 'Gracia Krisnanda';

    // Path Logo ISTTS dari folder public
    $logoIsttsPath = public_path('logo_istts.png');
    $hasLogoIstts = file_exists($logoIsttsPath);
@endphp

<!-- ================= COVER PAGE ================= -->
<div class="cover">
    <h1 style="margin-top: 30px; margin-bottom: 20px; font-size: 22pt; font-weight: bold; letter-spacing: 1px;">LAPORAN PERTANGGUNG JAWABAN</h1>
    
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
        <div>2026</div>
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
                    <h2>LAPORAN PERTANGGUNG JAWABAN ACARA</h2>
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
                    <h2>LAPORAN PERTANGGUNG JAWABAN ACARA</h2>
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
            <td>
                {{ $realizationDate ? \Carbon\Carbon::parse($realizationDate)->locale('id')->translatedFormat('l, d F Y') : 'Jumat, 5 Desember 2025' }}
                -
                {{ $realizationDate ? \Carbon\Carbon::parse($realizationDate)->locale('id')->translatedFormat('l, d F Y') : 'Jumat, 5 Desember 2025' }}
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Waktu</td>
            <td>:</td>
            <td>{{ trim(($startTime . ' - ' . $endTime)) ?: '15:30 - 17:00' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Tempat</td>
            <td>:</td>
            <td>{{ $venueName }}</td>
        </tr>
    </table>
</div>


<!-- ================= BAB III: PELAKSANAAN ACARA ================= -->
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
                    <h2>LAPORAN PERTANGGUNG JAWABAN ACARA</h2>
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

    <div class="section-title">III. PELAKSANAAN ACARA</div>
    <p style="text-align: justify; margin-bottom: 25px;">{!! nl2br(e($implementationText)) !!}</p>

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
                    <h2>LAPORAN PERTANGGUNG JAWABAN ACARA</h2>
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
    <table class="no-border" style="width: 100%; margin-top: 15px;">
        <tr>
            <td style="width: 25%; font-weight: bold;">Internal</td>
            <td style="width: 3%;">:</td>
            <td>{{ $targetMahasiswa }} Orang</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">SMA/SMK</td>
            <td>:</td>
            <td>{{ $targetSma }} Orang</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Umum</td>
            <td>:</td>
            <td>{{ $targetUmum }} Orang</td>
        </tr>
    </table>
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
                    <h2>LAPORAN PERTANGGUNG JAWABAN ACARA</h2>
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
                    <td>{{ strtoupper($committee->user?->name ?? $committee->name ?? '-') }}</td>
                    <td class="text-center">{{ $committee->user?->nrp ?? $committee->nrp ?? '-' }}</td>
                    <td>{{ strtoupper($committee->user?->prodi ?? $committee->prodi ?? '-') }}</td>
                    <td>{{ strtoupper($committee->jabatan ?? '-') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


<!-- ================= BAB VI: PERMOHONAN POIN ================= -->
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
                    <h2>LAPORAN PERTANGGUNG JAWABAN ACARA</h2>
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

    <div class="section-title">VI. PERMOHONAN POIN</div>
    <table style="margin-top: 15px;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th style="width: 6%;">NO.</th>
                <th style="width: 28%;">NAMA</th>
                <th style="width: 15%;">NRP</th>
                <th style="width: 26%;">PRODI</th>
                <th style="width: 15%;">JABATAN</th>
                <th style="width: 10%;">POIN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pointItems as $index => $pItem)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ strtoupper($pItem->name) }}</td>
                    <td class="text-center">{{ $pItem->nrp }}</td>
                    <td>{{ strtoupper($pItem->prodi) }}</td>
                    <td>{{ strtoupper($pItem->jabatan) }}</td>
                    <td class="text-center">{{ $pItem->poin }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


<!-- ================= BAB VII: EVALUASI & KRITIK SARAN ================= -->
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
                    <h2>LAPORAN PERTANGGUNG JAWABAN ACARA</h2>
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

    <div class="section-title">VII. EVALUASI & KRITIK SARAN</div>
    
    <strong style="display: block; margin-bottom: 8px; text-transform: uppercase;">a. Evaluasi</strong>
    <p style="text-align: justify; margin-top: 0;">{!! nl2br(e($evaluationText)) !!}</p>

    <strong style="display: block; margin-top: 20px; margin-bottom: 8px; text-transform: uppercase;">b. Kritik</strong>
    <ol style="margin: 0; padding-left: 20px; text-align: justify;">
        @foreach($critiqueItems as $critique)
            <li style="margin-bottom: 8px;">{{ $critique }}</li>
        @endforeach
    </ol>

    <strong style="display: block; margin-top: 20px; margin-bottom: 8px; text-transform: uppercase;">c. Saran</strong>
    <ol style="margin: 0; padding-left: 20px; text-align: justify;">
        @foreach($suggestionItems as $suggestion)
            <li style="margin-bottom: 8px;">{{ $suggestion }}</li>
        @endforeach
    </ol>
</div>


<!-- ================= BAB VIII: ANGGARAN DANA ================= -->
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
                    <h2>LAPORAN PERTANGGUNG JAWABAN ACARA</h2>
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

    <div class="section-title">VIII. ANGGARAN DANA</div>
    
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
                    $nominal = (int)($pem->nominal ?? $pem->nominal_realisasi ?? 0);
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
                    $nominal = (int)($peng->nominal ?? $peng->nominal_realisasi ?? 0);
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


<!-- ================= BAB IX: PENUTUP & SIGNATURE ================= -->
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
                    <h2>LAPORAN PERTANGGUNG JAWABAN ACARA</h2>
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

    <div class="section-title">IX. PENUTUP</div>
    <p style="text-align: justify; margin-bottom: 30px;">
        Demikianlah laporan pertanggung jawaban kegiatan ini kami buat. Atas perhatiannya, kami mengucapkan terimakasih.
    </p>

    <!-- Bagian Lembar Pengesahan / Tanda Tangan -->
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
                        Ketua GDG on Campus 2025/2026
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
                        Ketua GDG on Campus 2025/2026
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="height:40px;"></td>
                </tr>
                <tr>
                    <td class="text-center" style="vertical-align: top;">
                        Menyetujui,<br>
                        <span style="font-size: 9pt; display: block; margin-top: 5px;">Paraf:</span>
                    </td>
                    <td class="text-center" style="vertical-align: top;">
                        Menyetujui,<br>
                        <span style="font-size: 9pt; display: block; margin-top: 5px;">Paraf:</span>
                    </td>
                </tr>
                <tr>
                    <td class="text-center" style="padding-top:60px; vertical-align: bottom;">
                        <u><strong>Ir. Edwin Pramana, M.AppSc., Ph.D.</strong></u><br>
                        Dekan Fakultas Sains
                    </td>
                    <td class="text-center" style="padding-top:60px; vertical-align: bottom;">
                        <u><strong>Dr. Ir. F.X. Ferdinandus, M.T.</strong></u><br>
                        Wakil Rektor III
                    </td>
                </tr>
            </table>
        @endif
    </div>

    <!-- Contact Person di Bagian Bawah Kiri -->
    <div style="margin-top: 40px; font-size: 10pt; line-height: 1.4; page-break-inside: avoid;">
        <strong>Contact Person :</strong><br>
        Ferlinda Tanwio: 081332034650<br>
        Anastasia Evelyn: 087855272006
    </div>
</div>

</body>
</html>