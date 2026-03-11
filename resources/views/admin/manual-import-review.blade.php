@extends('layouts.admin')

@section('title', 'Review Import')
@section('page_title', 'Review Import')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Review Manual Donations Import</h5>
                    <p class="text-muted small mb-0">Please check the data below before confirming the import.</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>Full Name</th>
                                    <th>Phone Number</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalAmount = 0; @endphp
                                @foreach($importData as $index => $item)
                                    @php $totalAmount += $item['amount']; @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="fw-bold">{{ $item['name'] }}</td>
                                        <td>{{ $item['phone'] }}</td>
                                        <td class="text-success fw-bold">{{ number_format($item['amount']) }}</td>
                                        <td>{{ $item['date'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="3" class="text-end">Total to Import:</th>
                                    <th class="text-success fs-5">{{ number_format($totalAmount) }}</th>
                                    <th>{{ count($importData) }} Records</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <form action="{{ route('admin.transactions.manual.confirm') }}" method="POST" class="mt-4">
                        @csrf
                        <input type="hidden" name="import_json" value="{{ json_encode($importData) }}">
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('admin.transactions.manual') }}" class="btn btn-light border px-4">
                                <i class="bi bi-arrow-left me-1"></i> Cancel & Go Back
                            </a>
                            <button type="submit" class="btn btn-success px-5 fw-bold shadow-sm">
                                <i class="bi bi-cloud-upload me-1"></i> Confirm & Upload to Server
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
