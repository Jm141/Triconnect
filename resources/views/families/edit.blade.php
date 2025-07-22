@extends('adminlte::page')

@section('title', 'Edit Family')

@section('content_header')
    <h1>Edit Family</h1>
@endsection

@section('content')
<form action="{{ route('parents.update', $family->family_code) }}" method="POST">
    @csrf
    @method('PUT') 

    <div class="row">
        <div class="col-md-6">
            <div class="card card-info">
                <div class="card-header"><h3 class="card-title">Parent Details</h3></div>
                <div class="card-body">
                    <div class="form-group"><label>First Name</label><input type="text" name="parent[fname]" value="{{ optional($family->parents->first())->fname }}" class="form-control" required></div>
                    <div class="form-group"><label>Middle Name</label><input type="text" name="parent[mname]" value="{{ optional($family->parents->first())->mname }}" class="form-control" required></div> 
                    <div class="form-group"><label>Last Name</label><input type="text" name="parent[lname]" value="{{ optional($family->parents->first())->lname }}" class="form-control" required></div>
                    <div class="form-group"><label>Number</label><input type="text" name="parent[number]" value="{{ optional($family->parents->first())->number }}" class="form-control" required></div>
                    <div class="form-group"><label>Email</label><input type="email" name="parent[email]" value="{{ optional($family->parents->first())->email }}" class="form-control" required></div>
                    <div class="form-group"><label>Address</label><input type="text" name="parent[address]" value="{{ optional($family)->address }}" class="form-control" required></div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-success">
                <div class="card-header"><h3 class="card-title">Student Details</h3></div>
                <div class="card-body">
                    @foreach ($family->students as $index => $student)
                        <div class="student-form mb-3">
                            <div class="form-group"><label>First Name</label><input type="text" name="students[{{ $index }}][fname]" value="{{ $student->firstname }}" class="form-control" required></div>
                            <div class="form-group"><label>Middle Name</label><input type="text" name="students[{{ $index }}][mname]" value="{{ $student->middlename }}" class="form-control" required></div>
                            <div class="form-group"><label>Last Name</label><input type="text" name="students[{{ $index }}][lname]" value="{{ $student->lastname }}" class="form-control" required></div>
                            <div class="form-group"><label>Birthday</label><input type="date" name="students[{{ $index }}][birth]" value="{{ $student->birth }}" class="form-control" onchange="calculateAge({{ $index }})" required></div>
                            <div class="form-group"><label>Age</label><input type="text" name="students[{{ $index }}][age]" id="student-age-{{ $index }}" value="{{ $student->age }}" class="form-control" readonly></div>
                            <div class="form-group"><label>Grade Level</label>
                                <select name="students[{{ $index }}][year]" class="form-control" required>
                                    @for ($i = 1; $i <= 6; $i++)
                                        <option value="Grade {{ $i }}" {{ $student->grade_level == 'Grade '.$i ? 'selected' : '' }}>Grade {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="form-group text-center">
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</form>

<script>
    function calculateAge(index) {
        const birthday = document.querySelector(`[name="students[${index}][birth]"]`).value;
        const ageField = document.getElementById(`student-age-${index}`);
        if (birthday) {
            const birthdate = new Date(birthday);
            const today = new Date();
            let age = today.getFullYear() - birthdate.getFullYear();
            const monthDiff = today.getMonth() - birthdate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthdate.getDate())) age--;
            ageField.value = age > 0 ? age : '';
        } else {
            ageField.value = '';
        }
    }
</script>
@endsection
