@extends('layouts.admin')

@section('title', 'Expenses')
@section('page_title', 'Expenses')

@section('content')
    <div class="container-fluid">
        <div class="row g-4">
            <div class="col-12 col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">Add Expense</h5>
                        <div class="text-muted small">Track fundraiser spending. Receipt upload is optional.</div>
                    </div>
                    <div class="card-body">
                        @if (session('status') === 'saved')
                            <div class="alert alert-success py-2">Saved.</div>
                        @endif

                        <form method="POST" action="{{ route('admin.expenses.store') }}" enctype="multipart/form-data" class="row g-3">
                            @csrf

                            <div class="col-12">
                                <label class="form-label fw-semibold" for="spent_at">Date</label>
                                <input id="spent_at" name="spent_at" type="date" class="form-control @error('spent_at') is-invalid @enderror" value="{{ old('spent_at', now()->toDateString()) }}" required>
                                @error('spent_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold" for="description">Description</label>
                                <input id="description" name="description" type="text" class="form-control @error('description') is-invalid @enderror" value="{{ old('description') }}" required>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold" for="amount">Amount ({{ $currency ?? 'TZS' }})</label>
                                <input id="amount" name="amount" type="number" min="1" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" required>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold" for="receipt">Receipt (optional)</label>
                                <input id="receipt" name="receipt" type="file" class="form-control @error('receipt') is-invalid @enderror" accept="image/*,application/pdf">
                                @error('receipt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="text-muted small mt-1">Max 5MB.</div>
                            </div>

                            <div class="col-12 d-flex gap-2">
                                <button class="btn btn-success px-4" type="submit">
                                    <i class="bi bi-plus-circle me-1"></i> Add
                                </button>
                                <a class="btn btn-outline-secondary" href="{{ url('/admin/fundraiser') }}">Settings</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small">Total Expenses</div>
                                <div class="fw-bold" style="font-size: 1.25rem;">{{ $currency ?? 'TZS' }} {{ number_format((int) ($total ?? 0)) }}</div>
                            </div>
                            <div class="text-muted small text-end">
                                Syncs to fundraiser settings automatically
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-0 fw-bold">Expenses Log</h5>
                            <div class="text-muted small">Latest spending records.</div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-3 border-0 small text-uppercase text-muted fw-bold">Date</th>
                                        <th class="border-0 small text-uppercase text-muted fw-bold">Description</th>
                                        <th class="border-0 small text-uppercase text-muted fw-bold">Amount</th>
                                        <th class="pe-3 border-0 small text-uppercase text-muted fw-bold">Receipt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(($expenses ?? []) as $e)
                                        <tr>
                                            <td class="ps-3 py-3"><span class="text-muted small">{{ optional($e->spent_at)->format('Y-m-d') }}</span></td>
                                            <td><div class="fw-semibold text-dark small">{{ $e->description }}</div></td>
                                            <td><span class="fw-bold text-dark small">{{ $e->currency ?? ($currency ?? 'TZS') }} {{ number_format((int) $e->amount) }}</span></td>
                                            <td class="pe-3">
                                                @if ($e->receipt_path)
                                                    <a class="btn btn-sm btn-outline-secondary" href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($e->receipt_path) }}" target="_blank">
                                                        View
                                                    </a>
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">No expenses yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
