@extends('adminlte::page')

@section('title', 'Family List')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@section('content_header')
    <h1>Family List</h1>
@endsection

@section('content')
<div class="card">
    <div class="card-header bg-gradient-info">
        <h3 class="card-title">Families</h3>
        <div class="card-tools">
            <a href="{{ route('parents.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Add Family</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="family-table">
                <thead class="thead-light">
                    <tr>
                        <th>Family Code</th>
                        <th>Parent Name</th>
                        <th>Student(s)</th>
                        <th>Status</th>
                        <th>Billing Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($families as $family)
                        <tr>
                            <td>{{ $family->family_code }}</td>  
                            <td><i class="fas fa-user text-info"></i> {{ $family->fname }} {{ $family->lname }}<br><small>{{ $family->number }}</small></td> 
                            <td>
                                <ul class="list-unstyled">
                                    @foreach ($family->students as $student)
                                        <li>{{ $student->firstname }} ({{ $student->year }})</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                @if($family->family && $family->family->status)
                                    <span class="badge {{ $family->family->status == 'Subscribed' ? 'badge-success' : 'badge-danger' }}">{{ $family->family->status }}</span>
                                @else
                                    <span class="badge badge-secondary">No Status</span>
                                @endif
                            </td>
                            <td>
                                @if($family->family)
                                   {{' P.' }} {{ number_format($family->billing_amount, 2) }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('parents.edit', $family->family_code) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                @if($family->status != 'Subscribe')
                                    <form action="{{ route('families.recordPayment', $family->family_code) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm" title="Mark as Paid"><i class="fas fa-check"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        $('#family-table').DataTable({
            "responsive": true,
            "autoWidth": false,
        });
    });
</script>
@endsection