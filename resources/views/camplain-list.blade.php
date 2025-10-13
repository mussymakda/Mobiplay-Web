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
          <li class="">
            <a href="{{ route('analytics') }}"><img src="{{ asset('assets/images/analytics-icons.svg') }}"> <span>{{ __('messages.analytics') }}</span></a>
          </li>
          <li class="active">
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
                <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false"><img src="assets/images/edit.svg"> <span>Draft</span></button>
                
              </div>
            </nav>
          </div>
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
            <div class="col-xl-5 col-lg-5">
              <div class="page-title d-flex justify-content-between">
                <h1 class="mb-lg-0">Campaigns</h1>
                <div class="campgain-tab">
                  <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                      <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Published</button>
                      <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Draft</button>
                      
                    </div>
                  </nav>
                </div>
              </div>
            </div>
            <div class="col-xl-7 col-lg-7">
              <div class="row">
                <div class="col-lg-8">
                  <!-- Removed unused goal filters -->
                </div>
                <div class="col-lg-4">
                  <div class="form-group mb-lg-0 mb-3">
                    <a href="{{ route('campaign-wizard') }}" class="camp-btn"><img src="assets/images/plus.svg"> New Campaigns</a>
                  </div>
                </div>
                
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade active show" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                  <!-- <div class="campaign-table">
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">Campaign</th>
                            <th scope="col">Goal</th>
                            <th scope="col">Schedule</th>
                            <th scope="col">Impressions</th>
                            <th scope="col">CPM</th>
                            <th scope="col">Total Spent</th>
                            <th scope="col">Status</th>
                            <th scope="col"></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            
                            <td style="border: 0;" colspan="8" rowspan="3">
                              <div class="empty-table"><p>No Active Campaigns Currently</p></div>
                            </td>
                            
                          </tr>
                          
                        </tbody>
                      </table>
                    </div>
                  </div> -->
                  <div class="campaign-table">
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">Campaign</th>
                            <th scope="col">Schedule</th>
                            <th scope="col">Impressions</th>
                            <th scope="col">Daily Budget</th>
                            <th scope="col">Today Spent</th>
                            <th scope="col">Status</th>
                            <th scope="col"></th>
                          </tr>
                        </thead>
                        <tbody>
                          @forelse($publishedCampaigns as $campaign)
                          <tr>
                            <td>
                              <p>{{ $campaign->campaign_name }}</p>
                              <span>#{{ $campaign->id }}</span>
                            </td>
                            <td>{{ $campaign->created_at->format('n-j-y') }} to {{ $campaign->status === 'active' ? 'Ongoing' : 'Paused' }}</td>
                            <td>{{ number_format($campaign->impressions) }}</td>
                            <td>${{ number_format($campaign->daily_budget ?? 0, 2) }}</td>
                            <td>${{ number_format($campaign->daily_spent ?? 0, 2) }}</td>
                            <td>
                              @if($campaign->status === 'active')
                                <a href="#" class="btn btn-success">Active</a>
                              @elseif($campaign->status === 'paused')
                                <a href="#" class="btn btn-warning">Paused</a>
                              @elseif($campaign->status === 'pending')
                                <a href="#" class="btn btn-info">Pending</a>
                              @else
                                <a href="#" class="btn btn-secondary">{{ ucfirst($campaign->status) }}</a>
                              @endif
                            </td>
                            <td>
                              <div>
                                <a href="{{ route('campaigns.edit', $campaign) }}" class="me-3 d-inline-block"><img src="assets/images/edit.svg"></a>
                                @if($campaign->status === 'pending')
                                <form method="POST" action="{{ route('campaigns.destroy', $campaign) }}" class="d-inline">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit" class="btn btn-link p-0" onclick="return confirm('Are you sure you want to delete this campaign?')">
                                    <img src="assets/images/trash.svg">
                                  </button>
                                </form>
                                @endif
                              </div>
                            </td>
                          </tr>
                          @empty
                          <tr>
                            <td style="border: 0;" colspan="8">
                              <div class="empty-table"><p>No Active Campaigns Currently</p></div>
                            </td>
                          </tr>
                          @endforelse
                          
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                                    <!-- <div class="campaign-table">
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">Campaign</th>
                            <th scope="col">Goal</th>
                            <th scope="col">Schedule</th>
                            <th scope="col">Impressions</th>
                            <th scope="col">CPM</th>
                            <th scope="col">Total Spent</th>
                            <th scope="col">Status</th>
                            <th scope="col"></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            
                            <td style="border: 0;" colspan="8" rowspan="3">
                              <div class="empty-table"><p>No Active Campaigns Currently</p></div>
                            </td>
                            
                          </tr>
                          
                        </tbody>
                      </table>
                    </div>
                  </div> -->
                  <div class="campaign-table">
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">Campaign</th>
                            <th scope="col">Schedule</th>
                            <th scope="col">Next Step</th>                            
                            <th scope="col"></th>
                          </tr>
                        </thead>
                        <tbody>
                          @forelse($draftCampaigns as $campaign)
                          <tr>
                            <td>
                              <p>{{ $campaign->campaign_name }}</p>
                              <span>#{{ $campaign->id }}</span>
                            </td>
                            <td>{{ $campaign->created_at->format('n-j-y') }}</td>
                            <td>
                              @if($campaign->status === 'draft')
                                <a href="{{ route('campaigns.edit', $campaign) }}" class="btn btn-warning">Complete Campaign</a>
                              @elseif($campaign->status === 'pending')
                                <a href="#" class="btn btn-grey">Under Review</a>
                              @elseif($campaign->status === 'rejected')
                                <a href="{{ route('campaigns.edit', $campaign) }}" class="btn btn-grey">Needs Changes</a>
                              @endif
                            </td>
                            <td>
                              <div>
                                <a href="{{ route('campaigns.edit', $campaign) }}" class="me-3 d-inline-block"><img src="assets/images/edit.svg"></a>
                                <form method="POST" action="{{ route('campaigns.destroy', $campaign) }}" class="d-inline">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit" class="btn btn-link p-0" onclick="return confirm('Are you sure you want to delete this campaign?')">
                                    <img src="assets/images/trash.svg">
                                  </button>
                                </form>
                              </div>
                            </td>
                          </tr>
                          @empty
                          <tr>
                            <td style="border: 0;" colspan="5">
                              <div class="empty-table"><p>No Draft Campaigns Currently</p></div>
                            </td>
                          </tr>
                          @endforelse
                        </tbody>
                      </table>
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