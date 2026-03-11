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
                            <div class="alert alert-success py-2 border-0 shadow-sm mb-3">
                                <i class="bi bi-check-circle-fill me-2"></i> Expense added successfully.
                            </div>
                        @endif
                        @if (session('status') === 'updated')
                            <div class="alert alert-success py-2 border-0 shadow-sm mb-3">
                                <i class="bi bi-check-circle-fill me-2"></i> Expense updated successfully.
                            </div>
                        @endif
                        @if (session('status') === 'deleted')
                            <div class="alert alert-danger py-2 border-0 shadow-sm mb-3">
                                <i class="bi bi-trash-fill me-2"></i> Expense deleted successfully.
                            </div>
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
                                        <th class="pe-3 border-0 small text-uppercase text-muted fw-bold text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(($expenses ?? []) as $e)
                                        <tr>
                                            <td class="ps-3 py-3"><span class="text-muted small">{{ optional($e->spent_at)->format('Y-m-d') }}</span></td>
                                            <td><div class="fw-semibold text-dark small">{{ $e->description }}</div></td>
                                            <td><span class="fw-bold text-dark small">{{ $e->currency ?? ($currency ?? 'TZS') }} {{ number_format((int) $e->amount) }}</span></td>
                                            <td class="pe-3 text-end">
                                                <div class="d-flex justify-content-end gap-2">
                                                    @if ($e->receipt_path)
                                                        <a class="btn btn-sm btn-light rounded-circle" href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($e->receipt_path) }}" target="_blank" title="View Receipt">
                                                            <i class="bi bi-file-earmark-text"></i>
                                                        </a>
                                                    @endif
                                                    <button class="btn btn-sm btn-outline-primary rounded-circle" type="button" data-bs-toggle="modal" data-bs-target="#edit-expense-{{ $e->id }}" title="Edit Expense">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger rounded-circle" type="button" data-bs-toggle="modal" data-bs-target="#delete-expense-{{ $e->id }}" title="Delete Expense">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>

                                                <!-- Edit Expense Modal -->
                                                <div class="modal fade" id="edit-expense-{{ $e->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content border-0 shadow">
                                                            <div class="modal-header border-bottom-0 pb-0">
                                                                <h5 class="modal-title fw-bold">Edit Expense</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{ route('admin.expenses.update', $e->id) }}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                @method('PATCH')
                                                                <div class="modal-body text-start">
                                                                    <div class="mb-3">
                                                                        <label class="form-label small fw-bold">Date Spent</label>
                                                                        <input type="date" name="spent_at" class="form-control" value="{{ optional($e->spent_at)->format('Y-m-d') }}" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label small fw-bold">Description</label>
                                                                        <input type="text" name="description" class="form-control" value="{{ $e->description }}" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label small fw-bold">Amount ({{ $currency ?? 'TZS' }})</label>
                                                                        <input type="number" name="amount" class="form-control" value="{{ (int)$e->amount }}" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label small fw-bold">Receipt (optional)</label>
                                                                        <input type="file" name="receipt" class="form-control" accept="image/*,application/pdf">
                                                                        @if($e->receipt_path)
                                                                            <div class="mt-2 small text-muted">Current: <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($e->receipt_path) }}" target="_blank">View</a></div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer border-top-0 pt-0">
                                                                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Update Expense</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Delete Expense Modal -->
                                                <div class="modal fade" id="delete-expense-{{ $e->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-sm">
                                                        <div class="modal-content border-0 shadow">
                                                            <div class="modal-body text-center p-4">
                                                                <div class="text-danger mb-3">
                                                                    <i class="bi bi-exclamation-triangle-fill display-4"></i>
                                                                </div>
                                                                <h5 class="fw-bold mb-2">Delete Expense?</h5>
                                                                <p class="text-muted small mb-4">Are you sure? This will also update the campaign balance.</p>
                                                                
                                                                <form action="{{ route('admin.expenses.destroy', $e->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <div class="d-grid gap-2">
                                                                        <button type="submit" class="btn btn-danger rounded-pill fw-bold">Confirm Delete</button>
                                                                        <button type="button" class="btn btn-light rounded-pill border fw-bold" data-bs-dismiss="modal">Cancel</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
