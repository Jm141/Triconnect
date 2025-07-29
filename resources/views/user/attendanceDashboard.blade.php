<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Attendance Dashboard - Triconnect</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap4.min.css">
    
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

        .btn-success {
            background: linear-gradient(135deg, var(--success-color) 0%, #229954 100%);
            box-shadow: 0 2px 8px rgba(39, 174, 96, 0.3);
        }
        
        .current-class-card {
            border-left: 4px solid var(--success-color);
            background: rgba(39, 174, 96, 0.05);
        }

        .historical-card {
            border-left: 4px solid var(--accent-color);
            background: rgba(52, 152, 219, 0.05);
        }

        .attendance-stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 1rem;
        }

        .stat-item {
            text-align: center;
            padding: 1rem;
            background: var(--light-gray);
            border-radius: var(--border-radius);
            flex: 1;
            margin: 0 0.5rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--accent-color);
        }
        
        .stat-label {
            color: var(--dark-gray);
            font-size: 0.9rem;
        }

        .time-badge {
            background: var(--accent-color);
            color: var(--white);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-present {
            color: var(--success-color);
            font-weight: 600;
        }

        .status-absent {
            color: var(--danger-color);
            font-weight: 600;
        }

        .filter-section {
            background: var(--light-gray);
            padding: 1rem;
            border-radius: var(--border-radius);
            margin-bottom: 1rem;
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

            .attendance-stats {
                flex-direction: column;
            }

            .stat-item {
                margin: 0.25rem 0;
            }
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="navbar navbar-expand navbar-dark">
            <div class="container-fluid">
                <a href="/teacher/dashboard" class="navbar-brand">
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
                <h5 class="text-white mb-0">Teacher Portal</h5>
            </div>
            
            <div class="user-panel">
                    <div class="image">
                    <img src="/images/Triconnect.png" alt="User Image" onerror="this.onerror=null; this.src='https://via.placeholder.com/45x45/3498db/ffffff?text=U';">
                    </div>
                    <div class="info">
                    <a href="#" class="d-block">{{ session('userAccess')->name ?? 'Teacher' }}</a>
                    <small class="text-muted">Staff Account</small>
                    </div>
                </div>

            <nav class="sidebar-nav">
                <ul class="nav nav-pills nav-sidebar flex-column">
                        <li class="nav-item">
                        <a href="/teacher/dashboard" class="nav-link">
                                <i class="nav-icon fa fa-dashboard"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('schedules.index') }}" class="nav-link">
                            <i class="nav-icon fa fa-calendar"></i>
                            <span>Schedule Management</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('qr.generate') }}" class="nav-link">
                            <i class="nav-icon fa fa-qrcode"></i>
                            <span>Generate QR Code</span>
                            </a>
                        </li>
                        <li class="nav-item">
                        <a href="{{ route('qr.history') }}" class="nav-link">
                            <i class="nav-icon fa fa-history"></i>
                            <span>Attendance History</span>
                            </a>
                        </li>
                        <li class="nav-item">
                        <a href="{{ route('attendance.dashboard') }}" class="nav-link active">
                            <i class="nav-icon fa fa-check-square-o"></i>
                            <span>Attendance</span>
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
                        <h1 class="h3 mb-0 text-gray-800">Attendance Dashboard</h1>
                        <p class="text-muted">Monitor student attendance for your classes</p>
                        </div>
                    </div>

                <!-- Current Class Section -->
                @if($currentSchedule)
                    <div class="row mb-4">
                        <div class="col-12">
                        <div class="card current-class-card">
                                <div class="card-header">
                                <h5><i class="fa fa-clock-o"></i> Current Class - {{ $currentSchedule->subject_name }}</h5>
                                </div>
                                <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4>{{ $currentSchedule->subject_name }}</h4>
                                        <p class="mb-2">
                                            <strong>Room:</strong> {{ $currentSchedule->room->name ?? $currentSchedule->room_code }}<br>
                                            <strong>Time:</strong> {{ \Carbon\Carbon::parse($currentSchedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($currentSchedule->end_time)->format('H:i') }}<br>
                                            <strong>Grade Level:</strong> {{ $currentSchedule->grade_level ?? 'N/A' }}<br>
                                            <strong>Section:</strong> {{ $currentSchedule->section ?? 'N/A' }}
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="attendance-stats">
                                            <div class="stat-item">
                                                <div class="stat-number">{{ $currentClassAttendance->count() }}</div>
                                                <div class="stat-label">Present</div>
                                        </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Current Class Attendance Table -->
                                @if($currentClassAttendance->count() > 0)
                                <div class="table-responsive mt-3">
                                    <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Student Name</th>
                                                        <th>User Code</th>
                                                <th>Time Scanned</th>
                                                <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                            @foreach($currentClassAttendance as $attendance)
                                                        <tr>
                                                <td>{{ \App\Models\UserAccess::where('userCode', $attendance->userCode)->value('name') ?? 'Unknown' }}</td>
                                                <td>{{ $attendance->userCode }}</td>
                                                <td>{{ $attendance->time_scan->format('H:i:s') }}</td>
                                                <td><span class="status-present">Present</span></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                <div class="text-center py-4">
                                    <i class="fa fa-users fa-3x text-muted mb-3"></i>
                                    <h5>No Students Present Yet</h5>
                                    <p class="text-muted">Students will appear here once they scan the QR code.</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                                </div>
                @else
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <h5><i class="fa fa-info-circle"></i> No Current Class</h5>
                            <p>You don't have any active classes at the moment ({{ $currentTime->format('H:i') }}).</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Historical Attendance Section -->
                <div class="row">
                    <div class="col-12">
                        <div class="card historical-card">
                            <div class="card-header">
                                <h5><i class="fa fa-history"></i> Historical Attendance (Last 30 Days)</h5>
                            </div>
                            <div class="card-body">
                                <!-- Filter Section -->
                                <div class="filter-section">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="start_date">Start Date</label>
                                            <input type="date" id="start_date" class="form-control" value="{{ date('Y-m-d', strtotime('-30 days')) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="end_date">End Date</label>
                                            <input type="date" id="end_date" class="form-control" value="{{ date('Y-m-d') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="subject_filter">Subject</label>
                                            <select id="subject_filter" class="form-control">
                                                <option value="">All Subjects</option>
                                                @foreach($todaysSchedules as $schedule)
                                                <option value="{{ $schedule->subject_name }}">{{ $schedule->subject_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>&nbsp;</label>
                                            <div>
                                                <button class="btn btn-primary" onclick="filterAttendance()">
                                                    <i class="fa fa-filter"></i> Filter
                                                </button>
                                                <button class="btn btn-success" onclick="exportAttendance()">
                                                    <i class="fa fa-download"></i> Export
                                                </button>
                                            </div>
                                </div>
                            </div>
                        </div>

                                <!-- Historical Data Table -->
                                <div class="table-responsive">
                                    <table id="historicalTable" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Subject</th>
                                                <th>Room</th>
                                                <th>Students Present</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($historicalAttendance as $date => $attendanceRecords)
                                            @php
                                                $groupedBySubject = $attendanceRecords->groupBy('subject_name');
                                            @endphp
                                            @foreach($groupedBySubject as $subjectName => $records)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</td>
                                                <td>{{ $subjectName }}</td>
                                                <td>{{ $records->first()->roomCode ?? 'N/A' }}</td>
                                                <td>{{ $records->count() }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary" onclick="viewDayDetails('{{ $date }}', '{{ $subjectName }}')">
                                                        <i class="fa fa-eye"></i> View Details
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
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

    <!-- Day Details Modal -->
    <div class="modal fade" id="dayDetailsModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Attendance Details</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="dayDetailsContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#historicalTable').DataTable({
                responsive: true,
                order: [[0, 'desc']],
                pageLength: 25
            });

            // Auto-refresh current class attendance every 30 seconds
            setInterval(function() {
                if ({{ $currentSchedule ? 'true' : 'false' }}) {
                    location.reload();
                }
            }, 30000);
        });

        function filterAttendance() {
            const startDate = $('#start_date').val();
            const endDate = $('#end_date').val();
            const subject = $('#subject_filter').val();

            $.ajax({
                url: '{{ route("attendance.historical") }}',
                method: 'POST',
                data: {
                    teacher_staff_code: '{{ $userAccess->staff_code }}',
                    start_date: startDate,
                    end_date: endDate,
                    subject_name: subject,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        updateHistoricalTable(response.attendance);
                    }
                },
                error: function() {
                    alert('Error loading attendance data');
                }
            });
        }

        function updateHistoricalTable(attendanceData) {
            const tbody = $('#historicalTable tbody');
            tbody.empty();

            Object.keys(attendanceData).forEach(date => {
                const records = attendanceData[date];
                const groupedBySubject = {};
                
                records.forEach(record => {
                    if (!groupedBySubject[record.subject_name]) {
                        groupedBySubject[record.subject_name] = [];
                    }
                    groupedBySubject[record.subject_name].push(record);
                });

                Object.keys(groupedBySubject).forEach(subjectName => {
                    const records = groupedBySubject[subjectName];
                    const row = `
                        <tr>
                            <td>${new Date(date).toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'})}</td>
                            <td>${subjectName}</td>
                            <td>${records[0].roomCode || 'N/A'}</td>
                            <td>${records.length}</td>
                            <td>
                                <button class="btn btn-sm btn-primary" onclick="viewDayDetails('${date}', '${subjectName}')">
                                    <i class="fa fa-eye"></i> View Details
                                </button>
                            </td>
                        </tr>
                    `;
                    tbody.append(row);
                });
            });
        }

        function viewDayDetails(date, subjectName) {
            $.ajax({
                url: '{{ route("attendance.dayDetails") }}',
                method: 'POST',
                data: {
                    date: date,
                    subject_name: subjectName,
                    teacher_staff_code: '{{ $userAccess->staff_code }}',
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        let content = `
                            <h6>${subjectName} - ${new Date(date).toLocaleDateString()}</h6>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>User Code</th>
                                        <th>Time Scanned</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                        `;
                        
                        response.attendance.forEach(record => {
                            content += `
                                <tr>
                                    <td>${record.student_name || 'Unknown'}</td>
                                    <td>${record.userCode}</td>
                                    <td>${new Date(record.time_scan).toLocaleTimeString()}</td>
                                    <td><span class="status-present">Present</span></td>
                                </tr>
                            `;
                        });
                        
                        content += '</tbody></table>';
                        $('#dayDetailsContent').html(content);
                        $('#dayDetailsModal').modal('show');
                    }
                },
                error: function() {
                    alert('Error loading day details');
                }
            });
        }

        function exportAttendance() {
            const startDate = $('#start_date').val();
            const endDate = $('#end_date').val();
            
            const url = `{{ route('attendance.export') }}?teacher_staff_code={{ $userAccess->staff_code }}&start_date=${startDate}&end_date=${endDate}`;
            window.open(url, '_blank');
        }
    </script>
</body>
</html> 