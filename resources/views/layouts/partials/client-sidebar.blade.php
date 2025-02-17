<li class="nav-item {{ request()->is('projects*') ? 'menu-open' : '' }}">
    <a href="pages/gallery.html" class="nav-link {{ request()->is('projects*') ? 'active' : '' }}">
        <i class="nav-icon fab fa-stack-overflow"></i>
        <p>
            {{ __('labels.project_setting') }}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('projects.index') }}"
                class="nav-link {{ request()->routeIs('projects.*') && !request()->routeIs('projects.create') ? 'active' : '' }}">
                <i class="fas fa-list nav-icon"></i>
                <p>{{ __('labels.projects') }}</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('projects.create') }}"
                class="nav-link {{ request()->routeIs('projects.create') ? 'active' : '' }}">
                <i class="fas fa-plus nav-icon"></i>
                <p>{{ __('labels.project_add') }}</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item {{ request()->routeIs('client.users.*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->routeIs('client.users.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-users"></i>
        <p>
            {{ __('labels.user_setting') }}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('client.users.list', auth()->user()->tenant_id) }}"
                class="nav-link  {{ request()->routeIs('client.users.*') && !request()->routeIs('client.users.create') ? 'active' : '' }}">
                <i class="fas fa-list nav-icon"></i>
                <p>{{ __('labels.users') }}</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('client.users.create', auth()->user()->tenant_id) }}"
                class="nav-link {{ request()->routeIs('client.users.create') ? 'active' : '' }}">
                <i class="fas fa-plus nav-icon"></i>
                <p>{{ __('labels.user_add') }}</p>
            </a>
        </li>
    </ul>
</li>

<li class="nav-item {{ request()->routeIs('client.worklogs.*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->routeIs('client.worklogs.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-briefcase"></i>
        <p>
            Worklog setting
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        {{-- <li class="nav-item">
            <a href="{{ route('client.users.list', auth()->user()->tenant_id) }}"
                class="nav-link  {{ request()->routeIs('client.users.*') && !request()->routeIs('client.users.create') ? 'active' : '' }}">
                <i class="fas fa-list nav-icon"></i>
                <p>Users</p>
            </a>
        </li> --}}
        <li class="nav-item">
            <a href="{{ route('client.worklogs.management', auth()->user()->tenant_id) }}"
                class="nav-link {{ request()->routeIs('client.worklogs.*') ? 'active' : '' }}">
                <i class="fas fa-list nav-icon"></i>
                <p>Management</p>
            </a>
        </li>
    </ul>
</li>
