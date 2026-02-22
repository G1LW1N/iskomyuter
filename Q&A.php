<!DOCTYPE html>
<html lang="en" style="scroll-behavior: smooth;">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Frequently Asked Questions - Iskomyuter.ph">
    <title>FAQs - Iskomyuter.ph</title>
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
            <h1>Frequently Asked Questions</h1>
            <p>Find answers to common questions about Iskomyuter</p>
        </div>
    </section>

    <div class="container">
        <div class="qa-section">
            <div class="faq-item">
                <div class="question">
                    <span>Q: How can I find the best route to my destination?</span>
                    <i class='bx bx-chevron-down'></i>
                </div>
                <div class="answer">A: You can use our route planner feature to input your starting point and destination to get the optimal route. Simply go to the MAP page and enter your locations.</div>
            </div>
            
            <div class="faq-item">
                <div class="question">
                    <span>Q: Why I can't find the place I'm looking for?</span>
                    <i class='bx bx-chevron-down'></i>
                </div>
                <div class="answer">A: Sorry about that! Sometimes our search results don't have certain location names. Try using nearby landmarks or popular places as reference points.</div>
            </div>

            <div class="faq-item">
                <div class="question">
                    <span>Q: Why are no routes showing up?</span>
                    <i class='bx bx-chevron-down'></i>
                </div>
                <div class="answer">A: We only have routes inside Metro Manila. Regional routes data are not available yet. We're continuously working to expand our coverage.</div>
            </div>

            <div class="faq-item">
                <div class="question">
                    <span>Q: Do you have a transportation vehicle or a driver?</span>
                    <i class='bx bx-chevron-down'></i>
                </div>
                <div class="answer">A: Sorry, we don't. We only show commute routes, we do not provide vehicles or rides, unlike joyride, angkas and grab. We're a route planning and navigation platform.</div>
            </div>

            <div class="faq-item">
                <div class="question">
                    <span>Q: Is the route information real-time?</span>
                    <i class='bx bx-chevron-down'></i>
                </div>
                <div class="answer">A: Yes, our route information is updated in real-time to provide you with the latest and most accurate details about commuting options in Metro Manila.</div>
            </div>

            <div class="faq-item">
                <div class="question">
                    <span>Q: Is Iskomyuter free to use?</span>
                    <i class='bx bx-chevron-down'></i>
                </div>
                <div class="answer">A: Yes! Iskomyuter is completely free to use. Our mission is to help make commuting easier for everyone in Metro Manila.</div>
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
