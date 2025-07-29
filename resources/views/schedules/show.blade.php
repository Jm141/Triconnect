<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Schedule - Triconnect</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    @if (strpos(session('userAccess')->access, 'teacher') !== false)
    <div class="wrapper">
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row mb-4">
                    <div class="col-12">
                        <h1 class="h3 mb-0">Schedule Details</h1>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fa fa-calendar"></i> Schedule Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td><strong>Teacher:</strong></td>
                                                <td>{{ $schedule->teacher->firstname ?? 'N/A' }} {{ $schedule->teacher->lastname ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Subject:</strong></td>
                                                <td>{{ $schedule->subject_name }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Room:</strong></td>
                                                <td>{{ $schedule->room->name ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Day:</strong></td>
                                                <td>
                                                    @if(is_array($schedule->day_of_week))
                                                        @foreach($schedule->day_of_week as $day)
                                                            <span class="badge badge-primary me-1">{{ $day }}</span>
                                                        @endforeach
                                                    @else
                                                        <span class="badge badge-primary">{{ $schedule->day_of_week }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td><strong>Start Time:</strong></td>
                                                <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>End Time:</strong></td>
                                                <td>{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Grade Level:</strong></td>
                                                <td>{{ $schedule->grade_level ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Section:</strong></td>
                                                <td>{{ $schedule->section ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Status:</strong></td>
                                                <td>
                                                    <span class="badge badge-{{ $schedule->status === 'active' ? 'success' : 'warning' }}">
                                                        {{ ucfirst($schedule->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                
                                @if($schedule->notes)
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <h6><strong>Notes:</strong></h6>
                                        <p>{{ $schedule->notes }}</p>
                                    </div>
                                </div>
                                @endif

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <a href="{{ route('schedules.index') }}" class="btn btn-secondary">
                                            <i class="fa fa-arrow-left"></i> Back to List
                                        </a>
                                        <a href="{{ route('schedules.edit', $schedule) }}" class="btn btn-warning">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
        <p>Access Denied</p>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
</body>
</html> 