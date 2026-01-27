<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
header("Access-Control-Allow-Private-Network: true");
session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION["user"])) {
    header("Location: admin-login.php");
    exit();
}

$user = $_SESSION["user"];
$userid = $_SESSION["id"];

include "config.php";

// Count the number of users
$sqlUsers = "SELECT COUNT(*) AS userCount FROM user";
$stmtUsers = $conn->prepare($sqlUsers);
$stmtUsers->execute();
$resultUsers = $stmtUsers->get_result();
$userCount = $resultUsers->fetch_assoc()["userCount"];



// Count the number of saved routes
$sqlRoutes = "SELECT COUNT(*) AS routeCount FROM routes /* WHERE userid = ? */";
$stmtRoutes = $conn->prepare($sqlRoutes);
/* $stmtRoutes->bind_param("s", $userid); */
$stmtRoutes->execute();
$resultRoutes = $stmtRoutes->get_result();
$routeCount = $resultRoutes->fetch_assoc()["routeCount"];



// Count the number of feedbacks
$sqlFeedbacks = "SELECT COUNT(*) AS feedbackCount FROM feedback";
$stmtFeedbacks = $conn->prepare($sqlFeedbacks);
$stmtFeedbacks->execute();
$resultFeedbacks = $stmtFeedbacks->get_result();
$feedbackCount = $resultFeedbacks->fetch_assoc()["feedbackCount"];
?>

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

        .sidebar img {
            display: block;
            margin: 0 auto; /* Center the image horizontally */
            margin-bottom: 30px; /* Add some space below the image */
        }
    

        .section {
            margin-top: 50px;
        }

        .section h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .isko-details {
            width: 65%;
            margin: 0 auto;
            border-collapse: collapse;
            margin-top: 10px;
        }


        .isko-details th {
            padding: 10px;
            border: 1px solid black;
            background-color: #F4DFBA;
            color: black;
            text-align: left;
        }

        .isko-details td {
            padding: 10px;
            border: 1px solid black;
            background-color: #f8f9fa; 
            text-align: center;
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
            <li><a href="#">Fares</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    
    
       
    <div class="content">
        <div class="container">
            <?php
            if (!isset($_SESSION["user"])) {
                header("Location: login.php");
            }
            ?>

            <header>
                <h1 class="welcome" >Welcome to Admin</h1>
            </header>

            <div class="section">
                <h2>Iskomyuter Information</h2>
                <table class="isko-details">
                    <tr>
                        <th>Total Number of Users</th>
                        <td><?php echo $userCount; ?></td>
                    </tr>
                    <tr>
                        <th>Total Number of Saved Routes</th>
                        <td><?php echo $routeCount; ?></td>
                    </tr>
                    <tr>
                        <th>Total Number of Feedbacks</th>
                        <td><?php echo $feedbackCount; ?></td>
                    </tr>
                </table>
            </div>
            
        </div>
    </div>


        
    </div>
</body>
</html>
