@extends('layouts.admin')

@section('title', 'Transactions')
@section('page_title', 'Transactions')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<style>
    .dt-buttons { margin-bottom: 15px; }
    .dataTables_filter { margin-bottom: 15px; }
    .table thead th { border-top: none; }
</style>
@endpush

@section('content')
    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0 fw-bold">Contribution Stream</h5>
                    <div class="d-flex align-items-center gap-2">
                        <form method="POST" action="{{ route('admin.transactions.sync') }}" class="m-0">
                            @csrf
                            <input type="hidden" name="limit" value="100">
                            <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                <i class="bi bi-arrow-repeat me-1"></i> Sync Pending
                            </button>
                        </form>
                        <a class="btn btn-sm btn-outline-secondary rounded-pill px-3" href="{{ route('admin.transactions.manual') }}">
                            <i class="bi bi-plus-circle me-1"></i> Manual Donation
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('status') === 'synced')
                        <div class="alert alert-success py-2">Synced pending payments.</div>
                    @endif
                    <div class="table-responsive">
                        <table id="transactionsTable" class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-3 border-0 small text-uppercase text-muted fw-bold">Contributor</th>
                                    <th class="border-0 small text-uppercase text-muted fw-bold">Amount</th>
                                    <th class="border-0 small text-uppercase text-muted fw-bold">Status</th>
                                    <th class="border-0 small text-uppercase text-muted fw-bold">Paid At</th>
                                    <th class="pe-3 border-0 small text-uppercase text-muted fw-bold">Reference</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (($transactions ?? []) as $t)
                                    <tr>
                                        <td class="ps-3 py-3">
                                            <div class="fw-bold text-dark small">{{ $t->customer_name }}</div>
                                            <div class="text-muted x-small" style="font-size: 0.75rem;">
                                                {{ $t->customer_email ?? 'no-email' }}
                                                @if($t->customer_phone) · {{ $t->customer_phone }} @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark small">{{ $t->currency ?? 'TZS' }} {{ number_format((int) $t->amount) }}</span>
                                        </td>
                                        <td>
                                            @if ($t->status === 'completed')
                                                <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill small fw-bold">
                                                    completed
                                                </span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning px-2 py-1 rounded-pill small fw-bold">
                                                    pending
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-muted small">
                                                @if($t->paid_at)
                                                    {{ $t->paid_at->timezone('Africa/Dar_es_Salaam')->format('Y-m-d H:i') }}
                                                @else
                                                    <span class="text-light-emphasis small italic">Waiting...</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="pe-3">
                                            <code class="x-small text-muted" style="font-size: 0.7rem;">{{ $t->reference }}</code>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#transactionsTable').DataTable({
                dom: '<"d-flex justify-content-between align-items-center mb-3"Bf>rt<"d-flex justify-content-between align-items-center mt-3"ip>',
                buttons: [
                    {
                        extend: 'print',
                        className: 'btn btn-sm btn-outline-secondary rounded-pill px-3 me-2',
                        text: '<i class="bi bi-printer me-1"></i> Print'
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-sm btn-outline-success rounded-pill px-3 me-2',
                        text: '<i class="bi bi-file-earmark-excel me-1"></i> Excel'
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-sm btn-outline-danger rounded-pill px-3',
                        text: '<i class="bi bi-file-earmark-pdf me-1"></i> PDF'
                    }
                ],
                order: [[3, 'desc']],
                pageLength: 25,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Filter transactions..."
                }
            });
        });
    </script>
    @endpush
@endsection
