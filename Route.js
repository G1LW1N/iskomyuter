var map;
var routePolylines = []; // Changed to array to store multiple routes
var startMarker;
var endMarker;
var getUser = "";
var getUserId = "";
var getDistanceinkms = 0;
var getFare = 0;
var gerRouteId = 0;
var getTransportMode = '';
var getDuration = '';

window.onload = function () {
    console.log('=== Page loaded, initializing map ===');
    
    var userElement = document.getElementById('user');
    var userIdElement = document.getElementById('userid');
    
    if (userElement) getUser = userElement.value;
    if (userIdElement) getUserId = userIdElement.value;
    
    // Check if Leaflet is loaded
    if (typeof L === 'undefined') {
        console.error('Leaflet library not loaded!');
        return;
    }
    
    console.log('Leaflet loaded successfully');
    
    // Check if map container exists
    var mapContainer = document.getElementById('googleMap');
    if (!mapContainer) {
        console.error('Map container #googleMap not found!');
        return;
    }
    
    console.log('Map container found:', mapContainer);
    
    try {
        // Initialize map centered on Manila, Philippines
        map = L.map('googleMap').setView([14.6760, 121.0437], 12);
        console.log('Map object created:', map);
        
        // Add OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);
        
        console.log('Map initialized successfully!');
        
        // Force map to refresh after a short delay
        setTimeout(function() {
            map.invalidateSize();
            console.log('Map size invalidated/refreshed');
        }, 100);
    } catch (error) {
        console.error('Error initializing map:', error);
        alert('Error initializing map: ' + error.message);
    }
};

function calcRoute() {
    console.log('=== calcRoute called ===');
    
    // Check if map exists
    if (!map) {
        console.error('Map is not initialized');
        return;
    }
    
    var fromInput = document.getElementById('from').value;
    var toInput = document.getElementById('to').value;
    var selectedTransportation = document.querySelector('input[name="transportation"]:checked').value;

    console.log('From:', fromInput);
    console.log('To:', toInput);
    console.log('Transport:', selectedTransportation);

    if (!fromInput || !toInput) {
        alert('Please enter both starting location and destination');
        return;
    }

    getTransportMode = selectedTransportation;

    // Clear existing routes and markers
    if (routePolylines.length > 0) {
        routePolylines.forEach(function(polyline) {
            map.removeLayer(polyline);
        });
        routePolylines = [];
    }
    if (startMarker) map.removeLayer(startMarker);
    if (endMarker) map.removeLayer(endMarker);

    // Hide save button until route is calculated
    var saveButton = document.querySelector('.btn-save');
    if (saveButton) {
        saveButton.classList.remove('show');
    }

    var outputPanel = document.getElementById('output');
    outputPanel.innerHTML = '<div class="calculating">Calculating route...</div>';

    console.log('Starting geocoding via proxy...');
    
    // Geocode using PHP proxy to avoid CORS
    geocodeAddress(fromInput, function(fromResults) {
        console.log('From geocode results:', fromResults);
        
        if (fromResults && fromResults.length > 0) {
            console.log('From location found, geocoding destination...');
            geocodeAddress(toInput, function(toResults) {
                console.log('To geocode results:', toResults);
                
                if (toResults && toResults.length > 0) {
                    console.log('Both locations found!');
                    var fromLatLng = {lat: fromResults[0].lat, lng: fromResults[0].lon};
                    var toLatLng = {lat: toResults[0].lat, lng: toResults[0].lon};
                    
                    console.log('From coordinates:', fromLatLng);
                    console.log('To coordinates:', toLatLng);

                    startMarker = L.marker([fromLatLng.lat, fromLatLng.lng], {
                        icon: L.icon({
                            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41]
                        })
                    }).addTo(map).bindPopup('<b>Start:</b> ' + fromInput);

                    endMarker = L.marker([toLatLng.lat, toLatLng.lng], {
                        icon: L.icon({
                            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41]
                        })
                    }).addTo(map).bindPopup('<b>Destination:</b> ' + toInput);

                    // Build OSRM URL for route calculation with alternatives
                    var osrmUrl = 'https://router.project-osrm.org/route/v1/driving/' + 
                                  fromLatLng.lng + ',' + fromLatLng.lat + ';' + 
                                  toLatLng.lng + ',' + toLatLng.lat + 
                                  '?overview=full&geometries=geojson&alternatives=true&steps=true';

                    console.log('Fetching routes from:', osrmUrl);

                    fetch(osrmUrl)
                        .then(response => {
                            console.log('OSRM response status:', response.status);
                            return response.json();
                        })
                        .then(data => {
                            console.log('OSRM data:', data);
                            
                            if (data.code === 'Ok' && data.routes && data.routes.length > 0) {
                                console.log('Found ' + data.routes.length + ' route(s)!');
                                
                                // Colors for different routes
                                var colors = ['#2563eb', '#dc2626', '#16a34a', '#9333ea'];
                                var bounds = L.latLngBounds();
                                
                                // Draw all routes
                                data.routes.forEach(function(route, index) {
                                    var latlngs = route.geometry.coordinates.map(coord => [coord[1], coord[0]]);
                                    
                                    var polyline = L.polyline(latlngs, {
                                        color: colors[index % colors.length],
                                        weight: index === 0 ? 6 : 4,
                                        opacity: index === 0 ? 0.8 : 0.6
                                    }).addTo(map);
                                    
                                    routePolylines.push(polyline);
                                    
                                    // Extend bounds to include this route
                                    latlngs.forEach(function(latlng) {
                                        bounds.extend(latlng);
                                    });
                                });
                                
                                console.log('All routes drawn on map');
                                
                                // Fit map to show all routes
                                map.fitBounds(bounds, { padding: [50, 50] });
                                console.log('Map bounds fitted');
                                
                                processMultipleRoutes(data.routes, fromInput, toInput, outputPanel);
                            } else {
                                console.error('OSRM error:', data);
                                outputPanel.innerHTML = '<div class="alert alert-danger">Could not find route.</div>';
                            }
                        })
                        .catch(error => {
                            console.error('Route fetch error:', error);
                            outputPanel.innerHTML = '<div class="alert alert-danger">Error calculating route.</div>';
                        });
                } else {
                    alert('Destination not found.');
                }
            });
        } else {
            alert('Starting location not found.');
        }
    });
}

