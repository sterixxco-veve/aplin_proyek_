<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invitation Letter - GDG Surabaya</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; line-height: 1.6; }
        .header { border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .date { text-align: left; margin-bottom: 20px; }
        .info-table td { padding-right: 20px; vertical-align: top; }
        .event-details { background: #f9f9f9; padding: 15px; margin: 20px 0; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="header">
        <strong>GDG Surabaya</strong><br>
        Google Developer Group Surabaya<br>
        https://gdg.community.dev/gdg-surabaya/ 
    </div>

    <div class="date">Surabaya, {{ $date_sent }}</div>

    <table class="info-table">
        <tr><td>Number</td><td>: {{ $letter_number }}</td></tr>
        <tr><td>Subject</td><td>: {{ $subject }}</td></tr>
    </table>

    <div style="margin-top: 20px;">
        To {{ $recipient_name }}<br>
        {{ $recipient_role }} 
    </div>

    <p>Dear Sir/Madam,</p>
    <p>Regarding the upcoming <strong>{{ $event_name }}</strong> event organized by Google Developer Group (GDG) Surabaya on: </p>

    <div class="event-details">
        <table class="info-table">
            <tr><td>Day/Date</td><td>: {{ $event_day_date }}</td></tr>
            <tr><td>Time</td><td>: {{ $event_time }}</td></tr>
            <tr><td>Venue</td><td>: {{ $venue }}</td></tr>
            <tr><td>Participants</td><td>: {{ $participant_total }} participants</td></tr>
        </table>
    </div>

    <p>{{ $invitation_body_text }}</p>

    <p>Sincerely,<br><br><br>
    Organizing Committee</p>
</body>
</html>