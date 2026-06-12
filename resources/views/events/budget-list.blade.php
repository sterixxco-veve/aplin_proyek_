@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 pb-5"> {{-- Menggunakan container-fluid agar pas dengan space dasbor --}}

        {{-- HEADER SECTION --}}
        <div class="mb-4">
            <h2 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px; font-size: 1.5rem;">
                Budget Management
            </h2>
            <p class="text-muted small">
                Select an event to manage budgets
            </p>
        </div>

        {{-- EVENTS GRID --}}
        <div class="row g-3"> {{-- Menggunakan g-3 agar jarak antar grid pas dan proporsional --}}

            @forelse($events as $event)
                @php
                    $totalBudget = $event->budgets->sum(function ($budget) {
                        return $budget->qty * $budget->nominal_rencana;
                    });
                @endphp

                <div class="col-md-6 col-xl-4">
                    <div class="card border-0 shadow-sm event-card p-2"
                        style="border-radius: 20px; background: #ffffff; transition: 0.2s ease;">
                        <div class="card-body d-flex flex-column h-100 p-3">

                            {{-- Judul & Tanggal Event --}}
                            <h5 class="fw-bold text-dark mb-1 text-truncate-title" style="font-size: 1.15rem;">
                                {{ $event->nama_event }}
                            </h5>

                            <p class="text-secondary small mb-3" style="font-size: 0.8rem;">
                                <i class="bi bi-calendar3 me-1.5 text-primary"></i>
                                {{ $event->tgl_mulai ? \Carbon\Carbon::parse($event->tgl_mulai)->format('d M Y') : '-' }}
                            </p>

                            {{-- Badge Items --}}
                            <div class="mb-3">
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-2.5 py-15 fw-bold"
                                    style="font-size: 10px;">
                                    <i class="bi bi-box-seam me-1"></i>{{ $event->budgets->count() }} Budget Items
                                </span>
                            </div>

                            {{-- Total Planned Budget Box --}}
                            <div class="mb-4 p-25 rounded-3 border"
                                style="background-color: #f8fafc; border-color: #e2e8f0 !important;">
                                <div class="text-secondary fw-medium mb-1" style="font-size: 0.775rem;">
                                    Total Planned Budget
                                </div>
                                <div class="fw-bold text-success" style="font-size: 1.2rem; letter-spacing: -0.3px;">
                                    Rp {{ number_format($totalBudget, 0, ',', '.') }}
                                </div>
                            </div>

                            {{-- Action Button --}}
                            <div class="mt-auto pt-2">
                                <a href="{{ route('web.budget.show', $event->id_event) }}"
                                    class="btn btn-primary w-100 rounded-pill fw-semibold py-2"
                                    style="background-color: #4f46e5; border: none; font-size: 0.875rem;">
                                    Manage Budget
                                </a>
                            </div>

                        </div>
                    </div>
                </div>

            @empty

                {{-- Empty Placeholder State --}}
                <div class="col-12">
                    <div class="card border-0 shadow-sm text-center p-5 bg-white"
                        style="border-radius: 16px; min-height: 300px;">
                        <div class="my-auto py-4">
                            <i class="bi bi-wallet2 text-muted opacity-20 d-block mb-2" style="font-size: 50px;"></i>
                            <h5 class="fw-bold text-dark" style="font-size: 1.1rem;">Belum Ada Event Tersedia</h5>
                            <p class="text-muted small mx-auto mb-0" style="max-width: 380px;">
                                Saat ini belum ada data kegiatan aktif yang terdaftar untuk pengelolaan anggaran
                                operasional.
                            </p>
                        </div>
                    </div>
                </div>
            @endforelse

        </div>
    </div>

    <style>
        /* Spacing kustom mikro agar seimbang */
        .p-25 {
            padding: 10px 12px !important;
        }

        .py-15 {
            padding-top: 4px !important;
            padding-bottom: 4px !important;
        }

        .me-1.5 {
            margin-right: 6px !important;
        }

        /* Mencegah judul yang panjang merusak kesetaraan tinggi card */
        .text-truncate-title {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Efek hover lembut pada kartu */
        .event-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05) !important;
        }

        body {
            background-color: #f8fafc;
        }
    </style>
@endsection
