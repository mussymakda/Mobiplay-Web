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
  <title>Mobiplay</title>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar nav-header navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand" href="#"><img src="assets/images/logo.png" alt="Mobiplay"></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="@lang('messages.toggle_navigation')">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="#how-it-work">@lang('messages.how_it_works')</a>
          </li>
          <li class="nav-item">
            <a class="nav-link nav-btn active" href="#">@lang('messages.advertisers')</a>
          </li>
          <li class="nav-item">
            <a class="nav-link nav-btn" href="{{ route('driver') }}">@lang('messages.drivers')</a>
          </li>
          <li class="nav-item pe-0">
            <a class="nav-link nav-btn login-btn" href="{{ route('login') }}">@lang('messages.login')</a>
          </li>
        </ul>
      </div>
      <div class="lang-menu">
        <a href="{{ route('switchLang', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'active' : '' }}" data-lang="en"><img src="assets/images/us.svg" alt="English"> EN</a>
        <a href="{{ route('switchLang', 'es') }}" class="{{ app()->getLocale() == 'es' ? 'active' : '' }}" data-lang="es"><img style="height: 25px; width: 25px;" class="auto" src="assets/images/mexico.png" alt="Español"> ES</a>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <section class="hero-banner">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6">
          <div class="banner-details">
            <h1>@lang('messages.rideshare_advertising')</h1>
            <p>@lang('messages.create_account')</p>
            <p>@lang('messages.pause_campaign')</p>
            <a href="#how-it-work" class="btn btn-primary">@lang('messages.how_it_works')</a>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="mobiplay-form">
            <h3>@lang('messages.create_advertiser_account')</h3>
            <form action="{{ route('signup.initial') }}" method="POST" id="landingSignupForm">
              @csrf
              <div class="row">
                <div class="col-6">
                  <div class="form-group mb-3">
                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" placeholder="@lang('messages.first_name')" required>
                    <div class="error-message text-danger small d-none"></div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group mb-3">
                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" placeholder="@lang('messages.last_name')" required>
                    <div class="error-message text-danger small d-none"></div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group mb-3">
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="@lang('messages.email')" required>
                    <div class="error-message text-danger small d-none"></div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="@lang('messages.create_password')" required minlength="8">
                    <div class="error-message text-danger small d-none"></div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group mb-3">
                    <input type="password" name="password_confirmation" class="form-control" placeholder="@lang('messages.confirm_password')" required minlength="8">
                    <div class="error-message text-danger small d-none"></div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group mb-3 mt-4">
                    <button type="submit" class="btn btn-primary w-100">@lang('messages.sign_up')</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section id="features" class="features">
    <div class="container">
      <div class="row align-items-center justify-content-between">
        <div class="col-lg-8">
          <div class="features-details">
            <h2>@lang('messages.power_campaign')</h2>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="features-details">
            <div class="list-features">
              <div class="features-box">
                <div class="features-img">
                  <img src="assets/images/1.svg" alt="Feature Image">
                </div>
                <div class="features-desc">
                  <h3>@lang('messages.geotargeting')</h3>
                  <p>@lang('messages.geotargeting_desc')</p>
                </div>
              </div>
              <div class="features-box">
                <div class="features-img">
                  <img src="assets/images/2.svg" alt="Feature Image">
                </div>
                <div class="features-desc">
                  <h3>@lang('messages.captive_environment')</h3>
                  <p>@lang('messages.captive_environment_desc')</p>
                </div>
              </div>
              <div class="features-box">
                <div class="features-img">
                  <img src="assets/images/3.svg" alt="Feature Image">
                </div>
                <div class="features-desc">
                  <h3>@lang('messages.realtime_data')</h3>
                  <p>@lang('messages.realtime_data_desc')</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="feat-img">
            <img src="assets/images/how-img.png" class="img-fluid" alt="Feature Image">
          </div>
        </div>
        <div class="col-lg-12">
          <div class="feature-btn">
            <a href="#" class="btn btn-primary">@lang('messages.get_started')</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Rideshare Section -->
  <section class="ridershare">
    <div class="container">
      <div class="row justify-content-between">
        <div class="col-lg-4">
          <div class="rider-img">
            <img src="assets/images/rider.png" class="img-fluid" alt="Rider Image">
          </div>
        </div>
        <div class="col-lg-7">
          <div class="ride-details">
            <h2>@lang('messages.rider_earn_extra_cash')</h2>
            <p>@lang('messages.register_as_driver')</p>
            <a href="#" class="btn btn-secondary">@lang('messages.register_as_driver')</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- How It Works Section -->
  <section class="how-it-work-section" id="how-it-work">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-12 mb-5 pb-3">
          <h2 class="text-center">@lang('messages.how_it_works_title')</h2>
          <p class="hw-ing text-center">@lang('messages.maintain_min_deposit')</p>
        </div>
      </div>
      <div class="row align-items-center">
        <div class="col-lg-6">
          <div class="how-img">
            <img src="assets/images/s4.jpg" class="img-fluid" alt="How it Works Step 1">
          </div>
        </div>
        <div class="col-lg-6">
          <div class="how-desc">
            <h5>@lang('messages.step_1')</h5>
            <h3>@lang('messages.upload_content')</h3>
          </div>
        </div>
      </div>
      <div class="row align-items-center">
        <div class="col-lg-6 order-lg-2">
          <div class="how-img">
            <img src="assets/images/s1.jpg" class="img-fluid" alt="How it Works Step 2">
          </div>
        </div>
        <div class="col-lg-6 order-lg-1">
          <div class="how-desc">
            <h5>@lang('messages.step_2')</h5>
            <h3>@lang('messages.create_map')</h3>
          </div>
        </div>
      </div>
      <div class="row align-items-center">
        <div class="col-lg-6">
          <div class="how-img">
            <img src="assets/images/s2.jpg" class="img-fluid" alt="How it Works Step 3">
          </div>
        </div>
        <div class="col-lg-6">
          <div class="how-desc">
            <h5>@lang('messages.step_3')</h5>
            <h3>@lang('messages.optimize_reach')</h3>
          </div>
        </div>
      </div>
      <div class="row align-items-center">
        <div class="col-lg-6 order-lg-2">
          <div class="how-img">
            <img src="assets/images/s3.jpg" class="img-fluid" alt="How it Works Step 4">
          </div>
        </div>
        <div class="col-lg-6 order-lg-1">
          <div class="how-desc">
            <h5>@lang('messages.step_4')</h5>
            <h3>@lang('messages.view_data')</h3>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="signup-section-form">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-5">
        <div class="mobiplay-form">
            <h3>@lang('messages.create_advertiser_account')</h3>
            <form action="{{ route('signup.initial') }}" method="POST" id="landingSignupFormBottom">
              @csrf
              <div class="row">
                <div class="col-6">
                  <div class="form-group mb-3">
                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" placeholder="@lang('messages.first_name')" required>
                    <div class="error-message text-danger small d-none"></div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group mb-3">
                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" placeholder="@lang('messages.last_name')" required>
                    <div class="error-message text-danger small d-none"></div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group mb-3">
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="@lang('messages.email')" required>
                    <div class="error-message text-danger small d-none"></div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="@lang('messages.create_password')" required minlength="8">
                    <div class="error-message text-danger small d-none"></div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group mb-3">
                    <input type="password" name="password_confirmation" class="form-control" placeholder="@lang('messages.confirm_password')" required minlength="8">
                    <div class="error-message text-danger small d-none"></div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group mb-3 mt-4">
                    <button type="submit" class="btn btn-primary w-100">@lang('messages.sign_up')</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="map-mobiplay">
    <div class="row g-0">
      <div class="col-12">
        <div class="map-img">
          <img src="assets/images/map.png" style="width: 100%; height: 400px; object-fit: cover;">
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
    // Set up CSRF token for all AJAX requests
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $('.lang-menu a').click(function(){
      $('.lang-menu a.active').removeClass('active');
      $(this).addClass('active');
    });

    // Handle form submission with AJAX
    $('#landingSignupForm, #landingSignupFormBottom').on('submit', function(e) {
      e.preventDefault();
      
      const form = $(this);
      const submitBtn = form.find('button[type="submit"]');
      const originalText = submitBtn.text();
      
      // Show loading state
      submitBtn.prop('disabled', true).text('Creating account...');
      
      // Clear previous error messages
      form.find('.error-message').addClass('d-none').text('');
      form.find('.alert-danger').remove();

      // Get form data
      const formData = new FormData(form[0]);
      
      // Send AJAX request
      $.ajax({
        url: form.attr('action'),
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          if (response.redirect) {
            window.location.href = response.redirect;
          }
        },
        error: function(xhr) {
          // Reset button state
          submitBtn.prop('disabled', false).text(originalText);

          if (xhr.status === 422) {
            const response = xhr.responseJSON;
            console.log('Validation errors:', response.errors);
            if (response.errors) {
              // Display validation errors under each field
              $.each(response.errors, function(field, messages) {
                const input = form.find('[name="' + field + '"]');
                const errorDiv = input.siblings('.error-message');
                if (errorDiv.length) {
                  errorDiv.removeClass('d-none').text(messages[0]);
                }
              });

              // Also show all errors in an alert
              let errorList = '<ul class="mb-0">';
              $.each(response.errors, function(field, messages) {
                errorList += '<li>' + messages[0] + '</li>';
              });
              errorList += '</ul>';
              
              form.prepend(
                '<div class="alert alert-danger">' + errorList + '</div>'
              );
            }
          } else {
            // Display generic error
            form.prepend(
              '<div class="alert alert-danger">' +
              'An error occurred. Please try again.' +
              '</div>'
            );
          }
        }
      });
    });
  </script>
    $('.lang-menu a').click(function(){
      $('.lang-menu a.active').removeClass('active');
      $(this).addClass('active');
    });
  </script>
</body>
</html>
