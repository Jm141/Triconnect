@extends('layouts.user-dashboard')

@section('title', 'Custom QR Code - Triconnect')

@push('css')
<style>
    .qr-container {
        text-align: center;
        padding: 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .qr-code {
        max-width: 100%;
        height: auto;
        border: 2px solid #ddd;
        border-radius: 8px;
        padding: 10px;
        background: white;
    }
    .qr-info {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-top: 20px;
    }
    .expiry-warning {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 5px;
        padding: 10px;
        margin-top: 15px;
    }
    .download-btn {
        margin: 10px 5px;
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
                        <i class="fa fa-qrcode"></i> Custom QR Code Generated
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
                            <div class="qr-container">
                                <h4>QR Code</h4>
                                <img src="data:image/png;base64,{{ base64_encode($qrCode) }}" 
                                     alt="QR Code" 
                                     class="qr-code" 
                                     id="qrImage">
                                
                                <div class="mt-3">
                                    <button class="btn btn-primary download-btn" onclick="downloadQR()">
                                        <i class="fa fa-download"></i> Download QR
                                    </button>
                                    <button class="btn btn-success download-btn" onclick="printQR()">
                                        <i class="fa fa-print"></i> Print QR
                                    </button>
                                    <button class="btn btn-info download-btn" onclick="shareQR()">
                                        <i class="fa fa-share"></i> Share QR
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="qr-info">
                                <h5><i class="fa fa-info-circle"></i> QR Code Information</h5>
                                
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Type:</strong></td>
                                        <td>
                                            @switch($qrData['type'])
                                                @case('teacher_qr')
                                                    <span class="badge badge-primary">Teacher QR</span>
                                                    @break
                                                @case('room_qr')
                                                    <span class="badge badge-warning">Room QR</span>
                                                    @break
                                                @case('attendance_qr')
                                                    <span class="badge badge-success">Attendance QR</span>
                                                    @break
                                                @case('custom_qr')
                                                    <span class="badge badge-info">Custom QR</span>
                                                    @break
                                                @default
                                                    <span class="badge badge-secondary">{{ $qrData['type'] }}</span>
                                            @endswitch
                                        </td>
                                    </tr>
                                    
                                    @if(isset($qrData['teacher_staff_code']))
                                    <tr>
                                        <td><strong>Teacher:</strong></td>
                                        <td>{{ $teacher->firstname }} {{ $teacher->lastname }}</td>
                                    </tr>
                                    @endif
                                    
                                    @if(isset($qrData['room_code']))
                                    <tr>
                                        <td><strong>Room:</strong></td>
                                        <td>{{ $qrData['room_code'] }}</td>
                                    </tr>
                                    @endif
                                    
                                    @if(isset($qrData['subject_name']))
                                    <tr>
                                        <td><strong>Subject:</strong></td>
                                        <td>{{ $qrData['subject_name'] }}</td>
                                    </tr>
                                    @endif
                                    
                                    @if(isset($qrData['custom_data']))
                                    <tr>
                                        <td><strong>Custom Data:</strong></td>
                                        <td>{{ $qrData['custom_data'] }}</td>
                                    </tr>
                                    @endif
                                    
                                    <tr>
                                        <td><strong>Size:</strong></td>
                                        <td>{{ $qrSize }}px</td>
                                    </tr>
                                    
                                    <tr>
                                        <td><strong>Generated:</strong></td>
                                        <td>{{ \Carbon\Carbon::createFromTimestamp($qrData['generated_at'])->format('M d, Y H:i:s') }}</td>
                                    </tr>
                                    
                                    <tr>
                                        <td><strong>Expires:</strong></td>
                                        <td>
                                            {{ \Carbon\Carbon::createFromTimestamp($qrData['expires_at'])->format('M d, Y H:i:s') }}
                                            <br>
                                            <small class="text-muted">({{ $expiryMinutes }} minutes from generation)</small>
                                        </td>
                                    </tr>
                                </table>
                                
                                <div class="expiry-warning">
                                    <i class="fa fa-clock-o"></i>
                                    <strong>Note:</strong> This QR code will expire in {{ $expiryMinutes }} minutes from generation time.
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <h6><i class="fa fa-lightbulb-o"></i> Usage Instructions</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fa fa-check text-success"></i> Students can scan this QR code to mark attendance</li>
                                    <li><i class="fa fa-check text-success"></i> QR code is valid only during the specified time period</li>
                                    <li><i class="fa fa-check text-success"></i> Each student can only scan once per session</li>
                                    <li><i class="fa fa-check text-success"></i> Attendance will be automatically recorded in the system</li>
                                </ul>
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
function downloadQR() {
    const link = document.createElement('a');
    link.download = 'qr-code-{{ $qrData["type"] }}-{{ date("Y-m-d-H-i-s") }}.png';
    link.href = document.getElementById('qrImage').src;
    link.click();
}

function printQR() {
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>QR Code - {{ $qrData["type"] }}</title>
                <style>
                    body { font-family: Arial, sans-serif; text-align: center; }
                    .qr-code { max-width: 300px; height: auto; }
                    .info { margin: 20px 0; }
                </style>
            </head>
            <body>
                <h2>QR Code - {{ ucfirst(str_replace('_', ' ', $qrData["type"])) }}</h2>
                <img src="${document.getElementById('qrImage').src}" class="qr-code">
                <div class="info">
                    <p><strong>Teacher:</strong> {{ $teacher->firstname }} {{ $teacher->lastname }}</p>
                    <p><strong>Generated:</strong> {{ \Carbon\Carbon::createFromTimestamp($qrData['generated_at'])->format('M d, Y H:i:s') }}</p>
                    <p><strong>Expires:</strong> {{ \Carbon\Carbon::createFromTimestamp($qrData['expires_at'])->format('M d, Y H:i:s') }}</p>
                </div>
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}

function shareQR() {
    if (navigator.share) {
        navigator.share({
            title: 'QR Code - {{ $qrData["type"] }}',
            text: 'Scan this QR code to mark attendance',
            url: window.location.href
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Link copied to clipboard!');
        });
    }
}

// Auto-refresh countdown timer
function updateCountdown() {
    const now = Math.floor(Date.now() / 1000);
    const expiresAt = {{ $qrData['expires_at'] }};
    const timeLeft = expiresAt - now;
    
    if (timeLeft <= 0) {
        document.querySelector('.expiry-warning').innerHTML = 
            '<i class="fa fa-exclamation-triangle text-danger"></i> <strong>Expired:</strong> This QR code has expired and is no longer valid.';
        document.querySelector('.expiry-warning').className = 'expiry-warning bg-danger text-white';
    } else {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        document.querySelector('.expiry-warning').innerHTML = 
            `<i class="fa fa-clock-o"></i> <strong>Expires in:</strong> ${minutes}m ${seconds}s`;
    }
}

// Update countdown every second
setInterval(updateCountdown, 1000);
updateCountdown(); // Initial call
</script>
@endpush 