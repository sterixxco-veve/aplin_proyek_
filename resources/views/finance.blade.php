@extends('layouts.app')

@section('content')
<div class="container pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Finance</h2>
            <p class="text-muted mb-0">Pilih event untuk membuka detail budget dan expense.</p>
        </div>
    </div>

    <div class="row g-4">
        @forelse($events as $event)
            @php
                $financial = $event->financial_summary;
            @endphp

            <div class="col-md-6 col-lg-4">
                <a href="/events/{{ $event->id_event }}/expenses" class="text-decoration-none text-dark">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-2">{{ $event->nama_event }}</h5>
                            <p class="text-muted small mb-3">
                                {{ \Carbon\Carbon::parse($event->tgl_mulai)->format('M d, Y') }}
                            </p>

                            <div class="d-flex justify-content-between small mb-2">
                                <span class="text-muted">Budget</span>
                                <span class="fw-semibold">Rp {{ number_format($financial['total_budget']) }}</span>
                            </div>
                            <div class="d-flex justify-content-between small mb-2">
                                <span class="text-muted">Expense</span>
                                <span class="fw-semibold text-danger">Rp {{ number_format($financial['total_expense']) }}</span>
                            </div>
                            <div class="d-flex justify-content-between small">
                                <span class="text-muted">Remaining</span>
                                <span class="fw-semibold text-success">Rp {{ number_format($financial['remaining']) }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 text-muted">
                        Belum ada event yang bisa dibuka.
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
