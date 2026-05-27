<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>

    <h2>
        Hello {{ $certificate->nama_penerima }}
    </h2>

    <p>
        Thank you for participating in
        <strong>{{ $event->nama_event }}</strong>.
    </p>

    <p>
        Your certificate is attached to this email.
    </p>

    <br>

    <p>
        Regards,<br>
        Certificate System
    </p>

</body>
</html>