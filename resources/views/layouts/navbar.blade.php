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
                    <a href="#" class="profile-name">
                        <span>{{ Auth::user()->name }}</span>
                        <img src="{{ Auth::user()->profile_image_url ?? asset('assets/images/default-avatar.png') }}" 
                             style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-secondary logout-btn" style="margin-left: 10px;">
                            {{ __('messages.logout') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle sidebar
    $('#sidebar-toggle').click(function(e) {
        e.preventDefault();
        $('#wrapper').toggleClass('toggled');
    });
});
</script>
@endpush

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <div class="navbar-nav ms-auto align-items-center">
                    <a href="#" class="nav-item notification-link">
                        <img src="{{ asset('assets/images/notification.svg') }}" alt="Notifications">
                    </a>
                    
                    <div class="nav-item lang-menu">
                        <a href="{{ route('switchLang', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'active' : '' }}">
                            <img src="{{ asset('assets/images/us.svg') }}" alt="English"> EN
                        </a>
                        <a href="{{ route('switchLang', 'es') }}" class="{{ app()->getLocale() == 'es' ? 'active' : '' }}">
                            <img src="{{ asset('assets/images/mexico.png') }}" class="auto" style="height: 25px; width: 25px;" alt="Español"> ES
                        </a>

                    </div>
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle profile-name" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                            <span>{{ Auth::user()->name }}</span>
                            @if(Auth::user()->profile_image_url)
                                <img src="{{ Auth::user()->profile_image_url }}" alt="Profile" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                            @else
                                <img src="{{ asset('assets/images/default-avatar.png') }}" alt="Profile" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile') }}">
                                    <i class="fas fa-user me-2"></i> @lang('messages.profile')
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <button type="button" class="dropdown-item logout-btn text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i> @lang('messages.logout')
                                </button>
                            </li>
                        </ul>
                    </div>

        </li>                      Logout

        <li class="nav-item pe-0">                  </button>

          <a class="nav-link nav-btn login-btn" href="{{ route('login') }}">@lang('messages.login')</a>              </div>

                </div>
            </div>
        </div>
    </nav>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Handle logout button click
    $('.logout-btn').click(function() {
        const btn = $(this);
        const originalHtml = btn.html();

        // Show loading state
        btn.prop('disabled', true)
           .html('<i class="fas fa-spinner fa-spin me-2"></i>@lang("messages.logging_out")');

        // Make logout request
        $.ajax({
            url: '{{ route("logout") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                window.location.href = response.redirect;
            },
            error: function() {
                // Reset button state
                btn.prop('disabled', false).html(originalHtml);
                alert('@lang("messages.logout_error")');
            }
        });
    });

    // Toggle sidebar
    $('#sidebar-toggle').click(function(e) {
        e.preventDefault();
        $('#wrapper').toggleClass('toggled');
    });
});
</script>
@endpush