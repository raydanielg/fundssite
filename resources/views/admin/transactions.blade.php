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
    .table thead th { border-top: none; background: #f8f9fa; font-size: 0.8rem; padding: 12px 15px; color: #4a5568; }
    .status-badge { font-weight: 700; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.05em; padding: 6px 12px; border-radius: 50px; display: inline-block; }
    .status-completed { background: #d1fae5; color: #065f46; border: 1px solid #10b981; }
    .status-pending { background: #fef3c7; color: #92400e; border: 1px solid #f59e0b; }
    .status-active { background: #e0f2fe; color: #075985; border: 1px solid #0ea5e9; }
    .status-failed { background: #fee2e2; color: #991b1b; border: 1px solid #ef4444; }
    .status-cancelled { background: #f3f4f6; color: #374151; border: 1px solid #6b7280; }
    .avatar-sm { box-shadow: 0 2px 4px rgba(0,0,0,0.05); transition: transform 0.2s; }
    .avatar-sm:hover { transform: scale(1.1); }
    .tx-amount { font-size: 0.95rem; letter-spacing: -0.01em; }
    .filter-card { background: #ffffff; border-radius: 16px; border: 1px solid #edf2f7; margin-bottom: 24px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
    .btn-mint { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #fff; border: none; border-radius: 10px; font-weight: 600; transition: all 0.3s; }
    .btn-mint:hover { background: linear-gradient(135deg, #059669 0%, #047857 100%); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2); }
    .card { border-radius: 16px; overflow: hidden; }
    .table-hover tbody tr:hover { background-color: #f8fafc; cursor: pointer; }
    .btn-action { width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s; }
    .btn-action:hover { transform: translateY(-2px); }
    .btn-outline-dark { border-radius: 8px; }
    .rounded-pill { border-radius: 8px !important; }
    .btn-outline-secondary { border-radius: 8px !important; border-color: #e2e8f0; color: #475569; }
    .btn-outline-success { border-radius: 8px !important; border-color: #c6f6d5; color: #2f855a; }
    .btn-outline-danger { border-radius: 8px !important; border-color: #fed7d7; color: #c53030; }
    .btn-primary.rounded-pill { border-radius: 8px !important; }
    .btn-danger.rounded-pill { border-radius: 8px !important; }
    .btn-light.rounded-pill { border-radius: 8px !important; }
</style>
@endpush

@section('content')
    <div class="filter-card p-4 shadow-sm">
        <div class="row g-4 align-items-end">
            <div class="col-lg-5 col-md-6 position-relative">
                <label class="form-label small text-uppercase fw-bold">Search Contributor</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" id="customSearch" class="form-control border-start-0 ps-0" placeholder="Type name or phone..." autocomplete="off">
                </div>
                <div id="searchSuggestions" class="list-group position-absolute w-100 shadow-lg mt-1 d-none" style="z-index: 1050; max-height: 250px; overflow-y: auto; border-radius: 12px; border: 1px solid #edf2f7;">
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <label class="form-label small text-uppercase fw-bold">Status</label>
                <select id="statusFilter" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="completed">Completed</option>
                    <option value="pending">Pending</option>
                    <option value="active">Active</option>
                    <option value="failed">Failed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="col-lg-4 col-md-12 text-md-end">
                <label class="form-label d-block small text-uppercase fw-bold">Tools</label>
                <div id="tableButtons" class="d-inline-flex gap-2"></div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="card-title mb-0 fw-bold">Contribution Stream</h5>
                            <span id="tx-live-pill" class="badge text-bg-light border" style="font-weight:800">Live</span>
                        </div>
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
                                @foreach ($transactions as $t)
                                    <tr data-tx-id="{{ $t->id }}">
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
                                            <div class="fw-bold text-dark small tx-amount">{{ $t->currency ?? 'TZS' }} {{ number_format((int) $t->amount) }}</div>
                                            @if($t->external_reference)
                                                <div class="text-muted x-small">Ref: {{ $t->external_reference }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="status-badge tx-status status-{{ $t->status }}">
                                                {{ $t->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="small text-dark tx-event">{{ $t->webhook_event ? str_replace(['checkout.session.', 'payment.'], '', $t->webhook_event) : '-' }}</div>
                                            <code class="x-small text-muted" style="font-size: 0.65rem;">{{ $t->reference }}</code>
                                        </td>
                                        <td data-sort="{{ $t->paid_at ? $t->paid_at->timestamp : $t->created_at->timestamp }}">
                                            <div class="small">
                                                @if($t->paid_at)
                                                    <div class="fw-bold text-dark date-cell tx-date" data-raw="{{ $t->paid_at->timezone('Africa/Dar_es_Salaam')->format('Y-m-d') }}">{{ $t->paid_at->timezone('Africa/Dar_es_Salaam')->format('Y-m-d') }}</div>
                                                    <div class="text-muted x-small">{{ $t->paid_at->timezone('Africa/Dar_es_Salaam')->format('H:i') }}</div>
                                                @else
                                                    <div class="text-muted x-small italic date-cell tx-date" data-raw="{{ $t->created_at->format('Y-m-d') }}">Created {{ $t->created_at->diffForHumans() }}</div>
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
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals (Outside Table for Better UI/Accessibility) -->
    @foreach ($transactions as $t)
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
                                <label class="form-label small fw-bold">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="completed" @selected($t->status === 'completed')>Completed</option>
                                    <option value="pending" @selected($t->status === 'pending')>Pending</option>
                                    <option value="active" @selected($t->status === 'active')>Active</option>
                                    <option value="failed" @selected($t->status === 'failed')>Failed</option>
                                    <option value="cancelled" @selected($t->status === 'cancelled')>Cancelled</option>
                                </select>
                                <div class="form-text">Pending/Active will remove paid date.</div>
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
    @endforeach
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
                paging: false,
                info: false,
                order: [[4, 'desc']],
                dom: 'Brt',
                buttons: [
                    {
                        extend: 'print',
                        className: 'btn btn-sm btn-dark rounded-pill px-4 shadow-sm',
                        text: '<i class="bi bi-printer me-2"></i>Print Report',
                        title: 'Traction Report - ' + new Date().toLocaleDateString(),
                        messageTop: 'Official System Transaction Report',
                        exportOptions: { columns: [0, 1, 2, 3, 4] },
                        customize: function (win) {
                            $(win.document.body).css('font-size', '10pt').prepend('<h2 class="text-center mb-4">Selemani Fundraiser Traction Report</h2>');
                            $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                        }
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-sm btn-success rounded-pill px-4 shadow-sm',
                        text: '<i class="bi bi-file-earmark-excel me-2"></i>Export Excel',
                        filename: 'Traction_Report_' + new Date().getTime(),
                        exportOptions: { columns: [0, 1, 2, 3, 4] }
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-sm btn-danger rounded-pill px-4 shadow-sm',
                        text: '<i class="bi bi-file-earmark-pdf me-2"></i>Download PDF',
                        filename: 'Traction_Report_' + new Date().getTime(),
                        exportOptions: { columns: [0, 1, 2, 3, 4] },
                        customize: function(doc) {
                            doc.content[1].table.widths = ['*', 'auto', 'auto', 'auto', 'auto'];
                            doc.styles.tableHeader.fillColor = '#2e9e72';
                        }
                    }
                ],
                order: [[4, 'desc']],
                pageLength: 25,
                language: {
                    emptyTable: "No transactions found matching your filters"
                },
                drawCallback: function(settings) {
                    // This ensures that the table stays sorted even when new data arrives via AJAX
                    const api = this.api();
                    if (api.order()[0][0] !== 4) {
                        // If user hasn't manually changed sort, keep it by date desc
                    }
                },
                createdRow: function(row, data, dataIndex) {
                    $(row).attr('data-tx-id', data.id);
                }
            });

            // Move buttons to custom container
            table.buttons().container().appendTo('#tableButtons');

            // Custom Search & Suggestions
            const $searchInput = $('#customSearch');
            const $suggestions = $('#searchSuggestions');

            $searchInput.on('input', function() {
                const query = $(this).val().toLowerCase();
                table.search(query).draw();

                if (query.length < 2) {
                    $suggestions.addClass('d-none');
                    return;
                }

                // Get unique names from current table data
                const names = [...new Set(table.rows({search:'applied'}).data().toArray().map(r => r.customer_name))];
                const matches = names.filter(n => n.toLowerCase().includes(query)).slice(0, 5);

                if (matches.length > 0) {
                    $suggestions.empty().removeClass('d-none');
                    matches.forEach(name => {
                        $suggestions.append(`<button type="button" class="list-group-item list-group-item-action py-2 px-3 border-0 small suggestion-item">${name}</button>`);
                    });
                } else {
                    $suggestions.addClass('d-none');
                }
            });

            $(document).on('click', '.suggestion-item', function() {
                const name = $(this).text();
                $searchInput.val(name);
                table.search(name).draw();
                $suggestions.addClass('d-none');
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('.position-relative').length) {
                    $suggestions.addClass('d-none');
                }
            });

            // Status Filter
            $('#statusFilter').on('change', function() {
                table.column(2).search(this.value).draw();
            });

            // Live polling
            let busy = false;
            let timer = null;
            let knownIds = new Set();
            
            function updateKnownIds() {
                knownIds = new Set($('#transactionsTable tbody tr').map(function(){ return String($(this).data('tx-id')); }).get());
            }
            updateKnownIds();

            const fmt = (n) => (parseInt(n || 0, 10) || 0).toLocaleString('en-TZ');

            async function poll() {
                if (busy) return;
                if (document.visibilityState === 'hidden') return;
                
                // If user is actively typing in search or using filters, slow down polling or pause
                const isSearching = $('#customSearch').val().length > 0 || ($('#statusFilter').val() && $('#statusFilter').val() !== '');
                if (isSearching) return; 

                busy = true;
                const pill = document.getElementById('tx-live-pill');
                if (pill) {
                    pill.textContent = 'Updating...';
                    pill.classList.replace('text-bg-light', 'text-bg-success');
                }

                try {
                    // Check for new transactions silently without reloading the whole table
                    // If we find new ones, we can notify or refresh
                    const res = await fetch('{{ route('admin.api.live') }}', { headers: { 'Accept': 'application/json' } });
                    if (!res.ok) throw new Error('Network error');
                    
                    const data = await res.json();
                    
                    // Logic to detect if we need a refresh could go here
                    // For now, we just update the indicator
                    
                    setTimeout(() => {
                        if (pill) {
                            pill.textContent = 'Live';
                            pill.classList.replace('text-bg-success', 'text-bg-light');
                        }
                    }, 1000);
                } catch (e) {
                    if (pill) {
                        pill.textContent = 'Offline';
                        pill.classList.replace('text-bg-light', 'text-bg-danger');
                    }
                } finally {
                    busy = false;
                }
            }

            function start() {
                if (timer) return;
                timer = setInterval(poll, 3000); // Polling every 3s is better for UX than 1s
                poll();
                
                // Full table redraw every 60 seconds to ensure total consistency
                setInterval(() => {
                    if (document.visibilityState === 'hidden') return;
                    table.ajax.reload(null, false);
                }, 60000);
                
                // Also poll for Snippe sync in background every 5 seconds (faster sync)
                setInterval(async () => {
                    if (document.visibilityState === 'hidden') return;
                    try {
                        await fetch('{{ route('admin.api.sync') }}', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ limit: 20 })
                        });
                    } catch (e) {}
                }, 5000);
            }

            function stop() {
                if (!timer) return;
                clearInterval(timer);
                timer = null;
            }

            document.addEventListener('visibilitychange', () => {
                if (document.visibilityState === 'hidden') stop();
                else start();
            });

            start();
        });
    </script>
    @endpush
@endsection
