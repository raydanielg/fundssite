@extends('layouts.admin')

@section('title', 'Fundraiser Settings')
@section('page_title', 'Fundraiser Settings')

@section('content')
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="card-title mb-0 fw-bold">Target & Expenses Configuration</h5>
                </div>
                <form method="POST" action="{{ route('admin.fundraiser.update') }}">
                    @csrf
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-muted text-uppercase">Currency</label>
                                <div class="col-12">
                                <label class="form-label fw-semibold" for="currency">Currency</label>
                                <input id="currency" name="currency" type="text" class="form-control @error('currency') is-invalid @enderror" value="{{ old('currency', $settings->currency ?? 'TZS') }}" required>
                                @error('currency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-4">
                            <h6 class="fw-bold mb-3 text-mint">Payment Accounts (Selcom)</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Selcom Name</label>
                                    <input name="selcom_name" type="text" class="form-control" value="{{ old('selcom_name', $settings->selcom_name ?? '') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Selcom Number</label>
                                    <input name="selcom_number" type="text" class="form-control" value="{{ old('selcom_number', $settings->selcom_number ?? '') }}">
                                </div>
                            </div>

                            <h6 class="fw-bold mb-3 mt-4 text-mint">Payment Accounts (Tigo Pesa)</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tigo Name</label>
                                    <input name="tigo_name" type="text" class="form-control" value="{{ old('tigo_name', $settings->tigo_name ?? '') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tigo Number</label>
                                    <input name="tigo_number" type="text" class="form-control" value="{{ old('tigo_number', $settings->tigo_number ?? '') }}">
                                </div>
                            </div>

                            <h6 class="fw-bold mb-3 mt-4 text-mint">Payment Accounts (CRDB)</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">CRDB Name</label>
                                    <input name="crdb_name" type="text" class="form-control" value="{{ old('crdb_name', $settings->crdb_name ?? '') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">CRDB Number</label>
                                    <input name="crdb_number" type="text" class="form-control" value="{{ old('crdb_number', $settings->crdb_number ?? '') }}">
                                </div>
                            </div>

                            <div class="col-md-8">
                                <label class="form-label fw-bold small text-muted text-uppercase">Fundraising Target</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">{{ $settings->currency ?? 'TZS' }}</span>
                                    <input type="number" name="target_amount" class="form-control bg-light border-start-0" value="{{ old('target_amount', $settings->target_amount ?? 150000000) }}" min="1" step="1" />
                                </div>
                                @error('target_amount')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mt-4">
                                <label class="form-label fw-bold small text-muted text-uppercase">Medical Expenses Deducted</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-danger"><i class="bi bi-hospital"></i></span>
                                    <input type="number" name="expenses_amount" class="form-control bg-light border-start-0" value="{{ old('expenses_amount', $settings->expenses_amount ?? 2289225) }}" min="0" step="1" />
                                </div>
                                <div class="form-text small">This amount is subtracted from the "Total Raised" to show the current balance.</div>
                                @error('expenses_amount')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4 p-3 bg-primary-subtle rounded-3 border border-primary-subtle">
                            <div class="d-flex align-items-center text-primary">
                                <i class="bi bi-info-circle-fill fs-5 me-2"></i>
                                <span class="small fw-bold">Live Progress:</span>
                            </div>
                            <p class="small mb-0 text-primary-emphasis mt-1">
                                The progress bar on the public landing page updates automatically as soon as a donor completes their payment via Snippe.
                            </p>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0 p-4 d-flex justify-content-end gap-2">
                        <a href="{{ url('/admin') }}" class="btn btn-light border fw-bold px-4">Cancel</a>
                        <button type="submit" class="btn btn-primary fw-bold px-4 shadow-sm">
                            <i class="bi bi-save me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4 text-center p-4">
                <div class="card-body">
                    <div class="bg-success-subtle p-3 rounded-circle d-inline-block mb-3">
                        <i class="bi bi-shield-check fs-1 text-success"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Fundraiser Security</h5>
                    <p class="text-muted small">Only authorized administrators can modify these settings. Every change is reflected instantly on the public site.</p>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm p-4">
                <h6 class="fw-bold mb-3 small text-uppercase text-muted ls-1">Need help?</h6>
                <div class="d-grid gap-2">
                    <a href="{{ url('/') }}" target="_blank" class="btn btn-sm btn-outline-secondary text-start p-2">
                        <i class="bi bi-eye me-2"></i> View Public Site
                    </a>
                    <a href="https://docs.snippe.sh" target="_blank" class="btn btn-sm btn-outline-secondary text-start p-2">
                        <i class="bi bi-book me-2"></i> Snippe Documentation
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
