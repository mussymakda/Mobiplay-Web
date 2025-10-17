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
                    <div class="auth-form auth-first-step {{ $fromLandingPage ?? false ? '' : 'active' }}">
                        <h2>Sign up</h2>
                        <form id="signup-form">
                            @csrf
                            <div class="form-group mb-3">
                                <label>First Name</label>
                                <input type="text" class="form-control" name="first_name" value="{{ session('signup_data.first_name', old('first_name')) }}" required>
                                <span class="error-message text-danger" style="display: none;"></span>
                            </div>
                            <div class="form-group mb-3">
                                <label>Last Name</label>
                                <input type="text" class="form-control" name="last_name" value="{{ session('signup_data.last_name', old('last_name')) }}" required>
                                <span class="error-message text-danger" style="display: none;"></span>
                            </div>
                            <div class="form-group mb-3">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" value="{{ session('signup_data.email', old('email')) }}" required>
                                <span class="error-message text-danger" style="display: none;"></span>
                            </div>
                            <div class="form-group mb-4">
                                <label>Create Password</label>
                                <input type="password" class="form-control" name="password" value="{{ session('signup_data.password', old('password')) }}" required>
                                <span class="error-message text-danger" style="display: none;"></span>
                            </div>
                            <div class="form-group mb-4">
                                <label>Confirm Password</label>
                                <input type="password" class="form-control" name="password_confirmation" value="{{ session('signup_data.password', old('password_confirmation')) }}" required>
                                <span class="error-message text-danger" style="display: none;"></span>
                            </div>
                            <div class="form-group mb-4">
                                <button type="button" class="btn btn-primary auth-btn w-100" id="next-step">
                                    <span class="normal-text">Sign Up</span>
                                    <span class="loading-text d-none">
                                        <i class="fas fa-spinner fa-spin me-2"></i>Creating Account...
                                    </span>
                                </button>
                            </div>
                            <div class="form-group">
                                <p>Do you have an account? <a href="{{ route('login') }}">Sign In</a></p>
                            </div>
                        </form>
                    </div>

                    <div class="auth-form auth-with-height auth-second-step {{ $fromLandingPage ?? false ? 'active' : '' }}">
                        <h2 class="mb-2">Check your email</h2>
                        <p class="sub-auth">Please enter the 4-digit code sent to <span id="email-placeholder">{{ session('signup_data.email', '') }}</span></p>
                        <div class="form-group mb-3 mt-5">
                            <div class="otp" id="otp-inputs">
                                <input type="text" class="form-control otp-input" pattern="[0-9]*" inputmode="numeric" maxlength="1" autocomplete="off" data-index="1">
                                <input type="text" class="form-control otp-input" pattern="[0-9]*" inputmode="numeric" maxlength="1" autocomplete="off" data-index="2">
                                <input type="text" class="form-control otp-input" pattern="[0-9]*" inputmode="numeric" maxlength="1" autocomplete="off" data-index="3">
                                <input type="text" class="form-control otp-input" pattern="[0-9]*" inputmode="numeric" maxlength="1" autocomplete="off" data-index="4">
                            </div>
                            <div class="error-message text-danger mt-2" style="display: none;"></div>
                        </div>
                        <div class="new-code mb-4">
                            <button type="button" class="btn btn-link p-0 resend-code-btn">
                                <span class="normal-text">
                                    <img src="assets/images/reload.png" alt="Reload"> Get new code
                                </span>
                                <span class="loading-text d-none">
                                    <i class="fas fa-spinner fa-spin me-2"></i>Sending...
                                </span>
                            </button>
                            <div class="timer-text text-muted small mt-2 d-none">
                                Try again in <span class="countdown">60</span> seconds
                            </div>
                        </div>
                        <div class="form-group d-flex justify-content-between align-items-center mb-4">
                            <a href="#" class="back-btn"><img src="assets/images/back.png"></a>
                            <button class="btn btn-primary auth-btn1">
                                <span class="normal-text">Next</span>
                                <span class="loading-text d-none">
                                    <i class="fas fa-spinner fa-spin me-2"></i>Verifying...
                                </span>
                            </button>
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
                                        <input class="form-check-input" type="radio" name="account_type" id="advertiser"
                                            value="Advertiser" checked>
                                        <label class="form-check-label" for="advertiser">
                                            Advertiser
                                            <span>Create campaigns for your organization</span>
                                        </label>
                                    </div>
                                    <div class="form-check border-0">
                                        <input class="form-check-input" type="radio" name="account_type" id="agency"
                                            value="Agency">
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
                                    <button type="button" class="btn btn-primary w-100" id="complete-signup-btn">
                                        <span class="normal-text">Get Started</span>
                                        <span class="loading-text d-none">
                                            <i class="fas fa-spinner fa-spin me-2"></i>Creating Account...
                                        </span>
                                    </button>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let resendTimer;

        function startResendTimer() {
            const timerText = $('.timer-text');
            const resendBtn = $('.resend-code-btn');
            const countdown = timerText.find('.countdown');
            let seconds = 60;

            resendBtn.prop('disabled', true);
            timerText.removeClass('d-none');
            
            resendTimer = setInterval(() => {
                seconds--;
                countdown.text(seconds);
                
                if (seconds <= 0) {
                    clearInterval(resendTimer);
                    timerText.addClass('d-none');
                    resendBtn.prop('disabled', false);
                }
            }, 1000);
        }

        $(document).ready(function () {
            // Initialize countdown timer if user is on OTP screen
            if ($('.auth-second-step').hasClass('active')) {
                startResendTimer();
            }
            // Handle "Sign Up" button click
            $('#next-step').click(function () {
                const btn = $(this);
                const normalText = btn.find('.normal-text');
                const loadingText = btn.find('.loading-text');

                // Clear previous error messages
                $('.error-message').hide().text('');

                // Show loading state
                btn.prop('disabled', true);
                normalText.addClass('d-none');
                loadingText.removeClass('d-none');

                // Gather form data
                const formData = {
                    first_name: $('#signup-form input[name="first_name"]').val(),
                    last_name: $('#signup-form input[name="last_name"]').val(),
                    email: $('#signup-form input[name="email"]').val(),
                    password: $('#signup-form input[name="password"]').val(),
                    password_confirmation: $('#signup-form input[name="password_confirmation"]').val(),
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
                    },
                    complete: function() {
                        // Reset button state
                        btn.prop('disabled', false);
                        normalText.removeClass('d-none');
                        loadingText.addClass('d-none');
                    }
                });
            });

            // Handle OTP input focus and validation
            const otpInputs = document.querySelectorAll('.otp-input');

            otpInputs.forEach(input => {
                // Only allow numbers
                input.addEventListener('keypress', (e) => {
                    if (!/[0-9]/.test(e.key)) {
                        e.preventDefault();
                    }
                });

                // Handle paste event
                input.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const pastedText = e.clipboardData.getData('text');
                    const numbers = pastedText.match(/[0-9]/g);
                    
                    if (numbers) {
                        numbers.slice(0, 4).forEach((num, index) => {
                            if (otpInputs[index]) {
                                otpInputs[index].value = num;
                            }
                        });
                        if (otpInputs[3].value) otpInputs[3].focus();
                    }
                });

                // Handle input
                input.addEventListener('input', function(e) {
                    if (e.inputType === "deleteContentBackward") {
                        // On backspace
                        const prev = this.dataset.index > 1 ? 
                            document.querySelector(`[data-index="${parseInt(this.dataset.index) - 1}"]`) : null;
                        if (prev) prev.focus();
                    } else if (this.value) {
                        // On input
                        const next = document.querySelector(`[data-index="${parseInt(this.dataset.index) + 1}"]`);
                        if (next) next.focus();
                    }
                });

                // Handle backspace
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !this.value) {
                        const prev = document.querySelector(`[data-index="${parseInt(this.dataset.index) - 1}"]`);
                        if (prev) prev.focus();
                    }
                });
            });

            // Handle "Next" button click for OTP verification
            $('.auth-btn1').click(function () {
                const btn = $(this);
                const normalText = btn.find('.normal-text');
                const loadingText = btn.find('.loading-text');

                // Clear previous error messages
                $('.error-message').hide().text('');

                // Show loading state
                btn.prop('disabled', true);
                normalText.addClass('d-none');
                loadingText.removeClass('d-none');

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
                    },
                    complete: function() {
                        // Reset button state
                        btn.prop('disabled', false);
                        normalText.removeClass('d-none');
                        loadingText.addClass('d-none');
                    }
                });
            });


            // Handle resend OTP button click
            $('.resend-code-btn').click(function() {
                const btn = $(this);
                const normalText = btn.find('.normal-text');
                const loadingText = btn.find('.loading-text');

                // Show loading state
                btn.prop('disabled', true);
                normalText.addClass('d-none');
                loadingText.removeClass('d-none');

                $.ajax({
                    url: '/api/resend-otp',
                    type: 'POST',
                    success: function (response) {
                        console.log('OTP resent successfully');
                        startResendTimer();
                    },
                    error: function (xhr) {
                        console.error('Error:', xhr);
                        const message = xhr.responseJSON.message;
                        if (message) {
                            $('.error-message').text(message).show();
                        }
                    },
                    complete: function() {
                        // Reset button state
                        normalText.removeClass('d-none');
                        loadingText.addClass('d-none');
                        // Note: btn remains disabled due to timer
                    }
                });
            });

            // Handle complete signup button click (Step 3 - Account Type)
            $('#complete-signup-btn').click(function() {
                const btn = $(this);
                const normalText = btn.find('.normal-text');
                const loadingText = btn.find('.loading-text');

                // Show loading state
                btn.prop('disabled', true);
                normalText.addClass('d-none');
                loadingText.removeClass('d-none');

                // Get selected account type
                const accountType = $('input[name="account_type"]:checked').val();

                $.ajax({
                    url: '/complete-signup',
                    type: 'POST',
                    data: { account_type: accountType },
                    success: function (response) {
                        console.log('Signup completed:', response);
                        // Redirect to dashboard
                        window.location.href = response.redirect;
                    },
                    error: function (xhr) {
                        console.error('Error:', xhr);
                        const message = xhr.responseJSON?.message || 'An error occurred';
                        alert(message);
                    },
                    complete: function() {
                        // Reset button state
                        btn.prop('disabled', false);
                        normalText.removeClass('d-none');
                        loadingText.addClass('d-none');
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