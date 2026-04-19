<nav class="sidebar">
    <div class="sidebar-header">
        <a href="{{ url('/admin') }}" class="sidebar-brand">
            Smart<span>Care</span>
        </a>
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>

    <div class="sidebar-body">
        <ul class="nav">
            <li class="nav-item nav-category">{{ __('System Management') }}</li>

            <li class="nav-item {{ request()->is('admin') ? 'active' : '' }}">
                <a href="{{ url('/') }}" class="nav-link">
                    <i class="link-icon" data-feather="pie-chart"></i>
                    <span class="link-title">{{ __('Dashboard (Analytics)') }}</span>
                </a>
            </li>

            <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <a href="#" class="nav-link">
                    <i class="link-icon" data-feather="users"></i>
                    <span class="link-title">{{ __('User Management') }}</span>
                </a>
            </li>

            <li class="nav-item {{ request()->routeIs('admin.hospitals.*') || request()->routeIs('admin.approvals.*') ? 'active' : '' }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#hospitalsMenu" role="button" aria-expanded="{{ request()->routeIs('admin.hospitals.*') || request()->routeIs('admin.approvals.*') ? 'true' : 'false' }}" aria-controls="hospitalsMenu">
                    <i class="link-icon" data-feather="activity"></i>
                    <span class="link-title">{{ __('Hospitals') }}</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse {{ request()->routeIs('admin.hospitals.*') || request()->routeIs('admin.approvals.*') ? 'show' : '' }}" id="hospitalsMenu">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('admin.hospitals.index') }}" class="nav-link {{ request()->routeIs('admin.hospitals.index') ? 'active' : '' }}">{{ __('Approved Hospitals') }}</a>
                        </li>
                        <li class="nav-item d-flex align-items-center justify-content-between">
                            <a href="{{ route('admin.approvals.index') }}" class="nav-link {{ request()->routeIs('admin.approvals.index') ? 'active' : '' }}">{{ __('Join Requests') }}</a>
                            <span class="badge bg-danger rounded-pill">{{ __('New') }}</span>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
                <a href="{{ route('admin.employees.index') }}" class="nav-link">
                    <i class="link-icon" data-feather="briefcase"></i>
                    <span class="link-title">{{ __('Employee Management') }}</span>
                </a>
            </li>

            <li class="nav-item {{ request()->routeIs('admin.insurance.*') ? 'active' : '' }}">
                <a href="{{ route('admin.insurance.index') }}" class="nav-link">
                    <i class="link-icon" data-feather="shield"></i>
                    <span class="link-title">{{ __('Insurance Requests') }}</span>
                </a>
            </li>

            <li class="nav-item {{ request()->routeIs('admin.complaints.*') ? 'active' : '' }}">
                <a href="#" class="nav-link">
                    <i class="link-icon" data-feather="message-square"></i>
                    <span class="link-title">{{ __('Complaints & Suggestions') }}</span>
                </a>
            </li>

            <li class="nav-item nav-category">{{ __('Account') }}</li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="link-icon" data-feather="user"></i>
                    <span class="link-title">{{ __('Profile') }}</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
