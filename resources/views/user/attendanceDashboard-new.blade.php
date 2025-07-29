@extends('layouts.user-dashboard')

@section('title', 'Attendance Dashboard - Triconnect')

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
    <style>
        .attendance-card {
            border-left: 4px solid #28a745;
        }
        
        .room-card {
            border-left: 4px solid #007bff;
        }
        
        .student-card {
            border-left: 4px solid #ffc107;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fa fa-calendar-check-o"></i> Attendance Dashboard
                    </h3>
                </div>
                <div class="card-body">
                    <p>Welcome to the Attendance Dashboard. Here you can manage and view attendance records.</p>
                    
                    {{-- Add your attendance dashboard content here --}}
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card attendance-card">
                                <div class="card-body">
                                    <h5 class="card-title">Total Attendance</h5>
                                    <p class="card-text">View all attendance records</p>
                                    <a href="#" class="btn btn-primary">View Records</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card room-card">
                                <div class="card-body">
                                    <h5 class="card-title">Room Management</h5>
                                    <p class="card-text">Manage room assignments</p>
                                    <a href="/roomList" class="btn btn-primary">Manage Rooms</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card student-card">
                                <div class="card-body">
                                    <h5 class="card-title">Student List</h5>
                                    <p class="card-text">View student information</p>
                                    <a href="/student-list" class="btn btn-primary">View Students</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTables or any other JavaScript functionality
            console.log('Attendance Dashboard loaded');
        });
    </script>
@endpush 