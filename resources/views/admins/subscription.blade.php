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
            <a href="#" class="navbar-brand">Teacher</a>
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

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="mb-3">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addSubscriptionModal">
                            Add Subscription Plan
                        </button>
                    </div>
                
                    <!-- Subscription Plans Table -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Subscription Plans List</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered" id="subscriptionPlansTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Base Amount</th>
                                        <th>Additional Multiplier</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subscriptionPlans as $plan)
                                        <tr>
                                            <td>{{ $plan->id }}</td>
                                            <td>{{ $plan->name }}</td>
                                            <td>{{ $plan->base_amount }}</td>
                                            <td>{{ $plan->additional_multiplier }}</td>
                                            <td>{{ $plan->created_at->format('F j, Y g:i A') }}</td>
                                            <td>
                                                <button 
                                                    type="button" 
                                                    class="btn btn-sm btn-warning update-btn"
                                                    data-id="{{ $plan->id }}"
                                                    data-name="{{ $plan->name }}"
                                                    data-base_amount="{{ $plan->base_amount }}"
                                                    data-additional_multiplier="{{ $plan->additional_multiplier }}"
                                                    data-toggle="modal" 
                                                    data-target="#updateSubscriptionModal">
                                                    Update
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                
                    <!-- Create Subscription Modal -->
                    <div class="modal fade" id="addSubscriptionModal" tabindex="-1" role="dialog" aria-labelledby="addSubscriptionModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                         <div class="modal-content">
                            <form action="/subscription/" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addSubscriptionModalLabel">Add New Subscription Plan</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                         <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="name">Plan Name</label>
                                        <input type="text" class="form-control" name="name" id="name" placeholder="Enter plan name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="base_amount">Base Amount</label>
                                        <input type="number" step="0.01" class="form-control" name="base_amount" id="base_amount" placeholder="Enter base amount" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="additional_multiplier">Additional Multiplier</label>
                                        <input type="number" step="0.01" class="form-control" name="additional_multiplier" id="additional_multiplier" placeholder="Enter additional multiplier (e.g., 0.75)" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save Subscription</button>
                                </div>
                            </form>
                         </div>
                      </div>
                    </div>
                
                    <!-- Update Subscription Modal -->
                    <div class="modal fade" id="updateSubscriptionModal" tabindex="-1" role="dialog" aria-labelledby="updateSubscriptionModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                         <div class="modal-content">
                            <form id="updateSubscriptionForm" action="" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="updateSubscriptionModalLabel">Update Subscription Plan</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                         <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="update_name">Plan Name</label>
                                        <input type="text" class="form-control" name="name" id="update_name" placeholder="Enter plan name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="update_base_amount">Base Amount</label>
                                        <input type="number" step="0.01" class="form-control" name="base_amount" id="update_base_amount" placeholder="Enter base amount" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="update_additional_multiplier">Additional Multiplier</label>
                                        <input type="number" step="0.01" class="form-control" name="additional_multiplier" id="update_additional_multiplier" placeholder="Enter additional multiplier (e.g., 0.75)" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Update Subscription</button>
                                </div>
                            </form>
                         </div>
                      </div>
                    </div>
                    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
               
                    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
                    <script>
                        $(document).ready(function () {
                            $('#subscriptionPlansTable').DataTable({
                                "responsive": true,
                                "autoWidth": false,
                            });
                
                            $(document).on('click', '.update-btn', function(){
                                console.log('Update button clicked');
                
                                var id = $(this).data('id');
                                var name = $(this).data('name');
                                var base_amount = $(this).data('base_amount');
                                var additional_multiplier = $(this).data('additional_multiplier');
                
                                console.log({ id, name, base_amount, additional_multiplier });
                
                                var url = '/admin/' + id;
                                $('#updateSubscriptionForm').attr('action', url);
                
                                $('#update_name').val(name);
                                $('#update_base_amount').val(base_amount);
                                $('#update_additional_multiplier').val(additional_multiplier);
                            });
                        });
                    </script>
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
    
    <script>
        $(document).ready(function() {
            $('#subscriptionPlansTable').DataTable({
                responsive: true,
                language: {
                    search: "Search subscription plans:",
                    lengthMenu: "Show _MENU_ plans per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ plans",
                    infoEmpty: "No subscription plans available",
                    infoFiltered: "(filtered from _MAX_ total plans)"
                },
                pageLength: 10,
                order: [[0, 'asc']] // Sort by plan name ascending
            });

            $(document).on('click', '.update-btn', function(){
                console.log('Update button clicked');

                var id = $(this).data('id');
                var name = $(this).data('name');
                var base_amount = $(this).data('base_amount');
                var additional_multiplier = $(this).data('additional_multiplier');

                console.log({ id, name, base_amount, additional_multiplier });

                var url = '/admin/' + id;
                $('#updateSubscriptionForm').attr('action', url);

                $('#update_name').val(name);
                $('#update_base_amount').val(base_amount);
                $('#update_additional_multiplier').val(additional_multiplier);
            });
        });
    </script>
</body>
</html>
