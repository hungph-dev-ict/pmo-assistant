<li class="nav-item {{ request()->is('projects*') ? 'menu-open' : '' }}">
    <a href="pages/gallery.html"
        class="nav-link {{ request()->is('projects*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-briefcase"></i>
        <p>
            Tenant setting
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('tenants.index') }}"
                class="nav-link {{ request()->routeIs('projects.index') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Tenants</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('tenants.create') }}"
                class="nav-link {{ request()->routeIs('projects.create') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Tenant Add</p>
            </a>
        </li>
    </ul>
</li>