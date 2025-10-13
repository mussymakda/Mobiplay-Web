<!doctype html>
<html lang="en">

<h                            <a href="#" class="profile-name"><span>{{ Auth::user()->name }}</span><img
                                src="{{ Auth::user()->profile_image_url }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;" alt="Profile"></a>d>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Mobiplay</title>
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@300,301,400,401,500,501,700,701,900,901,1,2&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/ion.rangeSlider.css">
    <link rel="stylesheet" type="text/css" href="assets/css/stylesheet.css">
    <link rel="stylesheet" type="text/css" href="assets/css/responsive.css">

    <!-- Stripe.js -->
    <script src="https://js.stripe.com/v3/"></script>
</head>

<body>
    <div id="navbar-wrapper" class="campaginstep-header">
        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a href="{{ route('dashboard') }}" class="step-logo"><img src="assets/images/logo.svg"
                            alt="Logo"></a>
                    <div class="right-nav">
                        <a href="#" class="notification-link"><img src="assets/images/darknotification.svg"
                                alt="Notification"></a>
                        <div class="lang-menu">
                            <a href="#" class="active"><img src="assets/images/us.svg" alt="EN"> EN</a>
                            <a href="#" class=""><img src="assets/images/spain.svg" alt="ES"> ES</a>
                        </div>
                        <a href="#" class="profile-name"><span>{{ Auth::user()->name }}</span><img
                                src="{{ Auth::user()->profile_image_url }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;" alt="Profile"></a>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <div class="step-line">
        <a href="#" class="step-close">Close <img src="assets/images/close.svg" /></a>
    </div>
    <section id="content-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 g-lg-0">
                    <div class="choose-campgain">
                        <h2 class="text-start">Review</h2>
                        <p class="text-start">Review your payment before paying.</p>
                    </div>
                    <div class="row justify-content-between">
                        <div class="col-lg-7">
                            <div class="summary-box">
                                <h3>Summary</h3>
                                <div class="summary-details">
                                    <div class="summary-d">
                                        <div class="summary-inside">
                                            <label>Amount</label>
                                            <span id="summary-amount">$0.00</span>
                                        </div>
                                    </div>
                                    <div class="summary-d">
                                        <div class="summary-inside">
                                            <label>Tax (16%)</label>
                                            <span id="summary-tax">$0.00</span>
                                        </div>
                                    </div>
                                    <div class="summary-d">
                                        <div class="summary-inside">
                                            <label>Bonus</label>
                                            <span id="summary-bonus">$0.00</span>
                                        </div>
                                    </div>
                                    <div class="summary-d">
                                        <div class="summary-inside">
                                            <label>Total</label>
                                            <span id="summary-total">$0.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="payment-form center">
                                <form id="payment-form">
                                    @csrf
                                    <div class="payment-details">
                                        <div class="row gx-3">
                                            @if($offers->count() > 0)
                                            <div class="col-lg-12">
                                                <div class="form-group mb-3">
                                                    <label>Select Offer (Optional)</label>
                                                    <select id="offer-select" name="offer_id" class="form-control" onchange="updateSummary()">
                                                        <option value="">No offer selected</option>
                                                        @foreach($offers as $offer)
                                                        <option value="{{ $offer->id }}" 
                                                            data-type="{{ $offer->type }}"
                                                            data-percentage="{{ $offer->bonus_percentage }}"
                                                            data-fixed="{{ $offer->bonus_fixed_amount }}"
                                                            data-min="{{ $offer->minimum_deposit }}"
                                                            data-max="{{ $offer->maximum_bonus }}"
                                                            {{ $selectedOffer && $selectedOffer->id == $offer->id ? 'selected' : '' }}>
                                                            {{ $offer->name }} 
                                                            @if($offer->bonus_percentage)
                                                                ({{ $offer->bonus_percentage }}% bonus)
                                                            @elseif($offer->bonus_fixed_amount)
                                                                (${{ number_format($offer->bonus_fixed_amount, 2) }} bonus)
                                                            @endif
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="col-lg-12">
                                                <div class="form-group mb-3">
                                                    <label>Enter Amount<sup>*</sup></label>
                                                    <input type="number" id="amount" name="amount"
                                                        class="form-control" oninput="updateSummary()" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="camp-grp-btn pb-0">
                                        <button id="checkout-button" class="btn btn-primary" type="button">Pay with
                                            Stripe</button>
                                        
                                        <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- JavaScript dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="assets/js/ion.rangeSlider.js"></script>
    <script src="assets/js/script.js"></script>
 
    <script>
    function updateSummary() {
        const amount = parseFloat(document.getElementById('amount').value) || 0;
        const tax = amount * 0.16;
        let bonus = 0;
        
        // Get selected offer
        const offerSelect = document.getElementById('offer-select');
        if (offerSelect && offerSelect.value) {
            const selectedOption = offerSelect.options[offerSelect.selectedIndex];
            const offerType = selectedOption.getAttribute('data-type');
            const bonusPercentage = parseFloat(selectedOption.getAttribute('data-percentage')) || 0;
            const bonusFixed = parseFloat(selectedOption.getAttribute('data-fixed')) || 0;
            const minDeposit = parseFloat(selectedOption.getAttribute('data-min')) || 0;
            const maxBonus = parseFloat(selectedOption.getAttribute('data-max')) || 0;
            
            // Calculate bonus if amount meets minimum
            if (amount >= minDeposit) {
                if (offerType === 'percentage_bonus' || offerType === 'first_deposit' || offerType === 'reload_bonus') {
                    bonus = amount * (bonusPercentage / 100);
                    // Apply maximum bonus limit if set
                    if (maxBonus > 0 && bonus > maxBonus) {
                        bonus = maxBonus;
                    }
                } else if (offerType === 'fixed_bonus') {
                    bonus = bonusFixed;
                }
            }
        }
        
        const total = amount + tax;

        document.getElementById('summary-amount').textContent = `$${amount.toFixed(2)}`;
        document.getElementById('summary-tax').textContent = `$${tax.toFixed(2)}`;
        document.getElementById('summary-bonus').textContent = `$${bonus.toFixed(2)}`;
        document.getElementById('summary-total').textContent = `$${total.toFixed(2)}`;
    }

    // Initialize the form when page loads
    document.addEventListener('DOMContentLoaded', function() {
        updateSummary();
    });

document.getElementById('checkout-button').addEventListener('click', function () {
    const amount = parseFloat(document.getElementById('amount').value);
    
    // Get the selected offer ID
    const offerSelect = document.getElementById('offer-select');
    const offerId = offerSelect ? offerSelect.value : null;
    
    console.log('Sending payment request:', { amount, offer_id: offerId });

    const requestData = { amount };
    if (offerId) {
        requestData.offer_id = offerId;
    }

    fetch('/create-metered-payment', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify(requestData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.sessionId) {
            console.log('Received session ID:', data.sessionId);
            // Initialize Stripe with your public key
            const stripe = Stripe("{{ env('STRIPE_KEY') }}"); // Replace with your public key
            // Redirect to Stripe Checkout using the sessionId
            stripe.redirectToCheckout({
                sessionId: data.sessionId // Pass the sessionId from backend
            }).then(function(result) {
                if (result.error) {
                    // Handle error during checkout redirect
                    alert(result.error.message);
                }
            });
        } else {
            alert('Error: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Payment error: ' + error.message);
    });
});

</script>
</body>

</html>
