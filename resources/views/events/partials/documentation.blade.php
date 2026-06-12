<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <style>
        .doc-header {
            margin-bottom: 24px;
        }

        .doc-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
        }

        .doc-card .card-body {
            padding: 24px;
        }

        .section-title {
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 16px;
        }

        .upload-box {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 16px;
        }

        .photo-upload {
            border-radius: 12px;
        }

        .gallery-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 16px;
        }

        .gallery-item {
            transition: all .25s ease;
        }

        .gallery-item:hover {
            transform: translateY(-4px);
        }

        .save-btn {
            border-radius: 12px;
            font-weight: 600;
            padding: 10px;
        }

        .drive-btn {
            border-radius: 12px;
            font-weight: 600;
        }

        .empty-gallery {
            border-radius: 12px;
        }

        .photo-counter {
            font-size: .9rem;
            color: #6b7280;
        }
    </style>


    <div class="row g-4">

        <!-- LEFT PANEL -->
        <div class="col-lg-4">

            <div class="card doc-card">

                <div class="card-body">

                    <h5 class="section-title">
                        Upload Documentation
                    </h5>

                    <form action="{{ route('documentation.store', $event->id_event) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="upload-box mb-4">

                            <label class="fw-semibold mb-2">
                                Documentation Photos
                            </label>

                            <div id="photo-container" class="mt-2">

                                <div class="mb-2 photo-input">

                                    <input type="file" name="photos[]"
                                        class="form-control photo-upload @error('photos') is-invalid @enderror"
                                        accept="image/*">

                                </div>

                            </div>

                            @error('photos')
                                <div class="text-danger small fw-semibold mt-1">{{ $message }}</div>
                            @enderror

                            <div class="photo-counter mt-2">
                                Maximum 5 photos
                            </div>

                        </div>

                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Google Drive Folder
                            </label>

                            <input type="url" name="google_drive_link"
                                class="form-control @error('google_drive_link') is-invalid @enderror"
                                value="{{ old('google_drive_link', $event->documentationLinks->first()?->google_drive_link) }}"
                                placeholder="https://drive.google.com/...">

                            @error('google_drive_link')
                                <div class="invalid-feedback fw-semibold">{{ $message }}</div>
                            @enderror

                        </div>

                        <button type="submit" class="btn btn-primary w-100 save-btn">
                            Save Documentation
                        </button>

                    </form>

                    @php
                        $driveLink = $event->documentationLinks->first()?->google_drive_link;
                    @endphp

                    @if ($driveLink)
                        <a href="{{ $driveLink }}" target="_blank" class="btn btn-success w-100 drive-btn mt-3">
                            Open Google Drive Folder
                        </a>
                    @endif

                </div>

            </div>

        </div>

        <!-- RIGHT PANEL -->
        <div class="col-lg-8">

            <div class="card doc-card">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <h5 class="section-title mb-0">
                            Gallery
                        </h5>

                        <span class="badge bg-primary">
                            {{ $event->documentationLinks->whereNotNull('file_path')->count() }}
                            Photos
                        </span>

                    </div>

                    <div class="row g-3">

                        @forelse($event->documentationLinks as $photo)
                            @if ($photo->file_path)
                                <div class="col-md-4">

                                    <div class="gallery-item">

                                        <img src="{{ asset('storage/' . $photo->file_path) }}" class="gallery-image">

                                    </div>

                                </div>
                            @endif

                        @empty

                            <div class="col-12">

                                <div class="alert alert-secondary empty-gallery">

                                    No documentation uploaded yet.

                                </div>

                            </div>
                        @endforelse

                    </div>

                </div>

            </div>

        </div>

    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const container =
                document.getElementById('photo-container');

            let totalInputs = 1;

            container.addEventListener('change', function(e) {

                if (
                    e.target.classList.contains('photo-upload') &&
                    e.target.files.length > 0
                ) {

                    const inputs =
                        container.querySelectorAll('.photo-input');

                    const lastInput =
                        inputs[inputs.length - 1];

                    if (
                        e.target.closest('.photo-input') === lastInput &&
                        totalInputs < 5
                    ) {

                        totalInputs++;

                        const div =
                            document.createElement('div');

                        div.classList.add(
                            'mb-2',
                            'photo-input'
                        );

                        div.innerHTML = `
                    <input
                        type="file"
                        name="photos[]"
                        class="form-control photo-upload"
                        accept="image/*"
                    >
                `;

                        container.appendChild(div);
                    }
                }

            });

        });
    </script>
</body>

</html>
