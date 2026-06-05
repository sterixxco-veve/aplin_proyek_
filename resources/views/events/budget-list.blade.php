@extends('layouts.app')

@section('content')

    <div class="container py-4">

        <div class="mb-4">

            <h2 class="fw-bold">
                Budget Management
            </h2>

            <p class="text-muted mb-0">
                Select an event to manage budgets
            </p>

        </div>

        <div class="row g-4">

            @forelse($events as $event)

                @php

                    $totalBudget =
                        $event->budgets->sum(function ($budget) {
                            return $budget->qty * $budget->nominal_rencana;
                        });

                @endphp

                <div class="col-md-6 col-xl-4">

                    <div class="card border-0 shadow-sm rounded-4 h-100">

                        <div class="card-body d-flex flex-column">

                            <h5 class="fw-bold mb-2">
                                {{ $event->nama_event }}
                            </h5>

                            <p class="text-muted small mb-3">

                                {{ $event->tgl_mulai
                ? \Carbon\Carbon::parse($event->tgl_mulai)->format('d M Y')
                : '-' }}

                            </p>

                            <div class="mb-3">

                                <span class="badge bg-primary">

                                    {{ $event->budgets->count() }}
                                    Budget Items

                                </span>

                            </div>

                            <div class="mb-4">

                                <div class="small text-muted">
                                    Total Planned Budget
                                </div>

                                <div class="fw-bold text-success fs-5">

                                    Rp {{ number_format($totalBudget, 0, ',', '.') }}

                                </div>

                            </div>

                            <div class="mt-auto">

                                <a href="{{ route('web.budget.show', $event->id_event) }}"
                                    class="btn btn-primary w-100 rounded-3">

                                    Manage Budget

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            @empty

                <div class="col-12">

                    <div class="alert alert-secondary">

                        No events found.

                    </div>

                </div>

            @endforelse

        </div>

    </div>

@endsection