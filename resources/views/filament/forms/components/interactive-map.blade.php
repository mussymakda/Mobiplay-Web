@php
    $statePath = $getStatePath();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div 
        x-data="interactiveMap({
            latitude: @js($getState()['latitude'] ?? 40.7128),
            longitude: @js($getState()['longitude'] ?? -74.0060), 
            radius: @js($getState()['radius_miles'] ?? 5),
            statePath: @js($statePath)
        })"
        x-init="initMap()"
        wire:ignore
        class="relative"
    >
        <!-- Search Box -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Search Location</label>
            <input 
                type="text" 
                x-ref="searchBox"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Search for a location..."
            />
        </div>

        <!-- Map Container -->
        <div 
            id="map-{{ $getId() }}" 
            x-ref="mapContainer"
            class="w-full h-96 bg-gray-100 border border-gray-300 rounded-lg shadow-sm"
            style="min-height: 400px;"
        ></div>
        
        <!-- Map Controls -->
        <div class="mt-4 p-4 bg-gray-50 rounded-lg border">
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                    <input 
                        type="number" 
                        x-model="latitude"
                        step="0.000001"
                        @input="updateFromInputs()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="40.7128"
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                    <input 
                        type="number" 
                        x-model="longitude"
                        step="0.000001"
                        @input="updateFromInputs()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="-74.0060"
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Radius (Miles)</label>
                    <input 
                        type="number" 
                        x-model="radius"
                        min="0.5"
                        max="50"
                        step="0.5"
                        @input="updateRadius()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="5"
                    />
                </div>
            </div>
            
            <div class="mt-4 flex space-x-2">
                <button 
                    type="button"
                    @click="getCurrentLocation()"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                >
                    📍 Use Current Location
                </button>
            </div>
        </div>
        
        <!-- Instructions -->
        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
            <p class="text-sm text-blue-800">
                <strong>Instructions:</strong> Click on the map to set your target location, or search for a location above. 
                The circle shows the area where your ads will be displayed to users within the specified radius.
            </p>
        </div>
    </div>
</x-dynamic-component>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<script>
function interactiveMap(config) {
    return {
        latitude: parseFloat(config.latitude) || 40.7128,
        longitude: parseFloat(config.longitude) || -74.0060,
        radius: parseFloat(config.radius) || 5,
        statePath: config.statePath,
        map: null,
        marker: null,
        circle: null,
        searchBox: null,

        initMap() {
            // Wait for the element to be ready
            this.$nextTick(() => {
                if (this.map) return; // Prevent double initialization
                
                // Initialize Leaflet map
                this.map = L.map(this.$refs.mapContainer).setView([this.latitude, this.longitude], 13);
                
                // Add OpenStreetMap tiles
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors',
                    maxZoom: 19
                }).addTo(this.map);

                // Add marker
                this.marker = L.marker([this.latitude, this.longitude], {
                    draggable: true
                }).addTo(this.map);

                // Add radius circle (convert miles to meters: 1 mile = 1609.34 meters)
                this.circle = L.circle([this.latitude, this.longitude], {
                    radius: this.radius * 1609.34,
                    color: '#ef4444',
                    fillColor: '#ef4444',
                    fillOpacity: 0.2,
                    weight: 2
                }).addTo(this.map);

                // Setup Google Places search
                this.initSearchBox();

                // Handle map click
                this.map.on('click', (e) => {
                    this.updateLocation(e.latlng.lat, e.latlng.lng);
                });

                // Handle marker drag
                this.marker.on('dragend', (e) => {
                    const position = e.target.getLatLng();
                    this.updateLocation(position.lat, position.lng);
                });

                // Update form fields on initialization
                this.updateFormFields();
            });
        },

        initSearchBox() {
            if (typeof google !== 'undefined' && google.maps && google.maps.places) {
                const searchInput = this.$refs.searchBox;
                this.searchBox = new google.maps.places.Autocomplete(searchInput);
                
                this.searchBox.addListener('place_changed', () => {
                    const place = this.searchBox.getPlace();
                    if (place.geometry && place.geometry.location) {
                        const lat = place.geometry.location.lat();
                        const lng = place.geometry.location.lng();
                        this.updateLocation(lat, lng);
                        this.map.setView([lat, lng], 15);
                    }
                });
            }
        },

        updateLocation(lat, lng) {
            this.latitude = parseFloat(lat.toFixed(6));
            this.longitude = parseFloat(lng.toFixed(6));
            
            // Update marker position
            this.marker.setLatLng([this.latitude, this.longitude]);
            
            // Update circle position
            this.circle.setLatLng([this.latitude, this.longitude]);
            
            // Update form fields
            this.updateFormFields();
        },

        updateRadius() {
            if (this.circle) {
                // Convert miles to meters for Leaflet circle
                this.circle.setRadius(this.radius * 1609.34);
            }
            this.updateFormFields();
        },

        updateFromInputs() {
            if (this.map && this.marker && this.circle) {
                this.marker.setLatLng([this.latitude, this.longitude]);
                this.circle.setLatLng([this.latitude, this.longitude]);
                this.map.setView([this.latitude, this.longitude], this.map.getZoom());
            }
            this.updateFormFields();
        },

        updateFormFields() {
            // Update the actual form fields
            const latField = document.querySelector('input[name="latitude"]');
            const lngField = document.querySelector('input[name="longitude"]'); 
            const radiusField = document.querySelector('input[name="radius_miles"]');
            
            if (latField) latField.value = this.latitude;
            if (lngField) lngField.value = this.longitude;
            if (radiusField) radiusField.value = this.radius;

            // Trigger Livewire update
            if (latField) latField.dispatchEvent(new Event('input'));
            if (lngField) lngField.dispatchEvent(new Event('input'));
            if (radiusField) radiusField.dispatchEvent(new Event('input'));
        },

        getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        this.updateLocation(lat, lng);
                        this.map.setView([lat, lng], 15);
                    },
                    (error) => {
                        alert('Could not get your location: ' + error.message);
                    }
                );
            } else {
                alert('Geolocation is not supported by this browser.');
            }
        }
    }
}
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places"></script>
@endpush
