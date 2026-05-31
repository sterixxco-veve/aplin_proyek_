<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invitation Letter</title>
    <style>
        @page { 
            size: a4;
            margin: 1.5cm; /* Margins set to standard formal 3cm */
        }
        body {
            font-family: "Arial", sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #000;
            padding: 0;
            margin: 0;
        }
        
        /* Official Letterhead Styles */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        .kop-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }
        .logo-td {
            width: 90px;
            text-align: left;
        }
        .logo-img {
            max-width: 80px;
            max-height: 80px;
        }
        .brand-td {
            text-align: left;
            padding-left: 15px;
        }
        .org-name {
            font-size: 14pt;
            font-weight: bold;
            color: #222;
            margin: 0;
            line-height: 1.2;
        }
        .org-sub {
            font-size: 11pt;
            color: #555;
            margin: 2px 0 0 0;
        }
        .org-url {
            font-size: 9pt;
            color: #0066cc;
            text-decoration: none;
            margin-top: 2px;
            display: block;
        }
        .line-divider {
            border: none;
            border-top: 3px solid #000;
            margin-top: 10px;
            margin-bottom: 25px;
        }

        /* Document Metadata (Number, Attachment, Subject) */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .meta-table td {
            border: none;
            padding: 2px 0;
            font-size: 11pt;
            vertical-align: top;
        }
        .meta-label {
            width: 130px;
        }
        .meta-separator {
            width: 15px;
            text-align: center;
        }

        /* Borderless Event Details List to match image_98edbf.png */
        .event-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            page-break-inside: avoid;
        }
        .event-table td {
            border: none;
            padding: 3px 0;
            vertical-align: top;
            font-size: 11pt;
        }
        .event-table td.label-column {
            width: 180px;
            font-weight: bold;
        }
        .event-table td.separator-column {
            width: 15px;
            text-align: center;
        }

        /* Signature Block (Standard Bottom-Left Alignment) */
        .signature-area {
            margin-top: 50px;
            width: 100%;
            page-break-inside: avoid;
            text-align: left;
        }
        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }
        .signature-table td {
            border: none;
            width: 50%;
            vertical-align: top;
        }
    </style>
</head>
<body>
@php
    // Fallback configurations matching GDG Talk Alvin
    $eventName = $event_name ?? $event->nama_event ?? 'Flutter Fusion Conference';
    $eventDate = $event_date ?? ($event->tgl_mulai ? \Carbon\Carbon::parse($event->tgl_mulai)->format('Y-m-d') : '2026-02-21');
    $subject = $subject ?? 'Invitation as Speaker';
    $letterNumber = $letter_number ?? '060/GDG/INV/X/2026';
    $attachment = $attachment ?? '-';
    
    $orgName = $organization_name ?? 'Google Developer Group Surabaya';
    $orgUrl = $organization_url ?? 'https://gdg.community.dev/gdg-surabaya/';
    $orgSub = $orgName !== 'Google Developer Group Surabaya' ? 'Organizing Committee' : 'Google Developer Group Surabaya';

    // Formatting date to English (e.g. January 16, 2026)
    $dateSentRaw = $date_sent ?? date('Y-m-d');
    $dateSentFormatted = \Carbon\Carbon::parse($dateSentRaw)->locale('en')->translatedFormat('F j, Y');

    // Handle uploaded file or public path fallback
    $hasLogo = false;
    $logoPath = null;
    if (!empty($organization_logo)) {
        $logoPath = storage_path('app/public/' . $organization_logo);
        $hasLogo = file_exists($logoPath);
    }

    // Auto-detect chairman from committee
    $detectedChairman = null;
    if (isset($committees) && (is_array($committees) || is_iterable($committees))) {
        foreach ($committees as $committee) {
            $jabatan = strtolower($committee->jabatan ?? '');
            if ($jabatan === 'ketua' || $jabatan === 'ketua acara' || $jabatan === 'chairman') {
                $detectedChairman = $committee->user?->name ?? $committee->name ?? null;
                break;
            }
        }
    }

    // Signatory rules from user request
    $senderName = $sender_name ?? $chairman_name ?? $detectedChairman ?? 'Esther Irawati Setiawan';
    $senderRole = !empty($sender_role) ? $sender_role : ('Lead ' . $orgName);

    // Grab dynamic additional details
    $additionalDescRaw = $additional_description ?? '';
@endphp

