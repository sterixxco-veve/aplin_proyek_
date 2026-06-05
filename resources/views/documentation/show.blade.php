@extends('layouts.app')

@section('content')

    <style>
        .doc-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(15, 23, 42, .08);
        }

        .section-title {
            font-weight: 700;
        }

        .upload-box {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 16px;
        }

        .gallery-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-radius: 16px;
        }

        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 16px;
            transition: .25s;
        }

        .gallery-item:hover {
            transform: translateY(-4px);
        }

        .gallery-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, .45);

            display: flex;
            align-items: center;
            justify-content: center;

            opacity: 0;
            transition: .25s;
        }

        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }
    </style>

    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="fw-bold mb-1">
                    Documentation
                </h2>

                <p class="text-muted mb-0">
                    {{ $event->nama_event }}
                </p>

            </div>

            <a href="{{ route('documentation.index', $event) }}" class="btn btn-outline-secondary">
                Back
            </a>

        </div>

        @if(session('success'))

            <div class="alert alert-success">

                {{ session('success') }}

            </div>

        @endif

        <div class="row g-4">

            <!-- LEFT -->

            <div class="col-lg-4">

                <div class="card doc-card">

                    <div class="card-body">

                        <h5 class="section-title mb-3">

                            Upload Documentation

                        </h5>

                        <form action="{{ route('documentation.store', $event->id_event) }}" method="POST"
                            enctype="multipart/form-data">

                            @csrf

                            <div class="upload-box mb-4">

                                <label class="fw-semibold mb-2">

                                    Documentation Photos

                                </label>

                                <div id="photo-container">

                                    <div class="mb-2 photo-input">

                                        <input type="file" name="photos[]" class="form-control photo-upload"
                                            accept="image/*">

                                    </div>

                                </div>

                                <small class="text-muted">

                                    Maximum 5 photos

                                </small>

                            </div>

                            <div class="mb-4">

                                <label class="form-label fw-semibold">

                                    Google Drive Folder

                                </label>

                                <input type="url" name="google_drive_link" class="form-control"
                                    value="{{ $event->documentationLinks->first()?->google_drive_link }}"
                                    placeholder="https://drive.google.com/...">

                            </div>

                            <button type="submit" class="btn btn-primary w-100">

                                Save Documentation

                            </button>

                        </form>

                        @php
                            $driveLink = $event->documentationLinks
                                ->first()?->google_drive_link;
                        @endphp

                        @if($driveLink)

                            <a href="{{ $driveLink }}" target="_blank" class="btn btn-success w-100 mt-3">

                                Open Google Drive Folder

                            </a>

                        @endif

                    </div>

                </div>

            </div>

            <!-- RIGHT -->

            <div class="col-lg-8">

                <div class="card doc-card">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-4">

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

                                @if($photo->file_path)

                                    <div class="col-md-4">

                                        <div class="gallery-item">

                                            <img src="{{ asset('storage/' . $photo->file_path) }}" class="gallery-image"
                                                data-bs-toggle="modal" data-bs-target="#previewModal"
                                                onclick="previewImage(this.src)">

                                            <div class="gallery-overlay">

                                                <button class="btn btn-light btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#previewModal"
                                                    onclick="previewImage('{{ asset('storage/' . $photo->file_path) }}')">

                                                    View

                                                </button>

                                            </div>

                                        </div>

                                    </div>

                                @endif

                            @empty

                                <div class="col-12">

                                    <div class="alert alert-secondary">

                                        No documentation uploaded yet.

                                    </div>

                                </div>

                            @endforelse

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- MODAL -->

    <div class="modal fade" id="previewModal" tabindex="-1">

        <div class="modal-dialog modal-xl">

            <div class="modal-content">

                <div class="modal-body p-0">

                    <img id="previewImage" class="w-100">

                </div>

            </div>

        </div>

    </div>

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const container =
                document.getElementById('photo-container');

            let totalInputs = 1;

            container.addEventListener('change', function (e) {

                if (
                    e.target.classList.contains('photo-upload')
                    &&
                    e.target.files.length > 0
                ) {

                    const inputs =
                        container.querySelectorAll('.photo-input');

                    const lastInput =
                        inputs[inputs.length - 1];

                    if (
                        e.target.closest('.photo-input') === lastInput
                        &&
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

        function previewImage(src) {
            document.getElementById('previewImage').src = src;
        }

    </script>

@endsection