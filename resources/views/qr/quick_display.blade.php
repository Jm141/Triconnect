@extends('layouts.user-dashboard')

@section('title', 'Quick QR Code - Triconnect')

@push('css')
<style>
    .quick-qr-container {
        text-align: center;
        padding: 30px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        color: white;
        margin-bottom: 20px;
    }
    .qr-code {
        max-width: 250px;
        height: auto;
        border: 3px solid white;
        border-radius: 10px;
        padding: 15px;
        background: white;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    }
    .countdown {
        font-size: 2rem;
        font-weight: bold;
        margin: 20px 0;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    .class-info {
        background: rgba(255,255,255,0.1);
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
        backdrop-filter: blur(10px);
    }
    .status-indicator {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #28a745;
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
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
                        <i class="fa fa-bolt"></i> Quick QR Code - Active Class
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('qr.advanced') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to Advanced QR
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="quick-qr-container">
                                <h4><i class="fa fa-qrcode"></i> Quick Attendance QR</h4>
                                
                                <div class="countdown" id="countdown">
                                    <span id="minutes">15</span>:<span id="seconds">00</span>
                                </div>
                                
                                <img src="data:image/png;base64,{{ base64_encode($qrCode) }}" 
                                     alt="Quick QR Code" 
                                     class="qr-code" 
                                     id="qrImage">
                                
                                <div class="mt-3">
                                    <button class="btn btn-light btn-sm" onclick="downloadQR()">
                                        <i class="fa fa-download"></i> Download
                                    </button>
                                    <button class="btn btn-light btn-sm" onclick="printQR()">
                                        <i class="fa fa-print"></i> Print
                                    </button>
                                    <button class="btn btn-light btn-sm" onclick="refreshQR()">
                                        <i class="fa fa-refresh"></i> Refresh
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="class-info">
                                <h5><i class="fa fa-info-circle"></i> Current Class Information</h5>
                                
                                <div class="row">
                                    <div class="col-6">
                                        <p><strong>Subject:</strong><br>{{ $currentSchedule->subject_name }}</p>
                                    </div>
                                    <div class="col-6">
                                        <p><strong>Room:</strong><br>{{ $currentSchedule->room->name ?? $currentSchedule->room_code }}</p>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-6">
                                        <p><strong>Teacher:</strong><br>{{ $teacher->firstname }} {{ $teacher->lastname }}</p>
                                    </div>
                                    <div class="col-6">
                                        <p><strong>Time:</strong><br>{{ $currentSchedule->start_time }} - {{ $currentSchedule->end_time }}</p>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-12">
                                        <p><strong>Days:</strong><br>{{ implode(', ', json_decode($currentSchedule->day_of_week)) }}</p>
                                    </div>
                                </div>
                                
                                <div class="alert alert-info">
                                    <i class="fa fa-clock-o"></i>
                                    <strong>Quick QR Code</strong><br>
                                    This QR code is valid for 15 minutes and automatically expires.
                                    Students can scan it to mark their attendance for this class.
                                </div>
                            </div>
                            
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="card-title">
                                        <span class="status-indicator"></span> QR Code Status
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div id="qrStatus">
                                        <p><i class="fa fa-check-circle text-success"></i> QR Code is active and ready for scanning</p>
                                        <p><i class="fa fa-users text-info"></i> Students can scan this code to mark attendance</p>
                                        <p><i class="fa fa-shield text-warning"></i> Each student can only scan once per session</p>
                                    </div>
                                </div>
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
<script>
let timeLeft = 15 * 60; // 15 minutes in seconds

function updateCountdown() {
    if (timeLeft <= 0) {
        document.getElementById('countdown').innerHTML = '<span class="text-danger">EXPIRED</span>';
        document.getElementById('qrStatus').innerHTML = `
            <p><i class="fa fa-exclamation-triangle text-danger"></i> QR Code has expired</p>
            <p><i class="fa fa-refresh text-info"></i> Generate a new QR code to continue</p>
        `;
        document.querySelector('.status-indicator').style.background = '#dc3545';
        document.querySelector('.status-indicator').style.animation = 'none';
        return;
    }
    
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    
    document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
    document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
    
    timeLeft--;
}

function downloadQR() {
    const link = document.createElement('a');
    link.download = 'quick-qr-{{ date("Y-m-d-H-i-s") }}.png';
    link.href = document.getElementById('qrImage').src;
    link.click();
}

function printQR() {
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Quick QR Code - {{ $currentSchedule->subject_name }}</title>
                <style>
                    body { font-family: Arial, sans-serif; text-align: center; }
                    .qr-code { max-width: 250px; height: auto; }
                    .info { margin: 20px 0; }
                </style>
            </head>
            <body>
                <h2>Quick QR Code - {{ $currentSchedule->subject_name }}</h2>
                <img src="${document.getElementById('qrImage').src}" class="qr-code">
                <div class="info">
                    <p><strong>Subject:</strong> {{ $currentSchedule->subject_name }}</p>
                    <p><strong>Room:</strong> {{ $currentSchedule->room->name ?? $currentSchedule->room_code }}</p>
                    <p><strong>Teacher:</strong> {{ $teacher->firstname }} {{ $teacher->lastname }}</p>
                    <p><strong>Time:</strong> {{ $currentSchedule->start_time }} - {{ $currentSchedule->end_time }}</p>
                </div>
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}

function refreshQR() {
    if (confirm('Generate a new QR code? This will invalidate the current one.')) {
        window.location.reload();
    }
}

// Update countdown every second
setInterval(updateCountdown, 1000);
updateCountdown(); // Initial call

// Auto-refresh page when QR expires
setTimeout(() => {
    if (timeLeft <= 0) {
        if (confirm('QR code has expired. Generate a new one?')) {
            window.location.reload();
        }
    }
}, 15 * 60 * 1000); // 15 minutes
</script>
@endpush 