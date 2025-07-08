<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="{{ route('dashboard') }}" class="desk-logo"><img src="{{ asset('assets/images/logo.svg') }}"></a>
      <a href="{{ route('dashboard') }}" class="mobile-logo"><img src="{{ asset('assets/images/logo.svg') }}"></a>
    </div>
    <ul class="sidebar-nav">
      <li class="active">
        <a href="{{ route('dashboard') }}"><img src="{{ asset('assets/images/dashboard-icon.svg') }}"> <span>Tablero</span></a>
      </li>
      <li>
        <a href="{{ route('analytics') }}"><img src="{{ asset('assets/images/analytics-icons.svg') }}"> <span>Analítica</span></a>
      </li>
      <li>
        <a href="{{ route('camplain-list') }}"><img src="{{ asset('assets/images/campaign-icon.svg') }}"> <span>Campañas</span></a>
      </li>
      <li>
        <a href="{{ route('profile') }}"><img src="{{ asset('assets/images/profile-icon.svg') }}"> <span>Perfil</span></a>
      </li>
    </ul>
    <a href="#" class="help-link"><img src="{{ asset('assets/images/help.svg') }}"> <span>Centro de Ayuda</span></a>
  </aside>