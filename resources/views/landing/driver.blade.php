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
  <style>
    .signup-section-form.driver-section-form {
      background-image: url('assets/images/form-bg.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      position: relative;
    }
    .signup-section-form.driver-section-form::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.4);
      z-index: 1;
    }
    .signup-section-form.driver-section-form .container {
      position: relative;
      z-index: 2;
    }
    .quick-onboard {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }
    
    /* Toast Notification Styles */
    .toast-container {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 9999;
    }
    
    .toast {
      background: #28a745;
      color: white;
      padding: 15px 20px;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
      margin-bottom: 10px;
      transform: translateX(400px);
      opacity: 0;
      transition: all 0.3s ease;
    }
    
    .toast.show {
      transform: translateX(0);
      opacity: 1;
    }
    
    .toast.error {
      background: #dc3545;
    }
    
    .toast .close-btn {
      background: none;
      border: none;
      color: white;
      font-size: 18px;
      font-weight: bold;
      float: right;
      margin-left: 15px;
      cursor: pointer;
      padding: 0;
      line-height: 1;
    }
  </style>
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
        <div class="col-lg-6">
          <div class="mobiplay-form quick-onboard">
            <h3>@lang('messages.apply_free_device')</h3>
            <p class="text-muted mb-4">Quick setup - we'll get the rest later</p>
            
            <!-- Messages handled by toast notifications -->

            <form method="POST" action="{{ route('driver.register') }}">
              @csrf
              <div class="row justify-content-center">
                <div class="col-md-6">
                  <div class="form-group mb-3">
                    <input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" placeholder="@lang('messages.first_name')" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group mb-3">
                    <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" placeholder="@lang('messages.last_name')" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group mb-3">
                    <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="@lang('messages.email')" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group mb-3">
                    <input type="tel" class="form-control" name="phone" value="{{ old('phone') }}" placeholder="@lang('messages.phone_number')" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group mb-3">
                    <input type="text" class="form-control" name="city" value="{{ old('city') }}" placeholder="@lang('messages.city')" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group mb-3">
                    <select class="form-control" name="vehicle_type" required>
                      <option value="">@lang('messages.vehicle_type')</option>
                      <option value="sedan" {{ old('vehicle_type') == 'sedan' ? 'selected' : '' }}>Sedan</option>
                      <option value="suv" {{ old('vehicle_type') == 'suv' ? 'selected' : '' }}>SUV</option>
                      <option value="hatchback" {{ old('vehicle_type') == 'hatchback' ? 'selected' : '' }}>Hatchback</option>
                      <option value="minivan" {{ old('vehicle_type') == 'minivan' ? 'selected' : '' }}>Minivan</option>
                    </select>
                  </div>
                </div>
                
                <!-- Hidden fields with default values for quick onboarding -->
                <input type="hidden" name="country" value="Mexico">
                <input type="hidden" name="state" value="TBD">
                <input type="hidden" name="postal_code" value="00000">
                <input type="hidden" name="car_make" value="TBD">
                <input type="hidden" name="car_model" value="TBD">
                <input type="hidden" name="car_year" value="2020">
                <input type="hidden" name="vehicle_number" value="TBD">
                <input type="hidden" name="license_number" value="TBD">
                <input type="hidden" name="trips_per_month" value="100">
                <input type="hidden" name="password" value="password123">
                <input type="hidden" name="password_confirmation" value="password123">
                
                <div class="col-12 mt-4">
                  <div class="form-group mb-3">
                    <button type="submit" class="btn btn-primary w-100">@lang('messages.apply_now')</button>
                  </div>
                </div>
              </div>
            </form>
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

  <!-- Toast Container -->
  <div class="toast-container"></div>

  <!-- Bootstrap Bundle with Popper -->
  <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
  <script type="text/javascript">
    // Language menu functionality
    $('.lang-menu a').click(function(){
      $('.lang-menu a.active').removeClass('active');
      $(this).addClass('active');
    });
    
    // Toast notification function
    function showToast(message, type = 'success') {
      const toastContainer = $('.toast-container');
      const toast = $(`
        <div class="toast ${type}">
          <button class="close-btn" onclick="$(this).parent().remove()">&times;</button>
          ${message}
        </div>
      `);
      
      toastContainer.append(toast);
      
      // Show toast
      setTimeout(() => {
        toast.addClass('show');
      }, 100);
      
      // Auto-hide after 5 seconds
      setTimeout(() => {
        toast.removeClass('show');
        setTimeout(() => {
          toast.remove();
        }, 300);
      }, 5000);
    }
    
    // Form submission with loading state and success handling
    $('form[action*="driver.register"]').on('submit', function(e) {
      e.preventDefault();
      
      const form = $(this);
      const submitBtn = form.find('button[type="submit"]');
      const originalText = submitBtn.text();
      const formContainer = $('.quick-onboard');
      
      // Show loading state
      submitBtn.prop('disabled', true).text('Creating Account...');
      
      // Submit form via AJAX
      $.post(form.attr('action'), form.serialize())
        .done(function(response) {
          // Show success without page refresh
          formContainer.html(`
            <div class="text-center">
              <div style="font-size: 4rem; color: #28a745; margin-bottom: 20px;">
                ✓
              </div>
              <h3 style="color: #28a745; margin-bottom: 20px;">Welcome to Mobiplay!</h3>
              <p class="text-muted mb-4">Your driver account has been created successfully.</p>
              <p class="small text-muted">You'll receive further instructions via email or phone.</p>
              <button class="btn btn-primary" onclick="location.reload()">Register Another Driver</button>
            </div>
          `);
          
          // Also show toast for extra confirmation
          showToast('Welcome to Mobiplay! Your account is ready.', 'success');
        })
        .fail(function(xhr) {
          // Handle errors
          const errors = xhr.responseJSON?.errors || {};
          
          // Show error messages
          Object.values(errors).forEach(errorArray => {
            errorArray.forEach(error => {
              showToast(error, 'error');
            });
          });
          
          // Re-enable form
          submitBtn.prop('disabled', false).text(originalText);
        });
    });
  </script>

  <!-- Show Messages with Toast -->
  @if (session('success'))
    <script>showToast('{{ session('success') }}', 'success');</script>
  @endif
  
  @if ($errors->any())
    @foreach ($errors->all() as $error)
      <script>showToast('{{ addslashes($error) }}', 'error');</script>
    @endforeach
  @endif
</body>
</html>
