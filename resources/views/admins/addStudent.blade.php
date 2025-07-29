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

                    
@if(session()->has('error'))
<div class="alert alert-danger">
    {!! session('error') !!}
</div>
@endif 

<form action="\students\" method="POST">
    @csrf 
    
    <div class="form-group">
        <label for="firstname">First Name:</label>
        <input type="text" name="firstname" id="firstname" class="form-control" placeholder="Enter student firstname" required>
    </div>

    <div class="form-group">
        <label for="middlename">Middle Name:</label>
        <input type="text" name="middlename" id="middlename" class="form-control" placeholder="Enter student middlename" required>
    </div>
    <div class="form-group">
        <label for="lastname">Last Name:</label>
        <input type="text" name="lastname" id="lastname" class="form-control" placeholder="Enter student lastname" required>
    </div>
    <div class="form-group">
        <label for="birth">Birthday:</label>
        <input type="date" name="birth" id="birth" class="form-control" placeholder="Enter student birthday" onchange="calculateAge()"  required>
    </div>
    <div class="form-group">
        <label for="age">Age:</label>
        <input type="tes" name="age" id="age" class="form-control" placeholder=" Student age" readonly>
    </div>
    
    <div class="form-group">
        <label for="grade">Grade Level:</label>
       <select name="grade"  class="form-control" id="grade">
        <option value="grade 1">Grade 1</option>
        <option value="grade 2">Grade 2</option>
        <option value="grade 3">Grade 3</option>
        <option value="grade 4">Grade 4</option>
        <option value="grade 5">Grade 5</option>
        <option value="grade 6">Grade 6</option>
       </select>
    </div>

   

    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" class="form-control" placeholder="Enter student email" required>
    </div>

    <div class="form-group">
        <label for="phone">Phone:</label>
        <input type="number" name="phone" id="phone" class="form-control" maxlength="11" placeholder="Enter student phone number" required>
    </div>

    <div class="form-group">
        <label for="address">Address:</label>
        <textarea name="address" id="address" class="form-control" rows="3" placeholder="Enter student address" required></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Add Student</button>
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
