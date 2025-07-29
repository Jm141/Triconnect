<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Principal Dashboard - Triconnect</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
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

        .stats-card {
            border-left: 4px solid var(--accent-color);
            transition: transform 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .notification-item {
            border-left: 4px solid var(--warning-color);
            margin-bottom: 1rem;
            padding: 1rem;
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
        }

        .notification-item.urgent {
            border-left-color: var(--danger-color);
        }

        .notification-item.high {
            border-left-color: var(--warning-color);
        }

        .notification-item.medium {
            border-left-color: var(--accent-color);
        }

        .notification-item.low {
            border-left-color: var(--dark-gray);
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Sidebar Toggle Button -->
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fa fa-bars"></i>
        </button>

        <!-- Navbar -->
        <nav class="navbar navbar-expand navbar-dark bg-primary">
            <div class="container-fluid">
                <a href="{{ route('principal.dashboard') }}" class="navbar-brand">
                    <img src="/images/Triconnect.png" alt="Triconnect Logo" onerror="this.onerror=null; this.src='https://via.placeholder.com/40x40/3498db/ffffff?text=T';">
                    <span>Principal Portal</span>
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
                        <img src="/images/Triconnect.png" class="img-circle elevation-2" alt="User Image" onerror="this.onerror=null; this.src='https://via.placeholder.com/160x160/3498db/ffffff?text=P';">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">{{ session('userAccess')->name ?? 'Principal' }}</a>
                        <small class="text-muted">Principal Account</small>
                    </div>
                </div>

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        <li class="nav-item">
                            <a href="{{ route('principal.dashboard') }}" class="nav-link active">
                                <i class="nav-icon fa fa-dashboard"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('principal.students') }}" class="nav-link">
                                <i class="nav-icon fa fa-graduation-cap"></i>
                                <p>Student List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('principal.teachers') }}" class="nav-link">
                                <i class="nav-icon fa fa-users"></i>
                                <p>Teacher List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('principal.schedules') }}" class="nav-link">
                                <i class="nav-icon fa fa-calendar"></i>
                                <p>Schedules</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('principal.notifications') }}" class="nav-link">
                                <i class="nav-icon fa fa-bell"></i>
                                <p>Notifications</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('principal.notifications.create') }}" class="nav-link">
                                <i class="nav-icon fa fa-plus"></i>
                                <p>Send Notification</p>
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
                            <h1 class="m-0">Principal Dashboard</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('principal.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="container-fluid">
                    <!-- Statistics Cards -->
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info stats-card">
                                <div class="inner">
                                    <h3>{{ $totalStudents }}</h3>
                                    <p>Total Students</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-graduation-cap"></i>
                                </div>
                                <a href="{{ route('principal.students') }}" class="small-box-footer">
                                    View Students <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success stats-card">
                                <div class="inner">
                                    <h3>{{ $totalTeachers }}</h3>
                                    <p>Total Teachers</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-users"></i>
                                </div>
                                <a href="{{ route('principal.teachers') }}" class="small-box-footer">
                                    View Teachers <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning stats-card">
                                <div class="inner">
                                    <h3>{{ $totalSchedules }}</h3>
                                    <p>Active Schedules</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <a href="{{ route('principal.schedules') }}" class="small-box-footer">
                                    View Schedules <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger stats-card">
                                <div class="inner">
                                    <h3>{{ $todaySchedules }}</h3>
                                    <p>Today's Classes</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                                <a href="{{ route('principal.schedules') }}" class="small-box-footer">
                                    View Today's Schedule <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Quick Actions -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Quick Actions</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <a href="{{ route('principal.students') }}" class="btn btn-primary btn-block">
                                                <i class="fa fa-graduation-cap"></i> View Students
                                            </a>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <a href="{{ route('principal.teachers') }}" class="btn btn-success btn-block">
                                                <i class="fa fa-users"></i> View Teachers
                                            </a>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <a href="{{ route('principal.schedules') }}" class="btn btn-info btn-block">
                                                <i class="fa fa-calendar"></i> View Schedules
                                            </a>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <a href="{{ route('principal.notifications.create') }}" class="btn btn-warning btn-block">
                                                <i class="fa fa-bell"></i> Send Notification
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Notifications -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Recent Notifications</h3>
                                    <div class="card-tools">
                                        <a href="{{ route('principal.notifications') }}" class="btn btn-sm btn-primary">
                                            View All
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if($recentNotifications->count() > 0)
                                        @foreach($recentNotifications as $notification)
                                            <div class="notification-item {{ $notification->priority }}">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="mb-1">{{ $notification->title }}</h6>
                                                        <p class="mb-1 text-muted">{{ Str::limit($notification->message, 100) }}</p>
                                                        <small class="text-muted">
                                                            To: {{ $notification->getRecipientTypeDisplay() }} | 
                                                            {{ $notification->created_at->diffForHumans() }}
                                                        </small>
                                                    </div>
                                                    <span class="badge {{ $notification->getPriorityBadgeClass() }}">
                                                        {{ ucfirst($notification->priority) }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted text-center">No recent notifications</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Student Statistics</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="studentChart" style="height: 300px;"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Teacher Statistics</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="teacherChart" style="height: 300px;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
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

            // Load charts
            loadStudentChart();
            loadTeacherChart();
        });

        function loadStudentChart() {
            fetch('{{ route("principal.stats.students") }}')
                .then(response => response.json())
                .then(data => {
                    const ctx = document.getElementById('studentChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Active Students', 'Inactive Students'],
                            datasets: [{
                                data: [data.active, data.inactive],
                                backgroundColor: ['#27ae60', '#e74c3c']
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });
                });
        }

        function loadTeacherChart() {
            fetch('{{ route("principal.stats.teachers") }}')
                .then(response => response.json())
                .then(data => {
                    const ctx = document.getElementById('teacherChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Total Teachers', 'Active Teachers', 'Inactive Teachers'],
                            datasets: [{
                                label: 'Teacher Count',
                                data: [data.total, data.active, data.inactive],
                                backgroundColor: ['#3498db', '#27ae60', '#e74c3c']
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                });
        }
    </script>
</body>
</html> 