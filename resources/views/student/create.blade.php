<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add Student</title>
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

    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark navbar-dark">
            <a href="#" class="navbar-brand">Student</a>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="https://via.placeholder.com/150" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">User Name</a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="/student" class="nav-link">
                                <i class="nav-icon fa fa-graduation-cap"></i>
                                <p>Student</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('student.edit', session('userAccess')->userCode) }}" class="nav-link">
                                <i class="nav-icon fa fa-user"></i>
                                <p>Edit Profile</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <form action="\insertStudentP\" method="POST">
                        @csrf 

                        <div class="form-group">
                            <label for="firstname">First Name:</label>
                            <input type="text" name="firstname" id="firstname" class="form-control" placeholder="Enter student firstname" required>
                            <input type="hidden" name="famCode" id="famCode" class="form-control" value="{{ $family->family_code }}" required>
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
                            <input type="text" name="age" id="age" class="form-control" placeholder="Student age" readonly>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="text" name="email" id="email" class="form-control" placeholder="Student email" readonly>
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
                        
                        <button type="button" class="btn btn-primary" onclick="showSubscriptionModal()">Add Student</button>
                    </form>
                </div>
            </section>
        </div>

        <div id="subscriptionModal" style="display:none;">
            <script>
                Swal.fire({
                    title: 'Choose a Subscription Plan',
                    html: `
                        <div>
                            <h4>Standard Subscription</h4>
                            <p>Access to basic student management features.</p>
                        </div>
                        <div>
                            <h4>Premium Subscription</h4>
                            <p>Access to advanced analytics and priority support.</p>
                        </div>
                    `,
                    showCloseButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Standard',
                    cancelButtonText: 'Premium'
                });
            </script>
        </div>
    </div>

    <script>
        function calculateAge() {
            const birthday = document.getElementById("birth").value;
            const ageField = document.getElementById("age");

            if (birthday) {
                const birthdate = new Date(birthday);
                const today = new Date();

                let age = today.getFullYear() - birthdate.getFullYear();
                const monthDiff = today.getMonth() - birthdate.getMonth();

                if (monthDiff < 0 || (monthDiff == 0 && today.getDate() < birthdate.getDate())) {
                    age--;
                }

                if (age > 0) {
                    ageField.value = age;
                } else {
                    ageField.value = "";
                    alert("Invalid birthday");
                }
            } else {
                ageField.value = "";
            }
        }

        function showSubscriptionModal() {
            const modal = document.getElementById('subscriptionModal');
            if (modal) {
                Swal.fire({
                    title: 'Subscription Options',
                    html: `
                        <div>
                            <h4>Standard Subscription</h4>
                            <p>Access to basic student management features.</p>
                        </div>
                        <div>
                            <h4>Premium Subscription</h4>
                            <p>Access to advanced analytics and priority support.</p>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Subscribe to Standard',
                    cancelButtonText: 'Subscribe to Premium'
                }).then((result) => {
                    if (result.isConfirmed) {
                        alert('Standard Subscription Selected!');
                    } else {
                        alert('Premium Subscription Selected!');
                    }
                });
            }
        }
    </script>
</body>
</html>