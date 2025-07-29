@extends('adminlte::page')


@section('title', 'Student List - Triconnect')

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
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
        @if (strpos(session('userAccess')->access, 'admin') !== false)
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
                        <i class="fa fa-graduation-cap"></i> Student Management
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('students.create') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus"></i> Add New Student
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Original content from the students index page --}}
                    @if(isset($students) && count($students) > 0)
                        <div class="table-responsive">
                            <table id="studentsTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Student Name</th>
                                        <th>Student Code</th>
                                        <th>Grade/Year</th>
                                        <th>Family</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                        <tr>
                                            <td>{{ $student->id }}</td>
                                            <td>{{ $student->firstname }} {{ $student->lastname }}</td>
                                            <td>{{ $student->family->family_code ?? 'No Family' }}</td>
                                            <td>{{ $student->grade_level ?? 'N/A' }}</td>
                                            <td>{{ $student->family->family_name ?? 'No Family' }}</td>
                                            <td>
                                                <span class="badge badge-{{ $student->status === 'active' ? 'success' : 'warning' }}">
                                                    {{ $student->status ?? 'Unknown' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                                <button class="btn btn-sm btn-danger delete-student" data-id="{{ $student->id }}">
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
                            <i class="fa fa-info-circle"></i> No students found. 
                            <a href="{{ route('students.create') }}" class="alert-link">Add your first student</a>.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable with explicit search configuration
            $('#studentsTable').DataTable({
                responsive: true,
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

            // Delete student functionality
            $('.delete-student').click(function() {
                const studentId = $(this).data('id');
                
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
                            'Student has been deleted.',
                            'success'
                        );
                    }
                });
            });
        });
    </script>
@endpush
        