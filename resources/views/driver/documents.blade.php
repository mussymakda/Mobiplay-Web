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
  <title>@lang('messages.mobiplay') - Document Upload</title>
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
            <h1>Document Verification</h1>
            <p>Upload your required documents to complete your driver verification</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Document Upload Section -->
  <section class="signup-section-form driver-section-form">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="mobiplay-form">
            <h3>Upload Verification Documents</h3>
            <p class="text-center mb-4">Please upload the following documents for verification. All documents must be clear and readable.</p>

            @if ($errors->any())
              <div class="alert alert-danger">
                <ul class="mb-0">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

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

            <form method="POST" action="{{ route('driver.documents.upload') }}" enctype="multipart/form-data">
              @csrf

              <!-- Progress Steps -->
              <div class="row mb-4">
                <div class="col-12">
                  <div class="d-flex justify-content-center">
                    <div class="step-progress">
                      <div class="step completed">
                        <i class="fas fa-check"></i>
                        <span>Registration</span>
                      </div>
                      <div class="step active">
                        <i class="fas fa-upload"></i>
                        <span>Documents</span>
                      </div>
                      <div class="step">
                        <i class="fas fa-clock"></i>
                        <span>Verification</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Document Upload Cards -->
              <div class="row">
                <!-- Uber Screenshot -->
                <div class="col-md-12 mb-4">
                  <div class="document-card">
                    <div class="card">
                      <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-mobile-alt me-2"></i>Uber Driver Screenshot</h5>
                      </div>
                      <div class="card-body">
                        <p class="card-text">Upload a screenshot of your Uber driver profile showing your name and rating.</p>
                        <div class="upload-area" onclick="document.getElementById('uber_screenshot').click()">
                          <i class="fas fa-cloud-upload-alt"></i>
                          <p>Click to upload Uber screenshot</p>
                          <span class="file-info">PNG, JPG, PDF (Max 10MB)</span>
                        </div>
                        <input type="file" id="uber_screenshot" name="uber_screenshot" accept=".jpg,.jpeg,.png,.pdf" style="display: none;" required>
                        <div id="uber_preview" class="file-preview"></div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Identity Document -->
                <div class="col-md-12 mb-4">
                  <div class="document-card">
                    <div class="card">
                      <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-id-card me-2"></i>Identity Document</h5>
                      </div>
                      <div class="card-body">
                        <p class="card-text">Upload a clear photo of your driver's license or government-issued ID.</p>
                        <div class="upload-area" onclick="document.getElementById('identity_document').click()">
                          <i class="fas fa-cloud-upload-alt"></i>
                          <p>Click to upload identity document</p>
                          <span class="file-info">PNG, JPG, PDF (Max 10MB)</span>
                        </div>
                        <input type="file" id="identity_document" name="identity_document" accept=".jpg,.jpeg,.png,.pdf" style="display: none;" required>
                        <div id="identity_preview" class="file-preview"></div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Vehicle Number Plate -->
                <div class="col-md-12 mb-4">
                  <div class="document-card">
                    <div class="card">
                      <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-car me-2"></i>Vehicle License Plate</h5>
                      </div>
                      <div class="card-body">
                        <p class="card-text">Upload a clear photo of your vehicle's license plate.</p>
                        <div class="upload-area" onclick="document.getElementById('vehicle_number_plate').click()">
                          <i class="fas fa-cloud-upload-alt"></i>
                          <p>Click to upload license plate photo</p>
                          <span class="file-info">PNG, JPG (Max 10MB)</span>
                        </div>
                        <input type="file" id="vehicle_number_plate" name="vehicle_number_plate" accept=".jpg,.jpeg,.png" style="display: none;" required>
                        <div id="vehicle_preview" class="file-preview"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Terms and Submit -->
              <div class="row mb-4">
                <div class="col-12">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" id="document_terms" required>
                    <label class="form-check-label" for="document_terms">
                      I confirm that all uploaded documents are authentic and belong to me. I understand that providing false information may result in account termination.
                    </label>
                  </div>
                </div>
              </div>

              <!-- Submit Button -->
              <div class="row">
                <div class="col-lg-6 col-12 mx-auto">
                  <button type="submit" class="btn btn-primary w-100 btn-lg">Submit Documents for Verification</button>
                </div>
              </div>
            </form>

            <!-- Driver Information Summary -->
            <div class="mt-5 p-4 bg-light rounded">
              <h5>Registration Summary</h5>
              <div class="row">
                <div class="col-md-6">
                  <p><strong>Name:</strong> {{ $driver->name }}</p>
                  <p><strong>Email:</strong> {{ $driver->email }}</p>
                  <p><strong>Phone:</strong> {{ $driver->phone }}</p>
                </div>
                <div class="col-md-6">
                  <p><strong>Vehicle:</strong> {{ $driver->car_year }} {{ $driver->car_make }} {{ $driver->car_model }}</p>
                  <p><strong>Location:</strong> {{ $driver->city }}, {{ $driver->state }}</p>
                  <p><strong>Status:</strong> <span class="badge bg-warning">Pending Verification</span></p>
                </div>
              </div>
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

    .upload-area {
      border: 2px dashed #007bff;
      border-radius: 8px;
      padding: 40px 20px;
      text-align: center;
      cursor: pointer;
      transition: all 0.3s ease;
      background: #f8f9fa;
    }

    .upload-area:hover {
      border-color: #0056b3;
      background: #e3f2fd;
    }

    .upload-area i {
      font-size: 48px;
      color: #007bff;
      margin-bottom: 15px;
    }

    .upload-area p {
      margin: 10px 0 5px 0;
      font-weight: 600;
      color: #333;
    }

    .upload-area .file-info {
      color: #6c757d;
      font-size: 0.9em;
    }

    .file-preview {
      margin-top: 15px;
      padding: 10px;
      background: #e8f5e8;
      border-radius: 4px;
      display: none;
    }

    .file-preview.show {
      display: block;
    }

    .document-card {
      margin-bottom: 20px;
    }
  </style>

  <script>
    // File upload preview functionality
    function setupFilePreview(inputId, previewId) {
      document.getElementById(inputId).addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById(previewId);
        
        if (file) {
          preview.innerHTML = `
            <div class="d-flex align-items-center">
              <i class="fas fa-file text-success me-2"></i>
              <span>${file.name}</span>
              <span class="ms-auto text-muted">${(file.size / 1024 / 1024).toFixed(2)} MB</span>
            </div>
          `;
          preview.classList.add('show');
        } else {
          preview.classList.remove('show');
        }
      });
    }

    // Setup file previews for all upload inputs
    setupFilePreview('uber_screenshot', 'uber_preview');
    setupFilePreview('identity_document', 'identity_preview');
    setupFilePreview('vehicle_number_plate', 'vehicle_preview');
  </script>
</body>
</html>