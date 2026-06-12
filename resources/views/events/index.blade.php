@extends('layouts.app')

@section('content')
    <div class="container pb-5">
        {{-- HEADER SECTION --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h2 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px;">Event Management</h2>
                <p class="text-muted small">Manage all your events in one place</p>
            </div>
            <a href="/events/create" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm fw-bold"
                style="background-color: #4f46e5; border: none;">
                <i class="bi bi-plus-lg me-2"></i>Create Event
            </a>
        </div>

        {{-- FILTER PILLS (Sekarang Aktif Berdasarkan Request URL) --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <div class="card-body p-3 d-flex align-items-center gap-2 overflow-x-auto">
                <div class="bg-light p-2 rounded-3 me-2">
                    <i class="bi bi-filter text-muted"></i>
                </div>

                @php
                    // Mengambil parameter status saat ini dari URL, default-nya 'all' jika kosong
                    $currentFilter = request('status', 'all');
                @endphp

                <a href="{{ request()->fullUrlWithQuery(['status' => 'all']) }}"
                    class="btn rounded-pill px-3 py-1 small fw-bold {{ $currentFilter === 'all' ? 'btn-primary' : 'btn-light text-muted border' }}"
                    style="{{ $currentFilter === 'all' ? 'background-color: #4f46e5; border: none;' : '' }}">
                    All Events
                </a>

                <a href="{{ request()->fullUrlWithQuery(['status' => 'Planning']) }}"
                    class="btn rounded-pill px-3 py-1 small fw-bold {{ $currentFilter === 'Planning' ? 'btn-primary' : 'btn-light text-muted border' }}"
                    style="{{ $currentFilter === 'Planning' ? 'background-color: #4f46e5; border: none;' : '' }}">
                    Planning
                </a>

                <a href="{{ request()->fullUrlWithQuery(['status' => 'Ongoing']) }}"
                    class="btn rounded-pill px-3 py-1 small fw-bold {{ $currentFilter === 'Ongoing' ? 'btn-primary' : 'btn-light text-muted border' }}"
                    style="{{ $currentFilter === 'Ongoing' ? 'background-color: #4f46e5; border: none;' : '' }}">
                    Ongoing
                </a>

                <a href="{{ request()->fullUrlWithQuery(['status' => 'Done']) }}"
                    class="btn rounded-pill px-3 py-1 small fw-bold {{ $currentFilter === 'Done' ? 'btn-primary' : 'btn-light text-muted border' }}"
                    style="{{ $currentFilter === 'Done' ? 'background-color: #4f46e5; border: none;' : '' }}">
                    Done
                </a>
            </div>
        </div>

        {{-- EVENTS GRID --}}
        <div class="row g-3"> {{-- Diubah ke g-3 agar grid antar kartu pas (micro-spacing) --}}
            @forelse($events as $event)
                <div class="col-md-6 col-lg-4">
                    <a href="/events/{{ $event->id_event }}/details" class="text-decoration-none text-dark">
                        <div class="card h-100 border-0 shadow-sm event-card p-2"
                            style="border-radius: 20px; transition: 0.2s ease;">
                            <div class="card-body p-3 d-flex flex-column h-100">

                                {{-- Top Header: Icon & Status --}}
                                <div class="d-flex justify-content-between align-items-start mb-4">
                                    <div class="rounded-3 text-white p-2 d-flex align-items-center justify-content-center shadow-sm"
                                        style="width: 42px; height: 42px; background-color: #4f46e5;">
                                        <i class="bi bi-calendar-event fs-5"></i>
                                    </div>
                                    @php
                                        // Ambil waktu sekarang dan waktu event
                                        $now = \Carbon\Carbon::now();
                                        $mulai = \Carbon\Carbon::parse($event->tgl_mulai);
                                        $selesai = $event->tgl_selesai
                                            ? \Carbon\Carbon::parse($event->tgl_selesai)
                                            : null;

                                        // Logika penentuan status otomatis yang diperbaiki
                                        if ($now->lt($mulai)) {
                                            // 1. Jika sekarang BELUM melewati tanggal mulai
                                            $status = 'Planning';
                                            $badgeClass = 'bg-warning-subtle text-warning';
                                        } else {
                                            // Jika sekarang SUDAH melewati atau SAMA DENGAN tanggal mulai
                                            if ($selesai) {
                                                // Jika ada tanggal selesai, cek apakah sekarang berada di antaranya
                                                if ($now->between($mulai, $selesai)) {
                                                    $status = 'Ongoing';
                                                    $badgeClass = 'bg-primary-subtle text-primary';
                                                } else {
                                                    $status = 'Done';
                                                    $badgeClass = 'bg-success-subtle text-success';
                                                }
                                            } else {
                                                // FALLBACK AMAN: Jika tgl_selesai kosong, tapi hari ini sudah lewat tgl_mulai
                                                // Kita cek apakah hari mulainya sama dengan hari ini (Ongoing), jika sudah lewat hari (Done)
                                                if ($now->isSameDay($mulai)) {
                                                    $status = 'Ongoing';
                                                    $badgeClass = 'bg-primary-subtle text-primary';
                                                } else {
                                                    $status = 'Done';
                                                    $badgeClass = 'bg-success-subtle text-success';
                                                }
                                            }
                                        }
                                    @endphp
                                    <span class="badge {{ $badgeClass }} rounded-pill px-25 py-15 fw-bold"
                                        style="font-size: 10px;">{{ strtoupper($status) }}</span>
                                </div>

                                {{-- Event Info --}}
                                <h5 class="fw-bold text-dark mb-2 text-truncate-title">{{ $event->nama_event }}</h5>

                                <div class="mb-4">
                                    <div class="d-flex align-items-center mb-2 text-secondary small"
                                        style="font-size: 0.825rem;">
                                        <i class="bi bi-calendar3 me-2 text-primary"></i>
                                        <span>{{ \Carbon\Carbon::parse($event->tgl_mulai)->format('M d, Y') }}</span>
                                    </div>
                                </div>

                                {{-- Member Avatars --}}
                                <div class="d-flex align-items-center pt-3 border-top mt-auto"
                                    style="border-color: #f1f5f9 !important;">
                                    <div class="avatar-group d-flex">
                                        <div class="avatar text-white rounded-circle border border-white"
                                            style="width: 28px; height: 28px; font-size: 10px; display: flex; align-items: center; justify-content: center; margin-right: -8px; background-color: #4f46e5;">
                                            A</div>
                                        <div class="avatar text-white rounded-circle border border-white"
                                            style="width: 28px; height: 28px; font-size: 10px; display: flex; align-items: center; justify-content: center; margin-right: -8px; background-color: #10b981;">
                                            B</div>
                                        <div class="avatar text-white rounded-circle border border-white"
                                            style="width: 28px; height: 28px; font-size: 10px; display: flex; align-items: center; justify-content: center; margin-right: -8px; background-color: #f59e0b;">
                                            C</div>
                                        <div class="avatar bg-light text-muted rounded-circle border border-white fw-bold"
                                            style="width: 28px; height: 28px; font-size: 10px; display: flex; align-items: center; justify-content: center;">
                                            +{{ $event->committees->count() ?? 0 }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                {{-- Empty Placeholder State jika status filter tidak memiliki data --}}
                <div class="col-12">
                    <div class="card border-0 shadow-sm text-center p-5 bg-white mb-4"
                        style="border-radius: 16px; min-height: 300px;">
                        <div class="my-auto py-4">
                            <i class="bi bi-calendar-x text-muted opacity-20 d-block mb-2" style="font-size: 50px;"></i>
                            <h5 class="fw-bold text-dark" style="font-size: 1.1rem;">Tidak Ada Event Ditemukan</h5>
                            <p class="text-muted small mx-auto mb-0" style="max-width: 380px;">
                                Tidak ada kegiatan dengan status kepanitiaan <span
                                    class="text-primary fw-semibold">"{{ $currentFilter }}"</span> untuk saat ini.
                            </p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <style>
        .event-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05) !important;
        }

        .avatar-group .avatar {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .btn-light.border:hover {
            background-color: #f1f5f9;
            border-color: #cbd5e1 !important;
        }

        /* Mencegah judul event meluber merusak tinggi card grid */
        .text-truncate-title {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Micro spacing utilities */
        .px-25 {
            padding-left: 12px !important;
            padding-right: 12px !important;
        }

        .py-15 {
            padding-top: 4px !important;
            padding-bottom: 4px !important;
        }

        /* Modern Scrollbar for Filter */
        .overflow-x-auto::-webkit-scrollbar {
            height: 4px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        body {
            background-color: #f8fafc;
        }
    </style>
@endsection
