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
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
  <title>@lang('messages.mobiplay') - Verification Status</title>
  <meta http-equiv="refresh" content="30"> <!-- Auto-refresh every 30 seconds -->
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar nav-header navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand" href="{{ route('landing') }}"><img src="{{ asset('assets/images/logo.png') }}"></a>
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
        </ul>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <section class="hero-banner driver-banner">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-12 text-center">
          <div class="banner-details">
            <h1>Verification Status</h1>
            <p>Track your application progress and verification status</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Verification Status Section -->
  <section class="signup-section-form driver-section-form">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="mobiplay-form">
            <h3>Your Application Status</h3>

            @if (session('success'))
              <div class="alert alert-success">
                {{ session('success') }}
              </div>
            @endif

            @if (session('info'))
              <div class="alert alert-info">
                {{ session('info') }}
              </div>
            @endif

            <!-- Progress Steps -->
            <div class="row mb-5">
              <div class="col-12">
                <div class="d-flex justify-content-center">
                  <div class="step-progress">
                    <div class="step completed">
                      <i class="fas fa-check"></i>
                      <span>Registration</span>
                    </div>
                    <div class="step completed">
                      <i class="fas fa-check"></i>
                      <span>Documents</span>
                    </div>
                    <div class="step {{ $driver->verification_status == 'verified' ? 'completed' : ($driver->verification_status == 'under_review' ? 'active' : '') }}">
                      <i class="fas {{ $driver->verification_status == 'verified' ? 'fa-check' : 'fa-clock' }}"></i>
                      <span>Verification</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Status Card -->
            <div class="row mb-4">
              <div class="col-12">
                @php
                  $statusBadge = $driver->getVerificationStatusBadge();
                @endphp
                
                <div class="status-card text-center p-5">
                  @if($driver->verification_status == 'pending')
                    <i class="fas fa-clock status-icon text-warning"></i>
                    <h4 class="mt-3">Application Received</h4>
                    <p class="lead">Thank you for submitting your application. We are preparing to review your documents.</p>
                    <span class="badge bg-warning text-dark fs-6">{{ $statusBadge['text'] }}</span>
                    
                  @elseif($driver->verification_status == 'under_review')
                    <i class="fas fa-search status-icon text-info"></i>
                    <h4 class="mt-3">Under Review</h4>
                    <p class="lead">Our team is currently reviewing your documents. This process typically takes 1-3 business days.</p>
                    <span class="badge bg-info fs-6">{{ $statusBadge['text'] }}</span>
                    
                  @elseif($driver->verification_status == 'verified')
                    <i class="fas fa-check-circle status-icon text-success"></i>
                    <h4 class="mt-3">Verified Successfully!</h4>
                    <p class="lead">Congratulations! Your account has been verified. You can now start earning with MobiPlay.</p>
                    <span class="badge bg-success fs-6">{{ $statusBadge['text'] }}</span>
                    @if($driver->verified_at)
                      <p class="text-muted mt-2">Verified on {{ $driver->verified_at->format('F j, Y \a\t g:i A') }}</p>
                    @endif
                    
                  @elseif($driver->verification_status == 'rejected')
                    <i class="fas fa-times-circle status-icon text-danger"></i>
                    <h4 class="mt-3">Verification Issue</h4>
                    <p class="lead">There was an issue with your documents. Please review the feedback below and resubmit.</p>
                    <span class="badge bg-danger fs-6">{{ $statusBadge['text'] }}</span>
                    
                    @if($driver->rejection_reason)
                      <div class="alert alert-danger mt-3">
                        <strong>Reason:</strong> {{ $driver->rejection_reason }}
                      </div>
                    @endif
                  @endif

                  <!-- Auto-refresh indicator -->
                  <div class="mt-4">
                    <small class="text-muted">
                      <i class="fas fa-sync-alt"></i> This page refreshes automatically every 30 seconds
                    </small>
                  </div>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="row mb-4">
              <div class="col-12 text-center">
                @if($driver->verification_status == 'rejected')
                  <a href="{{ route('driver.documents.resend', $driver->id) }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-upload me-2"></i>Resubmit Documents
                  </a>
                @elseif($driver->verification_status == 'verified')
                  <a href="{{ route('login') }}" class="btn btn-success btn-lg">
                    <i class="fas fa-sign-in-alt me-2"></i>Login to Your Account
                  </a>
                  <a href="#" class="btn btn-outline-primary btn-lg ms-3">
                    <i class="fas fa-download me-2"></i>Download Driver App
                  </a>
                @endif
              </div>
            </div>

            <!-- Driver Information Summary -->
            <div class="driver-info-card p-4 bg-light rounded">
              <h5><i class="fas fa-user me-2"></i>Application Summary</h5>
              <div class="row">
                <div class="col-md-6">
                  <p><strong>Name:</strong> {{ $driver->name }}</p>
                  <p><strong>Email:</strong> {{ $driver->email }}</p>
                  <p><strong>Phone:</strong> {{ $driver->phone }}</p>
                  <p><strong>License:</strong> {{ $driver->license_number }}</p>
                </div>
                <div class="col-md-6">
                  <p><strong>Vehicle:</strong> {{ $driver->car_year }} {{ $driver->car_make }} {{ $driver->car_model }}</p>
                  <p><strong>Location:</strong> {{ $driver->city }}, {{ $driver->state }}</p>
                  <p><strong>Submitted:</strong> {{ $driver->created_at->format('F j, Y') }}</p>
                  @if($driver->documents_uploaded_at)
                    <p><strong>Documents Uploaded:</strong> {{ $driver->documents_uploaded_at->format('F j, Y \a\t g:i A') }}</p>
                  @endif
                </div>
              </div>

              <!-- Document Status -->
              <div class="mt-4">
                <h6>Document Status:</h6>
                <div class="row">
                  <div class="col-md-4">
                    <div class="document-status">
                      <i class="fas fa-mobile-alt {{ $driver->uber_screenshot ? 'text-success' : 'text-muted' }}"></i>
                      <span>Uber Screenshot</span>
                      @if($driver->uber_screenshot)
                        <i class="fas fa-check text-success ms-2"></i>
                      @endif
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="document-status">
                      <i class="fas fa-id-card {{ $driver->identity_document ? 'text-success' : 'text-muted' }}"></i>
                      <span>ID Document</span>
                      @if($driver->identity_document)
                        <i class="fas fa-check text-success ms-2"></i>
                      @endif
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="document-status">
                      <i class="fas fa-car {{ $driver->vehicle_number_plate ? 'text-success' : 'text-muted' }}"></i>
                      <span>License Plate</span>
                      @if($driver->vehicle_number_plate)
                        <i class="fas fa-check text-success ms-2"></i>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- What's Next Section -->
            <div class="mt-5 p-4 border rounded">
              <h5><i class="fas fa-info-circle me-2"></i>What happens next?</h5>
              
              @if($driver->verification_status == 'pending' || $driver->verification_status == 'under_review')
                <ul class="list-unstyled">
                  <li><i class="fas fa-check text-success me-2"></i>Our verification team reviews your documents</li>
                  <li><i class="fas fa-clock text-warning me-2"></i>Background check and credential verification (1-3 business days)</li>
                  <li><i class="fas fa-envelope text-info me-2"></i>You'll receive an email notification with the results</li>
                  <li><i class="fas fa-mobile-alt text-primary me-2"></i>Once approved, you can download the driver app and start earning</li>
                </ul>
                
                <div class="alert alert-info mt-3">
                  <strong>Need help?</strong> Contact our support team at <a href="mailto:support@mobiplay.com">support@mobiplay.com</a> or call <a href="tel:+1234567890">+1 (234) 567-890</a>
                </div>
                
              @elseif($driver->verification_status == 'verified')
                <ul class="list-unstyled">
                  <li><i class="fas fa-check text-success me-2"></i>Your account is fully activated</li>
                  <li><i class="fas fa-download text-primary me-2"></i>Download the MobiPlay Driver app</li>
                  <li><i class="fas fa-car text-info me-2"></i>Install the device in your vehicle</li>
                  <li><i class="fas fa-dollar-sign text-success me-2"></i>Start earning money while you drive</li>
                </ul>
                
              @elseif($driver->verification_status == 'rejected')
                <ul class="list-unstyled">
                  <li><i class="fas fa-upload text-primary me-2"></i>Review the rejection reason above</li>
                  <li><i class="fas fa-camera text-info me-2"></i>Take new, clearer photos of your documents</li>
                  <li><i class="fas fa-paper-plane text-success me-2"></i>Resubmit your documents using the button above</li>
                  <li><i class="fas fa-clock text-warning me-2"></i>Wait for re-verification (1-2 business days)</li>
                </ul>
              @endif
            </div>
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
            <img src="{{ asset('assets/images/logo.png') }}" class="img-fluid" alt="Mobiplay Logo">
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
  
  <style>
    .step-progress {
      display: flex;
      align-items: center;
      margin: 20px 0;
    }

    .step {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin: 0 30px;
      position: relative;
    }

    .step i {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #e9ecef;
      color: #6c757d;
      margin-bottom: 10px;
      font-size: 18px;
    }

    .step.completed i {
      background: #28a745;
      color: white;
    }

    .step.active i {
      background: #007bff;
      color: white;
    }

    .status-card {
      border: 2px solid #e9ecef;
      border-radius: 15px;
      background: white;
    }

    .status-icon {
      font-size: 4rem;
    }

    .driver-info-card {
      border: 1px solid #dee2e6;
    }

    .document-status {
      display: flex;
      align-items: center;
      margin-bottom: 10px;
    }

    .document-status i:first-child {
      width: 20px;
      margin-right: 8px;
    }
  </style>

  <script>
    // Auto-refresh status check (optional AJAX implementation)
    function checkStatus() {
      fetch(`/driver/verification-status/{{ $driver->id }}/check`)
        .then(response => response.json())
        .then(data => {
          // Update status if changed (optional enhancement)
          console.log('Status check:', data);
        })
        .catch(error => {
          console.log('Status check failed:', error);
        });
    }

    // Check status every 30 seconds
    setInterval(checkStatus, 30000);
  </script>
</body>
</html>