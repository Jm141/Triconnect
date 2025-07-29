# User Dashboard Navigation Component

This component provides a consistent navigation structure for all user dashboard pages in the Triconnect application.

## Files Created

1. **`components/user-dashboard-nav.blade.php`** - The main navigation component
2. **`layouts/user-dashboard.blade.php`** - Layout file that includes the navigation component
3. **`userDashboard.blade.php`** - Updated to use the new layout (refactored)

## How to Use

### Method 1: Using the Layout (Recommended)

For new pages, extend the user dashboard layout:

```php
@extends('layouts.user-dashboard')

@section('title', 'Your Page Title')

@section('content')
    {{-- Your page content here --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Your Page Title</h3>
                </div>
                <div class="card-body">
                    {{-- Your content --}}
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- Optional: Add page-specific CSS --}}
@push('css')
    <link rel="stylesheet" href="your-custom.css">
@endpush

{{-- Optional: Add page-specific JavaScript --}}
@push('js')
    <script src="your-script.js"></script>
@endpush

{{-- Optional: Add page-specific scripts --}}
@push('scripts')
    <script>
        $(document).ready(function() {
            // Your JavaScript code
        });
    </script>
@endpush
```

### Method 2: Including the Component Directly

If you need more control, you can include the navigation component directly:

```php
<!DOCTYPE html>
<html>
<head>
    {{-- Your head content --}}
</head>
<body>
    @include('components.user-dashboard-nav')
    
    {{-- Your page content --}}
</body>
</html>
```

## Navigation Features

### Active Link Highlighting
The navigation automatically highlights the current page using Laravel's `request()->is()` helper:

```php
{{ request()->is('family-list*') ? 'active' : '' }}
```

### User Information
The navigation displays the authenticated user's information:
- Name from `Auth::user()->name`
- Email from `Auth::user()->email`
- Profile picture (placeholder)

### Navigation Links
The component includes links to:
- Dashboard
- Family List
- Student List
- Teacher List
- Room Management
- Geofence
- Subscription Plans
- Billing Logs
- Settings

### User Dropdown
The header includes a user dropdown with:
- Profile link
- Logout functionality

## Customization

### Adding New Navigation Items
Edit `components/user-dashboard-nav.blade.php` and add new `<li>` elements to the navigation menu.

### Styling
The component uses AdminLTE classes and custom CSS. You can modify the styles in `layouts/user-dashboard.blade.php`.

### Conditional Navigation
You can add conditional navigation based on user roles or permissions:

```php
@if(Auth::user()->hasRole('admin'))
    <li class="nav-item">
        <a href="/admin-only" class="nav-link">
            <i class="nav-icon fa fa-shield"></i>
            <p>Admin Only</p>
        </a>
    </li>
@endif
```

## Benefits

1. **Consistency** - All pages have the same navigation structure
2. **Maintainability** - Changes to navigation only need to be made in one place
3. **DRY Principle** - No code duplication across pages
4. **Active State** - Automatic highlighting of current page
5. **Responsive** - Works on mobile and desktop
6. **Extensible** - Easy to add new features and pages

## Example Usage

See `userDashboard.blade.php` and `user/attendanceDashboard-new.blade.php` for complete examples of how to use the new navigation system. 