<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Schedule - Triconnect</title>
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

        .content-wrapper {
            margin-left: 0;
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

        .weekly-calendar {
            display: grid;
            grid-template-columns: 120px repeat(7, 1fr);
            gap: 1px;
            background: var(--light-gray);
            border-radius: var(--border-radius);
            overflow: hidden;
        }

        .calendar-header {
            background: var(--primary-color);
            color: var(--white);
            padding: 1rem;
            text-align: center;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .time-slot {
            background: var(--white);
            padding: 0.5rem;
            text-align: center;
            font-weight: 600;
            color: var(--dark-gray);
            font-size: 0.8rem;
            border-bottom: 1px solid var(--light-gray);
        }

        .day-column {
            background: var(--white);
            min-height: 600px;
        }

        .day-header {
            background: var(--accent-color);
            color: var(--white);
            padding: 1rem;
            text-align: center;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .schedule-item {
            background: var(--success-color);
            color: var(--white);
            margin: 2px;
            padding: 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .schedule-item:hover {
            transform: scale(1.02);
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .schedule-item.inactive {
            background: var(--warning-color);
        }

        .schedule-subject {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .schedule-details {
            font-size: 0.7rem;
            opacity: 0.9;
        }

        .filter-section {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
        }

        .no-schedules {
            text-align: center;
            padding: 2rem;
            color: var(--dark-gray);
        }

        .no-schedules i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .weekly-calendar {
                grid-template-columns: 80px repeat(7, 1fr);
                font-size: 0.7rem;
            }
            
            .calendar-header, .day-header {
                padding: 0.5rem;
                font-size: 0.7rem;
            }
            
            .schedule-item {
                padding: 0.25rem;
                font-size: 0.6rem;
            }
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    @if (strpos(session('userAccess')->access, 'teacher') !== false)
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

        <div class="content-wrapper">
            <div class="container-fluid">
                <!-- Page Header -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h1 class="h3 mb-0 text-gray-800">Weekly Schedule View</h1>
                        <p class="text-muted">View all schedules in a weekly calendar format</p>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="filter-section">
                            <form action="{{ route('schedules.weekly') }}" method="GET" class="row">
                                <div class="col-md-4">
                                    <label for="teacher" class="form-label">Filter by Teacher</label>
                                    <select name="teacher" id="teacher" class="form-control">
                                        <option value="">All Teachers</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->staff_code }}" {{ request('teacher') == $teacher->staff_code ? 'selected' : '' }}>
                                                {{ $teacher->firstname }} {{ $teacher->lastname }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-filter"></i> Apply Filter
                                        </button>
                                        <a href="{{ route('schedules.weekly') }}" class="btn btn-secondary">
                                            <i class="fa fa-refresh"></i> Clear Filter
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Weekly Calendar -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fa fa-calendar-week"></i> Weekly Schedule</h5>
                            </div>
                            <div class="card-body p-0">
                                @if($schedules->count() > 0)
                                    <div class="weekly-calendar">
                                        <!-- Time column header -->
                                        <div class="calendar-header">Time</div>
                                        
                                        <!-- Day headers -->
                                        @foreach($days as $day)
                                            <div class="calendar-header">{{ $day }}</div>
                                        @endforeach
                                        
                                        <!-- Time slots -->
                                        @php
                                            $timeSlots = [
                                                '07:00', '08:00', '09:00', '10:00', '11:00', '12:00',
                                                '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'
                                            ];
                                        @endphp
                                        
                                        @foreach($timeSlots as $time)
                                            <div class="time-slot">{{ $time }}</div>
                                            
                                            @foreach($days as $day)
                                                <div class="day-column">
                                                    @php
                                                        $daySchedules = $schedules->get($day, collect())->filter(function($schedule) use ($time) {
                                                            $startTime = \Carbon\Carbon::parse($schedule->start_time)->format('H:i');
                                                            return $startTime === $time;
                                                        });
                                                    @endphp
                                                    
                                                    @foreach($daySchedules as $schedule)
                                                        <div class="schedule-item {{ $schedule->status === 'inactive' ? 'inactive' : '' }}" 
                                                             title="{{ $schedule->subject_name }} - {{ $schedule->teacher->firstname ?? 'N/A' }} {{ $schedule->teacher->lastname ?? 'N/A' }}">
                                                            <div class="schedule-subject">{{ $schedule->subject_name }}</div>
                                                            <div class="schedule-details">
                                                                {{ $schedule->teacher->firstname ?? 'N/A' }} {{ $schedule->teacher->lastname ?? 'N/A' }}<br>
                                                                {{ $schedule->room->name ?? $schedule->room_code }}<br>
                                                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        @endforeach
                                    </div>
                                @else
                                    <div class="no-schedules">
                                        <i class="fa fa-calendar-times"></i>
                                        <h5>No Schedules Found</h5>
                                        <p>No schedules are available for the selected criteria.</p>
                                        <a href="{{ route('schedules.create') }}" class="btn btn-primary">
                                            <i class="fa fa-plus"></i> Create New Schedule
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fa fa-cogs"></i> Quick Actions</h5>
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
                                            <i class="fa fa-list"></i> List View
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="{{ route('qr.generate') }}" class="btn btn-success btn-block">
                                            <i class="fa fa-qrcode"></i> Generate QR Code
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="/teacher/dashboard" class="btn btn-secondary btn-block">
                                            <i class="fa fa-dashboard"></i> Dashboard
                                        </a>
                                    </div>
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
</body>
</html> 