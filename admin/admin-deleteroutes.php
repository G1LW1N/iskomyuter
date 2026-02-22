<?php
header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST');

header("Access-Control-Allow-Headers: X-Requested-With");

header("Access-Control-Allow-Private-Network: true");
session_start();
// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

$user = $_SESSION["user"];
$userid = $_SESSION["id"];

include "../config.php";

if (isset($_GET['routeid'])) {
    $routeid = $_GET['routeid'];

    $sql = "DELETE FROM routes WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $routeid);
        mysqli_stmt_execute($stmt);
        echo "<div class='alert alert-success'>Route deleted successfully.</div>";
    } else {
        // die("Something went wrong");
        echo "<div class='alert alert-danger'>Error. Something went wrong.</div>";
    }
    header("Location: admin-saved-routes.php");
}

