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

include "../config.php";

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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <title>Admin Dashboard - Iskomyuter.ph</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f6fa;
            color: #2c3e50;
        }

        /* Sidebar Styles */
        .sidebar {
            height: 100vh;
            width: 280px;
            position: fixed;
            top: 0;
            left: 0;
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            padding: 0;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 30px 20px;
            text-align: center;
            background: rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: white;
            padding: 10px;
            margin-bottom: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .sidebar-header h3 {
            color: white;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .sidebar-header p {
            color: #bdc3c7;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 20px 0;
        }

        .sidebar ul li {
            margin: 5px 15px;
        }

        .sidebar ul li a {
            display: flex;
            align-items: center;
            gap: 15px;
            text-decoration: none;
            color: #bdc3c7;
            font-weight: 500;
            font-size: 15px;
            padding: 14px 20px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .sidebar ul li a:hover,
        .sidebar ul li a.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }

        .sidebar ul li a i {
            font-size: 20px;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 30px;
            min-height: 100vh;
        }

        /* Top Bar */
        .top-bar {
            background: white;
            padding: 20px 30px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .top-bar h1 {
            font-size: 28px;
            font-weight: 700;
            color: #2c3e50;
        }

        .top-bar .welcome-text {
            font-size: 14px;
            color: #7f8c8d;
            margin-top: 5px;
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .admin-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 18px;
        }

        .admin-details h4 {
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
        }

        .admin-details p {
            font-size: 12px;
            color: #7f8c8d;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .stat-card.users {
            border-left-color: #3498db;
        }

        .stat-card.routes {
            border-left-color: #2ecc71;
        }

        .stat-card.feedbacks {
            border-left-color: #f39c12;
        }

        .stat-icon {
            width: 70px;
            height: 70px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: white;
        }

        .stat-card.users .stat-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .stat-card.routes .stat-icon {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .stat-card.feedbacks .stat-icon {
            background: linear-gradient(135deg, #fad0c4 0%, #ffd1ff 100%);
            color: #e74c3c;
        }

        .stat-details h3 {
            font-size: 32px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .stat-details p {
            font-size: 14px;
            color: #7f8c8d;
            font-weight: 500;
        }

        /* Quick Actions */
        .quick-actions {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .quick-actions h2 {
            font-size: 20px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quick-actions h2 i {
            color: #3498db;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            border: 2px solid #ecf0f1;
            color: #2c3e50;
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .action-btn i {
            font-size: 20px;
        }

        .action-btn.users {
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            border-color: #667eea;
        }

        .action-btn.users:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .action-btn.routes {
            background: linear-gradient(135deg, #f093fb15 0%, #f5576c15 100%);
            border-color: #f5576c;
        }

        .action-btn.routes:hover {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .action-btn.feedbacks {
            background: linear-gradient(135deg, #fad0c415 0%, #ffd1ff15 100%);
            border-color: #e74c3c;
        }

        .action-btn.feedbacks:hover {
            background: linear-gradient(135deg, #fad0c4 0%, #ffd1ff 100%);
            color: #2c3e50;
        }

        /* Overview Section */
        .overview-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .overview-section h2 {
            font-size: 20px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .overview-section h2 i {
            color: #3498db;
        }

        .overview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .overview-item {
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #3498db;
        }

        .overview-item h4 {
            font-size: 14px;
            color: #7f8c8d;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .overview-item p {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }

            .sidebar-header h3,
            .sidebar-header p,
            .sidebar ul li a span {
                display: none;
            }

            .sidebar ul li a {
                justify-content: center;
            }

            .main-content {
                margin-left: 70px;
                padding: 20px;
            }

            .top-bar {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="../images/iskomyuter.png" alt="Iskomyuter Logo">
            <h3>Admin Portal</h3>
            <p>Iskomyuter.ph</p>
        </div>
        <ul>
            <li>
                <a href="admin.php" class="active">
                    <i class='bx bx-home-alt'></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="admin-saved-routes.php">
                    <i class='bx bx-map-alt'></i>
                    <span>Saved Routes</span>
                </a>
            </li>
            <li>
                <a href="admin-users.php">
                    <i class='bx bx-user'></i>
                    <span>Users</span>
                </a>
            </li>
            <li>
                <a href="admin-feedback.php">
                    <i class='bx bx-message-dots'></i>
                    <span>Feedbacks</span>
                </a>
            </li>
            <li>
                <a href="../controllers/logout.php">
                    <i class='bx bx-log-out'></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div>
                <h1>Dashboard Overview</h1>
                <p class="welcome-text">Welcome back, <?php echo htmlspecialchars($user); ?>!</p>
            </div>
            <div class="admin-info">
                <div class="admin-avatar">
                    <?php echo strtoupper(substr($user, 0, 1)); ?>
                </div>
                <div class="admin-details">
                    <h4><?php echo htmlspecialchars($user); ?></h4>
                    <p>Administrator</p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card users">
                <div class="stat-icon">
                    <i class='bx bx-group'></i>
                </div>
                <div class="stat-details">
                    <h3><?php echo $userCount; ?></h3>
                    <p>Total Users</p>
                </div>
            </div>

            <div class="stat-card routes">
                <div class="stat-icon">
                    <i class='bx bx-map'></i>
                </div>
                <div class="stat-details">
                    <h3><?php echo $routeCount; ?></h3>
                    <p>Saved Routes</p>
                </div>
            </div>

            <div class="stat-card feedbacks">
                <div class="stat-icon">
                    <i class='bx bx-comment-detail'></i>
                </div>
                <div class="stat-details">
                    <h3><?php echo $feedbackCount; ?></h3>
                    <p>Total Feedbacks</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h2>
                <i class='bx bx-lightning-charge'></i>
                Quick Actions
            </h2>
            <div class="action-buttons">
                <a href="admin-users.php" class="action-btn users">
                    <i class='bx bx-user-plus'></i>
                    <span>Manage Users</span>
                </a>
                <a href="admin-saved-routes.php" class="action-btn routes">
                    <i class='bx bx-map-pin'></i>
                    <span>View Routes</span>
                </a>
                <a href="admin-feedback.php" class="action-btn feedbacks">
                    <i class='bx bx-chat'></i>
                    <span>Check Feedback</span>
                </a>
            </div>
        </div>

        <!-- Overview Section -->
        <div class="overview-section">
            <h2>
                <i class='bx bx-line-chart'></i>
                System Overview
            </h2>
            <div class="overview-grid">
                <div class="overview-item">
                    <h4>Total Users</h4>
                    <p><?php echo $userCount; ?></p>
                </div>
                <div class="overview-item" style="border-left-color: #2ecc71;">
                    <h4>Saved Routes</h4>
                    <p><?php echo $routeCount; ?></p>
                </div>
                <div class="overview-item" style="border-left-color: #f39c12;">
                    <h4>Feedbacks</h4>
                    <p><?php echo $feedbackCount; ?></p>
                </div>
                <div class="overview-item" style="border-left-color: #e74c3c;">
                    <h4>Avg Routes/User</h4>
                    <p><?php echo $userCount > 0 ? round($routeCount / $userCount, 1) : 0; ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
