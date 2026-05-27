<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>MOU</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            line-height: 1.8;
            color: #222;
        }

        .section {
            margin-top: 35px;
        }

        .signature {
            margin-top: 80px;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>

    <h1 class="center">
        MEMORANDUM OF UNDERSTANDING
    </h1>

    <div class="section">

        <strong>PIHAK PERTAMA</strong><br>
        {{ $first_party }}<br>
        {{ $first_party_role }}

        <br><br>

        <strong>PIHAK KEDUA</strong><br>
        {{ $second_party }}<br>
        {{ $second_party_role }}

    </div>

    <div class="section">
        <h3>Bentuk Kerja Sama</h3>

        <p>
            {{ $cooperation }}
        </p>
    </div>

    <div class="section">
        <strong>Periode Kerja Sama:</strong><br>

        {{ $start_date }}
        -
        {{ $end_date }}
    </div>

    <div class="signature">

        <div>
            PIHAK PERTAMA
            <br><br><br><br>

            {{ $first_party }}
        </div>

        <div>
            PIHAK KEDUA
            <br><br><br><br>

            {{ $second_party }}
        </div>

    </div>

</body>
</html>