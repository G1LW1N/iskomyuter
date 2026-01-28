<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if (isset($_GET['q'])) {
    $query = urlencode($_GET['q']);
    $url = "https://nominatim.openstreetmap.org/search?q={$query}&limit=5&format=json&addressdetails=1";
    
    // Set custom headers for Nominatim
    $options = [
        'http' => [
            'method' => 'GET',
            'header' => "User-Agent: Iskomyuter.ph Route Planner\r\n"
        ]
    ];
    
    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    
    if ($response !== false) {
        echo $response;
    } else {
        echo json_encode(['error' => 'Geocoding failed']);
    }
} else {
    echo json_encode(['error' => 'No query provided']);
}
?>
