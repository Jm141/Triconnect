<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Parent Dashboard - Triconnect</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    
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

        .alert {
            border: none;
            border-radius: var(--border-radius);
            padding: 1rem 1.25rem;
        }

        .alert-info {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            color: #0c5460;
            border-left: 4px solid var(--accent-color);
        }

        .student-card {
            border-left: 4px solid var(--success-color);
        }

        .location-card {
            border-left: 4px solid var(--accent-color);
        }

        .map-container {
            height: 400px;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: inset 0 0 10px rgba(0,0,0,0.1);
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

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        .badge {
            border-radius: 4px;
            font-weight: 500;
            padding: 0.375rem 0.75rem;
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

        .text-muted {
            color: var(--dark-gray) !important;
        }

        .address-cell {
            display: flex;
            align-items: center;
        }

        .address-cell .fa-spinner {
            margin-right: 8px;
            color: var(--accent-color);
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

        .bg-light {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
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
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="navbar navbar-expand navbar-dark">
            <div class="container-fluid">
                <a href="/parent/dashboard" class="navbar-brand">
                    <img src="{{ asset('images/Triconnect.png') }}" alt="Triconnect Logo" />
                    <span>Triconnect</span>
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
                    <img src="/images/Triconnect.png" alt="User Image" onerror="this.onerror=null; this.src='https://via.placeholder.com/150/3498db/ffffff?text=T';">
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ session('userAccess')->name ?? 'Parent' }}</a>
                    <small class="text-muted">Family Account</small>
                </div>
            </div>

            <nav class="sidebar-nav">
                <ul class="nav nav-pills nav-sidebar flex-column">
                    <li class="nav-item">
                        <a href="/parent/dashboard" class="nav-link active">
                            <i class="nav-icon fa fa-dashboard"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/student" class="nav-link">
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
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <p class="text-muted">Monitor your students' activities and locations</p>
                    </div>
                </div>

                <!-- Family Code Info -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-home fa-2x mr-3"></i>
                                <div>
                                    <h5 class="mb-1">Family Code: {{ $familyCode }}</h5>
                                    <p class="mb-0">You can track all students under this family code.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Students Overview -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card student-card">
                            <div class="card-header">
                                <h5><i class="fa fa-users"></i> My Students ({{ $students->count() }})</h5>
                            </div>
                            <div class="card-body">
                                @forelse ($students as $student)
                                    <div class="d-flex justify-content-between align-items-center mb-3 p-3 border rounded bg-light">
                                        <div>
                                            <strong>{{ $student->firstname }} {{ $student->lastname }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                Grade: {{ $student->grade_level ?? 'N/A' }} | 
                                                Age: {{ $student->age ?? 'N/A' }}
                                            </small>
                                        </div>
                                        <a href="{{ route('parent.student.location', $student->family_code) }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fa fa-map-marker"></i> Track Location
                                        </a>
                                    </div>
                                @empty
                                    <div class="text-center text-muted py-4">
                                        <i class="fa fa-users fa-3x mb-3"></i>
                                        <p>No students found under this family code.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Real-time Location Map -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fa fa-map"></i> Real-time Location Map</h5>
                            </div>
                            <div class="card-body">
                                <div id="map" class="map-container"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Location History -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card location-card">
                            <div class="card-header">
                                <h5><i class="fa fa-history"></i> Recent Location History</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="locationHistoryTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Student</th>
                                                <th>Location</th>
                                                <th>Address</th>
                                                <th>Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($locationHistory as $location)
                                                @php
                                                    $student = $students->where('family_code', $location->userCode)->first();
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <strong>{{ $student ? $student->firstname . ' ' . $student->lastname : 'Unknown Student' }}</strong>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-info">
                                                            {{ number_format($location->lat, 6) }}, {{ number_format($location->lng, 6) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <small class="address-cell" data-lat="{{ $location->lat }}" data-lng="{{ $location->lng }}">
                                                            <i class="fa fa-spinner fa-spin"></i> Loading address...
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <small>{{ $location->created_at->diffForHumans() }}</small>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">
                                                        No location history available.
                                                    </td>
                                                </tr>
                                            @endforelse
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

    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
    <script>
        // Initialize DataTable
        $(document).ready(function() {
            $('#locationHistoryTable').DataTable({
                responsive: true,
                language: {
                    search: "Search location history:",
                    lengthMenu: "Show _MENU_ records per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ records",
                    infoEmpty: "No location records available",
                    infoFiltered: "(filtered from _MAX_ total records)"
                },
                pageLength: 10,
                order: [[3, 'desc']] // Sort by time column descending
            });
        });

        // Initialize map
        let map = L.map('map').setView([10.5333, 122.8333], 13);
        let markers = [];

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Function to update real-time locations
        function updateRealTimeLocations() {
            fetch('/parent/real-time-locations', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                // Clear existing markers
                markers.forEach(marker => map.removeLayer(marker));
                markers = [];

                // Add new markers with address lookup
                data.forEach((item, index) => {
                    if (item.location && item.location.lat && item.location.lng) {
                        const marker = L.marker([item.location.lat, item.location.lng])
                            .addTo(map);
                        
                        // Get address for this location
                        fetch(`/parent/address/${item.location.lat}/${item.location.lng}`, {
                            method: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(addressData => {
                            marker.bindPopup(`
                                <strong>${item.student.firstname} ${item.student.lastname}</strong><br>
                                <small><strong>Address:</strong> ${addressData.address}</small><br>
                                <small><strong>Last update:</strong> ${item.lastUpdate}</small>
                            `);
                        })
                        .catch(error => {
                            marker.bindPopup(`
                                <strong>${item.student.firstname} ${item.student.lastname}</strong><br>
                                <small><strong>Coordinates:</strong> ${item.location.lat}, ${item.location.lng}</small><br>
                                <small><strong>Last update:</strong> ${item.lastUpdate}</small>
                            `);
                        });
                        
                        markers.push(marker);
                    }
                });

                // Fit map to show all markers
                if (markers.length > 0) {
                    const group = new L.featureGroup(markers);
                    map.fitBounds(group.getBounds().pad(0.1));
                }
            })
            .catch(error => console.error('Error fetching locations:', error));
        }

        // Update locations every 30 seconds
        updateRealTimeLocations();
        setInterval(updateRealTimeLocations, 30000);

        // Load addresses asynchronously to prevent timeout
        function loadAddresses() {
            const addressCells = document.querySelectorAll('.address-cell');
            addressCells.forEach((cell, index) => {
                const lat = cell.getAttribute('data-lat');
                const lng = cell.getAttribute('data-lng');
                
                if (lat && lng) {
                    // Add delay to prevent overwhelming the API
                    setTimeout(() => {
                        fetch(`/parent/address/${lat}/${lng}`, {
                            method: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            cell.innerHTML = `<strong>Address:</strong> ${data.address}`;
                        })
                        .catch(error => {
                            cell.innerHTML = '<strong>Address:</strong> <span class="text-muted">Address unavailable</span>';
                        });
                    }, index * 300); // 300ms delay between requests
                }
            });
        }

        // Load addresses after page loads
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(loadAddresses, 1000); // Start loading addresses after 1 second
            
            // Initialize notifications
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
            fetch('{{ route("notifications.recent") }}')
                .then(response => response.json())
                .then(data => {
                    const notificationList = document.getElementById('notificationList');
                    
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
                            <a class="dropdown-item ${isUnread ? 'unread' : ''}" href="{{ route('notifications.show', '') }}/${notification.id}" onclick="markAsRead(${notification.id})">
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
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                });
        }

        function loadUnreadCount() {
            fetch('{{ route("notifications.unread-count") }}')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notificationBadge');
                    if (data.count > 0) {
                        badge.textContent = data.count;
                        badge.style.display = 'inline';
                    } else {
                        badge.style.display = 'none';
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
    </script>
</body>
</html> 