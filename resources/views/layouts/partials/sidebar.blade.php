<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ Vite::asset('resources/images/adminlte/pmo-a_main.png') }}" alt="PMO Assistant Logo"
            class="brand-image img-circle elevation-3">
        <span class="brand-text font-weight-light">PMO Assistant</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ auth()->user()->avatar
                    ? App\Helpers\ImageHelper::imageToBase64(auth()->user()->avatar)
                    : Vite::asset('resources/images/adminlte/avatar5.png') }}"
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

                @php
                    // Lấy Task ID từ URL
                    $currentTaskId = request()->segment(1) == 'task' ? request()->segment(2) : null;

                    // Tìm Project ID nào chứa Task ID này
                    $currentProjectId = null;
                    if ($currentTaskId) {
                        foreach ($projects as $project) {
                            $taskIds = $project->tasks->pluck('id')->toArray();
                            if (in_array($currentTaskId, $taskIds)) {
                                $currentProjectId = $project->id;
                                break;
                            }
                        }
                    }
                @endphp

                @role('pm')
                    @php
                        // Lấy Task ID từ URL
                        $currentTaskId = request()->segment(1) == 'task' ? request()->segment(2) : null;

                        // Kiểm tra Task ID có thuộc Project nào không
                        $currentProjectId = null;
                        if ($currentTaskId) {
                            foreach ($projects as $project) {
                                $taskIds = $project->tasks->pluck('id')->toArray();
                                if (in_array($currentTaskId, $taskIds)) {
                                    $currentProjectId = $project->id;
                                    break;
                                }
                            }
                        }

                        // Kiểm tra có Project nào đang mở không
                        $isMenuOpen = request()->is('pm*') || $currentProjectId;
                    @endphp

                    <li class="nav-item {{ $isMenuOpen ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $isMenuOpen ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                {{ __('labels.project_management') }}
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @foreach ($projects as $project)
                                @php
                                    $isActiveProject = request()->segment(2) == $project->id;
                                    $isActiveProject = $isActiveProject || $currentProjectId == $project->id;
                                @endphp

                                <li class="nav-item {{ $isActiveProject ? 'menu-open' : '' }}">
                                    <a href="#" class="nav-link {{ $isActiveProject ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            {{ $project->name }}
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('pm.task', $project->id) }}"
                                                class="nav-link {{ $currentProjectId == $project->id || (request()->segment(2) == $project->id || request()->routeIs('pm.task')) ? 'active' : '' }}">
                                                <i class="fas fa-list-ul nav-icon"></i>
                                                <p>{{ __('labels.task_lists') }}</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('pm.member', $project->id) }}"
                                                class="nav-link {{ request()->segment(2) == $project->id && request()->routeIs('pm.member') ? 'active' : '' }}">
                                                <i class="fas fa-user-friends nav-icon"></i>
                                                <p>{{ __('labels.members') }}</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endrole


                @role('staff')
                    @foreach ($projects as $project)
                        <li
                            class="nav-item {{ request()->segment(2) == $project->id && request()->routeIs('staff.*') ? 'menu-open' : '' }}">
                            <a href="pages/gallery.html"
                                class="nav-link {{ request()->segment(2) == $project->id && request()->routeIs('staff.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-book"></i>
                                <p>
                                    {{ $project->name }}
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('staff.task', $project->id) }}"
                                        class="nav-link {{ request()->segment(2) == $project->id && request()->routeIs('staff.task') ? 'active' : '' }}">
                                        <i class="fas fa-list-ul nav-icon"></i>
                                        <p>{{ __('labels.task_lists') }}</p>
                                    </a>
                                </li>
                                {{-- <li class="nav-item">
                                    <a href="{{ route('staff.member', $project->id) }}"
                                        class="nav-link {{ request()->segment(2) == $project->id && request()->routeIs('staff.member') ? 'active' : '' }}">
                                        <i class="fas fa-user-friends nav-icon"></i>
                                        <p>{{ __('messages.members') }}</p>
                                    </a>
                                </li> --}}
                                {{-- <li class="nav-item">
                                    <a href="{{ route('staff.chart', $project->id) }}"
                                        class="nav-link {{ request()->segment(2) == $project->id && request()->routeIs('staff.chart') ? 'active' : '' }}">
                                        <i class="fas fa-chart-line nav-icon"></i>
                                        <p>{{ __('labels.chart') }}</p>
                                    </a>
                                </li> --}}
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
                                <p>{{ __('labels.logout') }}</p>
                            </a>
                        </form>
                    </li>
                @endauth
            </ul>
        </nav>
    </div>
</aside>
