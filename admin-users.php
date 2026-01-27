<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
header("Access-Control-Allow-Private-Network: true");
session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION["user"];
$userid = $_SESSION["id"];

include "config.php";

// Output Form Entries from the Database
$sql = "SELECT id, name, username, email, password FROM user";
$stmt = $conn->prepare($sql);

// Bind parameters to the SQL statement
/* $stmt->bind_param("s", $userid); */

// Execute the SQL statement
$stmt->execute();

// Get the result set
$result = $stmt->get_result();


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="css/details.css">

    <title>User Dashboard</title>

    <style>
        @media (max-width: 780px) {
       
    }

        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #630000; 
            padding-top: 20px;
            text-align: center;
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
            color: #EEEBDD;
            font-weight: bold;
        }

        .content {
            margin-left: 100px;
            padding: 0px;
        }

        .welcome{
            margin-left: 280px;
            color: black;
            margin-bottom: 1px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            color: white;
        }

        header {
            background-color: #fff; /* Change to your preferred color */
            padding: 20px;
            text-align: left;
        }

        table thead {
            background-color: #E08E6D; 
            color: black; 
        }

        .add-new-container {
            text-align: right;
            margin-top: 20px;
        }
        

        .add-new {
        background-color: maroon; 
        color: white; 
        border: none;
        }

        .add-new-container .add-new:hover {
        background-color: #BF3131;
        border-color: #BF3131;
        }

        .table-title h2 {
            color: #333; 
        }

        .table-wrapper {
        background-color: white; 
        padding: 20px;
        border-radius: 8px;
        margin-top: 20px; 
        }
        /* .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #f8f9fa;
            padding-top: 20px;
            text-align: center;
        } */

        .sidebar img {
            display: block;
            margin: 0 auto; /* Center the image horizontally */
            margin-bottom: 30px; /* Add some space below the image */
        }
    

    </style>

</head>
<body>
<i class='bx bx-menu'></i>
    <div class="sidebar">
        
      <a href="#">   <img src="images/iskomyuter.png" alt="Iskomyuter Logo" width="100" height="100"></a>
      
        <ul>
        <li><a href="admin.php">Dashboard</a></li>
            <li><a href="admin-saved-routes.php">Saved Routes</a></li>
            <li><a href="admin-users.php">Users</a></li>
            <li><a href="admin-feedback.php">Feedbacks</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    
    <?php

    if (!isset($_SESSION["user"])) {
        header("Location: login.php");
    }

    ?>

       
    <div class="content">
        <div class="container">
            <div class="table-wrapper">
                <div class="table-title">
                    <div class="row">
                        <div class="col-sm-12">
                            <h2 color="#007bff"><b>Iskomyuter Users</b></h2>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered">
                    <thead color="#007bff">
                        <tr>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>
                                    <td>' . $row["id"] . '</td>
                                    <td>' . $row["username"] . '</td>
                                    <td>' . $row["name"] . '</td>
                                    <td>' . $row["email"] . '</td>
                                    <td>' . $row["password"] . '</td>
                                    <td>
                                        <a class="edit" title="Edit" data-toggle="tooltip" onclick=location.href="edituser.php?routeid=' . $row["id"] . '" style="cursor: pointer;">
                                        <img src="images/edit-icon.png" alt="Edit" width="20" height="20">   
                                        </a>
                                        
                                        <a class="delete" title="Delete" data-toggle="tooltip" onclick=location.href="deleteuser.php?routeid=' . $row["id"] . '" style="cursor: pointer;">
                                        <img src="images/delete-icon.png" alt="Delete" width="20" height="20">
                                        </a>
                                    </td>
                                </tr>';
                        }
                        $stmt->close();
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    
</body>
</html>
