@extends('adminlte::page')
@section('title', 'Teacher List')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



@section('content_header')
    <h1>Teacher List</h1>
@endsection


@section('content')

<form action="{{ route('teachers.index') }}" method="GET" class="mb-3">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Search by name, email, or phone" value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Search</button>
    </div>
</form>

@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
        });
    </script>
@endif
    <a href="{{ route('teachers.create') }}" class="btn btn-primary mb-3">Add Teacher</a>
    <table class="table table-bordered" id="teachers">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Middle Name</th>
                <th>Last Name</th>
                {{-- <th>Grade Level</th> --}}
                <th>Email</th>
                <th>Phone</th>
                {{-- <th>Parent</th> --}}
                <th>Status</th>
                <th>Address</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($teachers as $teacher)
                <tr>
                    <td>{{ $teacher->firstname }}</td>
                    <td>{{ $teacher->middlename }}</td>
                    <td>{{ $teacher->lastname }}</td>
                    <td>{{ $teacher->email }}</td>
                    <td>{{ $teacher->phone }}</td>
    
                    <td>{{ $teacher->status }}</td>
                    <td>{{ $teacher->address }}</td>
                    {{-- <td>{{ $teacher->staff_code }}</td> --}}
                    <td>
                        <a href="{{ route('teachers.edit', $teacher->staff_code) }}">Edit</a>

                        {{-- @if ($student->status == 'inactive')
                            <form action="{{ route('students.updateStatus', ['id' => $student->id, 'action' => 'activate']) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-success" style="width: 50px; border: none; padding: 5px 10px; border-radius: 5px;">
                                    <i class="fas fa-check"></i> 
                                </button>
                            </form>
                        @else
                            <form action="{{ route('students.updateStatus', ['id' => $student->id, 'action' => 'deactivate']) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger" style="width: 50px; border: none; padding: 5px 10px; border-radius: 5px;">
                                    <i class="fas fa-times"></i> 
                                </button>
                            </form>
                        @endif --}}
                    </td>
                    
                    
                </tr>
            @endforeach
        </tbody>
    </table>

   
@endsection
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endsection
@section('js')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#teachers').DataTable({
                "responsive": true,
                "autoWidth": false,
            });

        });
    </script>
@endsection


