@extends('adminlte::page')

@section('title', 'Billing Logs')

@section('content_header')
    <h1>Billing Logs</h1>
@endsection

@section('content')
<div class="card mt-4">
    <div class="card-body">
        <table class="table table-bordered table-striped" id="billing_logs">
            <thead class="thead-dark">
                <tr>
                    <th>Family Code</th>
                    <th>Subscription Plan</th>
                    <th>Base Amount</th>
                    <th>Additional Multiplier</th>
                    <th>Amount Due</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($billingLogs as $log)
                    <tr>
                        <td>
                            @if ($log->family && $log->family->parents->isNotEmpty())
                                @foreach ($log->family->parents as $parent)
                                    {{ $parent->fname }} {{ $parent->lname }}<br>
                                @endforeach
                            @else
                                No Parents Available
                            @endif
                        </td>
                        
                        <td>{{ $log->subscription_plan }}</td>
                        <td>{{ number_format($log->base_amount, 2) }}</td>
                        <td>{{ $log->additional_multiplier }}</td>
                        <td>{{ number_format($log->amount_due, 2) }}</td>
                        <td>{{ $log->created_at->format('F j, Y g:i A') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endsection

@section('js')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#billing_logs').DataTable({
                "responsive": true,
                "autoWidth": false,
            });
        });
    </script>
@endsection