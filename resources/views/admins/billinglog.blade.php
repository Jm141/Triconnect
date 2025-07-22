<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Teacher List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
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
    <p>Access: {{ session('userAccess')->access }}</p>

                    @if (strpos(session('userAccess')->access, 'admin') !== false)
                        <p>Welcome, Admin!</p>
                    

    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark navbar-dark">
            <a href="#" class="navbar-brand">Teacher</a>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="https://via.placeholder.com/150" class="img-circle elevation-2" alt="User Image">
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
                                <i class="nav-icon fa fa-graduation-cap"></i>
                                <p>Subscription List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/billing" class="nav-link">
                                <i class="nav-icon fa fa-graduation-cap"></i>
                                <p>Billing Log List</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="mb-3">
                        <table class="table table-bordered table-striped" id="billing_logs">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Family Code</th>
                                    <th>Subscription Plan</th>
                                    <th>Base Amount</th>
                                    <th>Additional Multiplier</th>
                                    <th>Amount Due</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($billingLogs as $log)
                                    <tr>
                                        <td>
                                            @if ($log->family && $log->family->parents->isNotEmpty())
                                                @foreach ($log->family->parents as $parent)
                                                    {{ $parent->fname }} {{ $parent->lname }}<br>
                                                @endforeach
                                            @else
                                                No Parents Available
                                            @endif
                                        </td>
                                        
                                        <td>{{ $log->subscription_plan }}</td>
                                        <td>{{ number_format($log->base_amount, 2) }}</td>
                                        <td>{{ $log->additional_multiplier }}</td>
                                        <td>{{ number_format($log->amount_due, 2) }}</td>
                                        <td>{{ $log->created_at->format('F j, Y g:i A') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
               
                    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
                    <script>
                        $(document).ready(function () {
                            $('#billing_logs').DataTable({
                                "responsive": true,
                                "autoWidth": false,
                            });
                        });
                    </script>
                </div>
                @elseif (strpos(session('userAccess')->access, 'teacher') !== false)
                        <p>Teacher Good Morning, {{ session('userAccess')->access }}</p>
                    @else
                        <p>Access Denied</p>
                    @endif
            </section>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
</body>
</html>
