@extends('layouts.user-dashboard')

@section('title', 'Advanced QR Generation - Triconnect')

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .qr-option-card {
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
        }
        .qr-option-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .qr-option-card.selected {
            border: 2px solid #007bff;
            background-color: #f8f9ff;
            
        }
        .custom-form {
            display: none;
        }
        .custom-form.active {
            display: block;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fa fa-qrcode"></i> Advanced QR Code Generation
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('qr.generate') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to Basic QR
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Quick Actions -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card qr-option-card bg-success text-white" onclick="generateQuickQr()">
                                <div class="card-body text-center">
                                    <i class="fa fa-bolt fa-3x mb-3"></i>
                                    <h5>Quick QR</h5>
                                    <p class="mb-0">Generate QR for current class (15 min expiry)</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card qr-option-card bg-primary text-white" onclick="showCustomForm('teacher')">
                                <div class="card-body text-center">
                                    <i class="fa fa-user fa-3x mb-3"></i>
                                    <h5>Teacher QR</h5>
                                    <p class="mb-0">Reusable QR with teacher code</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card qr-option-card bg-warning text-white" onclick="showCustomForm('room')">
                                <div class="card-body text-center">
                                    <i class="fa fa-building fa-3x mb-3"></i>
                                    <h5>Room QR</h5>
                                    <p class="mb-0">QR for specific room</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Custom QR Form -->
                    <div class="card custom-form" id="customQrForm">
                        <div class="card-header">
                            <h5 class="card-title">Custom QR Generation</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('qr.custom') }}" method="POST" id="qrForm">
                                @csrf
                                <input type="hidden" name="qr_type" id="qrType">
                                
                                <!-- Schedule Selection -->
                                <div class="form-group" id="scheduleGroup" style="display: none;">
                                    <label for="schedule_id">Select Schedule</label>
                                    <select class="form-control" name="schedule_id" id="schedule_id">
                                        <option value="">Choose a schedule...</option>
                                        @foreach($schedules as $schedule)
                                            <option value="{{ $schedule->id }}">
                                                {{ $schedule->subject_name }} - {{ $schedule->room->name ?? $schedule->room_code }} 
                                                ({{ implode(', ', json_decode($schedule->day_of_week)) }} {{ $schedule->start_time }}-{{ $schedule->end_time }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Room Code Input -->
                                <div class="form-group" id="roomGroup" style="display: none;">
                                    <label for="room_code">Room Code</label>
                                    <input type="text" class="form-control" name="room_code" id="room_code" placeholder="Enter room code">
                                </div>

                                <!-- Custom Data Input -->
                                <div class="form-group" id="customDataGroup" style="display: none;">
                                    <label for="custom_data">Custom Data</label>
                                    <textarea class="form-control" name="custom_data" id="custom_data" rows="3" placeholder="Enter custom data for QR code"></textarea>
                                </div>

                                <!-- QR Settings -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="qr_size">QR Code Size</label>
                                            <select class="form-control" name="qr_size" id="qr_size">
                                                <option value="200">Small (200px)</option>
                                                <option value="300" selected>Medium (300px)</option>
                                                <option value="400">Large (400px)</option>
                                                <option value="500">Extra Large (500px)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="expiry_minutes">Expiry Time (minutes)</label>
                                            <select class="form-control" name="expiry_minutes" id="expiry_minutes">
                                                <option value="15">15 minutes</option>
                                                <option value="30">30 minutes</option>
                                                <option value="60" selected>1 hour</option>
                                                <option value="120">2 hours</option>
                                                <option value="240">4 hours</option>
                                                <option value="480">8 hours</option>
                                                <option value="1440">24 hours</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-qrcode"></i> Generate QR Code
                                    </button>
                                    <button type="button" class="btn btn-secondary" onclick="hideCustomForm()">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Current Schedule Info -->
                    @if($schedules->count() > 0)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fa fa-calendar"></i> Your Schedules ({{ $currentDay }})
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Subject</th>
                                            <th>Room</th>
                                            <th>Days</th>
                                            <th>Time</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($schedules as $schedule)
                                            <tr class="{{ $schedule->start_time <= $currentTime && $schedule->end_time >= $currentTime && in_array($currentDay, json_decode($schedule->day_of_week)) ? 'table-success' : '' }}">
                                                <td>{{ $schedule->subject_name }}</td>
                                                <td>{{ $schedule->room->name ?? $schedule->room_code }}</td>
                                                <td>{{ implode(', ', json_decode($schedule->day_of_week)) }}</td>
                                                <td>{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
                                                <td>
                                                    @if($schedule->start_time <= $currentTime && $schedule->end_time >= $currentTime && in_array($currentDay, json_decode($schedule->day_of_week)))
                                                        <span class="badge badge-success">Active Now</span>
                                                    @else
                                                        <span class="badge badge-secondary">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary" onclick="selectSchedule({{ $schedule->id }})">
                                                        <i class="fa fa-qrcode"></i> Generate QR
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function generateQuickQr() {
    window.location.href = "{{ route('qr.quick') }}";
}

function showCustomForm(type) {
    document.getElementById('qrType').value = type;
    document.getElementById('customQrForm').classList.add('active');
    
    // Hide all groups first
    document.getElementById('scheduleGroup').style.display = 'none';
    document.getElementById('roomGroup').style.display = 'none';
    document.getElementById('customDataGroup').style.display = 'none';
    
    // Show relevant group
    switch(type) {
        case 'schedule':
            document.getElementById('scheduleGroup').style.display = 'block';
            break;
        case 'room':
            document.getElementById('roomGroup').style.display = 'block';
            break;
        case 'custom':
            document.getElementById('customDataGroup').style.display = 'block';
            break;
    }
}

function hideCustomForm() {
    document.getElementById('customQrForm').classList.remove('active');
}

function selectSchedule(scheduleId) {
    document.getElementById('qrType').value = 'schedule';
    document.getElementById('schedule_id').value = scheduleId;
    showCustomForm('schedule');
}

// Form validation
document.getElementById('qrForm').addEventListener('submit', function(e) {
    const qrType = document.getElementById('qrType').value;
    
    if (qrType === 'schedule' && !document.getElementById('schedule_id').value) {
        e.preventDefault();
        Swal.fire('Error', 'Please select a schedule', 'error');
        return;
    }
    
    if (qrType === 'room' && !document.getElementById('room_code').value) {
        e.preventDefault();
        Swal.fire('Error', 'Please enter a room code', 'error');
        return;
    }
    
    if (qrType === 'custom' && !document.getElementById('custom_data').value) {
        e.preventDefault();
        Swal.fire('Error', 'Please enter custom data', 'error');
        return;
    }
});
</script>
@endpush 