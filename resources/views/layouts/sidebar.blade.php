<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="{{ route('dashboard') }}" class="desk-logo"><img src="{{ asset('assets/images/logo.svg') }}"></a>
      <a href="{{ route('dashboard') }}" class="mobile-logo"><img src="{{ asset('assets/images/logo.svg') }}"></a>
    </div>
    <ul class="sidebar-nav">
      <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a href="{{ route('dashboard') }}"><img src="{{ asset('assets/images/dashboard-icon.svg') }}" alt="Dashboard"> <span>{{ __('messages.dashboard') }}</span></a>
      </li>
      <li class="{{ request()->routeIs('analytics') ? 'active' : '' }}">
        <a href="{{ route('analytics') }}"><img src="{{ asset('assets/images/analytics-icons.svg') }}" alt="Analytics"> <span>{{ __('messages.analytics') }}</span></a>
      </li>
      <li class="{{ request()->routeIs('camplain-list') ? 'active' : '' }}">
        <a href="{{ route('camplain-list') }}"><img src="{{ asset('assets/images/campaign-icon.svg') }}" alt="Campaigns"> <span>{{ __('messages.campaigns') }}</span></a>
      </li>
      <li class="{{ request()->routeIs('profile') ? 'active' : '' }}">
        <a href="{{ route('profile') }}"><img src="{{ asset('assets/images/profile-icon.svg') }}" alt="Profile"> <span>{{ __('messages.profile') }}</span></a>
      </li>
    </ul>
    <a href="#" class="help-link"><img src="{{ asset('assets/images/help.svg') }}"> <span>Centro de Ayuda</span></a>
  </aside>