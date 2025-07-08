<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Information</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@300,400,500,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/stylesheet.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
</head>

<body>
    <section class="auth-section">
        <div class="container-fluid p-0 h-100">
            <div class="row g-0 align-items-center h-100">
                <div class="col-lg-6 logo-auth-desk h-100">
                    <div class="logo-auth h-100">
                        <img src="{{ asset('assets/images/logo.svg') }}" class="img-fluid" alt="Logo">
                    </div>
                </div>
                <div class="col-lg-6 p-relative h-100 align-content-center">
                    <div class="auth-form auth-with-height">
                        <h2>Account Type</h2>
                        <form method="POST" action="{{ route('signup.account.handle') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="account_type" value="advertiser" {{ old('account_type') == 'advertiser' ? 'checked' : '' }} required>
                                    Advertiser
                                    <span>Create campaigns for your organization</span>
                                </label>
                            </div>
                            <div class="mb-3">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="account_type" value="agency" {{ old('account_type') == 'agency' ? 'checked' : '' }} required>
                                    Agency
                                    <span>Create campaigns on behalf of your clients</span>
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Submit</button>
                        </form>
                    </div>
                    <div class="auth-footer">
                        <p>By proceeding, you agree to our <a href="#" data-bs-toggle="modal" data-bs-target="#privacyModal">Privacy Policy</a> and <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Privacy Policy -->
    <div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="privacyModalLabel">Privacy Policy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Privacy Policy Content -->
                    <p>Privacy policy content goes here.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Terms and Conditions -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Terms and Conditions Content -->
                    <p>Terms and conditions content goes here.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>