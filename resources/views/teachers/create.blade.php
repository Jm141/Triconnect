@extends('adminlte::page')

@section('title', 'Add Teacher')

<script> 

    function calculateAge() {
        const birthday = document.getElementById("birthday").value;
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
    <h1>Admin Dashboard</h1>
@endsection

@section('content')
    <p>Welcome to the Admin Dashboard</p>
    
    <form action="{{ route('teachers.store') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Teacher Information</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="firstname">First Name</label>
                    <input type="text" id="firstname" name="firstname" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="middlename">Middle Name</label>
                    <input type="text" id="middlename" name="middlename" class="form-control">
                </div>

                <div class="form-group">
                    <label for="lastname">Last Name</label>
                    <input type="text" id="lastname" name="lastname" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="birthday">Birthday</label>
                    <input type="date" id="birthday" name="birthday" class="form-control" onchange="calculateAge()"  required>
                </div>

                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="text" id="age" name="age" class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" class="form-control" required>
                </div>

               

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Save Teacher</button>
            </div>
        </div>
    </form>
@endsection
