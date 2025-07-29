<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Triconnect Dashboard')</title>
    
    {{-- CSS Dependencies --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    
    {{-- Additional CSS for specific pages --}}
    @stack('css')
    
    <style>
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #343a40;
            padding: 15px;
            position: fixed;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 10px 0;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #c2c7d0;
        }

        .content {
            margin-left: 270px;
            padding: 20px;
        }

        .main-header {
            position: fixed;
            width: 100%;
            background-color: #343a40;
            color: #c2c7d0;
            padding: 10px;
            z-index: 1;
        }

        .main-header a {
            color: #c2c7d0;
            text-decoration: none;
        }

        /* Active navigation link styling */
        .nav-link.active {
            background-color: #007bff !important;
            color: white !important;
        }

        /* Hover effects */
        .nav-link:hover {
            background-color: #495057 !important;
            color: white !important;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed bg-triconnect-light text-triconnect-dark">
    
    {{-- Include the navigation component --}}
    @include('components.user-dashboard-nav')

    {{-- JavaScript Dependencies --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    
    {{-- Additional JavaScript for specific pages --}}
    @stack('js')
    
    {{-- Page-specific scripts --}}
    @stack('scripts')
</body>
</html> 