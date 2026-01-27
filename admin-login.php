<?php
// Start the session at the beginning of the script
session_start();

// Check if the user is already logged in, redirect to dashboard if true
if (isset($_SESSION["user"])) {
    header("Location: admin.php");
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
    $sql = "SELECT * FROM admin WHERE email = ? OR username = ?";
    
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
        // Verify the entered password against the hashed password in the database
        if ($password == $user["password"]) {
            // Set user variables in the session
            $_SESSION["user"] = $user["username"];
            $_SESSION["id"] = $user["id"];

            // Redirect to the admin dashboard
            header("Location: admin.php");
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
    <title>Login</title>
   
    <style>
        * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Poppins", sans-serif;
        }    

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: url('images/loginbg.jpg') no-repeat;
            background-size: cover;
            background-position: center;
        }

        .wrapper {
            width: 420px;
            background: transparent;
            border: 2px solid rgba(255, 255, 255, .2);
            backdrop-filter: blur(5px);
            box-shadow: 0 0 10px rgba(0, 0, 0, .2); 
            color: #fff;   
            border-radius: 10px;
            padding: 30px 40px;
        }

        .wrapper h1 {
            font-size: 36px;
            text-align: center;
        }

        .wrapper .input-box {
            position: relative;
            width: 100%;
            height: 50%;
            margin: 30px 0;
        }

        .input-box input {
            width: 100%;
            height: 100%;
            background: transparent;
            border: none;
            outline: none;
            border: 2px solid rgba(255, 255, 255, .2);
            border-radius: 40px;
            font-size: 16px;
            color: #fff;
            padding: 20px 45px 20px 20px;
        }

        .input-box input::placeholder {
            color: #fff;

        }

        .input-box i {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
        }

        .wrapper .btn  {
            width: 100%;
            height: 45px;
            background: #fff;
            border: none;
            outline: none;
            border-radius: 40px;
            box-shadow: 0 0 10px rgba(0, 0, 0, .1);
            cursor: pointer;
            font-size: 16px;
            color: #333;
            font-weight: 600;
        }

        .wrapper .register-link {
            font-size: 14.5px;
            text-align: center;
            margin: 20px 0 15px;
        }

        .register-link p a {
            color: #fff;
            text-decoration: none;
            font-weight: 600;
        }

        .register-link p a:hover {
            text-decoration: underline;
        }
    </style>

</head>
<body>

    <div class="wrapper">
        <form action="admin-login.php" method="post" autocomplete="off">
            <h1>Administration</h1>
            <div class="input-box">
                <input type="text" id="username_email" name="username_email" placeholder="Email" required>
                <i class='bx bxs-user'></i>
            </div>
            <div class="input-box">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <i class='bx bxs-lock-alt'></i>
            </div>

            <button type="submit" name="login" class="btn">Login</button>

            <div class="register-link">
                <p><a href="login.php">Login as User</a></p>
            </div>

        </form>
    </div>
</body>
</html>