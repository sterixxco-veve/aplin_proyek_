<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Memorandum of Understanding</title>
    <style>
        body { font-family: 'Times New Roman', serif; padding: 50px; line-height: 1.5; color: #000; }
        .header { text-align: center; font-weight: bold; text-decoration: underline; margin-bottom: 20px; }
        .sub-header { text-align: center; margin-bottom: 30px; }
        .section { margin-top: 20px; text-align: justify; }
        .party-details { margin-left: 20px; margin-bottom: 15px; }
        .signature-container { margin-top: 50px; width: 100%; display: table; }
        .signature-box { display: table-cell; width: 50%; text-align: center; }
    </style>
</head>
<body>
    <div class="header">MEMORANDUM OF UNDERSTANDING</div>
    <div class="sub-header">
        Antara IEEE Student Branch Institut Sains dan Teknologi Terpadu Surabaya <br>
        dengan ({{ $pihak_kedua }}) [cite: 9]
    </div>

    <p>Pada hari ini, {{ $hari }}, tanggal {{ $tanggal }} bulan {{ $bulan }} tahun {{ $tahun }} ({{ $versi_terbilang }}), bertempat di {{ $tempat }}, masing-masing pihak yang bertandatangan di bawah ini: [cite: 10, 11]</p>

    <div class="section">
        <strong>PIHAK PERTAMA</strong> [cite: 1]
        <div class="party-details">
            Nama : {{ $nama_pihak_pertama }}<br>
            Jabatan : {{ $jabatan_pihak_pertama }}<br>
            Alamat : {{ $alamat_pihak_pertama }}<br>
            Email : {{ $email_pihak_pertama }}<br>
            No. telp : {{ $notelp_pihak_pertama }} [cite: 1]
        </div>
        Bertindak selaku {{ $peran_pihak_pertama }}[cite: 1].
    </div>

    <div class="section">
        <strong>PIHAK KEDUA</strong>
        <div class="party-details">
            Nama : {{ $nama_pihak_kedua }}<br>
            Jabatan : {{ $jabatan_pihak_kedua }}<br>
            Alamat : {{ $alamat_pihak_kedua }}<br>
            Email : {{ $email_pihak_kedua }}<br>
            No. telp : {{ $notelp_pihak_kedua }}
        </div>
        Bertindak selaku {{ $peran_pihak_kedua }}[cite: 1].
    </div>

    <div class="section">
        <p>Kedua belah pihak telah sepakat untuk menetapkan kewajiban dan tanggung jawab dalam <strong>{{ $kerja_sama }}</strong> yang dilaksanakan pada {{ $waktu }} di {{ $tempat }}[cite: 1].</p>
        <ol>
            <li><strong>Tujuan Kerja Sama:</strong> {{ $tujuan }}</li>
            <li><strong>Lingkup Kerja Sama:</strong> {{ $lingkup }}</li>
            <li><strong>Waktu dan Durasi:</strong> Beraku mulai {{ $mulai }} sampai {{ $selesai }}[cite: 1].</li>
        </ol>
    </div>

    <div class="signature-container">
        <div class="signature-box">
            PIHAK PERTAMA<br><br><br><br>
            ({{ $nama_pihak_pertama }})
        </div>
        <div class="signature-box">
            PIHAK KEDUA<br><br><br><br>
            ({{ $nama_pihak_kedua }})
        </div>
    </div>
</body>
</html>