<nav class="navbar">
    <a href="#" class="sidebar-toggler">
        <i data-feather="menu"></i>
    </a>
    <div class="navbar-content">
        <form class="search-form">
            <div class="input-group">
                <div class="input-group-text">
                    <i data-feather="search"></i>
                </div>
                <input type="text" class="form-control" id="navbarForm" placeholder="{{ __('Search...') }}">
            </div>
        </form>

        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="flag-icon flag-icon-{{ app()->getLocale() == 'ar' ? 'jo' : 'us' }} mt-1" title="{{ app()->getLocale() == 'ar' ? 'عربي' : 'English' }}"></i>
                    <span class="ms-1 me-1 d-none d-md-inline-block">{{ app()->getLocale() == 'ar' ? 'عربي' : 'English' }}</span>
                </a>
                <div class="dropdown-menu" aria-labelledby="languageDropdown">
                    <a href="{{ route('lang.switch', 'en') }}" class="dropdown-item py-2">
                        <i class="flag-icon flag-icon-us" title="us" id="us"></i>
                        <span class="ms-1"> English </span>
                    </a>
                    <a href="{{ route('lang.switch', 'ar') }}" class="dropdown-item py-2">
                        <i class="flag-icon flag-icon-jo" title="jo" id="jo"></i>
                        <span class="ms-1"> عربي </span>
                    </a>
                </div>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="wd-30 ht-30 rounded-circle" src="{{ asset('assets/images/faces/face1.jpg') }}" alt="profile">
                </a>
                <div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
                    <div class="d-flex flex-column align-items-center border-bottom px-5 py-3">
                        <div class="mb-3">
                            <img class="wd-80 ht-80 rounded-circle" src="{{ asset('assets/images/faces/face1.jpg') }}" alt="">
                        </div>
                        <div class="text-center">
                            <p class="tx-16 fw-bolder">{{ __('System Admin') }}</p>
                            <p class="tx-12 text-muted">{{ __('Admin') }}</p>
                        </div>
                    </div>
                    <ul class="list-unstyled p-1">
                        <li class="dropdown-item py-2">
                            <a href="#" class="text-body ms-0">
                                <i class="me-2 icon-md" data-feather="user"></i>
                                <span>{{ __('My Profile') }}</span>
                            </a>
                        </li>
                        <li class="dropdown-item py-2">
                            <a href="#" class="text-body ms-0">
                                <i class="me-2 icon-md" data-feather="settings"></i>
                                <span>{{ __('Settings') }}</span>
                            </a>
                        </li>
                        <li class="dropdown-item py-2">
                            <a href="{{ route('logout') }}" class="text-body ms-0 text-danger"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="me-2 icon-md text-danger" data-feather="log-out"></i>
                                <span>{{ __('Logout') }}</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</nav>
