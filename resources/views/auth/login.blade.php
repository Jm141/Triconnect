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
            background: #e3eaf3;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1a202c;
        }
        .container {
            display: flex;
            width: 820px;
            max-width: 98vw;
            min-height: 440px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px 0 rgba(33, 147, 176, 0.08);
            overflow: hidden;
        }
        .left {
            flex: 1.1;
            background: #eaf4fb;
            color: #1a202c;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 28px;
        }
        .left .logo {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 2px 8px rgba(33, 147, 176, 0.10);
            margin-bottom: 16px;
            object-fit: contain;
        }
        .left .system-name {
            font-size: 1.7rem;
            font-weight: 700;
            margin-bottom: 14px;
            letter-spacing: 1px;
            color: #222;
        }
        .left .desc {
            font-size: 1rem;
            margin-bottom: 18px;
            opacity: 0.92;
            color: #1a202c;
        }
        .left ul {
            list-style: none;
            padding: 0;
            margin: 0 0 0 0;
        }
        .left ul li {
            margin-bottom: 10px;
            font-size: 0.98rem;
            display: flex;
            align-items: center;
            color: #176b8a;
        }
        .left ul li i {
            margin-right: 8px;
            font-size: 1.1rem;
            color: #176b8a;
        }
        .right {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 28px;
            background: #fff;
            color: #1a202c;
        }
        .login-container {
            width: 100%;
            max-width: 320px;
            margin: 0 auto;
        }
        .title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #222;
            margin-bottom: 20px;
            letter-spacing: 1px;
            text-align: center;
        }
        .form-group {
            width: 100%;
            margin-bottom: 16px;
        }
        .form-group label {
            font-weight: 600;
            color: #2193b0;
            margin-bottom: 5px;
            display: block;
        }
        .form-group input {
            width: 100%;
            padding: 10px 9px;
            font-size: 1rem;
            border-radius: 5px;
            border: 1px solid #b2dfdb;
            background: #f7fafc;
            transition: border 0.2s;
        }
        .form-group input:focus {
            outline: none;
            border-color: #2193b0;
            background: #eaf4fb;
        }
        .btn-primary {
            width: 100%;
            padding: 11px;
            font-size: 1.05rem;
            background: #2193b0;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(33, 147, 176, 0.07);
            transition: background 0.2s, box-shadow 0.2s;
        }
        .btn-primary:hover {
            background: #176b8a;
            box-shadow: 0 4px 16px rgba(33, 147, 176, 0.10);
        }
        .links {
            width: 100%;
            margin-top: 14px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .links a {
            color: #2193b0;
            text-decoration: none;
            font-size: 0.95rem;
            margin: 3px 0;
            transition: color 0.2s;
        }
        .links a:hover {
            color: #176b8a;
            text-decoration: underline;
        }
        .alert {
            width: 100%;
            padding: 10px 14px;
            margin-bottom: 14px;
            border-radius: 5px;
            background: #e3f2fd;
            color: #176b8a;
            font-weight: 500;
            border: 1px solid #b3e0fc;
            box-shadow: 0 1px 4px rgba(33, 147, 176, 0.04);
            text-align: center;
        }
        .form-group.remember-group {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
        }
        .form-group.remember-group label {
            margin: 0;
            font-weight: 400;
            color: #555;
            cursor: pointer;
        }
        @media (max-width: 900px) {
            .container { flex-direction: column; min-width: 0; width: 98vw; }
            .left, .right { padding: 28px 8px; }
        }
        @media (max-width: 600px) {
            .container { flex-direction: column; min-width: 0; width: 100vw; border-radius: 0; }
            .left, .right { padding: 18px 4px; }
            .login-container { max-width: 100vw; }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body>
    <div class="container">
        <div class="left">
            <img src="{{ asset('images/triconnect.png') }}" alt="Triconnect Logo" class="logo" />
            <div class="system-name">Triconnect</div>
            <div class="desc">Smart school management and geofencing platform.</div>
            <ul>
                <li><i class="fas fa-user-graduate"></i> Student & Teacher Management</li>
                <li><i class="fas fa-map-marker-alt"></i> Geofence Tracking</li>
                <li><i class="fas fa-chart-bar"></i> Analytics & Reports</li>
                <li><i class="fas fa-users"></i> Family Integration</li>
                <li><i class="fas fa-bell"></i> Instant Alerts</li>
                <li><i class="fas fa-lock"></i> Secure Access</li>
            </ul>
        </div>
        <div class="right">
            <div class="login-container">
                <div class="title">Login</div>
                @if(session('status'))
                    <div class="alert">{{ session('status') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert">
                        @foreach($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                @endif
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <label for="email">{{ __('Email') }}</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                    </div>
                    <div class="form-group">
                        <label for="password">{{ __('Password') }}</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password">
                    </div>
                    <div class="form-group remember-group">
                        <input id="remember_me" type="checkbox" name="remember">
                        <label for="remember_me">{{ __('Remember me') }}</label>
                    </div>
                    <button type="submit" class="btn-primary">{{ __('Log in') }}</button>
                </form>
                <div class="links">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">Forgot your password?</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>
