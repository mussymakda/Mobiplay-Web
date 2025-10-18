<div id="navbar-wrapper">
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="#" class="navbar-brand nav-desk-brand" id="sidebar-toggle"><i class="fa fa-bars"></i></a>
                <div class="right-nav">
                    <a href="#" class="notification-link"><img src="{{ asset('assets/images/notification.svg') }}"></a>
                    <div class="lang-menu">
                        <a href="{{ route('switchLang', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'active' : '' }}">
                            <img src="{{ asset('assets/images/us.svg') }}" alt="English"> EN
                        </a>
                        <a href="{{ route('switchLang', 'es') }}" class="{{ app()->getLocale() == 'es' ? 'active' : '' }}">
                            <img src="{{ asset('assets/images/mexico.png') }}" style="height: 25px; width: 25px;" alt="Español"> ES
                        </a>
                    </div>
                    <div class="dropdown profile-dropdown">
                        <a href="#" class="profile-name dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                            <span>{{ Auth::user()->name }}</span>
                            <img src="{{ Auth::user()->profile_image_url ?? asset('assets/images/default-avatar.png') }}" 
                                 alt="Profile" class="profile-image">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile') }}">
                                    <i class="fas fa-user me-2"></i> {{ __('messages.profile') }}
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger logout-btn">
                                        <i class="fas fa-sign-out-alt me-2"></i> {{ __('messages.logout') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</div>