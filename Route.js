var directionsService, directionsRenderer;
var autocompleteFrom, autocompleteTo;
var map; // Move map variable to the global scope
var getUser = document.getElementById('user').value;
var getUserId = document.getElementById('userid').value;
var getDistanceinkms = 0;
var getFare = 0;
var gerRouteId = 0;
var getTransportMode = ''; // variable to store transportation mode
var getDuration = ''; // variable to store duration

window.onload = function () {
    var output = document.querySelector('#output');

    var mylatlang = { lat: 14.6760, lng: 121.0437 };
    var mapOptions = {
        center: mylatlang,
        zoom: 12,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    // Create Map
    map = new google.maps.Map(document.getElementById('googleMap'), mapOptions);
    // Initialize directions service and renderer
    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer({
        map: map,
        panel: document.getElementById('output') // Display route details in this panel
    });

    // Create Autocomplete for Origin input
    if (document.getElementById('from')) {    
        autocompleteFrom = new google.maps.places.Autocomplete(
            document.getElementById('from'),
            { types: ['geocode'] }
        );
    }
    // Create Autocomplete for Destination input
    if (document.getElementById('to')) {   
        autocompleteTo = new google.maps.places.Autocomplete(
            document.getElementById('to'),
            { types: ['geocode'] }
        );
    }
};

function calcRoute() {
    var userId = getUserId;
    var userName = getUser;
    var fromInput = document.getElementById('from').value;
    var toInput = document.getElementById('to').value;
    var selectedTransportation = document.querySelector('input[name="transportation"]:checked').value;

    getTransportMode = selectedTransportation; 

    // If "All Modes" is selected, get all transportation modes
    var allModes = ['driving', 'jeepney/bus', 'train', 'motorcycle taxi'];

    // Clear existing directions and polylines on the map
    directionsRenderer.set('directions', null);

    // Clear the output panel
    var outputPanel = document.getElementById('output');
    outputPanel.innerHTML = '';

    // Process each selected transportation mode or all modes
    var modesToProcess = selectedTransportation === 'all' ? allModes : [selectedTransportation];

    modesToProcess.forEach(function (currentMode) {
        var request = {
            origin: fromInput,
            destination: toInput,
            travelMode: google.maps.TravelMode.DRIVING
        };

        if (currentMode !== 'driving') {
            request.travelMode = google.maps.TravelMode.TRANSIT;
            request.transitOptions = {
                modes: [currentMode === 'jeepney/bus' ? google.maps.TransitMode.BUS :
                        currentMode === 'train' ? google.maps.TransitMode.TRAM :
                        google.maps.TransitMode.DRIVING]
            };
        }

        directionsService.route(request, function (result, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                // Extract duration information
                getDuration = result.routes[0].legs[0].duration.text;

                // Generate a unique color for each transportation mode
                var routeColor = getRandomColor();

                // Set directions on the DirectionsRenderer with the specified route color
                directionsRenderer.setDirections(result);
                directionsRenderer.setOptions({ polylineOptions: { strokeColor: routeColor } });

                // Display route details in the output panel
                var distanceInKm = result.routes[0].legs[0].distance.value / 1000;
                var fareRatePerKm = getFareRate(currentMode);
                var totalFare = Math.max(fareRatePerKm * distanceInKm, 13); // Minimum fare of 13 pesos

                var discountPercentage = 20;
                var isStudentSeniorPWD = confirm('Are you a student, senior citizen, or PWD (ok-yes, cancel-no)?');

                if (isStudentSeniorPWD) {
                    totalFare *= (1 - discountPercentage / 100);
                }

                getDistanceinkms = distanceInKm.toFixed(2);
                getFare = totalFare.toFixed(2);

                outputPanel.innerHTML += '<strong>ROUTE (' + currentMode.toUpperCase() + '):</strong><br>';
                outputPanel.innerHTML += '<strong>GET ON:</strong> ' + result.routes[0].legs[0].start_address + '<br>';
                outputPanel.innerHTML += '<strong>GET OFF:</strong> ' + result.routes[0].legs[0].end_address + '<br>';
                outputPanel.innerHTML += '<strong>DISTANCE:</strong> ' + distanceInKm.toFixed(2) + ' km<br>';
                outputPanel.innerHTML += '<strong>DURATION:</strong> ' + durationText + '<br>'; // Display duration
                outputPanel.innerHTML += '<strong>FARE:</strong> PHP ' + totalFare.toFixed(2) + '<br>';
                outputPanel.innerHTML += '--------------------------<br>';
            } else {
                // Handle error, e.g., show an alert
                alert('Error calculating route (' + currentMode.toUpperCase() + '): ' + status);
            }
        });
    });
}

// Function to get a random color for polylines
function getRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

// Function to get fare rate based on the transportation mode
function getFareRate(transportationMode) {
    switch (transportationMode) {
        case 'jeepney/bus':
            return 13; // Adjust bus fare rate
        case 'train':
            return 15; // Adjust train fare rate
        case 'motorcycle taxi':
            return 15; // Adjust motorcycle taxi fare rate
        default:
            return 13; // Default minimum fare rate per kilometer
    }
}

async function saveRoute() {
    // Clear the output panel
    var outputPanel = document.getElementById('output2');
    outputPanel.innerHTML = '';

    var formData = new FormData();
    formData.append('userid', getUserId);
    formData.append('start', document.getElementById('from').value);
    formData.append('destination', document.getElementById('to').value);
    formData.append('distance', getDistanceinkms);
    formData.append('fare', getFare);
    formData.append('transportmode', getTransportMode); // Append transportation mode
    formData.append('duration', getDuration); // Append duration

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