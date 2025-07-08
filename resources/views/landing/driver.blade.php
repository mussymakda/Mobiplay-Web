<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <!-- Required meta tags -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link href="https://api.fontshare.com/v2/css?f[]=satoshi@300,301,400,401,500,501,700,701,900,901,1,2&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="assets/css/style.css">
  <title>@lang('messages.mobiplay')</title>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar nav-header navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand" href="#"><img src="assets/images/logo.png"></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="@lang('messages.toggle_navigation')">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link nav-btn" href="{{ route('landing') }}">@lang('messages.advertisers')</a>
          </li>
          <li class="nav-item">
            <a class="nav-link nav-btn active" href="{{ route('driver') }}">@lang('messages.driver')</a>
          </li>
          <li class="nav-item pe-0">
            <a class="nav-link nav-btn login-btn" href="{{ route('login') }}">@lang('messages.login')</a>
          </li>
        </ul>
      </div>
      <div class="lang-menu">
        <a href="{{ route('switchLang', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'active' : '' }}">
          <img src="assets/images/us.svg"> @lang('messages.en')</a>
        <a href="{{ route('switchLang', 'es') }}" class="{{ app()->getLocale() == 'es' ? 'active' : '' }}">
          <img style="height: 25px; width: 25px;" class="auto" src="assets/images/mexico.png"> @lang('messages.es')</a>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <section class="hero-banner driver-banner">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-7">
          <div class="banner-details">
            <h1>@lang('messages.upgrade_rideshare')</h1>
            <p>@lang('messages.earn_cash_tips_ratings')</p>
            <a href="#mobiplayrider" class="btn btn-primary">@lang('messages.get_free_device')</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Sign Up Section -->
  <section id="mobiplayrider" class="signup-section-form driver-section-form">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="mobiplay-form">
            <h3>@lang('messages.apply_free_device')</h3>
            <div class="row justify-content-center">
              <div class="col-6">
                <div class="form-group mb-3">
                  <input type="text" class="form-control" placeholder="@lang('messages.first_name')">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group mb-3">
                  <input type="text" class="form-control" placeholder="@lang('messages.last_name')">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group mb-3">
                  <input type="text" class="form-control" placeholder="@lang('messages.country')">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group mb-3">
                  <input type="text" class="form-control" placeholder="@lang('messages.state')">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group mb-3">
                  <input type="text" class="form-control" placeholder="@lang('messages.city')">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group mb-3">
                  <input type="text" class="form-control" placeholder="@lang('messages.postal_code')">
                </div>
              </div>
              <div class="col-12">
                <div class="form-group mb-3">
                  <input type="text" class="form-control" placeholder="@lang('messages.trips_per_month')">
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group mb-3">
                  <input type="text" class="form-control" placeholder="@lang('messages.car_make')">
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group mb-3">
                  <input type="text" class="form-control" placeholder="@lang('messages.car_model')">
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group mb-3">
                  <input type="text" class="form-control" placeholder="@lang('messages.car_year')">
                </div>
              </div>
              <div class="col-lg-6 col-12">
                <div class="form-group mb-3">
                  <input type="text" class="form-control" placeholder="@lang('messages.email')">
                </div>
              </div>
              <div class="col-lg-6 col-12">
                <div class="form-group mb-3">
                  <input type="text" class="form-control" placeholder="@lang('messages.phone_number')">
                </div>
              </div>
              <div class="col-lg-6 col-12">
                <div class="form-group mb-3">
                  <input type="text" class="form-control" placeholder="@lang('messages.create_password')">
                </div>
              </div>
              <div class="col-lg-6 col-12">
                <div class="form-group mb-3">
                  <input type="text" class="form-control" placeholder="@lang('messages.confirm_password')">
                </div>
              </div>
              <div class="col-lg-4 col-12 mt-4">
                <div class="form-group mb-3">
                  <a href="#" class="btn btn-primary w-100">@lang('messages.apply_now')</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- What You Get Section -->
  <section class="ridershare whatget">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12">
        <div class="driver-title">
          <h2>@lang('messages.what_you_get')</h2>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="rider-img">
          <!-- Ensure the image is responsive -->
          <img src="assets/images/mobidriver.png" class="img-fluid w-100" alt="Mobiplay Device">
        </div>
      </div>
      <div class="col-lg-6">
        <div class="ride-details">
          <p>@lang('messages.free_media_device')</p>
          <p>@lang('messages.free_mount_and_charging')</p>
          <p>@lang('messages.free_lte_data')</p>
        </div>
      </div>
    </div>
  </div>
</section>


  <!-- Footer -->
  <footer>
    <div class="container">
      <div class="row">
        <div class="col-lg-3">
          <div class="footer-logo">
            <img src="assets/images/logo.png" class="img-fluid" alt="Mobiplay Logo">
          </div>
        </div>
        <div class="col-lg-9">
          <div class="row justify-content-end">
            <div class="col-lg-auto">
              <div class="footer-box">
                <a href="mailto:info@mobiplaymx.onmicrosoft.com">@lang('messages.questions_contact')</a>
                <a href="tel:+9876543210">@lang('messages.contact_number')</a>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="footer-box">
                <a href="#">@lang('messages.advertisers')</a>
                <a href="#">@lang('messages.drivers')</a>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="footer-box">
                <a href="#">@lang('messages.terms_conditions')</a>
                <a href="#">@lang('messages.privacy_policy')</a>
              </div>
            </div>
          </div>
        </div>
        <div class="text-center col-12">
          <p class="text-white p-3 pb-0">@lang('messages.copyright')</p>
        </div>
      </div>
    </div>
  </footer>

  <!-- Bootstrap Bundle with Popper -->
  <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
  <script type="text/javascript">
    $('.lang-menu a').click(function(){
      $('.lang-menu a.active').removeClass('active');
      $(this).addClass('active');
    });
  </script>
</body>
</html>
