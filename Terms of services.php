<!DOCTYPE html>
<html lang="en" style="scroll-behavior: smooth;">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Terms of Service - Iskomyuter.ph">
    <title>Terms of Service - Iskomyuter.ph</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/pages-style.css">
</head>

<body>
    <section class="page-header">
        <nav class="nav">
            <a href="index.php"><img src="images/iskomyuter.png" alt="Iskomyuter Logo"></a>
            <div class="nav-links">
                <ul>
                    <li><a href="index.php">HOME</a></li>
                    <li><a href="map.php">MAP</a></li>
                    <li><a href="index.php#blog">BLOG</a></li>
                    <li><a href="index.php#about">ABOUT US</a></li>
                    <li><a href="index.php#footer">CONTACT</a></li>
                    <li class="log"><a href="login.php">Log in</a></li>
                </ul>
            </div>
        </nav>

        <div class="page-title">
            <h1>Terms of Service</h1>
            <p>Rules and guidelines for using Iskomyuter.ph</p>
        </div>
    </section>

    <div class="container">
        <h2>1. Introduction</h2>
        <p>Welcome to Iskomyuter.ph's Terms of Service page. These terms outline the conditions that apply to your use of our route planning and navigation services. By accessing or using Iskomyuter.ph, you agree to be bound by these terms.</p>

        <h2>2. Acceptance of Terms</h2>
        <p>By accessing or using our services, you agree to be bound by these terms and conditions. If you do not agree to these terms, please do not use our services. We reserve the right to modify these terms at any time.</p>

        <h2>3. Service Description</h2>
        <p>Iskomyuter.ph provides route planning and navigation services for public transportation in Metro Manila. Our services include:</p>
        <ul>
            <li>Route recommendations and directions</li>
            <li>Transportation mode information</li>
            <li>Estimated travel times and distances</li>
            <li>Community features and user accounts</li>
        </ul>

        <h2>4. User Conduct</h2>
        <p>When using our services, you agree to:</p>
        <ul>
            <li>Provide accurate and truthful information</li>
            <li>Not violate any applicable laws or regulations</li>
            <li>Not use our services for any illegal or unauthorized purpose</li>
            <li>Not attempt to interfere with or disrupt our services</li>
            <li>Not post offensive, harmful, or inappropriate content</li>
            <li>Respect other users and the community</li>
        </ul>

        <h2>5. Disclaimer of Warranties</h2>
        <p>Iskomyuter.ph is provided "as is" without warranties of any kind. We do not guarantee:</p>
        <ul>
            <li>The accuracy or completeness of route information</li>
            <li>Uninterrupted or error-free service</li>
            <li>That routes will always be available or safe</li>
        </ul>
        <p>Users are responsible for their own safety and should use common sense when following routes.</p>

        <h2>6. Limitation of Liability</h2>
        <p>Iskomyuter.ph and its operators shall not be liable for any damages arising from the use or inability to use our services, including but not limited to delays, accidents, or inconveniences during commuting.</p>

        <h2>7. User Accounts</h2>
        <p>You are responsible for:</p>
        <ul>
            <li>Maintaining the confidentiality of your account credentials</li>
            <li>All activities that occur under your account</li>
            <li>Notifying us immediately of any unauthorized use</li>
        </ul>

        <h2>8. Intellectual Property</h2>
        <p>All content, features, and functionality on Iskomyuter.ph are owned by us or our licensors and are protected by copyright, trademark, and other intellectual property laws.</p>

        <h2>9. Termination</h2>
        <p>We reserve the right to terminate or suspend your access to our services at any time, without notice, for conduct that we believe violates these terms or is harmful to other users or our business.</p>

        <h2>10. Changes to Terms</h2>
        <p>We reserve the right to update or change these terms at any time. Changes will be effective immediately upon posting. Please check this page periodically for updates.</p>

        <h2>11. Governing Law</h2>
        <p>These terms shall be governed by and construed in accordance with the laws of the Republic of the Philippines.</p>

        <h2>12. Contact Us</h2>
        <p>If you have any questions about these terms, please contact us at <a href="mailto:info@iskomyuter.ph">info@iskomyuter.ph</a> or visit our <a href="Contacts.php">Contact Page</a>.</p>
        
        <p style="text-align: center; margin-top: 40px; color: #999; font-size: 14px;">Last Updated: January 2026</p>
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
