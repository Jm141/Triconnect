<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Triconnect Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script> 
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    
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
<body class="hold-transition sidebar-mini layout-fixed bg-triconnect-light text-triconnect-dark">
  
    @if (strpos(session('userAccess')->access, 'admin') !== false)
        <p>Welcome, Admin!</p>
    
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-dark bg-triconnect shadow-lg">
            <a href="#" class="navbar-brand flex items-center">
                <img src="{{ asset('images/triconnect.png') }}" alt="Triconnect Logo" class="w-10 h-10 rounded-full mr-2 bg-white p-1 shadow" />
                <span class="text-white text-2xl font-bold tracking-wide">Triconnect</span>
            </a>
        </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4 bg-triconnect-dark">
            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="https://via.placeholder.com/150" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block text-white">User Name</a>
                    </div>
                </div>

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="/teacher-list" class="nav-link text-triconnect-accent hover:bg-triconnect-light">
                                <i class="nav-icon fa fa-users"></i>
                                <p>Teacher List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/notifications" class="nav-link text-triconnect-accent hover:bg-triconnect-light">
                                <i class="nav-icon fa fa-bell"></i>
                                <p>Notifications</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/roomList" class="nav-link text-triconnect-accent hover:bg-triconnect-light">
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
                        <li class="nav-item">
                            <a href="/subscription" class="nav-link">
                                <i class="nav-icon fa fa-graduation-cap"></i>
                                <p>Subscription List</p>
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
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="csrf-token" content="{{ csrf_token() }}"> --}}

    <div>
        <p><strong>Latitude:</strong> <span id="latitude">Fetching...</span></p>
        <p><strong>Longitude:</strong> <span id="longitude">Fetching...</span></p>
        <p><strong>Address:</strong> <span id="address">Fetching...</span></p>
    </div>

<button id="scanQrBtn">Scan QR Code</button>
<input type="file" accept="image/*" id="qrInput" capture="environment" style="display: none;">

    
    <div id="map" style="height: 400px; margin-top: 20px;"></div>
    
   
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
    
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
                        </li><li class="nav-item">
                            <li class="nav-item">
                                <a href="{{ route('student.create', ['family_code' => session('userAccess')->userCode]) }}" class="nav-link">
                                    <i class="nav-icon fa fa-graduation-cap"></i>
                                    <p>Add Student</p>
                                </a>
                                
                            </li>
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
                   
                </div>
            </section>
        </div>
    </div>
    @elseif (strpos(session('userAccess')->access, 'student') !== false)
<p> welcome studnet</p>

<div>
    <p><strong>Latitude:</strong> <span id="latitude">Fetching...</span></p>
    <p><strong>Longitude:</strong> <span id="longitude">Fetching...</span></p>
    <p><strong>Address:</strong> <span id="address">Fetching...</span></p>
</div>

<button id="scanQrBtn">Scan QR Code</button>
<input type="file" accept="image/*" id="qrInput" capture="environment" style="display: none;">


<div id="map" style="height: 400px; margin-top: 20px;"></div>

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
                        // .then(data => alert(data.message))
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

    document.getElementById('scanQrBtn').addEventListener('click', function() {
    document.getElementById('qrInput').click(); 
   });

document.getElementById('qrInput').addEventListener('change', function(event) {
let file = event.target.files[0];
if (!file) return;

let reader = new FileReader();
reader.readAsDataURL(file);
reader.onload = function() {
    let img = new Image();
    img.src = reader.result;
    img.onload = function() {
        let canvas = document.createElement('canvas');
        let ctx = canvas.getContext('2d');

        canvas.width = img.width;
        canvas.height = img.height;
        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

        let imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        let qrCode = jsQR(imageData.data, imageData.width, imageData.height);

        if (qrCode) {
            sendQRCode(qrCode.data); 
        } else {
            alert("No QR code detected.");
        }
    };
};
});

function sendQRCode(qrValue) {
let formData = new FormData();
formData.append('qr_value', qrValue);

fetch("{{ route('attendance.scan') }}".trim(), { 
    method: "POST",
    headers: {
"X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
},

    body: formData
})
.then(response => response.json())
.then(data => {
    alert(data.message);
})
.catch(error => console.error("Error:", error));
}
</script>

@else
    <p>Access Denied</p>
@endif

    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
</body>
</html>
