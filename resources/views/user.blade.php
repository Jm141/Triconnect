<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', Arial, sans-serif;
            background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 50%, #56ab2f 100%);
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            padding: 40px 32px 32px 32px;
            max-width: 400px;
            width: 100%;
            margin: 32px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .logo {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 2px 8px rgba(33, 147, 176, 0.15);
            margin-bottom: 16px;
            object-fit: contain;
        }
        .title {
            font-size: 2rem;
            font-weight: 600;
            color: #2193b0;
            margin-bottom: 24px;
            letter-spacing: 1px;
        }
        .form-group {
            width: 100%;
            margin-bottom: 18px;
        }
        .form-group label {
            font-weight: 600;
            color: #2193b0;
            margin-bottom: 6px;
            display: block;
        }
        .form-group input {
            width: 100%;
            padding: 12px 10px;
            font-size: 1rem;
            border-radius: 6px;
            border: 1px solid #b2dfdb;
            background: #f7fafc;
            transition: border 0.2s;
        }
        .form-group input:focus {
            outline: none;
            border-color: #56ab2f;
            background: #e3fcec;
        }
        .btn-primary {
            width: 100%;
            padding: 12px;
            font-size: 1.1rem;
            background: linear-gradient(90deg, #2193b0 0%, #56ab2f 100%);
            color: #fff;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(33, 147, 176, 0.10);
            transition: background 0.2s, box-shadow 0.2s;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #56ab2f 0%, #2193b0 100%);
            box-shadow: 0 4px 16px rgba(33, 147, 176, 0.15);
        }
        .links {
            width: 100%;
            margin-top: 18px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .links a {
            color: #2193b0;
            text-decoration: none;
            font-size: 0.98rem;
            margin: 4px 0;
            transition: color 0.2s;
        }
        .links a:hover {
            color: #56ab2f;
            text-decoration: underline;
        }
        .alert {
            width: 100%;
            padding: 12px 16px;
            margin-bottom: 18px;
            border-radius: 6px;
            background: linear-gradient(90deg, #f8d7da 0%, #f1f8e9 100%);
            color: #b71c1c;
            font-weight: 500;
            border: 1px solid #f5c6cb;
            box-shadow: 0 1px 4px rgba(183, 28, 28, 0.07);
            text-align: center;
        }
        @media (max-width: 500px) {
            .login-container {
                padding: 24px 8px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="{{ asset('images/triconnect.png') }}" alt="Triconnect Logo" class="logo" />
        <div class="title">Triconnect User Login</div>
        @if(session()->has('error'))
            <div class="alert">
                {!! session('error') !!}
            </div>
        @endif
        <form method="post" action="{{ route('userLogin') }}">
            @csrf
            <div class="form-group">
                <label for="email">{{ __('Email') }}</label>
                <input type="email" class="form-control" id="email" name="email" required autocomplete="username">
            </div>
            <div class="form-group">
                <label for="password">{{ __('Password') }}</label>
                <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password">
            </div>
            <button type="submit" class="btn-primary">{{ __('Login') }}</button>
        </form>
        <div class="links">
            <a href="{{ route('user.registerTeacher') }}">Don't have an account? Register here</a>
            <a href="{{ route('password.request') }}">Forgot password?</a>
        </div>
    </div>
</body>
</html>
