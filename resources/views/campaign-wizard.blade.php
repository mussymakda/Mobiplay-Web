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
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.min.js"></script>
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css" type="text/css">
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.3.1/mapbox-gl.css" rel="stylesheet" />
</head>
<style>
    .advertisement img {
        width: 100%;
        height: auto;
    }
    
    /* Minimal package selection styling */
    .package-option {
        cursor: pointer;
    }
    
    .package-selected {
        position: absolute;
        bottom: 10px;
        left: 10px;
        background: rgba(40, 167, 69, 0.9);
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: bold;
        color: white;
    }
    
    /* Tablet Preview Styling */
    .uploadpreview {
        width: 100%;
        max-width: 350px;
        margin: 0 auto;
    }
    
    .tablet-frame {
        position: relative;
        width: 100%;
        background: #000000; /* Changed to black */
        border-radius: 20px;
        padding: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    }
    
    .tablet-bezel {
        position: relative;
        width: 100%;
        background: #1a1a1a; /* Darker black for bezel */
        border-radius: 12px;
        padding: 10px;
        box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.2);
    }
    
    .preview-wrapper {
        position: relative;
        width: 100%;
        aspect-ratio: 16 / 9; /* Changed to 16:9 aspect ratio */
        background: #f8f9fa;
        border-radius: 8px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .preview-media {
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .preview-img, .preview-video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 8px;
    }
    
    .qr-overlay {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: rgba(255, 255, 255, 0.95);
        padding: 5px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        display: none;
        z-index: 10;
    }
    
    .qr-overlay #qrcode {
        width: 35px;
        height: 35px;
    }
    
    .qr-overlay #qrcode img {
        width: 100% !important;
        height: 100% !important;
        border-radius: 4px;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .uploadpreview {
            max-width: 280px;
        }
        
        .tablet-frame {
            padding: 12px;
        }
        
        .tablet-bezel {
            padding: 8px;
        }
    }
