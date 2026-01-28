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

// Update logic here (use $conn for database operations)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update-info"])) {
    // Update Username

    $newUsername = $_POST["new-username"];
    $conn->query("UPDATE user SET username = '$newUsername' WHERE id = $userId");

    // Update the username in the session data
    $_SESSION['user'] = $newUsername;

    // Update Email
    $newEmail = $_POST["new-email"];
    $conn->query("UPDATE user SET email = '$newEmail' WHERE id = $userId");

    // Update Password
    $newPassword = $_POST["new-password"];
    $conn->query("UPDATE user SET password = '$newPassword' WHERE id = $userId");
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>User Dashboard</title>
    <style>

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

        /* Style for the settings page */
        .settings-page {
            max-width: 600px;
            margin: 0 auto;
        }

        h2, h5 {
            font-size: 25px; /* Adjust the font size as needed */
            color: 191717;
            margin-bottom: 10px; /* Adjust the margin as needed */
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

        /* Style for the user details table */
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
            margin-bottom: 30px; /* Adjust the margin as needed */
        }

        /* Adjust font size for Update Details */
        .update-details-heading {
            font-size: inherit;
            color: #007bff;
            margin-bottom: 10px; /* Adjust the margin as needed */
        }

        .sidebar img {
            display: block;
            margin: 0 auto; /* Center the image horizontally */
            margin-bottom: 5px; /* Add some space below the image */
        }

    </style>
</head>
<body>
    <div class="sidebar">
        
    <a href="FirstPage.php">   <img src="images/iskomyuter.png" alt="Iskomyuter Logo" width="100" height="100"></a>
       
        <ul>
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="profile.php">Profile</a></li>
            <!-- <li><a href="settings.php">Settings</a></li> -->
            <li><a href="FirstPage.php">Iskomyuter.ph</a></li>
            <li><a href="controllers/logout.php">Logout</a></li>
        </ul>
    </div>
    
    <div class="content">
        <section class="settings-page">
            <div class="settings-content">
                <div class="section">
                    <h5>Account Information</h5>
                    <table class="user-details-table">
                        <tr>
                            <th>Name</th>
                            <td><?php echo $userInfo['name']; ?></td>
                        </tr>
                        <tr>
                            <th>Username</th>
                            <td><?php echo $userInfo['username']; ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?php echo $userInfo['email']; ?></td>
                        </tr>
                        <tr>
                            <th>Password</th>
                            <td><?php echo str_repeat('*', strlen($userInfo['password'])); ?></td>
                        </tr>
                    </table>
                </div>

                <div class="section">
                    <h2>Update Account</h2>
                    <form action="" method="post">
                        <label for="new-username">Username:</label>
                        <input type="text" id="new-username" name="new-username" placeholder="<?php echo $userInfo['username'];?>" required>

                        <label for="new-email">Email:</label>
                        <input type="email" id="new-email" name="new-email" placeholder="<?php echo $userInfo['email']; ?>" required>

                        <label for="new-password">Password:</label>
                        <div class="input-group">
                            <input type="password" id="new-password" name="new-password" placeholder="<?php echo str_repeat('*', strlen($userInfo['password'])); ?>" required>
                            <div class="input-group-append">
                                <input type="checkbox" id="show-password" onclick="togglePassword()"> 
                                <label for="show-password" id="show-password-label">Show password</label>
                            </div>
                        </div>

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
                passwordField.placeholder = "<?php echo $userInfo['password']; ?>";
            } else {
                passwordField.type = "password";
                passwordField.placeholder = "<?php echo str_repeat('*', strlen($userInfo['password'])); ?>";
            }
        }
    </script>
</body>
</html>
