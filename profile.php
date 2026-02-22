<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit(); // Add exit to stop further execution
}

// Fetch the userId from the session
$userId = $_SESSION['id'];  

// Database connection
$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "iskomyuter";

$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch current user information
$userInfo = $conn->query("SELECT * FROM user WHERE id = $userId")->fetch_assoc();

// Initialize success and error messages
$successMessage = "";
$errorMessage = "";

// Update logic here (use $conn for database operations)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update-info"])) {
    $newUsername = trim($_POST["new-username"]);
    $newEmail = trim($_POST["new-email"]);
    $newPassword = trim($_POST["new-password"]);
    
    // Validation
    if (empty($newUsername) || empty($newEmail) || empty($newPassword)) {
        $errorMessage = "All fields are required.";
    } elseif (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Please enter a valid email address.";
    } elseif (strlen($newPassword) < 6) {
        $errorMessage = "Password must be at least 6 characters long.";
    } else {
        try {
            // Use prepared statements for security
            $updateStmt = $conn->prepare("UPDATE user SET username = ?, email = ?, password = ? WHERE id = ?");
            $updateStmt->bind_param("sssi", $newUsername, $newEmail, $newPassword, $userId);
            
            if ($updateStmt->execute()) {
                $_SESSION['user'] = $newUsername;
                $successMessage = "Profile updated successfully!";
                // Refresh user info
                $userInfo = $conn->query("SELECT * FROM user WHERE id = $userId")->fetch_assoc();
            } else {
                $errorMessage = "Failed to update profile. Please try again.";
            }
            $updateStmt->close();
        } catch (Exception $e) {
            $errorMessage = "An error occurred. Please try again later.";
        }
    }
}

