@extends('layouts.admin')

@section('title', 'Users')
@section('page_title', 'Users')

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
    <div class="container-fluid">
        <div class="row g-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 fw-bold">Donors & Activity</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="usersTable" class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-3 border-0 small text-uppercase text-muted fw-bold">Name</th>
                                        <th class="border-0 small text-uppercase text-muted fw-bold">Phone</th>
                                        <th class="border-0 small text-uppercase text-muted fw-bold">Email</th>
                                        <th class="border-0 small text-uppercase text-muted fw-bold">Total Paid</th>
                                        <th class="border-0 small text-uppercase text-muted fw-bold">Completed</th>
                                        <th class="border-0 small text-uppercase text-muted fw-bold">Pending</th>
                                        <th class="border-0 small text-uppercase text-muted fw-bold">Failed</th>
                                        <th class="border-0 small text-uppercase text-muted fw-bold">Last Paid</th>
                                        <th class="pe-3 border-0 small text-uppercase text-muted fw-bold">Last Seen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (($users ?? []) as $u)
                                        <tr>
                                            <td class="ps-3 py-3">
                                                <div class="fw-bold text-dark small">{{ $u->customer_name ?? '—' }}</div>
                                            </td>
                                            <td>
                                                <span class="text-muted small">{{ $u->customer_phone ?? '—' }}</span>
                                            </td>
                                            <td>
                                                <span class="text-muted small">{{ $u->customer_email ?? '—' }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-dark small">{{ $currency ?? 'TZS' }} {{ number_format((int) ($u->total_completed ?? 0)) }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill small fw-bold">{{ (int) ($u->completed_count ?? 0) }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning-subtle text-warning px-2 py-1 rounded-pill small fw-bold">{{ (int) ($u->pending_count ?? 0) }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-danger-subtle text-danger px-2 py-1 rounded-pill small fw-bold">{{ (int) ($u->failed_count ?? 0) }}</span>
                                            </td>
                                            <td>
                                                <span class="text-muted small">{{ $u->last_paid_at ? \Carbon\Carbon::parse($u->last_paid_at)->timezone('Africa/Dar_es_Salaam')->format('Y-m-d H:i') : '—' }}</span>
                                            </td>
                                            <td class="pe-3">
                                                <span class="text-muted small">{{ $u->last_seen_at ? \Carbon\Carbon::parse($u->last_seen_at)->timezone('Africa/Dar_es_Salaam')->format('Y-m-d H:i') : '—' }}</span>
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
            $('#usersTable').DataTable({
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
                    searchPlaceholder: "Filter users..."
                }
            });
        });
    </script>
    @endpush
@endsection
