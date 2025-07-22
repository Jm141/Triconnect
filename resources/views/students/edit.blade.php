@extends('adminlte::page')

@section('title', 'Edit Student')

@section('content_header')
    <h1>Edit Student</h1>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-info">
            <div class="card-header"><h3 class="card-title">Student Details</h3></div>
            <div class="card-body">
                <form action="/students/{{ $student->family_code }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <input type="text" name="firstname" id="firstname" class="form-control" value="{{ $student->firstname }}" placeholder="Enter student firstname" required>
                        <input type="hidden" name="code" id="code" class="form-control" value="{{ $student->family_code }}">
                    </div>
                    <div class="form-group">
                        <label for="middlename">Middle Name</label>
                        <input type="text" name="middlename" id="middlename" class="form-control" value="{{ $student->middlename }}" placeholder="Enter student middlename" required>
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" name="lastname" id="lastname" class="form-control" value="{{ $student->lastname }}" placeholder="Enter student lastname" required>
                    </div>
                    <div class="form-group">
                        <label for="birth">Birthday</label>
                        <input type="date" name="birth" id="birth" class="form-control" value="{{ $student->birth }}" placeholder="Enter student birthday" required>
                    </div>
                    <div class="form-group">
                        <label for="age">Age</label>
                        <input type="number" name="age" id="age" class="form-control" value="{{ $student->age }}" placeholder="Enter student age" required>
                    </div>
                    <div class="form-group">
                        <label for="grade">Grade Level</label>
                        <select name="grade" class="form-control" id="grade">
                            @for ($i = 1; $i <= 6; $i++)
                                <option value="Grade {{ $i }}" {{ $student->grade_level == 'Grade '.$i ? 'selected' : '' }}>Grade {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ $student->email }}" placeholder="Enter student email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary">Update Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
