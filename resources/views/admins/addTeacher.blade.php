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
    @if (strpos(session('userAccess')->access, 'admin') !== false)
                        <p>Welcome, Admin!</p>
                   

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
                            <a href="/teacher-list" class="nav-link">
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
                            <a href="/roomList" class="nav-link">
                                <i class="nav-icon fa fa-building"></i>
                                <p>Room List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/family-list" class="nav-link">
                                <i class="nav-icon fa fa-home"></i>
                                <p>Family List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/student-list" class="nav-link">
                                <i class="nav-icon fa fa-graduation-cap"></i>
                                <p>Student List</p>
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
                    <p>Access: {{ session('userAccess')->access }}</p>

                    
                        <form action="/createTeacher" method="POST">
                            @csrf
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Teacher Information</h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="firstname">First Name</label>
                                        <input type="text" id="firstname" name="firstname" class="form-control" required>
                                    </div>
                    
                                    <div class="form-group">
                                        <label for="middlename">Middle Name</label>
                                        <input type="text" id="middlename" name="middlename" class="form-control">
                                    </div>
                    
                                    <div class="form-group">
                                        <label for="lastname">Last Name</label>
                                        <input type="text" id="lastname" name="lastname" class="form-control" required>
                                    </div>
                    
                                    <div class="form-group">
                                        <label for="birthday">Birthday</label>
                                        <input type="date" id="birthday" name="birthday" class="form-control" onchange="calculateAge()"  required>
                                    </div>
                    
                                    <div class="form-group">
                                        <label for="age">Age</label>
                                        <input type="text" id="age" name="age" class="form-control" readonly>
                                    </div>
                    
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input type="text" id="address" name="address" class="form-control" required>
                                    </div>
                    
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" id="email" name="email" class="form-control" required>
                                    </div>
                    
                                    <div class="form-group">
                                        <label for="phone">Phone Number</label>
                                        <input type="text" id="phone" name="phone" class="form-control" required>
                                    </div>
                    
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" id="username" name="username" class="form-control" required>
                                    </div>
                    
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" id="password" name="password" class="form-control" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="access_level">Access Level</label>
                                        <select id="access_level" name="access_level" class="form-control" required>
                                            <option value="">Select Access Level</option>
                                            <option value="super_admin">Super Admin</option>
                                            <option value="admin">Admin</option>
                                            <option value="principal">Principal</option>
                                            <option value="teacher">Teacher</option>
                                        </select>
                                    </div>
                                </div>
                    
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Save Teacher</button>
                                </div>
                            </div>
                        </form>

                    @elseif (strpos(session('userAccess')->access, 'teacher') !== false)
                    <p>Teacher Good Morning, {{ session('userAccess')->access }}</p>
                @else
                    <p>Access Denied</p>
                @endif
                </div>
            </section>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
</body>
</html>
