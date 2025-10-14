<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
    <title>Mobiplay</title>
    <!-- Map container styles -->
    <style>
        /* Stable driver marker style */
        .driver-marker-stable {
            width: 12px !important;
            height: 12px !important;
            border-radius: 50% !important;
            border: 2px solid white !important;
            box-shadow: 0 0 3px rgba(0,0,0,0.3) !important;
            cursor: pointer !important;
            transition: box-shadow 0.15s ease !important;
        }
        
        /* Pulse animation for active drivers */
        @keyframes pulse-green {
            0% {
                box-shadow: 0 0 0 0 rgba(76, 175, 80, 0.6);
            }
            70% {
                box-shadow: 0 0 0 6px rgba(76, 175, 80, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(76, 175, 80, 0);
            }
        }

        /* Make sure markers stay in position on hover */
        .mapboxgl-marker {
            transform-origin: center !important;
        }
        
        /* Enhanced popup style */
        .driver-popup .mapboxgl-popup-content {
            border-radius: 8px !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15) !important;
            padding: 0 !important;
            overflow: hidden !important;
        }
        
        .driver-popup .mapboxgl-popup-close-button {
            font-size: 18px !important;
            padding: 6px 10px !important;
            color: #666 !important;
        }
        
        /* Animation for driver points when map is panned */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        /* Status indicator pulse animations */
        .status-available {
            animation: pulse-green 2s infinite;
        }
        
        .status-busy {
            animation: pulse-orange 2s infinite;
        }
        
        @keyframes pulse-orange {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 152, 0, 0.6);
            }
            70% {
                box-shadow: 0 0 0 6px rgba(255, 152, 0, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(255, 152, 0, 0);
            }
        }

        /* High specificity and !important to ensure our styles win */
        #map,
        div#map,
        body #map {
            width: 100% !important;
            height: 70vh !important;
            min-height: 500px !important;
            max-height: 700px !important;
            position: relative !important;
            display: block !important;
            border-radius: 0 !important;
            border: 1px solid #ddd !important;
            overflow: visible !important;
            z-index: 1 !important;
        }
        
        /* Fix any parent container issues */
        .col-lg-7:has(#map),
        div.col-lg-7:has(#map),
        [class*="col"]:has(#map) {
            padding: 0 !important;
            position: relative !important;
        }
        
        /* Ensure Mapbox canvas is correctly positioned */
        .mapboxgl-canvas,
        canvas.mapboxgl-canvas {
            left: 0 !important;
            top: 0 !important;
        }
        .mapboxgl-map {
            width: 100%;
            height: 100%;
        }
        #driver-count {
            position: absolute;
            top: 10px !important;
            right: 10px !important;
            background: #FFCC00 !important; /* Taxi yellow */
            padding: 8px 14px !important;
            border-radius: 6px !important;
            font-size: 14px !important;
            font-weight: bold !important;
            color: #000000 !important; /* Black text for contrast */
            box-shadow: 0 2px 6px rgba(0,0,0,0.25) !important;
            z-index: 999;
            border: 2px solid #000000 !important; /* Black border */
            display: flex !important;
            align-items: center !important;
            gap: 6px !important;
        }
        
        /* Taxi icon before driver count */
        #driver-count::before {
            content: "";
            display: inline-block;
            width: 16px;
            height: 16px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='currentColor' d='M5,11L6.5,6.5H17.5L19,11M17.5,16A1.5,1.5 0 0,1 16,14.5A1.5,1.5 0 0,1 17.5,13A1.5,1.5 0 0,1 19,14.5A1.5,1.5 0 0,1 17.5,16M6.5,16A1.5,1.5 0 0,1 5,14.5A1.5,1.5 0 0,1 6.5,13A1.5,1.5 0 0,1 8,14.5A1.5,1.5 0 0,1 6.5,16M18.92,6C18.72,5.42 18.16,5 17.5,5H6.5C5.84,5 5.28,5.42 5.08,6L3,12V20A1,1 0 0,0 4,21H5A1,1 0 0,0 6,20V19H18V20A1,1 0 0,0 19,21H20A1,1 0 0,0 21,20V12L18.92,6Z' /%3E%3C/svg%3E");
            background-size: contain;
            background-repeat: no-repeat;
        }
    </style>
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@300,301,400,401,500,501,700,701,900,901,1,2&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CSS Libraries -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ion-rangeslider/css/ion.rangeSlider.min.css">
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.css' rel='stylesheet' media="print" onload="this.media='all'" />
    
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
    
    /* Map Container Styles - Consolidated in the head section */
    
    /* Map loading indicator */
    #map-loading-indicator {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1000;
        background-color: rgba(255, 255, 255, 0.8);
        border-radius: 5px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        text-align: center;
    }
    
    /* Driver marker styles */
    .driver-marker {
        width: 14px !important;
        height: 14px !important;
        border-radius: 50% !important;
        box-shadow: 0 0 6px rgba(0, 0, 0, 0.5) !important;
        border: 2px solid white !important;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .mapboxgl-map {
        font-family: 'Satoshi', sans-serif !important;
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
        padding: 20px 0;
        min-height: calc(100vh - 250px);
    }
    
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }
    
    .container-fluid {
        padding: 0;
    }
    
    .row {
        margin-left: -15px;
        margin-right: -15px;
    }
    
    .col-lg-8, .col-lg-7, .col-lg-5, .col-lg-4, .col-lg-10, .col-lg-14 {
        padding-left: 15px;
        padding-right: 15px;
    }
    
    .col-lg-14 {
        width: 100%;
        max-width: 100%;
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
        height: 60vh;
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

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Driver marker base styles */
    .driver-marker {
        will-change: transform, box-shadow;
    }
</style>
</head>


<body class="bg">
    <div id="navbar-wrapper" class="campaginstep-header">
        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a href="{{ route('dashboard') }}" class="step-logo"><img src="assets/images/logo.svg" /></a>
                    <div class="right-nav">
                        <a href="#" class="notification-link"><img src="assets/images/darknotification.svg" /></a>
                        <div class="lang-menu">
                            <a href="#" class="active"><img src="assets/images/us.svg" /> EN</a>
                            <a href="#" class=""><img src="assets/images/spain.svg" /> ES</a>
                        </div>
                        <a href="#" class="profile-name"><span>{{ Auth::user()->name }} </span><img
                                src="{{ Auth::user()->profile_image_url }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;" /></a>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <div class="step-line">
        <a href="{{ route('dashboard') }}" class="step-close">Close <img src="assets/images/close.svg" /></a>
        <div class="figure-list">
            <ul>
                <li>
                    <a href="#" class="step-link active" data-step="0">
                        <label><img src="assets/images/creative.svg" /></label>
                        <span>Creative</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="step-link" data-step="1">
                        <label><img src="assets/images/location.svg" /></label>
                        <span>Locations</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="step-link" data-step="2">
                        <label><img src="assets/images/schedule.svg" /></label>
                        <span>Priority</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <section id="content-wrapper" class="campaign-wizard">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-14">
                    <form id="multiStepForm" method="POST" action="{{ route('campaigns.store') }}" enctype="multipart/form-data">
                        @csrf
                        {{-- Step 1: Creative --}}
                        <div class="step" id="step-1">
                            <section id="content-wrapper">
                                <div class="container">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-10">
                                            <div class="choose-campgain">
                                                <h2 class="text-start">Creative</h2>
                                                <p class="text-start">Upload and edit your advertisement</p>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label for="campaign_name" class="form-label">Campaign Name <span class="text-danger">*</span></label>
                                                <input type="text" name="campaign_name" id="campaign_name" 
                                                       class="form-control" placeholder="Enter Campaign Name" required>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="row justify-content-between">
                                                <div class="col-lg-7">
                                                    <div class="creative-box mb-3">
                                                        <div class="creative-inner">
                                                            <div class="row align-items-center">
                                                                <div class="col-xl-6">
                                                                    <div class="form-group">
                                                                        <h6>Upload Media<sup>*</sup></h6>
                                                                        <div class="uploadmedia" id="drop-zone">
                                                                            <!-- Changed id to match the JS -->
                                                                            <label>
                                                                                <input type="file" name=""
                                                                                    id="fileElem"
                                                                                    accept="image/*,video/*"
                                                                                    style="display:none;" />
                                                                                <div class="drop-zone">
                                                                                    Drop image here to upload
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
                                                                            id="cta" placeholder="Enter CTA">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-7">
                                                                    <div class="form-group mb-3">
                                                                        <h6>Call to Action URL<sup>*</sup></h6>
                                                                        <input type="text"
                                                                            class="form-control bg-grey"
                                                                            id="ctaUrl" placeholder="Enter URL">
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
                                                                            <img id="preview-image" src="assets/images/addbase.png"
                                                                                alt="Ad preview"
                                                                                style="width: 100%; height: 100%; object-fit: cover;" />
                                                                            <video id="preview-video" controls
                                                                                style="display: none; width: 100%; height: 100%;">
                                                                                <source id="video-source" src=""
                                                                                    type="video/mp4">
                                                                                Your browser does not support the video tag.
                                                                            </video>
                                                                        </div>
                                                                        <div class="qr-code-overlay">
                                                                            <div id="qrcode-preview" class="qr-code-position">
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
                                                                <input type="radio" id="qr-top-right" name="qr_position" value="top-right" checked>
                                                                <label for="qr-top-right" class="position-option">
                                                                    <div class="mini-screen">
                                                                        <div class="qr-dot top-right"></div>
                                                                    </div>
                                                                    <span>Top Right</span>
                                                                </label>
                                                                
                                                                <input type="radio" id="qr-top-left" name="qr_position" value="top-left">
                                                                <label for="qr-top-left" class="position-option">
                                                                    <div class="mini-screen">
                                                                        <div class="qr-dot top-left"></div>
                                                                    </div>
                                                                    <span>Top Left</span>
                                                                </label>
                                                                
                                                                <input type="radio" id="qr-bottom-right" name="qr_position" value="bottom-right">
                                                                <label for="qr-bottom-right" class="position-option">
                                                                    <div class="mini-screen">
                                                                        <div class="qr-dot bottom-right"></div>
                                                                    </div>
                                                                    <span>Bottom Right</span>
                                                                </label>
                                                                
                                                                <input type="radio" id="qr-bottom-left" name="qr_position" value="bottom-left">
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

                        {{-- Step: 3 --}}
                        <div class="step" id="step-2" style="display: none">
                            <section id="content-wrapper" class="p-0">
                                <div class="container-fluid p-0">
                                    <div class="row justify-content-end g-0">
                                        <div class="col-lg-7 order-lg-2" style="position: relative;">
                                            <div id='map' style="width: 100%; height: 85vh; min-height: 600px; max-height: 800px; position: relative; z-index: 1;"></div>
                                            
                                            <!-- Map Controls -->
                                            <div class="map-controls" style="position: absolute; top: 10px; left: 10px; z-index: 10; display: flex; flex-direction: column; gap: 10px;">
                                                <div class="map-search" style="background: white; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.2); padding: 8px 12px; display: flex; align-items: center;">
                                                    <input type="text" id="location-search" placeholder="Search location..." style="border: none; outline: none; width: 200px;">
                                                    <button id="search-button" style="background: none; border: none; cursor: pointer;">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z" stroke="#555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M21 21L16.65 16.65" stroke="#555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <!-- Loading Indicator -->
                                            <div id="map-loading" class="text-center py-4" style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: rgba(255, 255, 255, 0.9); padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); z-index: 20;">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Loading map...</span>
                                                </div>
                                                <p class="mt-2">Loading map...</p>
                                            </div>
                                            
                                            <!-- Taxi Count Indicator -->
                                            <div id="driver-count" style="position: absolute; top: 10px; right: 10px; background: #FFCC00; padding: 8px 16px; border-radius: 6px; font-size: 14px; font-weight: bold; color: #000; border: 2px solid #000; box-shadow: 0 2px 4px rgba(0,0,0,0.2); z-index: 20; display: flex; align-items: center; gap: 6px;">
                                                0 taxis in area
                                            </div>
                                        </div>
                                        <div class="col-lg-4 order-lg-1">
                                            <div class="loacat-section">
                                                <div class="choose-campgain">
                                                    <h2 class="text-start">Locations</h2>
                                                    <p class="text-start">Define where to target viewers</p>
                                                </div>
                                                <div class="location-list">
                                                    <div class="location-box active">
                                                        <div class="location-select">
                                                            <select class="form-select" id="location-select">
                                                                <option value="Queretaro">Santiago de Queretaro
                                                                </option>
                                                                <option value="Mexico City">Mexico City</option>
                                                            </select>
                                                            <a href="#"><img src="assets/images/delete.svg"></a>
                                                        </div>
                                                        <div class="location-address">
                                                            <label>Address</label>
                                                            <div class="address-box">
                                                                <p id="address">Aeropuerto Intercontinental de
                                                                    Queretaro</p>
                                                            </div>
                                                        </div>
                                                            <div class="location-radius">
                                                                <label>Set Radius</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="js-range-slider"
                                                                        id="radius-slider" name="radius_miles"
                                                                        value="10" />
                                                                </div>
                                                                <div class="extra-controls">
                                                                    <input type="text" class="js-input"
                                                                        id="radius-value" value="10" />Km
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Hidden fields for location data -->
                                                            <input type="hidden" name="latitude" id="latitude" value="">
                                                            <input type="hidden" name="longitude" id="longitude" value="">
                                                            <input type="hidden" name="location_name" id="location_name" value="">
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                        {{-- Step 4: Schedule --}}
                        <div class="step" id="step-3" style="display: none">
                            <div class="container">
                                <div class="row justify-content-center">
                                    <div class="col-lg-10">
                                        <div class="choose-campgain">
                                            <h2 class="text-start">Daily Ad Spend</h2>
                                            <p class="text-start">Set your daily spending limit for this campaign</p>
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
                                                    <input type="radio" id="package_{{ $package->id }}" name="package_id" value="{{ $package->id }}" class="package-selector d-none" {{ $index === 0 ? 'checked' : '' }}>
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
                                                        <p>The maximum amount you want to spend per day on this campaign.
                                                        </p>
                                                    </div>
                                                    <div class="quantity">
                                                        <button type="button" class="minus" aria-label="Decrease"><img
                                                                src="assets/images/minus.svg"></button>
                                                        <input type="number" class="input-box" id="budget" name="budget" value="1.00"
                                                            min="1" max="1000" step="0.01">
                                                        <button type="button" class="plus" aria-label="Increase"><img
                                                                src="assets/images/plus.svg"></button>
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
                                                    <h4 id="monthly-spend">$0.00</h4>
                                                    <span><b>Monthly Spend</b></span>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                      
                        <div class="camp-grp-btn">
                            <a href="#" class="btn btn-secondary prev-btn" id="prev">Back</a>
                            <button type="button" class="btn btn-outline-info" id="save-draft">
                                <i class="fas fa-save"></i> Save as Draft
                            </button>
                            <!-- Next button (shown on steps 1-2) -->
                            <a href="#" class="btn btn-primary next-btn" id="next">Next</a>
                            <!-- Publish button (shown only on step 3) -->
                            <button type="submit" class="btn btn-primary" id="publish-campaign" style="display: none;">
                                <i class="fas fa-rocket"></i> Publish Campaign
                            </button>
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
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.js' defer></script>
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
        // Global variables for map functionality - defined in window scope to ensure global access
        window.map = null;
        window.mapInitialized = false;
        window.marker = null;
        window.circleCoordinates = [-99.1332, 19.4326]; // Default to Mexico City
        window.radius = 5; // Default radius in miles (consistent with other defaults)
        window.radiusLayerId = 'radius-layer';
        window.driverMarkers = [];
        
        // Define map initialization functions in global scope
        window.initializeMap = function() {
            console.log("Global initializeMap called");
            if (window.mapInitialized && window.map) {
                console.log('Map already initialized, skipping');
                return;
            }
            
            // Check if mapboxgl is loaded
            if (typeof mapboxgl === 'undefined') {
                console.error('MapboxGL not available yet, retrying in 100ms');
                setTimeout(function() {
                    if (!window.mapInitialized) {
                        // Call core function by its proper name to avoid recursion
                        if (typeof initializeMapCore === 'function') {
                            initializeMapCore();
                        } else {
                            console.error('initializeMapCore function not found');
                        }
                    }
                }, 100);
                return;
            }
            
            // Check if map container exists
            const mapContainer = document.getElementById('map');
            if (!mapContainer) {
                console.error('Map container not found');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Map container not found. Please refresh the page.'
                });
                return;
            }
            
            try {
                console.log('Creating map in container:', mapContainer);
                
                // Log start of map initialization
                console.log('Starting map initialization...');
                
                // Set access token
                mapboxgl.accessToken = 'pk.eyJ1IjoibXVzdGFuc2lybWFrZGEiLCJhIjoiY20yYzNpd213MHJhNTJqcXduNjU4ZGFkdyJ9.qnsW91lfIZ1EniLcPlAEkQ';
                
                // Define fallback coordinates (Mexico City) if circleCoordinates are invalid
                let centerCoords = window.circleCoordinates;
                if (!window.circleCoordinates || !Array.isArray(window.circleCoordinates) || window.circleCoordinates.length !== 2) {
                    console.warn('Invalid circle coordinates, using fallback location');
                    centerCoords = [-99.1332, 19.4326]; // Mexico City as fallback
                    window.circleCoordinates = centerCoords;
                }
                
                // Add a loading indicator
                const loadingEl = document.createElement('div');
                loadingEl.id = 'map-loading-indicator';
                loadingEl.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading map...</span></div>';
                loadingEl.style.position = 'absolute';
                loadingEl.style.top = '50%';
                loadingEl.style.left = '50%';
                loadingEl.style.transform = 'translate(-50%, -50%)';
                loadingEl.style.zIndex = '1000';
                loadingEl.style.backgroundColor = 'rgba(255, 255, 255, 0.8)';
                loadingEl.style.padding = '20px';
                loadingEl.style.borderRadius = '5px';
                mapContainer.appendChild(loadingEl);
                
                // Create map with robust options - add a try-catch for better error handling
                try {
                    window.map = new mapboxgl.Map({
                        container: mapContainer,
                        style: 'mapbox://styles/mapbox/streets-v11',
                        center: centerCoords,
                        zoom: 10,
                        minZoom: 2,
                        maxZoom: 18,
                        failIfMajorPerformanceCaveat: false,
                        preserveDrawingBuffer: true,
                        attributionControl: true
                    });
                    
                    console.log('Map object created successfully');
                } catch (mapError) {
                    console.error('Error creating map object:', mapError);
                    
                    // Show error to user
                    const errorEl = document.createElement('div');
                    errorEl.style.padding = '20px';
                    errorEl.style.backgroundColor = '#f8d7da';
                    errorEl.style.color = '#721c24';
                    errorEl.style.borderRadius = '5px';
                    errorEl.style.margin = '20px';
                    errorEl.innerHTML = '<strong>Error loading map</strong><br>Please refresh the page and try again.';
                    mapContainer.appendChild(errorEl);
                    
                    // Remove loading indicator
                    if (loadingEl.parentNode) {
                        loadingEl.parentNode.removeChild(loadingEl);
                    }
                    
                    throw mapError; // Rethrow to stop execution
                }
                
                // Add events when map is loaded
                if (window.map) {
                    let setupAttempted = false;
                    
                    // Set a timeout in case the map doesn't load
                    const mapLoadTimeout = setTimeout(function() {
                        console.warn('Map load timeout - forcing initialization');
                        
                        // Remove loading indicator
                        const loadingEl = document.getElementById('map-loading-indicator');
                        if (loadingEl && loadingEl.parentNode) {
                            loadingEl.parentNode.removeChild(loadingEl);
                        }
                        
                        // Force map initialization if it hasn't happened
                        if (!window.mapInitialized) {
                            window.mapInitialized = true;
                            try {
                                window.setupMapFeatures();
                            } catch (e) {
                                console.error('Error in forced map setup:', e);
                            }
                        }
                    }, 5000); // 5 second timeout
                    
                    // Flag to track if the map is currently being moved/zoomed
                    window.mapIsMoving = false;
                    
                    // Track map movement to prevent flickering updates
                    window.map.on('movestart', () => {
                        window.mapIsMoving = true;
                    });
                    
                    window.map.on('moveend', () => {
                        window.mapIsMoving = false;
                        // Small delay to ensure map is settled
                        setTimeout(() => {
                            // If we have cached driver data, refresh the display
                            if (window.driverCache && window.driverCache.data && window.driverCache.data.drivers) {
                                window.processDriversInBatches(window.driverCache.data.drivers);
                            }
                        }, 100);
                    });
                    
                    window.map.on('load', function() {
                        console.log('Map loaded successfully');
                        window.mapInitialized = true;
                        
                        // Clear the timeout since map loaded successfully
                        clearTimeout(mapLoadTimeout);
                        
                        // Remove loading indicator
                        const loadingEl = document.getElementById('map-loading-indicator');
                        if (loadingEl && loadingEl.parentNode) {
                            loadingEl.parentNode.removeChild(loadingEl);
                        }
                        
                        try {
                            // Only attempt setup once
                            if (!setupAttempted) {
                                setupAttempted = true;
                                window.setupMapFeatures();
                            }
                        } catch (setupError) {
                            console.error('Error setting up map features:', setupError);
                        }
                    });
                    
                    window.map.on('error', function(e) {
                        console.error('Map error:', e);
                        
                        // Remove loading indicator on error
                        const loadingEl = document.getElementById('map-loading-indicator');
                        if (loadingEl && loadingEl.parentNode) {
                            loadingEl.parentNode.removeChild(loadingEl);
                        }
                        
                        // Show error message
                        const errorEl = document.createElement('div');
                        errorEl.style.padding = '10px';
                        errorEl.style.backgroundColor = '#f8d7da';
                        errorEl.style.color = '#721c24';
                        errorEl.style.borderRadius = '5px';
                        errorEl.style.margin = '10px';
                        errorEl.innerHTML = '<strong>Error loading map</strong><br>' + (e.error?.message || 'Unknown map error');
                        if (window.map.getContainer()) {
                            window.map.getContainer().appendChild(errorEl);
                        }
                    });
                }
            } catch (e) {
                console.error('Error initializing map:', e);
                Swal.fire({
                    icon: 'error',
                    title: 'Map Error',
                    text: 'Failed to initialize map: ' + e.message
                });
            }
        };
        
        // Setup map features after initialization
        window.setupMapFeatures = function() {
            try {
                console.log('Setting up map features...');
                
                // Initialize the Web Worker for driver data fetching
                // This will significantly improve performance by not blocking the UI thread
                if (window.Worker) {
                    console.log('Initializing driver Web Worker for background API calls...');
                    window.setupDriverWorker();
                    
                    // Start auto-refresh for driver data (every 45 seconds)
                    // Using a longer interval to prevent blinking and reduce unnecessary updates
                    window.startDriverRefresh(45);
                } else {
                    console.log('Web Workers not supported in this browser, using fallback method');
                    
                    // Use an even longer interval for browsers without Web Worker support
                    // to avoid UI blocking (every 60 seconds)
                    window.startDriverRefresh(60);
                }
                
                // Create a marker for campaign location
                window.marker = new mapboxgl.Marker({
                    draggable: true, // Make the marker draggable
                    color: '#FF5252',  // Use a distinct color for the campaign marker
                })
                .setLngLat(window.circleCoordinates) 
                .addTo(window.map);
                
                console.log('Campaign marker created:', window.marker);
    
                // Setup marker event listeners
                if (window.marker) {
                    window.marker.on('dragend', window.updateMarkerPosition);
                }
                
                // Initialize empty drivers GeoJSON source and layer if map is loaded
                if (window.map.isStyleLoaded()) {
                    setupDriverLayers({
                        type: 'FeatureCollection',
                        features: []
                    });
                } else {
                    // Wait for map style to load
                    window.map.once('style.load', function() {
                        setupDriverLayers({
                            type: 'FeatureCollection',
                            features: []
                        });
                    });
                }
                
                // Setup map click event
                if (window.map) {
                    window.map.on('click', function(e) {
                        const coordinates = e.lngLat;
                        console.log('Map clicked at:', coordinates);
                        
                        // Move marker to clicked position
                        if (window.marker) {
                            window.marker.setLngLat(coordinates);
                            window.updateMarkerPosition();
                        }
                    });
                }
                
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
        
        // Draw circle with given radius - fixed implementation to avoid recursion
        // Throttle the drawCircle function to prevent excessive redraws
        window._lastCircleDrawTime = 0;
        
        window.drawCircle = function(radius) {
            console.log('Circle redrawn after resize - radius:', radius, 'coordinates:', window.circleCoordinates);
            
            // Throttle to maximum once per 100ms
            const now = Date.now();
            if (now - window._lastCircleDrawTime < 100) {
                return;
            }
            window._lastCircleDrawTime = now;
            
            // Check for recursion detection
            if (window._drawingCircle) {
                return;
            }
            
            // Set recursion guard flag
            window._drawingCircle = true;
            
            try {
                // Safety check for map
                if (!window.map || !window.map.loaded()) {
                    window._drawingCircle = false;
                    return;
                }
                
                // Remove existing circle source and layer if they exist
                try {
                    if (window.map.getSource('circle-source')) {
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
                    return;
                }
                
                // Create circle using turf.js
                const center = window.circleCoordinates;
                const options = { steps: 80, units: 'miles' };
                const circleFeature = turf.circle(center, radius, options);
                
                // Add the circle source and layers
                window.map.addSource('circle-source', {
                    type: 'geojson',
                    data: circleFeature
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
                
                console.log('Circle drawn successfully');
            } catch(e) {
                console.error('Error in drawCircle:', e);
            } finally {
                // Always clear the recursion guard
                window._drawingCircle = false;
            }
        };
        
        // Get and display location name from coordinates
        window.getLocationName = function(lngLat) {
            try {
                console.log('Getting location name for:', lngLat);
                
                // Handle both object and array formats for coordinates
                let lng, lat;
                if (Array.isArray(lngLat)) {
                    lng = lngLat[0];
                    lat = lngLat[1];
                } else if (lngLat.lng && lngLat.lat) {
                    lng = lngLat.lng;
                    lat = lngLat.lat;
                } else {
                    console.error('Invalid coordinate format:', lngLat);
                    return;
                }
                
                // Use Mapbox Geocoding API to get location name
                const apiUrl = `https://api.mapbox.com/geocoding/v5/mapbox.places/${lng},${lat}.json?access_token=${mapboxgl.accessToken}`;
                
                fetch(apiUrl)
                    .then(response => response.json())
                    .then(data => {
                        if (data.features && data.features.length > 0) {
                            const locationName = data.features[0].place_name;
                            console.log('Location name:', locationName);
                            
                            // Update the address display
                            const addressElement = document.getElementById('address');
                            if (addressElement) {
                                addressElement.textContent = locationName;
                            }
                            
                            // Update hidden form fields
                            document.getElementById('longitude').value = lng;
                            document.getElementById('latitude').value = lat;
                            document.getElementById('location_name').value = locationName;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching location name:', error);
                    });
            } catch(e) {
                console.error('Error in getLocationName:', e);
            }
        };
        
        // Function to load and display nearby drivers
        window.driverFetchTimeout = null;
        window.currentDriverRequest = null;
        
        window.loadNearbyDrivers = function(longitude, latitude, radiusMiles) {
            try {
                console.log('Loading nearby drivers from global function:', longitude, latitude, radiusMiles);
                
                // Call the global fetchNearbyDrivers function (now attached to window)
                // Note: fetchNearbyDrivers expects (latitude, longitude, radiusKm) while this function 
                // receives (longitude, latitude, radiusMiles), so we need to swap the parameters
                window.fetchNearbyDrivers(latitude, longitude, radiusMiles);
                
            } catch(e) {
                console.error('Error in window.loadNearbyDrivers:', e);
                
                // Fallback to simple UI update
                if (document.getElementById('driver-count')) {
                    document.getElementById('driver-count').innerText = 'Error loading taxis';
                }
            }
        };
        
        // Library loading verification and debugging
        console.log('Map initialization function defined globally, starting library checks...');
        
        // Verify essential libraries are loaded
        function waitForLibraries(callback) {
            let attempts = 0;
            const maxAttempts = 100; // Increased attempts
            const checkInterval = 50;  // Faster checking
            
            function check() {
                attempts++;
                console.log(`Library check attempt ${attempts}:`, {
                    turf: typeof turf,
                    mapboxgl: typeof mapboxgl, 
                    Swal: typeof Swal
                });
                
                if (typeof turf !== 'undefined' && typeof mapboxgl !== 'undefined' && typeof Swal !== 'undefined') {
                    console.log('All libraries loaded successfully!');
                    callback();
                } else if (attempts < maxAttempts) {
                    setTimeout(check, checkInterval);
                } else {
                    console.error('Failed to load required libraries after', maxAttempts, 'attempts');
                    console.log('Final status - turf:', typeof turf, 'mapboxgl:', typeof mapboxgl, 'Swal:', typeof Swal);
                    
                    // Try to proceed anyway with available libraries
                    if (typeof mapboxgl !== 'undefined') {
                        console.warn('Proceeding with limited functionality (no Turf.js)');
                        callback();
                    }
                }
            }
            check();
        }
        
        // Immediate library check on script execution
        setTimeout(function() {
            console.log('Initial library status:', {
                turf: typeof turf,
                mapboxgl: typeof mapboxgl,
                Swal: typeof Swal
            });
        }, 100);
        
        // Variables already defined at top level
        // Checking library status...
    </script>
    <script type="text/javascript">
        // Updated Quantity Control Functionality
        (function() {
            const quantityContainer = document.querySelector(".quantity");
            const minusBtn = quantityContainer.querySelector(".minus");
            const plusBtn = quantityContainer.querySelector(".plus");
            const inputBox = quantityContainer.querySelector(".input-box");
            const maxValue = 1000; // Set maximum value for quantity input

            inputBox.max = maxValue; // Ensure max is set in input attributes
            
            // Get selected package's cost per impression
            function getSelectedPackageCost() {
                const selectedPackage = document.querySelector('input[name="package_id"]:checked');
                if (!selectedPackage) return 0.0001; // Default fallback
                
                // Get the cost from the selected package's label
                const packageLabel = document.querySelector(`label[for="${selectedPackage.id}"]`);
                if (packageLabel) {
                    const costText = packageLabel.querySelector('.package-pricing small');
                    if (costText) {
                        const match = costText.textContent.match(/\$([0-9.]+)/);
                        if (match) {
                            return parseFloat(match[1]);
                        }
                    }
                }
                return 0.0001; // Default fallback
            }
            
            // Calculate and update impressions and spend
            function updateCalculations() {
                const dailyBudget = parseFloat(inputBox.value) || 0;
                const costPerImpression = getSelectedPackageCost();
                
                // Calculate daily impressions
                const dailyImpressions = Math.floor(dailyBudget / costPerImpression);
                
                // Calculate monthly impressions (30 days)
                const monthlyImpressions = dailyImpressions * 30;
                
                // Calculate monthly spend
                const monthlySpend = dailyBudget * 30;
                
                // Update the display elements using IDs
                const dailyImpressionsEl = document.getElementById('daily-impressions');
                const monthlyImpressionsEl = document.getElementById('monthly-impressions');
                const monthlySpendEl = document.getElementById('monthly-spend');
                
                if (dailyImpressionsEl) {
                    dailyImpressionsEl.textContent = dailyImpressions.toLocaleString();
                }
                if (monthlyImpressionsEl) {
                    monthlyImpressionsEl.textContent = monthlyImpressions.toLocaleString();
                }
                if (monthlySpendEl) {
                    monthlySpendEl.textContent = '$' + monthlySpend.toFixed(2);
                }
                
                console.log('Budget calculations updated:', {
                    dailyBudget,
                    costPerImpression,
                    dailyImpressions,
                    monthlyImpressions,
                    monthlySpend
                });
            }

            function updateButtonStates() {
                const value = parseInt(inputBox.value);
                minusBtn.disabled = value <= 1;
                plusBtn.disabled = value >= maxValue;
            }

            function adjustValue(change) {
                let value = parseInt(inputBox.value) + change;
                value = Math.max(1, Math.min(value, maxValue)); // Ensure within bounds
                inputBox.value = value;
                updateButtonStates();
                updateCalculations(); // Update calculations when value changes
            }

            minusBtn.onclick = () => adjustValue(-1);
            plusBtn.onclick = () => adjustValue(1);
            
            // Update calculations when input changes manually
            inputBox.addEventListener('input', function() {
                updateButtonStates();
                updateCalculations();
            });
            
            // Update calculations when package selection changes
            document.querySelectorAll('input[name="package_id"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    updateCalculations();
                });
            });
            
            // Initial calculation on page load
            updateCalculations();
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

        // Map functions moved to main script block
        
        function setupMapFeatures() {
            // Create a marker
            marker = new mapboxgl.Marker({
                    draggable: true // Make the marker draggable
                })
                .setLngLat([-99.1332, 19.4326]) // Set marker position to Mexico City
                .addTo(map); // Add marker to the map

            // Setup marker event listeners
            marker.on('dragend', updateMarkerPosition);
            
            // Setup map click events
            map.on('click', function(e) {
                // Check if the clicked location is within Mexico
                if (!isLocationInMexico(e.lngLat.lat, e.lngLat.lng)) {
                    showErrorToast('Campaign locations are currently only available within Mexico. Please select a location within Mexico.');
                    return;
                }
                
                marker.setLngLat([e.lngLat.lng, e.lngLat.lat]);
                updateMarkerPosition();
            });
            
            // Initial setup
            updateMarkerPosition();
        }

        // Create a circle layer for the radius
        var radiusLayerId = 'radius-circle';
        var radius = 10; // Default radius in kilometers
        var circleCoordinates = [-99.1332, 19.4326]; // Initial circle coordinates (Mexico City)

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
            getLocationName(circleCoordinates); // Get the location name
            localDrawCircle(radius); // Draw the radius circle
            loadNearbyDrivers(lngLat.lat, lngLat.lng, radius); // Load nearby drivers
        }



        // Function to get location name using Mapbox Geocoding API
        function getLocationName(lngLat) {
            const url =
                `https://api.mapbox.com/geocoding/v5/mapbox.places/${lngLat[0]},${lngLat[1]}.json?access_token=${mapboxgl.accessToken}`;

            // Update hidden form fields with coordinates
            document.getElementById('longitude').value = lngLat[0];
            document.getElementById('latitude').value = lngLat[1];

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const addressElement = document.getElementById('address');
                    if (data.features && data.features.length > 0) {
                        const locationName = data.features[0].place_name;
                        addressElement.textContent = locationName;
                        // Update hidden form field with location name
                        document.getElementById('location_name').value = locationName;
                    } else {
                        addressElement.textContent = 'Location not found';
                        document.getElementById('location_name').value = '';
                    }
                })
                .catch(error => {
                    console.error('Error fetching location name:', error);
                    showErrorToast("Unable to fetch location data. Please try again.");
                });
        }

        // This drawCircle function has been replaced with a consolidated version further down the page
        // that includes better error handling and a fallback method
        // Keeping this comment to avoid breaking references

        // Make sure driver markers array exists
        if (typeof window.driverMarkers === 'undefined') {
            window.driverMarkers = [];
        }
        
        // Use the global variables for tracking requests
        window.driverFetchTimeout = null;
        window.currentDriverRequest = null;

        // Local function that delegates to global implementation
        function loadNearbyDrivers(latitude, longitude, radiusKm) {
            console.log(`Local loadNearbyDrivers called: lat=${latitude}, lng=${longitude}, radius=${radiusKm}`);
            
            // Try our local implementation first, fallback to global
            try {
                // Ensure parameters are properly defined
                if (isNaN(parseFloat(latitude)) || isNaN(parseFloat(longitude))) {
                    console.warn('Invalid coordinates provided to loadNearbyDrivers', { latitude, longitude });
                    // Check if parameters were passed in reversed order
                    if (isNaN(parseFloat(latitude)) && !isNaN(parseFloat(longitude))) {
                        console.log('Parameters might be reversed, swapping them');
                        const temp = latitude;
                        latitude = longitude;
                        longitude = temp;
                    }
                }
                
                // Call our now global implementation
                window.fetchNearbyDrivers(latitude, longitude, radiusKm);
            } catch (e) {
                console.warn('Error using implementation, falling back to global', e);
                
                // Swap parameters to match the order expected by the global function as fallback
                if (typeof window.loadNearbyDrivers === 'function') {
                    window.loadNearbyDrivers(longitude, latitude, radiusKm);
                } else {
                    console.error('Global loadNearbyDrivers function not available');
                }
            }
        }
        
        // Helper function to show driver count in UI
        window.showDriverCount = function(nearbyCount, totalCount) {
            const driverCountElem = document.getElementById('driver-count');
            const driverTotalElem = document.getElementById('driver-total');
            
            if (driverCountElem) {
                driverCountElem.textContent = nearbyCount;
            }
            
            if (driverTotalElem && totalCount) {
                driverTotalElem.textContent = `out of ${totalCount} total`;
            } else if (driverTotalElem) {
                driverTotalElem.textContent = '';
            }
        };
        
        // Function to fetch nearby drivers
        // Cache for driver data to reduce API calls
        window.driverCache = {
            data: null,
            timestamp: null,
            coordinates: null,
            radius: null,
            isCacheValid: function(lat, lng, radius) {
                if (!this.data || !this.timestamp || !this.coordinates) return false;
                
                // Cache is valid for 30 seconds
                const cacheAge = Date.now() - this.timestamp;
                if (cacheAge > 30000) return false;
                
                // Check if coordinates are close enough (within 0.01 degrees)
                const latDiff = Math.abs(lat - this.coordinates[0]);
                const lngDiff = Math.abs(lng - this.coordinates[1]);
                const radiusDiff = Math.abs(radius - this.radius);
                
                return latDiff < 0.01 && lngDiff < 0.01 && radiusDiff < 1;
            }
        };
        
        // Create a Web Worker for handling driver data fetching in a separate thread
        // This will significantly improve performance by not blocking the UI thread
        window.setupDriverWorker = function() {
            // Create the worker from a blob URL to avoid creating a separate file
            const workerCode = `
                // Driver API fetch worker
                let currentRequest = null;
                let driverCache = {
                    timestamp: 0,
                    data: null,
                    coordinates: null,
                    radius: 0
                };
                
                // Listen for messages from the main thread
                self.addEventListener('message', async function(e) {
                    const { type, latitude, longitude, radiusKm } = e.data;
                    
                    if (type === 'fetchDrivers') {
                        try {
                            // Validate parameters
                            if (!latitude || !longitude) {
                                // Default to Mexico City coordinates if none provided
                                latitude = 19.4326;
                                longitude = -99.1332;
                            }
                            
                            // Ensure radius is valid
                            const radius = radiusKm || 5;
                            
                            // Cache control - Check if we have recent driver data for this location and radius
                            const isCacheValid = function(lat, lng, rad) {
                                // Check if cache exists and is recent (less than 10 seconds old)
                                if (!driverCache || !driverCache.timestamp || (Date.now() - driverCache.timestamp) > 10000) {
                                    return false;
                                }
                                
                                // Check if coordinates and radius match
                                const latDiff = Math.abs(lat - driverCache.coordinates[0]);
                                const lngDiff = Math.abs(lng - driverCache.coordinates[1]);
                                const radiusDiff = Math.abs(rad - driverCache.radius);
                                
                                // Allow small differences for floating point inaccuracies
                                return latDiff < 0.01 && lngDiff < 0.01 && radiusDiff < 1;
                            };
                            
                            // Check cache first
                            if (isCacheValid(latitude, longitude, radius)) {
                                console.log('Worker: Using cached driver data');
                                if (driverCache.data.success && driverCache.data.drivers.length > 0) {
                                    // Send back the cached data
                                    self.postMessage({
                                        type: 'success',
                                        data: driverCache.data,
                                        fromCache: true
                                    });
                                    return;
                                }
                            }
                            
                            // Make API call with timeout
                            const response = await fetch('/api/campaigns/nearby-drivers?latitude=' + latitude + '&longitude=' + longitude + '&radius=' + radius, {
                                method: 'GET',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });
                            
                            if (!response.ok) {
                                throw new Error('Server error: ' + response.status);
                            }
                            
                            const data = await response.json();
                            
                            // Update cache
                            driverCache.data = data;
                            driverCache.timestamp = Date.now();
                            driverCache.coordinates = [latitude, longitude];
                            driverCache.radius = radius;
                            
                            // Send the data back to the main thread
                            self.postMessage({
                                type: 'success',
                                data: data
                            });
                            
                        } catch (error) {
                            // Send error back to main thread
                            self.postMessage({
                                type: 'error',
                                error: error.message,
                                details: {
                                    latitude,
                                    longitude,
                                    radiusKm
                                }
                            });
                        }
                    }
                });
            `;
            
            // Create a blob URL for the worker
            const blob = new Blob([workerCode], { type: 'application/javascript' });
            const workerUrl = URL.createObjectURL(blob);
            
            // Create the worker and set up event handlers
            try {
                window.driverWorker = new Worker(workerUrl);
                
                window.driverWorker.onmessage = function(e) {
                    const message = e.data;
                    
                    switch (message.type) {
                        case 'success':
                            console.log('Received driver data from worker', message.fromCache ? '(from cache)' : '');
                            
                            // Process the driver data
                            const data = message.data;
                            
                            if (data.success && data.drivers && data.drivers.length > 0) {
                                console.log(`Found ${data.drivers.length} nearby drivers`);
                                
                                // Don't clear if this is from cache to prevent flickering
                                // Or if the map is currently being interacted with
                                if (!message.fromCache && !window.mapIsMoving) {
                                    // Only clear if we're not using cached data
                                    window.clearDriverMarkers();
                                }
                                
                                // Process drivers using our GeoJSON approach
                                // Only if the map isn't currently being moved/zoomed
                                if (!window.mapIsMoving) {
                                    window.processDriversInBatches(data.drivers);
                                } else {
                                    console.log('Map is moving - deferring driver update to prevent flicker');
                                    // Store for later use when map movement ends
                                    if (!window.deferredDriverUpdate) {
                                        window.deferredDriverUpdate = setTimeout(() => {
                                            if (!window.mapIsMoving && data.drivers) {
                                                window.processDriversInBatches(data.drivers);
                                            }
                                            window.deferredDriverUpdate = null;
                                        }, 200);
                                    }
                                }
                                
                                // Show driver count in UI (always update this)
                                showDriverCount(data.drivers.length, data.total_count || '?');
                            } else {
                                console.log('No nearby drivers found');
                                if (!window.mapIsMoving) {
                                    window.clearDriverMarkers();
                                }
                                showDriverCount(0, 0);
                            }
                            break;
                            
                        case 'error':
                            console.error('Worker error:', message.error, message.details);
                            
                            // Show error in UI with context
                            let errorMessage = 'Error loading drivers';
                            if (message.error === 'Request timeout') {
                                errorMessage = 'Network timeout';
                            } else if (message.error.includes('Server error')) {
                                errorMessage = message.error;
                            }
                            
                            showDriverCount(errorMessage, '');
                            
                            // Show a less intrusive error message
                            if (typeof showErrorToast === 'function') {
                                showErrorToast('Unable to load nearby drivers: ' + message.error);
                            }
                            break;
                    }
                };
                
                window.driverWorker.onerror = function(error) {
                    console.error('Worker error:', error);
                    showErrorToast('Error with driver data processing. Please refresh the page.');
                };
                
                console.log('Driver Web Worker initialized successfully');
                return true;
                
            } catch (error) {
                console.error('Failed to create Web Worker:', error);
                showErrorToast('Your browser may not support some features. Driver updates may be slower.');
                return false;
            } finally {
                // Clean up the blob URL
                URL.revokeObjectURL(workerUrl);
            }
        };
        
        // Make fetchNearbyDrivers available globally so it can be called from window.loadNearbyDrivers
        window.fetchNearbyDrivers = async function fetchNearbyDrivers(latitude, longitude, radiusKm) {
            // Validate inputs and provide defaults if necessary
            if (!latitude || !longitude) {
                console.warn('Missing coordinates for fetchNearbyDrivers, using defaults');
                // Default to Mexico City coordinates if none provided
                latitude = 19.4326;
                longitude = -99.1332;
            }
            
            // Ensure radius is valid
            radiusKm = radiusKm || 5;
            
            // If we have a worker, use it for better performance
            if (window.driverWorker) {
                console.log(`Using worker to fetch drivers at [${latitude}, ${longitude}] with radius ${radiusKm} miles`);
                
                // Show loading state
                showDriverCount('Loading...', '');
                
                // Send the fetch request to the worker
                window.driverWorker.postMessage({
                    type: 'fetchDrivers',
                    latitude: parseFloat(latitude),
                    longitude: parseFloat(longitude),
                    radiusKm: parseFloat(radiusKm)
                });
                
                return;
            }
            
            // Fallback to the original implementation if worker isn't available
            
            // Check cache first
            if (window.driverCache.isCacheValid(latitude, longitude, radiusKm)) {
                console.log('Using cached driver data');
                if (window.driverCache.data.success && window.driverCache.data.drivers.length > 0) {
                    window.clearDriverMarkers();
                    await window.processDriversInBatches(window.driverCache.data.drivers);
                    showDriverCount(window.driverCache.data.drivers.length, window.driverCache.data.total_count);
                    return;
                }
            }
            
            // Create an AbortController for this request
            const controller = new AbortController();
            
            try {
                // Show loading state
                showDriverCount('Loading...', '');
                
                console.log(`Fetching drivers at [${latitude}, ${longitude}] with radius ${radiusKm} miles`);
                
                // Make API call with reduced timeout (5 seconds is enough)
                const response = await Promise.race([
                    fetch(`/api/campaigns/nearby-drivers?latitude=${latitude}&longitude=${longitude}&radius=${radiusKm}`, {
                        signal: controller.signal,
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }),
                    // Reduced timeout to 5 seconds for better UX
                    new Promise((_, reject) => 
                        setTimeout(() => reject(new Error('Request timeout')), 5000)
                    )
                ]);

                if (!response.ok) {
                    throw new Error(`Server error: ${response.status}. Please try again.`);
                }

                const data = await response.json();
                
                // Clear the current request reference
                currentDriverRequest = null;
                
                // Update cache
                window.driverCache.data = data;
                window.driverCache.timestamp = Date.now();
                window.driverCache.coordinates = [latitude, longitude];
                window.driverCache.radius = radiusKm;
                
                if (data.success && data.drivers && data.drivers.length > 0) {
                    console.log(`Found ${data.drivers.length} nearby drivers`);
                    
                    // Clear existing markers before adding new ones
                    window.clearDriverMarkers();
                    
                    // Process drivers using our GeoJSON approach
                    await window.processDriversInBatches(data.drivers);
                    
                    // Show driver count in UI
                    showDriverCount(data.drivers.length, data.total_count || '?');
                } else {
                    console.log('No nearby drivers found');
                    window.clearDriverMarkers();
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
                
                // Log detailed error information for debugging
                console.error('Error loading nearby drivers:', error, {
                    latitude: latitude,
                    longitude: longitude,
                    radiusKm: radiusKm,
                    endpoint: `/api/campaigns/nearby-drivers`,
                    errorMessage: error.message
                });
                
                // Show error in UI with more context
                let errorMessage = 'Error loading drivers';
                if (error.message === 'Request timeout') {
                    errorMessage = 'Network timeout';
                } else if (error.message.includes('HTTP error')) {
                    errorMessage = `Server error: ${error.message}`;
                }
                
                showDriverCount(errorMessage, '');
                
                // Show a less intrusive error message
                if (typeof showErrorToast === 'function') {
                    showErrorToast('Unable to load nearby drivers: ' + error.message);
                }
            }
        }

        // Process drivers in batches to prevent UI blocking
        window.processDriversInBatches = async function processDriversInBatches(drivers) {
            // Skip processing if no map or no drivers (prevents blinking)
            if (!map || !drivers) {
                console.log('No map or no drivers data - skipping update');
                return;
            }
            
            // Don't update if we're currently zooming or panning
            if (window.mapIsMoving) {
                console.log('Map is currently moving - skipping driver update to prevent flicker');
                return;
            }
            
            // Limit to maximum 100 drivers to prevent performance issues
            const limitedDrivers = drivers.slice(0, 100);
            
            // Create GeoJSON data from drivers
            const driversGeoJSON = window.createDriversGeoJSON(limitedDrivers);
            
            // Check if map and sources are ready
            if (!map.isStyleLoaded()) {
                console.log('Map style not loaded yet, waiting...');
                await new Promise(resolve => {
                    const checkStyleLoaded = () => {
                        if (map.isStyleLoaded()) {
                            resolve();
                        } else {
                            setTimeout(checkStyleLoaded, 100);
                        }
                    };
                    checkStyleLoaded();
                });
            }
            
            // Add or update the GeoJSON source
            if (!map.getSource('drivers')) {
                // First time: add source and layers
                setupDriverLayers(driversGeoJSON);
            } else {
                try {
                    // Update existing source with smooth transition
                    // This prevents blinking by using a better update method
                    map.getSource('drivers').setData(driversGeoJSON);
                } catch (e) {
                    console.error('Error updating driver source:', e);
                    // If we hit an error, try recreating the layer
                    setupDriverLayers(driversGeoJSON);
                }
            }
            
            // Log successful update
            console.log(`Updated driver layer with ${limitedDrivers.length} drivers`);
        }
        
        // Setup the map layers for drivers (source + circle layer + symbol layer)
        function setupDriverLayers(initialData) {
            try {
                // Add source for drivers
                map.addSource('drivers', {
                    type: 'geojson',
                    data: initialData || {
                        type: 'FeatureCollection',
                        features: []
                    },
                    cluster: false
                });
                
                // Use simple circle layer instead of custom icons
                map.addLayer({
                    id: 'driver-points',
                    type: 'circle',
                    source: 'drivers',
                    paint: {
                        'circle-radius': 6,
                        'circle-color': '#3887be', // Standard Mapbox blue
                        'circle-stroke-width': 2,
                        'circle-stroke-color': '#ffffff',
                        'circle-opacity': 0.85
                    }
                });
                // No custom icon creation needed - using standard Mapbox circles
                console.log('Using standard Mapbox circle markers for drivers');
                
                // No popup for simpler UI
                // setupDriverPopups();
                
                console.log('Driver layers setup complete');
            } catch (e) {
                console.error('Error setting up driver layers:', e);
            }
        }

        // Function to setup popups for the driver points - DISABLED
        function setupDriverPopups() {
            // No popups are shown for driver points
            console.log('Driver popups disabled');
            
            // We're no longer showing popups for driver points
            // Just change cursor to pointer on hover for better UX
            map.on('mouseenter', 'driver-points', () => {
                map.getCanvas().style.cursor = 'pointer';
            });
            
            // Reset cursor when leaving the feature
            map.on('mouseleave', 'driver-points', () => {
                map.getCanvas().style.cursor = '';
                // No popup to remove
            });
        }
        
        // Helper function for popup content - DISABLED
        function getDriverPopupContent(driver) {
            // We're no longer showing popup content
            // This is kept for backwards compatibility but not used
            
            // Parse driver properties if they're JSON strings (happens with GeoJSON properties)
            const driverData = typeof driver === 'string' ? JSON.parse(driver) : driver;
            
            // Return minimal content (not shown to users)
            return `<div>Taxi</div>`;
        }
        
        // Legacy function to add individual driver markers (kept for backward compatibility)
        function addDriverMarker(driver) {
            // Create popup first
            const popup = new mapboxgl.Popup({
                offset: 25,
                closeButton: false,
                closeOnClick: true,
                maxWidth: '300px',
                className: 'driver-popup'
            });
            
            // Set popup content
            popup.setHTML(`
                <div style="padding: 6px;">
                    <div style="font-weight: 600; font-size: 14px; margin-bottom: 8px; color: #1F2937;">${driver.name}</div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                        <span style="font-size: 13px; color: #6b7280; font-weight: 500;">Status:</span>
                        <span style="font-size: 13px; color: ${driver.status_color}; font-weight: 600;">${driver.is_active ? 'Active' : 'Inactive'}</span>
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
            `);
            
            // Create HTML element for the custom marker
            const el = document.createElement('div');
            
            // Use a simple colored dot without relative positioning that causes shift issues
            el.className = 'driver-marker-stable';
            el.style.cssText = `
                width: 12px;
                height: 12px;
                border-radius: 50%;
                background-color: ${driver.status_color || '#4CAF50'};
                border: 2px solid white;
                box-shadow: 0 0 3px rgba(0,0,0,0.3);
                cursor: pointer;
            `;
            
            // Add animation for active drivers
            if (driver.is_active) {
                el.style.animation = 'pulse-green 2s infinite';
            }
            
            // Add hover effect with proper positioning - using minimal transform
            el.addEventListener('mouseenter', function() {
                this.style.boxShadow = '0 0 5px rgba(0,0,0,0.5)';
                this.style.zIndex = '1001';
                this.style.animation = 'none'; // Stop pulsing on hover
            });
            
            el.addEventListener('mouseleave', function() {
                this.style.boxShadow = '0 0 3px rgba(0,0,0,0.3)';
                this.style.zIndex = '1000';
                // Restore animation
                if (driver.is_active) {
                    this.style.animation = 'pulse-green 2s infinite';
                }
            });

            // Use the existing popup with updated content
            popup.setHTML(`
                <div style="padding: 12px; min-width: 220px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
                    <div style="display: flex; align-items: center; margin-bottom: 10px;">
                        <div style="width: 12px; height: 12px; border-radius: 50%; background: ${driver.status_color}; margin-right: 8px;"></div>
                        <h4 style="margin: 0; font-size: 16px; font-weight: 600; color: #1f2937;">${driver.name}</h4>
                    </div>
                    <div style="space-y: 6px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                            <span style="font-size: 13px; color: #6b7280; font-weight: 500;">Status:</span>
                            <span style="font-size: 13px; color: ${driver.status_color}; font-weight: 600;">${driver.is_active ? 'Active' : 'Inactive'}</span>
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

            // Create marker with enhanced debugging
            console.log(`Creating marker for ${driver.name} at [${driver.longitude}, ${driver.latitude}]`);
            console.log('Map bounds:', map.getBounds());
            
            var marker = new mapboxgl.Marker({
                element: el,
                anchor: 'center' // This ensures the marker is properly centered
            })
            .setLngLat([driver.longitude, driver.latitude])
            .setPopup(popup)
            .addTo(map);

            // Store marker for cleanup
            window.driverMarkers.push(marker);
            console.log(`✓ Marker created and added to map. Total markers: ${window.driverMarkers.length}`);
            
            // Log marker visibility
            setTimeout(() => {
                const markerElement = marker.getElement();
                const isVisible = markerElement.offsetParent !== null;
                console.log(`Marker for ${driver.name} visible: ${isVisible}`, markerElement);
            }, 100);
        }

        // Function to clear all driver markers
        window.clearDriverMarkers = function clearDriverMarkers() {
            console.log(`Clearing driver markers`);
            
            // If using GeoJSON source, just update it with an empty feature collection
            if (map.getSource('drivers')) {
                map.getSource('drivers').setData({
                    type: 'FeatureCollection',
                    features: []
                });
            }
            
            // For backward compatibility, also clear any individual markers
            if (window.driverMarkers && window.driverMarkers.length > 0) {
                const markersToRemove = [...window.driverMarkers];
                markersToRemove.forEach(marker => {
                    if (marker && typeof marker.remove === 'function') {
                        marker.remove();
                    }
                });
                window.driverMarkers = [];
            }
        }
        
        // Convert driver data to GeoJSON feature collection
        window.createDriversGeoJSON = function createDriversGeoJSON(drivers) {
            return {
                type: 'FeatureCollection',
                features: drivers.map(driver => ({
                    type: 'Feature',
                    geometry: {
                        type: 'Point',
                        coordinates: [driver.longitude, driver.latitude]
                    },
                    properties: {
                        id: driver.id,
                        // We're not showing these properties in popups anymore,
                        // but we'll keep them in the data structure for future use
                        name: "Driver",  // Generic label for drivers
                        vehicle: driver.vehicle,
                        status: driver.status,
                        status_color: '#3887be', // Standard Mapbox blue for all drivers
                        distance: driver.distance,
                        last_update: driver.last_update,
                        is_active: driver.is_active
                    }
                }))
            };
        }

        // Function to show driver count in UI
        function showDriverCount(nearbyCount, totalCount) {
            var countElement = document.getElementById('driver-count');
            if (countElement) {
                if (nearbyCount === 'Loading...') {
                    countElement.innerHTML = `
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="width: 12px; height: 12px; border: 2px solid #f3f3f3; border-top: 2px solid #000000; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                            Loading taxis...
                        </div>
                    `;
                    countElement.style.display = 'block';
                } else if (nearbyCount === 'Error loading drivers') {
                    countElement.innerHTML = `
                        <div style="color: #e74c3c;">
                            ⚠️ Error loading taxis
                        </div>
                    `;
                    countElement.style.display = 'block';
                } else {
                    countElement.textContent = `${nearbyCount} taxis in area`;
                    countElement.style.display = 'block'; // Always show the count, even if 0
                }
            }
        }

        // Helper function to set up the map when it's ready
        function setupMapWhenReady() {
            if (!map || !map.loaded()) {
                console.log('Map not fully ready yet, waiting...');
                setTimeout(setupMapWhenReady, 500);
                return;
            }
            
            console.log('Map is loaded and ready, setting up features');
            
            // Resize map to ensure proper rendering
            map.resize();
            
            // Initial draw of the circle
            if (typeof window.drawCircle === 'function') {
                window.drawCircle(radius);
            }
            
            // Load initial nearby drivers
            if (typeof loadNearbyDrivers === 'function') {
                loadNearbyDrivers(circleCoordinates[1], circleCoordinates[0], radius);
            }
            
            // Add marker drag event listener if marker exists
            if (marker) {
                marker.on('drag', updateMarkerPosition);
                marker.on('dragend', updateMarkerPosition);
            }
            
            // Initialize the location name
            if (typeof getLocationName === 'function') {
                getLocationName(circleCoordinates);
            }
        }

        // Wait for map to load before adding circle and event listeners
        if (map) {
            map.on('load', function() {
                console.log('Map load event fired');
                setupMapWhenReady();
            });
            
            // Backup setup in case the load event already fired
            if (map.loaded()) {
                console.log('Map already loaded, setting up features directly');
                setupMapWhenReady();
            }
        } else {
            console.error('Map object not available for event binding');
        }

        // Function to handle radius slider
        document.getElementById('radius-slider').addEventListener('input', function() {
            radius = this.value; // Update the radius value
            document.getElementById('radius-value').value = radius;
            if (map.loaded()) {
                drawCircle(radius); // Redraw the circle with the updated radius
                loadNearbyDrivers(circleCoordinates[1], circleCoordinates[0], radius); // Reload nearby drivers with new radius
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
            from: 1,

            onStart: function(data) {
                $input.prop("value", data.from);
            },
            onChange: function(data) {
                $input.prop("value", data.from);
                // Update the global radius and redraw circle
                radius = data.from;
                drawCircle(radius);
            }
        });

        instance = $range.data("ionRangeSlider");

        $input.on("change keyup", function() {
            var val = $(this).prop("value");

            // validate
            if (val < min) {
                val = min;
            } else if (val > max) {
                val = max;
            }

            instance.update({
                from: val
            });
            
            // Update the global radius and redraw circle
            radius = val;
            drawCircle(radius);
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
                qrPreviewContainer.appendChild(previewQrContainer);
                qrPreviewContainer.style.padding = '2px';
                
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
        // Global variables for map functionality (accessible across all scripts)
        var map, marker;
        var mapInitialized = false;
        var radius = 5; // Default radius value
        var circleCoordinates = [-99.1332, 19.4326]; // Default coordinates
        var radiusLayerId = 'radius-circle'; // Circle layer ID
        
        // Driver markers are managed in the main JavaScript code
        // These are just backups if the window variables aren't set
        if (typeof window.driverMarkers === 'undefined') {
            window.driverMarkers = [];
        }
        if (typeof window.driverFetchTimeout === 'undefined') {
            window.driverFetchTimeout = null;
        }
        
        // Auto-refresh drivers at regular intervals
        window.startDriverRefresh = function(intervalSeconds) {
            // Default to 30 seconds if not specified
            const refreshInterval = (intervalSeconds || 30) * 1000;
            
            // Clear any existing interval
            window.stopDriverRefresh();
            
            console.log(`Starting automatic driver refresh every ${refreshInterval/1000} seconds`);
            
            // Set up a new interval
            window.driverRefreshInterval = setInterval(() => {
                // Only refresh if we have valid coordinates and radius
                if (window.circleCoordinates && window.circleCoordinates.length === 2 && window.radius > 0) {
                    console.log('Auto-refreshing driver data...');
                    
                    try {
                        // Call the driver fetch function with current coordinates
                        window.fetchNearbyDrivers(
                            window.circleCoordinates[1],  // latitude
                            window.circleCoordinates[0],  // longitude
                            window.radius                 // radius
                        );
                    } catch (error) {
                        console.error('Error in auto-refresh:', error);
                        // Don't show an error toast for background refreshes
                    }
                }
            }, refreshInterval);
            
            return true;
        };
        
        // Stop the auto-refresh
        window.stopDriverRefresh = function() {
            if (window.driverRefreshInterval) {
                clearInterval(window.driverRefreshInterval);
                window.driverRefreshInterval = null;
                console.log('Stopped automatic driver refresh');
            }
        };
        if (typeof window.currentDriverRequest === 'undefined') {
            window.currentDriverRequest = null;
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const steps = document.querySelectorAll(".step");
            const stepLinks = document.querySelectorAll(".step-link");
            let currentStep = 0;

            // Initially show the first step
            steps[currentStep].style.display = "block";
            // Set initial button visibility
            updateButtonVisibility();
            // Function to show a specific step
            function showStep(stepIndex) {
                steps[currentStep].style.display = "none"; // Hide current step
                currentStep = stepIndex; // Update current step
                steps[currentStep].style.display = "block"; // Show new step

                // Update active step link
                stepLinks.forEach((link, index) => {
                    link.classList.toggle("active", index === currentStep);
                });
                
                // Update button visibility based on current step
                updateButtonVisibility();
                
                // Initialize and resize map when showing locations step (step 2 = index 1)
                if (stepIndex === 1) {
                    console.log('Showing step 2 - ensuring map is initialized');
                    
                    // Try to initialize map if not already done
                    if (!mapInitialized) {
                        console.log('Map not initialized, initializing now');
                        // Give a brief moment for all elements to be visible
                        setTimeout(function() {
                            // Global function, will be available
                            if (typeof window.initializeMap === 'function') {
                                window.initializeMap();
                            } else {
                                console.error('initializeMap function not found!');
                                showErrorToast('Map initialization function not found. Please refresh the page.');
                            }
                        }, 500);
                    } 
                    // If map is already initialized, just resize it
                    else if (map) {
                        console.log('Map already initialized, resizing');
                        setTimeout(function() {
                            try {
                                if (map && typeof map.resize === 'function') {
                                    map.resize();
                                    console.log('Map resized successfully');
                                    
                                    if (typeof drawCircle === 'function') {
                                        drawCircle(radius);
                                        console.log('Circle redrawn after resize');
                                    }
                                }
                            } catch (e) {
                                console.error('Error resizing map:', e);
                            }
                        }, 200);
                    }
                    // Last resort - map variable exists but isn't initialized properly
                    else {
                        console.warn('Map not initialized properly, attempting recovery');
                        setTimeout(function() {
                            // Try to initialize fresh
                            try {
                                mapInitialized = false; // Reset flag to force initialization
                                initializeMap();
                            } catch(e) {
                                console.error('Recovery failed:', e);
                                showErrorToast('Map recovery failed. Please refresh the page.');
                            }
                        }, 200);
                    }
                }
            }

            // Function to update button visibility based on current step
            function updateButtonVisibility() {
                const nextBtn = document.getElementById('next');
                const publishBtn = document.getElementById('publish-campaign');
                const prevBtn = document.getElementById('prev');
                
                // Show/hide prev button
                prevBtn.style.display = currentStep === 0 ? 'none' : 'inline-block';
                
                // Show/hide next and publish buttons
                if (currentStep === steps.length - 1) {
                    // Last step: hide Next, show Publish
                    nextBtn.style.display = 'none';
                    publishBtn.style.display = 'inline-block';
                } else {
                    // Other steps: show Next, hide Publish
                    nextBtn.style.display = 'inline-block';
                    publishBtn.style.display = 'none';
                }
            }

            // Next button functionality
            document.getElementById("next").addEventListener("click", () => {
                if (currentStep < steps.length - 1) {
                    showStep(currentStep + 1);
                }
            });

            // Previous button functionality
            document.getElementById("prev").addEventListener("click", () => {
                if (currentStep > 0) {
                    showStep(currentStep - 1);
                }
            });

            // Step link functionality
            stepLinks.forEach((link) => {
                link.addEventListener("click", (event) => {
                    event.preventDefault(); // Prevent default link behavior
                    const stepIndex = parseInt(link.getAttribute("data-step"),
                        10); // Get the step number
                    if (stepIndex !== currentStep) {
                        showStep(stepIndex); // Show the clicked step
                    }
                    if (stepIndex == 2) {
                        // Wait and then trigger the map to resize
                        setTimeout(() => {
                            if (map && map.loaded()) {
                                map.resize();
                            }
                        }, 100);
                    }
                });
            });
        });
    </script>

    <script>
        // Initialize global variables for map functionality
        window.map = null;
        window.mapInitialized = false;
        window.marker = null;
        window.circleCoordinates = [-99.1332, 19.4326]; // Default to Mexico City
        window.radius = 5; // Default radius in miles
        
        // Safe initialization function with no recursion
        function initializeMap() {
            console.log('Local initializeMap called - direct implementation');
            
            // Safety check - don't use waitForLibraries, directly check
            if (typeof mapboxgl === 'undefined') {
                console.error('MapboxGL not available yet, waiting...');
                setTimeout(initializeMap, 100);  // Try again in 100ms
                return;
            }
            
            console.log('MapboxGL available, initializing map now');
            
            try {
                // Call the core function directly without delegation
                initializeMapCore();
            } catch (e) {
                console.error('Error in map initialization:', e);
                showErrorToast('Map initialization error: ' + e.message);
            }
        }
        
        // Add direct initialization on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Document loaded, initializing map directly');
            
            // Function to check map visibility and fix if needed
            function checkMapVisibility() {
                console.log('Checking map visibility...');
                const mapEl = document.getElementById('map');
                
                if (!mapEl) {
                    console.error('Map element not found!');
                    return;
                }
                
                console.log('Map element found:', mapEl);
                console.log('Map dimensions:', mapEl.getBoundingClientRect());
                console.log('Map display style:', window.getComputedStyle(mapEl).display);
                console.log('Map visible:', mapEl.offsetParent !== null);
                
                // Fix map visibility if needed
                if (window.getComputedStyle(mapEl).display === 'none' || 
                    window.getComputedStyle(mapEl).height === '0px') {
                    console.log('Fixing map visibility...');
                    mapEl.style.display = 'block';
                    mapEl.style.width = '100%';
                    mapEl.style.height = '70vh';
                    mapEl.style.minHeight = '500px';
                    mapEl.style.maxHeight = '700px';
                    
                    // Refresh the map if it exists
                    if (window.map) {
                        console.log('Resizing map...');
                        window.map.resize();
                    }
                }
            }
            
            // Check map visibility before initializing
            checkMapVisibility();
            
            // Force initial map initialization
            setTimeout(() => {
                console.log('Forcing initial map initialization attempt');
                initializeMap();
                checkMapVisibility();
            }, 1000);
            
            // Initialize map when step 2 (Location) becomes visible
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'style') {
                        const step2 = document.getElementById('step-2');
                        if (step2 && step2.style.display !== 'none') {
                            console.log('Step 2 is now visible, initializing map');
                            initializeMap();
                            
                            // Check visibility again after map is initialized
                            setTimeout(checkMapVisibility, 500);
                            
                                            // Double check again after a longer delay
                            setTimeout(function() {
                                checkMapVisibility();
                                if (window.map) window.map.resize();
                                
                                // Set up search functionality
                                setupLocationSearch();
                            }, 2000);
                        }
                    }
                });
                
            // Set up location search functionality
            function setupLocationSearch() {
                const searchInput = document.getElementById('location-search');
                const searchButton = document.getElementById('search-button');
                
                if (searchInput && searchButton) {
                    // Handle search button click
                    searchButton.addEventListener('click', function() {
                        searchLocation(searchInput.value);
                    });
                    
                    // Handle enter key press
                    searchInput.addEventListener('keypress', function(e) {
                        if (e.key === 'Enter') {
                            searchLocation(searchInput.value);
                        }
                    });
                }
                
                // Set up location dropdown functionality
                const locationSelect = document.getElementById('location-select');
                if (locationSelect) {
                    locationSelect.addEventListener('change', function(e) {
                        const selectedLocation = e.target.value;
                        console.log('Location dropdown changed to:', selectedLocation);
                        
                        // Predefined coordinates for common locations
                        const locationCoordinates = {
                            'Queretaro': [-100.3899, 20.5888],
                            'Mexico City': [-99.1332, 19.4326]
                        };
                        
                        if (locationCoordinates[selectedLocation]) {
                            const coords = locationCoordinates[selectedLocation];
                            
                            // Update global coordinates
                            window.circleCoordinates = coords;
                            
                            // Update the map view
                            if (window.map) {
                                window.map.flyTo({
                                    center: coords,
                                    essential: true,
                                    zoom: 11
                                });
                                
                                // Update the marker position
                                if (window.marker) {
                                    window.marker.setLngLat(coords);
                                }
                                
                                // Redraw the circle
                                if (typeof window.drawCircle === 'function') {
                                    setTimeout(() => window.drawCircle(window.radius || 5), 500);
                                }
                                
                                // Update location name display
                                if (typeof window.getLocationName === 'function') {
                                    window.getLocationName(coords);
                                }
                                
                                // Load nearby drivers
                                if (typeof loadNearbyDrivers === 'function') {
                                    setTimeout(() => loadNearbyDrivers(coords[1], coords[0], window.radius || 5), 1000);
                                }
                            }
                        }
                    });
                }
            }
            
            // Function to search for a location
            function searchLocation(query) {
                if (!query || query.trim() === '') return;
                
                // Show loading state
                const searchInput = document.getElementById('location-search');
                if (searchInput) searchInput.disabled = true;
                
                // Use the Mapbox Geocoding API to search for the location
                fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(query)}.json?access_token=${mapboxgl.accessToken}&limit=1`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.features && data.features.length > 0) {
                            const location = data.features[0];
                            const coordinates = location.center; // [longitude, latitude]
                            
                            // Update the global coordinates variable
                            window.circleCoordinates = coordinates;
                            
                            // Update the map view
                            if (window.map) {
                                window.map.flyTo({
                                    center: coordinates,
                                    essential: true,
                                    zoom: 11
                                });
                                
                                // Update the marker position
                                if (window.marker) {
                                    window.marker.setLngLat(coordinates);
                                }
                                
                                // Redraw the circle
                                if (typeof window.drawCircle === 'function') {
                                    setTimeout(() => window.drawCircle(window.radius || 5), 500);
                                }
                                
                                // Update location name display
                                if (typeof getLocationName === 'function') {
                                    getLocationName(coordinates);
                                }
                                
                                // Load nearby drivers
                                if (typeof loadNearbyDrivers === 'function') {
                                    setTimeout(() => loadNearbyDrivers(coordinates[1], coordinates[0], window.radius || 5), 1000);
                                }
                            }
                        } else {
                            showErrorToast('Location not found. Please try a different search term.');
                        }
                    })
                    .catch(error => {
                        console.error('Error searching for location:', error);
                        showErrorToast('Error searching for location. Please try again.');
                    })
                    .finally(() => {
                        // Reset loading state
                        if (searchInput) searchInput.disabled = false;
                    });
            }
            });
            
            const step2 = document.getElementById('step-2');
            if (step2) {
                observer.observe(step2, { attributes: true });
                
                // Also initialize if it's already visible
                if (step2.style.display !== 'none') {
                    console.log('Step 2 is already visible, initializing map');
                    initializeMap();
                }
            }
            
            // Initialize anyway after a delay as a fallback
            setTimeout(function() {
                initializeMap();
            }, 500);
        });
        
        // Helper function to log map initialization status
        function debugMapStatus() {
            try {
                console.log('DEBUG MAP STATUS:');
                console.log('- mapInitialized:', mapInitialized);
                console.log('- map variable:', typeof map, map);
                console.log('- mapboxgl available:', typeof mapboxgl);
                console.log('- map container:', document.getElementById('map'));
                console.log('- marker:', marker);
                console.log('- initializeMap function:', typeof initializeMap);
                console.log('- drawCircle function:', typeof drawCircle);
            } catch (e) {
                console.error('Error in debugMapStatus:', e);
            }
        }
        
        function initializeMapCore() {
            console.log('Starting initializeMapCore');
            debugMapStatus();
            
            // Check if map is already initialized
            if (mapInitialized && map) {
                console.log('Map already initialized, returning early');
                
                // If map exists but circle might not be drawn yet, trigger a redraw
                setTimeout(function() {
                    if (typeof window.drawCircle === 'function' && window.radius) {
                        console.log('Map already initialized - drawing circle with radius:', window.radius);
                        window.drawCircle(window.radius);
                    }
                    
                    // Make driver count visible if it exists
                    const driverCount = document.getElementById('driver-count');
                    if (driverCount) {
                        driverCount.style.display = 'block';
                    }
                }, 500);
                
                return;
            }
            
            // Verify the map container exists
            const mapContainer = document.getElementById('map');
            if (!mapContainer) {
                console.error('Map container not found');
                showErrorToast('Map container not found. Please refresh the page.');
                
                // Try to find the map container's parent
                console.warn('Map container not found, will try to recreate it');
                const mapParent = document.querySelector('.col-lg-7.order-lg-2');
                
                if (mapParent) {
                    // Clear the parent and create a new map container
                    mapParent.innerHTML = '';
                    const newMapContainer = document.createElement('div');
                    newMapContainer.id = 'map';
                    newMapContainer.style.width = '100%';
                    newMapContainer.style.height = '55vh';
                    newMapContainer.style.display = 'block';
                    newMapContainer.style.position = 'relative';
                    newMapContainer.style.zIndex = '1';
                    newMapContainer.style.border = '1px solid #ddd';
                    
                    // Add search and driver count elements
                    const driverCountEl = document.createElement('div');
                    driverCountEl.id = 'driver-count';
                    driverCountEl.style.cssText = 'position: absolute; top: 10px; right: 10px; background: rgba(255,255,255,0.9); padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: bold; color: #333; box-shadow: 0 2px 4px rgba(0,0,0,0.1); z-index: 20;';
                    driverCountEl.textContent = '0 drivers in area';
                    mapParent.appendChild(driverCountEl);
                    
                    // Insert the new map container
                    mapParent.appendChild(newMapContainer);
                    console.log('Created new map container', newMapContainer);
                    
                    // Continue with map initialization with the new container
                    return initializeMapCore(); // Try again with the new container
                } else {
                    console.error('Could not find map parent element');
                    return;
                }
            }
            
            // Ensure map container has the correct styles
            mapContainer.style.width = '100%';
            mapContainer.style.height = '70vh';
            mapContainer.style.minHeight = '500px';
            mapContainer.style.display = 'block';
            mapContainer.style.position = 'relative';
            
            // Check if mapboxgl is loaded
            if (typeof mapboxgl === 'undefined') {
                console.error('Mapbox GL JS not loaded');
                showErrorToast('Map library failed to load. Please refresh the page.');
                return;
            }
            
            console.log('Initializing Mapbox...');
            mapboxgl.accessToken =
                'pk.eyJ1IjoibXVzdGFuc2lybWFrZGEiLCJhIjoiY20yYzNpd213MHJhNTJqcXduNjU4ZGFkdyJ9.qnsW91lfIZ1EniLcPlAEkQ';
            
            // Clean up any existing map instance to prevent duplicates
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
                console.log('Creating new map instance in container:', mapContainer);
                
                // Create new map instance
                console.log('About to create map with container:', mapContainer);
                console.log('Container dimensions:', mapContainer.getBoundingClientRect());
                console.log('Container is visible:', mapContainer.offsetParent !== null);
                console.log('Container style:', mapContainer.style.cssText);
                console.log('Container computed style height:', window.getComputedStyle(mapContainer).height);
                
                // Ensure container is visible
                mapContainer.style.height = '400px';
                mapContainer.style.width = '100%';
                mapContainer.style.position = 'relative';
                mapContainer.style.display = 'block';
                
                map = new mapboxgl.Map({
                    container: mapContainer, // Use the actual DOM element instead of ID
                    style: 'mapbox://styles/mapbox/streets-v11', // Map style
                    center: [-99.1332, 19.4326], // Mexico City center [lng, lat]
                    zoom: 10, // Initial zoom level
                    attributionControl: true,
                    preserveDrawingBuffer: true // Important for taking screenshots
                });
                
                console.log('Map instance created:', map);
                
                // Make driver count visible
                const driverCount = document.getElementById('driver-count');
                if (driverCount) {
                    driverCount.style.display = 'block';
                }
                
                // Set default radius if not already set
                if (!window.radius) {
                    window.radius = 5; // Default 5 miles radius
                }
                
                // Set a default circle center if not already defined
                if (!window.circleCoordinates || window.circleCoordinates[0] === 0) {
                    window.circleCoordinates = [-99.1332, 19.4326]; // Mexico City
                    console.log('Setting default circle coordinates:', window.circleCoordinates);
                }
                
                // Create a marker immediately at the center
                window.marker = new mapboxgl.Marker({
                    draggable: true,
                    color: '#3887be'
                })
                .setLngLat(window.circleCoordinates)
                .addTo(map);
                
                // Add immediate event listener for style load to draw circle
                map.once('style.load', function() {
                    // Ensure we have circle coordinates (use map center if not set)
                    if (!window.circleCoordinates || !Array.isArray(window.circleCoordinates)) {
                        const center = map.getCenter();
                        window.circleCoordinates = [center.lng, center.lat];
                    }
                    
                    // Remove any existing markers first to ensure only one exists
                    if (window.marker) {
                        window.marker.remove();
                    }
                    
                    // Create a new marker
                    if (window.circleCoordinates) {
                        window.marker = new mapboxgl.Marker({
                            draggable: true,
                            color: '#E02020',
                            scale: 1.2 // Make it slightly larger for better visibility
                        })
                        .setLngLat(window.circleCoordinates)
                        .addTo(map);
                        
                        // Add marker event listeners
                        if (window.marker && typeof updateMarkerPosition === 'function') {
                            window.marker.on('drag', updateMarkerPosition);
                            window.marker.on('dragend', updateMarkerPosition);
                        }
                        
                        console.log('Main ad marker created/updated at', window.circleCoordinates);
                    }
                    
                    // Draw circle on a short timeout (helps with rendering issues)
                    setTimeout(function() {
                        if (typeof window.drawCircle === 'function') {
                            window.drawCircle(window.radius || 5);
                            
                            // Wait a bit longer before loading drivers to ensure map is fully ready
                            setTimeout(function() {
                                // Load nearby drivers with proper coordinates
                                if (typeof loadNearbyDrivers === 'function' && window.circleCoordinates) {
                                    const lat = window.circleCoordinates[1];
                                    const lng = window.circleCoordinates[0];
                                    loadNearbyDrivers(lat, lng, window.radius || 5);
                                }
                            }, 300);
                        }
                    }, 300);
                });
                
                // Set map variable to global scope to ensure it's accessible everywhere
                window.campaignMap = map;
                window.map = map; // Ensure window.map is also set for consistency
                
                // Check map visibility after initialization
                setTimeout(function() {
                    console.log('Map initialized, checking visibility again...');
                    const mapEl = document.getElementById('map');
                    
                    if (!mapEl) {
                        console.error('Map element not found after initialization!');
                        return;
                    }
                    
                    console.log('Map dimensions after init:', mapEl.getBoundingClientRect());
                    console.log('Map canvas element:', document.querySelector('.mapboxgl-canvas'));
                    
                    if (document.querySelector('.mapboxgl-canvas')) {
                        console.log('Canvas dimensions:', document.querySelector('.mapboxgl-canvas').getBoundingClientRect());
                    }
                    
                    // Force a resize of the map
                    if (map) {
                        console.log('Forcing map resize...');
                        map.resize();
                        
                        // Draw circle after resize
                        setTimeout(function() {
                            console.log('Circle redrawn after resize');
                            if (typeof window.drawCircle === 'function') {
                                window.drawCircle(window.radius || 5);
                            }
                            
                            // Show driver count
                            const driverCount = document.getElementById('driver-count');
                            if (driverCount) {
                                driverCount.textContent = "Finding nearby drivers...";
                                driverCount.style.display = 'block';
                            }
                            
                            // Load nearby drivers
                            if (typeof loadNearbyDrivers === 'function' && window.circleCoordinates) {
                                loadNearbyDrivers(window.circleCoordinates[1], window.circleCoordinates[0], window.radius || 5);
                            }
                        }, 500);
                    }
                }, 500);
                
                // Check if map was created properly
                if (map) {
                    // Listen for the map's load event
                    map.on('load', function() {
                        console.log('Map loaded successfully');
                        mapInitialized = true;
                        if (typeof debugMapStatus === 'function') {
                            debugMapStatus();
                        }
                        
                        // Setup map features after successful load
                        try {
                            setupMapFeatures();
                        } catch(setupError) {
                            console.error('Error setting up map features:', setupError);
                        }
                    });
                    
                    // Error handling for map
                    map.on('error', function(e) {
                        console.error('Map loading error:', e);
                        showErrorToast('Error loading map. Please check your internet connection.');
                    });
                } else {
                    console.error('Map was not created properly');
                    showErrorToast('Map could not be initialized. Please refresh the page.');
                }
                
            } catch (error) {
                console.error('Map initialization error:', error);
                showErrorToast('Failed to initialize map: ' + error.message);
            }
        }
        
        function setupMapFeatures() {
            // Remove any existing markers to prevent duplication
            if (window.marker) {
                window.marker.remove();
                window.marker = null;
                console.log('Removed existing marker');
            }
            
            // Create a new marker and store it in window.marker for global access
            window.marker = new mapboxgl.Marker({
                    draggable: true, // Make the marker draggable
                    color: '#E02020', // Red color for better visibility
                    scale: 1.2 // Make it slightly larger
                })
                .setLngLat([-99.1332, 19.4326]) // Set marker position to Mexico City
                .addTo(map); // Add marker to the map
            
            console.log('Created new main marker at [-99.1332, 19.4326]');

            // Setup marker event listeners
            window.marker.on('dragend', updateMarkerPosition);
            
            // Setup map click event
                map.on('click', function(e) {
                    const coordinates = e.lngLat;
                    console.log('Map clicked at:', coordinates);
                    
                    // Check if location is within Mexico
                    if (isLocationInMexico(coordinates.lat, coordinates.lng)) {
                        marker.setLngLat(coordinates);
                        updateMarkerPosition();
                    } else {
                        showWarningToast('Please select a location within Mexico.');
                    }
                });
                
                // Add debug info for map visibility
                console.log('Map debug info:');
                console.log('- Map container:', document.getElementById('map'));
                console.log('- Map container dimensions:', document.getElementById('map').getBoundingClientRect());
                console.log('- Map object initialized:', map);
                console.log('- Map container visible:', document.getElementById('map').offsetParent !== null);
                
                // Add a visible debug indicator
                const debugEl = document.createElement('div');
                debugEl.style.position = 'absolute';
                debugEl.style.bottom = '10px';
                debugEl.style.left = '10px';
                debugEl.style.backgroundColor = 'rgba(255,0,0,0.7)';
                debugEl.style.color = 'white';
                debugEl.style.padding = '5px 10px';
                debugEl.style.borderRadius = '4px';
                debugEl.style.zIndex = '9999';
                debugEl.textContent = 'Map Debug: Active';
                document.getElementById('map').appendChild(debugEl);            // Draw initial circle
            drawCircle(radius);
            
            // Load initial nearby drivers
            const initialCoords = marker.getLngLat();
            loadNearbyDrivers(initialCoords.lat, initialCoords.lng, radius);
        }
        
        // Function to check if coordinates are within Mexico
        function isLocationInMexico(lat, lng) {
            return lat >= 14.5 && lat <= 32.7 && lng >= -118.4 && lng <= -86.7;
        }
        
        // Function to update marker position and address
        function updateMarkerPosition() {
            const lngLat = marker.getLngLat();
            console.log('Marker position updated:', lngLat);
            
            // Update global coordinates
            circleCoordinates = [lngLat.lng, lngLat.lat];
            
            // Update the hidden form inputs
            document.getElementById('latitude').value = lngLat.lat;
            document.getElementById('longitude').value = lngLat.lng;
            
            // Get and display location name
            getLocationName(lngLat);
            
            // Redraw circle at new position
            drawCircle(radius);
            
            // Load nearby drivers for the new position
            loadNearbyDrivers(lngLat.lat, lngLat.lng, radius);
        }
        
        // Function to get location name using Mapbox Geocoding API
        function getLocationName(lngLat) {
            const geocodingUrl = `https://api.mapbox.com/geocoding/v5/mapbox.places/${lngLat.lng},${lngLat.lat}.json?access_token=${mapboxgl.accessToken}&language=en&country=mx`;
            
            fetch(geocodingUrl)
                .then(response => response.json())
                .then(data => {
                    if (data.features && data.features.length > 0) {
                        const placeName = data.features[0].place_name;
                        console.log('Location name:', placeName);
                        
                        // Update the address display
                        const addressElement = document.getElementById('address');
                        if (addressElement) {
                            addressElement.textContent = placeName;
                        }
                        
                        // Update hidden form input
                        document.getElementById('location_name').value = placeName;
                    } else {
                        document.getElementById('address').textContent = 'Location not found';
                    }
                })
                .catch(error => {
                    console.error('Geocoding error:', error);
                    document.getElementById('address').textContent = 'Location not found';
                });
        }
        
        // Function to draw a circle on the map - using a different name to avoid recursion
        function localDrawCircle(radiusValue) {
            console.log('Local circle drawing function called with radius:', radiusValue);
            if (typeof window.drawCircle === 'function') {
                // Call the global implementation
                window.drawCircle(radiusValue);
            } else {
                console.error('Global drawCircle function not found');
            }
        }
        
        // Define the draw circle function if it doesn't exist
        if (!window.drawCircle) {
            window.drawCircle = function(radius) {
                console.log('drawCircle called with radius:', radius);
                
                // If map isn't ready yet, wait a bit and try again
                if (!window.map || !window.map.loaded()) {
                    console.log('Map not ready yet for circle drawing, trying with fallback...');
                    if (typeof window.drawCircleFallback === 'function') {
                        window.drawCircleFallback(window.circleCoordinates, radius);
                    } else {
                        // Try again in a bit
                        setTimeout(function() {
                            window.drawCircle(radius);
                        }, 1000);
                    }
                    return;
                }
                
                try {
                    console.log('Drawing circle with radius:', radius, 'coordinates:', window.circleCoordinates);
                    
                    // Clean up existing circle if any
                    if (window.map.getSource('circle-source')) {
                        window.map.removeLayer('circle-fill');
                        window.map.removeLayer('circle-outline');
                        window.map.removeSource('circle-source');
                    }
                    
                    // Create circle using turf.js
                    if (typeof turf === 'undefined') {
                        console.error('Turf.js not available, trying alternate method');
                        window.drawCircleFallback(window.circleCoordinates, radius);
                        return;
                    }
                    
                    const circleFeature = turf.circle(window.circleCoordinates, radius, {
                        steps: 80,
                        units: 'miles'
                    });
                    
                    // Add circle to map
                    window.map.addSource('circle-source', {
                        type: 'geojson',
                        data: circleFeature
                    });
                    
                    // Add circle fill
                    window.map.addLayer({
                        id: 'circle-fill',
                        type: 'fill',
                        source: 'circle-source',
                        paint: {
                            'fill-color': '#3887be',
                            'fill-opacity': 0.3
                        }
                    });
                    
                    // Add circle outline
                    window.map.addLayer({
                        id: 'circle-outline',
                        type: 'line',
                        source: 'circle-source',
                        paint: {
                            'line-color': '#3887be',
                            'line-width': 2
                        }
                    });
                    
                    console.log('Circle drawn successfully with radius:', radius);
                } catch (error) {
                    console.error('Error drawing circle:', error);
                    // Try fallback method
                    if (typeof window.drawCircleFallback === 'function') {
                        window.drawCircleFallback(window.circleCoordinates, radius);
                    }
                }
            };
        }
        
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
            // This is an approximation - for a more accurate circle, Turf.js is better
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
            
            // Update the radius display
            if (document.getElementById('radius-display')) {
                document.getElementById('radius-display').innerText = radiusMiles;
            }
            
            // Load nearby drivers if function exists
            if (typeof loadNearbyDrivers === 'function') {
                loadNearbyDrivers(center[0], center[1], radiusMiles);
            }
        };
            
            // Add circle to map
            map.addSource('circle', {
                'type': 'geojson',
                'data': circle
            });
            
            map.addLayer({
                'id': 'circle-fill',
                'type': 'fill',
                'source': 'circle',
                'layout': {},
                'paint': {
                    'fill-color': '#007cbf',
                    'fill-opacity': 0.2
                }
            });
            
            map.addLayer({
                'id': 'circle-border',
                'type': 'line',
                'source': 'circle',
                'layout': {},
                'paint': {
                    'line-color': '#007cbf',
                    'line-width': 2
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

            removeMediaBtn.onclick = () => {
                previewImage.src = "assets/images/addbase.png";
                previewImage.style.display = "block";
                previewVideo.style.display = "none";
                removeMediaBtn.style.display = "none";
            };

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
                    removeMediaBtn.style.display = "block";
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
                    
                    console.log('QR position updated to:', position); // Debug log
                }
            }
            
            // Initialize with default position
            setTimeout(() => {
                updateQrPosition('top-right');
            }, 100);
        });
    </script>

    <script>
        // Save as Draft functionality
        document.addEventListener("DOMContentLoaded", function() {
            const saveDraftBtn = document.getElementById('save-draft');
            const form = document.getElementById('multiStepForm');
            
            saveDraftBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Change button state
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                this.disabled = true;
                
                // Collect form data
                const formData = new FormData(form);
                formData.append('_token', '{{ csrf_token() }}');
                
                // Send AJAX request
                fetch('{{ route("campaigns.saveDraft") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showSuccessToast(data.message);
                        
                        // Optionally store draft ID for future updates
                        if (data.draft_id) {
                            localStorage.setItem('current_draft_id', data.draft_id);
                        }
                    } else {
                        showErrorToast('Error saving draft: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorToast('Error saving draft. Please try again.');
                })
                .finally(() => {
                    // Restore button state
                    this.innerHTML = originalText;
                    this.disabled = false;
                });
            });
        });
    </script>

    <script>
        // Form validation
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById('multiStepForm');
            const nextBtn = document.getElementById('next');
            
            // Validation rules for each step
            const stepValidations = {
                1: {
                    fields: ['campaign_name', 'media_file', 'ctaUrl'],
                    validate: function() {
                        const name = document.getElementById('campaign_name')?.value.trim();
                        const file = document.getElementById('fileElem')?.files.length > 0;
                        const url = document.getElementById('ctaUrl')?.value.trim();
                        
                        if (!name) return 'Campaign name is required';
                        if (!file) return 'Please upload a media file';
                        if (!url) return 'Call to Action URL is required';
                        if (!isValidUrl(url)) return 'Please enter a valid URL';
                        
                        return true;
                    }
                },
                2: {
                    fields: ['location_name', 'radius'],
                    validate: function() {
                        const address = document.getElementById('address')?.textContent;
                        const radius = document.getElementById('radius-value')?.value;
                        
                        if (!address || address === 'Location not found') return 'Please select a location';
                        if (!radius || radius < 0.1) return 'Please set a valid radius';
                        
                        return true;
                    }
                },
                3: {
                    fields: ['package_id', 'budget'],
                    validate: function() {
                        const packageSelected = document.querySelector('input[name="package_id"]:checked');
                        const budget = document.getElementById('budget')?.value;
                        
                        if (!packageSelected) return 'Please select a package';
                        if (!budget || budget < 1) return 'Please enter a valid daily budget';
                        
                        return true;
                    }
                }
            };
            
            // URL validation helper
            function isValidUrl(string) {
                try {
                    new URL(string);
                    return true;
                } catch (_) {
                    return false;
                }
            }
            
            // Show error message
            function showError(message) {
                showErrorToast(message);
            }
            
            // Clear error messages (now handled by SweetAlert2)
            function clearErrors() {
                // No longer needed with SweetAlert2 toasts
            }
            
            // Validate current step
            function validateCurrentStep() {
                const currentStep = getCurrentStep();
                const validation = stepValidations[currentStep];
                
                if (!validation) return true;
                
                const result = validation.validate();
                
                if (result === true) {
                    clearErrors();
                    return true;
                } else {
                    const message = typeof result === 'string' ? result : validation.message;
                    showError(message);
                    return false;
                }
            }
            
            // Get current step number
            function getCurrentStep() {
                const steps = document.querySelectorAll('.step');
                for (let i = 0; i < steps.length; i++) {
                    if (steps[i].style.display !== 'none') {
                        return i + 1;
                    }
                }
                return 1;
            }
            
            // Intercept next button click for validation
            if (nextBtn) {
                nextBtn.addEventListener('click', function(e) {
                    if (!validateCurrentStep()) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }
                });
            }
            
            // Remove aggressive real-time validation to prevent random error popups
            // Only validate when user actually tries to proceed to next step
        });
    </script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
            crossorigin="anonymous"></script>

</body>

</html>
