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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <!-- Add your CSS and other meta tags here -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            color: #555;
        }

        input,
        textarea {
            padding: 10px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #550f15;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #c6d400;
        }

        .social-links {
            margin-top: 20px;
            text-align: center;
        }

        .social-links a {
            display: inline-block;
            text-decoration: none;
            color: #fff;
            padding: 10px;
            margin: 0 10px;
            border-radius: 5px;
        }

        .facebook {
            background-color: #3b5998;
        }

        .instagram {
            background-color: #e4405f;
        }

        .google {
            background-color: #027508;
        }

        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Contact Us</h2>
        <?php
        // Display error message if set
        if (isset($_SESSION['error_message'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']);
        }

        // Display success message if set
        if (isset($_SESSION['success_message'])) {
            echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
            unset($_SESSION['success_message']);
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="contactForm">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Message:</label>
            <textarea id="message" name="message" rows="4" required></textarea>

            <button type="submit" name="submit">Submit</button>
        </form>

      
        <div class="social-links">
            <a href="https://www.facebook.com/iskomyuter.manila?mibextid=2JQ9oc" class="facebook" target="_blank">Facebook</a>
            <a href="https://www.instagram.com/iskomyuter_manila2024/?fbclid=IwAR1dnz6XzRhLSTQeM2NOLU8brBRA7AvMr2m7295kVzOQEqYOioW9nSsqXEw" class="instagram" target="_blank">Instagram</a>
            <a href="FirstPage.php" class="google" target="_blank">Go back</a>
        </div>
    </div>

    </div>

 
    <script>
        function submitForm() {
            
            alert('Thank You for Contacting Iskomyuter');
        }
    </script>

</body>
</html>
