<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Mobiplay</title>
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@300,301,400,401,500,501,700,701,900,901,1,2&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="assets/css/stylesheet.css">
    <link rel="stylesheet" type="text/css" href="assets/css/responsive.css">

    <style>
        /* Hide all steps by default */
        .auth-form {
            display: none;
        }

        /* Show only the active step */
        .auth-form.active {
            display: block;
        }
    </style>
</head>

<body>
    <section class="auth-section">
        <div class="container-fluid p-0 h-100">
            <div class="row g-0 align-items-center h-100">
                <div class="col-lg-6 logo-auth-desk h-100">
                    <div class="logo-auth h-100">
                        <img src="assets/images/logo.svg" class="img-fluid">
                    </div>
                </div>
                <div class="col-lg-6 p-relative h-100 align-content-center">

                    <!-- Step 1: Sign Up -->
                    <div class="auth-form auth-first-step active">
                        <h2>Sign up</h2>
                        <form id="signup-form">
                            <div class="form-group mb-3">
                                <label>First Name</label>
                                <input type="text" class="form-control" name="first_name" required>
                                <span class="error-message text-danger" style="display: none;"></span>
                            </div>
                            <div class="form-group mb-3">
                                <label>Last Name</label>
                                <input type="text" class="form-control" name="last_name" required>
                                <span class="error-message text-danger" style="display: none;"></span>
                            </div>
                            <div class="form-group mb-3">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" required>
                                <span class="error-message text-danger" style="display: none;"></span>
                            </div>
                            <div class="form-group mb-4">
                                <label>Create Password</label>
                                <input type="password" class="form-control" name="password" required>
                                <span class="error-message text-danger" style="display: none;"></span>
                            </div>
                            <div class="form-group mb-4">
                                <label>Confirm Password</label>
                                <input type="password" class="form-control" name="password_confirmation" required>
                                <span class="error-message text-danger" style="display: none;"></span>
                            </div>
                            <div class="form-group mb-4">
                                <button type="button" class="btn btn-primary auth-btn w-100" id="next-step">Sign
                                    Up</button>
                            </div>
                            <div class="form-group">
                                <p>Do you have an account? <a href="{{ route('login') }}">Sign In</a></p>
                            </div>
                        </form>
                    </div>

                    <div class="auth-form auth-with-height auth-second-step">
                        <h2 class="mb-2">We just sent you an SMS</h2>
                        <p class="sub-auth">Please enter the 4-digit code sent to <span id="email-placeholder">your
                                email</span></p>
                        <div class="form-group mb-3 mt-5">
                            <div class="otp">
                                <input type="text" class="form-control" maxlength="1">
                                <input type="text" class="form-control" maxlength="1">
                                <input type="text" class="form-control" maxlength="1">
                                <input type="text" class="form-control" maxlength="1">
                            </div>
                        </div>
                        <div class="new-code mb-4">
                            <a href="#"><img src="assets/images/reload.png"> Get new code</a>
                        </div>
                        <div class="form-group d-flex justify-content-between align-items-center mb-4">
                            <a href="#" class="back-btn"><img src="assets/images/back.png"></a>
                            <button class="btn btn-primary auth-btn1">Next</button>
                        </div>
                        <span class="error-message text-danger" style="display: none;"></span>
                    </div>

                    <!-- Step 3: Select Account Type -->
                    <div class="auth-form auth-third-step">
                        <h2>Account Type</h2>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group form-radio-grp mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="accountType" id="advertiser"
                                            value="advertiser" checked>
                                        <label class="form-check-label" for="advertiser">
                                            Advertiser
                                            <span>Create campaigns for your organization</span>
                                        </label>
                                    </div>
                                    <div class="form-check border-0">
                                        <input class="form-check-input" type="radio" name="accountType" id="agency"
                                            value="agency">
                                        <label class="form-check-label" for="agency">
                                            Agency
                                            <span>Create campaigns on behalf of your clients</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group d-flex justify-content-between align-items-center mb-4">
                                    <button class="otp-back-btn me-2"><img src="assets/images/back-c.svg"></button>
                                    <a href="{{ route('login') }}" class="btn btn-primary w-100">Get Started</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="auth-footer">
                        <p>By proceeding, you agree to the Terms and Conditions and Privacy Policy</p>
                        <div class="auth-link">
                            <a href="#">Help</a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#privacyModal">Privacy</a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function () {
            // Handle "Sign Up" button click
            $('#next-step').click(function () {
                // Clear previous error messages
                $('.error-message').hide().text('');

                // Gather form data
                const formData = {
                    first_name: $('#signup-form input[name="first_name"]').val(),
                    last_name: $('#signup-form input[name="last_name"]').val(),
                    email: $('#signup-form input[name="email"]').val(),
                    password: $('#signup-form input[name="password"]').val(),
                    password_confirmation: $('#signup-form input[name="password_confirmation"]').val(),
                    account_type: $('input[name="accountType"]:checked').val(), // Ensure this captures the selected account type
                };
                console.log('Form Data:', formData); // Check the values being sent

                // Store first name and last name in session
                sessionStorage.setItem('signup_first_name', formData.first_name);
                sessionStorage.setItem('signup_last_name', formData.last_name);

                // Send signup request
                $.ajax({
                    url: '/api/signup',
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                        console.log('Signup successful:', response);
                        $('#email-placeholder').text(formData.email);
                        $('.auth-first-step').removeClass('active');
                        $('.auth-second-step').addClass('active');
                    },
                    error: function (xhr) {
                        console.error('Error:', xhr);
                        const errors = xhr.responseJSON.errors; // Assuming validation errors are returned in this format

                        // Display error messages under the relevant fields
                        if (errors) {
                            if (errors.first_name) {
                                $('input[name="first_name"]').next('.error-message').text(errors.first_name[0]).show();
                            }
                            if (errors.last_name) {
                                $('input[name="last_name"]').next('.error-message').text(errors.last_name[0]).show();
                            }
                            if (errors.email) {
                                $('input[name="email"]').next('.error-message').text(errors.email[0]).show();
                            }
                            if (errors.password) {
                                $('input[name="password"]').next('.error-message').text(errors.password[0]).show();
                            }
                        }
                    }
                });
            });

            // Handle OTP input focus
            $('.otp input').on('input', function () {
                const currentInput = $(this);
                if (this.value.length === 1) {
                    currentInput.next('input').focus();
                }
                if (this.value.length === 0) {
                    currentInput.prev('input').focus();
                }
            });

            // Handle "Next" button click for OTP verification
            $('.auth-btn1').click(function () {
                // Clear previous error messages
                $('.error-message').hide().text('');

                // Gather OTP from input fields
                const otpCode = $('.otp input').map(function () {
                    return $(this).val();
                }).get().join('');

                console.log('OTP Code:', otpCode); // Log the OTP code

                // Validate OTP Code before sending
                if (otpCode.length < 4) {
                    $('.error-message').text('Please enter a complete OTP.').show();
                    return;
                }

                // Send OTP verification request
                $.ajax({
                    url: '/api/verify-otp',
                    type: 'POST',
                    data: { otp: otpCode },
                    success: function (response) {
                        console.log('OTP verification successful:', response);
                        $('.auth-second-step').removeClass('active');
                        $('.auth-third-step').addClass('active');
                    },
                    error: function (xhr) {
                        console.error('Error:', xhr);
                        const message = xhr.responseJSON.message; // Adjusted to read the message correctly

                        // Display error message
                        if (message) {
                            $('.error-message').text(message).show();
                        }
                    }
                });
            });


            // Handle back button clicks
            $('.back-btn').click(function () {
                if ($('.auth-second-step').hasClass('active')) {
                    $('.auth-second-step').removeClass('active');
                    $('.auth-first-step').addClass('active');
                } else if ($('.auth-third-step').hasClass('active')) {
                    $('.auth-third-step').removeClass('active');
                    $('.auth-second-step').addClass('active');
                }
            });
        });
    </script>
</body>

</html>