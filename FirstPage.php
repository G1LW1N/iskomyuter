<!DOCTYPE html>
<html lang="en" style="scroll-behavior: smooth;">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Iskomyuter.ph - Your Ultimate Guide to Seamless Commutes in Metro Manila">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Iskomyuter.ph - Navigate Your Journey with Ease</title>
    <link rel="stylesheet" href="css/blog.css?v=3.5">
    <link rel="stylesheet" href="css/AboutUs-Iskomyuter.css?v=3.5">
    <link rel="stylesheet" href="css/contact.css?v=3.5">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>


<body>
    <!-- Header Section -->
    <section class="header" id="home">
        <nav class="nav">
            <a href="FirstPage.php"><img src="images/iskomyuter.png" alt="Iskomyuter Logo"></a>
            <div class="nav-links">
                <ul>
                    <li><a href="FirstPage.php">HOME</a></li>
                    <li><a href="map.php">MAP</a></li>
                    <li><a href="#blog">BLOG</a></li>
                    <li><a href="#about">ABOUT US</a></li>
                    <li><a href="#footer">CONTACT</a></li>
                    <li class="log"><a href="login.php">Log in</a></li>
                </ul>
            </div>
        </nav>

        <div class="text-box">
            <h1>Iskomyuter.ph</h1>
            <p>Navigate Your Journey with Ease<br>Your Ultimate Guide to Seamless Commutes!</p>
            <a href="map.php" class="hero-btn">Get Directions</a>
        </div>
    </section>

    <!--blog section with tabs-->
    <section class="blog" id="blog">
        <h1>Simplify Your Commute with Iskomyuter:<br>
            Your Ultimate Guide to Easy and Efficient Transportation</h1>

        <!-- Tab Navigation -->
        <div class="tab-navigation">
            <button class="tab-btn active" onclick="openTab(event, 'guide')">
                <i class='bx bx-info-circle'></i>
                <span>Understanding Iskomyuter</span>
            </button>
            <button class="tab-btn" onclick="openTab(event, 'routes')">
                <i class='bx bx-map-alt'></i>
                <span>Easy Commute Routes</span>
            </button>
            <button class="tab-btn" onclick="openTab(event, 'stories')">
                <i class='bx bx-user-circle'></i>
                <span>Success Stories</span>
            </button>
        </div>

        <!-- Tab Content -->
        <div id="guide" class="tab-content active">
            <div class="content-wrapper">
                <div class="content-image">
                    <img src="images/passengers.png" alt="Understanding Iskomyuter">
                </div>
                <div class="content-text">
                    <h2>Navigating the Iskomyuter Website: A Comprehensive Guide</h2>
                    <p>For commuters aiming to simplify their daily journeys, mastering the Iskomyuter website is crucial. This guide acts as a helpful tool, leading you through its features, registration process, and providing useful tips to enhance your commuting experience.</p>
                    <p>Understanding the Iskomyuter platform comprehensively ensures an efficient and stress-free journey. With features like personalized trip planning and community engagement, Iskomyuter becomes an invaluable tool for a seamless commuting experience tailored to your needs.</p>
                    <div class="feature-list">
                        <div class="feature-item">
                            <i class='bx bx-check-circle'></i>
                            <span>Easy registration and profile setup</span>
                        </div>
                        <div class="feature-item">
                            <i class='bx bx-check-circle'></i>
                            <span>Personalized route recommendations</span>
                        </div>
                        <div class="feature-item">
                            <i class='bx bx-check-circle'></i>
                            <span>Real-time traffic updates</span>
                        </div>
                        <div class="feature-item">
                            <i class='bx bx-check-circle'></i>
                            <span>Community engagement features</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="routes" class="tab-content">
            <div class="content-wrapper">
                <div class="content-image">
                    <img src="images/routes.png" alt="Easy Commute Routes">
                </div>
                <div class="content-text">
                    <h2>Discovering the Easy Commute Route: A Stress-Free Journey</h2>
                    <p>Starting your day with a stress-free commute can positively impact your daily routine. This guide is dedicated to unraveling the secrets of discovering an optimal and easy commute route.</p>
                    
                    <div class="route-tips">
                        <div class="tip-card">
                            <i class='bx bx-bus'></i>
                            <h3>Choose the Right Mode</h3>
                            <p>Evaluate diverse transportation alternatives - public transit, cycling, walking, or a combination. Understanding the advantages of each method empowers you to customize your commute.</p>
                        </div>
                        <div class="tip-card">
                            <i class='bx bx-mobile'></i>
                            <h3>Utilize Technology</h3>
                            <p>Take advantage of modern technology's array of tools to streamline your daily commute. Iskomyuter helps you effortlessly plan and navigate your route.</p>
                        </div>
                        <div class="tip-card">
                            <i class='bx bx-time'></i>
                            <h3>Avoid Peak Hours</h3>
                            <p>Master the art of timing. Learn strategies to avoid rush hours and traffic bottlenecks, ensuring a smoother journey with less time spent in transit.</p>
                        </div>
                        <div class="tip-card">
                            <i class='bx bx-customize'></i>
                            <h3>Personalize Your Routine</h3>
                            <p>Make your commute uniquely yours by tailoring it to your preferences. Add personal touches to enhance the daily routine and make it more enjoyable.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="stories" class="tab-content">
            <div class="content-wrapper full-width">
                <h2 class="section-title">Iskomyuter Success Stories</h2>
                <p class="section-subtitle">Real experiences from our community members</p>
                
                <div class="stories-grid">
                    <div class="story-card">
                        <div class="story-image">
                            <img src="images/file.jpg" alt="Alex">
                        </div>
                        <div class="story-content">
                            <h3>Building Bridges: Alex's Community Journey</h3>
                            <p class="story-excerpt">Alex's Iskomyuter success story is evidence of the platform's transformative impact on his daily commute and connections with fellow travelers. Using Iskomyuter, Alex effortlessly optimized routes through user-friendly features, saving time and simplifying his travels.</p>
                            <div class="story-tags">
                                <span class="tag"><i class='bx bx-group'></i> Community</span>
                                <span class="tag"><i class='bx bx-time'></i> Time Saver</span>
                            </div>
                        </div>
                    </div>

                    <div class="story-card">
                        <div class="story-image">
                            <img src="images/female.webp" alt="Sarah">
                        </div>
                        <div class="story-content">
                            <h3>Navigating Challenges: Sarah's Success Story</h3>
                            <p class="story-excerpt">Explore how Sarah overcame commuting challenges with Iskomyuter's help. Facing technical issues and navigation uncertainties, Sarah found solutions in the troubleshooting and FAQs sections, turning her commute from stressful to stress-free.</p>
                            <div class="story-tags">
                                <span class="tag"><i class='bx bx-support'></i> Support</span>
                                <span class="tag"><i class='bx bx-happy'></i> Satisfaction</span>
                            </div>
                        </div>
                    </div>

                    <div class="story-card">
                        <div class="story-image">
                            <img src="images/man.webp" alt="Mark">
                        </div>
                        <div class="story-content">
                            <h3>On-the-Go Convenience: Mark's Journey</h3>
                            <p class="story-excerpt">Mark, a frequent jetsetter, found his ideal commuting companion in the Iskomyuter mobile app. The app's on-the-go convenience seamlessly integrated into his fast-paced lifestyle, making his daily journeys a seamless adventure.</p>
                            <div class="story-tags">
                                <span class="tag"><i class='bx bx-mobile'></i> Mobile</span>
                                <span class="tag"><i class='bx bx-trip'></i> Travel</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function openTab(evt, tabName) {
            var i, tabContent, tabBtn;
            
            // Hide all tab content
            tabContent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabContent.length; i++) {
                tabContent[i].classList.remove("active");
            }
            
            // Remove active class from all buttons
            tabBtn = document.getElementsByClassName("tab-btn");
            for (i = 0; i < tabBtn.length; i++) {
                tabBtn[i].classList.remove("active");
            }
            
            // Show current tab and mark button as active
            document.getElementById(tabName).classList.add("active");
            evt.currentTarget.classList.add("active");
        }
    </script>

    <!--call to action-->
    <section class="cta">
        <h1>
            Start Your Journey with Iskomyuter.ph
        </h1>
        <a href="login.php" class="hero-btn"> LOG IN</a>
    </section>


    <section id="about" class="about-section">
        <div class="about-container">
            <div class="about-header">
                <h2>About Iskomyuter</h2>
                <p>Transforming the way Metro Manila commutes</p>
            </div>

            <div class="about-content">
                <div class="about-card">
                    <div class="card-icon mission">
                        <i class='bx bx-target-lock'></i>
                    </div>
                    <div class="card-content">
                        <h3>Our Mission</h3>
                        <h4>Optimizing Daily Commutes Efficiently</h4>
                        <p>At Iskomyuter.ph, we're on a mission to simplify and enhance the daily commute in Metro Manila. Using advanced technology and data-driven insights, we provide commuters with the best and most efficient routes, reducing travel time and enhancing overall satisfaction.</p>
                    </div>
                </div>

                <div class="about-card">
                    <div class="card-icon vision">
                        <i class='bx bx-bulb'></i>
                    </div>
                    <div class="card-content">
                        <h3>Our Vision</h3>
                        <h4>Enabling Seamless Urban Connectivity</h4>
                        <p>We envision a streamlined and efficient commuting experience in Metro Manila, where Iskomyuter.ph serves as the go-to platform for personalized, real-time route information. Our goal is to contribute to a more connected and accessible city, making every commuter's journey smooth, reliable, and time-saving.</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Footer Section -->
    <footer id="footer" class="modern-footer">
        <div class="footer-content">
            <div class="footer-section">
                <div class="footer-logo">
                    <img src="images/iskomyuter.png" alt="Iskomyuter Logo">
                    <h3>Iskomyuter.ph</h3>
                </div>
                <p class="footer-description">Your ultimate guide to seamless commutes in Metro Manila. Navigate smarter, travel better.</p>
                <div class="social-links">
                    <a href="#" aria-label="Facebook"><i class='bx bxl-facebook-circle'></i></a>
                    <a href="#" aria-label="Twitter"><i class='bx bxl-twitter'></i></a>
                    <a href="#" aria-label="Instagram"><i class='bx bxl-instagram'></i></a>
                    <a href="#" aria-label="LinkedIn"><i class='bx bxl-linkedin-square'></i></a>
                </div>
            </div>

            <div class="footer-section">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="FirstPage.php">Home</a></li>
                    <li><a href="map.php">Route Planner</a></li>
                    <li><a href="#about">About Us</a></li>
                    <li><a href="#blog">Blog & Tips</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h4>Support</h4>
                <ul>
                    <li><a href="Q&A.php">FAQs</a></li>
                    <li><a href="Contacts.php">Contact Us</a></li>
                    <li><a href="Privacy.php">Privacy Policy</a></li>
                    <li><a href="Terms of services.php">Terms of Service</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h4>Contact Info</h4>
                <ul class="contact-info">
                    <li><i class='bx bx-map'></i> Metro Manila, Philippines</li>
                    <li><i class='bx bx-envelope'></i> info@iskomyuter.ph</li>
                    <li><i class='bx bx-phone'></i> +63 (2) 1234-5678</li>
                    <li><i class='bx bx-time'></i> Mon-Fri: 9AM - 6PM</li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2026 Iskomyuter.ph. All rights reserved.</p>
            <p>Made with <i class='bx bx-heart'></i> for Filipino Commuters</p>
        </div>
    </footer>

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