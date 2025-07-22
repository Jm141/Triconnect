@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Admin Dashboard</h1>
@endsection

@section('content')
    <p>Update User Access</p>
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

    <form action="/teachers/{{ $teacher->staff_code }}" method="POST">
        @csrf 
        @method('PUT') 
        <input type="hidden" name="staffCode" id="staffCode" class="form-control" value="{{ $teacher->staff_code }}" placeholder="Enter student firstname" required>
        
        <div class="form-group">
            <label for="firstname">First Name:</label>
            <input type="text" name="firstname" id="firstname" class="form-control" value="{{ $teacher->firstname }}" placeholder="Enter student firstname" required>
        </div>

        <div class="form-group">
            <label for="middlename">Middle Name:</label>
            <input type="text" name="middlename" id="middlename" class="form-control" value="{{ $teacher->middlename }}" placeholder="Enter student middlename" required>
        </div>

        <div class="form-group">
            <label for="lastname">Last Name:</label>
            <input type="text" name="lastname" id="lastname" class="form-control" value="{{ $teacher->lastname }}" placeholder="Enter student lastname" required>
        </div>

        <div class="form-group">
            <label for="birth">Birthday:</label>
            <input type="date" name="birth" id="birth" class="form-control" value="{{ $teacher->birth }}" placeholder="Enter student birthday" required>
        </div>

        <div class="form-group">
            <label for="age">Age:</label>
            <input type="number" name="age" id="age" class="form-control" value="{{ $teacher->age }}" placeholder="Enter student age" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ $teacher->email }}" placeholder="Enter student email" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="number" name="phone" id="phone" class="form-control" maxlength="11" value="{{ $teacher->phone }}" placeholder="Enter student phone number" required>
        </div>

        <div class="form-group">
            <label for="address">Address:</label>
            <input name="address" id="address" class="form-control" rows="3" value="{{ $teacher->address }}" placeholder="Enter student address" required></textarea>
        </div>

        <div class="form-group">
            <label for="role">User Role:</label>
            <select name="role" id="role" class="form-control">
                <option value="teacher" {{ $teacher->role == 'teacher' ? 'selected' : '' }}>Teacher</option>
                <option value="principal" {{ $teacher->role == 'principal' ? 'selected' : '' }}>Principal</option>
                <option value="admin" {{ $teacher->role == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <div class="form-group">
            <label for="canupdate">Can Update:</label>
            <input type="checkbox" name="canupdate" id="canupdate" {{ $teacher->canupdate ? 'checked' : '' }}>
            <small class="form-text text-muted">Check this box to grant 'Can Update' access.</small>
        </div>

        <button type="submit" class="btn btn-primary">Update User</button>
    </form>
@endsection
