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

include "../config.php";

// Output Form Entries from the Database
$sql = "SELECT id, name, email, message, created_at FROM feedback";
$stmt = $conn->prepare($sql);

// Execute the SQL statement
$stmt->execute();

// Get the result set
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Feedbacks</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f5f6fa;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100%;
            width: 280px;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            padding: 20px 0;
            z-index: 100;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-header {
            padding: 20px 30px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }

        .sidebar-header img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 10px;
            border: 3px solid #fff;
        }

        .sidebar-header h3 {
            color: #fff;
            font-size: 18px;
            font-weight: 600;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0 15px;
        }

        .sidebar-menu li {
            margin-bottom: 5px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: #ecf0f1;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-size: 15px;
        }

        .sidebar-menu a:hover {
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }

        .sidebar-menu a.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .sidebar-menu a i {
            font-size: 22px;
            margin-right: 15px;
            min-width: 25px;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 30px;
        }

        /* Top Bar */
        .top-bar {
            background: #fff;
            padding: 20px 30px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .top-bar h1 {
            font-size: 28px;
            color: #2c3e50;
            font-weight: 600;
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .admin-info i {
            font-size: 24px;
            color: #667eea;
        }

        .admin-info span {
            color: #2c3e50;
            font-weight: 500;
        }

        /* Table Card */
        .table-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .table-header {
            padding: 25px 30px;
            border-bottom: 1px solid #ecf0f1;
        }

        .table-header h2 {
            font-size: 22px;
            color: #2c3e50;
            font-weight: 600;
            margin: 0;
        }

        .table-container {
            padding: 30px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 15px;
            text-align: left;
            font-weight: 500;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #ecf0f1;
            color: #2c3e50;
            font-size: 14px;
        }

        tr:hover {
            background: #f8f9fa;
        }

        tr:last-child td {
            border-bottom: none;
        }

        /* Message Cell */
        .message-cell {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Action Buttons */
        .delete-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            border-radius: 8px;
            background: linear-gradient(135deg, #f44336 0%, #e91e63 100%);
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .delete-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(244, 67, 54, 0.4);
        }

        .delete-btn i {
            color: #fff;
            font-size: 18px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }

            .sidebar-header h3,
            .sidebar-menu span {
                display: none;
            }

            .main-content {
                margin-left: 70px;
            }

            .top-bar h1 {
                font-size: 20px;
            }

            .table-container {
                padding: 15px;
            }

            .message-cell {
                max-width: 150px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="../images/iskomyuter.png" alt="Iskomyuter Logo">
            <h3>ISKOMYUTER</h3>
        </div>
        <ul class="sidebar-menu">
            <li><a href="admin.php"><i class='bx bx-grid-alt'></i><span>Dashboard</span></a></li>
            <li><a href="admin-saved-routes.php"><i class='bx bx-map'></i><span>Saved Routes</span></a></li>
            <li><a href="admin-users.php"><i class='bx bx-user'></i><span>Users</span></a></li>
            <li><a href="admin-feedback.php" class="active"><i class='bx bx-message-dots'></i><span>Feedbacks</span></a></li>
            <li><a href="../controllers/logout.php"><i class='bx bx-log-out'></i><span>Logout</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <h1>User Feedbacks</h1>
            <div class="admin-info">
                <i class='bx bx-user-circle'></i>
                <span>Admin</span>
            </div>
        </div>

        <!-- Table Card -->
        <div class="table-card">
            <div class="table-header">
                <h2>All User Feedbacks</h2>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Date & Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>
                                    <td>' . htmlspecialchars($row["name"]) . '</td>
                                    <td>' . htmlspecialchars($row["email"]) . '</td>
                                    <td class="message-cell" title="' . htmlspecialchars($row["message"]) . '">' . htmlspecialchars($row["message"]) . '</td>
                                    <td>' . htmlspecialchars($row["created_at"]) . '</td>
                                    <td>
                                        <button class="delete-btn" onclick="if(confirm(\'Are you sure you want to delete this feedback?\')) location.href=\'../controllers/deletefeedback.php?id=' . $row["id"] . '\'" title="Delete">
                                            <i class=\'bx bx-trash\'></i>
                                        </button>
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
