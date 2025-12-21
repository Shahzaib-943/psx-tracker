<nav class="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('home') }}" class="sidebar-brand">
            {{ \App\Constants\AppConstant::APP_FIRST_NAME }}<span>{{ \App\Constants\AppConstant::APP_SECOND_NAME }}</span>
        </a>
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav">
            <li class="nav-item nav-category">Main</li>
            <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                <a href="{{ route('home') }}" class="nav-link">
                    <i class="link-icon" data-feather="box"></i>
                    <span class="link-title">Dashboard</span>
                </a>
            </li>
            @canAny(['view users', 'view roles'])
                {{-- <li class="nav-item nav-category">User Section</li> --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('users.*') || request()->routeIs('roles.*') ? 'active' : '' }}" data-bs-toggle="collapse"
                        href="#users-roles" role="button" aria-expanded="{{ request()->routeIs('users.*') || request()->routeIs('roles.*') ? 'true' : 'false' }}"
                        aria-controls="users-roles">
                        <i class="link-icon" data-feather="users"></i>
                        <span class="link-title">Users & Roles</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('users.*') || request()->routeIs('roles.*') ? 'show' : '' }}" id="users-roles">
                        <ul class="nav sub-menu">
                            @can('view users')
                            <li class="nav-item">
                                <a href="{{ route('users.index') }}"
                                class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">Users</a>
                            </li>
                            @endcan
                            @can('view roles')
                                <li class="nav-item">
                                    <a href="{{ route('roles.index') }}"
                                        class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">Roles</a>
                                </li>
                            @endcan
                            </ul>
                        </div>  
                </li>
            @endcanAny
            @can('view portfolios')
                {{-- <li class="nav-item nav-category">Finance</li> --}}
                <li class="nav-item {{ request()->routeIs('portfolios.*') ? 'active' : '' }}">
                    <a href="{{ route('portfolios.index') }}" class="nav-link">
                        <i class="link-icon mdi mdi-book-plus"></i>
                        <span class="link-title">Portfolios</span>
                    </a>
                </li>
            @endcan
            {{-- <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('portfolios.*') ? 'active' : '' }}"
                    data-bs-toggle="collapse" href="#portfolios" role="button"
                    aria-expanded="{{ request()->routeIs('portfolios.*') ? 'true' : 'false' }}"
                    aria-controls="portfolios">
                    <i class="link-icon mdi mdi-book-plus"></i>
                    <span class="link-title">Portfolios</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse {{ request()->routeIs('portfolios.*') ? 'show' : '' }}" id="portfolios">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('portfolios.create') }}"
                                class="nav-link {{ request()->routeIs('portfolios.create') ? 'active' : '' }}">Create</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('portfolios.index') }}"
                                class="nav-link {{ request()->routeIs('portfolios.index') ? 'active' : '' }}">List</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('portfolios.trade') }}"
                                class="nav-link {{ request()->routeIs('portfolios.trade') ? 'active' : '' }}">Trade</a>
                        </li>
                    </ul>
                </div>
            </li> --}}
            @can('view system-settings')
                {{-- <li class="nav-item nav-category">Application</li> --}}
                <li class="nav-item {{ request()->routeIs('system-settings.*') ? 'active' : '' }}">
                    <a href="{{ route('system-settings.index') }}" class="nav-link">
                        <i class="link-icon" data-feather="settings"></i>
                        <span class="link-title">System Settings</span>
                    </a>
                </li>
            @endcan
    </div>
</nav>