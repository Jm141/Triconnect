<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Schedule - Triconnect</title>
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
                        <h1 class="h3 mb-0">Edit Schedule</h1>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fa fa-edit"></i> Schedule Information</h5>
                            </div>
                            <div class="card-body">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form action="{{ route('schedules.update', $schedule) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="teacher_staff_code" class="form-label">Teacher *</label>
                                            <select name="teacher_staff_code" id="teacher_staff_code" class="form-control" required>
                                                <option value="">Select Teacher</option>
                                                @foreach($teachers as $teacher)
                                                    <option value="{{ $teacher->staff_code }}" {{ old('teacher_staff_code', $schedule->teacher_staff_code) == $teacher->staff_code ? 'selected' : '' }}>
                                                        {{ $teacher->firstname }} {{ $teacher->lastname }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="subject_name" class="form-label">Subject Name *</label>
                                            <input type="text" name="subject_name" id="subject_name" class="form-control" value="{{ old('subject_name', $schedule->subject_name) }}" required>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="room_code" class="form-label">Room *</label>
                                            <select name="room_code" id="room_code" class="form-control" required>
                                                <option value="">Select Room</option>
                                                @foreach($rooms as $room)
                                                    <option value="{{ $room->room_code }}" {{ old('room_code', $schedule->room_code) == $room->room_code ? 'selected' : '' }}>
                                                        {{ $room->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="day_of_week" class="form-label">Day of Week *</label>
                                            <select name="day_of_week" id="day_of_week" class="form-control" required>
                                                <option value="">Select Day</option>
                                                @foreach($days as $day)
                                                    <option value="{{ $day }}" {{ old('day_of_week', $schedule->day_of_week) == $day ? 'selected' : '' }}>
                                                        {{ $day }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="start_time" class="form-label">Start Time *</label>
                                            <input type="time" name="start_time" id="start_time" class="form-control" value="{{ old('start_time', $schedule->start_time) }}" required>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="end_time" class="form-label">End Time *</label>
                                            <input type="time" name="end_time" id="end_time" class="form-control" value="{{ old('end_time', $schedule->end_time) }}" required>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="grade_level" class="form-label">Grade Level</label>
                                            <select name="grade_level" id="grade_level" class="form-control">
                                                <option value="">Select Grade Level</option>
                                                @foreach($gradeLevels as $grade)
                                                    <option value="{{ $grade }}" {{ old('grade_level', $schedule->grade_level) == $grade ? 'selected' : '' }}>
                                                        {{ $grade }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="section" class="form-label">Section</label>
                                            <input type="text" name="section" id="section" class="form-control" value="{{ old('section', $schedule->section) }}">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="status" class="form-label">Status *</label>
                                            <select name="status" id="status" class="form-control" required>
                                                <option value="active" {{ old('status', $schedule->status) == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="inactive" {{ old('status', $schedule->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label for="notes" class="form-label">Notes</label>
                                            <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $schedule->notes) }}</textarea>
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <div class="d-flex justify-content-end">
                                                <a href="{{ route('schedules.index') }}" class="btn btn-secondary me-2">
                                                    <i class="fa fa-times"></i> Cancel
                                                </a>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fa fa-save"></i> Update Schedule
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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