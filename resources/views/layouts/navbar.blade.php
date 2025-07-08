<div id="navbar-wrapper">
  <nav class="navbar navbar-inverse">
      <div class="container-fluid">
          <div class="navbar-header">
              <a href="#" class="navbar-brand nav-desk-brand" id="sidebar-toggle"><i class="fa fa-bars"></i></a>
              <div class="right-nav">
                  <a href="#" class="notification-link"><img src="{{ asset('assets/images/notification.svg') }}"></a>
                  <div class="lang-menu">
      <a href="{{ route('switchLang', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'active' : '' }}"><img src="assets/images/us.svg" alt="English"> EN</a>
      <a href="{{ route('switchLang', 'es') }}" class="{{ app()->getLocale() == 'es' ? 'active' : '' }}"><img style="height: 25px; width: 25px;" class="auto" src="assets/images/mexico.png" alt="Español"> ES</a>
    </div>
                  <a href="#" class="profile-name">
                      <span>{{ Auth::user()->name }}</span>
                      <img src="{{ asset('assets/images/dash-profile.png') }}">
                  </a>
                  <!-- Logout Button -->
                  <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                      @csrf
                      <button type="submit" class="btn btn-secondary" title="Cerrar sesión" style="margin-left: 10px;">
                          Cerrar sesión
                      </button>
                  </form>
              </div>
          </div>
      </div>
  </nav>
</div>
