@extends('layouts.user-dashboard')

@section('title', 'System Settings & Help - Triconnect')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fa fa-cog"></i> System Features & Help
                    </h3>
                </div>
                <div class="card-body">
                    <h4 class="mb-3">Triconnect Features</h4>
                    <ul class="list-group mb-4">
                        <li class="list-group-item">
                            <i class="fa fa-user-graduate text-info mr-2"></i> 
                            <strong>Student & Teacher Management:</strong> Add, edit, and manage student and teacher records easily.
                        </li>
                        <li class="list-group-item">
                            <i class="fa fa-users text-info mr-2"></i> 
                            <strong>Family & Parent Integration:</strong> Link students to families and manage parent access.
                        </li>
                        <li class="list-group-item">
                            <i class="fa fa-map-marker-alt text-info mr-2"></i> 
                            <strong>Geofence Tracking:</strong> Create and monitor geofences for real-time location awareness.
                        </li>
                        <li class="list-group-item">
                            <i class="fa fa-chart-bar text-info mr-2"></i> 
                            <strong>Analytics & Reports:</strong> View statistics and charts for students, teachers, and geofences.
                        </li>
                        <li class="list-group-item">
                            <i class="fa fa-bell text-info mr-2"></i> 
                            <strong>Instant Alerts:</strong> Receive notifications for important events and geofence breaches.
                        </li>
                        <li class="list-group-item">
                            <i class="fa fa-lock text-info mr-2"></i> 
                            <strong>Secure Access:</strong> Role-based authentication and secure login for all users.
                        </li>
                    </ul>
                    
                    <h4 class="mb-3">How to Use</h4>
                    <ol>
                        <li><strong>Dashboard:</strong> View key statistics and charts for your school.</li>
                        <li><strong>Teachers/Students:</strong> Use the navigation bar to manage teacher and student records.</li>
                        <li><strong>Families/Parents:</strong> Manage family and parent information, and link students to families.</li>
                        <li><strong>Rooms:</strong> Add and manage rooms for classes and activities.</li>
                        <li><strong>Geofences:</strong> Create geofences on the map to monitor student locations and receive alerts.</li>
                        <li><strong>Settings:</strong> Visit this page for help and system information.</li>
                    </ol>
                    
                    <div class="alert alert-info mt-4">
                        <i class="fa fa-info-circle"></i> For further assistance, contact your system administrator or support team.
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- System Information --}}
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fa fa-info-circle"></i> System Information
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Application Name:</strong></td>
                            <td>Triconnect</td>
                        </tr>
                        <tr>
                            <td><strong>Version:</strong></td>
                            <td>1.0.0</td>
                        </tr>
                        <tr>
                            <td><strong>Framework:</strong></td>
                            <td>Laravel {{ app()->version() }}</td>
                        </tr>
                        <tr>
                            <td><strong>PHP Version:</strong></td>
                            <td>{{ phpversion() }}</td>
                        </tr>
                        <tr>
                            <td><strong>Database:</strong></td>
                            <td>MySQL</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fa fa-question-circle"></i> Quick Help
                    </h3>
                </div>
                <div class="card-body">
                    <div class="accordion" id="helpAccordion">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        How to add a new student?
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#helpAccordion">
                                <div class="card-body">
                                    Go to <strong>Student List</strong> and click the <strong>Add New Student</strong> button. Fill in the required information and save.
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        How to create a geofence?
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#helpAccordion">
                                <div class="card-body">
                                    Navigate to <strong>Geofence</strong> and click <strong>Add New Geofence</strong>. Set the location and radius on the map.
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header" id="headingThree">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        How to manage billing?
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#helpAccordion">
                                <div class="card-body">
                                    Go to <strong>Billing Logs</strong> to view all billing records. You can add new billing, mark as paid, or export data.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 