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
            <a href="#" class="navbar-brand">Family</a>
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
                            <table class="table table-bordered table-striped" id="family">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Family Code</th>
                                        <th>Parent Name</th>
                                        <th>Parent Number</th>
                                        <th>Student(s)</th>
                                        <th>Address</th>
                                        <th>Status</th>
                                        <td>Billings </td>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($families as $family)
                                        <tr>
                                            <td>{{ $family->family_code }}</td>  
                                            <td>{{ $family->fname }} {{ $family->lname }}</td>  
                                            <td>{{ $family->number }}</td> 
                                            <td>
                                                <ul>
                                                    @foreach ($family->students as $student)
                                                        <li>{{ $student->firstname }} ({{ $student->year }} - {{ $student->status }})</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td>
                                                @if($family->students->isNotEmpty())
                                                    {{ $family->family->address ?? 'No Address' }}
                                                @else
                                                    No Students Available
                                                @endif
                                            </td>
                                            <td>
                                                @if($family->students->isNotEmpty())
                                                    {{ $family->family->status ?? 'No Status' }}
                                                @else
                                                    No Status Available
                                                @endif
                                            </td>
                                            <td>
                                                @if($family->family)
                                                    {{ number_format($family->billing_amount, 2) }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                {{-- <a href="{{ route('parents.show', $family->family_code) }}" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                @if($family->family->status == "Subscribe")
                                                <form action="{{ route('admins.updateStatusByParent', ['parentId' => $family->family_code, 'action' => 'Subscribed']) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="fas fa-check"></i> Subscribe
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admins.updateStatusByParent', ['parentId' => $family->family_code, 'action' => 'UnSubscribed']) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-times"></i> Unsubscribe
                                                    </button>
                                                </form>
                                             @endif --}}
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
                    @elseif (strpos(session('userAccess')->access, 'teacher') !== false)
                    <p>Teacher Good Morning, {{ session('userAccess')->access }}</p>
                @else
                    <p>Access Denied</p>
                @endif
                </div>
            </section>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#family').DataTable({
                "responsive": true,
                "autoWidth": false,
            });

        });
    </script>
</body>
</html>
    
