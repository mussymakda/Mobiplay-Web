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
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/stylesheet.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/responsive.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ion-rangeslider/css/ion.rangeSlider.min.css">

    <script src="https://davidshimjs.github.io/qrcodejs/qrcode.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/ion.rangeSlider.css">
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.css' rel='stylesheet' />

    <!-- Mapbox JS -->
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.js'></script>
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.3.1/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.3.1/mapbox-gl.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/turf/6.5.0/turf.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                                            <h2 class="text-start">Ad Spend</h2>
                                            <p class="text-start">Create a budget for your campaign</p>
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
                                                        <div class="spend-box" style="background-image: url('{{ asset($bgImage) }}');">
                                                            <div class="priority-badge">PRIORITY : {{ strtoupper($package->priority_text) }}</div>
                                                            <h5>{{ $package->name }}</h5>
                                                            <p>Priority Level: {{ ucfirst($package->priority_text) }}</p>
                                                            <p>{{ $package->description }}</p>
                                                            <div class="package-pricing">
                                                                <small class="text-muted">
                                                                    ${{ number_format($package->cost_per_impression, 4) }}/impression • 
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
                                                        <h3>Average Daily Budget</h3>
                                                        <p>The amount you will spend per month averagedover thirty days.
                                                        </p>
                                                    </div>
                                                    <div class="quantity">
                                                        <button type="button" class="minus" aria-label="Decrease"><img
                                                                src="assets/images/minus.svg"></button>
                                                        <input type="number" class="input-box" value="1.00"
                                                            min="1" max="10">
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
    <!-- Load jQuery before ion.rangeSlider -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/ion-rangeslider/js/ion.rangeSlider.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/turf/6.5.0/turf.min.js"></script>
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

        // Initialize Mapbox

        mapboxgl.accessToken =
            'pk.eyJ1IjoibXVzdGFuc2lybWFrZGEiLCJhIjoiY20yYzNpd213MHJhNTJqcXduNjU4ZGFkdyJ9.qnsW91lfIZ1EniLcPlAEkQ';
        var map = new mapboxgl.Map({
            container: 'map', // ID of the map container
            style: 'mapbox://styles/mapbox/streets-v11', // Map style
            center: [-100.392, 20.588], // Initial map center [lng, lat]
            zoom: 10 // Initial zoom level
        });
        // Create a marker
        var marker = new mapboxgl.Marker({
                draggable: true // Make the marker draggable
            })
            .setLngLat([-100.392, 20.588]) // Set marker position
            .addTo(map); // Add marker to the map

        // Create a circle layer for the radius
        var radiusLayerId = 'radius-circle';
        var radius = 10; // Default radius in kilometers
        var circleCoordinates = [-100.392, 20.588]; // Initial circle coordinates

        // Function to update marker position and address
        function updateMarkerPosition() {
            var lngLat = marker.getLngLat();
            circleCoordinates = [lngLat.lng, lngLat.lat];
            getLocationName(circleCoordinates); // Get the location name
            drawCircle(radius); // Draw the radius circle
        }

        // Update the address when marker is dragged
        marker.on('dragend', updateMarkerPosition);

        // Click event to move the marker and update the radius
        map.on('click', function(e) {
            marker.setLngLat(e.lngLat); // Move marker to the clicked location
            updateMarkerPosition(); // Update the marker position and address
        });

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

        // Function to draw a circle on the map
        function drawCircle(radius) {
            try {
                console.log('Drawing circle with radius:', radius, 'at coordinates:', circleCoordinates);
                
                if (typeof turf === 'undefined') {
                    console.error('Turf.js not loaded');
                    showErrorToast('Map library not loaded properly');
                    return;
                }
                
                var radiusInMeters = radius * 1000; // Convert km to meters
                var circle = turf.circle(circleCoordinates, radiusInMeters, {
                    steps: 64,
                    units: 'meters',
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
                    'fill-color': 'rgba(0, 123, 255, 0.2)', // Circle fill color (blue)
                    'fill-opacity': 0.2 // Circle fill opacity
                }
            });
            
            // Add circle border
            map.addLayer({
                id: radiusLayerId + '-border',
                type: 'line',
                source: radiusLayerId,
                layout: {},
                paint: {
                    'line-color': '#007bff', // Border color
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

        // Wait for map to load before adding circle and event listeners
        map.on('load', function() {
            // Resize map to ensure proper rendering
            map.resize();
            
            // Initial draw of the circle
            drawCircle(radius);
            
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
                
                // Resize map when showing locations step (step 2 = index 1)
                if (stepIndex === 1 && typeof map !== 'undefined' && map.loaded()) {
                    setTimeout(() => {
                        map.resize();
                        if (typeof drawCircle !== 'undefined') {
                            drawCircle(radius);
                        }
                    }, 100);
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
                    if (stepIndex ==2) {


                        // Waent and it a momthen trigger the map to resize
                        setTimeout(() => {
                            map.resize();
                        }, 1);
                    }
                });
            });
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
                        if (!budget || budget < 1) return 'Please enter a valid budget';
                        
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

</body>

</html>
