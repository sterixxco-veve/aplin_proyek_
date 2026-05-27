<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>LPJ Acara</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; }
        .header-lpj { text-align: center; font-weight: bold; margin-bottom: 30px; }
        .section { margin-top: 20px; }
        .table-rundown { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table-rundown th, .table-rundown td { border: 1px solid #000; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <div class="header-lpj">
        LAPORAN PERTANGGUNG JAWABAN ACARA<br>
        {{ $event_title }} [cite: 250]
    </div>

    <div class="section">
        <strong>I. WAKTU DAN TEMPAT REALISASI</strong>
        <p>Hari/Tanggal: {{ $realized_date }}<br>
        Waktu: {{ $realized_time }}<br>
        Tempat: {{ $realized_venue }} [cite: 265]</p>
    </div>

    <div class="section">
        <strong>II. PELAKSANAAN ACARA</strong>
        <p>{{ $execution_summary }} [cite: 267, 384]</p>
    </div>

    <div class="section">
        <strong>III. TARGET PESERTA</strong>
        <ul>
            <li>Internal: {{ $internal_count }} Orang</li>
            <li>Umum: {{ $public_count }} Orang [cite: 279]</li>
        </ul>
    </div>

    <div class="section">
        <strong>IV. RUNDOWN KEGIATAN</strong>
        <table class="table-rundown">
            <thead>
                <tr><th>Waktu</th><th>Durasi</th><th>Kegiatan</th></tr>
            </thead>
            <tbody>
                {{ $rundown_rows }} [cite: 275]
            </tbody>
        </table>
    </div>
</body>
</html>