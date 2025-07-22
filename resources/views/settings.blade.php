@extends('adminlte::page')

@section('title', 'System Settings & Help')

@section('content_header')
    <h1>System Features & Help</h1>
@endsection

@section('content')
<div class="card">
    <div class="card-header bg-gradient-info">
        <h3 class="card-title">Triconnect Features</h3>
    </div>
    <div class="card-body">
        <ul class="list-group mb-4">
            <li class="list-group-item"><i class="fas fa-user-graduate text-info mr-2"></i> <b>Student & Teacher Management:</b> Add, edit, and manage student and teacher records easily.</li>
            <li class="list-group-item"><i class="fas fa-users text-info mr-2"></i> <b>Family & Parent Integration:</b> Link students to families and manage parent access.</li>
            <li class="list-group-item"><i class="fas fa-map-marker-alt text-info mr-2"></i> <b>Geofence Tracking:</b> Create and monitor geofences for real-time location awareness.</li>
            <li class="list-group-item"><i class="fas fa-chart-bar text-info mr-2"></i> <b>Analytics & Reports:</b> View statistics and charts for students, teachers, and geofences.</li>
            <li class="list-group-item"><i class="fas fa-bell text-info mr-2"></i> <b>Instant Alerts:</b> Receive notifications for important events and geofence breaches.</li>
            <li class="list-group-item"><i class="fas fa-lock text-info mr-2"></i> <b>Secure Access:</b> Role-based authentication and secure login for all users.</li>
        </ul>
        <h4>How to Use</h4>
        <ol>
            <li><b>Dashboard:</b> View key statistics and charts for your school.</li>
            <li><b>Teachers/Students:</b> Use the navigation bar to manage teacher and student records.</li>
            <li><b>Families/Parents:</b> Manage family and parent information, and link students to families.</li>
            <li><b>Rooms:</b> Add and manage rooms for classes and activities.</li>
            <li><b>Geofences:</b> Create geofences on the map to monitor student locations and receive alerts.</li>
            <li><b>Settings:</b> Visit this page for help and system information.</li>
        </ol>
        <div class="alert alert-info mt-4">
            For further assistance, contact your system administrator or support team.
        </div>
    </div>
</div>
@endsection 