// Function to process multiple routes and display all options
function processMultipleRoutes(routes, fromInput, toInput, outputPanel) {
    var selectedTransportation = document.querySelector('input[name="transportation"]:checked').value;
    
    var isStudentSeniorPWD = confirm('Are you a student, senior citizen, or PWD (ok-yes, cancel-no)?');
    var discount = isStudentSeniorPWD ? 0.8 : 1.0;
    
    // Display all routes
    var html = '<div class="route-details">';
    html += '<h4><strong>' + routes.length + ' ROUTE OPTIONS (' + getTransportModeName(selectedTransportation) + '):</strong></h4>';
    html += '<p><strong>FROM:</strong> ' + fromInput + '</p>';
    html += '<p><strong>TO:</strong> ' + toInput + '</p>';
    html += '<hr>';
    
    var colors = ['Blue', 'Red', 'Green', 'Purple'];
    
    routes.forEach(function(route, index) {
        var distanceInKm = route.distance / 1000;
        var totalSeconds = route.duration;
        var hours = Math.floor(totalSeconds / 3600);
        var minutes = Math.floor((totalSeconds % 3600) / 60);
        var durationText = hours > 0 ? hours + ' hr ' + minutes + ' min' : minutes + ' min';
        
        var fareRatePerKm = getFareRate(selectedTransportation);
        var totalFare = Math.max(fareRatePerKm * distanceInKm, 13) * discount;
        
        // Store first route data for saving
        if (index === 0) {
            getDuration = durationText;
            getDistanceinkms = distanceInKm.toFixed(2);
            getFare = totalFare.toFixed(2);
        }
        
        var colorClass = index === 0 ? 'primary' : 'secondary';
        var colorName = colors[index % colors.length];
        
        html += '<div class="route-option route-' + index + '" style="border-left: 4px solid ' + 
                (['#2563eb', '#dc2626', '#16a34a', '#9333ea'][index % 4]) + '; padding-left: 15px; margin-bottom: 20px;">';
        html += '<h5><strong>Route ' + (index + 1) + ' (' + colorName + ' Line)</strong></h5>';
        html += '<p><strong>DISTANCE:</strong> ' + distanceInKm.toFixed(2) + ' km</p>';
        html += '<p><strong>DURATION:</strong> ' + durationText + '</p>';
        html += '<p><strong>FARE:</strong> PHP ' + totalFare.toFixed(2) + '</p>';
        
        if (index === 0) {
            html += '<p style="color: #16a34a; font-weight: bold;"><i class="bx bx-check-circle"></i> Recommended Route</p>';
        }
        
        html += '</div>';
    });
    
    html += '<p style="color: #666; font-style: italic; font-size: 0.9em;"><i class="bx bx-info-circle"></i> Different colored lines on the map show route alternatives</p>';
    html += '</div>';
    
    outputPanel.innerHTML = html;
    
    // Show the save button in the form
    var saveButton = document.querySelector('.btn-save');
    console.log('Save button element:', saveButton);
    console.log('getUserId:', getUserId);
    
    if (saveButton) {
        saveButton.classList.add('show');
        console.log('Save button shown');
    } else {
        console.error('Save button not found!');
    }
    
    console.log('All routes displayed successfully');
}

