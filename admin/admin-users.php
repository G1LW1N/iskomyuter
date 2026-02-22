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

// Handle user update via POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {
    $id = $_POST["user_id"];
    $newName = $_POST["name"];
    $newUsername = $_POST["username"];
    $newEmail = $_POST["email"];
    $newPassword = $_POST["password"];

    if (!empty($newPassword)) {
        // Update with new password
        $update_sql = "UPDATE user SET name=?, username=?, email=?, password=? WHERE id=?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ssssi", $newName, $newUsername, $newEmail, $newPassword, $id);
    } else {
        // Update without changing password
        $update_sql = "UPDATE user SET name=?, username=?, email=? WHERE id=?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sssi", $newName, $newUsername, $newEmail, $id);
    }

    if ($update_stmt->execute()) {
        $successMessage = "User updated successfully!";
    } else {
        $errorMessage = "Error updating user.";
    }
    $update_stmt->close();
}

// Output Form Entries from the Database
$sql = "SELECT id, name, username, email, password FROM user";
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
    <title>Admin - User Management</title>
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
            background: #667eea;
            color: #fff;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #5568d3;
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

        /* Action Buttons */
        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            border-radius: 8px;
            margin: 0 3px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }

        .edit-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .edit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .delete-btn {
            background: linear-gradient(135deg, #f44336 0%, #e91e63 100%);
        }

        .delete-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(244, 67, 54, 0.4);
        }

        .action-btn img {
            width: 18px;
            height: 18px;
            filter: brightness(0) invert(1);
        }

        .action-btn i {
            font-size: 18px;
            color: white;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 0;
            border-radius: 15px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            animation: slideDown 0.3s;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            padding: 25px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
        }

        .close {
            color: white;
            font-size: 32px;
            font-weight: 300;
            cursor: pointer;
            transition: all 0.3s;
            line-height: 1;
        }

        .close:hover {
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #2c3e50;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e8e8e8;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group small {
            color: #666;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        .modal-footer {
            padding: 20px 30px;
            background: #f8f9fa;
            border-radius: 0 0 15px 15px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn-modal {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
        }

        .btn-cancel {
            background: #e8e8e8;
            color: #333;
        }

        .btn-cancel:hover {
            background: #d0d0d0;
        }

        .btn-save-modal {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-save-modal:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .alert {
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
            <li><a href="admin-users.php" class="active"><i class='bx bx-user'></i><span>Users</span></a></li>
            <li><a href="admin-feedback.php"><i class='bx bx-message-dots'></i><span>Feedbacks</span></a></li>
            <li><a href="../controllers/logout.php"><i class='bx bx-log-out'></i><span>Logout</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <h1>User Management</h1>
            <div class="admin-info">
                <i class='bx bx-user-circle'></i>
                <span>Admin</span>
            </div>
        </div>

        <?php if (isset($successMessage)): ?>
        <div class="alert alert-success">
            <i class='bx bx-check-circle'></i>
            <span><?php echo $successMessage; ?></span>
        </div>
        <?php endif; ?>

        <?php if (isset($errorMessage)): ?>
        <div class="alert alert-error">
            <i class='bx bx-error-circle'></i>
            <span><?php echo $errorMessage; ?></span>
        </div>
        <?php endif; ?>

        <!-- Table Card -->
        <div class="table-card">
            <div class="table-header">
                <h2>Iskomyuter Users</h2>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>
                                    <td>' . htmlspecialchars($row["id"]) . '</td>
                                    <td>' . htmlspecialchars($row["username"]) . '</td>
                                    <td>' . htmlspecialchars($row["name"]) . '</td>
                                    <td>' . htmlspecialchars($row["email"]) . '</td>
                                    <td>••••••••</td>
                                    <td>
                                        <button class="action-btn edit-btn" onclick="openEditModal(' . $row["id"] . ', \'' . htmlspecialchars($row["username"], ENT_QUOTES) . '\', \'' . htmlspecialchars($row["name"], ENT_QUOTES) . '\', \'' . htmlspecialchars($row["email"], ENT_QUOTES) . '\')" title="Edit">
                                            <i class=\'bx bx-edit\'></i>
                                        </button>
                                        <button class="action-btn delete-btn" onclick="if(confirm(\'Are you sure you want to delete this user?\')) location.href=\'../controllers/deleteuser.php?routeid=' . $row["id"] . '\'" title="Delete">
                                            <i class=\'bx bx-trash\'></i>
                                        </button>
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

    <!-- Edit User Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit User</h2>
                <span class="close" onclick="closeEditModal()">&times;</span>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="update_user" value="1">
                    <input type="hidden" name="user_id" id="edit_user_id">
                    
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" id="edit_username" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" id="edit_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" id="edit_email" required>
                    </div>
                    
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="password" id="edit_password" placeholder="Leave blank to keep current password">
                        <small>Leave blank if you don't want to change the password</small>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn-modal btn-cancel" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" class="btn-modal btn-save-modal">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Open edit modal
        function openEditModal(id, username, name, email) {
            document.getElementById('edit_user_id').value = id;
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_password').value = '';
            document.getElementById('editModal').style.display = 'block';
        }

        // Close edit modal
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                closeEditModal();
            }
        }

        // Auto-hide success/error messages after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.display = 'none';
            });
        }, 5000);
    </script>
</body>
</html>
