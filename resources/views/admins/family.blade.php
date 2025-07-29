<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Teacher List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap4.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .content-wrapper {
            margin-left: 250px;
        }

        .content {
            padding: 20px;
        }
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
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark navbar-dark">
            <a href="#" class="navbar-brand">Family</a>
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
                            <a href="/teacher-list" class="nav-link">
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
                            <a href="/roomList" class="nav-link">
                                <i class="nav-icon fa fa-building"></i>
                                <p>Room List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/family-list" class="nav-link">
                                <i class="nav-icon fa fa-home"></i>
                                <p>Family List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/student-list" class="nav-link">
                                <i class="nav-icon fa fa-graduation-cap"></i>
                                <p>Student List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/subscription" class="nav-link">
                                <i class="nav-icon fa fa-credit-card"></i>
                                <p>Subscription Plans</p>
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
                    

                    <div class="mt-3 text-center">
                        <a href="addFamily" class="btn btn-primary">Add Family</a>
                    </div>
                    
                    @if (session('success'))
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: '{{ session('success') }}',
                            });
                        </script>
                    @endif
                    <div class="card mt-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="familyTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Family Code</th>
                                            <th>Parent Name</th>
                                            <th>Parent Number</th>
                                            <th>Student(s)</th>
                                            <th>Address</th>
                                            <th>Status</th>
                                            <th>Billings</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($families as $family)
                                            <tr>
                                                <td><strong>{{ $family->family_code }}</strong></td>  
                                                <td>{{ $family->fname }} {{ $family->lname }}</td>  
                                                <td>{{ $family->number }}</td> 
                                                <td>
                                                    <ul class="list-unstyled mb-0">
                                                        @foreach ($family->students as $student)
                                                            <li><small>{{ $student->firstname }} ({{ $student->year ?? 'N/A' }} - {{ $student->status }})</small></li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                                <td>
                                                    @if($family->students->isNotEmpty())
                                                        <small>{{ $family->family->address ?? 'No Address' }}</small>
                                                    @else
                                                        <small class="text-muted">No Students Available</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($family->students->isNotEmpty())
                                                        <span class="badge badge-{{ $family->family->status === 'active' ? 'success' : 'warning' }}">
                                                            {{ $family->family->status ?? 'No Status' }}
                                                        </span>
                                                    @else
                                                        <span class="badge badge-secondary">No Status</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($family->family)
                                                        <strong>₱{{ number_format($family->billing_amount, 2) }}</strong>
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($family->status != 'Subscribe')
                                                        <form action="{{ route('admin.recordPayment', $family->family_code) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-primary btn-sm">
                                                                <i class="fas fa-check"></i> Mark as Paid
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="badge badge-success">Paid</span>
                                                    @endif
                                                </td>               
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#familyTable').DataTable({
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'print'
                ],
                language: {
                    search: "Search families:",
                    lengthMenu: "Show _MENU_ families per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ families",
                    infoEmpty: "No families available",
                    infoFiltered: "(filtered from _MAX_ total families)"
                },
                pageLength: 10,
                order: [[0, 'asc']] // Sort by family code ascending
            });
        });
    </script>
</body>
</html>
    
