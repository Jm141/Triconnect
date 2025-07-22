@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $studentCount ?? 0 }}</h3>
                <p>Students</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $teacherCount ?? 0 }}</h3>
                <p>Teachers</p>
            </div>
            <div class="icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $parentCount ?? 0 }}</h3>
                <p>Parents/Families</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Students per Grade</h3>
            </div>
            <div class="card-body">
                <canvas id="studentsPerGradeChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Geofence Map</h3>
            </div>
            <div class="card-body">
                <div id="dashboard-map" style="height: 200px;"></div>
            </div>
        </div>
    </div>
</div>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('studentsPerGradeChart').getContext('2d');
    const studentsPerGradeChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($grades ?? []),
            datasets: [{
                label: 'Number of Students',
                data: @json($gradeCounts ?? []),
                backgroundColor: 'rgba(33, 147, 176, 0.7)',
                borderColor: 'rgba(33, 147, 176, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
<!-- Leaflet Map for Geofences -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let map = L.map('dashboard-map').setView([10.5333, 122.8333], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
        // Optionally, add geofence markers here if you pass them from the controller
        @if(isset($geofences))
            @foreach($geofences as $geo)
                @if($geo['type'] === 'circle')
                    L.circle([{{ $geo['lat'] }}, {{ $geo['lng'] }}], { radius: {{ $geo['radius'] }}, color: 'red' }).addTo(map);
                @elseif($geo['type'] === 'rectangle')
                    L.rectangle([[{{ $geo['swLat'] }}, {{ $geo['swLng'] }}], [{{ $geo['neLat'] }}, {{ $geo['neLng'] }}]], { color: 'blue' }).addTo(map);
                @endif
            @endforeach
        @endif
    });
</script>
@endsection
