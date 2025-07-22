<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Teacher List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .content-wrapper {
            margin-left: 250px;
        }

        .content {
            padding: 20px;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <p>Access: {{ session('userAccess')->access }}</p>

                    @if (strpos(session('userAccess')->access, 'admin') !== false)
                        <p>Welcome, Admin!</p>

    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark navbar-dark">
            <a href="#" class="navbar-brand">Student</a>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="https://via.placeholder.com/150" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">User Name</a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="/teacher-list" class="nav-link">
                                <i class="nav-icon fa fa-users"></i>
                                <p>Teacher List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/notifications" class="nav-link">
                                <i class="nav-icon fa fa-bell"></i>
                                <p>Notifications</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/roomList" class="nav-link">
                                <i class="nav-icon fa fa-building"></i>
                                <p>Room List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/family-list" class="nav-link">
                                <i class="nav-icon fa fa-home"></i>
                                <p>Family List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/student-list" class="nav-link">
                                <i class="nav-icon fa fa-graduation-cap"></i>
                                <p>Student List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/subscription" class="nav-link">
                                <i class="nav-icon fa fa-graduation-cap"></i>
                                <p>Subscription List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/billing" class="nav-link">
                                <i class="nav-icon fa fa-graduation-cap"></i>
                                <p>Billing Log List</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    
                        {{-- <form action="{{ route('students.index') }}" method="GET" class="mb-3">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Search by name, email, or phone" value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </form> --}}
                        
                        @if (session('success'))
                            <script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: '{{ session('success') }}',
                                });
                            </script>
                        @endif
                            <a href="addStudent" class="btn btn-primary mb-3">Add Student</a>
                            <table class="table table-bordered" id="students">
                                <thead>
                                    <tr>
                                        <th>First Name</th>
                                        <th>Middle Name</th>
                                        <th>Last Name</th>
                                        <th>Grade Level</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        {{-- <th>Parent</th> --}}
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
                                            <td>{{ $student->parents->email }}</td>
                                            <td>{{ $student->parents->number }}</td>
                                            {{-- <td>{{ $student->parents->fname ?? 'No Parent' }}</td> --}}
                                            {{-- <td>{{ $student->family->parents->number ?? 'No Number' }}</td> --}}
                            
                                            <td>{{ $student->status }}</td>
                                            <td>{{ $student->family->ddress }}</td>
                                            <td>
                                                <a href="{{ route('students.show', $student->id) }}" class="btn btn-warning" style="width: 50px;">
                                                    <i class="fas fa-edit"></i> 
                                                </a>
                                                @if ($student->status == 'inactive')
                                                    <form action="{{ route('admins.updateStatus', ['id' => $student->id, 'action' => 'activate']) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success" style="width: 50px; border: none; padding: 5px 10px; border-radius: 5px;">
                                                            <i class="fas fa-check"></i> 
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('admins.updateStatus', ['id' => $student->id, 'action' => 'deactivate']) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger" style="width: 50px; border: none; padding: 5px 10px; border-radius: 5px;">
                                                            <i class="fas fa-times"></i> 
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                            
                                            
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        
                    @elseif (strpos(session('userAccess')->access, 'teacher') !== false)
                        <p>Teacher Good Morning, {{ session('userAccess')->access }}</p>
                    @else
                        <p>Access Denied</p>
                    @endif

                   
                </div>
            </section>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#students').DataTable({
                "responsive": true,
                "autoWidth": false,
            });

        });
    </script>

</body>
</html>
