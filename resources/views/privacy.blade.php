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
    
    <link rel="stylesheet" type="text/css" href="assets/css/stylesheet.css">
    <link rel="stylesheet" type="text/css" href="assets/css/responsive.css">
  </head>
  <body class="inner-page">
    <div id="wrapper">

  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="dashboard.html"><img src="assets/images/logo.svg"></a>
    </div>
    <ul class="sidebar-nav">
      <li class="">
        <a href="dashboard.html"><img src="assets/images/dashboard-icon.svg"> Dashboard</a>
      </li>
      <li class="">
        <a href="analytics.html"><img src="assets/images/analytics-icons.svg"> Analytics</a>
      </li>
      <li >
        <a href="campaign.html"><img src="assets/images/campaign-icon.svg"> Campaigns</a>
      </li>
      <li class="active">
        <a href="#"><img src="assets/images/profile-icon.svg"> Profile</a>
      </li>
    </ul>
    <a href="#" class="help-link"><img src="assets/images/help.svg"> Help Center</a>
  </aside>

  <div id="navbar-wrapper">
    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <a href="#" class="navbar-brand" id="sidebar-toggle"><i class="fa fa-bars"></i></a>
          <div class="right-nav">
            <a href="#" class="notification-link"><img src="assets/images/notification.svg"></a>
            <div class="lang-menu">
              <a href="#" class="active"><img src="assets/images/us.svg"> EN</a>
              <a href="#" class=""><img style="height: 25px; width: 25px;" class="auto" src="assets/images/mexico.png"> ES</a>
            </div>
            <a href="#" class="profile-name"><span>{{ Auth::user()->name }}</span> <img src="{{ Auth::user()->profile_image_url }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;"></a>
          </div>
        </div>
      </div>
    </nav>
  </div>

  <section id="content-wrapper" class="mt-3">
    <div class="container">
      <div class="row justify-content-center g-0">
        <div class="col-lg-10">
          <div class="row align-items-center mb-4">
            <div class="col-xl-2 col-lg-3">
              <div class="page-title">
                <h1 class="mb-lg-0 profile-title">Profile</h1>
                <div class="profile-drop-link"><i class="fa fa-bars"></i></div>
              </div>
            </div>
            <div class="col-xl-10 col-lg-9">
              <div class="page-title">                
                <a class="back-c" href="{{ url('/settings') }}"><img src="assets/images/back-c.svg"></a>
              </div>
            </div>
            
          </div>
          <div class="row">
            <div class="col-xl-2 col-lg-3">
              <div class="profile-link">
                <a href="{{ route('profile') }}" >Account Settings</a>
                <a href="{{ url('/payments') }}">Payment History</a>
                <a href="{{ url('/settings') }}" class="active">Settings</a>
              </div>
            </div>
            <div class="col-xl-10 col-lg-9">
              <div class="setting-box">
                
                <div class="personal-info p-0">
                  <div class="row">
                    <div class="col-lg-12">                      
                      <h4>Privacy Policy</h4>
                    </div>
                    <div class="col-lg-8">
                      <div class="about-desc">
                        <p>LoremIpsum is a dynamic and innovative company dedicated to providing exceptional solutions for your needs. With a commitment to excellence and a passion for innovation, LoremIpsum has been serving clients worldwide since its inception.</p>
                        <p>Our mission is simple: to deliver top-notch services and products that exceed expectations. Whether you're looking for cutting-edge technology solutions, creative marketing strategies, or expert consultancy services, LoremIpsum has got you covered.</p>
                        <p>At LoremIpsum, we believe in fostering strong relationships with our clients. We prioritize communication, collaboration, and transparency every step of the way. Your success is our success, and we're dedicated to helping you achieve your goals.</p>
                        <p>With a team of experienced professionals and a customer-centric approach, LoremIpsum is your trusted partner for all your business needs. Join us on our journey as we strive to make a difference in the world of technology and beyond.</p>
                        <p>LoremIpsum - Where innovation meets excellence.</p>
                        <p>LoremIpsum is a dynamic and innovative company dedicated to providing exceptional solutions for your needs. With a commitment to excellence and a passion for innovation, LoremIpsum has been serving clients worldwide since its inception.</p>
                        <p>Our mission is simple: to deliver top-notch services and products that exceed expectations. Whether you're looking for cutting-edge technology solutions, creative marketing strategies, or expert consultancy services, LoremIpsum has got you covered.</p>
                        <p>At LoremIpsum, we believe in fostering strong relationships with our clients. We prioritize communication, collaboration, and transparency every step of the way. Your success is our success, and we're dedicated to helping you achieve your goals.</p>
                        <p>With a team of experienced professionals and a customer-centric approach, LoremIpsum is your trusted partner for all your business needs. Join us on our journey as we strive to make a difference in the world of technology and beyond.</p>
                      </div>
                    </div>
                   
                    
                  </div>
                </div>
                
              </div>
              
            </div>
            
            
          </div>
        </div>
      </div>
    </div>
  </section>

</div>

    <!-- Modal -->
<div class="modal fade" id="payment_Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      
      <div class="modal-body p-0">
        <div class="payment-method-modal">
          <div class="payment-title">
            <h3>Add a new payment method </h3>
            <a href="#" data-bs-dismiss="modal"><img src="assets/images/close-modal.svg"></a>
          </div>
          <div class="payment-form">
            <div class="row">
              <div class="col-12">
                <div class="form-group mb-3">
                  <label>Name<sup>*</sup></label>
                  <input type="text" name="" class="form-control">
                </div>
              </div>
              <div class="col-12">
                <div class="form-group mb-3">
                  <label>Card Number<sup>*</sup></label>
                  <input type="text" name="" class="form-control">
                </div>
              </div>
              <div class="col-9">
                <div class="form-group mb-3">
                  <label>Expiration Date<sup>*</sup></label>
                  <input type="text" name="" class="form-control">
                </div>
              </div>
              <div class="col-3">
                <div class="form-group mb-3">
                  <label>CVV<sup>*</sup></label>
                  <input type="text" name="" class="form-control">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group mb-3">
                  <label>Country</label>
                  <input type="text" name="" class="form-control">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group mb-3">
                  <label>Zip Code</label>
                  <input type="text" name="" class="form-control">
                </div>
              </div>
              <div class="col-lg-12">
                <div class=" mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label text-start" for="flexCheckDefault">
                      By providing your card information, you will allow Streetflteez to charge your card for future payments in accordance with their terms.
                    </label>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="form-group">
                  <a href="#" class="btn btn-primary w-100">Save</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
    </div>
  </div>
</div>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    
    <script type="text/javascript" src="assets/js/script.js"></script>
  </body>
</html>