</style>

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
                                src="assets/images/dark-dash-profile.png" /></a>
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
                        <label><img src="assets/images/trophy.svg" /></label>
                        <span>Campaign</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="step-link" data-step="1">
                        <label><img src="assets/images/creative.svg" /></label>
                        <span>Creative</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="step-link" data-step="2">
                        <label><img src="assets/images/location.svg" /></label>
                        <span>Locations</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="step-link" data-step="3">
                        <label><img src="assets/images/schedule.svg" /></label>
                        <span>Packages</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <section id="content-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-14">
                    <form id="multiStepForm" method="POST" action="{{ route('campaign.create') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="save_as_draft" id="save_as_draft" value="0">
                        
                        {{-- Display validation errors --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        {{-- Display success message --}}
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        {{-- Display info message --}}
                        @if (session('info'))
                            <div class="alert alert-info">
                                {{ session('info') }}
                            </div>
                        @endif
                        <!-- Hidden inputs to store form data -->
                        <input type="hidden" id="selectedPackageId" name="selected_package_id" value="">
                        <input type="hidden" id="selectedRadius" name="selected_radius" value="10">
                        <input type="hidden" id="selectedLatitude" name="selected_latitude" value="">
                        <input type="hidden" id="selectedLongitude" name="selected_longitude" value="">
                        <input type="hidden" id="scheduledDate" name="scheduled_date" value="{{ date('Y-m-d') }}">
                        {{-- Step 1: Campaign Name --}}
                        <div class="step" id="step-1">
                            <section id="content-wrapper">
                                <div class="container">
                                    <div class="row justify-content-center campe-list">
                                        <div class="col-lg-10 col-xl-8">
                                            <div class="choose-campgain">
                                                <img src="assets/images/campaign-icon.svg">
                                                <h2>Create a Campaign</h2>
                                                <p>Enter your campaign details</p>
                                            </div>
                                            <div class="row justify-content-center">
                                                <div class="col-lg-8">
                                                    <div class="form-group mb-4">
                                                        <label for="campaignName" class="form-label">Campaign Name <span class="text-danger">*</span></label>
                                                        <input type="text" id="campaignName" name="campaign_name" class="form-control"
                                                            placeholder="Enter Campaign Name" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                        {{-- Step 2: Creative --}}
                        <div class="step" id="step-2" style="display: none">
                            <section id="content-wrapper">
                                <div class="container">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-10">
                                            <div class="choose-campgain">
                                                <h2 class="text-start">Creative</h2>
                                                <p class="text-start">Upload and edit your advertisement</p>
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
                                                                                <input type="file" name="media_file"
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
                                                                        <h6>Campaign URL</h6>
                                                                        <input type="text"
                                                                            class="form-control bg-grey"
                                                                            id="ctaUrl" name="ctaUrl" placeholder="Enter URL (optional)">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-5 align-content-center">
                                                                    <div class="form-group">
                                                                        <p class="url-visit mb-0">Visits to this URL
                                                                            are tracked and reported in your dashboard.
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="uploadpreview">
                                                        <h6><i class="fa fa-tablet-alt"></i> Tablet Preview (16:9 Aspect Ratio)</h6>
                                                        <div class="tablet-frame">
                                                            <div class="tablet-bezel">
                                                                <div id="preview-container" class="preview-wrapper">
                                                                    <div class="preview-media">
                                                                        <img id="preview-image" src="assets/images/addbase.png"
                                                                            alt="Media Preview" class="preview-img" />
                                                                        <video id="preview-video" controls class="preview-video" style="display: none;">
                                                                            <source id="video-source" src="" type="video/mp4">
                                                                            Your browser does not support the video tag.
                                                                        </video>
                                                                        
                                                                        <!-- QR Code Overlay - Positioned at bottom right over the media -->
                                                                        <div id="qrcode-overlay" class="qr-overlay">
                                                                            <div id="qrcode"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Remove media button outside the frame -->
                                                        <button id="remove-media" class="btn btn-sm btn-outline-danger mt-3"
                                                            style="display: none;">
                                                            <i class="fa fa-trash"></i> Remove Media
                                                        </button>
                                                        
                                                        <!-- QR Code Status -->
                                                        <div id="qr-status" class="mt-2">
                                                            <small class="text-muted">QR code will be automatically generated when URL is entered</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>

                        {{-- Step: 3 --}}
                        <div class="step" id="step-3" style="display: none">
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
                                                        <div class="location-address">
                                                            <label>Selected Address</label>
                                                            <div class="address-box">
                                                                <p id="address">Click on map or search to select location</p>
                                                            </div>
                                                            <div class="location-radius">
                                                                <label>Set Radius</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="js-range-slider"
                                                                        id="radius-slider" name="my_range"
                                                                        value="10" />
                                                                </div>
                                                                <div class="extra-controls">
                                                                    <input type="text" class="js-input"
                                                                        id="radius-value" value="10" />Km
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
                        {{-- Step 4: Schedule --}}
                        <div class="step" id="step-4" style="display: none">
                            <div class="container">
                                <div class="row justify-content-center">
                                    <div class="col-lg-10">
                                        <div class="choose-campgain">
                                            <h2 class="text-start">Ad Spend</h2>
                                            <p class="text-start">Create a budget for your campaign</p>
                                        </div>
                                        <div class="row justify-content-between">
                                            @if(isset($packages) && $packages->count() > 0)
                                                @foreach($packages as $index => $package)
                                                    <div class="col-lg-4">
                                                        <div class="spend-box package-option" data-package-id="{{ $package->id }}" style="background-image: url('{{ asset('assets/images/bg' . (($index % 3) + 1) . '.png') }}'); cursor: pointer;">
                                                            <div class="priority-badge">PRIORITY : {{ strtoupper($package->priority_text ?? 'MEDIUM') }}</div>
                                                            <h5>{{ $package->name }}</h5>
                                                            <p>Priority Level: {{ ucfirst($package->priority_text ?? 'Medium') }}</p>
                                                            <p>{{ $package->description ?? 'Package description not available' }}</p>
                                                            @if($package->cost_per_impression)
                                                                <p><strong>{{ $package->getFormattedCostAttribute() }}</strong></p>
                                                            @endif
                                                            <div class="package-selected" style="display: none;">
                                                                <i class="fa fa-check-circle text-success"></i> Selected
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <!-- Fallback to hardcoded packages if no packages available -->
                                                <div class="col-lg-4">
                                                    <div class="spend-box" style="background-image: url('{{ asset('assets/images/bg1.png') }}');">
                                                        <div class="priority-badge">PRIORITY : LOW</div>
                                                        <h5>Basic Package</h5>
                                                        <p>Priority Level: Low</p>
                                                        <p>Your Ads will be displayed on Non-Rush hours</p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="spend-box" style="background-image: url('{{ asset('assets/images/bg2.png') }}');">
                                                        <div class="priority-badge">PRIORITY : HIGH</div>
                                                        <h5>Priority Package</h5>
                                                        <p>Priority Level: High</p>
                                                        <p>Your Ads will be displayed in Rush hours</p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="spend-box" style="background-image: url('{{ asset('assets/images/bg3.png') }}');">
                                                        <div class="priority-badge">PRIORITY : MAXIMUM</div>
                                                        <h5>Enterprise Package</h5>
                                                        <p>Priority Level: Maximum</p>
                                                        <p>Your Ads will be displayed throughout the day</p>
                                                    </div>
                                                </div>
                                            @endif
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
                                                        <input type="number" class="input-box" name="daily_budget" value="1.00"
                                                            min="1" max="1000">
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
                            <a href="#" class="btn btn-outline-primary" id="save-draft" style="display: none;">Save as Draft</a>
                            <a href="#" class="btn btn-primary next-btn" id="next">Next</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Load jQuery before ion.rangeSlider -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/ion-rangeslider/js/ion.rangeSlider.min.js"></script>
    <!-- Load Turf.js for geospatial calculations -->
    <script src="https://unpkg.com/@turf/turf@6/turf.min.js"></script>
    <script type="text/javascript">
        // Updated Quantity Control Functionality
        (function() {
            const quantityContainer = document.querySelector(".quantity");
            const minusBtn = quantityContainer.querySelector(".minus");
            const plusBtn = quantityContainer.querySelector(".plus");
            const inputBox = quantityContainer.querySelector(".input-box");
            const maxValue = 1000; // Set maximum value for quantity input

            // Get stats elements
            const dailyImpressionsEl = document.querySelector('.col-lg-2:nth-child(2) h4');
            const monthlyImpressionsEl = document.querySelector('.col-lg-2:nth-child(3) h4');
            const monthlySpendEl = document.querySelector('.col-lg-2:nth-child(4) h4');

            inputBox.max = maxValue; // Ensure max is set in input attributes

            function updateStats() {
                const dailyBudget = parseFloat(inputBox.value) || 0;
                
                // Calculate stats (these are example calculations - adjust as needed)
                const costPerImpression = 0.01; // $0.01 per impression
                const dailyImpressions = Math.floor(dailyBudget / costPerImpression);
                const monthlyImpressions = dailyImpressions * 30;
                const monthlySpend = dailyBudget * 30;

                // Update display
                if (dailyImpressionsEl) dailyImpressionsEl.textContent = dailyImpressions.toLocaleString();
                if (monthlyImpressionsEl) monthlyImpressionsEl.textContent = monthlyImpressions.toLocaleString();
                if (monthlySpendEl) monthlySpendEl.textContent = '$' + monthlySpend.toLocaleString();
            }

            function updateButtonStates() {
                const value = parseInt(inputBox.value);
                minusBtn.disabled = value <= 1;
                plusBtn.disabled = value >= maxValue;
                updateStats(); // Update stats when value changes
            }

            function adjustValue(change) {
                let value = parseInt(inputBox.value) + change;
                value = Math.max(1, Math.min(value, maxValue)); // Ensure within bounds
                inputBox.value = value;
                updateButtonStates();
            }

            // Event listeners
            minusBtn.onclick = () => adjustValue(-1);
            plusBtn.onclick = () => adjustValue(1);
            inputBox.addEventListener('input', updateButtonStates);
            
            // Initialize stats
            updateStats();
        })();
    </script>
    <script>
        // Initialize Mapbox - Simplified approach
        console.log('Starting map initialization...');

        // Check if libraries are available, if not, proceed without Turf.js
        function initializeMap() {
            if (typeof mapboxgl === 'undefined') {
                console.error('Mapbox GL JS not loaded, retrying...');
                setTimeout(initializeMap, 500);
                return;
            }
            
            if (typeof MapboxGeocoder === 'undefined') {
                console.error('Mapbox Geocoder not loaded, retrying...');
                setTimeout(initializeMap, 500);
                return;
            }
            
            console.log('Required libraries loaded, initializing map...');
            console.log('Turf.js available:', typeof turf !== 'undefined');

            mapboxgl.accessToken =
                'pk.eyJ1IjoibXVzdGFuc2lybWFrZGEiLCJhIjoiY20yYzNpd213MHJhNTJqcXduNjU4ZGFkdyJ9.qnsW91lfIZ1EniLcPlAEkQ';
        var map = new mapboxgl.Map({
            container: 'map', // ID of the map container
            style: 'mapbox://styles/mapbox/streets-v11', // Map style
            center: [-100.392, 20.588], // Initial map center [lng, lat]
            zoom: 10 // Initial zoom level
        });

        // Make map globally accessible
        window.map = map;

        // Add search control
        const geocoder = new MapboxGeocoder({
            accessToken: mapboxgl.accessToken,
            mapboxgl: mapboxgl,
            placeholder: 'Search for places',
            bbox: [-118.8, 14.5, -86.7, 32.7], // Mexico bounding box
            countries: 'mx' // Restrict to Mexico
        });
        
        map.addControl(geocoder);

        // Handle geocoder result selection
        geocoder.on('result', function(e) {
            const coordinates = e.result.center;
            marker.setLngLat(coordinates);
            circleCoordinates = coordinates;
            
            // Store location data in hidden inputs
            document.getElementById('selectedLatitude').value = coordinates[1];
            document.getElementById('selectedLongitude').value = coordinates[0];
            
            // Update address and draw circle
            getLocationName(coordinates);
            drawCircle(radius);
        });

        // Add navigation controls
        map.addControl(new mapboxgl.NavigationControl());
        
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

        // Make variables globally accessible
        window.radius = radius;
        window.radiusLayerId = radiusLayerId;
        window.circleCoordinates = circleCoordinates;

        // Wait for map to load before drawing initial circle
        map.on('load', function() {
            drawCircle(radius); // Draw initial circle
            getLocationName(circleCoordinates); // Get initial location name
        });

        // Function to update marker position and address
        function updateMarkerPosition() {
            var lngLat = marker.getLngLat();
            window.circleCoordinates = [lngLat.lng, lngLat.lat];
            
            // Store location data in hidden inputs
            document.getElementById('selectedLatitude').value = lngLat.lat;
            document.getElementById('selectedLongitude').value = lngLat.lng;
            
            getLocationName(window.circleCoordinates); // Get the location name
            window.drawCircle(window.radius); // Draw the radius circle
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

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const addressElement = document.getElementById('address');
                    if (data.features && data.features.length > 0) {
                        addressElement.textContent = data.features[0].place_name;
                    } else {
                        addressElement.textContent = 'Location not found';
                    }
                })
                .catch(error => {
                    console.error('Error fetching location name:', error);
                    alert("Unable to fetch location data. Please try again.");
                });
        }

        // Simplified circle drawing function
        function drawCircle(radiusKm) {
            if (!window.map || !window.map.isStyleLoaded()) {
                setTimeout(function() {
                    drawCircle(radiusKm);
                }, 500);
                return;
            }

            window.circleCoordinates = window.circleCoordinates || [-100.392, 20.588];
            console.log('Drawing circle with radius:', radiusKm, 'km at coordinates:', window.circleCoordinates);

            // Use Turf.js if available, otherwise use manual method
            if (typeof turf !== 'undefined') {
                drawCircleWithTurf(radiusKm);
            } else {
                drawCircleManual(radiusKm);
            }
        }

        // Make drawCircle globally accessible
        window.drawCircle = drawCircle;

        // Circle drawing with Turf.js
        function drawCircleWithTurf(radiusKm) {
            try {
                var radiusInMeters = radiusKm * 1000;
                var circle = turf.circle(window.circleCoordinates, radiusInMeters, {
                    steps: 64,
                    units: 'meters',
                });

                updateCircleOnMap(circle);
                console.log('Circle drawn using Turf.js');
            } catch (error) {
                console.error('Error with Turf.js, falling back to manual method:', error);
                drawCircleManual(radiusKm);
            }
        }

        // Manual circle drawing function (primary method)
        function drawCircleManual(radiusKm) {
            console.log('Drawing circle manually');
            
            // Simple circle approximation using points
            const center = window.circleCoordinates;
            const radiusInDegrees = radiusKm / 111; // Rough conversion: 1 degree ≈ 111 km
            const points = [];
            const steps = 64;
            
            for (let i = 0; i < steps; i++) {
                const angle = (i / steps) * 2 * Math.PI;
                const lng = center[0] + radiusInDegrees * Math.cos(angle);
                const lat = center[1] + radiusInDegrees * Math.sin(angle);
                points.push([lng, lat]);
            }
            points.push(points[0]); // Close the polygon
            
            const circle = {
                type: 'Feature',
                geometry: {
                    type: 'Polygon',
                    coordinates: [points]
                }
            };

            updateCircleOnMap(circle);
            console.log('Circle drawn manually');
        }

        // Update circle on map (common function)
        function updateCircleOnMap(circleData) {
            const map = window.map;
            const radiusLayerId = window.radiusLayerId;
            
            if (!map) return;
            
            // Remove existing circle layers if they exist
            if (map.getLayer(radiusLayerId + '-border')) {
                map.removeLayer(radiusLayerId + '-border');
            }
            if (map.getLayer(radiusLayerId)) {
                map.removeLayer(radiusLayerId);
            }
            if (map.getSource(radiusLayerId)) {
                map.removeSource(radiusLayerId);
            }

            // Add the circle as a new source
            map.addSource(radiusLayerId, {
                type: 'geojson',
                data: circleData
            });

            // Add fill layer
            map.addLayer({
                id: radiusLayerId,
                type: 'fill',
                source: radiusLayerId,
                layout: {},
                paint: {
                    'fill-color': '#007cbf',
                    'fill-opacity': 0.2
                }
            });

            // Add border layer
            map.addLayer({
                id: radiusLayerId + '-border',
                type: 'line',
                source: radiusLayerId,
                layout: {},
                paint: {
                    'line-color': '#007cbf',
                    'line-width': 2,
                    'line-opacity': 0.8
                }
            });
        }

        // Function to handle radius slider - Remove this as it conflicts with ionRangeSlider
        // document.getElementById('radius-slider').addEventListener('input', function() {
        //     radius = this.value; // Update the radius value
        //     document.getElementById('radius-value').value = radius;
        //     drawCircle(radius); // Redraw the circle with the updated radius
        // });

        // Initial draw of the circle
        map.on('load', function() {
            drawCircle(radius);
        });
        
        } // End of initializeMap function
        
        // Initialize immediately or when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeMap);
        } else {
            // DOM is already loaded
            setTimeout(initializeMap, 100);
        }
    </script>

    <script type="text/javascript">
        var $range = $(".js-range-slider"),
            $input = $(".js-input"),
            instance,
            min = 1,
            max = 50; // Reasonable max radius for targeting

        $range.ionRangeSlider({
            skin: "round",
            type: "single",
            min: min,
            max: max,
            from: 10, // Default to 10km

            onStart: function(data) {
                $input.prop("value", data.from);
                // Update map radius if map exists
                if (typeof radius !== 'undefined' && typeof drawCircle === 'function') {
                    radius = data.from;
                    drawCircle(radius);
                }
            },
            onChange: function(data) {
                $input.prop("value", data.from);
                // Update map radius when slider changes
                if (typeof window.drawCircle === 'function' && window.map) {
                    window.radius = data.from;
                    window.drawCircle(data.from);
                    // Store radius in hidden input
                    document.getElementById('selectedRadius').value = data.from;
                    console.log('Radius updated to:', data.from, 'km');
                } else {
                    console.log('drawCircle function or map not available');
                }
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

            // Update map radius when input changes
            if (typeof radius !== 'undefined' && typeof drawCircle === 'function') {
                radius = val;
                drawCircle(radius);
            }
        });
    </script>
    <script type="text/javascript">
        // Automatic QR Code generation when URL is entered
        function generateQRCode() {
            const ctaUrl = document.getElementById("ctaUrl").value.trim();
            const qrcodeContainer = document.getElementById("qrcode");
            const qrcodeOverlay = document.querySelector(".qr-overlay");
            const qrStatus = document.getElementById("qr-status");

            if (!ctaUrl) {
                // Hide QR code if URL is empty
                qrcodeContainer.innerHTML = "";
                qrcodeOverlay.style.display = 'none';
                qrStatus.innerHTML = '<small class="text-muted">QR code will be automatically generated when URL is entered</small>';
                return;
            }

            qrcodeContainer.innerHTML = ""; // Clear previous QR code
            try {
                new QRCode(qrcodeContainer, {
                    text: ctaUrl,
                    width: 35,  // Smaller size for better overlay
                    height: 35, // Smaller size for better overlay
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H
                });
                
                // Show the QR code overlay on the media
                qrcodeOverlay.style.display = 'block';
                qrStatus.innerHTML = '<small class="text-success"><i class="fa fa-check"></i> QR code generated and overlaid on media</small>';
                
            } catch (error) {
                console.error("QR Code generation error:", error);
                qrStatus.innerHTML = '<small class="text-danger"><i class="fa fa-exclamation-triangle"></i> Error generating QR code</small>';
            }
        }

        // Add event listener for automatic QR generation
        document.addEventListener("DOMContentLoaded", function() {
            const ctaUrlInput = document.getElementById("ctaUrl");
            if (ctaUrlInput) {
                ctaUrlInput.addEventListener('input', generateQRCode);
                ctaUrlInput.addEventListener('paste', function() {
                    setTimeout(generateQRCode, 10); // Small delay to ensure pasted content is processed
                });
            }
        });
    </script>

    <script>
        // Package selection functionality
        document.addEventListener("DOMContentLoaded", function() {
            const packageOptions = document.querySelectorAll('.package-option');
            const selectedPackageInput = document.getElementById('selectedPackageId');

            packageOptions.forEach(option => {
                option.addEventListener('click', function() {
                    // Remove active class from all packages
                    packageOptions.forEach(pkg => {
                        pkg.classList.remove('selected');
                        pkg.querySelector('.package-selected').style.display = 'none';
                        pkg.style.border = '';
                        pkg.style.transform = '';
                        pkg.style.boxShadow = '';
                    });

                    // Add active class to selected package
                    this.classList.add('selected');
                    this.querySelector('.package-selected').style.display = 'block';
                    this.style.border = '3px solid #28a745'; // Green color instead of blue
                    this.style.transform = 'scale(1.02)'; // Slight scale effect
                    this.style.boxShadow = '0 4px 12px rgba(40, 167, 69, 0.3)'; // Green shadow

                    // Store selected package ID
                    const packageId = this.getAttribute('data-package-id');
                    selectedPackageInput.value = packageId;
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const steps = document.querySelectorAll(".step");
            const stepLinks = document.querySelectorAll(".step-link");
            const nextBtn = document.getElementById("next");
            const prevBtn = document.getElementById("prev");
            let currentStep = 0;

            // Initially show the first step
            steps[currentStep].style.display = "block";
            
            // Function to validate current step
            function validateCurrentStep() {
                switch(currentStep) {
                    case 0: // Campaign Name step
                        const campaignName = document.getElementById("campaignName").value.trim();
                        if (!campaignName) {
                            alert("Please enter a campaign name.");
                            return false;
                        }
                        return true;
                    case 1: // Creative step
                        const fileInput = document.getElementById("fileElem");
                        if (!fileInput.files.length) {
                            alert("Please upload a media file.");
                            return false;
                        }
                        return true;
                    case 2: // Location step
                        // Location is optional or has defaults, so always valid
                        return true;
                    case 3: // Packages step
                        // Only require package selection if dynamic packages are loaded
                        const packageOptions = document.querySelectorAll('.package-option');
                        if (packageOptions.length > 0) {
                            const selectedPackage = document.getElementById("selectedPackageId").value;
                            if (!selectedPackage) {
                                alert("Please select a package.");
                                return false;
                            }
                        }
                        return true;
                    default:
                        return true;
                }
            }

            // Function to show a specific step
            function showStep(stepIndex) {
                steps[currentStep].style.display = "none"; // Hide current step
                currentStep = stepIndex; // Update current step
                steps[currentStep].style.display = "block"; // Show new step

                // Update active step link
                stepLinks.forEach((link, index) => {
                    link.classList.toggle("active", index === currentStep);
                });

                // Update button states
                const saveDraftBtn = document.getElementById("save-draft");
                prevBtn.style.display = currentStep === 0 ? "none" : "inline-block";
                saveDraftBtn.style.display = currentStep === steps.length - 1 ? "inline-block" : "none";
                nextBtn.textContent = currentStep === steps.length - 1 ? "Complete Campaign" : "Next";

                // Special handling for map step
                if (currentStep === 2) {
                    // Wait and trigger the map to resize
                    setTimeout(() => {
                        if (typeof map !== 'undefined' && map && typeof map.resize === 'function') {
                            map.resize();
                            console.log('Map resized for step 3');
                        } else {
                            console.log('Map not available for resize');
                        }
                    }, 300);
                }
            }

            // Next button functionality
            nextBtn.addEventListener("click", (e) => {
                e.preventDefault();
                
                if (validateCurrentStep()) {
                    if (currentStep < steps.length - 1) {
                        showStep(currentStep + 1);
                    } else {
                        // Final step - submit the form
                        document.getElementById('multiStepForm').submit();
                    }
                }
            });

            // Previous button functionality
            prevBtn.addEventListener("click", (e) => {
                e.preventDefault();
                if (currentStep > 0) {
                    showStep(currentStep - 1);
                }
            });

            // Save as Draft button functionality
            const saveDraftBtn = document.getElementById("save-draft");
            saveDraftBtn.addEventListener("click", (e) => {
                e.preventDefault();
                
                if (validateCurrentStep()) {
                    // Set the draft flag
                    document.getElementById('save_as_draft').value = '1';
                    
                    // Submit the form
                    document.getElementById('multiStepForm').submit();
                }
            });

            // Step link functionality
            stepLinks.forEach((link) => {
                link.addEventListener("click", (event) => {
                    event.preventDefault(); // Prevent default link behavior
                    const stepIndex = parseInt(link.getAttribute("data-step"), 10);
                    
                    // Only allow navigation if all previous steps are valid
                    let canNavigate = true;
                    for (let i = 0; i < stepIndex; i++) {
                        const originalStep = currentStep;
                        currentStep = i;
                        if (!validateCurrentStep()) {
                            canNavigate = false;
                            break;
                        }
                        currentStep = originalStep;
                    }
                    
                    if (canNavigate && stepIndex !== currentStep) {
                        showStep(stepIndex);
                    } else if (!canNavigate) {
                        alert("Please complete all previous steps before proceeding.");
                    }
                });
            });

            // Initialize first step
            showStep(0);
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
                const qrcodeOverlay = document.querySelector(".qr-overlay");
                const qrStatus = document.getElementById("qr-status");
                const previewWrapper = document.querySelector('.preview-wrapper');
                
                previewImage.src = "assets/images/addbase.png";
                previewImage.style.display = "block";
                previewVideo.style.display = "none";
                removeMediaBtn.style.display = "none";
                
                // Reset preview wrapper background
                previewWrapper.style.background = '#f8f9fa';
                
                // Hide QR code overlay when media is removed
                qrcodeOverlay.style.display = "none";
                qrStatus.innerHTML = '<small class="text-muted">Generate QR code to see it overlaid on your media</small>';
            };

            function handleFiles(files) {
                if (!files.length) return;
                const file = files[0];
                const reader = new FileReader();
                reader.onload = (event) => {
                    const previewWrapper = document.querySelector('.preview-wrapper');
                    
                    if (file.type.startsWith("video/")) {
                        videoSource.src = event.target.result;
                        previewVideo.load();
                        previewVideo.style.display = "block";
                        previewImage.style.display = "none";
                        // Add some styling for video
                        previewWrapper.style.background = '#000';
                    } else if (file.type.startsWith("image/")) {
                        previewImage.src = event.target.result;
                        previewImage.style.display = "block";
                        previewVideo.style.display = "none";
                        // Reset background for images
                        previewWrapper.style.background = '#f8f9fa';
                    }
                    removeMediaBtn.style.display = "block";
                };
                reader.readAsDataURL(file);
            }
        });
    </script>

</body>

</html>
