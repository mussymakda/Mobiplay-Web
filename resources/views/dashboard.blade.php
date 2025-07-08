@extends('layouts.app')

@section('meta')
@endsection

@section('style')
@endsection

@section('content')
<section id="content-wrapper" class="mt-3">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <div class="row align-items-center mb-4">
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
      <div class="col-lg-12">
        <div class="create-campaign-box offer-box">
          <div class="campaign-img">
            <img class="img-fluid" src="assets/images/add_money.svg">
          </div>
          <div class="campaign-details">
            <h2>{{ __('messages.add_balance') }}</h2>
            <p>{{ __('messages.limited_time_offer') }}</p>
            <form action="{{ route('payment.make') }}" method="GET">
              <button type="submit" class="btn btn-primary">{{ __('messages.add_balance_btn') }}</button>
            </form>
          </div>
        </div>
      </div>

      <div class="row">
          <div class="col-lg-4">
              <div class="total-box">
                  <div class="total-header">
                      <div class="total-left">
                          <h2 id="stripe-balance">${{ Auth::user()->credit_balance }}</h2>
                          <span>{{ __('messages.total_balance') }}</span>
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
</section>
@endsection

@section('script')
@endsection
