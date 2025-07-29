<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Details - Triconnect</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    
    <style>
        .notification-detail {
            border-left: 4px solid #ddd;
            padding: 2rem;
            background: #fff;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .notification-detail.urgent {
            border-left-color: #dc3545;
        }

        .notification-detail.high {
            border-left-color: #ffc107;
        }

        .notification-detail.medium {
            border-left-color: #17a2b8;
        }

        .notification-detail.low {
            border-left-color: #6c757d;
        }

        .priority-badge {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="navbar navbar-expand navbar-dark bg-primary">
            <div class="container-fluid">
                <a href="{{ route('notifications.index') }}" class="navbar-brand">
                    <i class="fa fa-arrow-left"></i> Back to Notifications
                </a>
                <span class="navbar-brand mx-auto">Notification Details</span>
                <a href="{{ route('teacher.dashboard') }}" class="navbar-brand">
                    <i class="fa fa-home"></i> Dashboard
                </a>
            </div>
        </nav>

        <div class="content-wrapper">
            <div class="content">
                <div class="container-fluid">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-12">
                            <div class="notification-detail {{ $notificationRecipient->notification->priority }}">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h2 class="mb-0">{{ $notificationRecipient->notification->title }}</h2>
                                    <span class="badge {{ $notificationRecipient->notification->getPriorityBadgeClass() }} priority-badge">
                                        {{ ucfirst($notificationRecipient->notification->priority) }} Priority
                                    </span>
                                </div>
                                
                                <div class="mb-4">
                                    <p class="lead">{{ $notificationRecipient->notification->message }}</p>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title mb-0">
                                                    <i class="fa fa-info-circle"></i> Notification Details
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-borderless">
                                                    <tr>
                                                        <td><strong>From:</strong></td>
                                                        <td>{{ $notificationRecipient->notification->sender->name ?? 'Principal' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Sent:</strong></td>
                                                        <td>{{ $notificationRecipient->notification->created_at->format('M d, Y g:i A') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Recipients:</strong></td>
                                                        <td>{{ $notificationRecipient->notification->getRecipientTypeDisplay() }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Status:</strong></td>
                                                        <td>
                                                            @if($notificationRecipient->status === 'unread')
                                                                <span class="badge badge-warning">Unread</span>
                                                            @else
                                                                <span class="badge badge-success">Read</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @if($notificationRecipient->read_at)
                                                    <tr>
                                                        <td><strong>Read at:</strong></td>
                                                        <td>{{ $notificationRecipient->read_at->format('M d, Y g:i A') }}</td>
                                                    </tr>
                                                    @endif
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title mb-0">
                                                    <i class="fa fa-users"></i> Recipient Information
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <p><strong>Total Recipients:</strong> {{ $notificationRecipient->notification->recipient_count }}</p>
                                                <p><strong>Your Status:</strong> 
                                                    @if($notificationRecipient->status === 'unread')
                                                        <span class="text-warning">Unread</span>
                                                    @else
                                                        <span class="text-success">Read</span>
                                                    @endif
                                                </p>
                                                <p><strong>Your Role:</strong> 
                                                    @if(strpos(session('userAccess')->access, 'teacher') !== false)
                                                        <span class="badge badge-info">Teacher</span>
                                                    @elseif(strpos(session('userAccess')->access, 'parent') !== false)
                                                        <span class="badge badge-success">Parent</span>
                                                    @else
                                                        <span class="badge badge-secondary">Other</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <a href="{{ route('notifications.index') }}" class="btn btn-secondary">
                                        <i class="fa fa-arrow-left"></i> Back to Notifications
                                    </a>
                                    <a href="{{ route('teacher.dashboard') }}" class="btn btn-info">
                                        <i class="fa fa-home"></i> Dashboard
                                    </a>
                                    @if($notificationRecipient->status === 'unread')
                                        <button class="btn btn-primary" onclick="markAsRead()">
                                            <i class="fa fa-check"></i> Mark as Read
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
    <script>
        function markAsRead() {
            fetch('{{ route("notifications.mark-read", $notificationRecipient->id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error marking notification as read:', error);
            });
        }
    </script>
</body>
</html> 