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

        .navbar-brand img {
            width: 40px;
            height: 40px;
            margin-right: 12px;
            border-radius: 50%;
            background: var(--white);
            padding: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .main-sidebar {
            transition: width 0.3s ease;
        }

        .main-sidebar.collapsed {
            width: 70px;
        }

        .main-sidebar.collapsed .user-panel .info,
        .main-sidebar.collapsed .nav-link p {
            display: none;
        }

        .main-sidebar.collapsed .user-panel {
            justify-content: center;
            padding: 1rem 0.5rem;
        }

        .main-sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 0.875rem 0.5rem;
        }

        .main-sidebar.collapsed .nav-icon {
            margin-right: 0;
            font-size: 1.2rem;
        }

        .content-wrapper {
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
        <!-- Sidebar Toggle Button -->
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fa fa-bars"></i>
        </button>

        <nav class="main-header navbar navbar-expand navbar-dark bg-triconnect shadow-lg">
            <div class="navbar-brand d-flex align-items-center">
                <img src="{{ asset('images/Triconnect.png') }}" alt="Triconnect Logo" style="height: 35px; margin-right: 10px;">
                <span class="text-white text-2xl font-bold tracking-wide">Triconnect</span>
            </div>
        </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4 bg-triconnect-dark" id="sidebar">
            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="/images/Triconnect.png" class="img-circle elevation-2" alt="User Image" onerror="this.onerror=null; this.src='https://via.placeholder.com/150/3498db/ffffff?text=T';">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block text-white">Admin User</a>
                    </div>
                </div>

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="/family-list" class="nav-link text-triconnect-accent hover:bg-triconnect-light">
                                <i class="nav-icon fa fa-home"></i>
                                <p>Family List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/student-list" class="nav-link text-triconnect-accent hover:bg-triconnect-light">
                                <i class="nav-icon fa fa-graduation-cap"></i>
                                <p>Student List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/teacher-list" class="nav-link text-triconnect-accent hover:bg-triconnect-light">
                                <i class="nav-icon fa fa-users"></i>
                                <p>Teacher List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/roomList" class="nav-link text-triconnect-accent hover:bg-triconnect-light">
                                <i class="nav-icon fa fa-building"></i>
                                <p>Room</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/geofence" class="nav-link text-triconnect-accent hover:bg-triconnect-light">
                                <i class="nav-icon fa fa-map-marker-alt"></i>
                                <p>Geofence</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/subscription" class="nav-link text-triconnect-accent hover:bg-triconnect-light">
                                <i class="nav-icon fa fa-credit-card"></i>
                                <p>Subscription Plans</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('billing.index') }}" class="nav-link text-triconnect-accent hover:bg-triconnect-light">
                                <i class="fas fa-file-invoice-dollar text-triconnect-accent"></i>
                                <p>Billing Logs</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper" id="contentWrapper">
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Admin Dashboard</h3>
                                </div>
                                <div class="card-body">
                                    <p>Welcome to the Admin Dashboard. Use the navigation menu to manage:</p>
                                    <ul>
                                        <li><strong>Family List:</strong> Manage family information and relationships</li>
                                        <li><strong>Student List:</strong> View and manage student records</li>
                                        <li><strong>Teacher List:</strong> Manage teacher information</li>
                                        <li><strong>Room:</strong> Manage room assignments and QR codes</li>
                                        <li><strong>Geofence:</strong> Set up and manage geofencing areas</li>
                                        <li><strong>Subscription Plans:</strong> Manage subscription plans and pricing</li>
                                        <li><strong>Billing Logs:</strong> View and manage billing records</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    @elseif (strpos(session('userAccess')->access, 'teacher') !== false)
        <script>
            window.location.href = '/teacher/dashboard';
        </script>
        <div class="text-center p-5">
            <p>Redirecting to Teacher Dashboard...</p>
            <p>If you are not redirected automatically, <a href="/teacher/dashboard">click here</a>.</p>
        </div>
    @elseif (strpos(session('userAccess')->access, 'parent') !== false)
        <script>
            window.location.href = '/parent/dashboard';
        </script>
        <div class="text-center p-5">
            <p>Redirecting to Parent Dashboard...</p>
            <p>If you are not redirected automatically, <a href="/parent/dashboard">click here</a>.</p>
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

    @elseif (strpos(session('userAccess')->access, 'principal') !== false)
        <script>
            window.location.href = '/principal/dashboard';
        </script>
        <div class="text-center p-5">
            <p>Redirecting to Principal Dashboard...</p>
            <p>If you are not redirected automatically, <a href="/principal/dashboard">click here</a>.</p>
        </div>
@else
    <p>Access Denied</p>
@endif

    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
    <script>
        // Sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const contentWrapper = document.getElementById('contentWrapper');
            
            // Check if sidebar state is saved in localStorage
            const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            
            if (sidebarCollapsed) {
                sidebar.classList.add('collapsed');
                contentWrapper.classList.add('collapsed');
            }

            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                contentWrapper.classList.toggle('collapsed');
                
                // Save state to localStorage
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            });

            // Add tooltips for collapsed sidebar
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('mouseenter', function() {
                    if (sidebar.classList.contains('collapsed')) {
                        const icon = this.querySelector('.nav-icon');
                        const text = this.querySelector('p').textContent;
                        icon.title = text;
                    }
                });
            });
        });
    </script>
</body>
</html>
