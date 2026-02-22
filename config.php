<?php

// $hostName = "localhost";
// $dbUser = "root";
// $dbPassword = "";
// $dbName = "iskomyuter";

$servername = "sql301.infinityfree.com";
$username = "if0_41219056";
$password = "IskomyuterWeb01";
$dbName = "if0_41219056_iskomyuter";

// Establish the database connection
// $conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);
$conn = mysqli_connect($servername, $username, $password, $dbName);


// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    // Uncomment the next line if you want to confirm a successful connection (for debugging purposes)
    // echo "Connected successfully";
}

?>