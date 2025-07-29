<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Add New Schedule - Triconnect</title>
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
            padding: 2rem;
        }

        .schedule-card {
            border-left: 4px solid var(--accent-color);
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

        .form-control {
            border-radius: 6px;
            border: 1px solid var(--light-gray);
            padding: 0.75rem;
            transition: all 0.3s ease;
            font-size: 14px;
            background-color: #ffffff;
            color: #333;
            line-height: 1.5;
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
            background-color: #ffffff;
        }

        .form-control:read-only {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 500;
        }

        /* Fix for select dropdowns */
        select.form-control {
            height: auto;
            min-height: 45px;
            padding: 0.75rem 2.5rem 0.75rem 0.75rem;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        select.form-control:focus {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%233498db' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        }

        .form-control option {
            padding: 12px 8px;
            font-size: 14px;
            background-color: #ffffff;
            color: #333;
            line-height: 1.4;
        }

        .form-control option:hover {
            background-color: #e9ecef;
        }

        .form-control option:checked {
            background-color: #3498db;
            color: #ffffff;
        }

        /* Input group adjustments for select */
        .input-group .form-control {
            border-left: none;
        }

        .input-group .input-group-text {
            border-right: none;
            background-color: #f8f9fa;
            border-color: #ced4da;
        }

        .input-group:focus-within .input-group-text {
            border-color: #3498db;
            background-color: #e3f2fd;
        }

        .form-label {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-check {
            margin-bottom: 8px;
            padding: 8px 12px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .form-check:hover {
            background-color: #f8f9fa;
        }

        .form-check-input {
            margin-right: 10px;
            transform: scale(1.2);
        }

        .form-check-label {
            font-size: 14px;
            color: #333;
            font-weight: 500;
            cursor: pointer;
        }

        .form-check-input:checked + .form-check-label {
            color: #3498db;
            font-weight: 600;
        }

        .days-container {
            border: 2px solid #e9ecef !important;
            border-radius: 8px !important;
            padding: 15px !important;
            background-color: #ffffff;
            max-height: 200px;
            overflow-y: auto;
        }

        .days-container::-webkit-scrollbar {
            width: 6px;
        }

        .days-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .days-container::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .days-container::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            color: #495057;
            font-weight: 500;
        }

        .form-control::placeholder {
            color: #6c757d;
            opacity: 0.7;
        }

        .invalid-feedback {
            font-size: 12px;
            font-weight: 500;
            margin-top: 5px;
        }

        .card-body {
            padding: 2rem;
        }

        .form-row {
            margin-bottom: 1.5rem;
        }

        .form-row:last-child {
            margin-bottom: 0;
        }

        .alert {
            border-radius: var(--border-radius);
            border: none;
        }

        .alert-danger {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
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
                        <a href="{{ route('schedules.index') }}" class="nav-link active">
                            <i class="nav-icon fa fa-calendar"></i>
                            <span>Schedule Management</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('schedules.weekly') }}" class="nav-link">
                            <i class="nav-icon fa fa-calendar-week"></i>
                            <span>Weekly View</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('attendance.dashboard') }}" class="nav-link">
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
                        <h1 class="h3 mb-0 text-gray-800">Add New Schedule</h1>
                        <p class="text-muted">Create a new schedule entry for teachers and subjects</p>
                    </div>
                </div>

                <!-- Schedule Form Card -->
                <div class="row">
                    <div class="col-12">
                        <div class="card schedule-card">
                            <div class="card-header">
                                <h5><i class="fa fa-plus"></i> Schedule Information</h5>
                            </div>
                            <div class="card-body">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form action="{{ route('schedules.store') }}" method="POST">
                                    @csrf
                                    
                                    <div class="row">
                                        <!-- Teacher Selection -->
                                        <div class="col-md-6 mb-3">
                                            <label for="teacher_staff_code" class="form-label">Current Teacher *</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fa fa-user"></i>
                                                </span>
                                                <input type="text" name="teacher_staff_code" id="teacher_staff_code" 
                                                       class="form-control @error('teacher_staff_code') is-invalid @enderror" 
                                                       value="{{ session('userAccess')->userCode ?? '' }}" 
                                                       readonly required>
                                            </div>
                                            <small class="form-text text-muted">
                                                <i class="fa fa-info-circle"></i> This is your teacher code from your session
                                            </small>
                                            @error('teacher_staff_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Subject Name -->
                                        <div class="col-md-6 mb-3">
                                            <label for="subject_name" class="form-label">Subject Name *</label>
                                            <input type="text" name="subject_name" id="subject_name" class="form-control @error('subject_name') is-invalid @enderror" value="{{ old('subject_name') }}" placeholder="e.g., Mathematics, Science, English" required>
                                            @error('subject_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Room Selection -->
                                        <div class="col-md-6 mb-3">
                                            <label for="room_code" class="form-label">Room *</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fa fa-building"></i>
                                                </span>
                                                <select name="room_code" id="room_code" class="form-control @error('room_code') is-invalid @enderror" required>
                                                    <option value="">Select Room</option>
                                                    @foreach($rooms as $room)
                                                        <option value="{{ $room->room_code }}" {{ old('room_code') == $room->room_code ? 'selected' : '' }}>
                                                            {{ $room->name }} ({{ $room->room_code }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('room_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Days of Week (Multiple Selection) -->
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Days of Week *</label>
                                            <div class="days-container">
                                                @foreach($days as $day)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="day_of_week[]" 
                                                               id="day_{{ $loop->index }}" value="{{ $day }}"
                                                               {{ in_array($day, old('day_of_week', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="day_{{ $loop->index }}">
                                                            {{ $day }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @error('day_of_week')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            @error('day_of_week.*')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Start Time -->
                                        <div class="col-md-6 mb-3">
                                            <label for="start_time" class="form-label">Start Time *</label>
                                            <input type="time" name="start_time" id="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time') }}" required>
                                            @error('start_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- End Time -->
                                        <div class="col-md-6 mb-3">
                                            <label for="end_time" class="form-label">End Time *</label>
                                            <input type="time" name="end_time" id="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time') }}" required>
                                            @error('end_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Grade Level -->
                                        <div class="col-md-6 mb-3">
                                            <label for="grade_level" class="form-label">Grade Level</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fa fa-graduation-cap"></i>
                                                </span>
                                                <select name="grade_level" id="grade_level" class="form-control @error('grade_level') is-invalid @enderror">
                                                    <option value="">Select Grade Level</option>
                                                    @foreach($gradeLevels as $grade)
                                                        <option value="{{ $grade }}" {{ old('grade_level') == $grade ? 'selected' : '' }}>
                                                            {{ $grade }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('grade_level')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Section -->
                                        <div class="col-md-6 mb-3">
                                            <label for="section" class="form-label">Section</label>
                                            <input type="text" name="section" id="section" class="form-control @error('section') is-invalid @enderror" value="{{ old('section') }}" placeholder="e.g., A, B, C, Alpha, Beta">
                                            @error('section')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Notes -->
                                        <div class="col-12 mb-3">
                                            <label for="notes" class="form-label">Notes</label>
                                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Additional notes or instructions...">{{ old('notes') }}</textarea>
                                            @error('notes')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Form Actions -->
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <div class="d-flex justify-content-end">
                                                <a href="{{ route('schedules.index') }}" class="btn btn-secondary me-2">
                                                    <i class="fa fa-times"></i> Cancel
                                                </a>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fa fa-save"></i> Create Schedule
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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
        // Time validation
        document.getElementById('end_time').addEventListener('change', function() {
            const startTime = document.getElementById('start_time').value;
            const endTime = this.value;
            
            if (startTime && endTime && startTime >= endTime) {
                alert('End time must be after start time');
                this.value = '';
            }
        });

        // Auto-fill end time suggestion
        document.getElementById('start_time').addEventListener('change', function() {
            const startTime = this.value;
            if (startTime) {
                const start = new Date(`2000-01-01T${startTime}`);
                const end = new Date(start.getTime() + (60 * 60 * 1000)); // Add 1 hour
                const endTimeString = end.toTimeString().slice(0, 5);
                document.getElementById('end_time').value = endTimeString;
            }
        });
    </script>
</body>
</html> 