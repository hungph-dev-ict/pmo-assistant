<li class="nav-item {{ request()->is('tenants*') ? 'menu-open' : '' }}">
    <a href="pages/gallery.html"
        class="nav-link {{ request()->is('tenants*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-briefcase"></i>
        <p>
            {{ __('messages.tenant_setting') }}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('tenants.index') }}"
                class="nav-link {{ request()->routeIs('tenants.*') && !request()->routeIs('tenants.create') ? 'active' : '' }}">
                <i class="fas fa-list nav-icon"></i>
                <p>{{ __('messages.tenants') }}</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= route('tenants.create') ?>"
                class="nav-link <?= request()->routeIs('tenants.create') ? 'active' : '' ?>">
                <i class="fas fa-plus nav-icon"></i>
                <p>{{ __('messages.tenant_add') }}</p>
            </a>
        </li>
    </ul>
</li>