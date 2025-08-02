<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Notifications Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap4.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .content-wrapper {
            margin-left: 250px;
        }
        .content {
            padding: 20px;
        }
        .notification-item {
            border-left: 4px solid #ddd;
            margin-bottom: 1rem;
            padding: 1rem;
            background: #fff;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .notification-item:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .notification-item.unread {
            border-left-color: #007bff;
            background: #f8f9ff;
        }
        .notification-item.urgent {
            border-left-color: #dc3545;
        }
        .notification-item.high {
            border-left-color: #ffc107;
        }
        .notification-item.medium {
            border-left-color: #17a2b8;
        }
        .notification-item.low {
            border-left-color: #6c757d;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    @if(session('userAccess'))
        <p>Access: {{ session('userAccess')->access }}</p>

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

    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark navbar-dark">
            <a href="#" class="navbar-brand">Notifications Management</a>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="/images/Triconnect.png" class="img-circle elevation-2" alt="User Image" onerror="this.onerror=null; this.src='https://via.placeholder.com/150/3498db/ffffff?text=T';">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">User Name</a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ route('teacher-list') }}" class="nav-link">
                                <i class="nav-icon fa fa-users"></i>
                                <p>Teacher List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/notifications" class="nav-link active">
                                <i class="nav-icon fa fa-bell"></i>
                                <p>Notifications</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('roomList') }}" class="nav-link">
                                <i class="nav-icon fa fa-building"></i>
                                <p>Room List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('family-list') }}" class="nav-link">
                                <i class="nav-icon fa fa-home"></i>
                                <p>Family List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('student-list') }}" class="nav-link">
                                <i class="nav-icon fa fa-graduation-cap"></i>
                                <p>Student List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('geofence') }}" class="nav-link">
                                <i class="nav-icon fa fa-map-marker-alt"></i>
                                <p>Geofence</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('billing.index') }}" class="nav-link">
                                <i class="nav-icon fa fa-credit-card"></i>
                                <p>Billing Logs</p>
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
                    
                    @if (session('success'))
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: '{{ session('success') }}',
                            });
                        </script>
                    @endif

                    @if (session('error'))
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: '{{ session('error') }}',
                            });
                        </script>
                    @endif

                    <!-- Action Buttons -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-primary" onclick="markAllAsRead()">
                                    <i class="fas fa-check-double"></i> Mark All as Read
                                </button>
                                <button type="button" class="btn btn-success" onclick="createNotification()">
                                    <i class="fas fa-plus"></i> Create Notification
                                </button>
                                <button type="button" class="btn btn-info" onclick="refreshNotifications()">
                                    <i class="fas fa-sync"></i> Refresh
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications Table -->
                    <div class="card">
                        <div class="card-header bg-gradient-info">
                            <h3 class="card-title">All Notifications</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="notificationsTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Title</th>
                                            <th>Message</th>
                                            <th>Priority</th>
                                            <th>Recipients</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Sample data - replace with actual data from controller -->
                                        <tr>
                                            <td><strong>System Maintenance</strong></td>
                                            <td>Scheduled maintenance will occur on Sunday at 2 AM.</td>
                                            <td><span class="badge badge-warning">High</span></td>
                                            <td><span class="badge badge-info">All Users</span></td>
                                            <td><span class="badge badge-success">Sent</span></td>
                                            <td><small>2024-01-15 10:30 AM</small></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-info btn-sm" onclick="viewNotification(1)" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-warning btn-sm" onclick="editNotification(1)" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-danger btn-sm" onclick="deleteNotification(1)" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>New Feature Available</strong></td>
                                            <td>QR code scanning feature is now available for attendance tracking.</td>
                                            <td><span class="badge badge-info">Medium</span></td>
                                            <td><span class="badge badge-primary">Teachers</span></td>
                                            <td><span class="badge badge-success">Sent</span></td>
                                            <td><small>2024-01-14 09:15 AM</small></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-info btn-sm" onclick="viewNotification(2)" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-warning btn-sm" onclick="editNotification(2)" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-danger btn-sm" onclick="deleteNotification(2)" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Payment Reminder</strong></td>
                                            <td>Please complete your monthly payment before the due date.</td>
                                            <td><span class="badge badge-danger">Urgent</span></td>
                                            <td><span class="badge badge-warning">Parents</span></td>
                                            <td><span class="badge badge-warning">Pending</span></td>
                                            <td><small>2024-01-13 02:45 PM</small></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-info btn-sm" onclick="viewNotification(3)" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-warning btn-sm" onclick="editNotification(3)" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-danger btn-sm" onclick="deleteNotification(3)" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#notificationsTable').DataTable({
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'print'
                ],
                language: {
                    search: "Search notifications:",
                    lengthMenu: "Show _MENU_ notifications per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ notifications",
                    infoEmpty: "No notifications available",
                    infoFiltered: "(filtered from _MAX_ total notifications)"
                },
                pageLength: 10,
                order: [[5, 'desc']], // Sort by created date descending
                columnDefs: [
                    {
                        targets: [2, 3, 4], // Priority, Recipients, Status columns
                        className: 'text-center'
                    }
                ]
            });
        });

        function markAllAsRead() {
            Swal.fire({
                title: 'Mark All as Read?',
                text: "This will mark all notifications as read.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, mark all as read!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Add AJAX call to mark all as read
                    Swal.fire('Success!', 'All notifications marked as read.', 'success');
                }
            });
        }

        function createNotification() {
            // Redirect to create notification page
            window.location.href = '/notifications/create';
        }

        function refreshNotifications() {
            location.reload();
        }

        function viewNotification(id) {
            // Redirect to view notification page
            window.location.href = `/notifications/${id}`;
        }

        function editNotification(id) {
            // Redirect to edit notification page
            window.location.href = `/notifications/${id}/edit`;
        }

        function deleteNotification(id) {
            Swal.fire({
                title: 'Delete Notification?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Add AJAX call to delete notification
                    Swal.fire('Deleted!', 'Notification has been deleted.', 'success');
                }
            });
        }
    </script>
</body>
</html> 