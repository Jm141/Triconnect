<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $student->firstname }} {{ $student->lastname }} - Location History</title>
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

        .location-card {
            border-left: 4px solid var(--accent-color);
        }

        .map-container {
            height: 500px;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: inset 0 0 10px rgba(0,0,0,0.1);
        }

        .location-timeline {
            max-height: 600px;
            overflow-y: auto;
        }

        .timeline-item {
            border-left: 3px solid var(--accent-color);
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
            position: relative;
            background: var(--white);
            border-radius: 0 var(--border-radius) var(--border-radius) 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -8px;
            top: 1.5rem;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--accent-color);
            border: 3px solid var(--white);
            box-shadow: 0 0 0 2px var(--accent-color);
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

        .btn-secondary {
            background: linear-gradient(135deg, var(--dark-gray) 0%, #6c757d 100%);
            box-shadow: 0 2px 8px rgba(108, 117, 125, 0.3);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.4);
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

        .student-info {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: var(--border-radius);
            padding: 1.5rem;
        }

        .student-info h4 {
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .student-info .badge {
            font-size: 0.75rem;
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
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="navbar navbar-expand navbar-dark">
            <div class="container-fluid">
                <a href="/parent/dashboard" class="navbar-brand">
                    <img src="{{ asset('images/Triconnect.png') }}" alt="Triconnect Logo" />
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
                        <a href="/parent/dashboard" class="nav-link">
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
                        <h1 class="h3 mb-0 text-gray-800">Student Location History</h1>
                        <p class="text-muted">Track {{ $student->firstname }}'s location history and activities</p>
                    </div>
                </div>

                <!-- Student Info Header -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card student-card">
                            <div class="card-body student-info">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h4><i class="fa fa-user"></i> {{ $student->firstname }} {{ $student->lastname }}</h4>
                                        <p class="mb-0">
                                            <strong>Grade:</strong> {{ $student->grade_level ?? 'N/A' }} | 
                                            <strong>Age:</strong> {{ $student->age ?? 'N/A' }} | 
                                            <strong>Status:</strong> 
                                            <span class="badge badge-{{ $student->status === 'active' ? 'success' : 'warning' }}">
                                                {{ $student->status ?? 'Unknown' }}
                                            </span>
                                        </p>
                                    </div>
                                    <a href="/parent/dashboard" class="btn btn-secondary">
                                        <i class="fa fa-arrow-left"></i> Back to Dashboard
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Location Map and Timeline -->
                <div class="row">
                    <!-- Location Map -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fa fa-map"></i> Location History Map</h5>
                            </div>
                            <div class="card-body">
                                <div id="map" class="map-container"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Location Timeline -->
                    <div class="col-md-4">
                        <div class="card location-card">
                            <div class="card-header">
                                <h5><i class="fa fa-history"></i> Location Timeline</h5>
                            </div>
                            <div class="card-body location-timeline">
                                @forelse ($locationHistory as $location)
                                    <div class="timeline-item">
                                        <div class="d-flex justify-content-between">
                                            <strong>{{ $location->created_at->format('M d, Y') }}</strong>
                                            <small class="text-muted">{{ $location->created_at->format('H:i') }}</small>
                                        </div>
                                        <p class="mb-1">
                                            <small class="text-muted">
                                                <strong>Coordinates:</strong> {{ number_format($location->lat, 6) }}, {{ number_format($location->lng, 6) }}
                                            </small>
                                        </p>
                                        <p class="mb-0">
                                            <small class="address-cell" data-lat="{{ $location->lat }}" data-lng="{{ $location->lng }}">
                                                <i class="fa fa-spinner fa-spin"></i> <strong>Loading address...</strong>
                                            </small>
                                        </p>
                                    </div>
                                @empty
                                    <div class="text-center text-muted py-4">
                                        <i class="fa fa-map-marker fa-3x mb-3"></i>
                                        <p>No location history available for this student.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Location History Table -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fa fa-table"></i> Detailed Location History</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="locationHistoryTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Date & Time</th>
                                                <th>Coordinates</th>
                                                <th>Address</th>
                                                <th>Time Ago</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($locationHistory as $location)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $location->created_at->format('M d, Y') }}</strong><br>
                                                        <small>{{ $location->created_at->format('H:i:s') }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-info">
                                                            {{ number_format($location->lat, 6) }}, {{ number_format($location->lng, 6) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <small class="address-cell" data-lat="{{ $location->lat }}" data-lng="{{ $location->lng }}">
                                                            <i class="fa fa-spinner fa-spin"></i> <strong>Loading address...</strong>
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
                order: [[3, 'desc']] // Sort by time ago column descending
            });
        });

        // Initialize map
        let map = L.map('map').setView([10.5333, 122.8333], 13);
        let markers = [];

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Add location markers to map with address lookup
        @foreach ($locationHistory as $location)
            @if ($location->lat && $location->lng)
                const marker{{ $loop->index }} = L.marker([{{ $location->lat }}, {{ $location->lng }}])
                    .addTo(map);
                
                // Get address for this location
                fetch(`/parent/address/{{ $location->lat }}/{{ $location->lng }}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(addressData => {
                    marker{{ $loop->index }}.bindPopup(`
                        <strong>{{ $student->firstname }} {{ $student->lastname }}</strong><br>
                        <small><strong>Address:</strong> ${addressData.address}</small><br>
                        <small><strong>Time:</strong> {{ $location->created_at->format('M d, Y H:i') }}</small>
                    `);
                })
                .catch(error => {
                    marker{{ $loop->index }}.bindPopup(`
                        <strong>{{ $student->firstname }} {{ $student->lastname }}</strong><br>
                        <small><strong>Coordinates:</strong> {{ number_format($location->lat, 6) }}, {{ number_format($location->lng, 6) }}</small><br>
                        <small><strong>Time:</strong> {{ $location->created_at->format('M d, Y H:i') }}</small>
                    `);
                });
                
                markers.push(marker{{ $loop->index }});
            @endif
        @endforeach

        // Fit map to show all markers
        if (markers.length > 0) {
            const group = new L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.1));
        }

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
        });
    </script>
</body>
</html> 