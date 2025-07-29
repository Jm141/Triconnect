{{-- User Dashboard Navigation Component --}}
@if(session('userAccess') && (strpos(session('userAccess')->access, 'admin') !== false || strpos(session('userAccess')->access, 'teacher') !== false || strpos(session('userAccess')->access, 'parent') !== false))
<div class="wrapper">
    {{-- Main Header --}}
    <nav class="main-header navbar navbar-expand navbar-dark bg-triconnect shadow-lg">
        <div class="navbar-brand d-flex align-items-center">
            <img src="{{ asset('images/Triconnect.png') }}" alt="Triconnect Logo" style="height: 35px; margin-right: 10px;">
            <span class="text-white text-2xl font-bold tracking-wide">Triconnect</span>
        </div>
        
        {{-- Right side navbar links --}}
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-user"></i> {{ Auth::user()->name ?? 'User' }}
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="fa fa-user-circle"></i> Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('userLogout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fa fa-sign-out"></i> Logout
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    {{-- Sidebar --}}
    <aside class="main-sidebar sidebar-dark-primary elevation-4 bg-triconnect-dark">
        <div class="sidebar">
            {{-- User Panel --}}
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="/images/Triconnect.png" class="img-circle elevation-2" alt="User Image" onerror="this.onerror=null; this.src='https://via.placeholder.com/150/3498db/ffffff?text=T';">
                </div>
                <div class="info">
                    <a href="#" class="d-block text-white">{{ Auth::user()->name ?? 'User' }}</a>
                    <small class="text-muted">{{ Auth::user()->email ?? '' }}</small>
                </div>
            </div>

            {{-- Navigation Menu --}}
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    {{-- Dashboard --}}
                    <li class="nav-item">
                        <a href="{{ route('userDashboard') }}" class="nav-link text-triconnect-accent hover:bg-triconnect-light {{ request()->routeIs('userDashboard') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-tachometer"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    {{-- Show admin/teacher navigation items --}}
                    @if(strpos(session('userAccess')->access, 'admin') !== false || strpos(session('userAccess')->access, 'teacher') !== false)
                        {{-- Family List --}}
                        <li class="nav-item">
                            <a href="/family-list" class="nav-link text-triconnect-accent hover:bg-triconnect-light {{ request()->is('family-list*') ? 'active' : '' }}">
                                <i class="nav-icon fa fa-home"></i>
                                <p>Family List</p>
                            </a>
                        </li>

                        {{-- Student List --}}
                        <li class="nav-item">
                            <a href="/student-list" class="nav-link text-triconnect-accent hover:bg-triconnect-light {{ request()->is('student-list*') ? 'active' : '' }}">
                                <i class="nav-icon fa fa-graduation-cap"></i>
                                <p>Student List</p>
                            </a>
                        </li>

                        {{-- Teacher List --}}
                        <li class="nav-item">
                            <a href="/teacher-list" class="nav-link text-triconnect-accent hover:bg-triconnect-light {{ request()->is('teacher-list*') ? 'active' : '' }}">
                                <i class="nav-icon fa fa-users"></i>
                                <p>Teacher List</p>
                            </a>
                        </li>

                        {{-- Room Management --}}
                        <li class="nav-item">
                            <a href="/roomList" class="nav-link text-triconnect-accent hover:bg-triconnect-light {{ request()->is('roomList*') ? 'active' : '' }}">
                                <i class="nav-icon fa fa-building"></i>
                                <p>Room</p>
                            </a>
                        </li>

                        {{-- Geofence --}}
                        <li class="nav-item">
                            <a href="/geofence" class="nav-link text-triconnect-accent hover:bg-triconnect-light {{ request()->is('geofence*') ? 'active' : '' }}">
                                <i class="nav-icon fa fa-map-marker-alt"></i>
                                <p>Geofence</p>
                            </a>
                        </li>

                        {{-- Subscription Plans --}}
                        <li class="nav-item">
                            <a href="/subscription" class="nav-link text-triconnect-accent hover:bg-triconnect-light {{ request()->is('subscription*') ? 'active' : '' }}">
                                <i class="nav-icon fa fa-credit-card"></i>
                                <p>Subscription Plans</p>
                            </a>
                        </li>

                        {{-- Billing Logs --}}
                        <li class="nav-item">
                            <a href="{{ route('billing.index') }}" class="nav-link text-triconnect-accent hover:bg-triconnect-light {{ request()->is('billing*') ? 'active' : '' }}">
                                <i class="fas fa-file-invoice-dollar text-triconnect-accent"></i>
                                <p>Billing Logs</p>
                            </a>
                        </li>
                    @endif

                    {{-- Settings --}}
                    <li class="nav-item">
                        <a href="/settings" class="nav-link text-triconnect-accent hover:bg-triconnect-light {{ request()->is('settings*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-cog"></i>
                            <p>Settings</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    {{-- Content Wrapper --}}
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                {{-- Page content will be inserted here --}}
                @yield('content')
            </div>
        </section>
    </div>
</div>
@endif 