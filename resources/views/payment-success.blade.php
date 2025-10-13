<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Payment Successful - Mobiplay</title>
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@300,301,400,401,500,501,700,701,900,901,1,2&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/ion.rangeSlider.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/stylesheet.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}">
</head>

<body class="">
    <div id="">

        <div id="navbar-wrapper" class="campaginstep-header">
            <nav class="navbar navbar-inverse">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <a href="#" class="step-logo"><img src="{{ asset('assets/images/logo.svg') }}"></a>
                        <div class="right-nav">
                            <a href="#" class="notification-link"><img src="{{ asset('assets/images/darknotification.svg') }}"></a>
                            <div class="lang-menu">
                                <a href="#" class="active"><img src="{{ asset('assets/images/us.svg') }}"> EN</a>
                                <a href="#" class=""><img src="{{ asset('assets/images/spain.svg') }}"> ES</a>
                            </div>
                            <a href="#" class="profile-name"><span>{{ Auth::user()->name }}</span><img src="{{ Auth::user()->profile_image_url }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;"></a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>

        <section id="content-wrapper" class="section-publish">
            <div class="container">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-10 g-0">
                        <div class="publish-box">
                            <img src="{{ asset('assets/images/circle_check.svg') }}">
                            <h4>Payment Successful!</h4>
                            <p>Thank you! Your payment has been successfully processed and your credits have been added to your account.</p>
                            
                            <div class="payment-details-box" style="background: #f8f9fa; border-radius: 12px; padding: 25px; margin: 20px 0; text-align: left;">
                                <h5 style="color: #333; margin-bottom: 15px; text-align: center;">
                                    <i class="fas fa-receipt" style="margin-right: 8px;"></i>
                                    Payment Details
                                </h5>
                                
                                <div class="detail-row" style="display: flex; justify-content: space-between; margin-bottom: 10px; padding: 8px 0; border-bottom: 1px solid #dee2e6;">
                                    <span style="color: #666; font-weight: 500;">Payment Amount:</span>
                                    <span style="color: #333; font-weight: 600;">${{ number_format($paymentDetails['amount'] ?? 0, 2) }} MXN</span>
                                </div>
                                
                                @if(($paymentDetails['bonus_amount'] ?? 0) > 0)
                                <div class="detail-row" style="display: flex; justify-content: space-between; margin-bottom: 10px; padding: 8px 0; border-bottom: 1px solid #dee2e6;">
                                    <span style="color: #28a745; font-weight: 500;">
                                        <i class="fas fa-gift" style="margin-right: 5px;"></i>
                                        Bonus Credits:
                                    </span>
                                    <span style="color: #28a745; font-weight: 600;">${{ number_format($paymentDetails['bonus_amount'] ?? 0, 2) }} MXN</span>
                                </div>
                                @endif
                                
                                <div class="detail-row" style="display: flex; justify-content: space-between; margin-bottom: 0; padding: 12px 0; border-top: 2px solid #28a745; margin-top: 15px;">
                                    <span style="color: #333; font-weight: 600; font-size: 16px;">Total Credits Added:</span>
                                    <span style="color: #28a745; font-weight: 700; font-size: 16px;">${{ number_format($paymentDetails['total_added'] ?? ($paymentDetails['amount'] ?? 0), 2) }} MXN</span>
                                </div>
                            </div>
                            
                            @if(($paymentDetails['bonus_amount'] ?? 0) > 0)
                            <div class="bonus-notice" style="background: linear-gradient(135deg, #28a745, #20c997); color: white; padding: 15px; border-radius: 8px; margin: 20px 0;">
                                <i class="fas fa-star" style="margin-right: 8px;"></i>
                                <strong>Congratulations!</strong> You've earned ${{ number_format($paymentDetails['bonus_amount'] ?? 0, 2) }} MXN in bonus credits from this offer!
                            </div>
                            @endif
                            
                            <div class="recurring-notice" style="background: #e3f2fd; color: #1976d2; padding: 15px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #2196f3;">
                                <i class="fas fa-sync-alt" style="margin-right: 8px;"></i>
                                <strong>Recurring Billing Active:</strong> Your account is now set up for automatic recurring payments for future credit purchases.
                            </div>
                            
                            <div class="action-buttons" style="margin-top: 30px;">
                                <a href="{{ route('dashboard') }}" class="btn btn-primary" style="margin-right: 10px;">
                                    <i class="fas fa-tachometer-alt" style="margin-right: 8px;"></i>
                                    Go to Dashboard
                                </a>
                                <a href="{{ route('payment.form') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-plus" style="margin-right: 8px;"></i>
                                    Add More Credits
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script type="text/javascript" src="{{ asset('assets/js/ion.rangeSlider.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/script.js') }}"></script>
    <script type="text/javascript">
        $('.lang-menu a').click(function() {
            $('.lang-menu a.active').removeClass('active');
            $(this).addClass('active');
        });
    </script>
</body>

</html>
