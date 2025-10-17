<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link href="https://api.fontshare.com/v2/css?f[]=satoshi@300,301,400,401,500,501,700,701,900,901,1,2&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/loading-button.css') }}">
  <title>Mobiplay</title>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar nav-header navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand" href="#"><img src="{{ asset('assets/images/logo.png') }}" alt="Mobiplay"></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="@lang('messages.toggle_navigation')">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="#how-it-work">@lang('messages.how_it_works')</a>
          </li>
          <li class="nav-item">
            <a class="nav-link nav-btn {{ request()->is('/') ? 'active' : '' }}" href="{{ route('landing') }}">@lang('messages.advertisers')</a>
          </li>
          <li class="nav-item">
            <a class="nav-link nav-btn {{ request()->is('driver') ? 'active' : '' }}" href="{{ route('driver') }}">@lang('messages.drivers')</a>
          </li>
          <li class="nav-item pe-0">
            <a class="nav-link nav-btn login-btn" href="{{ route('login') }}">@lang('messages.login')</a>
          </li>
        </ul>
      </div>
      <div class="lang-menu">
        <a href="{{ route('switchLang', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'active' : '' }}" data-lang="en"><img src="{{ asset('assets/images/us.svg') }}" alt="English"> EN</a>
        <a href="{{ route('switchLang', 'es') }}" class="{{ app()->getLocale() == 'es' ? 'active' : '' }}" data-lang="es"><img style="height: 25px; width: 25px;" class="auto" src="{{ asset('assets/images/mexico.png') }}" alt="Español"> ES</a>
      </div>
    </div>
  </nav>

  @yield('content')

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('assets/js/main.js') }}"></script>
  @yield('scripts')
</body>
</html>