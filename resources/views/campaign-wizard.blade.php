<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
    <title>Mobiplay</title>
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@300,301,400,401,500,501,700,701,900,901,1,2&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CSS Libraries -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ion-rangeslider/css/ion.rangeSlider.min.css">
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.css' rel='stylesheet' />
    
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
                                        <div class="col-lg-7 order-lg-2">
                                            <div id='map' ></div>
                                            <div id="driver-count" style="position: absolute; top: 10px; right: 10px; background: rgba(255,255,255,0.9); padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: bold; color: #333; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: none;">
                                                0 drivers in area
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
                                                    <h4>0</h4>
                                                   <b>Daily Impressions</b></span>

                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="">
                                                    <h4>0</h4>
                                                    <span><b>Monthly Impressions</b> </span>

                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="">
                                                    <h4>0</h4>
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
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.js'></script>
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
        window.radius = 10; // Default radius in miles
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
                setTimeout(window.initializeMap, 100);
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
                
                // Set access token
                mapboxgl.accessToken = 'pk.eyJ1IjoibXVzdGFuc2lybWFrZGEiLCJhIjoiY20yYzNpd213MHJhNTJqcXduNjU4ZGFkdyJ9.qnsW91lfIZ1EniLcPlAEkQ';
                
                // Create map
                window.map = new mapboxgl.Map({
                    container: mapContainer,
                    style: 'mapbox://styles/mapbox/streets-v11',
                    center: window.circleCoordinates,
                    zoom: 10
                });
                
                // Add events when map is loaded
                if (window.map) {
                    window.map.on('load', function() {
                        console.log('Map loaded successfully');
                        window.mapInitialized = true;
                        window.setupMapFeatures();
                    });
                    
                    window.map.on('error', function(e) {
                        console.error('Map error:', e);
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
                
                // Create a marker
                window.marker = new mapboxgl.Marker({
                    draggable: true // Make the marker draggable
                })
                .setLngLat(window.circleCoordinates) 
                .addTo(window.map);
                
                console.log('Marker created:', window.marker);
    
                // Setup marker event listeners
                if (window.marker) {
                    window.marker.on('dragend', window.updateMarkerPosition);
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
        
        // Draw circle with given radius
        window.drawCircle = function(radius) {
            try {
                console.log('Drawing circle with radius:', radius);
                
                // Remove existing circle source and layer if they exist
                if (window.map.getSource('circle-source')) {
                    window.map.removeLayer('circle-fill');
                    window.map.removeLayer('circle-outline');
                    window.map.removeSource('circle-source');
                }
                
                // Check if turf is defined
                if (typeof turf === 'undefined') {
                    console.error('Turf.js is not loaded yet');
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
            }
        };
        
        // Get and display location name from coordinates
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
        
        // Function to load and display nearby drivers
        window.driverFetchTimeout = null;
        window.currentDriverRequest = null;
        
        window.loadNearbyDrivers = function(longitude, latitude, radiusMiles) {
            try {
                console.log('Loading nearby drivers:', longitude, latitude, radiusMiles);
                
                // Cancel any pending requests
                if (window.currentDriverRequest) {
                    window.currentDriverRequest.abort();
                }
                
                // Clear any existing timeout
                if (window.driverFetchTimeout) {
                    clearTimeout(window.driverFetchTimeout);
                }
                
                // Update UI to show loading state
                if (document.getElementById('driver-count')) {
                    document.getElementById('driver-count').innerText = 'Loading...';
                }
                
                // Use a timeout to debounce the request
                window.driverFetchTimeout = setTimeout(function() {
                    // Build request URL
                    const url = `/api/drivers/nearby?longitude=${longitude}&latitude=${latitude}&radius=${radiusMiles}`;
                    
                    // Make the API request
                    window.currentDriverRequest = fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const count = data.drivers_count || 0;
                                
                                // Update UI with driver count
                                if (document.getElementById('driver-count')) {
                                    document.getElementById('driver-count').innerText = count;
                                }
                                
                                console.log(`Found ${count} drivers within ${radiusMiles} miles`);
                            } else {
                                console.error('Error fetching nearby drivers:', data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error loading nearby drivers:', error);
                        });
                }, 500); // 500ms debounce
            } catch(e) {
                console.error('Error in loadNearbyDrivers:', e);
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
            }

            minusBtn.onclick = () => adjustValue(-1);
            plusBtn.onclick = () => adjustValue(1);
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
            drawCircle(radius); // Draw the radius circle
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

        // Store driver markers for cleanup
        // Use global variables for driver markers
        window.driverMarkers = window.driverMarkers || [];
        window.driverFetchTimeout = null;
        window.currentDriverRequest = null;

        // Local function that delegates to global implementation
        function loadNearbyDrivers(latitude, longitude, radiusKm) {
            console.log('Local loadNearbyDrivers called, delegating to global implementation');
            
            // Swap parameters to match the order expected by the global function
            if (typeof window.loadNearbyDrivers === 'function') {
                window.loadNearbyDrivers(longitude, latitude, radiusKm);
            } else {
                console.error('Global loadNearbyDrivers function not available');
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
                    // Timeout promise (reduced to 2 seconds)
                    new Promise((_, reject) => 
                        setTimeout(() => reject(new Error('Request timeout')), 2000)
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
                z-index: 1000;
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
            svgIcon = `
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="white">
                        <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11C5.84 5 5.28 5.42 5.08 6.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/>
                        <circle cx="12" cy="3" r="1.5" fill="white"/>
                        <text x="12" y="4" text-anchor="middle" font-size="2" fill="${driver.status_color}" font-weight="bold">●</text>
                    </svg>
                `;
            
            taxiIcon.innerHTML = svgIcon;
            el.appendChild(taxiIcon);
            
            // Add animation for active drivers
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
                if (driver.is_active) {
                    this.style.animation = 'pulse-green 2s infinite';
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
            
            var marker = new mapboxgl.Marker(el)
                .setLngLat([driver.longitude, driver.latitude])
                .setPopup(popup)
                .addTo(map);

            // Store marker for cleanup
            driverMarkers.push(marker);
            console.log(`✓ Marker created and added to map. Total markers: ${driverMarkers.length}`);
            
            // Log marker visibility
            setTimeout(() => {
                const markerElement = marker.getElement();
                const isVisible = markerElement.offsetParent !== null;
                console.log(`Marker for ${driver.name} visible: ${isVisible}`, markerElement);
            }, 100);
        }

        // Function to clear all driver markers
        function clearDriverMarkers() {
            driverMarkers.forEach(marker => {
                marker.remove();
            });
            driverMarkers = [];
        }

        // Function to show driver count in UI
        function showDriverCount(nearbyCount, totalCount) {
            var countElement = document.getElementById('driver-count');
            if (countElement) {
                if (nearbyCount === 'Loading...') {
                    countElement.innerHTML = `
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="width: 12px; height: 12px; border: 2px solid #f3f3f3; border-top: 2px solid #3498db; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                            Loading drivers...
                        </div>
                    `;
                    countElement.style.display = 'block';
                } else if (nearbyCount === 'Error loading drivers') {
                    countElement.innerHTML = `
                        <div style="color: #e74c3c;">
                            ⚠️ Error loading drivers
                        </div>
                    `;
                    countElement.style.display = 'block';
                } else {
                    countElement.textContent = `${nearbyCount} drivers in area`;
                    countElement.style.display = 'block'; // Always show the count, even if 0
                }
            }
        }

        // Wait for map to load before adding circle and event listeners
        map.on('load', function() {
            // Resize map to ensure proper rendering
            map.resize();
            
            // Initial draw of the circle
            drawCircle(radius);
            
            // Load initial nearby drivers
            loadNearbyDrivers(circleCoordinates[1], circleCoordinates[0], radius);
            
            // Add marker drag event listener
            marker.on('drag', updateMarkerPosition);
            marker.on('dragend', updateMarkerPosition);
            
            // Initialize the location name
            getLocationName(circleCoordinates);
        });

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
        
        // Store driver markers for cleanup
        var driverMarkers = [];
        var driverFetchTimeout;
        var currentDriverRequest;
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
        window.circleCoordinates = [0, 0];
        window.radius = 5; // Default radius in miles
        
        // Call our globally defined initialization function
        function initializeMap() {
            console.log('Local initializeMap called, delegating to global implementation');
            if (typeof window.initializeMap === 'function') {
                window.initializeMap();
            } else {
                console.error('Global map initialization function not found');
            }
            
            console.log('Starting map initialization...');
            
            // Safety check - don't use waitForLibraries, directly check
            if (typeof mapboxgl === 'undefined') {
                console.error('MapboxGL not available yet, waiting...');
                setTimeout(initializeMap, 100);  // Try again in 100ms
                return;
            }
            
            console.log('MapboxGL available, initializing map now');
            
            try {
                initializeMapCore();
            } catch (e) {
                console.error('Error in map initialization:', e);
                showErrorToast('Map initialization error: ' + e.message);
            }
        }
        
        // Add direct initialization on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Document loaded, initializing map directly');
            // Don't initialize immediately, wait a bit for resources to load
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
                return;
            }
            
            // Verify the map container exists
            const mapContainer = document.getElementById('map');
            if (!mapContainer) {
                console.error('Map container not found');
                showErrorToast('Map container not found. Please refresh the page.');
                return;
            }
            
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
                map = new mapboxgl.Map({
                    container: mapContainer, // Use the actual DOM element instead of ID
                    style: 'mapbox://styles/mapbox/streets-v11', // Map style
                    center: [-99.1332, 19.4326], // Mexico City center [lng, lat]
                    zoom: 10, // Initial zoom level
                    attributionControl: true,
                    preserveDrawingBuffer: true // Important for taking screenshots
                });
                
                console.log('Map instance created:', map);
                
                // Set map variable to global scope to ensure it's accessible everywhere
                window.campaignMap = map;
                
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
            // Create a marker
            marker = new mapboxgl.Marker({
                    draggable: true // Make the marker draggable
                })
                .setLngLat([-99.1332, 19.4326]) // Set marker position to Mexico City
                .addTo(map); // Add marker to the map

            // Setup marker event listeners
            marker.on('dragend', updateMarkerPosition);
            
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
            
            // Draw initial circle
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
        
        // Function to draw a circle on the map - delegates to global function
        function drawCircle(radiusValue) {
            console.log('Local drawCircle called, delegating to global implementation');
            if (typeof window.drawCircle === 'function') {
                window.drawCircle(radiusValue);
            } else {
                console.error('Global drawCircle function not found');
            }
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
