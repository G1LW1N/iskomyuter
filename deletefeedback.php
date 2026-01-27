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

include "config.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM feedback WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $id);
        mysqli_stmt_execute($stmt);
        echo "<div class='alert alert-success'>User deleted successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error. Something went wrong.</div>";
    }

    // Redirect back to admin-users.php after deleting the user
    header("Location: admin-feedback.php");
    exit();
}
?>
