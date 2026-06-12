<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Proposal Kegiatan</title>
    <style>
        @page {
            /* PERUBAHAN 1: Margin top 140px memberikan ruang yang cukup bagi header (100px) di setiap halaman */
            margin: 140px 40px 40px 40px;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
        }

        .cover {
            text-align: center;
            padding-top: 50px;
            height: 100%;
            /* PERUBAHAN 2: Menarik cover ke atas mengabaikan margin @page agar layout cover tidak melorot kebawah */
            margin-top: -100px;
        }

        .main-document-content {
            page-break-before: always;
            /* PERUBAHAN 3: Menghapus margin-top lama karena sudah dicover oleh rule @page */
            margin-top: 0px;
        }

        .divHeader {
            position: fixed;
            /* PERUBAHAN 4: Menarik header ke posisi paling atas kertas melampaui batas margin dokumen */
            top: -110px;
            left: 0px;
            right: 0px;
            height: 100px;
            border-bottom: 4px solid #000;
            padding-bottom: 5px;
        }

        .bab-page {
            page-break-before: always;
        }

        .section-title {
            font-weight: bold;
            font-size: 14pt;
            margin: 10px 0 15px;
            text-transform: uppercase;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11pt;
        }

        table.data-table th,
        table.data-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: middle;
        }

        table.data-table th {
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

        .no-border,
        .no-border tr,
        .no-border td {
            border: none !important;
            padding: 4px;
        }

        .header-table {
            width: 100%;
            border: none;
        }

        .header-table td {
            border: none !important;
            vertical-align: middle;
        }

        .header-logo-left,
        .header-logo-right {
            width: 90px;
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
            font-size: 10pt;
        }

        .signature-section {
            page-break-inside: avoid;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    @php
        $eventName = $event_name ?? ($event->nama_event ?? 'Proposal Kegiatan');
        $organizationName = $organization_name ?? ($organization?->nama_org ?? 'Google Developer Groups On Campus');

        $eventDate =
            $event_date ??
            ($event->tgl_mulai ? \Carbon\Carbon::parse($event->tgl_mulai)->format('Y-m-d') : date('Y-m-d'));
        $startTime = $start_time ?? '15:30';
        $endTime = $end_time ?? '18:00';
        $venueName = $venue ?? 'Auditorium ISTTS';
        $academicYear = $academic_year ?? 'GOOGLE DEVELOPER GROUP ON CAMPUS 2025/2026';

        $backgroundText = $latar_belakang ?? ($event?->latar_belakang ?? 'Belum ada data.');
        $objectivesText = $tujuan ?? ($event?->tujuan ?? 'Belum ada data.');
        $descriptionText = $deskripsi_kegiatan ?? 'Belum ada data.';

        $rundownItems = $rundowns ?? [];
        $targetSma = $target_sma ?? 0;
        $targetMahasiswa = $target_mahasiswa ?? 0;
        $targetUmum = $target_umum ?? 0;

        $committeeItems = $committees ?? [];
        $resolvedChairman = $chairman_name ?? 'Gracia Krisnanda';
        foreach ($committeeItems as $committee) {
            $jabatan = strtolower($committee->jabatan ?? '');
            if (str_contains($jabatan, 'ketua') && !str_contains($jabatan, 'wakil')) {
                $resolvedChairman = $committee->user?->name ?? $committee->name;
                break;
            }
        }

        $signatureCount = (int) ($signature_count ?? 4);
        $signatureDate = $signature_date ?? now()->locale('id')->translatedFormat('d F Y');
        $logoIsttsPath = public_path('logo_istts.png');
        $hasLogoIstts = file_exists($logoIsttsPath);
    @endphp

    <div class="cover">
        <h1 style="margin-top: 50px; margin-bottom: 20px; font-size: 24pt; font-weight: bold; letter-spacing: 1px;">
            PROPOSAL KEGIATAN</h1>
        <div style="margin: 40px 0; min-height: 80px;">
            <h2 style="margin: 0; font-size: 18pt; font-weight: bold; line-height: 1.4;">{{ strtoupper($eventName) }}
            </h2>
        </div>
        <div style="margin: 80px auto 40px; text-align: center;">
            @if (!empty($organization_logo))
                <img src="{{ public_path('storage/' . $organization_logo) }}"
                    style="max-width: 500px; max-height: 130px; margin-bottom: 20px;">
            @endif
            <div style="margin-top: 10px;">
                @if ($hasLogoIstts)
                    <img src="{{ $logoIsttsPath }}" style="width: 200px; height: auto;">
                @endif
            </div>
        </div>
        <div
            style="margin-top: 120px; font-size: 11pt; line-height: 1.6; font-weight: bold; text-transform: uppercase;">
            <div>{{ $academicYear }}</div>
            <div>INSTITUT SAINS DAN TEKNOLOGI TERPADU SURABAYA</div>
            <div>SURABAYA</div>
        </div>
    </div>

    <div class="main-document-content">
        <div class="divHeader">
            <table class="header-table">
                <tr>
                    <td class="header-logo-left">
                        @if ($hasLogoIstts)
                            <img src="{{ $logoIsttsPath }}" style="width:65px;">
                        @endif
                    </td>
                    <td class="header-title">
                        <h2>PROPOSAL KEGIATAN</h2>
                        <h3 style="text-transform: uppercase;">{{ $eventName }}</h3>
                        <p>Jalan Ngagel Jaya Tengah 73–77, Surabaya, 60284</p>
                    </td>
                    <td class="header-logo-right">
                        @if (!empty($organization_logo))
                            <img src="{{ public_path('storage/' . $organization_logo) }}"
                                style="max-width:85px; max-height:65px;">
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <div>
            <div class="section-title">I. PENDAHULUAN</div>
            <strong style="display: block; margin-bottom: 8px; text-transform: uppercase;">a. Latar Belakang</strong>
            <p style="text-align: justify; text-indent: 35px; margin-top: 0;">{!! nl2br(e($backgroundText)) !!}</p>
            <strong style="display: block; margin-top: 20px; margin-bottom: 8px; text-transform: uppercase;">b.
                Tujuan</strong>
            <p style="text-align: justify; margin-top: 0;">{!! nl2br(e($objectivesText)) !!}</p>
        </div>
        <div class="divHeader">
            <table class="header-table">
                <tr>
                    <td class="header-logo-left">
                        @if ($hasLogoIstts)
                            <img src="{{ $logoIsttsPath }}" style="width:65px;">
                        @endif
                    </td>
                    <td class="header-title">
                        <h2>PROPOSAL KEGIATAN</h2>
                        <h3 style="text-transform: uppercase;">{{ $eventName }}</h3>
                        <p>Jalan Ngagel Jaya Tengah 73–77, Surabaya, 60284</p>
                    </td>
                    <td class="header-logo-right">
                        @if (!empty($organization_logo))
                            <img src="{{ public_path('storage/' . $organization_logo) }}"
                                style="max-width:85px; max-height:65px;">
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        <div class="bab-page">
            <div class="section-title">II. WAKTU DAN TEMPAT PELAKSANAAN</div>
            <table class="no-border" style="width: 100%; margin-top: 15px;">
                <tr>
                    <td style="width: 25%; font-weight: bold;">Hari / Tanggal</td>
                    <td style="width: 3%;">:</td>
                    <td>{{ \Carbon\Carbon::parse($eventDate)->locale('id')->translatedFormat('l, d F Y') }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Waktu</td>
                    <td>:</td>
                    <td>{{ $startTime }} - {{ $endTime }} WIB</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Tempat</td>
                    <td>:</td>
                    <td>{{ $venueName }}</td>
                </tr>
            </table>
        </div>
        <div class="divHeader">
            <table class="header-table">
                <tr>
                    <td class="header-logo-left">
                        @if ($hasLogoIstts)
                            <img src="{{ $logoIsttsPath }}" style="width:65px;">
                        @endif
                    </td>
                    <td class="header-title">
                        <h2>PROPOSAL KEGIATAN</h2>
                        <h3 style="text-transform: uppercase;">{{ $eventName }}</h3>
                        <p>Jalan Ngagel Jaya Tengah 73–77, Surabaya, 60284</p>
                    </td>
                    <td class="header-logo-right">
                        @if (!empty($organization_logo))
                            <img src="{{ public_path('storage/' . $organization_logo) }}"
                                style="max-width:85px; max-height:65px;">
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        <div class="bab-page">
            <div class="section-title">III. DESKRIPSI KEGIATAN</div>
            <p style="text-align: justify; margin-bottom: 25px;">{!! nl2br(e($descriptionText)) !!}</p>
            <h4 style="margin-bottom: 10px; font-weight: bold; text-transform: uppercase; font-size: 11pt;">Rundown
                Kegiatan</h4>
            @php $groupedRundowns = collect($rundownItems)->groupBy(fn($item) => $item->day_number ?? 1)->sortKeys(); @endphp
            @foreach ($groupedRundowns as $day => $items)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th colspan="4" style="text-transform: uppercase;">HARI {{ $day }}</th>
                        </tr>
                        <tr>
                            <th>NO.</th>
                            <th>WAKTU</th>
                            <th>DURASI</th>
                            <th>KEGIATAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="text-center">{{ substr($item->waktu_mulai, 0, 5) }} -
                                    {{ substr($item->waktu_selesai, 0, 5) }}</td>
                                <td class="text-center">
                                    @if (!empty($item->waktu_mulai) && !empty($item->waktu_selesai))
                                        @php
                                            $diffSeconds =
                                                strtotime($item->waktu_selesai) - strtotime($item->waktu_mulai);
                                            echo sprintf(
                                                '%02d:%02d:00',
                                                floor(($m = floor($diffSeconds / 60)) / 60),
                                                $m % 60,
                                            );
                                        @endphp
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $item->kegiatan ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        </div>
        <div class="divHeader">
            <table class="header-table">
                <tr>
                    <td class="header-logo-left">
                        @if ($hasLogoIstts)
                            <img src="{{ $logoIsttsPath }}" style="width:65px;">
                        @endif
                    </td>
                    <td class="header-title">
                        <h2>PROPOSAL KEGIATAN</h2>
                        <h3 style="text-transform: uppercase;">{{ $eventName }}</h3>
                        <p>Jalan Ngagel Jaya Tengah 73–77, Surabaya, 60284</p>
                    </td>
                    <td class="header-logo-right">
                        @if (!empty($organization_logo))
                            <img src="{{ public_path('storage/' . $organization_logo) }}"
                                style="max-width:85px; max-height:65px;">
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        <div class="bab-page">
            <div class="section-title">IV. TARGET PESERTA</div>
            <table class="data-table" style="margin-top: 15px;">
                <thead>
                    <tr style="background-color: #f2f2f2;">
                        <th>Kategori Peserta</th>
                        <th>Target Jumlah Rencana</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Siswa SMA / SMK</td>
                        <td class="text-center">{{ $targetSma }} Orang</td>
                    </tr>
                    <tr>
                        <td>Mahasiswa Internal Kampus</td>
                        <td class="text-center">{{ $targetMahasiswa }} Orang</td>
                    </tr>
                    <tr>
                        <td>Masyarakat Umum / Praktisi</td>
                        <td class="text-center">{{ $targetUmum }} Orang</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="divHeader">
            <table class="header-table">
                <tr>
                    <td class="header-logo-left">
                        @if ($hasLogoIstts)
                            <img src="{{ $logoIsttsPath }}" style="width:65px;">
                        @endif
                    </td>
                    <td class="header-title">
                        <h2>PROPOSAL KEGIATAN</h2>
                        <h3 style="text-transform: uppercase;">{{ $eventName }}</h3>
                        <p>Jalan Ngagel Jaya Tengah 73–77, Surabaya, 60284</p>
                    </td>
                    <td class="header-logo-right">
                        @if (!empty($organization_logo))
                            <img src="{{ public_path('storage/' . $organization_logo) }}"
                                style="max-width:85px; max-height:65px;">
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        <div class="bab-page">
            <div class="section-title">V. KEPENGURUSAN</div>
            <table class="data-table" style="margin-top: 15px;">
                <thead>
                    <tr>
                        <th>NO.</th>
                        <th>NAMA PANITIA</th>
                        <th>NRP</th>
                        <th>PROGRAM STUDI</th>
                        <th>JABATAN</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($committeeItems as $index => $committee)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ strtoupper($committee->user?->name ?? ($committee->name ?? '-')) }}</td>
                            <td class="text-center">{{ $committee->user?->nrp ?? ($committee->nrp ?? '-') }}</td>
                            <td>{{ strtoupper($committee->user?->prodi ?? ($committee->prodi ?? '-')) }}</td>
                            <td>{{ strtoupper($committee->jabatan ?? '-') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="divHeader">
            <table class="header-table">
                <tr>
                    <td class="header-logo-left">
                        @if ($hasLogoIstts)
                            <img src="{{ $logoIsttsPath }}" style="width:65px;">
                        @endif
                    </td>
                    <td class="header-title">
                        <h2>PROPOSAL KEGIATAN</h2>
                        <h3 style="text-transform: uppercase;">{{ $eventName }}</h3>
                        <p>Jalan Ngagel Jaya Tengah 73–77, Surabaya, 60284</p>
                    </td>
                    <td class="header-logo-right">
                        @if (!empty($organization_logo))
                            <img src="{{ public_path('storage/' . $organization_logo) }}"
                                style="max-width:85px; max-height:65px;">
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        <div class="bab-page">
            <div class="divHeader">
                <table class="header-table">
                    <tr>
                        <td class="header-logo-left">
                            @if ($hasLogoIstts)
                                <img src="{{ $logoIsttsPath }}" style="width:65px;">
                            @endif
                        </td>
                        <td class="header-title">
                            <h2>PROPOSAL KEGIATAN</h2>
                            <h3 style="text-transform: uppercase;">{{ $eventName }}</h3>
                            <p>Jalan Ngagel Jaya Tengah 73–77, Surabaya, 60284</p>
                        </td>
                        <td class="header-logo-right">
                            @if (!empty($organization_logo))
                                <img src="{{ public_path('storage/' . $organization_logo) }}"
                                    style="max-width:85px; max-height:65px;">
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <div class="section-title">VI. ANGGARAN DANA</div>

            @php
                $totalPemasukan = 0;
                $totalPengeluaran = 0;
            @endphp

            {{-- PEMASUKAN --}}
            <table class="data-table">
                <thead>
                    <tr>
                        <th colspan="5"
                            style="font-size:13pt;
                       font-weight:bold;
                       text-transform:uppercase;
                       background:white;">
                            PEMASUKAN
                        </th>
                    </tr>
                    <tr>
                        <th>NO.</th>
                        <th>SUMBER DANA</th>
                        <th>QTY</th>
                        <th>NOMINAL SATUAN</th>
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pemasukanItems as $index => $pem)
                        @php
                            $nominal = (int) ($pem->nominal ?? ($pem->nominal_rencana ?? 0));
                            $qty = (int) ($pem->qty ?? 1);
                            $subTotal = (int) ($pem->total ?? ($pem->sub_total ?? $nominal * $qty));

                            $totalPemasukan += $subTotal;
                        @endphp

                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $pem->sumber_dana ?? ($pem->keterangan ?? '-') }}</td>
                            <td class="text-center">{{ $qty }}</td>
                            <td>Rp {{ number_format($nominal, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($subTotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach

                    <tr style="font-weight:bold;">
                        <td colspan="4" class="text-right">
                            TOTAL PEMASUKAN
                        </td>
                        <td>
                            Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>

            {{-- PENGELUARAN BERDASARKAN KATEGORI --}}
            @php
                $groupedPengeluaran = collect($pengeluaranItems)->groupBy(function ($item) {
                    return $item->category->nama_kategori;
                });
            @endphp



            @foreach ($groupedPengeluaran as $kategori => $items)
                <table class="data-table">

                    <thead>
                        <tr>
                            <th colspan="5"
                                style="
                    background:white;
                    font-size:13pt;
                    font-weight:bold;
                    text-transform:uppercase;
                    border:1px solid black;
                ">
                                {{ $kategori }}
                            </th>
                        </tr>

                        <tr>
                            <th>NO.</th>
                            <th>KETERANGAN KEBUTUHAN</th>
                            <th>QTY</th>
                            <th>NOMINAL SATUAN</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($items as $index => $peng)
                            @php
                                $nominal = (int) ($peng->nominal ?? ($peng->nominal_rencana ?? 0));
                                $qty = (int) ($peng->qty ?? 1);
                                $subTotal = (int) ($peng->total ?? ($peng->sub_total ?? $nominal * $qty));

                                $totalPengeluaran += $subTotal;
                            @endphp

                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $peng->keterangan }}</td>
                                <td class="text-center">{{ $qty }}</td>
                                <td>Rp {{ number_format($nominal, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($subTotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach

                    </tbody>

                </table>
            @endforeach

            <h4 style="margin: 25px 0 8px; font-weight: bold; text-transform: uppercase;">
                REKAPITULASI ANGGARAN
            </h4>

            <table class="data-table" style="width:50%;">
                <tr>
                    <td style="font-weight:bold;">PEMASUKAN</td>
                    <td>Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
                </tr>

                <tr>
                    <td style="font-weight:bold;">PENGELUARAN</td>
                    <td>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                </tr>

                <tr style="font-weight:bold;">
                    <td>SALDO</td>
                    <td>
                        Rp {{ number_format($totalPemasukan - $totalPengeluaran, 0, ',', '.') }}
                    </td>
                </tr>
            </table>
        </div>
        <br>
        <div class="bab-page">
            <div class="divHeader">
                <table class="header-table">
                    <tr>
                        <td class="header-logo-left">
                            @if ($hasLogoIstts)
                                <img src="{{ $logoIsttsPath }}" style="width:65px;">
                            @endif
                        </td>
                        <td class="header-title">
                            <h2>PROPOSAL KEGIATAN</h2>
                            <h3 style="text-transform: uppercase;">{{ $eventName }}</h3>
                            <p>Jalan Ngagel Jaya Tengah 73–77, Surabaya, 60284</p>
                        </td>
                        <td class="header-logo-right">
                            @if (!empty($organization_logo))
                                <img src="{{ public_path('storage/' . $organization_logo) }}"
                                    style="max-width:85px; max-height:65px;">
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <div class="section-title">VII. PENUTUP</div>
            <p style="text-align: justify; margin-bottom: 30px;">Demikianlah proposal kegiatan ini kami buat dengan
                sebenar-benarnya. Atas perhatian dan dukungannya, kami mengucapkan banyak terimakasih.</p>
            <div class="signature-section">
                @if ($signatureCount == 2)
                    <div class="text-right" style="margin-bottom: 20px;">Surabaya, {{ $signatureDate }}</div>
                    <table class="no-border" style="width:100%;">
                        <tr>
                            <td class="text-center" width="50%">Menyetujui,<br><br><br><br><br><u><strong>Eka
                                        Rahayu
                                        Setyaningsih, S.Kom., M.Kom.</strong></u><br>Kepala Biro Administrasi
                                Kemahasiswaan</td>
                            <td class="text-center" width="50%">Hormat
                                Kami,<br><br><br><br><br><u><strong>{{ $resolvedChairman }}</strong></u><br>Ketua
                                Panitia Pelaksana</td>
                        </tr>
                    </table>
                @elseif($signatureCount == 4)
                    <table class="no-border" style="width:100%;">
                        <tr>
                            <td class="text-center" width="50%">Mengetahui,</td>
                            <td class="text-center" width="50%">Surabaya, {{ $signatureDate }}<br>Hormat Kami,
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center" style="padding-top:60px;"><u><strong>Ong. Hansel Santoso, S.Si.,
                                        M.Kom.</strong></u><br>GDG Manager</td>
                            <td class="text-center" style="padding-top:60px;">
                                <u><strong>{{ $resolvedChairman }}</strong></u><br>Ketua Panitia Acara
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="height:40px;"></td>
                        </tr>
                        <tr>
                            <td class="text-center" style="padding-top:70px;"><u><strong>Ir. Edwin Pramana, M.AppSc.,
                                        Ph.D.</strong></u><br>Dekan Fakultas Sains</td>
                            <td class="text-center" style="padding-top:70px;"><u><strong>Dr. Ir. F.X. Ferdinandus,
                                        M.T.</strong></u><br>Wakil Rektor III</td>
                        </tr>
                    </table>
                @endif
            </div>
        </div>
    </div>
</body>

</html>
