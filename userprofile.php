<?php


session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION["user"];
$userid = $_SESSION["id"];

include "config.php";

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="css/details.css">

    <title>User Dashboard</title>

    <style>
        
 /* Add custom styles for the sidebar */
 .sidebar {
    height: 100%;
    width: 250px;
    position: fixed;
    top: 0;
    left: 0;
    background-color: #f8f9fa; /* Change to your preferred color */
    padding-top: 20px;
}

.sidebar ul {
    list-style-type: none;
    padding: 0;
}

.sidebar ul li {
    padding: 10px 20px;
}

.sidebar ul li a {
    text-decoration: none;
    color: #333; /* Change to your preferred color */
    font-weight: bold;
}

.content {
    margin-left: 100px;
    padding: 20px;
}

.welcome{
    margin-left: 280px;
}

.container {
    max-width: 900px;
    margin: 0 auto;
}
    </style>

</head>
<body>
    <div class="sidebar">
        <ul>
        <li><a href="index.php">Dashboard</a></li>
            <li><a href="userprofile.php">Profile</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="blogpost.php" >Iskomyuter.ph</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    
    <?php

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
}

?>
<header>

    <h1 class="welcome" >Welcome, <?php echo ucfirst($_SESSION["user"]); ?>!</h1>
</header>
