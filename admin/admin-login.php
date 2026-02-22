<?php
// Start the session at the beginning of the script
session_start();

// Check if the user is already logged in, redirect to dashboard if true
if (isset($_SESSION["user"])) {
    header("Location: admin.php");
    exit();
}

// Include the configuration file
require_once "../config.php";

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
        }
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en" style="scroll-behavior: smooth;">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Admin Login - Iskomyuter.ph</title>
   
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }

        .login-header {
            min-height: 100vh;
            width: 100%;
            background-image: linear-gradient(rgba(4,9,30,0.7),rgba(4,9,30,0.7)),
                              url('https://media.istockphoto.com/photos/cross-roads-manila-picture-id172192324?k=6&m=172192324&s=612x612&w=0&h=WJQycnDiLjNry-Gq3cu6OnG7LRn4pDcetK1_QTm3QU4=');
            background-position: center;
            background-size: cover;
            position: relative;
        }

        /* Auth Navigation */
        .auth-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 2% 6%;
        }

        .auth-nav .logo-link img {
            width: 80px;
            transition: transform 0.3s ease;
        }

        .auth-nav .logo-link:hover img {
            transform: scale(1.05);
        }

        .auth-nav .back-home {
            display: flex;
            align-items: center;
            gap: 8px;
            color: white;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .auth-nav .back-home:hover {
            background: #f44336;
            border-color: #f44336;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(244, 67, 54, 0.4);
        }

        .auth-nav .back-home i {
            font-size: 18px;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 100px);
            padding: 40px 20px;
        }

        .wrapper {
            width: 100%;
            max-width: 450px;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, .3);
            backdrop-filter: blur(20px);
            box-shadow: 0 0 30px rgba(0, 0, 0, .3); 
            color: #fff;   
            border-radius: 15px;
            padding: 40px 45px;
        }

        .wrapper h1 {
            font-size: 38px;
            text-align: center;
            margin-bottom: 10px;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .wrapper .subtitle {
            text-align: center;
            font-size: 14px;
            margin-bottom: 30px;
            color: rgba(255, 255, 255, 0.9);
            letter-spacing: 0.5px;
        }

        .wrapper .input-box {
            position: relative;
            width: 100%;
            height: 50px;
            margin: 25px 0;
        }

        .input-box input {
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            outline: none;
            border: 2px solid rgba(255, 255, 255, .3);
            border-radius: 40px;
            font-size: 16px;
            color: #fff;
            padding: 20px 45px 20px 20px;
            transition: all 0.3s ease;
        }

        .input-box input:focus {
            border-color: #f44336;
            background: rgba(255, 255, 255, 0.15);
        }

        .input-box input::placeholder {
            color: rgba(255, 255, 255, 0.8);
        }

        .input-box i {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: rgba(255, 255, 255, 0.8);
        }

        .wrapper .btn {
            width: 100%;
            height: 50px;
            background: #f44336;
            border: none;
            outline: none;
            border-radius: 40px;
            box-shadow: 0 5px 15px rgba(244, 67, 54, 0.4);
            cursor: pointer;
            font-size: 16px;
            color: #fff;
            font-weight: 700;
            margin-top: 10px;
            transition: all 0.3s ease;
            letter-spacing: 1px;
        }

        .wrapper .btn:hover {
            background: #d32f2f;
            box-shadow: 0 8px 20px rgba(244, 67, 54, 0.6);
            transform: translateY(-2px);
        }

        .wrapper .register-link {
            font-size: 14px;
            text-align: center;
            margin: 25px 0 0;
        }

        .register-link p {
            margin: 8px 0;
        }

        .register-link p a {
            color: #f44336;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .register-link p a:hover {
            color: #ff5252;
            text-decoration: underline;
        }

        /* Alert Message */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
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

        .alert-danger {
            background: rgba(255, 82, 82, 0.2);
            color: #fff;
            border-left: 4px solid #f44336;
            border: 2px solid rgba(255, 82, 82, 0.5);
        }

        .alert i {
            font-size: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .auth-nav .logo-link img {
                width: 60px;
            }

            .wrapper {
                padding: 30px 25px;
            }

            .wrapper h1 {
                font-size: 28px;
            }

            .login-container {
                min-height: calc(100vh - 80px);
                padding: 20px;
            }
        }
    </style>

</head>
<body>
    <section class="login-header">
        <div class="auth-nav">
            <a href="../FirstPage.php" class="logo-link">
                <img src="../images/iskomyuter.png" alt="Iskomyuter Logo">
            </a>
            <a href="../FirstPage.php" class="back-home">
                <i class='bx bx-home-alt'></i>
                <span>Back to Home</span>
            </a>
        </div>

        <div class="login-container">
            <div class="wrapper">
                <form action="admin-login.php" method="post" autocomplete="off">
                    <h1>Administration</h1>
                    <p class="subtitle">Admin Portal Access</p>
                    
                    <?php
                    if (isset($_POST["login"]) && isset($user) === false) {
                        echo "<div class='alert alert-danger'>
                                <i class='bx bx-error-circle'></i>
                                <span>Username or Email does not match</span>
                              </div>";
                    } elseif (isset($_POST["login"]) && isset($user) && $password != $user["password"]) {
                        echo "<div class='alert alert-danger'>
                                <i class='bx bx-error-circle'></i>
                                <span>Password does not match</span>
                              </div>";
                    }
                    ?>

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
                        <p>Access the <a href="../login.php">User Portal</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>
</body>
</html>