<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - Principal Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
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

        .notification-item {
            border-left: 4px solid var(--warning-color);
            margin-bottom: 1rem;
            padding: 1rem;
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            transition: transform 0.3s ease;
        }

        .notification-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
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
                            <a href="{{ route('principal.dashboard') }}" class="nav-link">
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
                            <a href="{{ route('principal.notifications') }}" class="nav-link active">
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
                            <h1 class="m-0">Notifications</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('principal.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active">Notifications</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="container-fluid">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Notifications List -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">All Notifications ({{ $notifications->total() }})</h3>
                                    <div class="card-tools">
                                        <a href="{{ route('principal.notifications.create') }}" class="btn btn-primary btn-sm">
                                            <i class="fa fa-plus"></i> Send New Notification
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if($notifications->count() > 0)
                                        @foreach($notifications as $notification)
                                            <div class="notification-item {{ $notification->priority }}">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                            <h5 class="mb-1">{{ $notification->title }}</h5>
                                                            <div>
                                                                <span class="badge {{ $notification->getPriorityBadgeClass() }}">
                                                                    {{ ucfirst($notification->priority) }}
                                                                </span>
                                                                <span class="badge badge-info ml-1">
                                                                    {{ $notification->recipient_count }} recipients
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <p class="mb-2">{{ $notification->message }}</p>
                                                        <div class="text-muted">
                                                            <small>
                                                                <strong>To:</strong> {{ $notification->getRecipientTypeDisplay() }} | 
                                                                <strong>Sent:</strong> {{ $notification->created_at->format('M d, Y g:i A') }} | 
                                                                <strong>Status:</strong> {{ ucfirst($notification->status) }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('principal.notifications.view', $notification->id) }}" 
                                                               class="btn btn-info btn-sm" title="View Details">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                            <button type="button" class="btn btn-danger btn-sm" 
                                                                    onclick="deleteNotification({{ $notification->id }})" 
                                                                    title="Delete">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                        <!-- Pagination -->
                                        <div class="d-flex justify-content-center mt-3">
                                            {{ $notifications->links() }}
                                        </div>
                                    @else
                                        <div class="text-center py-5">
                                            <i class="fa fa-bell fa-3x text-muted mb-3"></i>
                                            <h5>No Notifications</h5>
                                            <p class="text-muted">You haven't sent any notifications yet.</p>
                                            <a href="{{ route('principal.notifications.create') }}" class="btn btn-primary">
                                                <i class="fa fa-plus"></i> Send Your First Notification
                                            </a>
                                        </div>
                                    @endif
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
        });

        function deleteNotification(notificationId) {
            Swal.fire({
                title: 'Delete Notification?',
                text: 'Are you sure you want to delete this notification? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create a form to submit the delete request
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("principal.notifications.delete", ":id") }}'.replace(':id', notificationId);
                    
                    // Add CSRF token
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);
                    
                    // Add method override for DELETE
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    form.appendChild(methodField);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
</body>
</html> 