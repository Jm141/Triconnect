<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Geofence Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap4.min.css">
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
            <a href="#" class="navbar-brand">Geofence Management</a>
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
                            <a href="{{ route('geofence') }}" class="nav-link active">
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
                    
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-6">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-map-marker-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Geofences</span>
                                    <span class="info-box-number" id="totalGeofences">{{ isset($geofences) ? $geofences->count() : 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Circle Geofences</span>
                                    <span class="info-box-number" id="circleGeofences">{{ isset($geofences) ? $geofences->where('type', 'circle')->count() : 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-square"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Rectangle Geofences</span>
                                    <span class="info-box-number" id="rectangleGeofences">{{ isset($geofences) ? $geofences->where('type', 'rectangle')->count() : 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="info-box bg-danger">
                                <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Active Today</span>
                                    <span class="info-box-number" id="activeToday">{{ isset($geofences) ? $geofences->where('created_at', '>=', now()->startOfDay())->count() : 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Existing Geofences Table -->
                    <div class="card mb-4">
                        <div class="card-header bg-gradient-success">
                            <h3 class="card-title">Existing Geofences</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="geofencesTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Location</th>
                                            <th>Radius/Dimensions</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="geofencesTableBody">
                                        @if(isset($geofences) && $geofences->count() > 0)
                                            @foreach($geofences as $geofence)
                                                <tr>
                                                    <td><strong>{{ $geofence->name }}</strong></td>
                                                    <td>
                                                        @if($geofence->type === 'circle')
                                                            <span class="badge badge-info">Circle</span>
                                                        @else
                                                            <span class="badge badge-primary">Rectangle</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($geofence->type === 'circle')
                                                            <span class="location-text" data-lat="{{ $geofence->lat }}" data-lng="{{ $geofence->lng }}">
                                                                <i class="fas fa-spinner fa-spin"></i> Loading...
                                                            </span>
                                                        @else
                                                            <span class="location-text" data-swlat="{{ $geofence->swLat }}" data-swlng="{{ $geofence->swLng }}" data-nelat="{{ $geofence->neLat }}" data-nelng="{{ $geofence->neLng }}">
                                                                <i class="fas fa-spinner fa-spin"></i> Loading...
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($geofence->type === 'circle')
                                                            <small>{{ number_format($geofence->radius, 2) }} meters radius</small>
                                                        @else
                                                            @php
                                                                $width = $geofence->neLng - $geofence->swLng;
                                                                $height = $geofence->neLat - $geofence->swLat;
                                                                $widthMeters = $width * 111320 * cos(deg2rad($geofence->swLat));
                                                                $heightMeters = $height * 111320;
                                                            @endphp
                                                            <small>{{ number_format($widthMeters, 0) }}m x {{ number_format($heightMeters, 0) }}m</small>
                                                        @endif
                                                    </td>
                                                    <td><small>{{ $geofence->created_at->format('M j, Y g:i A') }}</small></td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <button class="btn btn-info btn-sm" onclick="viewGeofence({{ $geofence->id }}, '{{ $geofence->type }}', {{ $geofence->type === 'circle' ? $geofence->lat : $geofence->swLat }}, {{ $geofence->type === 'circle' ? $geofence->lng : $geofence->swLng }}, {{ $geofence->type === 'circle' ? $geofence->radius : 'null' }}, {{ $geofence->type === 'rectangle' ? $geofence->neLat : 'null' }}, {{ $geofence->type === 'rectangle' ? $geofence->neLng : 'null' }})" title="View on Map">
                                                                <i class="fas fa-map-marker-alt"></i>
                                                            </button>
                                                            <button class="btn btn-warning btn-sm" onclick="editGeofence({{ $geofence->id }})" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button class="btn btn-danger btn-sm" onclick="deleteGeofence({{ $geofence->id }})" title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">
                                                    <i class="fas fa-map-marker-alt fa-2x mb-2"></i>
                                                    <br>No geofences found. Draw a geofence on the map below to get started.
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Map Section -->
                    <div class="card">
                        <div class="card-header bg-gradient-info">
                            <h3 class="card-title">Draw a Geofence</h3>
                        </div>
                        <div class="card-body">
                            <p class="mb-3">Use the map below to draw a geofence (circle or rectangle). After drawing, you will be prompted to name the geofence. Your current location will be shown if available.</p>
                            <div id="map" style="height: 500px; border-radius: 8px; overflow: hidden;"></div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    
    <script>
        // Initialize DataTable for geofences
        $(document).ready(function() {
            $('#geofencesTable').DataTable({
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'print'
                ],
                language: {
                    search: "Search geofences:",
                    lengthMenu: "Show _MENU_ geofences per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ geofences",
                    infoEmpty: "No geofences available",
                    infoFiltered: "(filtered from _MAX_ total geofences)"
                },
                pageLength: 10,
                order: [[4, 'desc']], // Sort by created date descending
                columnDefs: [
                    {
                        targets: [1, 5], // Type and Actions columns
                        className: 'text-center'
                    }
                ]
            });

            // Load reverse geocoding for all location elements
            loadReverseGeocoding();
        });

        // Reverse geocoding function
        function loadReverseGeocoding() {
            const locationElements = document.querySelectorAll('.location-text');
            
            locationElements.forEach(element => {
                let lat, lng;
                
                if (element.hasAttribute('data-lat') && element.hasAttribute('data-lng')) {
                    // Circle geofence
                    lat = parseFloat(element.getAttribute('data-lat'));
                    lng = parseFloat(element.getAttribute('data-lng'));
                } else if (element.hasAttribute('data-swlat') && element.hasAttribute('data-swlng')) {
                    // Rectangle geofence - use center point
                    const swLat = parseFloat(element.getAttribute('data-swlat'));
                    const swLng = parseFloat(element.getAttribute('data-swlng'));
                    const neLat = parseFloat(element.getAttribute('data-nelat'));
                    const neLng = parseFloat(element.getAttribute('data-nelng'));
                    lat = (swLat + neLat) / 2;
                    lng = (swLng + neLng) / 2;
                } else {
                    return;
                }

                // Fetch reverse geocoding data
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.display_name) {
                            // Extract street name or address
                            let address = data.display_name;
                            if (data.address) {
                                if (data.address.road) {
                                    address = data.address.road;
                                    if (data.address.house_number) {
                                        address = data.address.house_number + ' ' + address;
                                    }
                                } else if (data.address.suburb) {
                                    address = data.address.suburb;
                                } else if (data.address.city) {
                                    address = data.address.city;
                                }
                            }
                            element.innerHTML = `<i class="fas fa-map-marker-alt text-primary"></i> ${address}`;
                        } else {
                            element.innerHTML = `<i class="fas fa-map-marker-alt text-muted"></i> ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching reverse geocoding:', error);
                        element.innerHTML = `<i class="fas fa-map-marker-alt text-muted"></i> ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                    });
            });
        }

        // Map functionality
        let map = L.map('map').setView([10.5333, 122.8333], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
        
        let drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);
        
        let drawControl = new L.Control.Draw({
            draw: {
                polygon: false,
                polyline: false,
                circle: true,
                rectangle: true,
                marker: false
            },
            edit: {
                featureGroup: drawnItems
            }
        });
        map.addControl(drawControl);
        
        let geofenceShape = null;
        map.on('draw:created', function (e) {
            let layer = e.layer;
            drawnItems.clearLayers();
            drawnItems.addLayer(layer);
            let geofenceData = {};
            let name = prompt("Enter a name for the geofence:");
            if (!name) {
                alert("Geofence name is required!");
                drawnItems.clearLayers();
                return;
            }
            if (layer instanceof L.Circle) {
                let center = layer.getLatLng();
                let radius = layer.getRadius();
                geofenceData = {
                    name: name,
                    type: "circle",
                    lat: center.lat,
                    lng: center.lng,
                    radius: radius
                };
            } else if (layer instanceof L.Rectangle) {
                let bounds = layer.getBounds();
                geofenceData = {
                    name: name,
                    type: "rectangle",
                    swLat: bounds.getSouthWest().lat,
                    swLng: bounds.getSouthWest().lng,
                    neLat: bounds.getNorthEast().lat,
                    neLng: bounds.getNorthEast().lng
                };
            }
            fetch("/save_geofence", {
                method: "POST",
                headers: { 
                    "Content-Type": "application/json", 
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content") 
                },
                body: JSON.stringify(geofenceData)
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message,
                });
                // Refresh the table after successful save
                location.reload();
            })
            .catch(error => {
                console.error("Error saving geofence:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to save geofence.',
                });
            });
        });
        
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                let userLat = position.coords.latitude;
                let userLng = position.coords.longitude;
                map.setView([userLat, userLng], 15);
                L.marker([userLat, userLng]).addTo(map).bindPopup("Your current location").openPopup();
            }, function () {
                // Permission denied
            });
        }

        // Geofence table functions
        function viewGeofence(id, type, lat, lng, radius, neLat, neLng) {
            // Clear existing drawn items
            drawnItems.clearLayers();
            
            let mapCenter, mapZoom;
            
            if (type === 'circle') {
                mapCenter = [lat, lng];
                mapZoom = 15;
                
                // Add circle to map
                let circle = L.circle(mapCenter, radius).addTo(map);
                drawnItems.addLayer(circle);
                
                // Add center marker
                L.marker(mapCenter).addTo(map).bindPopup(`Circle Center - ${radius.toFixed(2)}m radius`).openPopup();
                
            } else if (type === 'rectangle') {
                let swLat = parseFloat(neLat);
                let swLng = parseFloat(neLng);
                let neLat = parseFloat(neLat);
                let neLng = parseFloat(neLng);
                
                mapCenter = [(swLat + neLat) / 2, (swLng + neLng) / 2];
                mapZoom = 14;
                
                // Add rectangle to map
                let sw = L.latLng(swLat, swLng);
                let ne = L.latLng(neLat, neLng);
                let rectangle = L.rectangle(L.bounds(sw, ne)).addTo(map);
                drawnItems.addLayer(rectangle);
                
                // Add corner markers
                L.marker(sw).addTo(map).bindPopup("Southwest Corner").openPopup();
                L.marker(ne).addTo(map).bindPopup("Northeast Corner").openPopup();
            }
            
            // Set map view
            map.setView(mapCenter, mapZoom);
            
            // Fit bounds to show the entire geofence
            if (drawnItems.getBounds().isValid()) {
                map.fitBounds(drawnItems.getBounds().pad(0.1));
            }
            
            // Show success message
            Swal.fire({
                title: 'Geofence Displayed',
                text: `The ${type} geofence has been highlighted on the map.`,
                icon: 'success',
                confirmButtonText: 'OK'
            });
        }

        function editGeofence(id) {
            Swal.fire({
                title: 'Edit Geofence?',
                text: "This will allow you to modify the geofence.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, edit it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Add edit functionality
                    Swal.fire('Edit Mode', 'Geofence edit mode activated.', 'info');
                }
            });
        }

        function deleteGeofence(id) {
            Swal.fire({
                title: 'Delete Geofence?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send delete request
                    fetch(`/geofences/${id}`, {
                        method: 'DELETE',
                        headers: { 
                            "Content-Type": "application/json", 
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content") 
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire('Deleted!', 'Geofence has been deleted.', 'success');
                        // Refresh the page to update the table
                        location.reload();
                    })
                    .catch(error => {
                        console.error("Error deleting geofence:", error);
                        Swal.fire('Error!', 'Failed to delete geofence.', 'error');
                    });
                }
            });
        }
    </script>
</body>
</html> 