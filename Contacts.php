<?php
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'config.php';

    // Validate user input
    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $message = validate($_POST['message']);

    if (empty($name) || empty($email) || empty($message)) {
        $_SESSION['error_message'] = "All fields are required!";
    } else {
        // Insert feedback into database
        $sql = "INSERT INTO feedback (name, email, message) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $message);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION['success_message'] = "Your message was sent successfully!";
        } else {
            $_SESSION['error_message'] = "Your message could not be sent!";
        }

        $stmt->close();
    }

    $conn->close();

    // Redirect back to the contact page
    header("Location: Contacts.php");
    exit();
}

// Function to sanitize user input
function validate($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en" style="scroll-behavior: smooth;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Contact Us - Iskomyuter.ph">
    <title>Contact Us - Iskomyuter.ph</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/pages-style.css">
</head>
<body>
    <section class="page-header">
        <nav class="nav">
            <a href="FirstPage.php"><img src="images/iskomyuter.png" alt="Iskomyuter Logo"></a>
            <div class="nav-links">
                <ul>
                    <li><a href="FirstPage.php">HOME</a></li>
                    <li><a href="map.php">MAP</a></li>
                    <li><a href="FirstPage.php#blog">BLOG</a></li>
                    <li><a href="FirstPage.php#about">ABOUT US</a></li>
                    <li><a href="FirstPage.php#footer">CONTACT</a></li>
                    <li class="log"><a href="login.php">Log in</a></li>
                </ul>
            </div>
        </nav>

        <div class="page-title">
            <h1>Contact Us</h1>
            <p>Get in touch with the Iskomyuter team</p>
        </div>
    </section>

    <div class="container">
        <?php
        // Display error message if set
        if (isset($_SESSION['error_message'])) {
            echo '<div class="alert alert-error">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']);
        }

        // Display success message if set
        if (isset($_SESSION['success_message'])) {
            echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
            unset($_SESSION['success_message']);
        }
        ?>

        <div class="contact-info">
            <div class="info-card">
                <i class='bx bx-map'></i>
                <h3>Address</h3>
                <p>Metro Manila<br>Philippines</p>
            </div>
            <div class="info-card">
                <i class='bx bx-envelope'></i>
                <h3>Email</h3>
                <p>info@iskomyuter.ph</p>
            </div>
            <div class="info-card">
                <i class='bx bx-phone'></i>
                <h3>Phone</h3>
                <p>+63 (2) 1234-5678</p>
            </div>
        </div>

        <div class="contact-form">
            <h2>Send us a Message</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="contactForm">
                <div class="form-group">
                    <label for="name">Your Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter your name" required>
                </div>

                <div class="form-group">
                    <label for="email">Your Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>

                <div class="form-group">
                    <label for="message">Your Message</label>
                    <textarea id="message" name="message" placeholder="Write your message here..." required></textarea>
                </div>

                <button type="submit" name="submit" class="submit-btn">Send Message</button>
            </form>
        </div>

        <div style="text-align: center; margin-top: 40px;">
            <h3 style="color: #1a237e; margin-bottom: 20px;">Follow Us</h3>
            <div class="social-links" style="display: flex; justify-content: center; gap: 20px;">
                <a href="https://www.facebook.com/iskomyuter.manila?mibextid=2JQ9oc" target="_blank" style="color: #3b5998; font-size: 40px;"><i class='bx bxl-facebook-circle'></i></a>
                <a href="https://www.instagram.com/iskomyuter_manila2024/" target="_blank" style="color: #e4405f; font-size: 40px;"><i class='bx bxl-instagram-alt'></i></a>
            </div>
        </div>
    </div>

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
