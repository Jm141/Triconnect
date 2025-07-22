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
    
    <form action="{{ route('rooms.store') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Insert Room</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="room">Room Name</label>
                    <input type="text" id="room" name="room" class="form-control" required>
                </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Save Room</button>
            </div>
        </div>
    </form>
@endsection
