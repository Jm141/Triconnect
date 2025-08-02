<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Teacher Dashboard - Triconnect</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
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

        /* Notification styles */
        .notification-dropdown {
            width: 350px !important;
            max-height: 400px;
            overflow-y: auto;
        }

        .notification-dropdown .dropdown-item {
            padding: 0.75rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .notification-dropdown .dropdown-item:last-child {
            border-bottom: none;
        }

        .notification-dropdown .dropdown-item.unread {
            background-color: #f8f9ff;
            border-left: 3px solid #007bff;
        }

        .notification-dropdown .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.7rem;
            min-width: 18px;
            height: 18px;
            line-height: 18px;
            text-align: center;
            z-index: 1000;
            display: none;
            pointer-events: none; /* Allow clicks to pass through to the parent */
            background-color: #dc3545 !important;
            color: white !important;
            border-radius: 50% !important;
            padding: 2px !important;
        }

        .nav-item.dropdown {
            position: relative;
        }

        .nav-link.dropdown-toggle {
            position: relative;
            cursor: pointer;
        }

        .dropdown-menu.show {
            display: block !important;
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
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bell"></i>
                            <span class="badge badge-danger notification-badge" id="notificationBadge" style="display: none;">0</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right notification-dropdown" aria-labelledby="notificationDropdown" style="width: 350px; max-height: 400px; overflow-y: auto;">
                            <div class="dropdown-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Notifications</h6>
                                <button class="btn btn-sm btn-link text-decoration-none" onclick="markAllAsRead()">Mark all read</button>
                            </div>
                            <div class="dropdown-divider"></div>
                            <div id="notificationList">
                                <div class="text-center p-3">
                                    <i class="fa fa-spinner fa-spin"></i> Loading...
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-center" href="{{ route('notifications.index') }}">
                                View All Notifications
                            </a>
                        </div>
                    </li>
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
                                        <div class="col-md-3">
                                            <a href="{{ route('attendance.dashboard') }}" class="btn btn-info btn-block">
                                                <i class="fa fa-users"></i> Attendance Dashboard
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <button onclick="exportAttendanceCSV()" class="btn btn-success btn-block">
                                                <i class="fa fa-download"></i> Download Attendance CSV
                                            </button>
                                        </div>
                                        <!-- <div class="col-md-3 mt-2">
                                            <button onclick="createTestNotification()" class="btn btn-danger btn-block">
                                                <i class="fa fa-bell"></i> Test Notification
                                            </button>
                                        </div>
                                        <div class="col-md-3 mt-2">
                                            <button onclick="debugSession()" class="btn btn-info btn-block">
                                                <i class="fa fa-bug"></i> Debug Session
                                            </button>
                                        </div>-->
                                        <!-- <div class="col-md-3 mt-2">
                                            <button onclick="simpleTest()" class="btn btn-secondary btn-block">
                                                <i class="fa fa-check"></i> Simple Test
                                            </button>
                                        </div>
                                        <div class="col-md-3 mt-2">
                                            <button onclick="testNotificationDetail()" class="btn btn-warning btn-block">
                                                <i class="fa fa-eye"></i> Test Detail Page
                                            </button>
                                        </div>
                                        <div class="col-md-3 mt-2">
                                            <button onclick="testDropdown()" class="btn btn-info btn-block">
                                                <i class="fa fa-list"></i> Test Dropdown
                                            </button>
                                        </div>
                                        <div class="col-md-3 mt-2">
                                            <button onclick="testBadge()" class="btn btn-success btn-block">
                                                <i class="fa fa-bell"></i> Test Badge
                                            </button>
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

            // Initialize notifications
            console.log('Initializing notifications...');
            const badge = document.getElementById('notificationBadge');
            console.log('Badge element found:', badge);
            if (badge) {
                console.log('Badge initial display:', badge.style.display);
                console.log('Badge initial text:', badge.textContent);
            }
            
            // Initialize dropdown functionality
            const notificationDropdown = document.getElementById('notificationDropdown');
            console.log('Notification dropdown element found:', notificationDropdown);
            
            if (notificationDropdown) {
                notificationDropdown.addEventListener('click', function(e) {
                    console.log('Notification dropdown clicked');
                    e.preventDefault();
                    
                    // Manually toggle dropdown if Bootstrap doesn't work
                    const dropdownMenu = this.nextElementSibling;
                    if (dropdownMenu && dropdownMenu.classList.contains('dropdown-menu')) {
                        dropdownMenu.classList.toggle('show');
                        console.log('Dropdown menu toggled manually');
                    }
                });
                
                // Test dropdown functionality
                console.log('Testing dropdown functionality...');
                if (typeof $ !== 'undefined') {
                    console.log('jQuery is available');
                    $(notificationDropdown).dropdown();
                } else {
                    console.log('jQuery is not available, using native Bootstrap');
                }
            }
            
            loadNotifications();
            loadUnreadCount();

            // Refresh notifications every 30 seconds
            setInterval(function() {
                loadNotifications();
                loadUnreadCount();
            }, 30000);
        });

        // Notification functions
        function loadNotifications() {
            console.log('Loading notifications...');
            fetch('{{ route("notifications.recent") }}')
                .then(response => {
                    console.log('Notifications response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Notifications data:', data);
                    const notificationList = document.getElementById('notificationList');
                    console.log('Notification list element:', notificationList);
                    
                    if (data.length === 0) {
                        notificationList.innerHTML = '<div class="text-center p-3 text-muted">No notifications</div>';
                        return;
                    }

                    let html = '';
                    data.forEach(notification => {
                        const isUnread = notification.status === 'unread';
                        const priorityClass = getPriorityBadgeClass(notification.notification.priority);
                        const timeAgo = getTimeAgo(notification.created_at);
                        
                        html += `
                            <a class="dropdown-item ${isUnread ? 'unread' : ''}" href="/notifications/${notification.id}" onclick="markAsRead(${notification.id})">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <h6 class="mb-0 font-weight-bold">${notification.notification.title}</h6>
                                            <span class="badge ${priorityClass} ml-2">${notification.notification.priority.toUpperCase()}</span>
                                        </div>
                                        <p class="mb-1 text-muted small">${notification.notification.message.substring(0, 100)}${notification.notification.message.length > 100 ? '...' : ''}</p>
                                        <small class="text-muted">${timeAgo}</small>
                                    </div>
                                    ${isUnread ? '<div class="ml-2"><span class="badge badge-primary">NEW</span></div>' : ''}
                                </div>
                            </a>
                        `;
                    });
                    
                    notificationList.innerHTML = html;
                    console.log('Notifications loaded successfully');
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                });
        }

        function loadUnreadCount() {
            console.log('Loading unread count...');
            fetch('{{ route("notifications.unread-count") }}')
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Unread count data:', data);
                    const badge = document.getElementById('notificationBadge');
                    console.log('Badge element:', badge);
                    console.log('Badge current display:', badge ? badge.style.display : 'element not found');
                    console.log('Badge current text:', badge ? badge.textContent : 'element not found');
                    
                    if (badge) {
                        if (data.count > 0) {
                            badge.textContent = data.count;
                            badge.style.display = 'inline';
                            console.log('Badge should be visible with count:', data.count);
                            console.log('Badge display after setting:', badge.style.display);
                            console.log('Badge text after setting:', badge.textContent);
                        } else {
                            badge.style.display = 'none';
                            console.log('Badge should be hidden');
                        }
                    } else {
                        console.error('Badge element not found!');
                    }
                })
                .catch(error => {
                    console.error('Error loading unread count:', error);
                });
        }

        function markAsRead(notificationId) {
            fetch(`{{ route('notifications.mark-read', '') }}/${notificationId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadUnreadCount();
                }
            })
            .catch(error => {
                console.error('Error marking notification as read:', error);
            });
        }

        function markAllAsRead() {
            fetch('{{ route("notifications.mark-all-read") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications();
                    loadUnreadCount();
                }
            })
            .catch(error => {
                console.error('Error marking all notifications as read:', error);
            });
        }

        function getPriorityBadgeClass(priority) {
            switch (priority) {
                case 'urgent': return 'badge-danger';
                case 'high': return 'badge-warning';
                case 'medium': return 'badge-info';
                case 'low': return 'badge-secondary';
                default: return 'badge-secondary';
            }
        }

        function getTimeAgo(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diffInSeconds = Math.floor((now - date) / 1000);
            
            if (diffInSeconds < 60) return 'Just now';
            if (diffInSeconds < 3600) return Math.floor(diffInSeconds / 60) + 'm ago';
            if (diffInSeconds < 86400) return Math.floor(diffInSeconds / 3600) + 'h ago';
            return Math.floor(diffInSeconds / 86400) + 'd ago';
        }

        function createTestNotification() {
            fetch('{{ route("notifications.test-create") }}', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Test notification created successfully!');
                    loadNotifications(); // Refresh notifications
                    loadUnreadCount(); // Refresh unread count
                    
                    // Force show badge for testing
                    setTimeout(() => {
                        const badge = document.getElementById('notificationBadge');
                        if (badge) {
                            badge.style.display = 'inline';
                            badge.textContent = '1';
                            console.log('Badge forced to show for testing');
                        }
                    }, 1000);
                } else {
                    alert('Error creating test notification: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error creating test notification:', error);
                alert('Error creating test notification: ' + error.message);
            });
        }

        function debugSession() {
            fetch('{{ route("notifications.debug") }}', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('Debug data:', data);
                alert('Debug data logged to console. Check browser console for details.');
            })
            .catch(error => {
                console.error('Error debugging session:', error);
                alert('Error debugging session: ' + error.message);
            });
        }

        function simpleTest() {
            fetch('{{ route("notifications.simple-test") }}', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('Simple test response:', data);
                alert('Simple test successful! Check console for details.');
            })
            .catch(error => {
                console.error('Error in simple test:', error);
                alert('Error in simple test: ' + error.message);
            });
        }

        function testNotificationDetail() {
            // Get the first notification from the recent notifications
            fetch('{{ route("notifications.recent") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        const firstNotification = data[0];
                        console.log('Testing notification detail for ID:', firstNotification.id);
                        window.location.href = `/notifications/${firstNotification.id}`;
                    } else {
                        alert('No notifications available to test. Create a test notification first.');
                    }
                })
                .catch(error => {
                    console.error('Error getting notifications for testing:', error);
                    alert('Error getting notifications for testing.');
                });
        }

        function testDropdown() {
            console.log('Testing dropdown functionality...');
            const notificationDropdown = document.getElementById('notificationDropdown');
            if (notificationDropdown) {
                if (typeof $ !== 'undefined') {
                    console.log('jQuery is available, testing dropdown...');
                    $(notificationDropdown).dropdown();
                } else {
                    console.log('jQuery is not available, using native Bootstrap dropdown...');
                    // Native Bootstrap dropdown logic (if needed, but not directly in this file)
                }
            } else {
                console.log('Notification dropdown element not found.');
            }
        }

        function testBadge() {
            console.log('Testing badge functionality...');
            const badge = document.getElementById('notificationBadge');
            if (badge) {
                badge.style.display = 'inline';
                console.log('Badge should be visible.');
                console.log('Badge display after setting:', badge.style.display);
            } else {
                console.error('Badge element not found!');
            }
        }

        function exportAttendanceCSV() {
            Swal.fire({
                title: 'Export Attendance Data?',
                text: "This will download all your attendance records as a CSV file.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, download CSV!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Preparing CSV...',
                        text: 'Please wait while we generate your attendance report.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Download the CSV
                    const url = '{{ route("attendance.export-csv") }}';
                    const link = document.createElement('a');
                    link.href = url;
                    link.download = 'attendance_export.csv';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    
                    // Show success message
                    setTimeout(() => {
                        Swal.fire({
                            title: 'Download Complete!',
                            text: 'Your attendance CSV file has been downloaded.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    }, 1000);
                }
            });
        }
    </script>
</body>
</html> 