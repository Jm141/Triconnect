<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - Triconnect</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        .notification-item {
            border-left: 4px solid #ddd;
            margin-bottom: 1rem;
            padding: 1rem;
            background: #fff;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .notification-item:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .notification-item.unread {
            border-left-color: #007bff;
            background: #f8f9ff;
        }

        .notification-item.urgent {
            border-left-color: #dc3545;
        }

        .notification-item.high {
            border-left-color: #ffc107;
        }

        .notification-item.medium {
            border-left-color: #17a2b8;
        }

        .notification-item.low {
            border-left-color: #6c757d;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.7rem;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="navbar navbar-expand navbar-dark bg-primary">
            <div class="container-fluid">
                <a href="{{ route('teacher.dashboard') }}" class="navbar-brand">
                    <i class="fa fa-arrow-left"></i> Back to Dashboard
                </a>
                <span class="navbar-brand mx-auto">Notifications</span>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <button class="btn btn-sm btn-outline-light" onclick="markAllAsRead()">
                            <i class="fa fa-check-double"></i> Mark All Read
                        </button>
                    </li>
                </ul>
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
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">All Notifications ({{ $notifications->total() }})</h3>
                                </div>
                                <div class="card-body">
                                    @if($notifications->count() > 0)
                                        @foreach($notifications as $notificationRecipient)
                                            @php
                                                $notification = $notificationRecipient->notification;
                                                $isUnread = $notificationRecipient->status === 'unread';
                                            @endphp
                                            <div class="notification-item {{ $isUnread ? 'unread' : '' }} {{ $notification->priority }}">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                            <h5 class="mb-1">
                                                                <a href="{{ route('notifications.show', $notificationRecipient->id) }}" class="text-decoration-none">
                                                                    {{ $notification->title }}
                                                                </a>
                                                            </h5>
                                                            <div>
                                                                <span class="badge {{ $notification->getPriorityBadgeClass() }}">
                                                                    {{ ucfirst($notification->priority) }}
                                                                </span>
                                                                @if($isUnread)
                                                                    <span class="badge badge-primary ml-1">NEW</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <p class="mb-2">{{ $notification->message }}</p>
                                                        <div class="text-muted">
                                                            <small>
                                                                <strong>From:</strong> {{ $notification->sender->name ?? 'Principal' }} | 
                                                                <strong>Sent:</strong> {{ $notification->created_at->format('M d, Y g:i A') }}
                                                                @if($notificationRecipient->read_at)
                                                                    | <strong>Read:</strong> {{ $notificationRecipient->read_at->format('M d, Y g:i A') }}
                                                                @endif
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                        <!-- Pagination -->
                                        <div class="d-flex justify-content-center mt-3">
                                            {{ $notifications->links() }}
                                        </div>
                                    @else
                                        <div class="text-center py-5">
                                            <i class="fa fa-bell fa-3x text-muted mb-3"></i>
                                            <h5>No Notifications</h5>
                                            <p class="text-muted">You don't have any notifications yet.</p>
                                        </div>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function markAllAsRead() {
            fetch('{{ route("notifications.mark-all-read") }}', {
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
                console.error('Error marking all notifications as read:', error);
            });
        }
    </script>
</body>
</html> 