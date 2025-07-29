<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Billing Logs</title>
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
        .stats-card {
            transition: transform 0.2s;
        }
        .stats-card:hover {
            transform: translateY(-2px);
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
            <a href="#" class="navbar-brand">Billing Management</a>
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
                            <a href="{{ route('subscription.index') }}" class="nav-link">
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
                    
                    @if (session('success'))
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: '{{ session('success') }}',
                            });
                        </script>
                    @endif

                    @if (session('error'))
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: '{{ session('error') }}',
                            });
                        </script>
                    @endif

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-6">
                            <div class="info-box bg-info stats-card">
                                <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Billing</span>
                                    <span class="info-box-number">₱{{ number_format($totalBilling ?? 0, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="info-box bg-warning stats-card">
                                <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Pending Billing</span>
                                    <span class="info-box-number">₱{{ number_format($pendingBilling ?? 0, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="info-box bg-success stats-card">
                                <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Paid Billing</span>
                                    <span class="info-box-number">₱{{ number_format($paidBilling ?? 0, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="info-box bg-danger stats-card">
                                <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Overdue</span>
                                    <span class="info-box-number" id="overdueCount">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-primary" onclick="generateAllBilling()">
                                    <i class="fas fa-plus"></i> Generate All Billing
                                </button>
                                <button type="button" class="btn btn-success" onclick="exportBilling()">
                                    <i class="fas fa-download"></i> Export CSV
                                </button>
                                <button type="button" class="btn btn-info" onclick="refreshStats()">
                                    <i class="fas fa-sync"></i> Refresh Stats
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Billing Logs Table -->
                    <div class="card">
                        <div class="card-header bg-gradient-info">
                            <h3 class="card-title">Billing Logs</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="billingLogsTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Family Code</th>
                                            <th>Parent Name</th>
                                            <th>Subscription Plan</th>
                                            <th>Base Amount</th>
                                            <th>Additional Multiplier</th>
                                            <th>Amount Due</th>
                                            <th>Status</th>
                                            <th>Billing Date</th>
                                            <th>Due Date</th>
                                            <th>Student Count</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($billingLogs as $log)
                                            <tr>
                                                <td><strong>{{ $log->family_code }}</strong></td>
                                                <td>
                                                    @if ($log->family && $log->family->parents->isNotEmpty())
                                                        @foreach ($log->family->parents as $parent)
                                                            <strong>{{ $parent->fname }} {{ $parent->lname }}</strong><br>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">No Parents Available</span>
                                                    @endif
                                                </td>
                                                <td><span class="badge badge-info">{{ $log->subscription_plan ?? 'No Plan' }}</span></td>
                                                <td><strong>₱{{ number_format($log->base_amount ?? 0, 2) }}</strong></td>
                                                <td>{{ $log->additional_multiplier ?? 0 }}</td>
                                                <td><strong class="text-success">₱{{ number_format($log->amount_due ?? 0, 2) }}</strong></td>
                                                <td>
                                                    @php
                                                        $statusClass = $log->status === 'paid' ? 'badge-success' : 
                                                                     ($log->isOverdue() ? 'badge-danger' : 'badge-warning');
                                                        $statusText = $log->status === 'paid' ? 'Paid' : 
                                                                     ($log->isOverdue() ? 'Overdue' : 'Pending');
                                                    @endphp
                                                    <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                                    @if($log->isOverdue())
                                                        <br><small class="text-danger">({{ $log->getOverdueDays() }} days overdue)</small>
                                                    @endif
                                                </td>
                                                <td><small>{{ $log->getFormattedBillingDate() }}</small></td>
                                                <td><small>{{ $log->getFormattedDueDate() }}</small></td>
                                                <td><span class="badge badge-secondary">{{ $log->student_count ?? 0 }}</span></td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        @if($log->status === 'pending')
                                                            <button class="btn btn-success btn-sm" onclick="markAsPaid({{ $log->id }})" title="Mark as Paid">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        @else
                                                            <button class="btn btn-warning btn-sm" onclick="markAsPending({{ $log->id }})" title="Mark as Pending">
                                                                <i class="fas fa-clock"></i>
                                                            </button>
                                                        @endif
                                                        <button class="btn btn-danger btn-sm" onclick="deleteBilling({{ $log->id }})" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
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
            $('#billingLogsTable').DataTable({
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'print'
                ],
                language: {
                    search: "Search billing logs:",
                    lengthMenu: "Show _MENU_ logs per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ logs",
                    infoEmpty: "No billing logs available",
                    infoFiltered: "(filtered from _MAX_ total logs)"
                },
                pageLength: 10,
                order: [[7, 'desc']], // Sort by billing date descending
                columnDefs: [
                    {
                        targets: [3, 4, 5, 8, 9], // Base Amount, Additional Multiplier, Amount Due, Due Date, Student Count
                        className: 'text-center'
                    },
                    {
                        targets: [6], // Status column
                        className: 'text-center'
                    }
                ]
            });

            // Load overdue count
            loadOverdueCount();
        });

        function generateAllBilling() {
            Swal.fire({
                title: 'Generate All Billing?',
                text: "This will generate billing for all families that don't have billing this month.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, generate!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/billing/generate-all';
                }
            });
        }

        function markAsPaid(billingId) {
            Swal.fire({
                title: 'Mark as Paid?',
                text: "This will mark the billing as paid.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, mark as paid!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/billing/${billingId}/mark-paid`;
                }
            });
        }

        function markAsPending(billingId) {
            Swal.fire({
                title: 'Mark as Pending?',
                text: "This will mark the billing as pending.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, mark as pending!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/billing/${billingId}/mark-pending`;
                }
            });
        }

        function deleteBilling(billingId) {
            Swal.fire({
                title: 'Delete Billing?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/billing/${billingId}/delete`;
                }
            });
        }

        function exportBilling() {
            window.location.href = '/billing/export';
        }

        function loadOverdueCount() {
            fetch('/billing/overdue')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('overdueCount').textContent = data.length;
                })
                .catch(error => {
                    console.error('Error loading overdue count:', error);
                    document.getElementById('overdueCount').textContent = 'Error';
                });
        }

        function refreshStats() {
            location.reload();
        }
    </script>
</body>
</html>
