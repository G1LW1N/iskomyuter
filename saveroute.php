<?php
// session_start();
// require_once "config.php";
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
header("Access-Control-Allow-Private-Network: true");

include("./config.php");

if (!isset($_POST["fare"])) {
    $msg = 'Complete information required before saving!';
    echo "<div class='alert alert-danger'>$msg</div>";
} else {
    $userid = $_POST['userid'];
    $start = $_POST['start'];
    $destination = $_POST['destination'];
    $distance = $_POST['distance'];
    $fare = $_POST['fare'];
    $routeid = $_POST['routeid'];
    $transportMode = $_POST['transportmode']; // Add transportation mode
    $duration = $_POST['duration']; // Add duration

    if ($routeid == 0) {
        $query = "INSERT INTO routes (userid, start, destination, distance, fare, transportMode, duration) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssssss", $userid, $start, $destination, $distance, $fare, $transportMode, $duration);
            mysqli_stmt_execute($stmt);
            $response = ['success' => true, 'message' => 'Route saved successfully'];
        } else {
            $response = ['success' => false, 'message' => 'Error. Information not saved'];
        }
    } else {
        $query = "UPDATE `routes` SET `userid`= ?, `start`= ?, `destination`= ?, `distance`= ?, `fare`= ?, `transportMode`= ?, `duration`= ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssssss", $userid, $start, $destination, $distance, $fare, $transportMode, $duration, $routeid);
            mysqli_stmt_execute($stmt);
            $response = ['success' => true, 'message' => 'Route updated successfully'];
        } else {
            $response = ['success' => false, 'message' => 'Error. Information not saved'];
        }
    }

    // Send JSON response
    header('Routedetails.php');
    echo json_encode($response);
}
?>
