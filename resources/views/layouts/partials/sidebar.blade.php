<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ Vite::asset('resources/images/adminlte/pmo-a_main.png') }}" alt="PMO Assistant Logo"
            class="brand-image img-circle elevation-3">
        <span class="brand-text font-weight-light">PMO Assistant</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ App\Helpers\ImageHelper::imageToBase64($user->avatar ?? 'https://drive.google.com/uc?export=view&id=1lv0f70ekHE_5AH7o6NQPEF9PmCPgc6Mk') }}"
                    class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <nav class="user-panel mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                @role('admin')
                <!-- Admin links -->
                @include('layouts.partials.admin-sidebar')
                @endrole

                @role('client')
                <!-- Client links -->
                @include('layouts.partials.client-sidebar')
                @endrole

                @role('client|pm')
                <li class="nav-item {{ request()->is('pages*') ? 'menu-open' : '' }}">
                    <a href="pages/gallery.html" class="nav-link {{ request()->is('pages*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                            {{ __('messages.project_management') }}
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @foreach ($projects as $project)
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    {{ $project->name }}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('pm.task', $project->id) }}" class="nav-link">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>{{ __('messages.task_lists') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('pm.member', 1) }}" class="nav-link">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>{{ __('messages.members') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('pm.chart', 1) }}" class="nav-link">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>{{ __('messages.chart') }}</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endforeach
                    </ul>
                </li>
                @endrole

                @role('pm|staff')
                @foreach ($projects as $project)
                <li class="nav-item {{ request()->is('pages*') ? 'menu-open' : '' }}">
                    <a href="pages/gallery.html" class="nav-link {{ request()->is('pages*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                            {{ $project->name }}
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('staff.task', $project->id) }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('messages.task_lists') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('messages.chart') }}</p>
                            </a>
                    </ul>
                </li>
                @endforeach

                @endrole

                @auth
                <li class="nav-item mt-3" style="border-top: 1px solid rgba(255, 255, 255, 0.1);">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="#" class="nav-link"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="fas fa-sign-out-alt nav-icon"></i>
                            <p>{{ __('messages.logout') }}</p>
                        </a>
                    </form>
                </li>
                @endauth
            </ul>
        </nav>
    </div>
</aside>