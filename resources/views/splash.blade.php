<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Triconnect Splash</title>
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <style>
        body {
            margin: 0;
            height: 100vh;
            background: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            font-family: 'Figtree', sans-serif;
        }
        .logo {
            width: 120px;
            height: 120px;
            border-radius: 24px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
        }
        .logo img {
            width: 100px;
            height: 100px;
            object-fit: contain;
        }
        .title {
            color: #fff;
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 2px;
        }
    </style>
    <script>
        setTimeout(function() {
            window.location.href = "{{ route('login') }}";
        }, 2000);
    </script>
</head>
<body>
    <div class="logo">
        <img src="{{ asset('images/triconnect.png') }}" alt="Triconnect Logo">
    </div>
    <div class="title">Triconnect</div>
</body>
</html> 