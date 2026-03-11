@extends('layouts.admin')

@section('title', 'Transactions')
@section('page_title', 'Transactions')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .dt-buttons { margin-bottom: 0; }
    .dataTables_filter { display: none; }
    .table thead th { border-top: none; background: #f8f9fa; font-size: 0.75rem; }
    .status-badge { font-weight: 700; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.05em; padding: 5px 10px; border-radius: 999px; }
    .status-completed { background: #dcfce7; color: #166534; }
    .status-pending { background: #fef9c3; color: #854d0e; }
    .status-failed { background: #fee2e2; color: #991b1b; }
    .status-cancelled { background: #f3f4f6; color: #374151; }
    .x-small { font-size: 0.72rem; }
    .filter-card { background: #fff; border-radius: 12px; border: 1px solid #edf2f7; margin-bottom: 24px; }
    .btn-mint { background-color: #2e9e72; color: #fff; border: none; }
    .btn-mint:hover { background-color: #25855f; color: #fff; }
</style>
@endpush

@section('content')
    <!-- Advanced Filters -->
    <div class="filter-card p-4 shadow-sm">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted text-uppercase">Search</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" id="customSearch" class="form-control border-start-0" placeholder="Name, email, or ref...">
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold text-muted text-uppercase">Status</label>
                <select id="statusFilter" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    <option value="completed">Completed</option>
                    <option value="pending">Pending</option>
                    <option value="failed">Failed</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted text-uppercase">Date Range</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-calendar3"></i></span>
                    <input type="text" id="dateFilter" class="form-control border-start-0" placeholder="Select dates...">
                </div>
            </div>
            <div class="col-md-4 text-end">
                <div id="tableButtons" class="d-inline-block"></div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 fw-bold">Contribution Stream</h5>
                        <p class="text-muted small mb-0">Filtered results will be used for printing/exporting.</p>
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
                    @if (session('status') === 'updated')
                        <div class="alert alert-success py-2 border-0 shadow-sm mb-4">
                            <i class="bi bi-check-circle-fill me-2"></i> Transaction updated successfully.
                        </div>
                    @endif
                    @if (session('status') === 'deleted')
                        <div class="alert alert-danger py-2 border-0 shadow-sm mb-4">
                            <i class="bi bi-trash-fill me-2"></i> Transaction has been deleted successfully.
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
                                    <th class="pe-3 small text-uppercase text-muted fw-bold text-end">Actions</th>
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
                                                    <div class="fw-bold text-dark small contributor-name">{{ $t->customer_name }}</div>
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
                                        <td data-sort="{{ $t->paid_at ? $t->paid_at->timestamp : $t->created_at->timestamp }}">
                                            <div class="small">
                                                @if($t->paid_at)
                                                    <div class="fw-bold text-dark date-cell">{{ $t->paid_at->timezone('Africa/Dar_es_Salaam')->format('Y-m-d') }}</div>
                                                    <div class="text-muted x-small">{{ $t->paid_at->timezone('Africa/Dar_es_Salaam')->format('H:i') }}</div>
                                                @else
                                                    <div class="text-muted x-small italic date-cell" data-raw="{{ $t->created_at->format('Y-m-d') }}">Created {{ $t->created_at->diffForHumans() }}</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="pe-3 text-end">
                                            <div class="d-flex justify-content-end gap-2">
                                                <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="modal" data-bs-target="#modal-{{ $t->id }}" title="View Details">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-primary rounded-circle" type="button" data-bs-toggle="modal" data-bs-target="#edit-modal-{{ $t->id }}" title="Edit Transaction">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger rounded-circle" type="button" data-bs-toggle="modal" data-bs-target="#delete-modal-{{ $t->id }}" title="Delete Transaction">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>

                                            <!-- Transaction Edit Modal -->
                                            <div class="modal fade" id="edit-modal-{{ $t->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow">
                                                        <div class="modal-header border-bottom-0 pb-0">
                                                            <h5 class="modal-title fw-bold">Edit Transaction</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('admin.transactions.update', $t->id) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <div class="modal-body text-start">
                                                                <div class="mb-3">
                                                                    <label class="form-label small fw-bold">Contributor Name</label>
                                                                    <input type="text" name="customer_name" class="form-control" value="{{ $t->customer_name }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label small fw-bold">Phone Number</label>
                                                                    <input type="text" name="customer_phone" class="form-control" value="{{ $t->customer_phone }}">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label small fw-bold">Amount ({{ $t->currency }})</label>
                                                                    <input type="number" name="amount" class="form-control" value="{{ (int)$t->amount }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label small fw-bold">Date Paid</label>
                                                                    <input type="date" name="paid_at" class="form-control" value="{{ $t->paid_at ? $t->paid_at->format('Y-m-d') : $t->created_at->format('Y-m-d') }}">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer border-top-0 pt-0">
                                                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Update Transaction</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

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

                                            <!-- Delete Confirmation Modal -->
                                            <div class="modal fade" id="delete-modal-{{ $t->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                                    <div class="modal-content border-0 shadow">
                                                        <div class="modal-body text-center p-4">
                                                            <div class="text-danger mb-3">
                                                                <i class="bi bi-exclamation-triangle-fill display-4"></i>
                                                            </div>
                                                            <h5 class="fw-bold mb-2">Delete Transaction?</h5>
                                                            <p class="text-muted small mb-4">This action cannot be undone. This will remove the record from your database.</p>
                                                            
                                                            <form action="{{ route('admin.transactions.destroy', $t->id) }}" method="POST">
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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        $(document).ready(function() {
            // Custom Date Range Filtering
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    const dateRange = $('#dateFilter').val();
                    if (!dateRange || !dateRange.includes(' to ')) return true;
                    
                    const [startStr, endStr] = dateRange.split(' to ');
                    const start = new Date(startStr);
                    const end = new Date(endStr);
                    
                    // Get date from table cell (using data-raw or text)
                    const cell = $(settings.aoData[dataIndex].nTr).find('.date-cell');
                    const rowDateStr = cell.data('raw') || cell.text().trim();
                    const rowDate = new Date(rowDateStr);

                    return (rowDate >= start && rowDate <= end);
                }
            );

            const table = $('#transactionsTable').DataTable({
                dom: 'rtip',
                buttons: [
                    {
                        extend: 'print',
                        className: 'btn btn-sm btn-outline-secondary rounded-pill px-3 me-2',
                        text: '<i class="bi bi-printer me-1"></i> Print Filtered',
                        exportOptions: { columns: [0, 1, 2, 3, 4] }
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-sm btn-outline-success rounded-pill px-3 me-2',
                        text: '<i class="bi bi-file-earmark-excel me-1"></i> Excel',
                        exportOptions: { columns: [0, 1, 2, 3, 4] }
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-sm btn-outline-danger rounded-pill px-3',
                        text: '<i class="bi bi-file-earmark-pdf me-1"></i> PDF',
                        exportOptions: { columns: [0, 1, 2, 3, 4] }
                    }
                ],
                order: [[4, 'desc']],
                pageLength: 25,
                language: {
                    emptyTable: "No transactions found matching your filters"
                }
            });

            // Move buttons to custom container
            table.buttons().container().appendTo('#tableButtons');

            // Custom Search
            $('#customSearch').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Status Filter
            $('#statusFilter').on('change', function() {
                table.column(2).search(this.value).draw();
            });

            // Date Range Filter (Flatpickr)
            flatpickr("#dateFilter", {
                mode: "range",
                dateFormat: "Y-m-d",
                onClose: function() {
                    table.draw();
                }
            });
        });
    </script>
    @endpush
@endsection
