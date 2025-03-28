<!-- resources/views/layouts/partials/navbar.blade.php -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('my-worklog') }}" class="nav-link">My Worklog</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('leave-request') }}" class="nav-link">Leave Requests</a>
        </li>
        
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('password.change') }}" class="nav-link">Change Password</a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                @if(app()->getLocale() == 'en')
                    <i class="flag-icon flag-icon-us"></i> 
                @elseif(app()->getLocale() == 'jp')
                    <i class="flag-icon flag-icon-jp"></i>
                @elseif(app()->getLocale() == 'vi')
                    <i class="flag-icon flag-icon-vn"></i>
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-right p-0">
                <a href="{{ url('locale/en') }}" class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}">
                    <i class="flag-icon flag-icon-us mr-2"></i> English
                </a>
                <a href="{{ url('locale/jp') }}" class="dropdown-item {{ app()->getLocale() == 'jp' ? 'active' : '' }}">
                    <i class="flag-icon flag-icon-jp mr-2"></i> 日本語
                </a>
                <a href="{{ url('locale/vi') }}" class="dropdown-item {{ app()->getLocale() == 'vi' ? 'active' : '' }}">
                    <i class="flag-icon flag-icon-vn mr-2"></i> Tiếng Việt
                </a>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>
