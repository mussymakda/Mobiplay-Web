@extends('layouts.app')

@section('style')
<style>
  .total-box {
    margin-bottom: 30px;
  }
</style>
@endsection

@section('content')
<section id="page-wrapper">
    <div id="wrapper">
        @include('layouts.sidebar')
        @include('layouts.navbar')

        <div id="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-4 col-lg-4">
                        <div class="page-title">
                            <h1 class="mb-lg-0">{{ __('messages.control_panel') }}</h1>
                        </div>
                    </div>
                </div>
      <div class="col-lg-12">
        <div class="create-campaign-box">
          <div class="campaign-img">
            <img class="img-fluid" src="assets/images/create-capaign.svg">
          </div>
          <div class="campaign-details">
            <h2>{{ __('messages.create_first_campaign') }}</h2>
            <p>{{ __('messages.launch_campaign_desc') }}</p>
            <a href="{{ route('campaign-wizard') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> {{ __('messages.create') }}</a>
          </div>
        </div>
      </div>
      
      @if($offers->count() > 0)
        @foreach($offers as $offer)
        <div class="col-lg-12">
          <div class="create-campaign-box offer-box">
            <div class="campaign-img">
              <img class="img-fluid" src="assets/images/add_money.svg">
            </div>
            <div class="campaign-details">
              <h2>{{ $offer->name }}</h2>
              <p>{{ $offer->description }}</p>
              <form action="{{ route('payment.make') }}" method="GET">
                <input type="hidden" name="offer_id" value="{{ $offer->id }}">
                <button type="submit" class="btn btn-primary">{{ __('messages.add_balance_btn') }}</button>
              </form>
            </div>
          </div>
        </div>
        @endforeach
      @else
        <div class="col-lg-12">
          <div class="create-campaign-box offer-box">
            <div class="campaign-img">
              <img class="img-fluid" src="assets/images/add_money.svg">
            </div>
            <div class="campaign-details">
              <h2>{{ __('messages.add_balance') }}</h2>
              <p>{{ __('messages.no_offers_available') }}</p>
              <form action="{{ route('payment.make') }}" method="GET">
                <button type="submit" class="btn btn-primary">{{ __('messages.add_balance_btn') }}</button>
              </form>
            </div>
          </div>
        </div>
      @endif

      <div class="row">
          <div class="col-lg-4">
              <div class="total-box">
                  <div class="total-header">
                      <div class="total-left">
                          <h2 id="stripe-balance">${{ Auth::user()->total_balance }}</h2>
                          <span>{{ __('messages.total_balance') }}</span>
                          @if(Auth::user()->bonus_balance > 0)
                          <small style="color: #28a745; font-size: 12px; display: block; margin-top: 5px;">
                              <i class="fas fa-gift"></i> Bonus: ${{ number_format(Auth::user()->bonus_balance, 2) }}
                          </small>
                          @endif
                      </div>
                      <div class="total-right">
                          <img src="assets/images/money-bag.png" style="height: 40px; width: 40px;" alt="Ícono de Saldo Total">
                      </div>
                  </div>
                  <div class="total-body">
                      <p>{{ __('messages.manage_balance_desc') }}</p>
                  </div>
              </div>
          </div>
        <div class="col-lg-4">
          <div class="total-box" style="color: gray">
            <div class="total-header">
              <div class="total-left">
                <h2>0</h2>
                <span>{{ __('messages.total_impressions') }}</span>
              </div>
              <div class="total-right">
                <img src="assets/images/imp.svg">
              </div>
            </div>
            <div class="total-body">
              <p>{{ __('messages.impressions_desc') }}</p>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="total-box" style="color: gray">
            <div class="total-header">
              <div class="total-left">
                <h2>$0.00</h2>
                <span>{{ __('messages.total_spent') }}</span>
              </div>
              <div class="total-right">
                <img src="assets/images/imp1.svg">
              </div>
            </div>
            <div class="total-body">
              <p>{{ __('messages.no_spend_message') }}</p>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="total-box" style="color: gray">
            <div class="total-header">
              <div class="total-left">
                <h2>0</h2>
                <span>{{ __('messages.active_campaigns') }}</span>
              </div>
              <div class="total-right">
                <img src="assets/images/imp2.svg">
              </div>
            </div>
            <div class="total-body">
              <p>{{ __('messages.create_campaigns_desc') }}</p>
            </div>
          </div>
        </div>
      </div>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});
</script>
@endsection
