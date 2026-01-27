<?php
session_start();
if (isset($_SESSION["user"])) {
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en" style="scroll-behavior: smooth;">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Register - Iskomyuter.ph">
    <title>Register - Iskomyuter.ph</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/blog.css">
    <link rel="stylesheet" href="css/Registration-style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <section class="register-header">
        <div class="auth-nav">
            <a href="FirstPage.php" class="logo-link">
                <img src="images/iskomyuter.png" alt="Iskomyuter Logo">
            </a>
            <a href="FirstPage.php" class="back-home">
                <i class='bx bx-home-alt'></i>
                <span>Back to Home</span>
            </a>
        </div>

        <div class="register-container">
            <div class="wrapper">
                <?php
                require_once "config.php";

                if (isset($_POST["submit"])) {
                    $name = $_POST["name"];
                    $username = $_POST["username"];
                    $email = $_POST["email"];
                    $password = $_POST["password"];
                    $confirmpassword = $_POST["confirmpassword"];

                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                    $errors = array();

                    if (empty($name) || empty($username) || empty($email) || empty($password) || empty($confirmpassword)) {
                        array_push($errors, "All fields are required");
                    }

                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        array_push($errors, "Email is not valid");
                    }

                    if (strlen($password) < 0) {
                        array_push($errors, "Password must be at least 8 characters long");
                    }

                    if ($password !== $confirmpassword) {
                        array_push($errors, "Password does not match");
                    }

                    $sql = "SELECT * FROM user WHERE email = ?";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, "s", $email);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);
                    $rowCount = mysqli_stmt_num_rows($stmt);

                    if ($rowCount > 0) {
                        array_push($errors, "Email already exists!");
                    }

                    if (count($errors) > 0) {
                        foreach ($errors as $error) {
                            echo "<div class='alert alert-danger'>$error</div>";
                        }
                    } else {
                        $sql = "INSERT INTO user (name, username, email, password) VALUES (?, ?, ?, ?)";
                        $stmt = mysqli_prepare($conn, $sql);

                        if ($stmt) {
                            mysqli_stmt_bind_param($stmt, "ssss", $name, $username, $email, $password);
                            mysqli_stmt_execute($stmt);
                            echo "<div class='alert alert-success'>You are registered successfully.</div>";
                        } else {
                            die("Something went wrong");
                        }
                    }
                }
                ?>

                <form action="register.php" method="post" autocomplete="off">
                    <h1>Join Iskomyuter!</h1>
                    <p class="subtitle">Create your account to get started</p>
                    
                    <div class="input-box">
                        <input type="text" id="name" name="name" placeholder="Full Name" required>
                        <i class='bx bxs-user'></i>
                    </div>
                    <div class="input-box">
                        <input type="text" id="username" name="username" placeholder="Username" required>
                        <i class='bx bxs-user-circle'></i>
                    </div>
                    <div class="input-box">
                        <input type="email" id="email" name="email" placeholder="Email" required>
                        <i class='bx bx-envelope'></i>
                    </div>
                    <div class="input-box">
                        <input type="password" id="password" name="password" placeholder="Password" required>
                        <i class='bx bxs-lock-alt'></i>
                    </div>
                    <div class="input-box">
                        <input type="password" id="confirmpassword" name="confirmpassword" placeholder="Confirm Password" required>
                        <i class='bx bxs-lock-alt'></i>
                    </div>
                    <button type="submit" name="submit" class="btn">Register</button>
                    <div class="register-link">
                        <p>Already have an account? <a href="login.php">Login</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Scroll to Top Button -->
    <button id="scrollToTop" onclick="scrollToTop()">
        <i class='bx bx-chevron-up'></i>
    </button>

    <script>
        // Show/hide scroll to top button
        window.addEventListener('scroll', function() {
            const scrollBtn = document.getElementById('scrollToTop');
            if (window.pageYOffset > 300) {
                scrollBtn.classList.add('show');
            } else {
                scrollBtn.classList.remove('show');
            }
        });

        // Smooth scroll to top
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    </script>
</body>
</html>
