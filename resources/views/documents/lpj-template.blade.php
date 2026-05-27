<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>LPJ</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            color: #222;
            line-height: 1.7;
        }

        .section {
            margin-top: 30px;
        }
    </style>
</head>
<body>

    <h1>Laporan Pertanggungjawaban</h1>

    <h2>{{ $event_name }}</h2>

    <div class="section">
        <strong>Tanggal Realisasi:</strong>
        {{ $realization_date }}
    </div>

    <div class="section">
        <strong>Jumlah Peserta:</strong>
        {{ $participant_count }}
    </div>

    <div class="section">
        <h3>Pelaksanaan Acara</h3>
        <p>{{ $implementation }}</p>
    </div>

    <div class="section">
        <h3>Evaluasi</h3>
        <p>{{ $evaluation }}</p>
    </div>

</body>
</html>