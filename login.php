<?php
// Start the session at the beginning of the script
session_start();

// Check if the user is already logged in, redirect to dashboard if true
if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

// Include the configuration file
require_once "config.php";

// Check if the login form is submitted
if (isset($_POST["login"])) {
    // Get the username or email and password from the submitted form
    $usernameOrEmail = $_POST["username_email"];
    $password = $_POST["password"];
    
    // Create a new mysqli connection object
    $conn = new mysqli("localhost", "root", "", "iskomyuter");

    // Check for connection errors
    if ($conn->connect_error) {
        session_destroy();
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to retrieve user information based on email or username
    $sql = "SELECT * FROM user WHERE email = ? OR username = ?";
    // $sql = "SELECT * FROM user WHERE email = 'test@gmail.com' OR username = 'test'";
 
    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);
    
    // Bind parameters to the SQL statement
    $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);

    // Execute the SQL statement
    $stmt->execute();

    // Get the result set
    $result = $stmt->get_result();

    // Fetch the user data as an associative array
    $user = $result->fetch_assoc();

    // Check if a user is found
    if ($user) {
        // echo $user["username"];
        // echo $user["password"];
        // echo $password;
        // echo password_verify($password, $user["password"]);
        // Verify the entered password against the hashed password in the database
        // if (password_verify($password, $user["password"])) 
        if ($password == $user["password"]) 
        {
            // Set user variables in the session
            $_SESSION["user"] = $user["username"];
            $_SESSION["id"] = $user["id"];

            // Redirect to the dashboard
            header("Location: index.php");
            exit();
        } else {
            // Display an error message if the password doesn't match
            echo "<div class='alert alert-danger'>Password does not match</div>";
            $_SESSION["user"] = NULL;
            $_SESSION["id"] = NULL;
            session_destroy();
        }
    } else {
        // Display an error message if the username or email is not found
        echo "<div class='alert alert-danger'>Username or Email does not match</div>";
        $_SESSION["user"] = NULL;
        $_SESSION["id"] = NULL;
        session_destroy();
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Iskomyuter.ph</title>
    <link rel="stylesheet" href="css/blog.css">
    <link rel="stylesheet" href="css/login-style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <section class="login-header">
        <nav>
            <a href="FirstPage.php"> <img src="images/iskomyuter.png" alt="Iskomyuter Logo"></a>

            <div class="nav-links">
                <i class='bx bx-menu-alt-right'></i>
                <ul>
                    <li><a href="FirstPage.php">Home</a></li>
                    <li><a href="map.php">Map</a></li>
                    <li><a href="FirstPage.php#about">About Us</a></li>
                    <li><a href="FirstPage.php#blog">Blog Post</a></li>
                    <li><a href="FirstPage.php#contact">Contacts</a></li>
                    <li class="log active"><a href="login.php">Log in</a></li>
                </ul>
            </div>
        </nav>

        <div class="login-container">
            <div class="wrapper">
                <form action="login.php" method="post" autocomplete="off">
                    <h1>Welcome Back!</h1>
                    <p class="subtitle">Login to continue your journey</p>
                    
                    <div class="input-box">
                        <input type="text" id="username_email" name="username_email" placeholder="Email or Username" required>
                        <i class='bx bxs-user'></i>
                    </div>
                    <div class="input-box">
                        <input type="password" id="password" name="password" placeholder="Password" required>
                        <i class='bx bxs-lock-alt'></i>
                    </div>

                    <button type="submit" name="login" class="btn">Login</button>

                    <div class="register-link">
                        <p>Don't have an account? <a href="register.php">Register</a></p>
                        <p>Access the <a href="admin-login.php">ADMIN</a> portal</p>
                    </div>
                </form>
            </div>
        </div>
    </section>
</body>
</html>



