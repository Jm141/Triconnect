<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Notification - Principal Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --light-gray: #ecf0f1;
            --dark-gray: #7f8c8d;
            --white: #ffffff;
            --shadow: 0 2px 10px rgba(0,0,0,0.1);
            --border-radius: 8px;
        }

        .navbar-brand img {
            width: 40px;
            height: 40px;
            margin-right: 12px;
            border-radius: 50%;
            background: var(--white);
            padding: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .main-sidebar {
            transition: width 0.3s ease;
        }

        .main-sidebar.collapsed {
            width: 70px;
        }

        .main-sidebar.collapsed .user-panel .info,
        .main-sidebar.collapsed .nav-link p {
            display: none;
        }

        .main-sidebar.collapsed .user-panel {
            justify-content: center;
            padding: 1rem 0.5rem;
        }

        .main-sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 0.875rem 0.5rem;
        }

        .main-sidebar.collapsed .nav-icon {
            margin-right: 0;
            font-size: 1.2rem;
        }

        .content-wrapper {
            transition: margin-left 0.3s ease;
        }

        .content-wrapper.collapsed {
            margin-left: 70px;
        }

        .sidebar-toggle {
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            background: var(--accent-color);
            border: none;
            color: white;
            padding: 0.5rem;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .sidebar-toggle:hover {
            background: #2980b9;
            transform: scale(1.1);
        }

        .notification-form {
            border-left: 4px solid var(--accent-color);
        }

        .priority-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            margin: 0.25rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .priority-badge:hover {
            transform: scale(1.05);
        }

        .priority-badge.low {
            background: var(--light-gray);
            color: var(--dark-gray);
        }

        .priority-badge.medium {
            background: var(--accent-color);
            color: var(--white);
        }

        .priority-badge.high {
            background: var(--warning-color);
            color: var(--white);
        }

        .priority-badge.urgent {
            background: var(--danger-color);
            color: var(--white);
        }

        .priority-badge.selected {
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.3);
        }

        .recipient-option {
            border: 2px solid var(--light-gray);
            border-radius: var(--border-radius);
            padding: 1rem;
            margin: 0.5rem 0;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .recipient-option:hover {
            border-color: var(--accent-color);
            background: rgba(52, 152, 219, 0.05);
        }

        .recipient-option.selected {
            border-color: var(--accent-color);
            background: rgba(52, 152, 219, 0.1);
        }

        .recipient-option input[type="radio"] {
            margin-right: 0.5rem;
        }

        .preview-card {
            border: 2px dashed var(--light-gray);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            background: var(--white);
            margin-top: 1rem;
        }

        .preview-card.show {
            border-color: var(--accent-color);
            background: rgba(52, 152, 219, 0.05);
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Sidebar Toggle Button -->
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fa fa-bars"></i>
        </button>

        <!-- Navbar -->
        <nav class="navbar navbar-expand navbar-dark bg-primary">
            <div class="container-fluid">
                <a href="{{ route('principal.dashboard') }}" class="navbar-brand">
                    <img src="/images/Triconnect.png" alt="Triconnect Logo" onerror="this.onerror=null; this.src='https://via.placeholder.com/40x40/3498db/ffffff?text=T';">
                    <span>Principal Portal</span>
                </a>
                
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <form method="POST" action="{{ route('userLogout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link" style="background: none; border: none; color: inherit;">
                                <i class="fa fa-sign-out"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Sidebar -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4" id="sidebar">
            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="/images/Triconnect.png" class="img-circle elevation-2" alt="User Image" onerror="this.onerror=null; this.src='https://via.placeholder.com/160x160/3498db/ffffff?text=P';">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">{{ session('userAccess')->name ?? 'Principal' }}</a>
                        <small class="text-muted">Principal Account</small>
                    </div>
                </div>

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        <li class="nav-item">
                            <a href="{{ route('principal.dashboard') }}" class="nav-link">
                                <i class="nav-icon fa fa-dashboard"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('principal.students') }}" class="nav-link">
                                <i class="nav-icon fa fa-graduation-cap"></i>
                                <p>Student List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('principal.teachers') }}" class="nav-link">
                                <i class="nav-icon fa fa-users"></i>
                                <p>Teacher List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('principal.schedules') }}" class="nav-link">
                                <i class="nav-icon fa fa-calendar"></i>
                                <p>Schedules</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('principal.notifications') }}" class="nav-link">
                                <i class="nav-icon fa fa-bell"></i>
                                <p>Notifications</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('principal.notifications.create') }}" class="nav-link active">
                                <i class="nav-icon fa fa-plus"></i>
                                <p>Send Notification</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper" id="contentWrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Send Notification</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('principal.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('principal.notifications') }}">Notifications</a></li>
                                <li class="breadcrumb-item active">Send Notification</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card notification-form">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fa fa-bell"></i> Create New Notification
                                    </h3>
                                </div>
                                <div class="card-body">
                                    @if(session('success'))
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    @endif

                                    @if(session('error'))
                                        <div class="alert alert-danger">
                                            {{ session('error') }}
                                        </div>
                                    @endif

                                    <form action="{{ route('principal.notifications.store') }}" method="POST" id="notificationForm">
                                        @csrf
                                        
                                        <!-- Title -->
                                        <div class="form-group">
                                            <label for="title">Notification Title <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                                   id="title" name="title" value="{{ old('title') }}" 
                                                   placeholder="Enter notification title" required>
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Message -->
                                        <div class="form-group">
                                            <label for="message">Message <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                                      id="message" name="message" rows="6" 
                                                      placeholder="Enter your message here..." required>{{ old('message') }}</textarea>
                                            @error('message')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                <span id="charCount">0</span> characters
                                            </small>
                                        </div>

                                        <!-- Recipients -->
                                        <div class="form-group">
                                            <label>Recipients <span class="text-danger">*</span></label>
                                            <div class="recipient-options">
                                                <div class="recipient-option" onclick="selectRecipient('all')">
                                                    <input type="radio" name="recipient_type" value="all" id="recipient_all" 
                                                           {{ old('recipient_type') == 'all' ? 'checked' : '' }} required>
                                                    <label for="recipient_all">
                                                        <strong>All Teachers & Parents</strong>
                                                        <br><small class="text-muted">Send to all teachers and parents in the system</small>
                                                    </label>
                                                </div>
                                                
                                                <div class="recipient-option" onclick="selectRecipient('teachers')">
                                                    <input type="radio" name="recipient_type" value="teachers" id="recipient_teachers"
                                                           {{ old('recipient_type') == 'teachers' ? 'checked' : '' }}>
                                                    <label for="recipient_teachers">
                                                        <strong>Teachers Only</strong>
                                                        <br><small class="text-muted">Send to all teachers only</small>
                                                    </label>
                                                </div>
                                                
                                                <div class="recipient-option" onclick="selectRecipient('parents')">
                                                    <input type="radio" name="recipient_type" value="parents" id="recipient_parents"
                                                           {{ old('recipient_type') == 'parents' ? 'checked' : '' }}>
                                                    <label for="recipient_parents">
                                                        <strong>Parents Only</strong>
                                                        <br><small class="text-muted">Send to all parents only</small>
                                                    </label>
                                                </div>
                                            </div>
                                            @error('recipient_type')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Priority -->
                                        <div class="form-group">
                                            <label>Priority Level <span class="text-danger">*</span></label>
                                            <div class="priority-options">
                                                <span class="priority-badge low" onclick="selectPriority('low')">
                                                    <i class="fa fa-arrow-down"></i> Low
                                                </span>
                                                <span class="priority-badge medium" onclick="selectPriority('medium')">
                                                    <i class="fa fa-minus"></i> Medium
                                                </span>
                                                <span class="priority-badge high" onclick="selectPriority('high')">
                                                    <i class="fa fa-arrow-up"></i> High
                                                </span>
                                                <span class="priority-badge urgent" onclick="selectPriority('urgent')">
                                                    <i class="fa fa-exclamation-triangle"></i> Urgent
                                                </span>
                                            </div>
                                            <input type="hidden" name="priority" id="priorityInput" value="{{ old('priority', 'medium') }}" required>
                                            @error('priority')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Preview -->
                                        <div class="form-group">
                                            <label>Preview</label>
                                            <div class="preview-card" id="previewCard">
                                                <div class="text-center text-muted">
                                                    <i class="fa fa-eye fa-2x mb-2"></i>
                                                    <p>Notification preview will appear here</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Submit Buttons -->
                                        <div class="form-group">
                                            <button type="button" class="btn btn-info" onclick="previewNotification()">
                                                <i class="fa fa-eye"></i> Preview
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-paper-plane"></i> Send Notification
                                            </button>
                                            <a href="{{ route('principal.notifications') }}" class="btn btn-secondary">
                                                <i class="fa fa-times"></i> Cancel
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Tips Card -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fa fa-lightbulb-o"></i> Tips for Effective Notifications
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <i class="fa fa-check text-success"></i>
                                            <strong>Be Clear:</strong> Use concise, clear language
                                        </li>
                                        <li class="mb-2">
                                            <i class="fa fa-check text-success"></i>
                                            <strong>Set Priority:</strong> Choose appropriate priority level
                                        </li>
                                        <li class="mb-2">
                                            <i class="fa fa-check text-success"></i>
                                            <strong>Target Audience:</strong> Select the right recipients
                                        </li>
                                        <li class="mb-2">
                                            <i class="fa fa-check text-success"></i>
                                            <strong>Preview:</strong> Always preview before sending
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Priority Guide -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fa fa-info-circle"></i> Priority Guide
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <span class="badge badge-secondary">Low</span>
                                        <small>General announcements, updates</small>
                                    </div>
                                    <div class="mb-2">
                                        <span class="badge badge-info">Medium</span>
                                        <small>Important information, reminders</small>
                                    </div>
                                    <div class="mb-2">
                                        <span class="badge badge-warning">High</span>
                                        <small>Urgent matters, deadlines</small>
                                    </div>
                                    <div class="mb-2">
                                        <span class="badge badge-danger">Urgent</span>
                                        <small>Critical issues, emergencies</small>
                                    </div>
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
        // Sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const contentWrapper = document.getElementById('contentWrapper');
            
            // Check if sidebar state is saved in localStorage
            const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            
            if (sidebarCollapsed) {
                sidebar.classList.add('collapsed');
                contentWrapper.classList.add('collapsed');
            }

            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                contentWrapper.classList.toggle('collapsed');
                
                // Save state to localStorage
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            });

            // Add tooltips for collapsed sidebar
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('mouseenter', function() {
                    if (sidebar.classList.contains('collapsed')) {
                        const icon = this.querySelector('.nav-icon');
                        const text = this.querySelector('p').textContent;
                        icon.title = text;
                    }
                });
            });

            // Initialize priority selection
            selectPriority('{{ old("priority", "medium") }}');
            
            // Character count for message
            const messageTextarea = document.getElementById('message');
            const charCount = document.getElementById('charCount');
            
            messageTextarea.addEventListener('input', function() {
                charCount.textContent = this.value.length;
            });

            // Form validation
            document.getElementById('notificationForm').addEventListener('submit', function(e) {
                const title = document.getElementById('title').value.trim();
                const message = document.getElementById('message').value.trim();
                const recipientType = document.querySelector('input[name="recipient_type"]:checked');
                const priority = document.getElementById('priorityInput').value;

                if (!title || !message || !recipientType || !priority) {
                    e.preventDefault();
                    Swal.fire('Error', 'Please fill in all required fields.', 'error');
                    return false;
                }

                // Confirm before sending
                e.preventDefault();
                Swal.fire({
                    title: 'Send Notification?',
                    text: 'Are you sure you want to send this notification?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, send it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });

        function selectRecipient(type) {
            // Remove selected class from all options
            document.querySelectorAll('.recipient-option').forEach(option => {
                option.classList.remove('selected');
            });
            
            // Add selected class to clicked option
            event.currentTarget.classList.add('selected');
            
            // Check the radio button
            document.getElementById('recipient_' + type).checked = true;
        }

        function selectPriority(priority) {
            // Remove selected class from all badges
            document.querySelectorAll('.priority-badge').forEach(badge => {
                badge.classList.remove('selected');
            });
            
            // Add selected class to clicked badge
            event.currentTarget.classList.add('selected');
            
            // Update hidden input
            document.getElementById('priorityInput').value = priority;
        }

        function previewNotification() {
            const title = document.getElementById('title').value.trim();
            const message = document.getElementById('message').value.trim();
            const recipientType = document.querySelector('input[name="recipient_type"]:checked');
            const priority = document.getElementById('priorityInput').value;

            if (!title || !message || !recipientType || !priority) {
                Swal.fire('Error', 'Please fill in all fields before previewing.', 'error');
                return;
            }

            const previewCard = document.getElementById('previewCard');
            const recipientText = recipientType.value === 'all' ? 'All Teachers & Parents' : 
                                recipientType.value === 'teachers' ? 'Teachers Only' : 'Parents Only';

            previewCard.innerHTML = `
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h5 class="mb-0">${title}</h5>
                    <span class="badge ${getPriorityBadgeClass(priority)}">${priority.toUpperCase()}</span>
                </div>
                <p class="mb-2">${message}</p>
                <div class="text-muted">
                    <small><strong>To:</strong> ${recipientText}</small><br>
                    <small><strong>From:</strong> {{ session('userAccess')->name ?? 'Principal' }}</small><br>
                    <small><strong>Time:</strong> ${new Date().toLocaleString()}</small>
                </div>
            `;
            previewCard.classList.add('show');
        }

        function getPriorityBadgeClass(priority) {
            switch (priority) {
                case 'urgent': return 'badge-danger';
                case 'high': return 'badge-warning';
                case 'medium': return 'badge-info';
                case 'low': return 'badge-secondary';
                default: return 'badge-secondary';
            }
        }
    </script>
</body>
</html> 