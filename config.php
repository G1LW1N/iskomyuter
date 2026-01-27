<?php

$hostName = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "iskomyuter";

// Establish the database connection
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    // Uncomment the next line if you want to confirm a successful connection (for debugging purposes)
    // echo "Connected successfully";
}

?>