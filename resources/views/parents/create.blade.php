@extends('adminlte::page')

@section('title', 'Add Family')

@section('content_header')
    <h1>Add Family</h1>
@endsection

@section('content')
<form action="/parents/" method="POST" id="registrationForm">
    @csrf
    <input type="hidden" name="subscription" id="subscriptionType" value="">

    <div class="row">
        <div class="col-md-6">
            <div class="card card-info">
                <div class="card-header"><h3 class="card-title">Parent Details</h3></div>
                <div class="card-body">
                    <div class="form-group"><label for="parent-fname">First Name</label><input type="text" name="parent[fname]" id="parent-fname" class="form-control" required></div> 
                    <div class="form-group"><label for="parent-mname">Middle Name</label><input type="text" name="parent[mname]" id="parent-mname" class="form-control" required></div> 
                    <div class="form-group"><label for="parent-lname">Last Name</label><input type="text" name="parent[lname]" id="parent-lname" class="form-control" required></div>
                    <div class="form-group"><label for="parent-number">Number</label><input type="text" name="parent[number]" id="parent-number" class="form-control" required></div>
                    <div class="form-group"><label for="parent-email">Email</label><input type="email" name="parent[email]" id="parent-email" class="form-control" required></div>
                    <div class="form-group"><label for="parent-password">Password</label><input type="password" name="parent[password]" id="parent-password" class="form-control" required></div>
                    <div class="form-group"><label for="Address">Address</label><input type="text" name="parent[address]" id="parent-address" class="form-control" required></div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-success">
                <div class="card-header"><h3 class="card-title">Student Details</h3></div>
                <div class="card-body">
                    <div id="students-container">
                        <div class="student-form mb-3">
                            <div class="form-group"><label for="student-fname-0">First Name</label><input type="text" name="students[0][fname]" id="student-fname-0" class="form-control" required></div>
                            <div class="form-group"><label for="student-mname-0">Middle Name</label><input type="text" name="students[0][mname]" id="student-mname-0" class="form-control" required></div>
                            <div class="form-group"><label for="student-lname-0">Last Name</label><input type="text" name="students[0][lname]" id="student-lname-0" class="form-control" required></div>
                            <div class="form-group"><label for="student-birth-0">Birthday</label><input type="date" name="students[0][birth]" id="student-birth-0" class="form-control" onchange="calculateAge(0)" required></div>
                            <div class="form-group"><label for="student-age-0">Age</label><input type="text" name="students[0][age]" id="student-age-0" class="form-control" readonly></div>
                            <div class="form-group"><label for="student-year-0">Grade Level</label>
                                <select name="students[0][year]" id="student-year-0" class="form-control" required>
                                    <option value="" disabled selected>Select Grade</option>
                                    @for ($i = 1; $i <= 6; $i++)
                                        <option value="Grade {{ $i }}">Grade {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="form-group"><label for="student-email-0">Email</label><input type="email" name="students[0][email]" id="student-email-0" class="form-control" required></div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary" onclick="addStudent()">Add Student</button>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group text-center">
        <button type="submit" class="btn btn-primary" onclick="showSubscriptionModal(event)">Submit</button>
    </div>
</form>

<script>
    let studentIndex = 1;
    function addStudent() {
        const container = document.getElementById('students-container');
        const newStudent = document.createElement('div');
        newStudent.className = 'student-form mb-3';
        newStudent.innerHTML = `
            <hr>
            <div class="form-group"><label for="student-fname-${studentIndex}">First Name</label><input type="text" name="students[${studentIndex}][fname]" class="form-control" required></div>
            <div class="form-group"><label for="student-mname-${studentIndex}">Middle Name</label><input type="text" name="students[${studentIndex}][mname]" class="form-control" required></div>
            <div class="form-group"><label for="student-lname-${studentIndex}">Last Name</label><input type="text" name="students[${studentIndex}][lname]" class="form-control" required></div>
            <div class="form-group"><label for="student-birth-${studentIndex}">Birthday</label><input type="date" name="students[${studentIndex}][birth]" class="form-control" onchange="calculateAge(${studentIndex})" required></div>
            <div class="form-group"><label for="student-age-${studentIndex}">Age</label><input type="text" name="students[${studentIndex}][age]" id="student-age-${studentIndex}" class="form-control" readonly></div>
            <div class="form-group"><label for="student-year-${studentIndex}">Grade Level</label>
                <select name="students[${studentIndex}][year]" class="form-control" required>
                    <option value="" disabled selected>Select Grade</option>
                    @for ($i = 1; $i <= 6; $i++)
                        <option value="Grade {{ $i }}">Grade {{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="form-group"><label for="student-email-${studentIndex}">Email</label><input type="email" name="students[${studentIndex}][email]" class="form-control" required></div>
        `;
        container.appendChild(newStudent);
        studentIndex++;
    }

    function calculateAge(index) {
        const birthday = document.querySelector(`[name="students[${index}][birth]"]`).value;
        const ageField = document.querySelector(`[name="students[${index}][age]"]`);
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

    function showSubscriptionModal(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Choose a Subscription Plan',
            html: `
            <div>
                <h4>Standard Subscription</h4>
                <p>Access to basic features for managing student records.</p>
            </div>
            <div>
                <h4>Premium Subscription</h4>
                <p>Access to advanced analytics, premium features, and priority support.</p>
            </div>
        `,
            showCancelButton: true,
            confirmButtonText: 'Standard',
            cancelButtonText: 'Premium'
        }).then((result) => {
            const subscriptionField = document.getElementById('subscriptionType');
            if (subscriptionField) {
                if (result.isConfirmed) {
                    subscriptionField.value = 'Standard';
                    document.getElementById('registrationForm').submit();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    subscriptionField.value = 'Premium';
                    document.getElementById('registrationForm').submit();
                }
            } else {
                console.error("Hidden input field with ID 'subscriptionType' is missing!");
            }
        });
    }
</script>
@endsection
