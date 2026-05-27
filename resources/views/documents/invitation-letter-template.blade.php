<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invitation Letter</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 50px;
            line-height: 1.8;
            color: #222;
        }

        .right {
            text-align: right;
        }

        .section {
            margin-top: 30px;
        }
    </style>
</head>
<body>

    <div class="right">
        Surabaya, {{ now()->format('d F Y') }}
    </div>

    <div class="section">
        <strong>Number:</strong> {{ $letter_number }}
    </div>

    <div class="section">
        To:<br>

        <strong>{{ $recipient_name }}</strong><br>
        {{ $recipient_role }}
    </div>

    <div class="section">
        Dear Sir/Madam,
    </div>

    <div class="section">
        We would like to invite you to participate in:

        <br><br>

        <strong>{{ $event_name }}</strong>

        <br><br>

        Date: {{ $event_date }}<br>
        Venue: {{ $event_location }}<br>
        Participants: {{ $participant_total }}
    </div>

    <div class="section">
        We hope you can attend this event.
    </div>

    <div class="section">
        Regards,
    </div>

</body>
</html>