<!-- ================= OFFICIAL LETTERHEAD ================= -->
<table class="kop-table">
    <tr>
        <td class="logo-td">
            @if($hasLogo)
                <img src="{{ $logoPath }}" class="logo-img" alt="Organization Logo">
            @else
                <!-- Fallback to public folder logo -->
                <img src="{{ public_path('logo_istts.png') }}" class="logo-img" alt="ISTTS Logo">
            @endif
        </td>
        <td class="brand-td">
            <h1 class="org-name">{{ $orgName }}</h1>
            <p class="org-sub">{{ $orgSub }}</p>
            @if(!empty($orgUrl))
                <a href="{{ $orgUrl }}" class="org-url">{{ $orgUrl }}</a>
            @endif
        </td>
    </tr>
</table>

<hr class="line-divider">

<!-- Date Sent -->
<div style="margin-bottom: 20px;">
    Surabaya, {{ $dateSentFormatted }}
</div>

<!-- Letter Metadata Table -->
<table class="meta-table">
    <tr>
        <td class="meta-label">Number</td>
        <td class="meta-separator">:</td>
        <td>{{ $letterNumber }}</td>
    </tr>
    <tr>
        <td class="meta-label">Attachment</td>
        <td class="meta-separator">:</td>
        <td>{{ $attachment }}</td>
    </tr>
    <tr>
        <td class="meta-label">Subject</td>
        <td class="meta-separator">:</td>
        <td><strong>{{ $subject }}</strong></td>
    </tr>
</table>

<!-- Recipient Info -->
<p style="margin-bottom: 20px; line-height: 1.4;">
    To <strong>{{ $recipient_name ?? 'Mr. Ibnu Sina Wardy' }}</strong><br>
    {{ $recipient_role ?? 'CTO @Carte WMS & Google Developer Expert @Cloud & AI' }}
</p>

<p>Dear Sir,</p>

<p style="text-align: justify; margin-bottom: 15px;">
    Regarding the upcoming <strong>{{ $eventName }}</strong> event organized by <strong>{{ $orgName }}</strong> on:
</p>

<!-- ================= EVENT METADATA LIST (BORDERLESS) ================= -->
<table class="event-table">
    <tr>
        <td class="label-column">Day/Date</td>
        <td class="separator-column">:</td>
        <td>{{ $eventDate ? \Carbon\Carbon::parse($eventDate)->locale('en')->translatedFormat('l, F j, Y') : 'Saturday, February 21, 2026' }}</td>
    </tr>
    <tr>
        <td class="label-column">Time</td>
        <td class="separator-column">:</td>
        <td>{{ $event_time ?? '13:00 - 18:00 WIB' }}</td>
    </tr>
    <tr>
        <td class="label-column">Venue</td>
        <td class="separator-column">:</td>
        <td>{{ $event_location ?? 'Institut Sains dan Teknologi Terpadu Surabaya (ISTTS) Jl. Ngagel Jaya Tengah No. 73-77, Surabaya' }}</td>
    </tr>
    <tr>
        <td class="label-column">Number of Participants</td>
        <td class="separator-column">:</td>
        <td>{{ $participant_total ?? 100 }} participants</td>
    </tr>
</table>

<!-- ================= OFFICIAL INVITATION BODY TEXT ================= -->
<p style="text-align: justify; margin-top: 15px;">
    We would like to invite you to be a speaker at the <strong>{{ $eventName }}</strong> {{ $orgName }} event. 
    @if(!empty($additionalDescRaw))
        {{ $additionalDescRaw }}
    @endif 
    We believe your expertise and contributions will significantly enrich the program and inspire our participants. The organizing committee will also assist to ensure your speaking experience is smooth and impactful.
</p>

<p style="text-align: justify; margin-top: 15px;">
    We sincerely hope you will accept this invitation and join us in making <strong>{{ $eventName }}</strong> a memorable and meaningful event. Thank you for your kind consideration. We look forward to your positive response.
</p>

<!-- Area Tanda Tangan (Left Aligned) -->
<div class="signature-area">
    <table class="signature-table">
        <tr>
            <td style="width: 50%;">
                Sincerely,<br>
                <div style="height: 75px; vertical-align: bottom;">
                    <!-- Blank signature space -->
                </div>
                <strong><u>{{ $senderName }}</u></strong><br>
                {{ $senderRole }}
            </td>
            <td></td>
        </tr>
    </table>
</div>

</body>
</html>