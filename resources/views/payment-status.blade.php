<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Mobiplay</title>
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@300,301,400,401,500,501,700,701,900,901,1,2&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <link rel="stylesheet" type="text/css" href="assets/css/ion.rangeSlider.css">
    <link rel="stylesheet" type="text/css" href="assets/css/stylesheet.css">
    <link rel="stylesheet" type="text/css" href="assets/css/responsive.css">
</head>

<body class="">
    <div id="">

        <div id="navbar-wrapper" class="campaginstep-header">
            <nav class="navbar navbar-inverse">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <a href="#" class="step-logo"><img src="assets/images/logo.svg"></a>
                        <div class="right-nav">
                            <a href="#" class="notification-link"><img src="assets/images/darknotification.svg"></a>
                            <div class="lang-menu">
                                <a href="#" class="active"><img src="assets/images/us.svg"> EN</a>
                                <a href="#" class=""><img src="assets/images/spain.svg"> ES</a>
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
                            <img src="{{ $isSuccess ? 'assets/images/circle_check.svg' : 'assets/images/circle_cross.svg' }}">
                            <h4>{{ $isSuccess ? 'Payment Successful' : 'Payment Canceled' }}</h4>
                            <p>{{ $isSuccess ? 'Thank you! Your payment has been successfully processed.' : 'Your payment was canceled or failed. Please try again.' }}</p>
                            <a href="{{ $isSuccess ? url('/analytics') : url('/') }}" class="btn btn-primary">
                                {{ $isSuccess ? 'View Analytics' : 'Go to Homepage' }}
                            </a>
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
    <script type="text/javascript" src="assets/js/ion.rangeSlider.js"></script>
    <script type="text/javascript" src="assets/js/script.js"></script>
    <script type="text/javascript">
        $('.lang-menu a').click(function() {
            $('.lang-menu a.active').removeClass('active');
            $(this).addClass('active');
        });
    </script>
    <script type="text/javascript">
        $('.morelink').click(function() {
            $('.more-box').slideToggle('active');
        });
    </script>
</body>

</html>