// Keep the old function for backwards compatibility
function processRouteData(route, fromInput, toInput, outputPanel) {
    var selectedTransportation = document.querySelector('input[name="transportation"]:checked').value;
    var distanceInKm = route.distance / 1000;
    var totalSeconds = route.duration;
    var hours = Math.floor(totalSeconds / 3600);
    var minutes = Math.floor((totalSeconds % 3600) / 60);
    var durationText = hours > 0 ? hours + ' hr ' + minutes + ' min' : minutes + ' min';
    
    getDuration = durationText;
    var fareRatePerKm = getFareRate(selectedTransportation);
    var totalFare = Math.max(fareRatePerKm * distanceInKm, 13);

    var isStudentSeniorPWD = confirm('Are you a student, senior citizen, or PWD (ok-yes, cancel-no)?');
    if (isStudentSeniorPWD) {
        totalFare *= 0.8;
    }

    getDistanceinkms = distanceInKm.toFixed(2);
    getFare = totalFare.toFixed(2);

    outputPanel.innerHTML = '<div class="route-details">';
    outputPanel.innerHTML += '<h4><strong>ROUTE (' + getTransportModeName(selectedTransportation) + '):</strong></h4>';
    outputPanel.innerHTML += '<p><strong>FROM:</strong> ' + fromInput + '</p>';
    outputPanel.innerHTML += '<p><strong>TO:</strong> ' + toInput + '</p>';
    outputPanel.innerHTML += '<p><strong>DISTANCE:</strong> ' + getDistanceinkms + ' km</p>';
    outputPanel.innerHTML += '<p><strong>DURATION:</strong> ' + durationText + '</p>';
    outputPanel.innerHTML += '<p><strong>FARE:</strong> PHP ' + getFare + '</p>';
    outputPanel.innerHTML += '<p style="color: #2563eb;"><i class="bx bx-info-circle"></i> Blue route line shown on map</p>';
    
    if (getUserId) {
        outputPanel.innerHTML += '<button type="button" class="btn-save" onclick="saveRoute()"><i class="bx bx-save"></i> Save Route</button>';
    }
    outputPanel.innerHTML += '</div>';
}

function getFareRate(transportationMode) {
    switch (transportationMode) {
        case 'bus': return 13;
        case 'jeepney': return 15;
        case 'driving': return 15;
        case 'all': return 13;
        default: return 13;
    }
}

function getTransportModeName(mode) {
    switch (mode) {
        case 'bus': return 'BUS / JEEPNEY';
        case 'jeepney': return 'LRT / MRT / PNR';
        case 'driving': return 'MOTORCYCLE / TAXI / CAR';
        case 'all': return 'ALL MODES';
        default: return mode.toUpperCase();
    }
}

async function saveRoute() {
    var outputPanel = document.getElementById('output2');
    outputPanel.innerHTML = '';

    var formData = new FormData();
    formData.append('userid', getUserId);
    formData.append('start', document.getElementById('from').value);
    formData.append('destination', document.getElementById('to').value);
    formData.append('distance', getDistanceinkms);
    formData.append('fare', getFare);
    formData.append('transportmode', getTransportMode);
    formData.append('duration', getDuration);
    formData.append('routeid', 0);

    var response = await fetch('controllers/saveroute.php', {
        method: 'POST',
        mode: "cors",
        body: formData
    });

    if (response.ok) {
        outputPanel.innerHTML += '<br><strong class="alert alert-success">Route saved successfully</strong><br>';
    } else {
        outputPanel.innerHTML += '<br><strong class="alert alert-danger">Error: Information not saved</strong><br>';
    }
}

// Helper function to geocode addresses using PHP proxy
function geocodeAddress(address, callback) {
    fetch('geocode.php?q=' + encodeURIComponent(address))
        .then(response => response.json())
        .then(data => {
            console.log('Geocode response for "' + address + '":', data);
            if (data.error) {
                console.error('Geocoding error:', data.error);
                callback(null);
            } else {
                callback(data);
            }
        })
        .catch(error => {
            console.error('Geocode fetch error:', error);
            callback(null);
        });
}
