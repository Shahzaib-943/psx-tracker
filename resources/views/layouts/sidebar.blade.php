<nav class="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('home') }}" class="sidebar-brand">
            Fiscal<span>Ease</span>
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
            @role(\App\Models\User::ROLE_ADMIN)
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
                <a class="nav-link {{ request()->routeIs('finance-categories.*') ? 'active' : '' }}"
                    data-bs-toggle="collapse" href="#finance-categories" role="button"
                    aria-expanded="{{ request()->routeIs('finance-categories.*') ? 'true' : 'false' }}"
                    aria-controls="finance-categories">
                    <i class="link-icon mdi mdi-book-open"></i>
                    <span class="link-title">Categories</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse {{ request()->routeIs('finance-categories.*') ? 'show' : '' }}"
                    id="finance-categories">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('finance-categories.create') }}"
                                class="nav-link {{ request()->routeIs('finance-categories.create') ? 'active' : '' }}">Create</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('finance-categories.index') }}"
                                class="nav-link {{ request()->routeIs('finance-categories.index') ? 'active' : '' }}">List</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('finance-records.*') ? 'active' : '' }}"
                    data-bs-toggle="collapse" href="#finance-records" role="button"
                    aria-expanded="{{ request()->routeIs('finance-records.*') ? 'true' : 'false' }}"
                    aria-controls="finance-records">
                    <i class="link-icon mdi mdi-book-plus"></i>
                    <span class="link-title">Records</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse {{ request()->routeIs('finance-records.*') ? 'show' : '' }}" id="finance-records">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('finance-records.create') }}"
                                class="nav-link {{ request()->routeIs('finance-records.create') ? 'active' : '' }}">Create</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('finance-records.index') }}"
                                class="nav-link {{ request()->routeIs('finance-records.index') ? 'active' : '' }}">List</a>
                        </li>
                    </ul>
                </div>
            </li>
    </div>
</nav>
