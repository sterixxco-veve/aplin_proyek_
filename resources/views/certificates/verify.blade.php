<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Verification</title>
    <!-- Bootstrap 5.3.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts (Plus Jakarta Sans) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body, html {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #ffffff;
            height: 100vh;
            margin: 0;
            padding: 0;
            overflow: hidden; /* Mencegah scrollbar muncul di layar desktop */
        }

        /* Layout Full Screen Responsif */
        .fullscreen-wrapper {
            height: 100vh;
            width: 100vw;
        }

        .img-column {
            background-color: #f8f9fa; /* Latar belakang estetik cerah untuk area sertifikat */
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .info-column {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 2.5rem;
            border-left: 1px solid #e9ecef;
            background-color: #ffffff;
        }

        /* Mengoptimalkan tampilan gambar sertifikat */
        .certificate-container {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .certificate-img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
        }

        /* Komponen Identitas & Badge */
        .verification-badge {
            background-color: #e8f5e9;
            color: #2e7d32;
            font-weight: 600;
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: 1px solid #c8e6c9;
            font-size: 0.85rem;
        }

        .info-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #8c949c;
            font-weight: 700;
        }

        .info-value {
            color: #1a1d20;
            font-weight: 600;
            font-size: 1rem;
        }

        /* Tombol Download Premium */
        .btn-download {
            background: #198754;
            border: none;
            padding: 0.85rem 1.5rem;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.2s ease;
        }

        .btn-download:hover {
            background: #146c43;
            box-shadow: 0 5px 15px rgba(25, 135, 84, 0.3);
        }

        /* Mengembalikan kemampuan scroll khusus hanya saat diakses via Handphone */
        @media (max-width: 767.98px) {
            body, html {
                overflow: auto;
                height: auto;
            }
            .fullscreen-wrapper, .img-column, .info-column {
                height: auto;
            }
            .img-column {
                padding: 1rem;
                height: 300px;
            }
            .info-column {
                padding: 1.5rem;
                border-left: none;
                border-top: 1px solid #e9ecef;
            }
        }
    </style>
</head>

<body>

<div class="container-fluid p-0 fullscreen-wrapper">
    <div class="row g-0">
        
        <!-- SISI KIRI: Preview Gambar Sertifikat Komplit (Besar & Center Luas) -->
        <div class="col-md-8 img-column">
            <div class="certificate-container">
                <img src="{{ asset('storage/' . $certificate->file_url) }}" 
                     alt="Certificate Preview" 
                     class="certificate-img border bg-white">
            </div>
        </div>

        <!-- SISI KANAN: Panel Informasi Detail -->
        <div class="col-md-4 info-column">
            
            <!-- Area Atas & Tengah (Data) -->
            <div>
                <!-- Status Keaslian -->
                <div class="mb-4 text-start">
                    <div class="verification-badge mb-2">
                        <i class="bi bi-patch-check-fill fs-6"></i>
                        <span>✓ Certificate Verified</span>
                    </div>
                    <p class="text-muted small mb-0">Sertifikat terdaftar resmi di dalam sistem</p>
                </div>

                <!-- Nama Penerima Utama -->
                <div class="mb-4">
                    <span class="info-label d-block mb-1">Nama Penerima</span>
                    <h2 class="fw-bold text-dark mb-0" style="letter-spacing: -0.5px; font-size: 1.85rem;">
                        {{ $certificate->nama_penerima }}
                    </h2>
                </div>

                <!-- Susunan Kotak Informasi -->
                <div class="d-flex flex-column gap-3">
                    <div class="p-3 bg-light rounded-3">
                        <span class="info-label d-block mb-1">Email</span>
                        <span class="info-value text-break">{{ $certificate->email_penerima }}</span>
                    </div>
                    
                    <div class="p-3 bg-light rounded-3">
                        <span class="info-label d-block mb-1">Nama Event</span>
                        <span class="info-value">{{ $certificate->event->nama_event ?? '-' }}</span>
                    </div>

                    <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center">
                        <div class="overflow-hidden me-2">
                            <span class="info-label d-block mb-1">Token / ID</span>
                            <span class="info-value text-secondary font-monospace d-block text-truncate" style="font-size: 0.9rem;">
                                {{ $certificate->qr_token }}
                            </span>
                        </div>
                        <i class="bi bi-qr-code text-muted fs-4 flex-shrink-0"></i>
                    </div>
                </div>
            </div>

            <!-- Area Bawah (Aksi Utama) -->
            <div class="mt-4">
                <div class="d-grid mb-3">
                    <a href="{{ asset('storage/' . $certificate->file_url) }}" 
                       download 
                       class="btn btn-primary btn-download text-white shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-cloud-arrow-down-fill fs-5"></i>
                        Download Certificate
                    </a>
                </div>
                <div class="text-center text-muted" style="font-size: 0.75rem; letter-spacing: 0.3px;">
                    <i class="bi bi-shield-lock-fill text-success me-1"></i> Authentic & Secure Verification Page
                </div>
            </div>

        </div>

    </div>
</div>

</body>
</html>