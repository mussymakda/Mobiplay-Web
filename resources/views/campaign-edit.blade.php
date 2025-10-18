<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
    <title>Edit Campaign - Mobiplay</title>
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@300,301,400,401,500,501,700,701,900,901,1,2&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CSS Libraries -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ion-rangeslider/css/ion.rangeSlider.min.css">
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.css' rel='stylesheet' />
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css" type="text/css">
    
    <!-- Lottie Animation Library -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/stylesheet.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/ion.rangeSlider.css') }}">

<style>
    /* Critical Campaign Wizard Styles - Improved Layout */
    .device-mockup {
        text-align: center;
        margin: 20px auto;
        background: #fff;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        border: 1px solid #e9ecef;
    }
    
    /* Map Container Styles */
    #map {
        width: 100%;
        height: 70vh;
        min-height: 500px;
        max-height: 700px;
        border-radius: 0;
    }
    
    .col-lg-7:has(#map) {
        padding: 0 !important;
    }
    
    .tablet-frame {
        display: block;
        padding: 15px;
        background: linear-gradient(145deg, #000, #333);
        border-radius: 25px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        position: relative;
        transition: all 0.3s ease;
        margin: 0 auto;
        width: fit-content;
    }
    
    .tablet-frame:hover {
        transform: scale(1.02);
        box-shadow: 0 15px 40px rgba(0,0,0,0.4);
    }
    
    .tablet-frame::before {
        content: '';
        position: absolute;
        top: 50%;
        left: -8px;
        width: 4px;
        height: 40px;
        background: #000;
        border-radius: 2px;
        transform: translateY(-50%);
    }
    
    .tablet-screen {
        width: 320px;
        height: 200px;
        background: #000;
        border-radius: 15px;
        overflow: hidden;
        position: relative;
        border: 2px solid #333;
    }
    
    .ad-preview-container {
        width: 100%;
        height: 100%;
        position: relative;
    }
    
    .ad-content {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .qr-code-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        pointer-events: none;
    }
    
    .qr-code-position {
        position: absolute;
        width: 40px;
        height: 40px;
    }
    
    .qr-code-position.top-right {
        top: 10px;
        right: 10px;
    }
    
    .qr-code-position.top-left {
        top: 10px;
        left: 10px;
    }
    
    .qr-code-position.bottom-right {
        bottom: 10px;
        right: 10px;
    }
    
    .qr-code-position.bottom-left {
        bottom: 10px;
        left: 10px;
    }
    
    .qr-placeholder {
        background: rgba(255,255,255,0.95);
        border-radius: 8px;
        padding: 8px;
        text-align: center;
        font-size: 9px;
        color: #495057;
        border: 1px dashed #007bff;
        transition: all 0.3s ease;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        max-width: 50px;
    }
    
    .qr-placeholder i {
        font-size: 14px;
        margin-bottom: 2px;
        color: #007bff;
    }
    
    .qr-placeholder span {
        font-size: 7px;
        font-weight: 500;
        color: #6c757d;
        line-height: 1.2;
    }
    
    .device-label {
        margin-top: 20px;
        font-size: 14px;
        color: #495057;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    /* QR Position Selector */
    .qr-position-selector h6 {
        margin-bottom: 15px;
        color: #000;
        font-weight: 600;
    }
    
    .position-options {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }
    
    .position-options input[type="radio"] {
        display: none;
    }
    
    .position-option {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 15px;
        border: 2px solid #e9e9e9;
        border-radius: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .position-option:hover {
        border-color: #007bff;
        background-color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,123,255,0.15);
    }
    
    .position-options input[type="radio"]:checked + .position-option {
        border-color: #007bff;
        background-color: rgba(0,123,255,0.05);
        box-shadow: 0 4px 15px rgba(0,123,255,0.25);
        transform: translateY(-1px);
    }
    
    .position-options input[type="radio"]:checked + .position-option .qr-dot {
        background: #007bff;
        box-shadow: 0 0 8px rgba(0,123,255,0.4);
    }
    
    .mini-screen {
        width: 40px;
        height: 30px;
        background: #000;
        border-radius: 5px;
        position: relative;
        margin-bottom: 5px;
    }
    
    .qr-dot {
        width: 8px;
        height: 8px;
        background: #007bff;
        border-radius: 2px;
        position: absolute;
    }
    
    .qr-dot.top-right {
        top: 3px;
        right: 3px;
    }
    
    .qr-dot.top-left {
        top: 3px;
        left: 3px;
    }
    
    .qr-dot.bottom-right {
        bottom: 3px;
        right: 3px;
    }
    
    .qr-dot.bottom-left {
        bottom: 3px;
        left: 3px;
    }
    
    .position-option span {
        font-size: 12px;
        font-weight: 500;
        color: #000;
    }
    
    /* Draft Button Fix */
    .btn-outline-info {
        background: transparent !important;
        border: 1px solid #6c757d !important;
        color: #6c757d !important;
        padding: 13px 25px !important;
        font-size: 18px !important;
        font-weight: 600 !important;
        border-radius: 50px !important;
    }
    
    .btn-outline-info:hover {
        background: #6c757d !important;
        color: #fff !important;
        border: 1px solid #6c757d !important;
    }
    
    /* Package Selection - Use Original Styling */
    .spend-box-wrapper {
        display: block;
        cursor: pointer;
        text-decoration: none;
    }
    
    .package-selector:checked + .spend-box-wrapper .spend-box {
        border: 2px solid #000 !important;
    }
    
    /* General Spacing Fixes */
    .step {
        padding: 30px 0;
        min-height: calc(100vh - 200px);
    }
    
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }
    
    .row {
        margin-left: -15px;
        margin-right: -15px;
    }
    
    .col-lg-8, .col-lg-7, .col-lg-5, .col-lg-4 {
        padding-left: 15px;
        padding-right: 15px;
    }
    
    .choose-campgain {
        margin-bottom: 25px;
        text-align: left;
    }
    
    .choose-campgain h2 {
        margin-bottom: 8px;
        font-size: 26px;
        font-weight: 700;
        line-height: 1.2;
    }
    
    .choose-campgain p {
        margin-bottom: 0;
        color: #6c757d;
        font-size: 15px;
        line-height: 1.4;
    }
    
    .loacat-section {
        padding: 25px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        height: fit-content;
    }
    
    .creative-box {
        background: white;
        border-radius: 15px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        padding: 20px;
        margin-bottom: 15px;
    }
    
    .form-group {
        margin-bottom: 15px;
    }
    
    .camp-grp-btn {
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid #e9ecef;
        display: flex;
        gap: 15px;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .camp-grp-btn .btn {
        margin-right: 0;
    }
    
    .camp-grp-btn .prev-btn {
        margin-right: auto;
    }
    
    /* Original Button Styles - Black and White */
    .btn-primary {
        background-color: #000;
        border-color: #000;
        color: white;
    }
    
    .btn-primary:hover {
        background-color: #333;
        border-color: #333;
        color: white;
    }
    
    .btn-secondary {
        background-color: white;
        border-color: #000;
        color: #000;
    }
    
    .btn-secondary:hover {
        background-color: #f8f9fa;
        border-color: #000;
        color: #000;
    }
    
    /* Form Elements */
    .form-control {
        border-radius: 8px;
        border: 2px solid #e3e6f0;
        padding: 12px 15px;
        font-size: 14px;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        font-size: 14px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .package-pricing {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid rgba(255,255,255,0.2);
    }
    
    .package-pricing small {
        color: rgba(255,255,255,0.9) !important;
        font-size: 12px !important;
        font-weight: 500 !important;
    }
    
    /* Form validation fixes */
    .form-control.is-invalid {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.2rem rgba(220,53,69,0.25) !important;
    }
    
    .invalid-feedback {
        color: #dc3545 !important;
    }
    
    .validation-error {
        animation: slideIn 0.3s ease-out;
        border-radius: 15px;
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Driver marker animations */
    @keyframes pulse-green {
        0% {
            box-shadow: 0 4px 12px rgba(0,0,0,0.3), 0 2px 4px rgba(0,0,0,0.2), 0 0 0 0 rgba(16, 185, 129, 0.7);
        }
        50% {
            box-shadow: 0 4px 12px rgba(0,0,0,0.3), 0 2px 4px rgba(0,0,0,0.2), 0 0 0 8px rgba(16, 185, 129, 0.3);
        }
        100% {
            box-shadow: 0 4px 12px rgba(0,0,0,0.3), 0 2px 4px rgba(0,0,0,0.2), 0 0 0 0 rgba(16, 185, 129, 0);
        }
    }

    @keyframes pulse-orange {
        0% {
            box-shadow: 0 4px 12px rgba(0,0,0,0.3), 0 2px 4px rgba(0,0,0,0.2), 0 0 0 0 rgba(245, 158, 11, 0.7);
        }
        50% {
            box-shadow: 0 4px 12px rgba(0,0,0,0.3), 0 2px 4px rgba(0,0,0,0.2), 0 0 0 6px rgba(245, 158, 11, 0.4);
        }
        100% {
            box-shadow: 0 4px 12px rgba(0,0,0,0.3), 0 2px 4px rgba(0,0,0,0.2), 0 0 0 0 rgba(245, 158, 11, 0);
        }
    }

    /* Driver marker base styles */
    .driver-marker {
        will-change: transform, box-shadow;
        width: 16px !important;
        height: 16px !important;
        border-radius: 50% !important;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.5) !important;
        border: 2px solid white !important;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    /* Pulse animation for active markers */
    .driver-marker.active {
        animation: pulse 1.5s infinite;
    }
    
    /* Driver marker popup styles */
    .driver-popup {
        padding: 5px;
    }
    
    .driver-popup h6 {
        font-weight: 600;
        border-bottom: 1px solid #eee;
        padding-bottom: 5px;
    }
    
    .driver-popup .driver-details {
        font-size: 12px;
    }
    
    /* Loading indicator for map */
    #map-loading-indicator {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 1000;
        background-color: rgba(255, 255, 255, 0.8);
        border-radius: 4px;
        padding: 5px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }
    
    /* Pulse animation keyframes */
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(0, 128, 0, 0.7);
        }
        70% {
            box-shadow: 0 0 0 8px rgba(0, 128, 0, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(0, 128, 0, 0);
        }
    }
</style>
</head>


<body class="bg">
    <div id="navbar-wrapper" class="campaginstep-header">
        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a href="{{ route('dashboard') }}" class="step-logo"><img src="{{ asset('assets/images/logo.svg') }}" /></a>
                    <div class="right-nav">
                        <a href="#" class="notification-link"><img src="{{ asset('assets/images/darknotification.svg') }}" /></a>
                        <div class="lang-menu">
                            <a href="{{ route('switchLang', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'active' : '' }}"><img src="{{ asset('assets/images/us.svg') }}" /> EN</a>
                            <a href="{{ route('switchLang', 'es') }}" class="{{ app()->getLocale() == 'es' ? 'active' : '' }}"><img src="{{ asset('assets/images/spain.svg') }}" /> ES</a>
                        </div>
                        <a href="#" class="profile-name"><span>{{ Auth::user()->name }} </span><img
                                src="{{ Auth::user()->profile_image_url }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;" /></a>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <div class="step-line">
        <a href="{{ route('camplain-list') }}" class="step-close">Close <img src="{{ asset('assets/images/close.svg') }}" /></a>
        <div class="figure-list">
            <ul>
                <li>
                    <a href="#" class="step-link active" data-step="0">
                        <label><img src="{{ asset('assets/images/creative.svg') }}" /></label>
                        <span>Creative</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="step-link" data-step="1">
                        <label><img src="{{ asset('assets/images/location.svg') }}" /></label>
                        <span>Locations</span>
                    </a>
                </li>
                @if($campaign->status === 'draft')
                <li>
                    <a href="#" class="step-link" data-step="2">
                        <label><img src="{{ asset('assets/images/schedule.svg') }}" /></label>
                        <span>Priority</span>
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </div>

    <section id="content-wrapper" class="campaign-wizard">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-14">
                    <form id="multiStepForm" method="POST" action="{{ route('campaigns.update', $campaign) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        {{-- Step 0: Creative --}}
                        <div class="step" id="step-0">
                            <section id="content-wrapper">
                                <div class="container">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-10">
                                            <div class="choose-campgain">
                                                <h2 class="text-start">Edit Creative</h2>
                                                <p class="text-start">Update your advertisement details</p>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label for="campaign_name" class="form-label">Campaign Name <span class="text-danger">*</span></label>
                                                <input type="text" name="campaign_name" id="campaign_name" 
                                                       class="form-control" placeholder="Enter Campaign Name" 
                                                       value="{{ old('campaign_name', $campaign->campaign_name) }}" {{ $campaign->status !== 'draft' ? 'required' : '' }}>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="row justify-content-between">
                                                <div class="col-lg-7">
                                                    <div class="creative-box mb-3">
                                                        <div class="creative-inner">
                                                            <div class="row align-items-center">
                                                                <div class="col-xl-6">
                                                                    <div class="form-group">
                                                                        <h6>Upload Media @if($campaign->status === 'draft')<sup>*</sup>@endif</h6>
                                                                        @if($campaign->media_path)
                                                                            <div class="mb-2">
                                                                                <small class="text-muted">Current: {{ basename($campaign->media_path) }}</small>
                                                                            </div>
                                                                        @endif
                                                                        <div class="uploadmedia" id="drop-zone">
                                                                            <label>
                                                                                <input type="file" name="media_file"
                                                                                    id="fileElem"
                                                                                    accept="image/*,video/*"
                                                                                    style="display:none;" />
                                                                                <div class="drop-zone">
                                                                                    Drop new media here to upload
                                                                                </div>
                                                                                <span>Browse</span>
                                                                            </label>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="col-xl-6 mt-2">
                                                                    <div class="upmedia-details">
                                                                        <p>Asset must fit a 16:9 ratio or will be
                                                                            automatically resized upon upload</p>
                                                                        <p>Supported video formats: MP4, MPEG, AVI, and
                                                                            MOV</p>
                                                                        <p>Supported image formats: PNG and JPEG</p>
                                                                        @if($campaign->status !== 'draft')
                                                                            <p><strong>Note:</strong> Leave empty to keep current media</p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="creative-inner">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="form-group mb-3">
                                                                        <h4>Embed QR Code</h4>
                                                                        <p>Add a trackable URL to track visits reported
                                                                            in your dashboard. A QR code will be
                                                                            generated to your ad upon upload.</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-7">
                                                                    <div class="form-group mb-3">
                                                                        <h6>Call to Action (CTA)<sup>*</sup></h6>
                                                                        <input type="text" name="cta_text"
                                                                            class="form-control bg-grey"
                                                                            id="cta" placeholder="Enter CTA" 
                                                                            value="{{ old('cta_text', $campaign->cta_text ?? '') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-7">
                                                                    <div class="form-group mb-3">
                                                                        <h6>Call to Action URL<sup>*</sup></h6>
                                                                        <input type="text" name="cta_url"
                                                                            class="form-control bg-grey"
                                                                            id="ctaUrl" placeholder="Enter URL" 
                                                                            value="{{ old('cta_url', $campaign->cta_url) }}" {{ $campaign->status !== 'draft' ? 'required' : '' }}>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-5 align-content-center">
                                                                    <div class="form-group">
                                                                        <p class="url-visit mb-0">Visits to this URL
                                                                            are tracked and reported in your dashboard.
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 mb-3">
                                                                    <button type="button" id="generateQr"
                                                                        class="btn btn-primary">Generate QR
                                                                        Code</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="uploadpreview">
                                                        <h6>How it will appear to drivers</h6>
                                                        <div class="device-mockup">
                                                            <div class="tablet-frame">
                                                                <div class="tablet-screen">
                                                                    <div class="ad-preview-container">
                                                                        <div id="preview-container" class="ad-content">
                                                                            @if($campaign->media_path)
                                                                                @if($campaign->media_type === 'image')
                                                                                    <img id="preview-image" src="{{ asset('storage/' . $campaign->media_path) }}"
                                                                                        alt="Campaign preview"
                                                                                        style="width: 100%; height: 100%; object-fit: cover;" />
                                                                                @else
                                                                                    <video id="preview-video" controls
                                                                                        style="width: 100%; height: 100%;">
                                                                                        <source id="video-source" src="{{ asset('storage/' . $campaign->media_path) }}"
                                                                                            type="video/mp4">
                                                                                        Your browser does not support the video tag.
                                                                                    </video>
                                                                                @endif
                                                                            @else
                                                                                <img id="preview-image" src="{{ asset('assets/images/addbase.png') }}"
                                                                                    alt="Ad preview"
                                                                                    style="width: 100%; height: 100%; object-fit: cover;" />
                                                                                <video id="preview-video" controls
                                                                                    style="display: none; width: 100%; height: 100%;">
                                                                                    <source id="video-source" src=""
                                                                                        type="video/mp4">
                                                                                    Your browser does not support the video tag.
                                                                                </video>
                                                                            @endif
                                                                        </div>
                                                                        <div class="qr-code-overlay">
                                                                            <div id="qrcode-preview" class="qr-code-position {{ $campaign->qr_position ?? 'top-right' }}">
                                                                                <div class="qr-placeholder">
                                                                                    <i class="fas fa-qrcode"></i>
                                                                                    <span>QR Code</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="device-label">Driver's Tablet View</div>
                                                        </div>
                                                        <div class="qr-position-selector mt-3">
                                                            <h6>QR Code Position</h6>
                                                            <div class="position-options">
                                                                <input type="radio" id="qr-top-right" name="qr_position" value="top-right" 
                                                                       {{ old('qr_position', $campaign->qr_position ?? 'top-right') === 'top-right' ? 'checked' : '' }}>
                                                                <label for="qr-top-right" class="position-option">
                                                                    <div class="mini-screen">
                                                                        <div class="qr-dot top-right"></div>
                                                                    </div>
                                                                    <span>Top Right</span>
                                                                </label>
                                                                
                                                                <input type="radio" id="qr-top-left" name="qr_position" value="top-left"
                                                                       {{ old('qr_position', $campaign->qr_position) === 'top-left' ? 'checked' : '' }}>
                                                                <label for="qr-top-left" class="position-option">
                                                                    <div class="mini-screen">
                                                                        <div class="qr-dot top-left"></div>
                                                                    </div>
                                                                    <span>Top Left</span>
                                                                </label>
                                                                
                                                                <input type="radio" id="qr-bottom-right" name="qr_position" value="bottom-right"
                                                                       {{ old('qr_position', $campaign->qr_position) === 'bottom-right' ? 'checked' : '' }}>
                                                                <label for="qr-bottom-right" class="position-option">
                                                                    <div class="mini-screen">
                                                                        <div class="qr-dot bottom-right"></div>
                                                                    </div>
                                                                    <span>Bottom Right</span>
                                                                </label>
                                                                
                                                                <input type="radio" id="qr-bottom-left" name="qr_position" value="bottom-left"
                                                                       {{ old('qr_position', $campaign->qr_position) === 'bottom-left' ? 'checked' : '' }}>
                                                                <label for="qr-bottom-left" class="position-option">
                                                                    <div class="mini-screen">
                                                                        <div class="qr-dot bottom-left"></div>
                                                                    </div>
                                                                    <span>Bottom Left</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div id="qrcode" style="margin-top: 20px; display: none;"></div>
                                                        <span id="remove-media" class="btn btn-sm btn-danger mt-2" style="display: none;">Remove Media</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>

                        {{-- Step 1: Location --}}
                        <div class="step" id="step-1" style="display: none">
                            <section id="content-wrapper" class="p-0">
                                <div class="container-fluid p-0">
                                    <div class="row justify-content-end g-0">
                                        <div class="col-lg-7 order-lg-2">
                                            <div id='map'></div>
                                            <div id="driver-count" style="position: absolute; top: 10px; right: 10px; background: rgba(255,255,255,0.9); padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: bold; color: #333; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: none;">
                                                0 drivers in area
                                            </div>
                                        </div>
                                        <div class="col-lg-4 order-lg-1">
                                            <div class="loacat-section">
                                                <div class="choose-campgain">
                                                    <h2 class="text-start">Edit Location</h2>
                                                    <p class="text-start">Update where to target viewers</p>
                                                </div>
                                                <div class="location-list">
                                                    <div class="location-box active">
                                                        <div class="location-select">
                                                            <select class="form-select" id="location-select">
                                                                <option value="Queretaro">Santiago de Queretaro</option>
                                                                <option value="Mexico City">Mexico City</option>
                                                            </select>
                                                        </div>
                                                        <div class="location-address">
                                                            <label>Address</label>
                                                            <div class="address-box">
                                                                <p id="address">{{ $campaign->location_name ?? 'Click on map to set location' }}</p>
                                                            </div>
                                                            <input type="hidden" name="location_name" id="location_name" value="{{ old('location_name', $campaign->location_name) }}">
                                                            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $campaign->latitude) }}">
                                                            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $campaign->longitude) }}">
                                                            <div class="location-radius">
                                                                <label>Set Radius</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="js-range-slider"
                                                                        id="radius-slider" name="my_range"
                                                                        value="{{ old('radius_miles', $campaign->radius_miles ?? 10) }}" />
                                                                </div>
                                                                <div class="extra-controls">
                                                                    <input type="text" class="js-input" name="radius_miles"
                                                                        id="radius-value" value="{{ old('radius_miles', $campaign->radius_miles ?? 10) }}" />Miles
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

                        @if($campaign->status === 'draft')
                        {{-- Step 3: Priority (only for drafts) --}}
                        <div class="step" id="step-2" style="display: none">
                            <div class="container">
                                <div class="row justify-content-center">
                                    <div class="col-lg-10">
                                        <div class="choose-campgain">
                                            <h2 class="text-start">Select Priority</h2>
                                            <p class="text-start">Choose a package for your campaign</p>
                                        </div>
                                        <div class="row justify-content-between">
                                            @forelse($packages as $index => $package)
                                                @php
                                                    $backgroundImages = [
                                                        'assets/images/bg1.png',
                                                        'assets/images/bg2.png', 
                                                        'assets/images/bg3.png'
                                                    ];
                                                    $bgImage = $backgroundImages[$index % 3];
                                                @endphp
                                                <div class="col-lg-4 mb-3">
                                                    <input type="radio" id="package_{{ $package->id }}" name="package_id" value="{{ $package->id }}" 
                                                           class="package-selector d-none" 
                                                           data-cost-per-impression="{{ $package->cost_per_impression }}"
                                                           {{ old('package_id', $campaign->package_id) == $package->id ? 'checked' : ($index === 0 && !$campaign->package_id ? 'checked' : '') }}>
                                                    <label for="package_{{ $package->id }}" class="spend-box-wrapper">
                                                        <div class="spend-box" style="background-image: url('{{ asset($bgImage) }}'); padding: 12px; min-height: 100px;">
                                                            <div class="priority-badge" style="font-size: 11px; padding: 3px 8px;">{{ strtoupper($package->priority_text) }}</div>
                                                            <h6 style="margin: 6px 0; font-size: 15px; font-weight: bold;">{{ $package->name }}</h6>
                                                            <p style="margin: 5px 0; font-size: 12px; line-height: 1.3;">{{ \Illuminate\Support\Str::limit($package->description, 55) }}</p>
                                                            <div class="package-pricing" style="margin-top: 6px;">
                                                                <small class="text-muted" style="font-size: 11px; display: block; margin-bottom: 2px;">
                                                                    ${{ number_format($package->cost_per_impression, 4) }}/impression
                                                                </small>
                                                                <small class="text-muted" style="font-size: 11px;">
                                                                    ${{ number_format($package->cost_per_qr_scan, 2) }}/QR scan
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            @empty
                                                <div class="col-12">
                                                    <div class="alert alert-warning text-center">
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                        No packages available. Please contact support.
                                                    </div>
                                                </div>
                                            @endforelse
                                        </div>
                                        <div class="row align-items-center justify-content-between mt-4">
                                            <div class="col-lg-6">
                                                <div class="daily-budget-box">
                                                    <div class="col align-items-start ">
                                                        <h3>Daily Ad Spend</h3>
                                                        <p>Maximum amount you want to spend per day on this campaign.</p>
                                                    </div>
                                                    <div class="quantity">
                                                        <button type="button" class="minus" aria-label="Decrease"><img
                                                                src="{{ asset('assets/images/minus.svg') }}"></button>
                                                        <input type="number" class="input-box" name="daily_budget" id="daily_budget"
                                                            value="{{ old('daily_budget', $campaign->daily_budget ?? 1.00) }}"
                                                            min="1" max="10000" step="0.01">
                                                        <button type="button" class="plus" aria-label="Increase"><img
                                                                src="{{ asset('assets/images/plus.svg') }}"></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="">
                                                    <h4 id="daily-impressions">0</h4>
                                                   <b>Daily Impressions</b></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="">
                                                    <h4 id="monthly-impressions">0</h4>
                                                    <span><b>Monthly Impressions</b> </span>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="">
                                                    <h4 id="monthly-spend">0</h4>
                                                    <span><b>Monthly Spend</b></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        {{-- Daily budget input for published campaigns --}}
                        <input type="hidden" name="daily_budget" value="{{ $campaign->daily_budget }}">
                        @endif
                      
                        <div class="camp-grp-btn">
                            <a href="#" class="btn btn-secondary prev-btn" id="prev">Back</a>
                            <a href="#" class="btn btn-primary next-btn" id="next">Next</a>
                            @if($campaign->status === 'draft')
                                <button type="submit" name="action" value="save_draft" class="btn btn-outline-info" id="save-draft-btn">
                                    <i class="fas fa-save"></i> Update Draft
                                </button>
                                <button type="submit" name="action" value="publish" class="btn btn-success" id="publish-btn" style="display: none;">
                                    <i class="fas fa-rocket"></i> Publish Campaign
                                </button>
                            @else
                                <button type="submit" class="btn btn-primary next-btn" id="update-btn" style="display: none;">Update Campaign</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/ion-rangeslider/js/ion.rangeSlider.min.js"></script>
    <script src="https://davidshimjs.github.io/qrcodejs/qrcode.min.js"></script>
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.js'></script>
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.min.js"></script>
    <!-- Load Turf.js from multiple CDNs for reliability -->
    <script>
        // Function to load Turf.js from multiple sources
        function loadTurfJS() {
            const turfSources = [
                'https://cdnjs.cloudflare.com/ajax/libs/turf/6.5.0/turf.min.js',
                'https://unpkg.com/@turf/turf@6/turf.min.js',
                'https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js'
            ];
            
            function tryLoadTurf(index) {
                if (index >= turfSources.length) {
                    console.error('All Turf.js sources failed to load');
                    return;
                }
                
                const script = document.createElement('script');
                script.src = turfSources[index];
                script.onload = function() {
                    console.log('Turf.js loaded successfully from:', turfSources[index]);
                };
                script.onerror = function() {
                    console.error('Turf.js failed to load from:', turfSources[index]);
                    tryLoadTurf(index + 1);
                };
                document.head.appendChild(script);
            }
            
            tryLoadTurf(0);
        }
        
        // Start loading Turf.js
        loadTurfJS();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        // Updated Quantity Control Functionality
        (function() {
            const quantityContainer = document.querySelector(".quantity");
            if (!quantityContainer) return;
            
            const minusBtn = quantityContainer.querySelector(".minus");
            const plusBtn = quantityContainer.querySelector(".plus");
            const inputBox = quantityContainer.querySelector(".input-box");
            const maxValue = 10000; // Set maximum value for quantity input

            if (!inputBox) return;
            
            inputBox.max = maxValue; // Ensure max is set in input attributes

            function updateButtonStates() {
                const value = parseFloat(inputBox.value);
                if (minusBtn) minusBtn.disabled = value <= 1;
                if (plusBtn) plusBtn.disabled = value >= maxValue;
            }

            function adjustValue(change) {
                let value = parseFloat(inputBox.value) + change;
                value = Math.max(1, Math.min(value, maxValue)); // Ensure within bounds
                inputBox.value = value.toFixed(2);
                updateButtonStates();
                // Update impression counts when budget changes
                if (typeof updateImpressionCounts === 'function') {
                    updateImpressionCounts();
                }
            }

            if (minusBtn) minusBtn.onclick = () => adjustValue(-1);
            if (plusBtn) plusBtn.onclick = () => adjustValue(1);
            
            updateButtonStates();
        })();
    </script>
    <script>
        // SweetAlert2 Toast Helper Functions
        function showSuccessToast(message) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true
            });
        }

        function showErrorToast(message) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true
            });
        }

        function showWarningToast(message) {
            Swal.fire({
                icon: 'warning',
                title: 'Warning!',
                text: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true
            });
        }

        // Function to calculate and update impression counts
        function updateImpressionCounts() {
            const budgetInput = document.querySelector('input[name="daily_budget"]') || document.querySelector('.input-box');
            const selectedPackage = document.querySelector('input[name="package_id"]:checked');
            
            if (!budgetInput || !selectedPackage) {
                console.error('Missing budget input or selected package', {
                    budgetInput: budgetInput,
                    selectedPackage: selectedPackage
                });
                return;
            }
            
            const budget = parseFloat(budgetInput.value) || 0;
            const costPerImpression = parseFloat(selectedPackage.dataset.costPerImpression) || 0;
            
            console.log('Updating impression counts:', {
                budget: budget,
                costPerImpression: costPerImpression,
                packageId: selectedPackage.value,
                dataAttributes: selectedPackage.dataset
            });
            
            if (costPerImpression > 0 && budget > 0) {
                // Calculate total impressions based on budget and cost per impression
                const totalImpressions = Math.floor(budget / costPerImpression);
                
                // Get package priority level for frequency calculation
                const packagePriority = parseInt(selectedPackage.value) || 1;
                let displayFrequencyMultiplier = 1;
                
                // Priority multipliers based on package level (matching AdService logic)
                switch(packagePriority) {
                    case 1: // Basic Package ($0.0050 per impression)
                        displayFrequencyMultiplier = 0.3; // 30% frequency - basic visibility
                        break;
                    case 2: // Priority Package ($0.0150 per impression)  
                        displayFrequencyMultiplier = 0.6; // 60% frequency - enhanced visibility
                        break;
                    case 3: // Enterprise Package ($0.0300 per impression)
                        displayFrequencyMultiplier = 1.0; // 100% frequency - maximum visibility
                        break;
                }
                
                // Calculate realistic daily and monthly impressions based on driver activity
                const theoreticalDaily = Math.floor(totalImpressions / 30);
                const realisticDaily = Math.floor(theoreticalDaily * displayFrequencyMultiplier);
                const realisticMonthly = realisticDaily * 30;
                
                // Update display with calculated values
                document.getElementById('daily-impressions').textContent = realisticDaily.toLocaleString();
                document.getElementById('monthly-impressions').textContent = realisticMonthly.toLocaleString();
                document.getElementById('monthly-spend').textContent = '$' + budget.toLocaleString();
            } else {
                document.getElementById('daily-impressions').textContent = '0';
                document.getElementById('monthly-impressions').textContent = '0';
                document.getElementById('monthly-spend').textContent = '$0';
            }
        }

        function showInfoToast(message) {
            Swal.fire({
                icon: 'info',
                title: 'Info',
                text: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }
        
        // Function to wait for all necessary libraries to load
        function waitForLibraries(callback) {
            let attempts = 0;
            const maxAttempts = 100; // Increased attempts
            const checkInterval = 50;  // Faster checking
            
            function check() {
                attempts++;
                console.log(`Library check attempt ${attempts}:`, {
                    turf: typeof turf,
                    mapboxgl: typeof mapboxgl, 
                    Swal: typeof Swal,
                    MapboxGeocoder: typeof MapboxGeocoder
                });
                
                if (typeof turf !== 'undefined' && typeof mapboxgl !== 'undefined' && 
                    typeof Swal !== 'undefined' && typeof MapboxGeocoder !== 'undefined') {
                    console.log('All libraries loaded successfully!');
                    callback();
                } else if (attempts < maxAttempts) {
                    setTimeout(check, checkInterval);
                } else {
                    console.error('Failed to load required libraries after', maxAttempts, 'attempts');
                    console.log('Final status - turf:', typeof turf, 'mapboxgl:', typeof mapboxgl, 
                                'Swal:', typeof Swal, 'MapboxGeocoder:', typeof MapboxGeocoder);
                    
                    // Try to proceed anyway with available libraries
                    if (typeof mapboxgl !== 'undefined') {
                        console.warn('Proceeding with limited functionality');
                        callback();
                    } else {
                        showErrorToast('Map libraries failed to load. Please refresh the page.');
                    }
                }
            }
            check();
        }

        // Initialize Mapbox with lazy loading
        var map, marker;
        // Use existing campaign coordinates or default to Mexico City
        const initialLng = {{ $campaign->longitude ?? -99.1332 }};
        const initialLat = {{ $campaign->latitude ?? 19.4326 }};
        
        // Define required global map functions first
        window.mapInitialized = false;
        window.circleCoordinates = [initialLng, initialLat];
        window.radius = {{ $campaign->radius ?? 5 }}; // Default radius in miles
        window.driverMarkers = []; // Array to store driver markers
        
        // Define global map initialization function
        window.initializeMap = function() {
            console.log('Global initializeMap called from edit page');
            if (window.mapInitialized && window.map) {
                console.log('Map already initialized, skipping');
                return;
            }
            
            // Ensure libraries are loaded
            waitForLibraries(function() {
                try {
                    // Check if map container exists
                    const mapContainer = document.getElementById('map');
                    if (!mapContainer) {
                        console.error('Map container not found');
                        return;
                    }
                    
                    console.log('Initializing edit map with center:', [initialLng, initialLat]);
                    
                    // Set Mapbox access token
                    mapboxgl.accessToken = 'pk.eyJ1IjoibXVzdGFuc2lybWFrZGEiLCJhIjoiY20yYzNpd213MHJhNTJqcXduNjU4ZGFkdyJ9.qnsW91lfIZ1EniLcPlAEkQ';
                    
                    // Define fallback coordinates (New York City) if initialLat/initialLng are invalid
                    let centerCoords = [initialLng, initialLat];
                    if (!initialLng || !initialLat || isNaN(initialLng) || isNaN(initialLat)) {
                        console.warn('Invalid initial coordinates, using fallback location');
                        centerCoords = [-73.935242, 40.730610]; // NYC as fallback
                    }
                    
                    // Create map with robust options
                    window.map = new mapboxgl.Map({
                        container: 'map',
                        style: 'mapbox://styles/mapbox/streets-v11',
                        center: centerCoords,
                        zoom: 10,
                        minZoom: 2,
                        maxZoom: 18,
                        failIfMajorPerformanceCaveat: false,
                        preserveDrawingBuffer: true
                    });
                    
                    // Add map events with retry mechanism
                    let loadAttempts = 0;
                    const maxAttempts = 3;
                    
                    window.map.on('load', function() {
                        console.log('Map loaded successfully');
                        window.mapInitialized = true;
                        
                        // Setup map features with error handling
                        try {
                            window.setupMapFeatures();
                        } catch (e) {
                            console.error('Error setting up map features:', e);
                        }
                        
                        // Load drivers data after map initializes
                        setTimeout(() => {
                            window.loadDriversData();
                        }, 1000); // Add a small delay to ensure map is fully ready
                    });
                    
                    // Add error handler
                    window.map.on('error', function(e) {
                        console.error('Map error:', e);
                    });
                    
                } catch(e) {
                    console.error('Error initializing map:', e);
                    Swal.fire({
                        icon: 'error',
                        title: 'Map Error',
                        text: 'Failed to initialize map: ' + e.message
                    });
                }
            });
        };
        
        // Setup map features (marker, circle, etc.)
        window.setupMapFeatures = function() {
            try {
                console.log('Setting up map features...');
                
                // Create a marker
                window.marker = new mapboxgl.Marker({
                    draggable: true,
                    color: '#FF5722' // Orange color for the main marker
                })
                .setLngLat([initialLng, initialLat])
                .addTo(window.map);
                
                // Setup marker event listeners
                window.marker.on('dragend', window.updateMarkerPosition);
                
                // Setup map click event
                window.map.on('click', function(e) {
                    const coordinates = e.lngLat;
                    console.log('Map clicked at:', coordinates);
                    
                    // Move marker to clicked position
                    window.marker.setLngLat(coordinates);
                    window.updateMarkerPosition();
                });
                
                // Draw initial circle
                window.drawCircle(window.radius);
                
                console.log('Map features set up successfully');
            } catch(e) {
                console.error('Error in setupMapFeatures:', e);
            }
        };
        
        // Update marker position and redraw circle
        window.updateMarkerPosition = function() {
            try {
                if (!window.marker) return;
                
                const lngLat = window.marker.getLngLat();
                console.log('Marker position updated:', lngLat);
                
                // Update global coordinates
                window.circleCoordinates = [lngLat.lng, lngLat.lat];
                
                // Update the hidden form inputs
                if (document.getElementById('latitude')) {
                    document.getElementById('latitude').value = lngLat.lat;
                }
                if (document.getElementById('longitude')) {
                    document.getElementById('longitude').value = lngLat.lng;
                }
                
                // Get and display location name
                window.getLocationName(lngLat);
                
                // Redraw circle at new position
                window.drawCircle(window.radius);
            } catch(e) {
                console.error('Error in updateMarkerPosition:', e);
            }
        };
        
        // Fallback function to draw a circle without Turf.js
        window.drawCircleFallback = function(center, radiusMiles) {
            if (!window.map || !window.map.loaded()) return;
            
            console.log('Using fallback method to draw circle');
            
            // Remove existing circle layer if it exists
            if (window.map.getSource('circle-source')) {
                window.map.removeLayer('circle-fill');
                window.map.removeLayer('circle-outline');
                window.map.removeSource('circle-source');
            }
            
            // Convert radius from miles to approximate pixels at this zoom level
            const pixelsPerMile = 2000 / Math.pow(2, window.map.getZoom());
            const radiusPixels = radiusMiles * pixelsPerMile;
            
            // Create a simple circle with points
            const points = 64;
            const coordinates = [];
            
            for (let i = 0; i < points; i++) {
                const angle = (i / points) * 2 * Math.PI;
                const lng = center[0] + (radiusPixels * Math.cos(angle)) / (111320 * Math.cos(center[1] * Math.PI / 180));
                const lat = center[1] + (radiusPixels * Math.sin(angle)) / 111320;
                coordinates.push([lng, lat]);
            }
            
            // Close the circle
            coordinates.push(coordinates[0]);
            
            // Create a GeoJSON object for the circle
            const circleData = {
                'type': 'Feature',
                'geometry': {
                    'type': 'Polygon',
                    'coordinates': [coordinates]
                },
                'properties': {}
            };
            
            // Add the circle to the map
            window.map.addSource('circle-source', {
                'type': 'geojson',
                'data': circleData
            });
            
            // Add a fill layer for the circle
            window.map.addLayer({
                'id': 'circle-fill',
                'type': 'fill',
                'source': 'circle-source',
                'paint': {
                    'fill-color': '#3388ff',
                    'fill-opacity': 0.2
                }
            });
            
            // Add an outline layer for the circle
            window.map.addLayer({
                'id': 'circle-outline',
                'type': 'line',
                'source': 'circle-source',
                'paint': {
                    'line-color': '#3388ff',
                    'line-width': 2
                }
            });
        };
        
        // Get location name from coordinates
        window.getLocationName = function(lngLat) {
            try {
                console.log('Getting location name for:', lngLat);
                
                // Use Mapbox Geocoding API to get location name
                const apiUrl = `https://api.mapbox.com/geocoding/v5/mapbox.places/${lngLat.lng},${lngLat.lat}.json?access_token=${mapboxgl.accessToken}`;
                
                fetch(apiUrl)
                    .then(response => response.json())
                    .then(data => {
                        if (data.features && data.features.length > 0) {
                            const locationName = data.features[0].place_name;
                            console.log('Location name:', locationName);
                            
                            // Update location name display
                            if (document.getElementById('location-name')) {
                                document.getElementById('location-name').textContent = locationName;
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching location name:', error);
                    });
            } catch(e) {
                console.error('Error in getLocationName:', e);
            }
        };
        
        // Load and display drivers as pins on the map
        window.loadDriversData = function() {
            try {
                // Initialize driver markers array if it doesn't exist
                if (!window.driverMarkers) {
                    window.driverMarkers = [];
                }
                
                // Clear existing driver markers
                if (window.driverMarkers.length > 0) {
                    window.driverMarkers.forEach(marker => {
                        try {
                            marker.remove();
                        } catch (err) {
                            console.warn('Could not remove marker:', err);
                        }
                    });
                    window.driverMarkers = [];
                }
                
                if (!window.map || !window.mapInitialized) {
                    console.log('Map not initialized yet, scheduling retry in 2 seconds');
                    setTimeout(() => window.loadDriversData(), 2000);
                    return;
                }
                
                // Use current map center if circle coordinates not defined
                let lng, lat;
                if (window.circleCoordinates && window.circleCoordinates.length === 2) {
                    lng = window.circleCoordinates[0];
                    lat = window.circleCoordinates[1];
                } else {
                    const center = window.map.getCenter();
                    lng = center.lng;
                    lat = center.lat;
                    // Store for later use
                    window.circleCoordinates = [lng, lat];
                }
                
                const radius = window.radius || 5;
                
                console.log('Loading driver data near:', lng, lat, 'with radius:', radius);
                
                // Add loading indicator
                const loadingEl = document.createElement('div');
                loadingEl.id = 'map-loading-indicator';
                loadingEl.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
                loadingEl.style.position = 'absolute';
                loadingEl.style.top = '10px';
                loadingEl.style.right = '10px';
                loadingEl.style.zIndex = '1000';
                document.getElementById('map').appendChild(loadingEl);
                
                // Make API request to get drivers with timeout
                const fetchTimeout = setTimeout(() => {
                    console.warn('Driver fetch request timed out');
                    if (loadingEl.parentNode) {
                        loadingEl.parentNode.removeChild(loadingEl);
                    }
                }, 20000); // 20 second timeout
                
                fetch(`/api/campaigns/nearby-drivers?longitude=${lng}&latitude=${lat}&radius=${radius}`)
                    .then(response => {
                        clearTimeout(fetchTimeout);
                        if (!response.ok) {
                            throw new Error(`HTTP error ${response.status}: ${response.statusText}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Remove loading indicator
                        const loadingEl = document.getElementById('map-loading-indicator');
                        if (loadingEl && loadingEl.parentNode) {
                            loadingEl.parentNode.removeChild(loadingEl);
                        }
                        
                        if (data.success && data.drivers) {
                            console.log(`Found ${data.drivers.length} drivers within ${radius} miles`);
                            
                            // Add markers for each driver
                            data.drivers.forEach(driver => {
                                try {
                                    // Create custom driver marker
                                    const el = document.createElement('div');
                                    el.className = 'driver-marker';
                                    
                                    // Use status color or default to green
                                    const color = driver.status_color || '#4CAF50';
                                    
                                    // Style the marker
                                    el.style.backgroundColor = color;
                                    el.style.width = '16px';
                                    el.style.height = '16px';
                                    el.style.borderRadius = '50%';
                                    el.style.border = '2px solid #fff';
                                    el.style.boxShadow = '0 0 5px rgba(0,0,0,0.3)';
                                    
                                    // Create popup HTML with more information
                                    const popupHtml = `
                                        <div class="driver-popup">
                                            <h6 style="margin-bottom: 5px;"><i class="fas fa-car"></i> Driver #${driver.id}</h6>
                                            <div class="driver-details">
                                                <p style="margin-bottom: 3px;"><strong>Name:</strong> ${driver.name || 'Unknown'}</p>
                                                <p style="margin-bottom: 3px;"><strong>Vehicle:</strong> ${driver.vehicle || 'N/A'}</p>
                                                <p style="margin-bottom: 3px;"><strong>Status:</strong> <span style="color: ${color}">${driver.status || 'Unknown'}</span></p>
                                                <p style="margin-bottom: 3px;"><strong>Distance:</strong> ${driver.distance ? driver.distance + ' mi' : 'Unknown'}</p>
                                                <p style="margin-bottom: 0;"><strong>Last Update:</strong> ${driver.last_update || 'Unknown'}</p>
                                            </div>
                                        </div>
                                    `;
                                    
                                    // Create and add marker to map
                                    const marker = new mapboxgl.Marker(el)
                                        .setLngLat([driver.longitude, driver.latitude])
                                        .setPopup(new mapboxgl.Popup({ offset: 25 })
                                            .setHTML(popupHtml))
                                        .addTo(window.map);
                                    
                                    window.driverMarkers.push(marker);
                                } catch (err) {
                                    console.warn('Error creating marker for driver:', driver.id, err);
                                }
                            });
                            
                            // Update driver count display
                            if (document.getElementById('driver-count')) {
                                document.getElementById('driver-count').textContent = data.drivers.length;
                            }
                            
                            // Highlight the area with drivers if there are any
                            if (data.drivers.length > 0 && window.map && window.drawCircle) {
                                window.drawCircle();
                            }
                        } else {
                            console.error('Error loading driver data:', data.message || 'Unknown error');
                            
                            // Show error message to user
                            if (document.getElementById('driver-count')) {
                                document.getElementById('driver-count').textContent = 'Error loading drivers';
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching driver data:', error);
                        
                        // Remove loading indicator
                        const loadingEl = document.getElementById('map-loading-indicator');
                        if (loadingEl && loadingEl.parentNode) {
                            loadingEl.parentNode.removeChild(loadingEl);
                        }
                        
                        // Show error message to user
                        if (document.getElementById('driver-count')) {
                            document.getElementById('driver-count').textContent = 'Failed to load';
                        }
                        
                        // Retry once after a delay
                        if (!window.driverLoadRetried) {
                            window.driverLoadRetried = true;
                            setTimeout(() => {
                                console.log('Retrying driver data load...');
                                window.loadDriversData();
                            }, 5000);
                        }
                    });
            } catch(e) {
                console.error('Error loading driver data:', e);
            }
        };
        
        // Draw circle with given radius
        window.drawCircle = function(radius) {
            // Check for recursion prevention
            if (window._drawingCircle) {
                console.warn('Preventing recursive drawCircle call');
                return;
            }
            
            // Set recursion guard flag
            window._drawingCircle = true;
            
            try {
                console.log('Drawing circle with radius:', radius);
                window.radius = radius;
                
                // Safety check for map
                if (!window.map || !window.map.loaded()) {
                    console.warn('Map not ready yet, skipping circle drawing');
                    window._drawingCircle = false;
                    return;
                }
                
                // Remove existing circle source and layer if they exist
                try {
                    if (window.map && window.map.getSource('circle-source')) {
                        window.map.removeLayer('circle-fill');
                        window.map.removeLayer('circle-outline');
                        window.map.removeSource('circle-source');
                    }
                } catch (layerError) {
                    console.warn('Error removing existing layers:', layerError);
                }
                
                // Check if turf is defined
                if (typeof turf === 'undefined') {
                    console.error('Turf.js is not loaded yet');
                    window._drawingCircle = false;
                    window.drawCircleFallback(window.circleCoordinates, radius);
                    return;
                }
                
                // Create circle using turf.js
                const circle = turf.circle(window.circleCoordinates, radius, {
                    steps: 80,
                    units: 'miles'
                });
                
                // Add the circle source and layers
                window.map.addSource('circle-source', {
                    type: 'geojson',
                    data: circle
                });
                
                // Add a fill layer for the circle
                window.map.addLayer({
                    id: 'circle-fill',
                    type: 'fill',
                    source: 'circle-source',
                    paint: {
                        'fill-color': '#3887be',
                        'fill-opacity': 0.3
                    }
                });
                
                // Add an outline layer for the circle
                window.map.addLayer({
                    id: 'circle-outline',
                    type: 'line',
                    source: 'circle-source',
                    paint: {
                        'line-color': '#3887be',
                        'line-width': 2
                    }
                });
                
                // Update the radius display
                if (document.getElementById('radius-display')) {
                    document.getElementById('radius-display').innerText = radius;
                }
                
                // Refresh driver data after circle updates
                window.loadDriversData();
                
                console.log('Circle drawn successfully');
            } catch(e) {
                console.error('Error in drawCircle:', e);
                window.drawCircleFallback(window.circleCoordinates, radius);
            } finally {
                // Always clear the recursion guard
                window._drawingCircle = false;
            }
        };
                console.error('Mapbox GL JS not loaded');
                showErrorToast('Map library failed to load. Please refresh the page.');
                return;
            }
            
            mapboxgl.accessToken = 'pk.eyJ1IjoibXVzdGFuc2lybWFrZGEiLCJhIjoiY20yYzNpd213MHJhNTJqcXduNjU4ZGFkdyJ9.qnsW91lfIZ1EniLcPlAEkQ';
            
            // Clean up existing map if any
            if (map) {
                try {
                    map.remove();
                    console.log('Removed existing map instance');
                } catch (e) {
                    console.warn('Error removing existing map:', e);
                }
                map = null;
            }
            
            try {
                map = new mapboxgl.Map({
                    container: 'map',
                    style: 'mapbox://styles/mapbox/streets-v11',
                    center: [initialLng, initialLat],
                    zoom: 10
                });
                
                map.on('load', function() {
                    console.log('Edit map loaded successfully');
                    setupMapFeatures();
                });
                
                map.on('error', function(e) {
                    console.error('Edit map loading error:', e);
                });
                
                mapInitialized = true;
            } catch (error) {
                console.error('Edit map initialization error:', error);
                showErrorToast('Failed to initialize map: ' + error.message);
            }
        }
        
        function setupMapFeatures() {
            // Add geocoder search control
            const geocoder = new MapboxGeocoder({
                accessToken: mapboxgl.accessToken,
                mapboxgl: mapboxgl,
                placeholder: 'Search for places...',
                bbox: [-180, -90, 180, 90],
                proximity: [initialLng, initialLat]
            });
            
            map.addControl(geocoder, 'top-left');

        // Handle geocoder result
        geocoder.on('result', function(e) {
            const coordinates = e.result.center;
            const placeName = e.result.place_name;
            
            // Update marker position
            marker.setLngLat(coordinates);
            
            // Update form fields
            document.getElementById('latitude').value = coordinates[1];
            document.getElementById('longitude').value = coordinates[0];
            document.getElementById('location_name').value = placeName;
            document.getElementById('address').innerText = placeName;
            
            // Update circle coordinates and redraw
            circleCoordinates = coordinates;
            drawCircle(radius);
            });
            
            // Create a marker
            marker = new mapboxgl.Marker({
                    draggable: true
                })
                .setLngLat([initialLng, initialLat])
                .addTo(map);

            // Setup marker event listeners
            marker.on('dragend', updateMarkerPosition);
            
            // Setup map click events
            map.on('click', function(e) {
                // Check if the clicked location is within Mexico
                if (!isLocationInMexico(e.lngLat.lat, e.lngLat.lng)) {
                    showErrorToast('Campaign locations are currently only available within Mexico. Please select a location within Mexico.');
                    return;
                }
                
                marker.setLngLat(e.lngLat);
                updateMarkerPosition();
            });
            
            // Initial setup
            updateMarkerPosition();
        }

        // Create a circle layer for the radius
        var radiusLayerId = 'radius-circle';
        var radius = {{ $campaign->radius_miles ?? 10 }}; // Use existing radius or default
        var circleCoordinates = [initialLng, initialLat];

        // Function to check if coordinates are within Mexico
        function isLocationInMexico(lat, lng) {
            // Mexico's approximate bounding box
            return lat >= 14.32 && lat <= 32.72 && lng >= -118.45 && lng <= -86.70;
        }

        // Function to update marker position and address
        function updateMarkerPosition() {
            var lngLat = marker.getLngLat();
            
            // Check if the location is within Mexico
            if (!isLocationInMexico(lngLat.lat, lngLat.lng)) {
                // Show error and reset to previous valid location
                showErrorToast('Campaign locations are currently only available within Mexico. Please select a location within Mexico.');
                marker.setLngLat(circleCoordinates); // Reset to previous valid position
                return;
            }
            
            circleCoordinates = [lngLat.lng, lngLat.lat];
            
            // Update hidden form fields
            document.getElementById('latitude').value = lngLat.lat;
            document.getElementById('longitude').value = lngLat.lng;
            
            getLocationName(circleCoordinates);
            drawCircle(radius);
            loadNearbyDrivers(lngLat.lat, lngLat.lng, radius); // Load nearby drivers
        }

        // Function to get location name using Mapbox Geocoding API
        function getLocationName(lngLat) {
            const url = `https://api.mapbox.com/geocoding/v5/mapbox.places/${lngLat[0]},${lngLat[1]}.json?access_token=${mapboxgl.accessToken}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const addressElement = document.getElementById('address');
                    const locationNameInput = document.getElementById('location_name');
                    if (data.features && data.features.length > 0) {
                        addressElement.textContent = data.features[0].place_name;
                        locationNameInput.value = data.features[0].place_name;
                    } else {
                        addressElement.textContent = 'Location not found';
                        locationNameInput.value = 'Location not found';
                    }
                })
                .catch(error => {
                    console.error('Error fetching location name:', error);
                    showErrorToast("Unable to fetch location data. Please try again.");
                });
        }

        // Function to draw a circle on the map
        function drawCircle(radius) {
            try {
                console.log('Drawing circle with radius:', radius, 'at coordinates:', circleCoordinates);
                
                // Try Turf.js first, fallback if not available
                if (typeof turf !== 'undefined' && turf.circle) {
                    console.log('Using Turf.js for circle drawing');
                } else {
                    console.log('Turf.js not available - using fallback circle');
                    drawFallbackCircle(radius);
                    return;
                }
                
                var radiusInKm = radius * 1.60934; // Convert miles to km for turf
                var circle = turf.circle(circleCoordinates, radiusInKm, {
                    steps: 64,
                    units: 'kilometers',
                });
                
                console.log('Circle created:', circle);

            // Remove the existing circle layers if they exist
            if (map.getLayer(radiusLayerId)) {
                map.removeLayer(radiusLayerId);
            }
            if (map.getLayer(radiusLayerId + '-border')) {
                map.removeLayer(radiusLayerId + '-border');
            }
            if (map.getSource(radiusLayerId)) {
                map.removeSource(radiusLayerId);
            }

            // Add the circle as a new source
            map.addSource(radiusLayerId, {
                type: 'geojson',
                data: circle
            });

            // Add a new layer to visualize the radius
            map.addLayer({
                id: radiusLayerId,
                type: 'fill',
                source: radiusLayerId,
                layout: {},
                paint: {
                    'fill-color': 'rgba(0, 123, 255, 0.2)',
                    'fill-opacity': 0.2
                }
            });
            
            // Add circle border
            map.addLayer({
                id: radiusLayerId + '-border',
                type: 'line',
                source: radiusLayerId,
                layout: {},
                paint: {
                    'line-color': '#007bff',
                    'line-width': 2,
                    'line-opacity': 0.8
                }
            });
            
            console.log('Circle layer added successfully');
            } catch (error) {
                console.error('Error drawing circle:', error);
                showErrorToast('Error drawing radius circle: ' + error.message);
            }
        }

        // Store driver markers for cleanup
        var driverMarkers = [];
        var driverFetchTimeout;
        var currentDriverRequest;

        // Function to load and display nearby drivers (with debouncing)
        function loadNearbyDrivers(latitude, longitude, radiusKm) {
            // Cancel any pending requests
            if (currentDriverRequest) {
                currentDriverRequest.abort();
            }
            
            // Clear any existing timeout
            if (driverFetchTimeout) {
                clearTimeout(driverFetchTimeout);
            }
            
            // Show loading state
            showDriverCount('Loading...', '');
            
            // Debounce the API call by 150ms for faster response
            driverFetchTimeout = setTimeout(async () => {
                await performDriverFetch(latitude, longitude, radiusKm);
            }, 150);
        }

        // Function to perform the actual driver fetch (async, non-blocking)
        async function performDriverFetch(latitude, longitude, radiusKm) {
            // Clear existing driver markers
            clearDriverMarkers();

            // Create AbortController for request cancellation
            const controller = new AbortController();
            currentDriverRequest = controller;

            try {
                // Make API call to get nearby drivers with timeout (async, non-blocking)
                const response = await Promise.race([
                    fetch(`{{ route('campaigns.nearby-drivers') }}?latitude=${latitude}&longitude=${longitude}&radius=${radiusKm}`, {
                        signal: controller.signal,
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }),
                    // Timeout promise
                    new Promise((_, reject) => 
                        setTimeout(() => reject(new Error('Request timeout')), 3000)
                    )
                ]);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                
                // Clear the current request reference
                currentDriverRequest = null;
                
                if (data.success && data.drivers.length > 0) {
                    console.log(`Found ${data.drivers.length} nearby drivers`);
                    
                    // Clear existing markers before adding new ones
                    clearDriverMarkers();
                    
                    // Process drivers in batches to avoid UI blocking
                    await processDriversInBatches(data.drivers);
                    
                    // Show driver count in UI
                    showDriverCount(data.drivers.length, data.total_count);
                    
                    // Debug markers after loading
                    setTimeout(() => debugMarkers(), 1000);
                } else {
                    console.log('No nearby drivers found');
                    clearDriverMarkers();
                    showDriverCount(0, 0);
                }
            } catch (error) {
                // Clear the current request reference
                currentDriverRequest = null;
                
                // Don't show error for aborted requests
                if (error.name === 'AbortError') {
                    console.log('Driver fetch request was cancelled');
                    return;
                }
                
                console.error('Error loading nearby drivers:', error);
                showDriverCount('Error loading drivers', '');
                
                // Show a less intrusive error message
                if (typeof showErrorToast === 'function') {
                    showErrorToast('Unable to load nearby drivers');
                }
            }
        }

        // Process drivers in batches to prevent UI blocking
        async function processDriversInBatches(drivers) {
            const batchSize = 10; // Process 10 drivers at a time
            
            for (let i = 0; i < drivers.length; i += batchSize) {
                const batch = drivers.slice(i, i + batchSize);
                
                // Process batch
                batch.forEach(driver => {
                    addDriverMarker(driver);
                });
                
                // Yield control to browser to prevent UI blocking 
                if (i + batchSize < drivers.length) {
                    await new Promise(resolve => setTimeout(resolve, 0));
                }
            }
        }
        }

        // Function to add a driver marker to the map
        function addDriverMarker(driver) {
            // Create attractive driver marker element
            var el = document.createElement('div');
            el.className = 'driver-marker';
            el.style.cssText = `
                width: 32px;
                height: 32px;
                border-radius: 50%;
                background: linear-gradient(135deg, ${driver.status_color} 0%, ${driver.status_color}dd 100%);
                border: 2px solid white;
                box-shadow: 0 2px 8px rgba(0,0,0,0.3), 0 1px 3px rgba(0,0,0,0.2);
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.2s ease;
                position: relative;
                overflow: hidden;
            `;
            
            // Create professional taxi/cab icon for active drivers
            var taxiIcon = document.createElement('div');
            taxiIcon.style.cssText = `
                width: 28px;
                height: 28px;
                display: flex;
                align-items: center;
                justify-content: center;
            `;
            
            // Create SVG taxi icon with proper styling
            var svgIcon = '';
            if (driver.status === 'available') {
                // Professional taxi icon for available tablets
                svgIcon = `
                    <svg viewBox="0 0 24 24" width="28" height="28" fill="white">
                        <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11C5.84 5 5.28 5.42 5.08 6.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/>
                        <circle cx="12" cy="3" r="1.5" fill="white"/>
                        <text x="12" y="4" text-anchor="middle" font-size="2" fill="${driver.status_color}" font-weight="bold">●</text>
                    </svg>
                `;
            } else if (driver.status === 'busy') {
                // Taxi with busy indicator
                svgIcon = `
                    <svg viewBox="0 0 24 24" width="28" height="28" fill="white">
                        <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11C5.84 5 5.28 5.42 5.08 6.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/>
                        <path d="M19 7L20 6L18 4L17 5" stroke="white" stroke-width="1" fill="none"/>
                    </svg>
                `;
            } else {
                // Offline taxi
                svgIcon = `
                    <svg viewBox="0 0 24 24" width="28" height="28" fill="white">
                        <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11C5.84 5 5.28 5.42 5.08 6.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/>
                        <line x1="4" y1="4" x2="20" y2="20" stroke="white" stroke-width="1.5"/>
                    </svg>
                `;
            }
            
            taxiIcon.innerHTML = svgIcon;
            el.appendChild(taxiIcon);
            
            // Add animation for available tablets  
            if (driver.is_active) {
                el.style.animation = 'pulse-green 2s infinite';
            }
            
            // Add hover effect with proper positioning
            el.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.1)';
                this.style.transformOrigin = 'center center';
                this.style.position = 'relative';
                this.style.zIndex = '1001';
                this.style.animation = 'none'; // Stop pulsing on hover
            });
            
            el.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
                this.style.transformOrigin = 'center center';
                this.style.position = 'relative';
                this.style.zIndex = '1000';
                // Restore animation
                if (driver.status === 'available') {
                    this.style.animation = 'pulse-green 2s infinite';
                } else if (driver.status === 'busy') {
                    this.style.animation = 'pulse-orange 3s infinite';
                }
            });

            // Create enhanced popup for driver info
            var popup = new mapboxgl.Popup({ 
                offset: 30,
                closeButton: true,
                closeOnClick: false
            }).setHTML(`
                <div style="padding: 12px; min-width: 220px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
                    <div style="display: flex; align-items: center; margin-bottom: 10px;">
                        <div style="width: 12px; height: 12px; border-radius: 50%; background: ${driver.status_color}; margin-right: 8px;"></div>
                        <h4 style="margin: 0; font-size: 16px; font-weight: 600; color: #1f2937;">${driver.name}</h4>
                    </div>
                    <div style="space-y: 6px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                            <span style="font-size: 13px; color: #6b7280; font-weight: 500;">Status:</span>
                            <span style="font-size: 13px; color: ${driver.status_color}; font-weight: 600; text-transform: capitalize;">${driver.status}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                            <span style="font-size: 13px; color: #6b7280; font-weight: 500;">Vehicle:</span>
                            <span style="font-size: 13px; color: #374151; font-weight: 500;">${driver.vehicle}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                            <span style="font-size: 13px; color: #6b7280; font-weight: 500;">Distance:</span>
                            <span style="font-size: 13px; color: #374151; font-weight: 600;">${driver.distance} miles</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="font-size: 13px; color: #6b7280; font-weight: 500;">Last Update:</span>
                            <span style="font-size: 13px; color: #374151;">${driver.last_update}</span>
                        </div>
                    </div>
                </div>
            `);

            // Create marker
            console.log('Creating marker for driver:', { id: driver.id, lat: driver.latitude, lng: driver.longitude, is_active: driver.is_active });
            
            var marker = new mapboxgl.Marker(el)
                .setLngLat([driver.longitude, driver.latitude])
                .setPopup(popup)
                .addTo(map);

            console.log('Marker created and added to map:', marker);

            // Store marker for cleanup
            driverMarkers.push(marker);
        }

        // Function to clear all driver markers
        function clearDriverMarkers() {
            driverMarkers.forEach(marker => {
                marker.remove();
            });
            driverMarkers = [];
        }

        // Debug function to check marker visibility
        function debugMarkers() {
            console.log('Total markers:', driverMarkers.length);
            console.log('Markers on map:', driverMarkers.map(m => ({ 
                lngLat: m.getLngLat(), 
                element: m.getElement() 
            })));
        }

        // Function to show driver count in UI
        function showDriverCount(nearbyCount, totalCount) {
            var countElement = document.getElementById('driver-count');
            if (countElement) {
                countElement.textContent = `${nearbyCount} drivers in area`;
                countElement.style.display = 'block'; // Always show the count, even if 0
            }
        }

        // Fallback circle drawing without Turf.js using approximate polygon
        function drawFallbackCircle(radius) {
            try {
                console.log('Drawing fallback circle with radius:', radius);
                
                // Remove existing circle layers
                if (map.getLayer(radiusLayerId)) {
                    map.removeLayer(radiusLayerId);
                }
                if (map.getLayer(radiusLayerId + '-border')) {
                    map.removeLayer(radiusLayerId + '-border');
                }
                if (map.getSource(radiusLayerId)) {
                    map.removeSource(radiusLayerId);
                }

                // Create a simple circle approximation using a polygon
                var center = circleCoordinates;
                var radiusInKm = radius * 1.60934; // Convert miles to km
                var points = [];
                var numPoints = 64;
                
                for (var i = 0; i < numPoints; i++) {
                    var angle = (i / numPoints) * 2 * Math.PI;
                    // Use proper spherical projection to maintain circular shape
                    var latRadians = center[1] * Math.PI / 180;
                    var dx = radiusInKm * Math.cos(angle) / (111.32 * Math.cos(latRadians));
                    var dy = radiusInKm * Math.sin(angle) / 111.32;
                    points.push([center[0] + dx, center[1] + dy]);
                }
                points.push(points[0]); // Close the polygon

                var circleGeoJSON = {
                    type: 'Feature',
                    geometry: {
                        type: 'Polygon',
                        coordinates: [points]
                    }
                };

                // Add the circle as a source
                map.addSource(radiusLayerId, {
                    type: 'geojson',
                    data: circleGeoJSON
                });

                // Add fill layer
                map.addLayer({
                    id: radiusLayerId,
                    type: 'fill',
                    source: radiusLayerId,
                    paint: {
                        'fill-color': '#007bff',
                        'fill-opacity': 0.2
                    }
                });
                
                // Add border layer
                map.addLayer({
                    id: radiusLayerId + '-border',
                    type: 'line',
                    source: radiusLayerId,
                    paint: {
                        'line-color': '#007bff',
                        'line-width': 2,
                        'line-opacity': 0.8
                    }
                });
                
                console.log('Fallback circle layer added successfully');
            } catch (error) {
                console.error('Error drawing fallback circle:', error);
            }
        }

        // Wait for map to load before adding circle and event listeners
        map.on('load', function() {
            map.resize();
            drawCircle(radius);
            loadNearbyDrivers(initialLat, initialLng, radius); // Load initial nearby drivers
            marker.on('drag', updateMarkerPosition);
            marker.on('dragend', updateMarkerPosition);
        });

        // Function to handle radius slider
        document.getElementById('radius-slider').addEventListener('input', function() {
            radius = this.value;
            document.getElementById('radius-value').value = radius;
            if (map.loaded()) {
                drawCircle(radius);
            }
        });
    </script>

    <script type="text/javascript">
        var $range = $(".js-range-slider"),
            $input = $(".js-input"),
            instance,
            min = 1,
            max = 100;

        $range.ionRangeSlider({
            skin: "round",
            type: "single",
            min: min,
            max: max,
            from: {{ $campaign->radius_miles ?? 10 }},

            onStart: function(data) {
                $input.prop("value", data.from);
            },
            onChange: function(data) {
                $input.prop("value", data.from);
                radius = data.from;
                if (typeof drawCircle === 'function') {
                    drawCircle(radius);
                }
                // Reload nearby drivers with new radius
                const center = marker.getLngLat();
                loadNearbyDrivers(center.lat, center.lng, radius);
            }
        });

        instance = $range.data("ionRangeSlider");

        $input.on("change keyup", function() {
            var val = $(this).prop("value");

            if (val < min) {
                val = min;
            } else if (val > max) {
                val = max;
            }

            instance.update({
                from: val
            });
            
            radius = val;
            if (typeof drawCircle === 'function') {
                drawCircle(radius);
            }
        });
    </script>
    
    <script type="text/javascript">
        document.getElementById("generateQr").onclick = function() {
            const ctaUrl = document.getElementById("ctaUrl").value.trim();
            const qrcodeContainer = document.getElementById("qrcode");
            const qrPreviewContainer = document.querySelector("#qrcode-preview .qr-placeholder");

            if (!ctaUrl) {
                showWarningToast("Please enter a URL to generate the QR code.");
                return;
            }

            // Clear previous QR codes
            qrcodeContainer.innerHTML = "";
            
            try {
                // Generate QR code for the main container
                new QRCode(qrcodeContainer, {
                    text: ctaUrl,
                    width: 128,
                    height: 128,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H
                });
                
                // Generate smaller QR code for tablet preview
                const previewQrContainer = document.createElement('div');
                previewQrContainer.style.width = '100%';
                previewQrContainer.style.height = '100%';
                
                new QRCode(previewQrContainer, {
                    text: ctaUrl,
                    width: 40,
                    height: 40,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H
                });
                
                // Replace placeholder content with actual QR code
                qrPreviewContainer.innerHTML = '';
                qrPreviewContainer.className = qrPreviewContainer.className.replace('qr-placeholder', '');
                qrPreviewContainer.appendChild(previewQrContainer);
                qrPreviewContainer.style.padding = '2px';
                qrPreviewContainer.style.display = 'flex';
                qrPreviewContainer.style.justifyContent = 'center';
                qrPreviewContainer.style.alignItems = 'center';
                
                // Show the QR code container
                qrcodeContainer.style.display = "block";
                
                console.log('QR Code generated successfully for:', ctaUrl);
                
            } catch (error) {
                console.error("QR Code generation error:", error);
                showErrorToast("An error occurred while generating the QR code. Please try again.");
            }
        };
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const steps = document.querySelectorAll(".step");
            const stepLinks = document.querySelectorAll(".step-link");
            let currentStep = 0;
            const totalSteps = {{ $campaign->status === 'draft' ? 3 : 2 }};

            // Function to update button visibility based on current step
            function updateButtonVisibility() {
                const nextBtn = document.getElementById("next");
                const prevBtn = document.getElementById("prev");
                const publishBtn = document.getElementById("publish-btn");
                const updateBtn = document.getElementById("update-btn");
                const saveDraftBtn = document.getElementById("save-draft-btn");
                
                // Show/hide previous button based on step
                if (prevBtn) {
                    prevBtn.style.display = currentStep === 0 ? "none" : "inline-block";
                }
                
                // Save draft button should always be visible for draft campaigns
                if (saveDraftBtn) {
                    saveDraftBtn.style.display = "inline-block";
                }
                
                // Show/hide next button and publish/update buttons based on step
                if (currentStep === totalSteps - 1) {
                    // Last step - hide next, show publish/update
                    if (nextBtn) nextBtn.style.display = "none";
                    if (publishBtn) publishBtn.style.display = "inline-block";
                    if (updateBtn) updateBtn.style.display = "inline-block";
                } else {
                    // Not last step - show next, hide publish/update
                    if (nextBtn) nextBtn.style.display = "inline-block";
                    if (publishBtn) publishBtn.style.display = "none";
                    if (updateBtn) updateBtn.style.display = "none";
                }
            }

            // Initially show the first step
            steps[currentStep].style.display = "block";
            
            // Set initial button visibility
            updateButtonVisibility();
            
            // Function to show a specific step
            function showStep(stepIndex) {
                if (stepIndex >= 0 && stepIndex < totalSteps) {
                    steps[currentStep].style.display = "none";
                    currentStep = stepIndex;
                    steps[currentStep].style.display = "block";

                    // Update active step link
                    stepLinks.forEach((link, index) => {
                        link.classList.toggle("active", index === currentStep);
                    });
                    
                    // Update button visibility based on current step
                    updateButtonVisibility();
                    
                    // Initialize and resize map when showing locations step (step 2 = index 1)
                    if (stepIndex === 1) {
                        console.log('Showing edit step 2 - initializing map');
                        if (!window.mapInitialized) {
                            // Use global initialization with delay to ensure DOM is ready
                            setTimeout(function() {
                                if (typeof window.initializeMap === 'function') {
                                    window.initializeMap();
                                } else {
                                    console.error('Global map function not found');
                                }
                            }, 500);
                        } else if (typeof window.map !== 'undefined' && window.map.loaded()) {
                            setTimeout(() => {
                                window.map.resize();
                                if (typeof window.drawCircle === 'function') {
                                    window.drawCircle(window.radius);
                                }
                            }, 100);
                        }
                    }
                }
            }

            // Next button functionality
            const nextBtn = document.getElementById("next");
            if (nextBtn) {
                nextBtn.addEventListener("click", (e) => {
                    e.preventDefault();
                    if (currentStep < totalSteps - 1) {
                        showStep(currentStep + 1);
                    }
                });
            }

            // Previous button functionality
            const prevBtn = document.getElementById("prev");
            if (prevBtn) {
                prevBtn.addEventListener("click", (e) => {
                    e.preventDefault();
                    if (currentStep > 0) {
                        showStep(currentStep - 1);
                    }
                });
            }

            // Step link functionality
            stepLinks.forEach((link) => {
                link.addEventListener("click", (event) => {
                    event.preventDefault();
                    const stepIndex = parseInt(link.getAttribute("data-step"), 10);
                    if (stepIndex !== currentStep && stepIndex < totalSteps) {
                        showStep(stepIndex);
                    }
                });
            });
        });

        // Force save draft to work
        document.addEventListener('DOMContentLoaded', function() {
            const saveDraftBtn = document.getElementById('save-draft-btn');
            if (saveDraftBtn) {
                saveDraftBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Save draft clicked!');
                    
                    const form = document.getElementById('multiStepForm');
                    console.log('Form found:', form);
                    
                    // Remove any existing action inputs
                    const existingActionInputs = form.querySelectorAll('input[name="action"]');
                    existingActionInputs.forEach(input => input.remove());
                    
                    // Create hidden input for action
                    const actionInput = document.createElement('input');
                    actionInput.type = 'hidden';
                    actionInput.name = 'action';
                    actionInput.value = 'save_draft';
                    form.appendChild(actionInput);
                    
                    console.log('Submitting form...');
                    // Submit the form
                    form.submit();
                });
            } else {
                console.log('Save draft button not found!');
            }
        });
    </script>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const fileInput = document.getElementById("fileElem");
            const dropArea = document.getElementById("drop-zone");
            const previewImage = document.getElementById("preview-image");
            const previewVideo = document.getElementById("preview-video");
            const videoSource = document.getElementById("video-source");
            const removeMediaBtn = document.getElementById("remove-media");

            function preventDefaults(event) {
                event.preventDefault();
                event.stopPropagation();
            }

            ["dragenter", "dragover", "dragleave", "drop"].forEach(eventName => {
                dropArea.addEventListener(eventName, preventDefaults, false);
            });

            dropArea.addEventListener("dragover", () => dropArea.classList.add("hover"));
            dropArea.addEventListener("dragleave", () => dropArea.classList.remove("hover"));
            dropArea.addEventListener("drop", (event) => {
                dropArea.classList.remove("hover");
                handleFiles(event.dataTransfer.files);
            });

            fileInput.addEventListener("change", () => handleFiles(fileInput.files));

            if (removeMediaBtn) {
                removeMediaBtn.onclick = () => {
                    previewImage.src = "{{ asset('assets/images/addbase.png') }}";
                    previewImage.style.display = "block";
                    previewVideo.style.display = "none";
                    removeMediaBtn.style.display = "none";
                    fileInput.value = '';
                };
            }

            function handleFiles(files) {
                if (!files.length) return;
                const file = files[0];
                const reader = new FileReader();
                reader.onload = (event) => {
                    if (file.type.startsWith("video/")) {
                        videoSource.src = event.target.result;
                        previewVideo.load();
                        previewVideo.play();
                        previewVideo.style.display = "block";
                        previewImage.style.display = "none";
                    } else if (file.type.startsWith("image/")) {
                        previewImage.src = event.target.result;
                        previewImage.style.display = "block";
                        previewVideo.style.display = "none";
                    }
                    if (removeMediaBtn) {
                        removeMediaBtn.style.display = "block";
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    </script>

    <script>
        // QR Position Handler
        document.addEventListener("DOMContentLoaded", function() {
            const qrPositionRadios = document.querySelectorAll('input[name="qr_position"]');
            const qrCodeElement = document.getElementById('qrcode-preview');
            
            // Handle QR position changes
            qrPositionRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.checked) {
                        updateQrPosition(this.value);
                    }
                });
            });
            
            function updateQrPosition(position) {
                if (qrCodeElement) {
                    // Remove all existing position classes
                    qrCodeElement.classList.remove('top-right', 'top-left', 'bottom-right', 'bottom-left');
                    
                    // Add the selected position class
                    qrCodeElement.classList.add(position);
                    
                    console.log('QR position updated to:', position);
                }
            }
            
            // Initialize with current position
            const currentPosition = document.querySelector('input[name="qr_position"]:checked');
            if (currentPosition) {
                updateQrPosition(currentPosition.value);
            }

            // Package selection event listeners
            const packageSelectors = document.querySelectorAll('.package-selector');
            packageSelectors.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.checked) {
                        console.log('Package changed to:', this.value);
                        updateImpressionCounts();
                    }
                });
            });

            // Initial impression count calculation
            updateImpressionCounts();
        });


    </script>

    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
            crossorigin="anonymous"></script>

</body>

</html>