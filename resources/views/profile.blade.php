<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <!-- {{ __('messages.required_meta_tags') }} -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- {{ __('messages.bootstrap_css') }} -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>{{ __('messages.mobiplay') }}</title>
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@300,301,400,401,500,501,700,701,900,901,1,2&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/stylesheet.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}">
  </head>
  <body class="inner-page">
    <div id="wrapper">

      <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
          <a href="{{ route('dashboard') }}" class="desk-logo"><img src="{{ asset('assets/images/logo.svg') }}" alt="{{ __('messages.mobiplay') }}"></a>
          <a href="{{ route('dashboard') }}" class="mobile-logo"><img src="{{ asset('assets/images/logo.svg') }}" alt="{{ __('messages.mobiplay') }}"></a>
        </div>
        <ul class="sidebar-nav">
          <li>
            <a href="{{ route('dashboard') }}"><img src="{{ asset('assets/images/dashboard-icon.svg') }}" alt="{{ __('messages.dashboard') }}"> <span>{{ __('messages.dashboard') }}</span></a>
          </li>
          <li>
            <a href="{{ route('analytics') }}"><img src="{{ asset('assets/images/analytics-icons.svg') }}" alt="{{ __('messages.analytics') }}"> <span>{{ __('messages.analytics') }}</span></a>
          </li>
          <li>
            <a href="{{ route('camplain-list') }}"><img src="{{ asset('assets/images/campaign-icon.svg') }}" alt="{{ __('messages.campaigns') }}"> <span>{{ __('messages.campaigns') }}</span></a>
          </li>
          <li class="active">
            <a href="{{ route('profile') }}"><img src="{{ asset('assets/images/profile-icon.svg') }}" alt="{{ __('messages.profile') }}"> <span>{{ __('messages.profile') }}</span></a>
          </li>
        </ul>
        <a href="#" class="help-link"><img src="{{ asset('assets/images/help.svg') }}" alt="{{ __('messages.help_center') }}"> <span>{{ __('messages.help_center') }}</span></a>
      </aside>

  <div id="navbar-wrapper">
    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <a href="#" class="navbar-brand" id="sidebar-toggle"><i class="fa fa-bars" aria-label="{{ __('messages.toggle_navigation') }}"></i></a>
          <div class="right-nav">
            <a href="#" class="notification-link"><img src="{{ asset('assets/images/notification.svg') }}" alt="{{ __('messages.notification') }}"></a>
            <div class="lang-menu">
      <a href="{{ route('switchLang', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'active' : '' }}"><img src="{{ asset('assets/images/us.svg') }}" alt="{{ __('messages.english') }}"> {{ __('messages.english') }}</a>
      <a href="{{ route('switchLang', 'es') }}" class="{{ app()->getLocale() == 'es' ? 'active' : '' }}"><img src="{{ asset('assets/images/mexico.png') }}" alt="{{ __('messages.spanish') }}" style="height: 25px; width: 25px;" class="auto"> {{ __('messages.spanish') }}</a>
    </div>
            <a href="#" class="profile-name"><span>{{ Auth::user()->name }}</span> <img src="{{ asset('assets/images/dash-profile.png') }}" alt="{{ __('messages.profile') }}"></a>
          </div>
        </div>
      </div>
    </nav>
  </div>

  <section id="content-wrapper" class="mt-3">
    <div class="container">
      <div class="row justify-content-center g-0">
        <div class="col-lg-10">
          <div class="row align-items-center mb-4">
            <div class="col-xl-12">
              <div class="page-title">
                <h1 class="mb-lg-0">{{ __('messages.profile') }}</h1>
                <div class="profile-drop-link"><i class="fa fa-bars" aria-label="{{ __('messages.toggle_navigation') }}"></i></div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-xl-2 col-lg-3">
              <div class="profile-link">
                <a href="{{ route('profile') }}" class="active">{{ __('messages.account_settings') }}</a>
                <a href="{{ url('/payments') }}">{{ __('messages.payment_history') }}</a>
                <a href="{{ url('/settings') }}">{{ __('messages.settings') }}</a>
              </div>
            </div>

            <div class="col-xl-10 col-lg-9">
              @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  {{ session('success') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              @endif
              
              @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  {{ session('error') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              @endif

              @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <ul class="mb-0">
                    @foreach($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              @endif
              
              <div class="setting-box">
                <div class="mobiplay-profile">
                  <div class="mobiplay-left">
                    <img src="{{ $profile_image_url }}" alt="{{ __('messages.profile') }}" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
                    <div class="profile-name">
                      <h3>{{ $name }}</h3>
                      <form action="{{ route('profile.upload-photo') }}" method="POST" enctype="multipart/form-data" id="profile-image-form">
                        @csrf
                        <label for="profile_image" style="cursor: pointer;">
                          <input type="file" name="profile_image" id="profile_image" accept="image/*" style="display: none;" onchange="document.getElementById('profile-image-form').submit();">
                          {{ __('messages.add_photo') }}
                        </label>
                      </form>
                    </div>
                  </div>
                  <div class="mobiplay-right">
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary"><img src="{{ asset('assets/images/edit-2.svg') }}" alt="{{ __('messages.change') }}"> {{ __('messages.change') }}</a>
                  </div>
                </div>

                <div class="personal-info">
                  <div class="row">
                    <div class="col-lg-12">
                      <h4>{{ __('messages.personal_info') }}</h4>
                    </div>

                    <div class="col-lg-4">
                      <div class="persoanl-desc">
                        <label>{{ __('messages.email') }}</label>
                        <span>{{ $email }}</span>
                      </div>
                    </div>

                    <div class="col-lg-4">
                      <div class="persoanl-desc">
                        <label>{{ __('messages.city') }}</label>
                        <span>{{ $city }}</span>
                      </div>
                    </div>

                    <div class="col-lg-4">
                      <div class="persoanl-desc">
                        <label>{{ __('messages.zip_code') }}</label>
                        <span>{{ $postal_code }}</span>
                      </div>
                    </div>

                    <div class="col-lg-4">
                      <div class="persoanl-desc">
                        <label>{{ __('messages.state_province') }}</label>
                        <span>{{ $state_province }}</span>
                      </div>
                    </div>

                    <div class="col-lg-4">
                      <div class="persoanl-desc">
                        <label>{{ __('messages.country') }}</label>
                        <span>{{ $country }}</span>
                      </div>
                    </div>

                    <div class="col-lg-4">
                      <div class="persoanl-desc">
                        <label>{{ __('messages.address') }}</label>
                        <span>{{ $address_line1 }} {{ $address_line2 }}</span>
                      </div>
                    </div>
                  </div>
                </div>

                <div style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);"></div>

                <div class="organization-desc">
                  <div class="row">
                    <div class="col-lg-12">
                      <h4>{{ __('messages.organization_info') }}</h4>
                    </div>

                    <div class="col-lg-6">
                      <div class="organization-des">
                        <label>{{ __('messages.account_type') }}</label>
                        <span>{{ $account_type }}</span>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="organization-des">
                        <label>{{ __('messages.phone_number') }}</label>
                        <span>{{ $phone_number }}</span>
                      </div>
                    </div>
                  </div>
                </div>
                
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </section>

</div>

<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script type="text/javascript" src="{{ asset('assets/js/script.js') }}"></script>
</body>
</html>
