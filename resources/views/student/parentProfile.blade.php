<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <style>
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #343a40;
            padding: 15px;
            position: fixed;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 10px 0;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #c2c7d0;
        }

        .content {
            margin-left: 270px;
            padding: 20px;
        }

        .main-header {
            position: fixed;
            width: 100%;
            background-color: #343a40;
            color: #c2c7d0;
            padding: 10px;
            z-index: 1;
        }

        .main-header a {
            color: #c2c7d0;
            text-decoration: none;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
  
    @if (strpos(session('userAccess')->access, 'admin') !== false)
        <p>Welcome, Admin!</p>
    
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-dark">
            <a href="#" class="navbar-brand">Dashboard</a>
        </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="https://via.placeholder.com/150" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">User Name</a>
                    </div>
                </div>

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

        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                   
                </div>
            </section>
        </div>
    </div>
    @elseif (strpos(session('userAccess')->access, 'teacher') !== false)
    <p>Teacher Good Morning, {{ session('userAccess')->access }}</p>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div>
        <p><strong>Latitude:</strong> <span id="latitude">Fetching...</span></p>
        <p><strong>Longitude:</strong> <span id="longitude">Fetching...</span></p>
        <p><strong>Address:</strong> <span id="address">Fetching...</span></p>
    </div>
    
    <div id="map" style="height: 400px; margin-top: 20px;"></div>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let map = L.map('map').setView([10.5333, 122.8333], 13);
            let marker;
    
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
    
            function updateLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function (position) {
                            let lat = position.coords.latitude;
                            let lng = position.coords.longitude;
    
                            document.getElementById("latitude").innerText = lat;
                            document.getElementById("longitude").innerText = lng;
    
                            if (marker) {
                                marker.setLatLng([lat, lng]);
                            } else {
                                marker = L.marker([lat, lng]).addTo(map);
                            }
                            map.setView([lat, lng], 15);
    
                            // Fetch address
                            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                                .then(response => response.json())
                                .then(data => {
                                    document.getElementById("address").innerText = data.display_name || "Address not found";
                                })
                                .catch(error => {
                                    console.error("Error fetching address:", error);
                                    document.getElementById("address").innerText = "Error fetching address";
                                });
    
                            // Send location to backend for saving
                            fetch("/insert_location", {
                                method: "POST",
                                headers: { 
                                    "Content-Type": "application/json", 
                                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content") 
                                },
                                body: JSON.stringify({ latitude: lat, longitude: lng })
                            })
                            .then(response => response.json())
                            .then(data => {
                                console.log("Location saved:", data);
                            })
                            .catch(error => console.error("Error saving location:", error));
    
                            fetch("/checkGeofence", {
                                method: "POST",
                                headers: { 
                                    "Content-Type": "application/json", 
                                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content") 
                                },
                                 body: JSON.stringify({ lat: lat, lng: lng })
                            })
                            .then(response => response.json())
                            .then(data => alert(data.message))
                            .catch(error => console.error("Error checking geofence:", error));
    
                        },
                        function (error) {
                            console.error("Geolocation Error:", error.message);
                            document.getElementById("latitude").innerText = "Error";
                            document.getElementById("longitude").innerText = "Error";
                            document.getElementById("address").innerText = "Unable to fetch location";
                        }
                    );
                } else {
                    alert("Geolocation is not supported by this browser.");
                }
            }
    
            updateLocation();
            setInterval(updateLocation, 60000);
        });
    </script>
    
    @elseif (strpos(session('userAccess')->access, 'parent') !== false)

    <p>Hello  {{ session('userAccess')->access }} </p>
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-dark">
            <a href="#" class="navbar-brand">Dashboard</a>
        </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="https://via.placeholder.com/150" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">User Name</a>
                    </div>
                </div>

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        
                        <li class="nav-item">
                            <a href="/student" class="nav-link">
                                <i class="nav-icon fa fa-graduation-cap"></i>
                                <p>Student List</p>
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

        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    
                    <form action="{{ route('student.update', $family->family_code) }}" method="POST">
                        @csrf
                        @method('PUT') 
                       
                    
                        <!-- Parent Details Section -->
                        <div class="card p-3 mb-4">
                            <h3>Parent Details</h3>
                            <div class="form-group">
                                <label for="parent-fname">First Name:</label>
                                <input type="text" name="parent[fname]" id="parent-fname" 
                                    value="{{ optional($family->parents->first())->fname }}" 
                                    class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="parent-mname"> Middle Name:</label>
                                <input type="text" name="parent[mname]" id="parent-mname"  value="{{ optional($family->parents->first())->mname }}" class="form-control" required>
                            </div> 
                            <div class="form-group">
                                <label for="parent-lname">Last Name:</label>
                                <input type="text" name="parent[lname]" id="parent-lname" value="{{ optional($family->parents->first())->lname }}"  class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="parent-number">Number:</label>
                                <input type="text" name="parent[number]" id="parent-number" class="form-control" value="{{ optional($family->parents->first())->number }}"  required>
                            </div>
                            <div class="form-group">
                                <label for="parent-email">Email:</label>
                                <input type="text" name="parent[email]" id="parent-email" class="form-control" value="{{ optional($family->parents->first())->email }}"  required>
                            </div>
                            <div class="form-group">
                                <label for="Address">Address:</label>
                                <input type="text" name="parent[address]" id="parent-address" class="form-control" value="{{ optional($family->first())->address }}"  required>
                            </div>
                            
                        </div>
                    
                        <!-- Student Details Section -->
                        <div class="card p-3 mb-4">
                            <h3>Student Details</h3>
                            <div id="students">
                                <div class="student-form mb-3">
                                    <div class="form-group">
                                        <label for="student-fname-0">First Name:</label>
                                        <input type="text" name="students[0][fname]" id="student-fname-0"  value="{{ optional($family->students->first())->firstname }}" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="student-mname-0">Middle Name:</label>
                                        <input type="text" name="students[0][mname]" id="student-mname-0"  value="{{ optional($family->students->first())->middlename }}" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="student-lname-0">Last Name:</label>
                                        <input type="text" name="students[0][lname]" id="student-lname-0" value="{{ optional($family->students->first())->lastname }}" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="student-birth-0">Birthday:</label>
                                        <input type="date" name="students[0][birth]" id="student-birth-0" class="form-control" value="{{ optional($family->students->first())->birth }}"  placeholder="Enter student birthday" onchange="calculateAge()"  required>
                                    </div>
                                    <div class="form-group">
                                        <label for="student-age-0">Age:</label>
                                        <input type="tes"name="students[0][age]" id="student-age-0" class="form-control" value="{{ optional($family->students->first())->age }}" placeholder=" Student age" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="student-year-0">Grade Level:</label>
                                        <select name="students[0][year]" id="student-year-0" value="{{ optional($family->students->first())->grade_level }}" class="form-control" required>
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
                                    </div>
                                    <div class="form-group">
                                        <label for="student-email-0">Email:</label>
                                        <input type="text" name="students[0][email]" id="student-email-0" class="form-control" required>
                                    </div> --}}
                                </div>
                            {{-- </div>
                            <button type="button" class="btn btn-secondary" onclick="addStudent()">Add Student</button>
                        </div> --}}
                    
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                
                </div>
            </section>
        </div>
    </div>

@else
    <p>Access Denied</p>
@endif

    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
</body>
</html>
