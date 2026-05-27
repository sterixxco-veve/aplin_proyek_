<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Proposal Kegiatan</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; line-height: 1.5; }
        .cover { text-align: center; margin-bottom: 50px; }
        .title { font-size: 18pt; font-weight: bold; margin: 20px 0; }
        .section-title { font-weight: bold; text-decoration: underline; margin-top: 25px; }
        .content { text-align: justify; }
    </style>
</head>
<body>
    <div class="cover">
        <div class="title">PROPOSAL KEGIATAN</div>
        <div class="title">{{ $event_title }}</div>
        <p>Google Developer Groups On Campus Institut STTS [cite: 87]</p>
        <p>{{ $academic_year }}</p>
    </div>

    <div class="section-title">I. PENDAHULUAN</div>
    <div class="content">
        <strong>a. Latar Belakang</strong>
        <p>{{ $background_text }} [cite: 89, 90]</p>
        <strong>b. Tujuan</strong>
        <p>{{ $objectives }} [cite: 95, 96]</p>
    </div>

    <div class="section-title">II. WAKTU DAN TEMPAT PELAKSANAAN</div>
    <div class="content">
        <ul>
            <li>Hari/Tanggal: {{ $event_date }}</li>
            <li>Waktu: {{ $event_time }}</li>
            <li>Tempat: {{ $venue }} [cite: 100]</li>
        </ul>
    </div>

    <div class="section-title">III. DESKRIPSI KEGIATAN</div>
    <div class="content">
        <p>{{ $description_text }} [cite: 102, 103]</p>
    </div>
</body>
</html>