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
        height: 400px;
        border-radius: 8px;
        border: 1px solid #dee2e6;
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
        overflow: hidden;
        border-radius: 15px;
    }
    
    .ad-preview-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 15px;
    }
    
    .tablet-info {
        margin-top: 15px;
        text-align: center;
    }
    
    .tablet-info h5 {
        color: #333;
        font-weight: 600;
        margin-bottom: 8px;
    }
    
    .tablet-info p {
        color: #666;
        font-size: 14px;
        margin-bottom: 0;
    }
    
    /* Campaign Performance Stats */
    .performance-stats {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
    }
    
    .stat-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #e9ecef;
    }
    
    .stat-row:last-child {
        border-bottom: none;
    }
    
    .stat-label {
        font-weight: 500;
        color: #495057;
    }
    
    .stat-value {
        font-weight: 600;
        color: #007bff;
    }
    
    /* Status Badge */
    .campaign-status {
        display: inline-block;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 10px;
    }
    
    .status-active { background-color: #d4edda; color: #155724; }
    .status-paused { background-color: #fff3cd; color: #856404; }
    .status-draft { background-color: #f8d7da; color: #721c24; }
    .status-pending { background-color: #d1ecf1; color: #0c5460; }
    
    /* Display Fields */
    .display-field {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 5px;
        padding: 10px 15px;
        margin-top: 5px;
        font-weight: 500;
        color: #495057;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
</style>
</head>

<body>
    <div id="navbar-wrapper">
        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a href="#" class="navbar-brand" id="sidebar-toggle"><i class="fa fa-bars"></i></a>
                    <div class="right-nav">
                        <a href="#" class="notification-link"><img src="assets/images/notification.svg" /></a>
                        <div class="lang-menu">
                            <a href="{{ route('switchLang', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'active' : '' }}"><img src="assets/images/us.svg" alt="English"> EN</a>
                            <a href="{{ route('switchLang', 'es') }}" class="{{ app()->getLocale() == 'es' ? 'active' : '' }}"><img style="height: 25px; width: 25px;" class="auto" src="assets/images/mexico.png" alt="Español"> ES</a>
                        </div>
                        <a href="#" class="profile-name"><span>{{ Auth::user()->name }}</span> <img
                                src="{{ Auth::user()->profile_image_url }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;" /></a>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <div class="step-line">
        <a href="{{ route('campaigns.index') }}" class="step-close">Back to Campaigns <img src="assets/images/close.svg" /></a>
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
                        <span>Details</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <section id="content-wrapper" class="campaign-wizard">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-14">
                    {{-- Step 1: Creative --}}
                    <div class="step" id="step-1">
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <label for="campaign_name" class="form-label fw-bold">Campaign Name</label>
                                    <div class="display-field">{{ $campaign->campaign_name }}</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label fw-bold">Campaign Status</label>
                                    <div>
                                        <span class="campaign-status status-{{ $campaign->status }}">
                                            {{ ucfirst($campaign->status) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label fw-bold">Media Type</label>
                                    <div class="display-field">{{ ucfirst($campaign->media_type) }}</div>
                                </div>

                                @if($campaign->cta_text)
                                <div class="form-group">
                                    <label class="form-label fw-bold">Call to Action Text</label>
                                    <div class="display-field">{{ $campaign->cta_text }}</div>
                                </div>
                                @endif

                                @if($campaign->cta_url)
                                <div class="form-group">
                                    <label class="form-label fw-bold">Call to Action URL</label>
                                    <div class="display-field"><a href="{{ $campaign->cta_url }}" target="_blank">{{ $campaign->cta_url }}</a></div>
                                </div>
                                @endif

                                @if($campaign->qr_position)
                                <div class="form-group">
                                    <label class="form-label fw-bold">QR Code Position</label>
                                    <div class="display-field">{{ ucfirst($campaign->qr_position) }}</div>
                                </div>
                                @endif

                                <!-- Performance Stats -->
                                <div class="performance-stats">
                                    <h6 class="mb-3">Campaign Performance</h6>
                                    <div class="stat-row">
                                        <span class="stat-label">Impressions</span>
                                        <span class="stat-value">{{ number_format($campaign->impressions) }}</span>
                                    </div>
                                    <div class="stat-row">
                                        <span class="stat-label">QR Scans</span>
                                        <span class="stat-value">{{ number_format($campaign->qr_scans) }}</span>
                                    </div>
                                    <div class="stat-row">
                                        <span class="stat-label">Scan Rate</span>
                                        <span class="stat-value">{{ $campaign->qr_scan_rate }}%</span>
                                    </div>
                                    <div class="stat-row">
                                        <span class="stat-label">Total Spent</span>
                                        <span class="stat-value">${{ number_format($campaign->spent, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="device-mockup">
                                    <div class="tablet-frame">
                                        <div class="tablet-screen">
                                            <div class="ad-preview-container">
                                                @if($campaign->media_type === 'image' && $campaign->media_path)
                                                    <img src="{{ asset('storage/' . $campaign->media_path) }}" alt="Campaign Creative" class="ad-preview-image">
                                                @elseif($campaign->media_type === 'video' && $campaign->media_path)
                                                    <video class="ad-preview-image" controls>
                                                        <source src="{{ asset('storage/' . $campaign->media_path) }}" type="video/mp4">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                @else
                                                    <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                                        <div class="text-center">
                                                            <i class="fas fa-image fa-3x mb-3"></i>
                                                            <p>No media uploaded</p>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tablet-info">
                                        <h5>{{ $campaign->campaign_name }}</h5>
                                        <p>This is how your ad will appear on mobile devices</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Step 2: Locations --}}
                    <div class="step" id="step-2" style="display: none;">
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Selected Location</label>
                                    <div class="display-field">{{ $campaign->location_name ?: 'Custom Location' }}</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label fw-bold">Coordinates</label>
                                    <div class="display-field">
                                        Latitude: {{ $campaign->latitude }}<br>
                                        Longitude: {{ $campaign->longitude }}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label fw-bold">Radius</label>
                                    <div class="display-field">{{ $campaign->radius_miles }} miles</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label fw-bold">Targeting Area</label>
                                    <div class="display-field">
                                        Coverage area of {{ number_format(3.14159 * pow($campaign->radius_miles, 2), 1) }} square miles
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div id="map"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Step 3: Details --}}
                    <div class="step" id="step-3" style="display: none;">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Campaign Budget</label>
                                    <div class="display-field">${{ number_format($campaign->budget, 2) }}</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label fw-bold">Amount Spent</label>
                                    <div class="display-field">${{ number_format($campaign->spent, 2) }}</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label fw-bold">Remaining Budget</label>
                                    <div class="display-field text-success">${{ number_format($campaign->remaining_budget, 2) }}</div>
                                </div>

                                @if($campaign->package)
                                <div class="form-group">
                                    <label class="form-label fw-bold">Package</label>
                                    <div class="display-field">{{ $campaign->package->name }}</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label fw-bold">Cost per QR Scan</label>
                                    <div class="display-field">${{ number_format($campaign->package->cost_per_qr_scan, 2) }}</div>
                                </div>
                                @endif
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Created Date</label>
                                    <div class="display-field">{{ $campaign->created_at->format('M d, Y g:i A') }}</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label fw-bold">Last Updated</label>
                                    <div class="display-field">{{ $campaign->updated_at->format('M d, Y g:i A') }}</div>
                                </div>

                                <!-- Budget Progress -->
                                <div class="form-group">
                                    <label class="form-label fw-bold">Budget Usage</label>
                                    <div class="progress mb-2" style="height: 20px;">
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: {{ $campaign->budget > 0 ? ($campaign->spent / $campaign->budget) * 100 : 0 }}%"
                                             aria-valuenow="{{ $campaign->spent }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="{{ $campaign->budget }}">
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $campaign->budget > 0 ? number_format(($campaign->spent / $campaign->budget) * 100, 1) : 0 }}% of budget used</small>
                                </div>

                                <!-- Action Buttons -->
                                <div class="form-group mt-4">
                                    <label class="form-label fw-bold">Campaign Actions</label>
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('campaigns.edit', $campaign) }}" class="btn btn-primary">
                                            <i class="fas fa-edit me-2"></i>Edit Campaign
                                        </a>
                                        
                                        @if($campaign->status === 'active')
                                            <form action="{{ route('campaigns.pause', $campaign) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-warning w-100">
                                                    <i class="fas fa-pause me-2"></i>Pause Campaign
                                                </button>
                                            </form>
                                        @elseif($campaign->status === 'paused')
                                            <form action="{{ route('campaigns.resume', $campaign) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success w-100">
                                                    <i class="fas fa-play me-2"></i>Resume Campaign
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="camp-grp-btn">
                        <a href="#" class="btn btn-secondary prev-btn" id="prev" style="display: none;">Back</a>
                        <a href="#" class="btn btn-primary next-btn" id="next">Next</a>
                        <a href="{{ route('campaigns.index') }}" class="btn btn-outline-secondary" id="back-to-campaigns" style="display: none;">Back to Campaigns</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Load jQuery before ion.rangeSlider -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/ion-rangeslider/js/ion.rangeSlider.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/turf/6.5.0/turf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const steps = document.querySelectorAll(".step");
            const stepLinks = document.querySelectorAll(".step-link");
            let currentStep = 0;
            const totalSteps = 3;

            // Initially show the first step
            steps[currentStep].style.display = "block";
            
            // Function to update button visibility based on current step
            function updateButtonVisibility() {
                const nextBtn = document.getElementById("next");
                const prevBtn = document.getElementById("prev");
                const backToCampaignsBtn = document.getElementById("back-to-campaigns");
                
                // Show/hide previous button based on step
                if (prevBtn) {
                    prevBtn.style.display = currentStep === 0 ? "none" : "inline-block";
                }
                
                // Show/hide next button and back to campaigns button based on step
                if (currentStep === totalSteps - 1) {
                    // Last step - hide next, show back to campaigns
                    if (nextBtn) nextBtn.style.display = "none";
                    if (backToCampaignsBtn) backToCampaignsBtn.style.display = "inline-block";
                } else {
                    // Not last step - show next, hide back to campaigns
                    if (nextBtn) nextBtn.style.display = "inline-block";
                    if (backToCampaignsBtn) backToCampaignsBtn.style.display = "none";
                }
            }

            // Initially set button visibility
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
                    
                    // Resize map when showing locations step
                    if (stepIndex === 1 && typeof map !== 'undefined' && map.loaded()) {
                        setTimeout(() => {
                            map.resize();
                        }, 100);
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

        // Initialize Mapbox for step 2
        @if($campaign->latitude && $campaign->longitude)
        let map;
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Mapbox
            mapboxgl.accessToken = 'pk.eyJ1IjoibXVzc3ltYWtkYSIsImEiOiJjbTFldXAwM20wcGJkMmpxd2xxMW1qMWdiIn0.HzjAGCBaVUh-dSBLfMWAcg';
            
            map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/mapbox/streets-v11',
                center: [{{ $campaign->longitude }}, {{ $campaign->latitude }}],
                zoom: 12
            });

            // Add marker for campaign location
            new mapboxgl.Marker({ color: '#007bff' })
                .setLngLat([{{ $campaign->longitude }}, {{ $campaign->latitude }}])
                .addTo(map);

            // Add radius circle using Turf.js to create proper circle geometry
            map.on('load', function() {
                const center = [{{ $campaign->longitude }}, {{ $campaign->latitude }}];
                const radiusInMiles = {{ $campaign->radius_miles }};
                
                // Create a circle using Turf.js
                const circle = turf.circle(center, radiusInMiles, {
                    steps: 64,
                    units: 'miles'
                });

                map.addSource('radius-fill', {
                    'type': 'geojson',
                    'data': circle
                });

                map.addLayer({
                    'id': 'radius-fill',
                    'type': 'fill',
                    'source': 'radius-fill',
                    'paint': {
                        'fill-color': '#007bff',
                        'fill-opacity': 0.1
                    }
                });

                map.addLayer({
                    'id': 'radius-stroke',
                    'type': 'line',
                    'source': 'radius-fill',
                    'paint': {
                        'line-color': '#007bff',
                        'line-width': 2,
                        'line-opacity': 0.8
                    }
                });
            });
        });
        @endif
    </script>
</body>

</html>