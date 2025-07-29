<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - Triconnect</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
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

        .navbar-brand img {
            width: 40px;
            height: 40px;
            margin-right: 12px;
            border-radius: 50%;
            background: var(--white);
            padding: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .main-sidebar {
            transition: width 0.3s ease;
        }

        .main-sidebar.collapsed {
            width: 70px;
        }

        .main-sidebar.collapsed .user-panel .info,
        .main-sidebar.collapsed .nav-link p {
            display: none;
        }

        .main-sidebar.collapsed .user-panel {
            justify-content: center;
            padding: 1rem 0.5rem;
        }

        .main-sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 0.875rem 0.5rem;
        }

        .main-sidebar.collapsed .nav-icon {
            margin-right: 0;
            font-size: 1.2rem;
        }

        .content-wrapper {
            transition: margin-left 0.3s ease;
        }

        .content-wrapper.collapsed {
            margin-left: 70px;
        }

        .sidebar-toggle {
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            background: var(--accent-color);
            border: none;
            color: white;
            padding: 0.5rem;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .sidebar-toggle:hover {
            background: #2980b9;
            transform: scale(1.1);
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    @if (strpos(session('userAccess')->access, 'teacher') !== false)
    <div class="wrapper">
        <!-- Sidebar Toggle Button -->
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fa fa-bars"></i>
        </button>

        <!-- Navbar -->
        <nav class="navbar navbar-expand navbar-dark bg-primary">
            <div class="container-fluid">
                <a href="/teacher/dashboard" class="navbar-brand">
                    <img src="assets{/images/Triconnect.png}" alt="Triconnect Logo" style="width: 30px; height: 30px; margin-right: 10px;">
                    <span></span>
                </a>
                
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <form method="POST" action="{{ route('userLogout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link" style="background: none; border: none; color: inherit;">
                                <i class="fa fa-sign-out"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Sidebar -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4" id="sidebar">
            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="/images/Triconnect.png" class="img-circle elevation-2" alt="User Image" onerror="this.onerror=null; this.src='https://via.placeholder.com/160x160/3498db/ffffff?text=T';">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">{{ session('userAccess')->name ?? 'Teacher' }}</a>
                    </div>
                </div>

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        <li class="nav-item">
                            <a href="/teacher/dashboard" class="nav-link active">
                                <i class="nav-icon fa fa-dashboard"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('schedules.index') }}" class="nav-link">
                                <i class="nav-icon fa fa-calendar"></i>
                                <p>Schedule Management</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('schedules.weekly') }}" class="nav-link">
                                <i class="nav-icon fa fa-calendar-week"></i>
                                <p>Weekly View</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('qr.generate') }}" class="nav-link">
                                <i class="nav-icon fa fa-qrcode"></i>
                                <p>Generate QR Code</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('qr.history') }}" class="nav-link">
                                <i class="nav-icon fa fa-history"></i>
                                <p>Attendance History</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('attendance.dashboard') }}" class="nav-link">
                                <i class="nav-icon fa fa-check-square-o"></i>
                                <p>Attendance</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper" id="contentWrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Teacher Dashboard</h1>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ \App\Models\Schedule::where('teacher_staff_code', session('userAccess')->userCode)->count() }}</h3>
                                    <p>My Schedules</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <a href="{{ route('schedules.index') }}" class="small-box-footer">
                                    View Schedules <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ \App\Models\Schedule::where('teacher_staff_code', session('userAccess')->userCode)->where('status', 'active')->count() }}</h3>
                                    <p>Active Schedules</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-check-circle"></i>
                                </div>
                                <a href="{{ route('schedules.index') }}" class="small-box-footer">
                                    View Details <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>Weekly</h3>
                                    <p>Schedule View</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-calendar-week"></i>
                                </div>
                                <a href="{{ route('schedules.weekly') }}" class="small-box-footer">
                                    View Weekly <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>Attendance</h3>
                                    <p>Management</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-users"></i>
                                </div>
                                <a href="{{ route('attendance.dashboard') }}" class="small-box-footer">
                                    Manage Attendance <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Quick Actions</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <a href="{{ route('schedules.create') }}" class="btn btn-primary btn-block">
                                                <i class="fa fa-plus"></i> Add New Schedule
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('schedules.index') }}" class="btn btn-info btn-block">
                                                <i class="fa fa-list"></i> View All Schedules
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('qr.generate.teacher') }}" class="btn btn-success btn-block">
                                                <i class="fa fa-refresh"></i> Reusable QR Code
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('qr.generate') }}" class="btn btn-warning btn-block">
                                                <i class="fa fa-qrcode"></i> Generate QR Code
                                            </a>
                                        </div>
                                        <!-- <div class="col-md-3">
                                            <a href="{{ route('qr.advanced') }}" class="btn btn-info btn-block">
                                                <i class="fa fa-cogs"></i> Advanced QR Options
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('qr.quick') }}" class="btn btn-danger btn-block">
                                                <i class="fa fa-bolt"></i> Quick QR (15min)
                                            </a>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Recent Schedules</h3>
                                </div>
                                <div class="card-body">
                                    @php
                                        $recentSchedules = \App\Models\Schedule::where('teacher_staff_code', session('userAccess')->userCode)
                                            ->orderBy('created_at', 'desc')
                                            ->limit(5)
                                            ->get();
                                    @endphp
                                    
                                    @if($recentSchedules->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Subject</th>
                                                        <th>Room</th>
                                                        <th>Day</th>
                                                        <th>Time</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($recentSchedules as $schedule)
                                                        <tr>
                                                            <td>{{ $schedule->subject_name }}</td>
                                                            <td>{{ $schedule->room->name ?? 'N/A' }}</td>
                                                            <td>
                                                                @if(is_array($schedule->day_of_week))
                                                                    @foreach($schedule->day_of_week as $day)
                                                                        <span class="badge badge-primary me-1">{{ $day }}</span>
                                                                    @endforeach
                                                                @else
                                                                    <span class="badge badge-primary">{{ $schedule->day_of_week }}</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                                                            <td>
                                                                <span class="badge badge-{{ $schedule->status === 'active' ? 'success' : 'warning' }}">
                                                                    {{ ucfirst($schedule->status) }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted">No schedules found. <a href="{{ route('schedules.create') }}">Create your first schedule</a></p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
        <p>Access Denied</p>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
    <script>
        // Sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const contentWrapper = document.getElementById('contentWrapper');
            
            // Check if sidebar state is saved in localStorage
            const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            
            if (sidebarCollapsed) {
                sidebar.classList.add('collapsed');
                contentWrapper.classList.add('collapsed');
            }

            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                contentWrapper.classList.toggle('collapsed');
                
                // Save state to localStorage
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            });

            // Add tooltips for collapsed sidebar
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('mouseenter', function() {
                    if (sidebar.classList.contains('collapsed')) {
                        const icon = this.querySelector('.nav-icon');
                        const text = this.querySelector('p').textContent;
                        icon.title = text;
                    }
                });
            });
        });
    </script>
</body>
</html> 