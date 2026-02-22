<!DOCTYPE html>
<html lang="en" style="scroll-behavior: smooth;">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Privacy Policy - Iskomyuter.ph">
    <title>Privacy Policy - Iskomyuter.ph</title>
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
            <h1>Privacy Policy</h1>
            <p>How we protect and handle your information</p>
        </div>
    </section>

    <div class="container">
        <p>This Privacy Policy explains how Iskomyuter.ph collects, uses, and protects your personal information when you use our website or services. By using our website or services, you agree to the terms outlined in this policy.</p>

        <h2>1. Information We Collect</h2>
        <p>We may collect the following types of information:</p>
        <ul>
            <li><strong>Personal Information:</strong> Name, email address, username, and other registration details</li>
            <li><strong>Location Data:</strong> Starting point and destination information you provide for route planning</li>
            <li><strong>Log Data:</strong> Information about your use of the service, such as IP address, browser type, pages visited</li>
            <li><strong>Cookies:</strong> Small data files stored on your device for record-keeping and improved user experience</li>
        </ul>

        <h2>2. How We Use Your Information</h2>
        <p>We use the collected information for various purposes, including:</p>
        <ul>
            <li>Providing and maintaining our route planning services</li>
            <li>Improving and personalizing the user experience</li>
            <li>Analyzing usage patterns to enhance our platform</li>
            <li>Communicating important updates and information</li>
            <li>Ensuring security and preventing fraud</li>
        </ul>

        <h2>3. Information Sharing</h2>
        <p>We do not sell, trade, or rent your personal information to third parties. We may share information only in the following circumstances:</p>
        <ul>
            <li>With your explicit consent</li>
            <li>To comply with legal obligations</li>
            <li>To protect our rights and safety</li>
        </ul>

        <h2>4. Data Security</h2>
        <p>We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over the internet is 100% secure.</p>

        <h2>5. Your Rights</h2>
        <p>You have the right to:</p>
        <ul>
            <li>Access your personal information</li>
            <li>Correct inaccurate data</li>
            <li>Request deletion of your data</li>
            <li>Opt-out of marketing communications</li>
        </ul>

        <h2>6. Children's Privacy</h2>
        <p>Our services are not directed to individuals under the age of 13. We do not knowingly collect personal information from children.</p>

        <h2>7. Changes to This Policy</h2>
        <p>We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new policy on this page with an updated "Last Updated" date.</p>

        <h2>8. Contact Us</h2>
        <p>If you have any questions about our Privacy Policy, please contact us at <a href="mailto:info@iskomyuter.ph">info@iskomyuter.ph</a> or visit our <a href="Contacts.php">Contact Page</a>.</p>
        
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
