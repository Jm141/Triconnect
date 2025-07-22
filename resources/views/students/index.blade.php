@extends('adminlte::page')
@section('title', 'Student List')

@section('content_header')
    <h1>Student List</h1>
@endsection

@section('content')
<div class="card">
    <div class="card-header bg-gradient-info">
        <h3 class="card-title">Students</h3>
        <div class="card-tools">
            <a href="{{ route('students.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Add Student</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="students-table">
                <thead class="thead-light">
                    <tr>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Grade Level</th>
                        <th>Parent Phone</th>
                        <th>Status</th>
                        <th>Address</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                        <tr>
                            <td>{{ $student->firstname }}</td>
                            <td>{{ $student->middlename }}</td>
                            <td>{{ $student->lastname }}</td>
                            <td>{{ $student->grade_level }}</td>
                            <td>{{ $student->family->phone ?? 'No Number' }}</td>
                            <td>
                                <span class="badge {{ $student->status == 'active' ? 'badge-success' : 'badge-danger' }}">{{ ucfirst($student->status) }}</span>
                            </td>
                            <td>{{ $student->family->address  ?? 'No Address' }}</td>
                            <td>
                                <a href="{{ route('students.show', $student->family_code) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                @if ($student->status == 'inactive')
                                    <form action="{{ route('students.updateStatus', ['id' => $student->id, 'action' => 'activate']) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" title="Activate"><i class="fas fa-check"></i></button>
                                    </form>
                                @else
                                    <form action="{{ route('students.updateStatus', ['id' => $student->id, 'action' => 'deactivate']) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm" title="Deactivate"><i class="fas fa-times"></i></button>
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
        $('#students-table').DataTable({
            "responsive": true,
            "autoWidth": false,
        });
    });
</script>
@endsection

        