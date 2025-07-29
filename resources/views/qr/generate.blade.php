<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate QR Code - Triconnect</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Remove the problematic CDN script and handle loading dynamically -->
    
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
            border-radius: 50%;
            background: var(--white);
            padding: 8px;
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
            transition: width 0.3s ease;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar.collapsed .sidebar-header h5,
        .sidebar.collapsed .user-panel .info,
        .sidebar.collapsed .nav-link span {
            display: none;
        }

        .sidebar.collapsed .user-panel {
            justify-content: center;
            padding: 1rem 0.5rem;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 0.875rem 0.5rem;
        }

        .sidebar.collapsed .nav-icon {
            margin-right: 0;
            font-size: 1.2rem;
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

        .current-schedule {
            border-left: 4px solid var(--success-color);
            background: rgba(39, 174, 96, 0.05);
        }

        .schedule-item {
            border-left: 4px solid var(--accent-color);
            transition: all 0.3s ease;
        }

        .schedule-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .time-badge {
            background: var(--accent-color);
            color: var(--white);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-active {
            color: var(--success-color);
            font-weight: 600;
        }

        .status-inactive {
            color: var(--warning-color);
            font-weight: 600;
        }

        /* Reusable QR Code Card */
        .reusable-qr-card {
            border-left: 4px solid var(--accent-color);
            background: rgba(52, 152, 219, 0.05);
        }

        /* Print Styles */
        @media print {
            .sidebar, .navbar, .btn, .no-print {
                display: none !important;
            }
            
            .content-wrapper {
                margin-left: 0 !important;
                padding: 0 !important;
            }
            
            .card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
                page-break-inside: avoid;
            }
            
            body {
                background: white !important;
            }
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
        <!-- Sidebar Toggle Button -->
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fa fa-bars"></i>
        </button>

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
        <aside class="sidebar" id="sidebar">
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
                        <a href="{{ route('qr.generate') }}" class="nav-link active">
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
                        <a href="{{ route('attendance.dashboard') }}" class="nav-link">
                            <i class="nav-icon fa fa-check-square-o"></i>
                            <span>Attendance</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <div class="content-wrapper" id="contentWrapper">
            <div class="container-fluid">
                <!-- Page Header -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h1 class="h3 mb-0 text-gray-800">QR Code Generation</h1>
                        <p class="text-muted">Generate QR codes for attendance tracking</p>
                        <!-- <input type="text" value="{{ session('userAccess')->userCode ?? 'TCH456' }}"> -->

                    </div>
                </div>

                <!-- Reusable Teacher QR Code Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card reusable-qr-card">
                            <div class="card-header">
                                <h5><i class="fa fa-refresh"></i> Reusable Teacher QR Code</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4>One QR Code for All Classes</h4>
                                        <p class="mb-2">
                                            <strong>Benefits:</strong><br>
                                            • Generate once, use for all your classes<br>
                                            • System automatically detects current class based on time and day<br>
                                            • No need to generate separate QR codes for each schedule<br>
                                            • Works throughout the entire week
                                        </p>
                                        <p class="text-muted">
                                            <small>
                                                <i class="fa fa-info-circle"></i> 
                                                When students scan this QR code, the system will automatically find your current class schedule and record attendance for the correct subject.
                                            </small>
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn btn-success btn-lg generate-qr" id="generate-teacher-qr">
                                            <i class="fa fa-qrcode"></i> Generate Reusable QR
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Schedule Card -->
                @if($currentSchedule)
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card current-schedule">
                            <div class="card-header">
                                <h5><i class="fa fa-clock-o"></i> Current Class ({{ $currentDay }})</h5>
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
                                        <button class="btn btn-success btn-lg generate-qr" id="generate-current-qr">
                                            <i class="fa fa-qrcode"></i> Generate QR Code
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <h5><i class="fa fa-info-circle"></i> No Current Class</h5>
                            <p>You don't have any active classes at the moment ({{ $currentTime }}).</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Today's Schedule -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fa fa-calendar"></i> Today's Schedule ({{ $currentDay }})</h5>
                            </div>
                            <div class="card-body">
                                @if($todaysSchedules->count() > 0)
                                    <div class="row">
                                        @foreach($todaysSchedules as $schedule)
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="card schedule-item">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                            <h6 class="card-title mb-0">{{ $schedule->subject_name }}</h6>
                                                            <span class="time-badge">
                                                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                                            </span>
                                                        </div>
                                                        <p class="card-text">
                                                            <small class="text-muted">
                                                                <strong>Room:</strong> {{ $schedule->room->name ?? $schedule->room_code }}<br>
                                                                <strong>Grade:</strong> {{ $schedule->grade_level ?? 'N/A' }}<br>
                                                                <strong>Section:</strong> {{ $schedule->section ?? 'N/A' }}
                                                            </small>
                                                        </p>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="status-{{ $schedule->status }}">
                                                                {{ ucfirst($schedule->status) }}
                                                            </span>
                                                            <button class="btn btn-primary btn-sm generate-qr" id="generate-schedule-{{ $schedule->id }}-qr">
                                                                <i class="fa fa-qrcode"></i> Generate QR
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fa fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <h5>No Classes Today</h5>
                                        <p class="text-muted">You don't have any classes scheduled for {{ $currentDay }}.</p>
                                    </div>
                                @endif
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Generate QR for teacher userCode
            $('.generate-qr').click(function() {
                var teacherName = "{{ session('userAccess')->name ?? 'Teacher' }}";
                var userCode = "{{ session('userAccess')->userCode ?? 'TCH456' }}";
                
                // Create a temporary container for QR code
                var qrContainer = document.createElement('div');
                qrContainer.id = 'tempQrContainer';
                qrContainer.style.textAlign = 'center';
                
                // Generate QR code
                new QRCode(qrContainer, {
                    text: userCode,
                    width: 200,
                    height: 200,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H
                });
                
                // Wait a moment for QR code to render, then show modal
                setTimeout(function() {
                    Swal.fire({
                        title: 'QR Code Generated',
                        html: `
                            <div style="text-align: center;">
                                <h4 style="margin-bottom: 20px; color: #333;">${teacherName}</h4>
                                <div style="display: inline-block; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                                    ${qrContainer.innerHTML}
                                </div>
                                <p style="margin-top: 15px; color: #666; font-size: 14px;">User Code: ${userCode}</p>
                            </div>
                        `,
                        width: 450,
                        showCloseButton: true,
                        showConfirmButton: true,
                        confirmButtonText: 'Print QR Code',
                        confirmButtonColor: '#28a745',
                        showDenyButton: true,
                        denyButtonText: 'Close',
                        denyButtonColor: '#6c757d',
                        customClass: {
                            container: 'qr-modal-container',
                            popup: 'qr-modal-popup'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            printQrCode(qrContainer.innerHTML, teacherName, userCode);
                        }
                    });
                }, 100);
            });
        });

        function printQrCode(qrHtml, teacherName, userCode) {
            var printWindow = window.open('', '_blank');
            var currentDate = new Date().toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
            var currentTime = new Date().toLocaleTimeString('en-US', { 
                hour: '2-digit', 
                minute: '2-digit' 
            });
            
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Teacher QR Code - ${teacherName}</title>
                        <style>
                            @page {
                                margin: 0.5in;
                                size: A4;
                            }
                            
                            body { 
                                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                                margin: 0;
                                padding: 0;
                                background: white;
                                color: #333;
                                line-height: 1.6;
                            }
                            
                            .print-container {
                                max-width: 100%;
                                margin: 0 auto;
                                padding: 20px;
                                text-align: center;
                            }
                            
                            .header {
                                border-bottom: 3px solid #2c3e50;
                                padding-bottom: 15px;
                                margin-bottom: 30px;
                            }
                            
                            .school-logo {
                                width: 80px;
                                height: 80px;
                                margin: 0 auto 15px;
                                display: block;
                                background: #3498db;
                                border-radius: 50%;
                                padding: 15px;
                                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                            }
                            
                            .school-name {
                                font-size: 28px;
                                font-weight: bold;
                                color: #2c3e50;
                                margin: 0 0 5px 0;
                                text-transform: uppercase;
                                letter-spacing: 1px;
                            }
                            
                            .document-title {
                                font-size: 20px;
                                color: #7f8c8d;
                                margin: 0 0 10px 0;
                                font-weight: 500;
                            }
                            
                            .qr-section {
                                margin: 40px 0;
                                padding: 30px;
                                border: 2px solid #ecf0f1;
                                border-radius: 15px;
                                background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
                                box-shadow: 0 4px 15px rgba(0,0,0,0.05);
                            }
                            
                            .teacher-info {
                                margin-bottom: 25px;
                            }
                            
                            .teacher-name {
                                font-size: 32px;
                                font-weight: bold;
                                color: #2c3e50;
                                margin: 0 0 10px 0;
                                text-transform: uppercase;
                                letter-spacing: 0.5px;
                            }
                            
                            .teacher-role {
                                font-size: 18px;
                                color: #7f8c8d;
                                margin: 0 0 20px 0;
                                font-weight: 500;
                            }
                            
                            .qr-code-container {
                                display: inline-block;
                                padding: 25px;
                                background: white;
                                border: 3px solid #3498db;
                                border-radius: 12px;
                                box-shadow: 0 6px 20px rgba(52, 152, 219, 0.15);
                                margin: 20px 0;
                            }
                            
                            .qr-code-container img {
                                display: block;
                                margin: 0 auto;
                            }
                            
                            .user-code {
                                font-size: 18px;
                                color: #34495e;
                                margin: 20px 0 0 0;
                                font-weight: 600;
                                background: #ecf0f1;
                                padding: 10px 20px;
                                border-radius: 25px;
                                display: inline-block;
                            }
                            
                            .footer {
                                margin-top: 40px;
                                padding-top: 20px;
                                border-top: 2px solid #ecf0f1;
                                color: #7f8c8d;
                                font-size: 14px;
                            }
                            
                            .date-time {
                                margin-bottom: 10px;
                                font-weight: 500;
                            }
                            
                            .instructions {
                                background: #f8f9fa;
                                padding: 15px;
                                border-radius: 8px;
                                margin: 20px 0;
                                border-left: 4px solid #3498db;
                            }
                            
                            .instructions h4 {
                                margin: 0 0 10px 0;
                                color: #2c3e50;
                                font-size: 16px;
                            }
                            
                            .instructions p {
                                margin: 0;
                                color: #7f8c8d;
                                font-size: 14px;
                            }
                            
                            @media print {
                                body { 
                                    -webkit-print-color-adjust: exact;
                                    color-adjust: exact;
                                }
                                .print-container { 
                                    max-width: none; 
                                    padding: 0;
                                }
                                .qr-section {
                                    page-break-inside: avoid;
                                }
                            }
                        </style>
                    </head>
                    <body>
                        <div class="print-container">
                            <div class="header">
                                <div class="school-logo">
                                    <svg width="50" height="50" viewBox="0 0 50 50" fill="white">
                                        <circle cx="25" cy="25" r="20" fill="white"/>
                                        <text x="25" y="32" text-anchor="middle" font-size="24" font-weight="bold" fill="#3498db">T</text>
                                    </svg>
                                </div>
                                <h1 class="school-name">Triconnect</h1>
                                <p class="document-title">Teacher QR Code for Attendance</p>
                            </div>
                            
                            <div class="qr-section">
                                <div class="teacher-info">
                                    <h2 class="teacher-name">${teacherName}</h2>
                                    <p class="teacher-role">Teaching Staff</p>
                                </div>
                                
                                <div class="qr-code-container">
                                    ${qrHtml}
                                </div>
                                
                                <p class="user-code">User Code: ${userCode}</p>
                            </div>
                            
                            <div class="instructions">
                                <h4>📱 How to Use This QR Code:</h4>
                                <p>• Students can scan this QR code using the Triconnect mobile app<br>
                                • The system will automatically record attendance for the current class<br>
                                • This QR code is valid for all your scheduled classes</p>
                            </div>
                            
                            <div class="footer">
                                <div class="date-time">Generated on: ${currentDate} at ${currentTime}</div>
                                <div>© ${new Date().getFullYear()} Triconnect - All rights reserved</div>
                            </div>
                        </div>
                    </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
        }

        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, QR system ready...');

            // Sidebar toggle functionality
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const contentWrapper = document.getElementById('contentWrapper');
            
            const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            
            if (sidebarCollapsed) {
                sidebar.classList.add('collapsed');
                contentWrapper.classList.add('collapsed');
            }

            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                contentWrapper.classList.toggle('collapsed');
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            });

            // Add tooltips for collapsed sidebar
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('mouseenter', function() {
                    if (sidebar.classList.contains('collapsed')) {
                        const icon = this.querySelector('.nav-icon');
                        const text = this.querySelector('span').textContent;
                        icon.title = text;
                    }
                });
            });
        });
    </script>
</body>
</html> 