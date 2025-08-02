<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Teacher List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .content-wrapper {
            margin-left: 250px;
        }

        .content {
            padding: 20px;
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
            <a href="#" class="navbar-brand">Teacher</a>
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
                            <a href="/notifications" class="nav-link">
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
                    <form action="{{ route('admins.update', $teacher->id) }}" method="POST">
                        @csrf 
                        @method('PUT') 
                        <input type="hidden" name="staffCode" id="staffCode" class="form-control" value="{{ $teacher->staff_code }}" required>
                        
                        <div class="form-group">
                            <label for="firstname">First Name:</label>
                            <input type="text" name="firstname" id="firstname" class="form-control" value="{{ $teacher->firstname }}" required>
                        </div>
                    
                        <div class="form-group">
                            <label for="middlename">Middle Name:</label>
                            <input type="text" name="middlename" id="middlename" class="form-control" value="{{ $teacher->middlename }}" required>
                        </div>
                    
                        <div class="form-group">
                            <label for="lastname">Last Name:</label>
                            <input type="text" name="lastname" id="lastname" class="form-control" value="{{ $teacher->lastname }}" required>
                        </div>
                    
                        <div class="form-group">
                            <label for="birth">Birthday:</label>
                            <input type="date" name="birth" id="birth" class="form-control" value="{{ $teacher->birth }}" required>
                        </div>
                    
                        <div class="form-group">
                            <label for="age">Age:</label>
                            <input type="number" name="age" id="age" class="form-control" value="{{ $teacher->age }}" required>
                        </div>
                    
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ $teacher->email }}" required>
                        </div>
                    
                        <div class="form-group">
                            <label for="phone">Phone:</label>
                            <input type="number" name="phone" id="phone" class="form-control" maxlength="11" value="{{ $teacher->phone }}" required>
                        </div>
                    
                        <div class="form-group">
                            <label for="address">Address:</label>
                            <textarea name="address" id="address" class="form-control" rows="3" placeholder="Enter address" required>{{ $teacher->address }}</textarea>
                        </div>
                    
                        <div class="form-group">
                            <label for="role">User Role:</label>
                            <select name="role" id="role" class="form-control">
                                <option value="teacher" {{ $teacher->role == 'teacher' ? 'selected' : '' }}>Teacher</option>
                                <option value="principal" {{ $teacher->role == 'principal' ? 'selected' : '' }}>Principal</option>
                                <option value="admin" {{ $teacher->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>
                    
                        <div class="form-group">
                            <label for="canupdate">Can Update:</label>
                            <input type="checkbox" name="canupdate" id="canupdate" {{ $teacher->canupdate ? 'checked' : '' }}>
                            <small class="form-text text-muted">Check this box to grant 'Can Update' access.</small>
                        </div>
                    
                        <button type="submit" class="btn btn-primary">Update User</button>
                    </form>
                </div>
            </section>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
</body>
</html>
