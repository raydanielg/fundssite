@extends('layouts.admin')

@section('title', 'Manual Donations')
@section('page_title', 'Manual Donations')

@section('content')
    <div class="container-fluid">
        <div class="row g-4">
            <div class="col-12 col-lg-5">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">Import from Excel</h5>
                        <div class="text-muted small">Bulk upload donations using an Excel file.</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 p-3 bg-light rounded-3 border">
                            <h6 class="fw-bold small mb-2">Step 1: Download Template</h6>
                            <p class="x-small text-muted mb-3">Download the Excel template to ensure your data is in the correct format.</p>
                            <a href="{{ route('admin.transactions.manual.template') }}" class="btn btn-sm btn-outline-primary w-100">
                                <i class="bi bi-download me-1"></i> Download Template (.xlsx)
                            </a>
                        </div>

                        <form method="POST" action="{{ route('admin.transactions.manual.import') }}" enctype="multipart/form-data">
                            @csrf
                            <h6 class="fw-bold small mb-2">Step 2: Upload & Review</h6>
                            <div class="mb-3">
                                <input type="file" name="excel_file" class="form-control @error('excel_file') is-invalid @enderror" accept=".xlsx,.xls,.csv" required>
                                @error('excel_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-file-earmark-arrow-up me-1"></i> Preview Import
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">Add Manual Donation</h5>
                        <div class="text-muted small">Record donations received via cash, bank, or other channels.</div>
                    </div>
                    <div class="card-body">
                        @if (session('status') === 'saved')
                            <div class="alert alert-success py-2">Transaction saved successfully.</div>
                        @endif
                        @if (session('status') === 'imported')
                            <div class="alert alert-success py-2">Bulk donations imported successfully!</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger py-2">{{ session('error') }}</div>
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
