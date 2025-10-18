<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Mobiplay</title>
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@300,301,400,401,500,501,700,701,900,901,1,2&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <link rel="stylesheet" type="text/css" href="assets/css/stylesheet.css">
    <link rel="stylesheet" type="text/css" href="assets/css/responsive.css">
</head>

  <body class="inner-page has-sidebar">
    <div id="wrapper">

        <aside id="sidebar-wrapper">
            <div class="sidebar-brand">
              <a href="{{ route('dashboard') }}" class="desk-logo"><img src="{{ asset('assets/images/logo.svg') }}"></a>
              <a href="{{ route('dashboard') }}" class="mobile-logo"><img src="{{ asset('assets/images/logo.svg') }}"></a>
            </div>
            <ul class="sidebar-nav">
              <li >
                <a href="{{ route('dashboard') }}"><img src="{{ asset('assets/images/dashboard-icon.svg') }}"> <span>Tablero</span></a>
              </li>
              <li>
                <a href="{{ route('analytics') }}"><img src="{{ asset('assets/images/analytics-icons.svg') }}"> <span>Analítica</span></a>
              </li>
              <li>
                <a href="campaign.html"><img src="{{ asset('assets/images/campaign-icon.svg') }}"> <span>Campañas</span></a>
              </li>
              <li class="active">
                <a href="{{ route('profile') }}"><img src="{{ asset('assets/images/profile-icon.svg') }}"> <span>Perfil</span></a>
              </li>
            </ul>
            <a href="#" class="help-link"><img src="{{ asset('assets/images/help.svg') }}"> <span>Centro de Ayuda</span></a>
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
                            <a href="#" class="profile-name"><span>{{ Auth::user()->name }}</span> <img
                                    src="{{ Auth::user()->profile_image_url }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;"></a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>

        <section id="content-wrapper">
            <div class="container">
                <div class="row justify-content-center g-0">
                    <div class="col-lg-10">
                        <div class="row align-items-center mb-4">
                            <div class="col-xl-2 col-lg-3">
                                <div class="page-title">
                                    <h1 class="mb-lg-0">Profile</h1>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                        <div class="col-xl-2 col-lg-3">
                            <div class="profile-link">
                                <a href="{{ route('profile') }}" >{{ __('messages.account_settings') }}</a>
                                <a href="{{ url('/payments') }}" class="active">{{ __('messages.payment_history') }}</a>
                                <a href="{{ url('/settings') }}">{{ __('messages.settings') }}</a>
                            </div>
                            </div>
                            <div class="col-xl-10 col-lg-9">
                                <div class="setting-box p-0">
                                    <div class="personal-info history-table">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h4>Payment History</h4>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Type</th>
                                                            <th>Amount</th>
                                                            <th>Date</th>
                                                            <th>Status</th>
                                                            <th>Details</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($payments as $payment)
                                                            <tr>
                                                                <td>
                                                                    @switch($payment->type)
                                                                        @case('deposit')
                                                                            <span class="badge bg-primary">Deposit</span>
                                                                            @break
                                                                        @case('bonus')
                                                                            <span class="badge bg-success">Bonus</span>
                                                                            @break
                                                                        @case('refund')
                                                                            <span class="badge bg-info">Refund</span>
                                                                            @break
                                                                        @case('ad_spend')
                                                                            <span class="badge bg-warning">Ad Spend</span>
                                                                            @break
                                                                        @default
                                                                            <span class="badge bg-secondary">{{ ucfirst($payment->type) }}</span>
                                                                    @endswitch
                                                                </td>
                                                                <td>
                                                                    ${{ number_format($payment->amount, 2) }} MXN
                                                                    @if($payment->type === 'deposit' && $payment->bonus_amount > 0)
                                                                        <small class="text-success d-block">
                                                                            <i class="fas fa-gift"></i> +${{ number_format($payment->bonus_amount, 2) }} bonus
                                                                        </small>
                                                                    @endif
                                                                </td>
                                                                <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                                                <td>
                                                                    @switch($payment->status)
                                                                        @case('completed')
                                                                            <span class="badge bg-success">Completed</span>
                                                                            @break
                                                                        @case('pending')
                                                                            <span class="badge bg-warning">Pending</span>
                                                                            @break
                                                                        @case('failed')
                                                                            <span class="badge bg-danger">Failed</span>
                                                                            @break
                                                                        @default
                                                                            <span class="badge bg-secondary">{{ ucfirst($payment->status) }}</span>
                                                                    @endswitch
                                                                </td>
                                                                <td>
                                                                    @if($payment->description)
                                                                        {{ $payment->description }}
                                                                    @elseif($payment->offer_id)
                                                                        Related to offer ID: {{ $payment->offer_id }}
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="5" class="text-center">No payments found</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                                <div class="d-flex justify-content-end m-3 ">
                                                    {!! $payments->links('pagination::bootstrap-4') !!}
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>


    <script type="text/javascript" src="assets/js/script.js"></script>
</body>

</html>
