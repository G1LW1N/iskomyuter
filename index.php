<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
header("Access-Control-Allow-Private-Network: true");
session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION["user"];
$userid = $_SESSION["id"];

include "config.php";

// Output Form Entries from the Database
$sql = "SELECT id, start, destination, distance, fare, transportmode, duration FROM routes WHERE userid = ?";
$stmt = $conn->prepare($sql);

// Bind parameters to the SQL statement
$stmt->bind_param("s", $userid);

// Execute the SQL statement
$stmt->execute();

// Get the result set
$result = $stmt->get_result();

// Get route count for stats
$count_sql = "SELECT COUNT(*) as total FROM routes WHERE userid = ?";
$count_stmt = $conn->prepare($count_sql);
$count_stmt->bind_param("s", $userid);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$route_count = $count_result->fetch_assoc()['total'];
$count_stmt->close();

?>

<!DOCTYPE html>
<html lang="en" style="scroll-behavior: smooth;">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <title>Dashboard - Iskomyuter.ph</title>
    <link rel="stylesheet" href="css/blog.css?v=3.5">
    <link rel="stylesheet" href="css/AboutUs-Iskomyuter.css?v=3.5">
    <link rel="stylesheet" href="css/contact.css?v=3.5">

    <style>
        /* User Dropdown Styles Only */
        .user-dropdown {
            position: relative;
            z-index: 10000;
        }

        .user-menu-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 10px 18px;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .user-menu-btn:hover {
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            transform: translateY(-2px);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #667eea;
            font-weight: 700;
            font-size: 14px;
        }

        .user-name {
            color: white;
            font-weight: 600;
            font-size: 13px;
        }

        .dropdown-icon {
            color: white;
            font-size: 16px;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            min-width: 220px;
            margin-top: 10px;
            z-index: 999999;
            display: none;
        }

        .dropdown-menu a {
            display: flex !important;
            align-items: center;
            gap: 12px;
            padding: 15px 20px;
            color: #333 !important;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 14px;
            border-bottom: 1px solid #f0f0f0;
            background: white;
        }

        .dropdown-menu a:first-child {
            border-radius: 10px 10px 0 0;
        }

        .dropdown-menu a:last-child {
            border-bottom: none;
            border-radius: 0 0 10px 10px;
            color: #f44336 !important;
        }

        .dropdown-menu a:hover {
            background: #f8f9fa;
            padding-left: 25px;
        }

        .dropdown-menu a i {
            font-size: 18px;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <section class="header" id="home">
        <nav class="nav">
            <a href="index.php"><img src="images/iskomyuter.png" alt="Iskomyuter Logo"></a>
            <div class="nav-links">
                <ul>
                    <li><a href="index.php">HOME</a></li>
                    <li><a href="map.php">MAP</a></li>
                    <li><a href="#blog">BLOG</a></li>
                    <li><a href="#about">ABOUT US</a></li>
                    <li><a href="#footer">CONTACT</a></li>
                    <li class="user-dropdown">
                        <div class="user-menu-btn" onclick="toggleDropdown(event)">
                            <div class="user-avatar"><?php echo strtoupper(substr($user, 0, 1)); ?></div>
                            <span class="user-name"><?php echo ucfirst($user); ?></span>
                            <i class='bx bx-chevron-down dropdown-icon'></i>
                        </div>
                        <div class="dropdown-menu" id="userDropdownMenu">
                            <a href="profile.php">
                                <i class='bx bx-user'></i>
                                <span>My Profile</span>
                            </a>
                            <a href="#saved-routes">
                                <i class='bx bx-history'></i>
                                <span>Commute History</span>
                            </a>
                            <a href="Route.php">
                                <i class='bx bx-plus-circle'></i>
                                <span>Add New Route</span>
                            </a>
                            <a href="controllers/logout.php">
                                <i class='bx bx-log-out'></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="text-box">
            <h1>Iskomyuter.ph</h1>
            <p>Navigate Your Journey with Ease<br>Your Ultimate Guide to Seamless Commutes!</p>
            <a href="<?php echo isset($_SESSION['user']) ? 'Route.php' : 'map.php'; ?>" class="hero-btn">Get Directions</a>
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

    <!--Your Saved Routes Section-->
    <section class="saved-routes" id="saved-routes">
        <div class="routes-container">
            <h1>Your Saved Routes</h1>
            <p class="routes-subtitle">Track and manage all your commute history</p>

            <?php if ($route_count > 0): ?>
            <div class="routes-table-wrapper">
                <table class="routes-table-modern">
                    <thead>
                        <tr>
                            <th><i class='bx bx-map-pin'></i> Start Location</th>
                            <th><i class='bx bx-target-lock'></i> Destination</th>
                            <th><i class='bx bx-ruler'></i> Distance</th>
                            <th><i class='bx bx-money'></i> Fare</th>
                            <th><i class='bx bx-bus'></i> Transport</th>
                            <th><i class='bx bx-time'></i> Duration</th>
                            <th><i class='bx bx-cog'></i> Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        mysqli_data_seek($result, 0);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>
                                    <td><strong>' . htmlspecialchars($row["start"]) . '</strong></td>
                                    <td><strong>' . htmlspecialchars($row["destination"]) . '</strong></td>
                                    <td>' . htmlspecialchars($row["distance"]) . ' km</td>
                                    <td>₱ ' . number_format($row["fare"], 2) . '</td>
                                    <td><span class="transport-badge">' . htmlspecialchars($row["transportmode"]) . '</span></td>
                                    <td>' . htmlspecialchars($row["duration"]) . '</td>
                                    <td>
                                        <button class="delete-route-btn" onclick="deleteRoute(' . $row["id"] . ')">
                                            <i class="bx bx-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="add-route-section">
                <a href="Route.php" class="hero-btn">
                    <i class='bx bx-plus-circle'></i> Plan New Route
                </a>
            </div>
            <?php else: ?>
            <div class="no-routes">
                <i class='bx bx-map'></i>
                <h3>No routes saved yet</h3>
                <p>Start planning your commute and save your favorite routes!</p>
                <a href="Route.php" class="hero-btn">Plan Your First Route</a>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <style>
        /* Saved Routes Section Styles */
        .saved-routes {
            padding: 80px 6%;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        .routes-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .routes-container h1 {
            text-align: center;
            font-size: 42px;
            color: #333;
            margin-bottom: 10px;
        }

        .routes-subtitle {
            text-align: center;
            color: #666;
            font-size: 18px;
            margin-bottom: 40px;
        }

        .routes-table-wrapper {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow-x: auto;
        }

        .routes-table-modern {
            width: 100%;
            border-collapse: collapse;
        }

        .routes-table-modern thead {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .routes-table-modern thead th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
        }

        .routes-table-modern thead th:first-child {
            border-radius: 10px 0 0 0;
        }

        .routes-table-modern thead th:last-child {
            border-radius: 0 10px 0 0;
        }

        .routes-table-modern tbody tr {
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.3s;
        }

        .routes-table-modern tbody tr:hover {
            background: #f8f9fa;
        }

        .routes-table-modern tbody td {
            padding: 15px;
            color: #333;
        }

        .transport-badge {
            background: #667eea;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .delete-route-btn {
            background: #f44336;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .delete-route-btn:hover {
            background: #d32f2f;
            transform: scale(1.05);
        }

        .add-route-section {
            text-align: center;
            margin-top: 30px;
        }

        .no-routes {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .no-routes i {
            font-size: 80px;
            color: #667eea;
            margin-bottom: 20px;
        }

        .no-routes h3 {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
        }

        .no-routes p {
            color: #666;
            margin-bottom: 30px;
        }
    </style>

    <!--call to action-->
    <section class="cta">
        <h1>
            Continue Your Journey with Iskomyuter.ph
        </h1>
        <a href="map.php" class="hero-btn">PLAN NEW ROUTE</a>
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

            <div class="about-stats">
                <div class="stat-item">
                    <i class='bx bx-user'></i>
                    <h3>10,000+</h3>
                    <p>Active Users</p>
                </div>
                <div class="stat-item">
                    <i class='bx bx-map'></i>
                    <h3>50,000+</h3>
                    <p>Routes Planned</p>
                </div>
                <div class="stat-item">
                    <i class='bx bx-time'></i>
                    <h3>2hrs</h3>
                    <p>Avg. Time Saved</p>
                </div>
                <div class="stat-item">
                    <i class='bx bx-happy'></i>
                    <h3>95%</h3>
                    <p>Satisfaction Rate</p>
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
                    <li><a href="index.php">Home</a></li>
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
        // Tab switching function
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

        // Toggle dropdown menu
        function toggleDropdown(event) {
            if (event) {
                event.stopPropagation();
                event.preventDefault();
            }
            
            const dropdown = document.getElementById('userDropdownMenu');
            if (dropdown) {
                const currentDisplay = dropdown.style.display;
                dropdown.style.display = currentDisplay === 'none' || currentDisplay === '' ? 'block' : 'none';
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const userDropdown = document.querySelector('.user-dropdown');
            const dropdown = document.getElementById('userDropdownMenu');
            
            if (dropdown && userDropdown && !userDropdown.contains(event.target)) {
                dropdown.style.display = 'none';
            }
        });

        // Delete route function
        function deleteRoute(routeId) {
            if (confirm('Are you sure you want to delete this route?')) {
                window.location.href = 'controllers/deleteroute.php?routeid=' + routeId;
            }
        }

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
<?php
$stmt->close();
$conn->close();
?>
