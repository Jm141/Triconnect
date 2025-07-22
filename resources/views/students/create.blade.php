@extends('adminlte::page')

@section('title', 'Add Student')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script> 

    function calculateAge() {
        const birthday = document.getElementById("birth").value;
        const ageField = document.getElementById("age");

        if (birthday){
            const birthdate = new Date(birthday);
            const today = new Date();

            let age = today.getFullYear() - birthdate.getFullYear();
            const monthDiff =today.getMonth() - birthdate.getMonth();

            if (monthDiff < 0 || monthDiff == 0 && today.getDate() < birthdate.getDate()){
                age--;
            }

            if(age >0){
                ageField.value = age;
               
            }else{
                ageField.value="";
                alert ("invalid birthday ");
            }
        }else{
                ageField.value ="";
            }


        }


    
</script>
@section('content_header')
    <h1>Add Student</h1>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-info">
            <div class="card-header"><h3 class="card-title">Student Details</h3></div>
            <div class="card-body">
                @if(session()->has('error'))
                    <div class="alert alert-danger">
                        {!! session('error') !!}
                    </div>
                @endif
                <form action="/students/" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <input type="text" name="firstname" id="firstname" class="form-control" placeholder="Enter student firstname" required>
                    </div>
                    <div class="form-group">
                        <label for="middlename">Middle Name</label>
                        <input type="text" name="middlename" id="middlename" class="form-control" placeholder="Enter student middlename" required>
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" name="lastname" id="lastname" class="form-control" placeholder="Enter student lastname" required>
                    </div>
                    <div class="form-group">
                        <label for="birth">Birthday</label>
                        <input type="date" name="birth" id="birth" class="form-control" placeholder="Enter student birthday" onchange="calculateAge()" required>
                    </div>
                    <div class="form-group">
                        <label for="age">Age</label>
                        <input type="text" name="age" id="age" class="form-control" placeholder="Student age" readonly>
                    </div>
                    <div class="form-group">
                        <label for="grade">Grade Level</label>
                        <select name="grade" class="form-control" id="grade">
                            @for ($i = 1; $i <= 6; $i++)
                                <option value="Grade {{ $i }}">Grade {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Enter student email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="number" name="phone" id="phone" class="form-control" maxlength="11" placeholder="Enter student phone number" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea name="address" id="address" class="form-control" rows="3" placeholder="Enter student address" required></textarea>
                    </div>
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary">Add Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function calculateAge() {
        const birthday = document.getElementById("birth").value;
        const ageField = document.getElementById("age");
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
