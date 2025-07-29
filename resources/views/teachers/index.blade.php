@extends('adminlte::page')


@section('title', 'Teacher List - Triconnect')

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
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fa fa-users"></i> Teacher Management
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('teachers.create') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus"></i> Add New Teacher
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Original content from the teachers index page --}}
                    @if(isset($teachers) && count($teachers) > 0)
                        <div class="table-responsive">
                            <table id="teachersTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Teacher Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($teachers as $teacher)
                                        <tr>
                                            <td>{{ $teacher->id }}</td>
                                            <td>{{ $teacher->firstname }} {{ $teacher->lastname }}</td>
                                            <td>{{ $teacher->email }}</td>
                                            <td>{{ $teacher->phone }}</td>
                                            <td>{{ $teacher->subject ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge badge-{{ $teacher->status === 'active' ? 'success' : 'warning' }}">
                                                    {{ $teacher->status ?? 'Unknown' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('teachers.edit', $teacher->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                                <button class="btn btn-sm btn-danger delete-teacher" data-id="{{ $teacher->id }}">
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
                            <i class="fa fa-info-circle"></i> No teachers found. 
                            <a href="{{ route('teachers.create') }}" class="alert-link">Add your first teacher</a>.
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
            $('#teachersTable').DataTable({
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

            // Delete teacher functionality
            $('.delete-teacher').click(function() {
                const teacherId = $(this).data('id');
                
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
                            'Teacher has been deleted.',
                            'success'
                        );
                    }
                });
            });
        });
    </script>
@endpush


