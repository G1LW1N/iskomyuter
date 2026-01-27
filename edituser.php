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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update user information in the database
    $id = $_POST["id"];
    $newName = $_POST["new-name"];
    $newUsername = $_POST["new-username"];
    $newEmail = $_POST["new-email"];
    $newPassword = $_POST["new-password"];

    $update_sql = "UPDATE user SET name=?, username=?, email=?, password=? WHERE id=?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssi", $newName, $newUsername, $newEmail, $newPassword, $id);

    if ($update_stmt->execute()) {
        // Redirect to the user list page after successful update
        header("Location: admin-users.php");
        exit();
    } else {
        // Handle error if update fails
        $error_message = "Error updating user information.";
    }
}

// Fetch user details for editing
if (isset($_GET['userid'])) {
    $edit_id = $_GET['userid'];
    $select_sql = "SELECT id, name, username, email, password FROM user WHERE id=?";
    $select_stmt = $conn->prepare($select_sql);
    $select_stmt->bind_param("i", $edit_id);
    $select_stmt->execute();
    $edit_result = $select_stmt->get_result();
    $edit_user = $edit_result->fetch_assoc();
} else {
    // If no user is selected for editing, redirect to user list page
    header("Location: admin-users.php");
    exit();
}
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

    <title>Edit User</title>

    <style>
        @media (max-width: 780px) {
            /* Add your styles for smaller screens if needed */
        }

        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #E2C799 /* #f8f9fa */;
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
            color: black;
            font-weight: bold;
        }

        .content {
            margin-left: 280px;
            padding: 0px;
        }

        .settings-page {
            max-width: 600px;
            margin: 0 auto;
        }

        h2, h5 {
            font-size: 25px;
            color: #191717;
            margin-bottom: 10px;
        }

        .settings-content {
            margin-top: 20px;
        }

        .settings-content form {
            margin-bottom: 20px;
        }

        .settings-content label {
            display: block;
            margin-bottom: 5px;
        }

        .settings-content input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        .settings-btn {
            margin-top: 20px;
            background-color: maroon;
            color: white;
            border: 1px solid darkred;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .settings-btn:hover {
            background-color: #BF3131;
            border-color: #BF3131;
        }

        .input-group-append {
            display: flex;
            align-items: center;
            margin-left: 0px;
        }

        #show-password-label {
            margin-left: 5px;
            white-space: nowrap;
            font-style: italic;
        }

        .user-details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .user-details-table th, .user-details-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .user-details-table th {
            background-color: white;
            color: black;
        }

        .section {
            margin-bottom: 30px;
        }

        .update-details-heading {
            font-size: inherit;
            color: #007bff;
            margin-bottom: 10px;
        }

        .sidebar img {
            display: block;
            margin: 0 auto;
            margin-bottom: 5px;
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
        <section class="settings-page">
            <div class="settings-content">
                <div class="section">
                    <h5>Account Information</h5>
                    <table class="user-details-table">
                        <tr>
                            <th>Name</th>
                            <td><?php echo $edit_user['name']; ?></td>
                        </tr>
                        <tr>
                            <th>Username</th>
                            <td><?php echo $edit_user['username']; ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?php echo $edit_user['email']; ?></td>
                        </tr>
                        <tr>
                            <th>Password</th>
                            <td><?php echo str_repeat('*', strlen($edit_user['password'])); ?></td>
                        </tr>
                    </table>
                </div>

                <div class="section">
                    <h2>Update Account</h2>
                    <form action="edituser.php?userid=<?php echo $edit_user['id']; ?>" method="post">
                        <input type="hidden" name="id" value="<?php echo $edit_user['id']; ?>">

                        <label for="new-name">Name:</label>
                        <input type="text" id="new-name" name="new-name" value="<?php echo $edit_user['name']; ?>" required>

                        <label for="new-username">Username:</label>
                        <input type="text" id="new-username" name="new-username" value="<?php echo $edit_user['username']; ?>" required>

                        <label for="new-email">Email:</label>
                        <input type="email" id="new-email" name="new-email" value="<?php echo $edit_user['email']; ?>" required>

                        <label for="new-password">Password:</label>
                        <input type="password" id="new-password" name="new-password" value="" required>

                        <button type="submit" name="update-info" class="btn btn-primary settings-btn">Update Information</button>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <script>
        function togglePassword() {
            var passwordField = document.getElementById("new-password");
            var showCheckbox = document.getElementById("show-password");

            if (showCheckbox.checked) {
                passwordField.type = "text";
                passwordField.placeholder = "<?php echo $edit_user['password']; ?>";
            } else {
                passwordField.type = "password";
                passwordField.placeholder = "<?php echo str_repeat('*', strlen($edit_user['password'])); ?>";
            }
        }
    </script>
</body>
</html>
