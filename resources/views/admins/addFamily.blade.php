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
    <script>
         function calculateAge() {
        const birthday = document.getElementById("student-birth-0").value;
        const ageField = document.getElementById("student-age-0");

        if (birthday){
            const birthdate = new Date(birthday);
            const today = new Date();

            let age = today.getFullYear() - birthdate.getFullYear();
            const monthDiff =today.getMonth() - birthdate.getMonth();

            if (monthDiff < 0 || monthDiff == 0 && today.getDate() < birthdate.getDate()){
                age--;
            }

            if(age >0){
                ageField.value = age;
               
            }else{
                ageField.value="";
                alert ("invalid birthday ");
            }
        }else{
                ageField.value ="";
            }


        }
    </script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    
    <p>Access: {{ session('userAccess')->access }}</p>
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

                   
                        <form action="/insertFamily" method="POST">
                            @csrf
                        
                            <!-- Parent Details Section -->
        <div class="card p-3 mb-4">
            <h3>Parent Details</h3>
            <div class="form-group">
                <label for="parent-fname">First Name:</label>
                <input type="text" name="parent[fname]" id="parent-fname" class="form-control" required>
            </div> 
            <div class="form-group">
                <label for="parent-mname"> Middle Name:</label>
                <input type="text" name="parent[mname]" id="parent-mname" class="form-control" required>
            </div> 
            <div class="form-group">
                <label for="parent-lname">Last Name:</label>
                <input type="text" name="parent[lname]" id="parent-lname" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="parent-number">Number:</label>
                <input type="text" name="parent[number]" id="parent-number" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="parent-email">Email:</label>
                <input type="email" name="parent[email]" id="parent-email" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="parent-password">Password:</label>
                <input type="password" name="parent[password]" id="parent-password" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="Address">Address:</label>
                <input type="text" name="parent[address]" id="parent-address" class="form-control" required>
            </div>
            
        </div>
    
        <!-- Student Details Section -->
        <div class="card p-3 mb-4">
            <h3>Student Details</h3>
            <div id="students">
                <div class="student-form mb-3">
                    <div class="form-group">
                        <label for="student-fname-0">First Name:</label>
                        <input type="text" name="students[0][fname]" id="student-fname-0" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="student-mname-0">Middle Name:</label>
                        <input type="text" name="students[0][mname]" id="student-mname-0" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="student-lname-0">Last Name:</label>
                        <input type="text" name="students[0][lname]" id="student-lname-0" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="student-birth-0">Birthday:</label>
                        <input type="date" name="students[0][birth]" id="student-birth-0" class="form-control" placeholder="Enter student birthday" onchange="calculateAge()"  required>
                    </div>
                    <div class="form-group">
                        <label for="student-age-0">Age:</label>
                        <input type="tes"name="students[0][age]" id="student-age-0" class="form-control" placeholder=" Student age" readonly>
                    </div>
                    <div class="form-group">
                        <label for="student-year-0">Grade Level:</label>
                        <select name="students[0][year]" id="student-year-0" class="form-control" required>
                            <option value="" disabled selected>Select Grade</option>
                            <option value="Grade 1">Grade 1</option>
                            <option value="Grade 2">Grade 2</option>
                            <option value="Grade 3">Grade 3</option>
                            <option value="Grade 4">Grade 4</option>
                            <option value="Grade 5">Grade 5</option>
                            <option value="Grade 6">Grade 6</option>
                        </select>
                    </div>
                    
                    {{-- <div class="form-group">
                        <label for="student-phone-0">Phone:</label>
                        <input type="text" name="students[0][phone]" id="student-phone-0" class="form-control" required>
                    </div>--}}
                    <div class="form-group">
                        <label for="student-email-0">Email:</label>
                        <input type="text" name="students[0][email]" id="student-email-0" class="form-control" required>
                    </div> 
                </div>
            </div>
            <button type="button" class="btn btn-secondary" onclick="addStudent()">Add Student</button>
        </div>
    
        <div class="form-group text-center">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>

    <script>
        let studentIndex = 1;

        function addStudent() {
            const studentsDiv = document.getElementById('students');
            const studentHtml = `
                <div class="student-form mb-3">
                    <div class="form-group">
                        <label for="student-fname-${studentIndex}">First Name:</label>
                        <input type="text" name="students[${studentIndex}][fname]" id="student-fname-${studentIndex}" class="form-control" required>
                    </div> 
                    <div class="form-group">
                        <label for="student-mname-${studentIndex}">Middle Name:</label>
                        <input type="text" name="students[${studentIndex}][mname]" id="student-mname-${studentIndex}" class="form-control" required>
                    </div>
                     <div class="form-group">
                        <label for="student-lname-${studentIndex}">Last Name:</label>
                        <input type="text" name="students[${studentIndex}][lname]" id="student-lname-${studentIndex}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="student-year-${studentIndex}">Year:</label>
                        <input type="text" name="students[${studentIndex}][year]" id="student-year-${studentIndex}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="student-phone-${studentIndex}">Phone:</label>
                        <input type="text" name="students[${studentIndex}][phone]" id="student-phone-${studentIndex}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="student-email-${studentIndex}">Email:</label>
                        <input type="text" name="students[${studentIndex}][email]" id="student-email-${studentIndex}" class="form-control" required>
                    </div>
                     <div class="form-group">
                        <label for="student-birth-${studentIndex}">Birthday:</label>
                        <input type="date" name="students[${studentIndex}][birth]" id="student-birth-${studentIndex}" onchange="calculateAge()" class="form-control" placeholder="Enter student birthday" onchange="calculateAge()"  required>
                    </div>
                    <div class="form-group">
                        <label for="student-age-${studentIndex}">Age:</label>
                        <input type="tes"name="students[${studentIndex}][age]" id="student-age-${studentIndex}" class="form-control" placeholder=" Student age" >
                    </div>
                    // <div class="form-group">
                    //     <label for="student-phone-${studentIndex}">Phone:</label>
                    //     <input type="text" name="students[${studentIndex}][phone]" id="student-phone-${studentIndex}" class="form-control" required>
                    // </div>
                </div>
            `;
            studentsDiv.insertAdjacentHTML('beforeend', studentHtml);
            studentIndex++;
        }
    </script>

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
