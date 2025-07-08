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
          <div class="campgain-tab analytics-tab">
            <nav>
              <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true"><img src="assets/images/audience.svg"> <span>{{ __('messages.published') }}</span></button>
                <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false"><img src="assets/images/percent.svg"> <span>{{ __('messages.engagement') }}</span></button>
                
              </div>
            </nav>
          </div>
          <div class="right-nav">
            <a href="#" class="notification-link"><img src="assets/images/notification.svg"></a>
            <div class="lang-menu">
      <a href="{{ route('switchLang', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'active' : '' }}"><img src="assets/images/us.svg" alt="English"> EN</a>
      <a href="{{ route('switchLang', 'es') }}" class="{{ app()->getLocale() == 'es' ? 'active' : '' }}"><img style="height: 25px; width: 25px;" class="auto" src="assets/images/mexico.png" alt="Español"> ES</a>
    </div>
            <a href="#" class="profile-name"><span>{{ Auth::user()->name }}</span> <img src="assets/images/dash-profile.png"></a>
          </div>
        </div>
      </div>
    </nav>
  </div>

  
  <section id="content-wrapper" class="mt-3">
      <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade active show" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div class="container">
                      <div class="row justify-content-center">
                      <div class="col-lg-10">
                        <div class="row align-items-center mb-4">
                          <div class="col-lg-3">
                            <div class="page-title">
                              <h1 class="mb-lg-0">{{ __('messages.audience') }}</h1>
                            </div>
                          </div>
                          <div class="col-lg-9">
                            <div class="row justify-content-lg-end">
                              <div class="col-lg-7">
                                <div class="form-group mb-3">
                                  <select class="form-select campagin-select w-100">
                                    <option>{{ __('messages.select_campaign') }}</option>
                                  </select>
                                  <span class="form-icon"><img src="assets/images/campaign.svg"></span>
                                </div>
                              </div>
                              <div class="col-lg-5">
                                <div class="form-group mb-3">
                                  <div class="date-custom form-control" id="reportrange">
                                    <i class="fa-solid fa-calendar-day"></i>&nbsp;
                                    <span></span> <i class="fa-solid fa-chevron-down"></i>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-lg-6">
                            <div class="total-box">
                              <div class="analytics-body">
                                <div class="total-left">
                                  <img src="assets/images/impression.svg">
                                </div>
                                <div class="total-right">
                                  <h2>49,656</h2>
                                  <span>{{ __('messages.total_impressions') }}</span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-6">
                            <div class="total-box">
                              <div class="analytics-body">
                                <div class="total-left">
                                  <img src="assets/images/cpm.svg">
                                </div>
                                <div class="total-right">
                                  <h2>654</h2>
                                  <span>{{ __('messages.total_cpm') }}</span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-6">
                            <div class="total-box">
                              
                              <div id="impressionchart"></div>
                            </div>
                          </div>
                          <div class="col-lg-6">
                            <div class="total-box">
                              
                              <div id="costchart" class=""></div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    </div>
                </div>
                  
                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                    <div class="container">
                      <div class="row justify-content-center">
                      <div class="col-lg-10">
                        <div class="row align-items-center mb-4">
                          <div class="col-lg-3">
                            <div class="page-title">
                              <h1 class="mb-lg-0">Audience <img src="assets/images/impression.svg"></h1>
                            </div>
                          </div>
                          <div class="col-lg-9">
                            <div class="row justify-content-lg-end">
                              <div class="col-lg-7">
                                <div class="form-group mb-lg-0 mb-3">
                                  <select class="form-select campagin-select w-100">
                                    <option>Select Campaign</option>
                                  </select>
                                  <span class="form-icon"><img src="assets/images/campaign.svg"></span>
                                </div>
                              </div>
                              <div class="col-lg-5">
                                <div class="form-group mb-lg-0 mb-3">
                                  <div class="date-custom form-control" id="reportrange">
                                    <i class="fa-solid fa-calendar-day"></i>&nbsp;
                                    <span></span> <i class="fa-solid fa-chevron-down"></i>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-lg-3 col-6">
                            <div class="total-box">
                              <div class="analytics-box">
                                <div class="total-left">
                                  <img src="assets/images/reach.svg">
                                </div>
                                <div class="total-right">
                                  <span>Total Reach</span>
                                  <h2>12,832</h2>
                                  
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-3 col-6">
                            <div class="total-box">
                              <div class="analytics-box">
                                <div class="total-left">
                                  <img src="assets/images/clicks.svg">
                                </div>
                                <div class="total-right">
                                  <span>Total Clicks</span>
                                  <h2>656</h2>
                                  
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-3 col-6">
                            <div class="total-box">
                              <div class="analytics-box">
                                <div class="total-left">
                                  <img src="assets/images/ctr.svg">
                                </div>
                                <div class="total-right">
                                  <span>Total CTR</span>
                                  <h2>654</h2>
                                  
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-3 col-6">
                            <div class="total-box">
                              <div class="analytics-box">
                                <div class="total-left">
                                  <img src="assets/images/cpc.svg">
                                </div>
                                <div class="total-right">
                                  <span>Total CPM</span>
                                  <h2>654</h2>
                                  
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-12">
                            <div class="total-box">
                              <div class="row g-2">
                                <div class="col-12">
                                  
                                </div>
                                <div class="col-lg-6">
                                  <div class="chart-box">
                                    
                                    <div id="impression-chart"></div>
                                  </div>
                                </div>
                                <div class="col-lg-6">
                                  <div class="chart-box">
                                    
                                    <div id="cost-chart" class=""></div>
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
              </div>
                
      
  </section>



</div>

    
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script type="text/javascript">
      $(function() {
      var start = moment().subtract(29, 'days');
      var end = moment();
      function cb(start, end) {
      $('#reportrange span').html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
      }
      $('#reportrange').daterangepicker({
      startDate: start,
      endDate: end,
      ranges: {
      'Today': [moment(), moment()],
      'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
      'Last 7 Days': [moment().subtract(6, 'days'), moment()],
      'Last 30 Days': [moment().subtract(29, 'days'), moment()],
      'This Month': [moment().startOf('month'), moment().endOf('month')],
      'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      }
      }, cb);
      cb(start, end);
      });
    </script>
    <script type="text/javascript" src="assets/js/script.js"></script>
  </body>
</html>