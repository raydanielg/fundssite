@extends('layouts.admin')

@section('title', 'Fundraiser Settings')
@section('page_title', 'Fundraiser Settings')

@push('styles')
<style>
    .settings-card { border-radius: 16px; border: none; }
    .section-label { font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; margin-bottom: 1rem; display: block; }
    .form-control, .form-select, .input-group-text { border-radius: 10px; padding: 0.6rem 1rem; border: 1px solid #e2e8f0; }
    .form-control:focus { border-color: #2e9e72; box-shadow: 0 0 0 3px rgba(46, 158, 114, 0.1); }
    .input-group-text { background-color: #f8fafc; font-weight: 600; color: #475569; }
    .btn-save { background-color: #2e9e72; color: #fff; border: none; padding: 0.7rem 2rem; border-radius: 12px; font-weight: 700; transition: all 0.2s; }
    .btn-save:hover { background-color: #25855f; color: #fff; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(46, 158, 114, 0.2); }
    .pay-section { background: #f8fafc; border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; border: 1px solid #f1f5f9; }
    .pay-icon { width: 40px; height: 40px; background: #fff; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05); color: #2e9e72; }
    .help-card { background: linear-gradient(135deg, #2e9e72 0%, #1a5e44 100%); color: #fff; border-radius: 16px; border: none; }
    .help-btn { background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.2); border-radius: 10px; transition: all 0.2s; }
    .help-btn:hover { background: rgba(255,255,255,0.25); color: #fff; }
</style>
@endpush

@section('content')
    <div class="row g-4">
        <div class="col-xl-8">
            <form method="POST" action="{{ route('admin.fundraiser.update') }}">
                @csrf
                <!-- Core Campaign Settings -->
                <div class="card settings-card shadow-sm mb-4">
                    <div class="card-body p-4 p-md-5">
                        <span class="section-label">Campaign Target & Currency</span>
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Currency Code</label>
                                <input name="currency" type="text" class="form-control @error('currency') is-invalid @enderror" value="{{ old('currency', $settings->currency ?? 'TZS') }}" maxlength="3" required>
                                @error('currency') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-8">
                                <label class="form-label small fw-bold">Total Goal Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ $settings->currency ?? 'TZS' }}</span>
                                    <input type="number" name="target_amount" class="form-control" value="{{ old('target_amount', $settings->target_amount ?? 0) }}" required>
                                </div>
                            </div>
                        </div>

                        <hr class="my-5 opacity-50">

                        <span class="section-label">Direct Payment Methods (Hero Section)</span>
                        
                        <div class="row g-3">
                            <!-- Selcom -->
                            <div class="col-md-12">
                                <div class="pay-section">
                                    <div class="pay-icon"><i class="bi bi-phone"></i></div>
                                    <h6 class="fw-bold mb-3">Selcom Microfinance</h6>
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <label class="form-label x-small fw-bold text-muted">Account Name</label>
                                            <input name="selcom_name" type="text" class="form-control" value="{{ old('selcom_name', $settings->selcom_name ?? '') }}" placeholder="e.g. Joseph Msuya">
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label x-small fw-bold text-muted">Number / Reference</label>
                                            <input name="selcom_number" type="text" class="form-control" value="{{ old('selcom_number', $settings->selcom_number ?? '') }}" placeholder="e.g. 0714 000 000">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tigo Pesa -->
                            <div class="col-md-12">
                                <div class="pay-section">
                                    <div class="pay-icon"><i class="bi bi-smartphone"></i></div>
                                    <h6 class="fw-bold mb-3">Tigo Pesa</h6>
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <label class="form-label x-small fw-bold text-muted">Account Name</label>
                                            <input name="tigo_name" type="text" class="form-control" value="{{ old('tigo_name', $settings->tigo_name ?? '') }}" placeholder="e.g. Joseph Msuya">
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label x-small fw-bold text-muted">Phone Number</label>
                                            <input name="tigo_number" type="text" class="form-control" value="{{ old('tigo_number', $settings->tigo_number ?? '') }}" placeholder="e.g. 0714 000 000">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- CRDB -->
                            <div class="col-md-12">
                                <div class="pay-section">
                                    <div class="pay-icon"><i class="bi bi-bank"></i></div>
                                    <h6 class="fw-bold mb-3">CRDB Bank</h6>
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <label class="form-label x-small fw-bold text-muted">Account Name</label>
                                            <input name="crdb_name" type="text" class="form-control" value="{{ old('crdb_name', $settings->crdb_name ?? '') }}" placeholder="e.g. Joseph Abdallah Msuya">
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label x-small fw-bold text-muted">Account Number</label>
                                            <input name="crdb_number" type="text" class="form-control" value="{{ old('crdb_number', $settings->crdb_number ?? '') }}" placeholder="e.g. 0152 000 000 000">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-5 opacity-50">

                        <span class="section-label">Initial Expense (Old System - Legacy)</span>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Manual Expenses Deducted</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-hospital text-danger"></i></span>
                                <input type="number" name="expenses_amount" class="form-control" value="{{ old('expenses_amount', $settings->expenses_amount ?? 0) }}">
                            </div>
                            <div class="form-text x-small mt-2">Note: This is added to the dynamic expenses logged in the Expenses tab.</div>
                        </div>
                    </div>
                    <div class="card-footer bg-light border-0 p-4 d-flex justify-content-between align-items-center">
                        <span class="text-muted small"><i class="bi bi-clock-history me-1"></i> Last updated: {{ $settings->updated_at ? \Carbon\Carbon::parse($settings->updated_at)->diffForHumans() : 'Never' }}</span>
                        <div class="d-flex gap-2">
                            <a href="{{ url('/admin') }}" class="btn btn-link text-decoration-none text-muted fw-bold">Cancel</a>
                            <button type="submit" class="btn btn-save shadow-sm">
                                <i class="bi bi-check-lg me-2"></i>Save Settings
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-xl-4">
            <div class="card help-card shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-white bg-opacity-20 p-2 rounded-circle me-3">
                            <i class="bi bi-lightning-charge-fill fs-4 text-white"></i>
                        </div>
                        <h5 class="fw-bold mb-0">Live Updates</h5>
                    </div>
                    <p class="small text-white text-opacity-75 mb-4">
                        Any changes made here will reflect instantly on the landing page hero section. Ensure account numbers are accurate for donors.
                    </p>
                    <div class="d-grid gap-2">
                        <a href="{{ url('/') }}" target="_blank" class="btn help-btn text-start">
                            <i class="bi bi-eye me-2"></i> Preview Website
                        </a>
                        <a href="{{ route('admin.transactions') }}" class="btn help-btn text-start">
                            <i class="bi bi-list-check me-2"></i> Manage Transactions
                        </a>
                    </div>
                </div>
            </div>

            <div class="card settings-card shadow-sm border-0">
                <div class="card-body p-4 text-center">
                    <div class="mb-3 text-success">
                        <i class="bi bi-shield-lock-fill display-5"></i>
                    </div>
                    <h6 class="fw-bold">Secure Access</h6>
                    <p class="x-small text-muted mb-0">Changes are logged for security auditing. Only admins with fundraiser permissions can access this page.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
