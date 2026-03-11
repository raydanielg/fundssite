@extends('layouts.admin')

@section('title', 'Manual Donations')
@section('page_title', 'Manual Donations')

@section('content')
    <div class="container-fluid">
        <div class="row g-4">
            <div class="col-12 col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">Add Manual Donation</h5>
                        <div class="text-muted small">Record donations received via cash, bank, or other channels.</div>
                    </div>
                    <div class="card-body">
                        @if (session('status') === 'saved')
                            <div class="alert alert-success py-2">Saved.</div>
                        @endif

                        <form method="POST" action="{{ route('admin.transactions.manual.store') }}" class="row g-3">
                            @csrf

                            <div class="col-12">
                                <label class="form-label fw-semibold" for="name">Full name</label>
                                <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold" for="phone">Phone number</label>
                                <input id="phone" name="phone" type="text" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required>
                                @error('phone')
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
                                <button class="btn btn-success px-4" type="submit">
                                    <i class="bi bi-check2-circle me-1"></i> Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-0 fw-bold">Recent Manual Donations</h5>
                            <div class="text-muted small">Latest records entered manually.</div>
                        </div>
                        <a class="btn btn-sm btn-outline-secondary" href="{{ url('/admin/transactions') }}">
                            View Payments
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-3 border-0 small text-uppercase text-muted fw-bold">Name</th>
                                        <th class="border-0 small text-uppercase text-muted fw-bold">Phone</th>
                                        <th class="border-0 small text-uppercase text-muted fw-bold">Amount</th>
                                        <th class="border-0 small text-uppercase text-muted fw-bold">Paid At</th>
                                        <th class="pe-3 border-0 small text-uppercase text-muted fw-bold">Reference</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(($recent ?? []) as $t)
                                        <tr>
                                            <td class="ps-3 py-3">
                                                <div class="fw-bold text-dark small">{{ $t->customer_name }}</div>
                                            </td>
                                            <td><span class="text-muted small">{{ $t->customer_phone ?? '—' }}</span></td>
                                            <td><span class="fw-bold text-dark small">{{ $t->currency ?? ($currency ?? 'TZS') }} {{ number_format((int) $t->amount) }}</span></td>
                                            <td><span class="text-muted small">{{ $t->paid_at ? $t->paid_at->timezone('Africa/Dar_es_Salaam')->format('Y-m-d H:i') : '—' }}</span></td>
                                            <td class="pe-3"><code class="text-muted" style="font-size:0.75rem">{{ $t->reference }}</code></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">No manual donations yet.</td>
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
