@extends('adminlte::page')


@section('title', 'Family List - Triconnect')

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap4.min.css">
    <style>
        .dataTables_wrapper .dataTables_filter {
            display: block !important;
            visibility: visible !important;
            margin-bottom: 10px;
        }
        .dataTables_wrapper .dataTables_filter input {
            margin-left: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
        }
        .dataTables_wrapper .dataTables_length {
            display: block !important;
            visibility: visible !important;
            margin-bottom: 10px;
        }
    </style>
@endpush

@section('content')
    @if(session('userAccess'))
        @if (strpos(session('userAccess')->access, 'super_admin') !== false)
            <p>Welcome, Super Admin!</p>
        @elseif (strpos(session('userAccess')->access, 'admin') !== false)
            <p>Welcome, Admin!</p>
        @elseif (strpos(session('userAccess')->access, 'teacher') !== false)
            <p>Teacher Good Morning, {{ session('userAccess')->access }}</p>
        @else
            <p>Access Denied</p>
        @endif
    @else
        <p>No access information available</p>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fa fa-home"></i> Family Management
                        @if(isset($userRole) && $userRole === 'super_admin')
                            <span class="badge badge-danger">Super Admin</span>
                        @elseif(isset($userRole) && $userRole === 'admin')
                            <span class="badge badge-warning">Admin</span>
                        @endif
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('families.create') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus"></i> Add New Family
                        </a>
                        @if(isset($userRole) && $userRole === 'super_admin')
                            <a href="{{ route('billing.index') }}" class="btn btn-success btn-sm">
                                <i class="fa fa-credit-card"></i> Billing Management
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    {{-- Original content from the families index page --}}
                    @if(isset($families) && count($families) > 0)
                        <div class="table-responsive">
                            <table id="familiesTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Family Name</th>
                                        <th>Student(s)</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                        <th>Total Bill</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($families as $family)
                                        <tr>
                                            <td>{{ $family->family_code }}</td>
                                            <td>{{ $family->fname }} {{ $family->lname }}</td>
                                            <td>
                                                @if($family->students && count($family->students) > 0)
                                                    @foreach($family->students as $student) 
                                                        <span class="badge badge-info">{{ $student->firstname }} {{ $student->lastname }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">No students</span>
                                                @endif
                                            </td>
                                            <td>{{ $family->number ?? 'N/A' }}</td>
                                            <td>{{ $family->email ?? 'N/A' }}</td>
                                            <td>{{ $family->family ? $family->family->address : 'N/A' }}</td>
                                            <td>
                                                @if($family->billing_amount > 0)
                                                    <strong class="text-primary">₱{{ number_format($family->billing_amount, 2) }}</strong>
                                                @else
                                                    <span class="text-muted">₱0.00</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($family->family)
                                                    <span class="badge badge-{{ $family->family->status === 'Paid' ? 'success' : ($family->family->status === 'Subscribe' ? 'info' : 'warning') }}">
                                                        {{ $family->family->status }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary">Unknown</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('families.edit', $family->family_code) }}" class="btn btn-sm btn-info">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                                
                                                {{-- Role-based billing actions --}}
                                                @if(isset($userRole) && $userRole === 'super_admin')
                                                    {{-- Super Admin: Use BillingLogController for advanced billing --}}
                                                    @if($family->family && $family->family->status !== 'Paid' && $family->billing_amount > 0)
                                                        <a href="{{ route('billing.generate-family', $family->family_code) }}" class="btn btn-sm btn-warning" onclick="return confirm('Generate billing for this family?')">
                                                            <i class="fa fa-file-invoice"></i> Generate Bill
                                                        </a>
                                                    @endif
                                                @elseif(isset($userRole) && $userRole === 'admin')
                                                    {{-- Regular Admin: Use AdminController for simple payment recording --}}
                                                    @if($family->family && $family->family->status !== 'Paid' && $family->billing_amount > 0)
                                                        <form action="{{ route('admin.recordPayment', $family->family_code) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to mark this payment as received?')">
                                                                <i class="fa fa-credit-card"></i> Pay
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif
                                                
                                                <button class="btn btn-sm btn-danger delete-family" data-id="{{ $family->family_code }}">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> No families found. 
                            <a href="{{ route('families.create') }}" class="alert-link">Add your first family</a>.
                        </div>
                    @endif
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
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#familiesTable').DataTable({
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                searching: true,
                ordering: true,
                info: true,
                lengthChange: true,
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    infoFiltered: "(filtered from _MAX_ total entries)"
                }
            });

            // Delete family functionality
            $('.delete-family').click(function() {
                const familyId = $(this).data('id');
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Add your delete logic here
                        Swal.fire(
                            'Deleted!',
                            'Family has been deleted.',
                            'success'
                        );
                    }
                });
            });
        });
    </script>
@endpush