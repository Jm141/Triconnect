<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Registration</title>

    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        /* Card Container */
        .card {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .card-header h3 {
            font-size: 24px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
            display: block;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }

        .form-group input:focus {
            outline: none;
            border-color: #4e73df;
            box-shadow: 0 0 5px rgba(78, 115, 223, 0.6);
        }

        .btn-primary {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: #4e73df;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #2e59d9;
        }

        .mt-4 {
            margin-top: 20px;
            text-align: center;
        }

        .mt-4 a {
            color: #007bff;
            text-decoration: none;
        }

        .mt-4 a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <script>
        function calculateAge() {
            const birthday = document.getElementById("birthday").value;
            const ageField = document.getElementById("age");

            if (birthday) {
                const birthdate = new Date(birthday);
                const today = new Date();

                let age = today.getFullYear() - birthdate.getFullYear();
                const monthDiff = today.getMonth() - birthdate.getMonth();

                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthdate.getDate())) {
                    age--;
                }

                if (age > 0) {
                    ageField.value = age;
                } else {
                    ageField.value = "";
                    alert("Invalid birthday");
                }
            } else {
                ageField.value = "";
            }
        }
    </script>

    <form action="{{ route('user.registerTeacher') }}" method="POST">
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
                    <input type="date" id="birthday" name="birthday" class="form-control" onchange="calculateAge()" required>
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
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Register</button>
            </div>
        </div>
    </form>

    <div class="mt-4">
        <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">
            Already registered? Login here.
        </a>
    </div>

</body>
</html>
