<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher QR Code - Triconnect</title>
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

        .qr-container {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--shadow);
            text-align: center;
            max-width: 600px;
            margin: 0 auto;
        }

        .qr-code {
            margin: 2rem 0;
            padding: 1rem;
            background: var(--light-gray);
            border-radius: var(--border-radius);
            display: inline-block;
        }

        .teacher-info {
            background: var(--light-gray);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: left;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--light-gray);
        }

        .info-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .info-label {
            font-weight: 600;
            color: var(--primary-color);
        }

        .info-value {
            color: var(--dark-gray);
        }

        .timer {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--accent-color);
            text-align: center;
            margin: 1rem 0;
        }

        .status-active {
            color: var(--success-color);
            font-weight: 600;
        }

        .reusable-badge {
            background: var(--success-color);
            color: var(--white);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            margin-bottom: 1rem;
            display: inline-block;
        }

        @media print {
            .navbar, .btn, .no-print {
                display: none !important;
            }
            
            .content-wrapper {
                margin-left: 0 !important;
                padding: 0 !important;
            }
            
            .qr-container {
                box-shadow: none !important;
                border: 1px solid #ccc !important;
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
                        <button onclick="window.print()" class="nav-link btn btn-link" title="Print QR Code">
                            <i class="fa fa-print"></i>
                        </button>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('qr.generate') }}" class="nav-link" title="Back to QR Generation">
                            <i class="fa fa-arrow-left"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="content-wrapper">
            <div class="container-fluid">
                <!-- Page Header -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h1 class="h3 mb-0 text-gray-800">Reusable Teacher QR Code</h1>
                        <p class="text-muted">This QR code can be used for all your classes throughout the week</p>
                    </div>
                </div>

                <!-- Teacher Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="teacher-info">
                            <h5><i class="fa fa-user"></i> Teacher Information</h5>
                            <div class="info-item">
                                <span class="info-label">Name:</span>
                                <span class="info-value">{{ $teacher->firstname ?? 'N/A' }} {{ $teacher->lastname ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Staff Code:</span>
                                <span class="info-value">{{ $teacher->staff_code ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Email:</span>
                                <span class="info-value">{{ $teacher->email ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Status:</span>
                                <span class="info-value status-active">{{ ucfirst($teacher->status ?? 'Active') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QR Code Display -->
                <div class="row">
                    <div class="col-12">
                        <div class="qr-container">
                            <div class="reusable-badge">
                                <i class="fa fa-refresh"></i> SIMPLE TEACHER QR CODE
                            </div>
                            
                            <h4><i class="fa fa-qrcode"></i> Scan QR Code for Attendance</h4>
                            
                            <div class="timer" id="timer">
                                <i class="fa fa-clock-o"></i> <span id="timeDisplay">Loading...</span>
                            </div>
                            
                            <div class="qr-code">
                                <img src="data:image/png;base64,{{ base64_encode($qrCode) }}" alt="QR Code" style="max-width: 100%; height: auto;">
                            </div>
                            
                            <p class="text-muted">
                                <strong>How it works:</strong><br>
                                1. Display this QR code on your screen or print it<br>
                                2. Students scan this QR code using their mobile devices<br>
                                3. QR code contains only your teacher userCode: <strong>{{ $qrData }}</strong><br>
                                4. System automatically detects current time and day<br>
                                5. Finds your active class schedule for that time<br>
                                6. Records attendance for the correct subject<br>
                                7. Works for all your classes throughout the week!
                            </p>
                            
                            <div class="mt-4">
                                <button onclick="window.print()" class="btn btn-primary me-2">
                                    <i class="fa fa-print"></i> Print QR Code
                                </button>
                                <a href="{{ route('qr.generate') }}" class="btn btn-secondary">
                                    <i class="fa fa-arrow-left"></i> Back to Schedule
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QR Code Data (for debugging) -->
                <div class="row mt-4 no-print">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fa fa-code"></i> QR Code Data (Debug)</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>QR Code Content:</strong> <code>{{ $qrData }}</code></p>
                                <p><strong>Type:</strong> Simple Teacher UserCode</p>
                                <p><strong>Description:</strong> This QR code contains only the teacher's userCode. When scanned, the system will automatically find the teacher's current active class based on the current time and day.</p>
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
        // Update timer every second
        function updateTimer() {
            const now = new Date();
            const timeString = now.toLocaleTimeString();
            document.getElementById('timeDisplay').textContent = timeString;
        }
        
        // Update timer immediately and then every second
        updateTimer();
        setInterval(updateTimer, 1000);
    </script>
</body>
</html> 