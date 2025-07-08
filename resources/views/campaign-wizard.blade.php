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
</head>
<style>
    .advertisement img {
        width: 100%;
        height: auto;
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
                        <span>Goal</span>
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
                        <span>Priority</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <section id="content-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-14">
                    <form id="multiStepForm">
                        {{-- Step 1: Goals --}}
                        <div class="step" id="step-1">
                            <section id="content-wrapper">
                                <div class="container">
                                    <div class="row justify-content-center campe-list">
                                        <div class="col-lg-10 col-xl-8">
                                            <div class="choose-campgain">
                                                <img src="assets/images/campaign-icon.svg">
                                                <h2>Create a Campaign</h2>
                                                <p>Choose a Campaign Goal</p>
                                            </div>
                                            <div class="row justify-content-center">
                                                <div class="col-lg-4">
                                                    <a href="#" class="campe-box">
                                                        <div class="camp-img">
                                                            <img src="assets/images/c1.svg">
                                                        </div>
                                                        <div class="camp-title">
                                                            <h3>Brand Awareness</h3>
                                                            <p>Connect with your audience and make a lasting impression
                                                            </p>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-lg-4">
                                                    <a href="#" class="campe-box">
                                                        <div class="camp-img">
                                                            <img src="assets/images/c2.svg">
                                                        </div>
                                                        <div class="camp-title">
                                                            <h3>In-Person Visits</h3>
                                                            <p>Increase visitors to your location</p>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-lg-4">
                                                    <a href="#" class="campe-box">
                                                        <div class="camp-img">
                                                            <img src="assets/images/c3.svg">
                                                        </div>
                                                        <div class="camp-title">
                                                            <h3>Mobile Conversions</h3>
                                                            <p>Create leads and increase mobile visits to your website
                                                                or app</p>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="form-group mb-3 mt-3">
                                                <input type="text" name="" class="form-control"
                                                    placeholder="Enter Campaign Name">
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
                                                                        <input type="text"
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
                                                        <h6>Preview</h6>
                                                        <div id="preview-container" class="text-center">
                                                            <img id="preview-image" src="assets/images/addbase.png"
                                                                alt="assets/images/addbase.png"
                                                                style=" max-width: 100%;" />
                                                            <video id="preview-video" controls
                                                                style="display: none; max-width: 100%;">
                                                                <source id="video-source" src=""
                                                                    type="video/mp4">
                                                                Your browser does not support the video tag.
                                                            </video>
                                                            <span id="remove-media" class=" mt-2"
                                                                style="display: none;">Remove Media</button>
                                                        </div>
                                                    </div>
                                                    <div id="qrcode" style="margin-top: 20px;"></div>
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
                                            <div class="col-lg-4">
                                                <div class="spend-box" style="background-image: url('{{ asset('assets/images/bg1.png') }}');">
                                                    <div class="priority-badge">PRIORITY : LOW</div>
                                                    <h5>Basic Package</h5>
                                                    <p>Priority Level: Low</p>
                                                    <p>Your Ads will be displayed on Non-Rush hours</p>
                                                </div>
                                            </div>
                                            <div class="col-lg-4" >
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

        // Function to draw a circle on the map
        function drawCircle(radius) {
            var radiusInMeters = radius * 1000; // Convert km to meters
            var circle = turf.circle(circleCoordinates, radiusInMeters, {
                steps: 64,
                units: 'meters',
            });

            // Remove the existing circle layer if it exists
            if (map.getLayer(radiusLayerId)) {
                map.removeLayer(radiusLayerId);
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
                    'fill-color': 'rgba(0, 0, 255, 0.5)', // Circle color
                    'fill-opacity': 0.5 // Circle opacity
                }
            });
        }

        // Function to handle radius slider
        document.getElementById('radius-slider').addEventListener('input', function() {
            radius = this.value; // Update the radius value
            document.getElementById('radius-value').value = radius;
            drawCircle(radius); // Redraw the circle with the updated radius
        });

        // Initial draw of the circle
        drawCircle(radius);
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
        });
    </script>
    <script type="text/javascript">
        document.getElementById("generateQr").onclick = function() {
            const ctaUrl = document.getElementById("ctaUrl").value.trim();
            const qrcodeContainer = document.getElementById("qrcode");

            if (!ctaUrl) {
                alert("Please enter a URL to generate the QR code.");
                return;
            }

            qrcodeContainer.innerHTML = ""; // Clear previous QR code
            try {
                new QRCode(qrcodeContainer, {
                    text: ctaUrl,
                    width: 128,
                    height: 128,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H // Fixed to remove comma error
                });
            } catch (error) {
                console.error("QR Code generation error:", error);
                alert("An error occurred while generating the QR code. Please try again.");
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
            // Function to show a specific step
            function showStep(stepIndex) {
                steps[currentStep].style.display = "none"; // Hide current step
                currentStep = stepIndex; // Update current step
                steps[currentStep].style.display = "block"; // Show new step

                // Update active step link
                stepLinks.forEach((link, index) => {
                    link.classList.toggle("active", index === currentStep);
                });
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

</body>

</html>
