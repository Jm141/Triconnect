<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Teacher List</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .content-wrapper { margin-left: 250px; }
        .content { padding: 20px; }
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
    <nav class="main-header navbar navbar-expand navbar-dark navbar-dark">
        <a href="#" class="navbar-brand">Student</a>
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
                        <a href="{{ route('geofence') }}" class="nav-link">
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


    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                
<a href="{{ route('addRooms') }}" class="btn btn-primary mb-3">Add Room</a>
<div class="table-responsive">
    <table id="roomsTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
                <th>Room Name</th>
                <th>Room Code</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rooms as $room)
                <tr>
                    <td><strong>{{ $room->name }}</strong></td>
                    <td><code>{{ $room->room_code }}</code></td>
                    <td>
                        <button class="btn btn-info btn-sm generate-qr" data-room-name="{{ $room->name }}" data-room-code="{{ $room->room_code }}">
                            <i class="fa fa-qrcode"></i> Generate QR
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

            </div>
        </section>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content p-5">
            <div class="modal-header">
                <h5 class="modal-title" id="qrModalLabel">QR Code</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <h4 id="roomName"></h4>
                <div id="qrCodeContainer"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printQrCode()">Print</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#roomsTable').DataTable({
            responsive: true,
            language: {
                search: "Search rooms:",
                lengthMenu: "Show _MENU_ rooms per page",
                info: "Showing _START_ to _END_ of _TOTAL_ rooms",
                infoEmpty: "No rooms available",
                infoFiltered: "(filtered from _MAX_ total rooms)"
            },
            pageLength: 10,
            order: [[0, 'asc']] // Sort by room name ascending
        });

        // QR Code generation
        $(".generate-qr").click(function() {
            var roomName = $(this).data("room-name");
            var roomCode = $(this).data("room-code");

            $("#roomName").text(roomName);
            $("#qrCodeContainer").empty(); // Clear previous QR codes

            new QRCode(document.getElementById("qrCodeContainer"), {
                text: roomCode,
                width: 200,
                height: 200
            });

            $("#qrModal").modal("show"); // Ensure modal pops up
        });
    });

    function printQrCode() {
        var printContents = document.getElementById("qrCodeContainer").innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }
</script>

</body>
</html>