// Close the database connection
$conn->close();
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
    <title>Profile - Iskomyuter.ph</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f6f9;
            min-height: 100vh;
            color: #2c3e50;
        }

        /* Navigation Bar Styles */
        .nav {
            display: flex;
            padding: 1.5% 6%;
            justify-content: space-between;
            align-items: center;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav img {
            width: 70px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .nav img:hover {
            transform: scale(1.05);
        }

        .nav-links {
            flex: 1;
            text-align: right;
        }

        .nav-links ul {
            display: flex;
            justify-content: flex-end;
            gap: 25px;
            list-style: none;
            align-items: center;
        }

        .nav-links ul li a {
            color: #34495e;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.3s;
            padding: 8px 16px;
            border-radius: 6px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .nav-links ul li a:hover,
        .nav-links ul li a.active {
            background: #2c3e50;
            color: white;
        }

        /* User Dropdown */
        .user-dropdown {
            position: relative;
        }

        .user-menu-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 10px 18px;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .user-menu-btn:hover {
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            transform: translateY(-2px);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #667eea;
            font-weight: 700;
            font-size: 14px;
        }

        .user-name {
            color: white;
            font-weight: 600;
            font-size: 13px;
        }

        .dropdown-icon {
            color: white;
            font-size: 16px;
        }

        /* Main Content */
        .content {
            padding: 50px 6%;
            max-width: 1300px;
            margin: 0 auto;
        }

        .page-header {
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e8ecef;
        }

        .page-header h1 {
            font-size: 32px;
            color: #2c3e50;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .page-header p {
            color: #7f8c8d;
            font-size: 15px;
            font-weight: 400;
        }

        /* Alert Messages */
        .alert {
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 500;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .alert i {
            font-size: 20px;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 25px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 35px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            border: 1px solid #e8ecef;
        }

        .card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border-color: #d0d7de;
        }

        .card-header {
            font-size: 20px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 30px;
            padding-bottom: 18px;
            border-bottom: 2px solid #e8ecef;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-header i {
            font-size: 24px;
            color: #667eea;
        }

        .info-row {
            display: flex;
            padding: 18px 0;
            border-bottom: 1px solid #f7f9fb;
            transition: all 0.2s;
            align-items: center;
        }

        .info-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .info-row:hover {
            background: #f8f9fa;
            padding-left: 15px;
            margin-left: -15px;
            margin-right: -15px;
            padding-right: 15px;
            border-radius: 6px;
        }

        .info-label {
            font-weight: 600;
            color: #5a6c7d;
            width: 130px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-label i {
            color: #667eea;
            font-size: 18px;
        }

        .info-value {
            color: #2c3e50;
            flex: 1;
            font-weight: 500;
            font-size: 15px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
            color: #5a6c7d;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-group label i {
            color: #667eea;
            font-size: 16px;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e1e8ed;
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            background: #fafbfc;
            color: #2c3e50;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.08);
        }

        .form-group input::placeholder {
            color: #95a5a6;
        }

        .password-toggle {
            display: flex;
            align-items: center;
            margin-top: 12px;
            gap: 8px;
        }

        .password-toggle input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
            accent-color: #667eea;
        }

        .password-toggle label {
            color: #7f8c8d;
            font-size: 13px;
            cursor: pointer;
            user-select: none;
            margin: 0;
            font-weight: 500;
            text-transform: none;
            letter-spacing: normal;
        }

        .password-requirements {
            margin-top: 8px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 3px solid #667eea;
        }

        .password-requirements p {
            font-size: 11px;
            color: #5a6c7d;
            margin-bottom: 6px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .password-requirements ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .password-requirements li {
            font-size: 12px;
            color: #7f8c8d;
            padding: 3px 0;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .password-requirements li i {
            font-size: 14px;
            color: #95a5a6;
        }

        .btn-update {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 16px 40px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-update:active {
            transform: translateY(0);
        }

        .btn-update i {
            font-size: 18px;
        }

        /* Security Badge */
        .security-badge {
            background: #e8f5e9;
            border: 1px solid #c8e6c9;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            display: flex;
            align-items: start;
            gap: 12px;
        }

        .security-badge i {
            color: #4caf50;
            font-size: 24px;
            margin-top: 2px;
        }

        .security-badge-content h4 {
            color: #2e7d32;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .security-badge-content p {
            color: #558b2f;
            font-size: 12px;
            line-height: 1.5;
        }

        @media (max-width: 968px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .nav-links ul {
                flex-direction: column;
                gap: 10px;
            }

            .content {
                padding: 30px 4%;
            }

            .page-header h1 {
                font-size: 26px;
            }

            .card {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="nav">
        <a href="index.php"><img src="images/iskomyuter.png" alt="Iskomyuter Logo"></a>
        <div class="nav-links">
            <ul>
                <li><a href="index.php">HOME</a></li>
                <li><a href="map.php">MAP</a></li>
                <li><a href="index.php#blog">BLOG</a></li>
                <li><a href="index.php#about">ABOUT US</a></li>
                <li><a href="index.php#footer">CONTACT</a></li>
                <li class="user-dropdown">
                    <div class="user-menu-btn">
                        <div class="user-avatar"><?php echo strtoupper(substr($_SESSION["user"], 0, 1)); ?></div>
                        <span class="user-name"><?php echo ucfirst($_SESSION["user"]); ?></span>
                        <i class='bx bx-chevron-down dropdown-icon'></i>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    
    <!-- Main Content -->
    <div class="content">
        <div class="page-header">
            <h1>My Profile</h1>
            <p>Manage your account information and settings</p>
        </div>

        <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success">
            <i class='bx bx-check-circle'></i>
            <span><?php echo $successMessage; ?></span>
        </div>
        <?php endif; ?>

        <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-error">
            <i class='bx bx-error-circle'></i>
            <span><?php echo $errorMessage; ?></span>
        </div>
        <?php endif; ?>

        <div class="profile-grid">
            <!-- Account Information Card -->
            <div class="card">
                <div class="card-header">
                    <i class='bx bx-user-circle'></i>
                    Account Information
                </div>
                <div class="info-row">
                    <div class="info-label">
                        <i class='bx bx-id-card'></i>
                        Name
                    </div>
                    <div class="info-value"><?php echo htmlspecialchars($userInfo['name']); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">
                        <i class='bx bx-user'></i>
                        Username
                    </div>
                    <div class="info-value"><?php echo htmlspecialchars($userInfo['username']); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">
                        <i class='bx bx-envelope'></i>
                        Email
                    </div>
                    <div class="info-value"><?php echo htmlspecialchars($userInfo['email']); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">
                        <i class='bx bx-lock-alt'></i>
                        Password
                    </div>
                    <div class="info-value"><?php echo str_repeat('●', 8); ?></div>
                </div>

                <div class="security-badge">
                    <i class='bx bx-shield-quarter'></i>
                    <div class="security-badge-content">
                        <h4>Account Security</h4>
                        <p>Your account is protected with secure encryption. Always keep your password private and secure.</p>
                    </div>
                </div>
            </div>

            <!-- Update Account Card -->
            <div class="card">
                <div class="card-header">
                    <i class='bx bx-edit-alt'></i>
                    Update Account
                </div>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="new-username">
                            <i class='bx bx-user'></i>
                            Username
                        </label>
                        <input type="text" id="new-username" name="new-username" placeholder="Enter new username" value="<?php echo htmlspecialchars($userInfo['username']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="new-email">
                            <i class='bx bx-envelope'></i>
                            Email Address
                        </label>
                        <input type="email" id="new-email" name="new-email" placeholder="Enter new email address" value="<?php echo htmlspecialchars($userInfo['email']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="new-password">
                            <i class='bx bx-lock-alt'></i>
                            New Password
                        </label>
                        <input type="password" id="new-password" name="new-password" placeholder="Enter new password" required>
                        <div class="password-toggle">
                            <input type="checkbox" id="show-password" onclick="togglePassword()">
                            <label for="show-password">Show password</label>
                        </div>
                        <div class="password-requirements">
                            <p>Password Requirements:</p>
                            <ul>
                                <li><i class='bx bx-check'></i> At least 6 characters long</li>
                                <li><i class='bx bx-check'></i> Mix of letters and numbers recommended</li>
                                <li><i class='bx bx-check'></i> Avoid using personal information</li>
                            </ul>
                        </div>
                    </div>

                    <button type="submit" name="update-info" class="btn-update">
                        <i class='bx bx-save'></i>
                        Update Information
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            var passwordField = document.getElementById("new-password");
            var showCheckbox = document.getElementById("show-password");
            
            if (showCheckbox.checked) {
                passwordField.type = "text";
            } else {
                passwordField.type = "password";
            }
        }
    </script>
</body>
</html>
