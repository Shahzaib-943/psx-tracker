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
            @role(\App\Constants\AppConstant::ROLE_ADMIN)
                <li class="nav-item nav-category">Users Section</li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" data-bs-toggle="collapse"
                        href="#users" role="button" aria-expanded="{{ request()->routeIs('users.*') ? 'true' : 'false' }}"
                        aria-controls="users">
                        <i class="link-icon" data-feather="users"></i>
                        <span class="link-title">Users</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('users.*') ? 'show' : '' }}" id="users">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('users.create') }}"
                                    class="nav-link {{ request()->routeIs('users.create') ? 'active' : '' }}">Create</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('users.index') }}"
                                    class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">List</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}" data-bs-toggle="collapse"
                        href="#roles" role="button"
                        aria-expanded="{{ request()->routeIs('roles.*') ? 'true' : 'false' }}" aria-controls="roles">
                        <i class="link-icon" data-feather="user-check"></i>
                        <span class="link-title">Roles</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('roles.*') ? 'show' : '' }}" id="roles">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('roles.create') }}"
                                    class="nav-link {{ request()->routeIs('roles.create') ? 'active' : '' }}">Create</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('roles.index') }}"
                                    class="nav-link {{ request()->routeIs('roles.index') ? 'active' : '' }}">List</a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endrole

            <li class="nav-item nav-category">Finance</li>
            <li class="nav-item">
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
            </li>
            @role(\App\Constants\AppConstant::ROLE_ADMIN)
                <li class="nav-item nav-category">Application</li>
                <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                    <a href="{{ route('home') }}" class="nav-link">
                        <i class="link-icon" data-feather="settings"></i>
                        <span class="link-title">Settings</span>
                    </a>
                </li>
            @endrole
    </div>
</nav>
