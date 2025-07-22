@extends('adminlte::page')

@section('title', 'Create Geofence')
@section('content_header')
    <h1>Create Geofence</h1>
@endsection
@section('content')
<div class="card">
    <div class="card-header bg-gradient-info">
        <h3 class="card-title">Draw a Geofence</h3>
    </div>
    <div class="card-body">
        <p class="mb-3">Use the map below to draw a geofence (circle or rectangle). After drawing, you will be prompted to name the geofence. Your current location will be shown if available.</p>
        <div id="map" style="height: 400px; border-radius: 8px; overflow: hidden;"></div>
        <a href="{{ route('geofences.index') }}" class="btn btn-secondary mt-3">Back to Geofence List</a>
    </div>
</div>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />
<script>
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
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name=\'csrf-token\']').getAttribute("content") },
            body: JSON.stringify(geofenceData)
        })
        .then(response => response.json())
        .then(data => alert(data.message))
        .catch(error => console.error("Error saving geofence:", error));
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
</script>
@endsection
