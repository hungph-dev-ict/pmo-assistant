<aside class="main-sidebar sidebar-dark-primary elevation-4">
    @php
        function getRoleIcon($role)
        {
            $icons = [
                'Head Account' => 'fas fa-user-tie', // 👔 Người đứng đầu tài khoản
                'Project Manager' => 'fas fa-briefcase', // 💼 Quản lý dự án
                'Bridge System Engineer' => 'fas fa-network-wired', // 🌉 Kỹ sư cầu nối hệ thống
                'Developer' => 'fab fa-stack-overflow', // 👨‍💻 Nhà phát triển phần mềm
                'Tester' => 'fas fa-user-md', // 🧪 Tester (kiểm thử phần mềm)
                'Comtor' => 'fas fa-language', // 🌎 Biên dịch viên (Comtor)
                'Other' => 'fas fa-user', // 👤 Mặc định cho các role khác
                'PMO Head' => 'fas fa-tasks', // 📋 Người đứng đầu PMO (quản lý dự án)
                'Auditor' => 'fas fa-search', // 🔍 Kiểm toán viên
                'Technical Leader' => 'fas fa-microchip', // 🖥️ Technical Leader
                'Team Leader' => 'fas fa-users-cog', // 👥 Trưởng nhóm
            ];

            return $icons[$role] ?? 'fas fa-user'; // Mặc định nếu không có trong danh sách
        }
    @endphp

    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ Vite::asset('resources/images/adminlte/pmo-a_main.png') }}" alt="PMO Assistant Logo"
            class="brand-image img-circle elevation-3">
        <span
            class="brand-text font-weight-light">{{ auth()->user()->tenant ? auth()->user()->tenant->name : 'PMO Assistant' }}</span>
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
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <span class="d-block" style="color: #c2c7d0">
                    <strong>Main Role:</strong> <i
                        class="nav-icon {{ getRoleIcon(Auth::user()->jobPosition->value2) }}"></i>
                    {{ Auth::user()->jobPosition->value2 }}
                </span>
                @if (Auth::user()->subRole1)
                    <span class="d-block" style="color: #c2c7d0"><strong>Sub Role 1:</strong> <i
                            class="nav-icon {{ getRoleIcon(Auth::user()->subRole1->value2) }}"></i>
                        {{ Auth::user()->subRole1->value2 }}</span>
                @endif
                @if (Auth::user()->subRole2)
                    <span class="d-block" style="color: #c2c7d0"><strong>Sub Role 2:</strong> <i
                            class="nav-icon {{ getRoleIcon(Auth::user()->subRole2->value2) }}"></i>
                        {{ Auth::user()->subRole2->value2 }} </span>
                @endif
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
                        foreach ($client_projects as $project) {
                            $taskIds = $project->tasks->pluck('id')->toArray();
                            if (in_array($currentTaskId, $taskIds)) {
                                $currentProjectId = $project->id;
                                break;
                            }
                        }
                    }
                @endphp

                @role('client')
                    @php
                        // Lấy Task ID từ URL
                        $currentTaskId = request()->segment(1) == 'task' ? request()->segment(2) : null;

                        // Kiểm tra Task ID có thuộc Project nào không
                        $currentProjectId = null;
                        if ($currentTaskId) {
                            foreach ($pm_projects as $project) {
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
                            <i class="nav-icon fas fa-project-diagram"></i>
                            <p>
                                Tenant Projects
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @foreach ($client_projects as $project)
                                @php
                                    $isActiveProject = request()->segment(2) == $project->id;
                                    $isActiveProject = $isActiveProject || $currentProjectId == $project->id;
                                @endphp

                                @php
                                    $colors = [
                                        'bg-primary',
                                        'bg-success',
                                        'bg-danger',
                                        'bg-warning',
                                        'bg-info',
                                        'bg-purple',
                                        'bg-indigo',
                                        'bg-teal',
                                        'bg-pink',
                                        'bg-orange',
                                        'bg-cyan',
                                        'bg-lime',
                                        'bg-brown',
                                        'bg-gray',
                                        'bg-lightblue',
                                        'bg-deep-orange',
                                        'bg-amber',
                                        'bg-blue-gray',
                                        'bg-deep-purple',
                                        'bg-light-green',
                                    ];
                                    $colorClass = $colors[$project->id % count($colors)];
                                @endphp

                                <li class="nav-item {{ $isActiveProject ? 'menu-open' : '' }}">
                                    <a href="#" class="nav-link {{ $isActiveProject ? 'active' : '' }}">
                                        <span class="project-icon {{ $colorClass }}">
                                            {{ strtoupper(substr($project->name, 0, 1)) }}
                                        </span>
                                        <p
                                            style="max-width: 160px; white-space: nowrap; overflow:hidden; text-overflow: ellipsis; display: inline-block; vertical-align: middle;">
                                            {{ $project->name }}
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('pm.task', $project->id) }}"
                                                class="nav-link {{ ($currentProjectId == $project->id || request()->segment(2) == $project->id) && request()->routeIs('pm.task') ? 'active' : '' }}">
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
                                        <li class="nav-item">
                                            <a href="{{ route('pm.worklogs.management', $project->id) }}"
                                                class="nav-link {{ request()->segment(2) == $project->id && request()->routeIs('pm.worklogs.management') ? 'active' : '' }}">
                                                <i class="fas fa-briefcase nav-icon"></i>
                                                <p>Project Worklog</p>
                                            </a>
                                        </li><li class="nav-item">
                                            <a href="{{ route('pm.components', $project->id) }}"
                                                class="nav-link {{ request()->segment(2) == $project->id && request()->routeIs('pm.components') ? 'active' : '' }}">
                                                <i class="fas fa-puzzle-piece nav-icon"></i>
                                                <p>Components</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endrole

                @if (Auth::user()->hasRole('pm') && !Auth::user()->hasRole('client'))
                    @php
                        // Lấy Task ID từ URL
                        $currentTaskId = request()->segment(1) == 'task' ? request()->segment(2) : null;

                        // Kiểm tra Task ID có thuộc Project nào không
                        $currentProjectId = null;
                        if ($currentTaskId) {
                            foreach ($pm_projects as $project) {
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
                            @foreach ($pm_projects as $project)
                                @php
                                    $isActiveProject = request()->segment(2) == $project->id;
                                    $isActiveProject = $isActiveProject || $currentProjectId == $project->id;
                                @endphp

                                @php
                                    $colors = [
                                        'bg-primary',
                                        'bg-success',
                                        'bg-danger',
                                        'bg-warning',
                                        'bg-info',
                                        'bg-purple',
                                        'bg-indigo',
                                        'bg-teal',
                                        'bg-pink',
                                        'bg-orange',
                                        'bg-cyan',
                                        'bg-lime',
                                        'bg-brown',
                                        'bg-gray',
                                        'bg-lightblue',
                                        'bg-deep-orange',
                                        'bg-amber',
                                        'bg-blue-gray',
                                        'bg-deep-purple',
                                        'bg-light-green',
                                    ];
                                    $colorClass = $colors[$project->id % count($colors)];
                                @endphp

                                <li class="nav-item {{ $isActiveProject ? 'menu-open' : '' }}">
                                    <a href="#" class="nav-link {{ $isActiveProject ? 'active' : '' }}">
                                        <span class="project-icon {{ $colorClass }}">
                                            {{ strtoupper(substr($project->name, 0, 1)) }}
                                        </span>
                                        <p
                                            style="max-width: 160px; white-space: nowrap; overflow:hidden; text-overflow: ellipsis; display: inline-block; vertical-align: middle;">
                                            {{ $project->name }}
                                            <i class="right fas fa-angle-left"
                                                style="display: inline-block; vertical-align: middle;"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('pm.task', $project->id) }}"
                                                class="nav-link {{ ($currentProjectId == $project->id || request()->segment(2) == $project->id) && request()->routeIs('pm.task') ? 'active' : '' }}">
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
                                        <li class="nav-item">
                                            <a href="{{ route('pm.worklogs.management', $project->id) }}"
                                                class="nav-link {{ request()->segment(2) == $project->id && request()->routeIs('pm.worklogs.management') ? 'active' : '' }}">
                                                <i class="fas fa-briefcase nav-icon"></i>
                                                <p>Project Worklog</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('pm.components', $project->id) }}"
                                                class="nav-link {{ request()->segment(2) == $project->id && request()->routeIs('pm.components') ? 'active' : '' }}">
                                                <i class="fas fa-puzzle-piece nav-icon"></i>
                                                <p>Components</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endrole


                @if (Auth::user()->hasRole('staff') && !Auth::user()->hasRole('client') && !Auth::user()->hasRole('pm'))
                    @foreach ($staff_projects as $project)
                        @php
                            $colors = [
                                'bg-primary',
                                'bg-success',
                                'bg-danger',
                                'bg-warning',
                                'bg-info',
                                'bg-purple',
                                'bg-indigo',
                                'bg-teal',
                                'bg-pink',
                                'bg-orange',
                                'bg-cyan',
                                'bg-lime',
                                'bg-brown',
                                'bg-gray',
                                'bg-lightblue',
                                'bg-deep-orange',
                                'bg-amber',
                                'bg-blue-gray',
                                'bg-deep-purple',
                                'bg-light-green',
                            ];
                            $colorClass = $colors[$project->id % count($colors)];
                        @endphp
                        <li
                            class="nav-item {{ request()->segment(2) == $project->id && request()->routeIs('staff.*') ? 'menu-open' : '' }}">
                            <a href="pages/gallery.html"
                                class="nav-link {{ request()->segment(2) == $project->id && request()->routeIs('staff.*') ? 'active' : '' }}">
                                <span class="project-icon {{ $colorClass }}">
                                    {{ strtoupper(substr($project->name, 0, 1)) }}
                                </span>
                                <p
                                    style="max-width: 160px; white-space: nowrap; overflow:hidden; text-overflow: ellipsis; display: inline-block; vertical-align: middle;">
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

<style>
.project-icon {
width: 30px;
height: 30px;
display: inline-flex;
align-items: center;
justify-content: center;
font-size: 16px;
font-weight: bold;
color: white;
border-radius: 50%;
/* Bo tròn */
margin-right: 8px;
/* Khoảng cách với tên project */
text-transform: uppercase;
/* In hoa */
}
</style>
