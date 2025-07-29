<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Student List - Triconnect</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --light-gray: #ecf0f1;
            --dark-gray: #7f8c8d;
            --white: #ffffff;
            --shadow: 0 2px 10px rgba(0,0,0,0.1);
            --border-radius: 8px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: var(--primary-color);
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: var(--shadow);
            padding: 0.75rem 1.5rem;
            border: none;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            font-weight: 600;
            font-size: 1.5rem;
            color: var(--white) !important;
            text-decoration: none;
        }

        .navbar-brand img {
            width: 40px;
            height: 40px;
            margin-right: 12px;
            border-radius: 8px;
            background: var(--white);
            padding: 4px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .sidebar {
            width: 280px;
            height: 100vh;
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            padding: 0;
            position: fixed;
            left: 0;
            top: 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background: rgba(255,255,255,0.05);
        }

        .user-panel {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .user-panel .image img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: 3px solid rgba(255,255,255,0.2);
        }

        .user-panel .info {
            margin-left: 12px;
        }

        .user-panel .info a {
            color: var(--white);
            font-weight: 500;
            text-decoration: none;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-item {
            margin: 0;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.5rem;
            color: rgba(255,255,255,0.8) !important;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: var(--white) !important;
            border-left-color: var(--accent-color);
            transform: translateX(5px);
        }

        .nav-link.active {
            background: rgba(52, 152, 219, 0.2);
            color: var(--white) !important;
            border-left-color: var(--accent-color);
        }

        .nav-icon {
            width: 20px;
            margin-right: 12px;
            text-align: center;
        }

        .content-wrapper {
            margin-left: 280px;
            padding: 2rem;
            min-height: 100vh;
        }

        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            background: var(--white);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background: var(--white);
            border-bottom: 1px solid var(--light-gray);
            padding: 1.25rem 1.5rem;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }

        .card-header h5 {
            margin: 0;
            font-weight: 600;
            color: var(--primary-color);
        }

        .card-body {
            padding: 1.5rem;
        }

        .student-card {
            border-left: 4px solid var(--success-color);
        }

        .btn {
            border-radius: 6px;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent-color) 0%, #2980b9 100%);
            box-shadow: 0 2px 8px rgba(52, 152, 219, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.4);
        }

        .table {
            border-radius: var(--border-radius);
            overflow: hidden;
        }

        .table th {
            background: var(--light-gray);
            border: none;
            font-weight: 600;
            color: var(--primary-color);
            padding: 1rem;
        }

        .table td {
            padding: 1rem;
            border-color: var(--light-gray);
            vertical-align: middle;
        }

        .navbar-nav .nav-link {
            color: rgba(255,255,255,0.8) !important;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
            border-radius: 4px;
            margin: 0 0.25rem;
        }

        .navbar-nav .nav-link:hover {
            color: var(--white) !important;
            background: rgba(255,255,255,0.1);
            transform: translateY(-1px);
        }

        .navbar-nav .nav-link i {
            font-size: 1.1rem;
        }

        .text-gray-800 {
            color: var(--primary-color) !important;
        }

        .h3 {
            font-size: 1.75rem;
            font-weight: 600;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .content-wrapper {
                margin-left: 0;
                padding: 1rem;
            }

            .navbar-brand {
                font-size: 1.25rem;
            }

            .navbar-brand img {
                width: 32px;
                height: 32px;
            }
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    @if (strpos(session('userAccess')->access, 'parent') !== false)
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="navbar navbar-expand navbar-dark">
            <div class="container-fluid">
                <a href="/parent/dashboard" class="navbar-brand">
                    <img src="/images/Triconnect.png" alt="Triconnect Logo" onerror="this.onerror=null; this.src='https://via.placeholder.com/40x40/3498db/ffffff?text=T'; console.log('Logo image failed to load');" />
                    <span>Triconnect</span>
                </a>
                
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#" title="Notifications">
                            <i class="fa fa-bell"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" title="Settings">
                            <i class="fa fa-cog"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('userLogout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link" title="Logout" style="background: none; border: none; color: inherit;">
                                <i class="fa fa-sign-out"></i>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h5 class="text-white mb-0">Parent Portal</h5>
            </div>
            
            <div class="user-panel">
                <div class="image">
                    <img src="/images/Triconnect.png" alt="User Image" onerror="this.onerror=null; this.src='https://via.placeholder.com/45x45/3498db/ffffff?text=U';">
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ session('userAccess')->name ?? 'Parent' }}</a>
                    <small class="text-muted">Family Account</small>
                </div>
            </div>

            <nav class="sidebar-nav">
                <ul class="nav nav-pills nav-sidebar flex-column">
                    <li class="nav-item">
                        <a href="/parent/dashboard" class="nav-link">
                            <i class="nav-icon fa fa-dashboard"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/student" class="nav-link active">
                            <i class="nav-icon fa fa-graduation-cap"></i>
                            <span>Student List</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('student.create', ['family_code' => session('userAccess')->userCode]) }}" class="nav-link">
                            <i class="nav-icon fa fa-plus"></i>
                            <span>Add Student</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('student.edit', session('userAccess')->userCode) }}" class="nav-link">
                            <i class="nav-icon fa fa-user"></i>
                            <span>Edit Profile</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <div class="content-wrapper">
            <div class="container-fluid">
                <!-- Page Header -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h1 class="h3 mb-0 text-gray-800">Student List</h1>
                        <p class="text-muted">Manage your students and view their information</p>
                    </div>
                </div>

                <!-- Student List Card -->
                <div class="row">
                    <div class="col-12">
                        <div class="card student-card">
                            <div class="card-header">
                                <h5><i class="fa fa-graduation-cap"></i> My Students</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('students.index') }}" method="GET" class="mb-3">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" placeholder="Search by name, email, or phone" value="{{ request('search') }}">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-search"></i> Search
                                        </button>
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

                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="students">
                                        <thead>
                                            <tr>
                                                <th>First Name</th>
                                                <th>Middle Name</th>
                                                <th>Last Name</th>
                                                <th>Grade Level</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Status</th>
                                                <th>Address</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($students as $student)
                                                <tr>
                                                    <td>{{ $student->firstname }}</td>
                                                    <td>{{ $student->middlename }}</td>
                                                    <td>{{ $student->lastname }}</td>
                                                    <td>{{ $student->grade_level }}</td>
                                                    <td>{{ $student->email ?? 'No Email' }}</td>
                                                    <td>{{ $student->family->phone ?? 'No Phone' }}</td>
                                                    <td>
                                                        <span class="badge badge-{{ $student->status === 'active' ? 'success' : 'warning' }}">
                                                            {{ $student->status }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $student->family->address }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @elseif (strpos(session('userAccess')->access, 'teacher') !== false)
        <p>Teacher Good Morning, {{ session('userAccess')->access }}</p>
    @else
        <p>Access Denied</p>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#students').DataTable({
                responsive: true,
                language: {
                    search: "Search students:",
                    lengthMenu: "Show _MENU_ students per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ students",
                    infoEmpty: "No students available",
                    infoFiltered: "(filtered from _MAX_ total students)"
                },
                pageLength: 10,
                order: [[0, 'asc']] // Sort by first name ascending
            });
        });
    </script>
</body>
</html>
