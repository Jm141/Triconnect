@extends('adminlte::page')

@section('title', 'Billing Logs - Triconnect')

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap4.min.css">
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fa fa-file-invoice-dollar"></i> Billing Logs Management
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('billing.generate-all') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus"></i> Generate All Billing
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Original content from the billing logs index page --}}
                    @if(isset($billingLogs) && count($billingLogs) > 0)
                        <div class="table-responsive">
                            <table id="billingLogsTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Family</th>
                                        <th>Subscription Plan</th>
                                        <th>Amount Due</th>
                                        <th>Status</th>
                                        <th>Billing Date</th>
                                        <th>Due Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($billingLogs as $billing)
                                        <tr>
                                            <td>{{ $billing->id }}</td>
                                            <td>
                                                <strong>{{ $billing->family->family_name ?? 'N/A' }}</strong>
                                                <br>
                                                <small class="text-muted">Code: {{ $billing->family_code }}</small>
                                            </td>
                                            <td>{{ $billing->subscription_plan ?? 'N/A' }}</td>
                                            <td>₱{{ number_format($billing->amount_due, 2) }}</td>
                                            <td>
                                                <span class="badge badge-{{ $billing->status === 'paid' ? 'success' : ($billing->status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($billing->status ?? 'Unknown') }}
                                                </span>
                                            </td>
                                            <td>{{ $billing->billing_date ? $billing->billing_date->format('M d, Y') : 'N/A' }}</td>
                                            <td>{{ $billing->due_date ? $billing->due_date->format('M d, Y') : 'N/A' }}</td>
                                            <td>
                                                @if($billing->status === 'pending')
                                                    <a href="{{ route('billing.mark-paid', $billing->id) }}" class="btn btn-sm btn-success" onclick="return confirm('Mark as paid?')">
                                                        <i class="fa fa-check"></i> Mark Paid
                                                    </a>
                                                @else
                                                    <a href="{{ route('billing.mark-pending', $billing->id) }}" class="btn btn-sm btn-warning" onclick="return confirm('Mark as pending?')">
                                                        <i class="fa fa-clock-o"></i> Mark Pending
                                                    </a>
                                                @endif
                                                <a href="{{ route('billing.delete', $billing->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Delete this billing record?')">
                                                    <i class="fa fa-trash"></i> Delete
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> No billing logs found. 
                            <a href="{{ route('billing.generate-all') }}" class="alert-link">Generate billing for all families</a>.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Billing Statistics --}}
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">Total Bills</h4>
                            <h2 class="mb-0">{{ $billingLogs->count() ?? 0 }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fa fa-file-invoice fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">Paid Bills</h4>
                            <h2 class="mb-0">{{ $billingLogs->where('status', 'paid')->count() ?? 0 }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fa fa-check-circle fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">Pending Bills</h4>
                            <h2 class="mb-0">{{ $billingLogs->where('status', 'pending')->count() ?? 0 }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fa fa-clock-o fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">Overdue Bills</h4>
                            <h2 class="mb-0">{{ $billingLogs->where('status', 'overdue')->count() ?? 0 }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fa fa-exclamation-triangle fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#billingLogsTable').DataTable({
                responsive: true,
                language: {
                    search: "Search billing logs:",
                    lengthMenu: "Show _MENU_ billing logs per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ billing logs",
                    infoEmpty: "No billing logs available",
                    infoFiltered: "(filtered from _MAX_ total billing logs)"
                },
                pageLength: 10,
                order: [[0, 'desc']] // Sort by ID descending
            });
        });
    </script>
@endpush