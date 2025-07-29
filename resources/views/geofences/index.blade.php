@extends('adminlte::page')

@section('title', 'Geofence List')

@section('content_header')
    <h1>Geofence List</h1>
@endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="row mb-3">
    <div class="col-md-3">
        <div class="info-box bg-info">
            <span class="info-box-icon"><i class="fas fa-map-marker-alt"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Geofences</span>
                <span class="info-box-number">{{ $totalGeofences ?? 0 }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box bg-success">
            <span class="info-box-icon"><i class="fas fa-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Circle</span>
                <span class="info-box-number">{{ $circleCount ?? 0 }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box bg-primary">
            <span class="info-box-icon"><i class="fas fa-square"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Rectangle</span>
                <span class="info-box-number">{{ $rectangleCount ?? 0 }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 text-right">
        <a href="{{ route('geofences.create') }}" class="btn btn-success mt-3"> <i class="fas fa-plus"></i> Add Fence</a>
    </div>
</div>
<div class="card">
    <div class="card-header bg-gradient-info">
        <h3 class="card-title">Geofence List</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="geofence-table">
                </tbody>
            </table>
        </div>
        <div id="map" style="height: 350px; margin-top: 20px;"></div>
    </div>
</div>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    let map = L.map('map').setView([10.5333, 122.8333], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);
    let markers = {};
    let currentGeofence = null;
    function loadGeofences() {
        fetch("/get-geofences")
            .then(response => response.json())
            .then(geofences => {
                let table = document.getElementById("geofence-table");
                table.innerHTML = "";
                geofences.forEach(geo => {
                    let row = document.createElement("tr");
                    let badge = geo.type === 'circle' ? '<span class=\'badge badge-success\'>Circle</span>' : '<span class=\'badge badge-primary\'>Rectangle</span>';
                    row.innerHTML = `
                        <td><i class="fas fa-map-marker-alt text-info"></i> ${geo.name}</td>
                        <td>${badge}</td>
                        <td>
                            <button class="btn btn-info btn-sm mr-1" title="Show on Map" onclick="showOnMap(${geo.id}, '${geo.type}', ${geo.lat}, ${geo.lng}, ${geo.radius}, ${geo.swLat}, ${geo.swLng}, ${geo.neLat}, ${geo.neLng})"><i class="fas fa-map"></i></button>
                            <button class="btn btn-warning btn-sm" title="Edit" onclick="editGeofence(${geo.id})"><i class="fas fa-edit"></i></button>
                        </td>
                    `;
                    table.appendChild(row);
                    markers[geo.id] = geo;
                });
            })
            .catch(error => console.error("Error fetching geofences:", error));
    }
    window.showOnMap = function (id, type, lat, lng, radius, swLat, swLng, neLat, neLng) {
        map.eachLayer(layer => {
            if (layer instanceof L.Circle || layer instanceof L.Rectangle) {
                map.removeLayer(layer);
            }
        });
        if (type === "circle") {
            currentGeofence = L.circle([lat, lng], { radius: radius, color: 'red', draggable: true }).addTo(map);
            map.setView([lat, lng], 15);
        } else if (type === "rectangle") {
            let bounds = [[swLat, swLng], [neLat, neLng]];
            currentGeofence = L.rectangle(bounds, { color: 'blue', draggable: true }).addTo(map);
            map.fitBounds(bounds);
        }
    };
    window.editGeofence = function (id) {
        if (!currentGeofence) {
            alert("Select a geofence first!");
            return;
        }
        document.getElementById("update-btn").style.display = "block";
        document.getElementById("update-btn").onclick = function () {
            let updatedData;
            if (currentGeofence instanceof L.Circle) {
                updatedData = {
                    id: id,
                    type: "circle",
                    lat: currentGeofence.getLatLng().lat,
                    lng: currentGeofence.getLatLng().lng,
                    radius: currentGeofence.getRadius()
                };
            } else if (currentGeofence instanceof L.Rectangle) {
                let bounds = currentGeofence.getBounds();
                updatedData = {
                    id: id,
                    type: "rectangle",
                    swLat: bounds.getSouthWest().lat,
                    swLng: bounds.getSouthWest().lng,
                    neLat: bounds.getNorthEast().lat,
                    neLng: bounds.getNorthEast().lng
                };
            }

            fetch("/update-geofence", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(updatedData)
            })
            .then(response => response.json())
            .then(data => {
                alert("Geofence updated successfully!");
                loadGeofences();
            })
            .catch(error => console.error("Error updating geofence:", error));
        };
    };
    loadGeofences();
});
</script>
@endsection