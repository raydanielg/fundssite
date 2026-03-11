@extends('layouts.admin')

@section('title', 'Transactions')
@section('page_title', 'Transactions')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<style>
    .dt-buttons { margin-bottom: 15px; }
    .dataTables_filter { margin-bottom: 15px; }
    .table thead th { border-top: none; background: #f8f9fa; }
    .status-badge { font-weight: 700; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.05em; padding: 5px 10px; border-radius: 999px; }
    .status-completed { background: #dcfce7; color: #166534; }
    .status-pending { background: #fef9c3; color: #854d0e; }
    .status-failed { background: #fee2e2; color: #991b1b; }
    .status-cancelled { background: #f3f4f6; color: #374151; }
    .x-small { font-size: 0.72rem; }
</style>
@endpush

@section('content')
    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 fw-bold">Contribution Stream</h5>
                        <p class="text-muted small mb-0">Real-time donation logs and payment status.</p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <form method="POST" action="{{ route('admin.transactions.sync') }}" class="m-0">
                            @csrf
                            <input type="hidden" name="limit" value="100">
                            <button type="submit" class="btn btn-sm btn-mint rounded-pill px-3">
                                <i class="bi bi-arrow-repeat me-1"></i> Sync Pending
                            </button>
                        </form>
                        <a class="btn btn-sm btn-outline-dark rounded-pill px-3" href="{{ route('admin.transactions.manual') }}">
                            <i class="bi bi-plus-circle me-1"></i> Manual Donation
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('status') === 'synced')
                        <div class="alert alert-success py-2 border-0 shadow-sm mb-4">
                            <i class="bi bi-check-circle-fill me-2"></i> Synced pending payments with Snippe.
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table id="transactionsTable" class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th class="ps-3 small text-uppercase text-muted fw-bold">Contributor</th>
                                    <th class="small text-uppercase text-muted fw-bold">Amount</th>
                                    <th class="small text-uppercase text-muted fw-bold">Status</th>
                                    <th class="small text-uppercase text-muted fw-bold">Method/Event</th>
                                    <th class="small text-uppercase text-muted fw-bold">Date</th>
                                    <th class="pe-3 small text-uppercase text-muted fw-bold text-end">Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (($transactions ?? []) as $t)
                                    <tr>
                                        <td class="ps-3 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-3 bg-light rounded-circle d-flex align-items-center justify-content-center text-mint fw-bold" style="width: 32px; height: 32px;">
                                                    {{ strtoupper(substr($t->customer_name ?? 'D', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark small">{{ $t->customer_name }}</div>
                                                    <div class="text-muted x-small">
                                                        {{ $t->customer_phone ?? $t->customer_email ?? 'No contact' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark small">{{ $t->currency ?? 'TZS' }} {{ number_format((int) $t->amount) }}</div>
                                            @if($t->external_reference)
                                                <div class="text-muted x-small">Ref: {{ $t->external_reference }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="status-badge status-{{ $t->status }}">
                                                {{ $t->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="small text-dark">{{ $t->webhook_event ? str_replace(['checkout.session.', 'payment.'], '', $t->webhook_event) : '-' }}</div>
                                            <code class="x-small text-muted" style="font-size: 0.65rem;">{{ $t->reference }}</code>
                                        </td>
                                        <td>
                                            <div class="small">
                                                @if($t->paid_at)
                                                    <div class="fw-bold text-dark">{{ $t->paid_at->timezone('Africa/Dar_es_Salaam')->format('Y-m-d') }}</div>
                                                    <div class="text-muted x-small">{{ $t->paid_at->timezone('Africa/Dar_es_Salaam')->format('H:i') }}</div>
                                                @else
                                                    <div class="text-muted x-small italic">Created {{ $t->created_at->diffForHumans() }}</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="pe-3 text-end">
                                            <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="modal" data-bs-target="#modal-{{ $t->id }}">
                                                <i class="bi bi-eye"></i>
                                            </button>

                                            <!-- Transaction Detail Modal -->
                                            <div class="modal fade" id="modal-{{ $t->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow">
                                                        <div class="modal-header border-bottom-0 pb-0">
                                                            <h5 class="modal-title fw-bold">Transaction Details</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-start">
                                                            <div class="mb-4 text-center">
                                                                <div class="display-6 fw-bold text-dark">{{ $t->currency }} {{ number_format($t->amount) }}</div>
                                                                <span class="status-badge status-{{ $t->status }}">{{ $t->status }}</span>
                                                            </div>
                                                            <div class="row g-3">
                                                                <div class="col-6">
                                                                    <label class="small text-muted text-uppercase fw-bold d-block">Contributor</label>
                                                                    <span class="small text-dark fw-semibold">{{ $t->customer_name }}</span>
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="small text-muted text-uppercase fw-bold d-block">Phone</label>
                                                                    <span class="small text-dark fw-semibold">{{ $t->customer_phone ?? '-' }}</span>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="small text-muted text-uppercase fw-bold d-block">Email</label>
                                                                    <span class="small text-dark fw-semibold">{{ $t->customer_email ?? '-' }}</span>
                                                                </div>
                                                                <hr class="my-2">
                                                                <div class="col-6">
                                                                    <label class="small text-muted text-uppercase fw-bold d-block">Internal Ref</label>
                                                                    <code class="small">{{ $t->reference }}</code>
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="small text-muted text-uppercase fw-bold d-block">External Ref</label>
                                                                    <code class="small text-dark fw-semibold">{{ $t->external_reference ?? '-' }}</code>
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="small text-muted text-uppercase fw-bold d-block">Paid At</label>
                                                                    <span class="small text-dark fw-semibold">{{ $t->paid_at ? $t->paid_at->format('Y-m-d H:i') : 'N/A' }}</span>
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="small text-muted text-uppercase fw-bold d-block">Event</label>
                                                                    <span class="small text-dark fw-semibold">{{ $t->webhook_event ?? '-' }}</span>
                                                                </div>
                                                                @if($t->failure_reason)
                                                                    <div class="col-12">
                                                                        <label class="small text-muted text-uppercase fw-bold d-block text-danger">Failure Reason</label>
                                                                        <span class="small text-danger fw-semibold">{{ $t->failure_reason }}</span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-top-0 pt-0">
                                                            <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
