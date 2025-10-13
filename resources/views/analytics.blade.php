<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Mobiplay - Analytics</title>
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@300,301,400,401,500,501,700,701,900,901,1,2&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <link rel="stylesheet" type="text/css" href="assets/css/stylesheet.css">
    <link rel="stylesheet" type="text/css" href="assets/css/responsive.css">
  </head>
  <body class="inner-page">
    <div id="wrapper">

      <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
          <a href="{{ route('dashboard') }}"><img src="{{ asset('assets/images/logo.svg') }}"></a>
        </div>
        <ul class="sidebar-nav">
          <li class="">
            <a href="{{ route('dashboard') }}"><img src="{{ asset('assets/images/dashboard-icon.svg') }}"> {{ __('messages.dashboard') }}</a>
          </li>
          <li class="active">
            <a href="{{ route('analytics') }}"><img src="{{ asset('assets/images/analytics-icons.svg') }}"> <span>{{ __('messages.analytics') }}</span></a>
          </li>
          <li >
          <a href="{{ route('camplain-list') }}"><img src="{{ asset('assets/images/campaign-icon.svg') }}"> <span>Campañas</span></a>
          </li>
          <li >
            <a href="{{ route('profile') }}"><img src="{{ asset('assets/images/profile-icon.svg') }}"> {{ __('messages.profile') }}</a>
          </li>
        </ul>
        <a href="#" class="help-link"><img src="{{ asset('assets/images/help.svg') }}"> {{ __('messages.help_center') }}</a>
      </aside>
    

  <div id="navbar-wrapper">
    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <a href="#" class="navbar-brand" id="sidebar-toggle"><i class="fa fa-bars"></i></a>
          <div class="right-nav">
            <a href="#" class="notification-link"><img src="assets/images/notification.svg"></a>
            <div class="lang-menu">
      <a href="{{ route('switchLang', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'active' : '' }}"><img src="assets/images/us.svg" alt="English"> EN</a>
      <a href="{{ route('switchLang', 'es') }}" class="{{ app()->getLocale() == 'es' ? 'active' : '' }}"><img style="height: 25px; width: 25px;" class="auto" src="assets/images/mexico.png" alt="Español"> ES</a>
    </div>
            <a href="#" class="profile-name"><span>{{ Auth::user()->name }}</span> <img src="{{ Auth::user()->profile_image_url }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;"></a>
          </div>
        </div>
      </div>
    </nav>
  </div>

  
  <section id="content-wrapper" class="mt-3">
      <div class="container">
        <div class="row justify-content-center">
        <div class="col-lg-10">
          <div class="row align-items-center mb-4">
            <div class="col-lg-4">
              <div class="page-title">
                <h1 class="mb-lg-0">{{ __('messages.analytics') }}</h1>
              </div>
            </div>
            <div class="col-lg-8">
              <div class="row justify-content-lg-end">
                <div class="col-lg-6">
                  <div class="form-group mb-3">
                    <select class="form-select campagin-select w-100" id="campaignSelect">
                      <option value="">{{ __('messages.all_campaigns') }}</option>
                      @foreach($campaigns as $campaign)
                        <option value="{{ $campaign->id }}" {{ $campaignId == $campaign->id ? 'selected' : '' }}>
                          {{ $campaign->campaign_name }}
                        </option>
                      @endforeach
                    </select>
                    <span class="form-icon"><img src="assets/images/campaign.svg"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Analytics Summary Cards -->
          <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
              <div class="total-box">
                <div class="analytics-body">
                  <div class="total-left">
                    <img src="assets/images/impression.svg">
                  </div>
                  <div class="total-right">
                    <h2>{{ number_format($analytics['total_impressions']) }}</h2>
                    <span>{{ __('messages.total_impressions') }}</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
              <div class="total-box">
                <div class="analytics-body">
                  <div class="total-left">
                    <img src="assets/images/clicks.svg">
                  </div>
                  <div class="total-right">
                    <h2>{{ number_format($analytics['total_qr_scans']) }}</h2>
                    <span>QR Scans</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
              <div class="total-box">
                <div class="analytics-body">
                  <div class="total-left">
                    <img src="assets/images/ctr.svg">
                  </div>
                  <div class="total-right">
                    <h2>{{ $analytics['ctr'] }}%</h2>
                    <span>CTR</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
              <div class="total-box">
                <div class="analytics-body">
                  <div class="total-left">
                    <img src="assets/images/cpc.svg">
                  </div>
                  <div class="total-right">
                    <h2>${{ number_format($analytics['total_spent'], 2) }}</h2>
                    <span>Total Spent</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          @if($campaigns->isEmpty())
            <div class="row">
              <div class="col-12">
                <div class="total-box text-center py-5">
                  <h3>No Active Campaigns</h3>
                  <p class="text-muted">You don't have any active campaigns yet. <a href="{{ route('campaign-wizard') }}">Create your first campaign</a> to start seeing analytics data.</p>
                </div>
              </div>
            </div>
          @endif
          
        </div>
      </div>
      </div>      
  </section>

</div>

    
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    
    <script>
      // Campaign filter functionality
      document.getElementById('campaignSelect').addEventListener('change', function() {
        const campaignId = this.value;
        const url = new URL(window.location);
        if (campaignId) {
          url.searchParams.set('campaign_id', campaignId);
        } else {
          url.searchParams.delete('campaign_id');
        }
        window.location.href = url.toString();
      });
    </script>
    <script type="text/javascript" src="assets/js/script.js"></script>
  </body>
</html>