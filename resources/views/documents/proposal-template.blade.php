<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Proposal</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.7;
            color: #222;
            padding: 40px;
        }

        h1, h2, h3 {
            margin-bottom: 10px;
        }

        .center {
            text-align: center;
        }

        .section {
            margin-top: 35px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table td,
        table th {
            border: 1px solid #ddd;
            padding: 10px;
        }
    </style>
</head>
<body>

    <div class="center">
        <h1>{{ $title }}</h1>
        <h3>{{ $event_name }}</h3>
        <p>{{ $event_theme }}</p>
    </div>

    <div class="section">
        <h2>Latar Belakang</h2>
        <p>{{ $background }}</p>
    </div>

    <div class="section">
        <h2>Deskripsi Kegiatan</h2>
        <p>{{ $description }}</p>
    </div>

    <div class="section">
        <h2>Informasi Acara</h2>

        <table>
            <tr>
                <th>Tanggal</th>
                <td>{{ $event_date }}</td>
            </tr>

            <tr>
                <th>Tempat</th>
                <td>{{ $venue }}</td>
            </tr>
        </table>
    </div>

</body>
</html>