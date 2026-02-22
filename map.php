<?php
session_start();

// Redirect logged-in users to Route.php
if (isset($_SESSION["user"])) {
    header("Location: Route.php");
    exit();
}

// Initialize user variables
$user = isset($_SESSION["user"]) ? $_SESSION["user"] : "";
$userid = isset($_SESSION["id"]) ? $_SESSION["id"] : "";
?>
<!DOCTYPE html>
<html lang="en" style="scroll-behavior: smooth;">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Route Planner - Iskomyuter.ph">
    <title>Route Planner - Iskomyuter.ph</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="css/blog.css">
    <link rel="stylesheet" href="css/projectstyle.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Leaflet Routing Machine CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <!-- Leaflet Control Geocoder CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
</head>

<body>
    <section class="map-header">
        <nav class="nav">
            <a href="FirstPage.php"><img src="images/iskomyuter.png" alt="Iskomyuter Logo"></a>
            <div class="nav-links">
                <ul>
                    <li><a href="FirstPage.php">HOME</a></li>
                    <li><a href="map.php">MAP</a></li>
                    <!-- <li><a href="FirstPage.php#blog">BLOG</a></li>
                    <li><a href="FirstPage.php#about">ABOUT US</a></li>
                    <li><a href="FirstPage.php#footer">CONTACT</a></li> -->
                    <li class="log"><a href="login.php">Log in</a></li>
                </ul>
            </div>
        </nav>
    </section>

    <div class="map-container">
        <div class="container">
            <div class="map-title-section">
                <h1><i class='bx bx-map'></i> Plan Your Route</h1>
                <p>Streamline your daily commute with us - Get directions, distances, and fare estimates</p>
            </div>

            <div class="route-planner-card">
                <form action="" class="route-form">
                    <div class="input-group-custom">
                        <div class="icon-wrapper">
                            <i class='bx bx-current-location'></i>
                        </div>
                        <input type="text" id="from" placeholder="Enter starting location" class="form-control-custom">
                    </div>

                    <div class="input-group-custom">
                        <div class="icon-wrapper">
                            <i class='bx bx-map-pin'></i>
                        </div>
                        <input type="text" id="to" placeholder="Enter destination" class="form-control-custom">
                    </div>

                    <div class="transport-mode-section">
                        <h3><i class='bx bx-car'></i> Transportation Mode</h3>
                        <div class="transport-options">
                            <label class="transport-option">
                                <input type="radio" name="transportation" value="all">
                                <span class="option-content">
                                    <i class='bx bx-transfer'></i>
                                    <span>All Modes</span>
                                </span>
                            </label>
                            <label class="transport-option">
                                <input type="radio" name="transportation" value="driving" checked>
                                <span class="option-content">
                                    <i class='bx bx-car'></i>
                                    <span>Taxi / Car</span>
                                </span>
                            </label>
                            <label class="transport-option">
                                <input type="radio" name="transportation" value="bus">
                                <span class="option-content">
                                    <i class='bx bx-bus'></i>
                                    <span>Bus / Jeepney</span>
                                </span>
                            </label>
                            <label class="transport-option">
                                <input type="radio" name="transportation" value="jeepney">
                                <span class="option-content">
                                    <i class='bx bx-train'></i>
                                    <span>Train</span>
                                </span>
                            </label>
                        </div>
                    </div>

                    <input type="hidden" id="user" name="user" value="<?php echo $user; ?>">
                    <input type="hidden" id="userid" name="userid" value="<?php echo $userid; ?>">

                    <div class="button-group">
                        <button type="button" class="btn-calculate" onclick="calcRoute()">
                            <i class='bx bx-navigation'></i> Calculate Route
                        </button>
                        <button type="button" class="btn-back" onclick="location.href='FirstPage.php'">
                            <i class='bx bx-arrow-back'></i> Back to Home
                        </button>
                    </div>
                </form>

                <div id="googleMap" class="google-map"></div>
                <div id="output" class="route-output"></div>
                <div id="output2"></div>
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

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Leaflet Routing Machine JS -->
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
    <!-- Leaflet Control Geocoder JS -->
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-rbs5n513fCq6QmA9z8JDL8pG1LdhgLz5w5t8xvYU6/DfOMAv/9iVbp4Pa9dHbIZ5"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <script src="Route.js"></script>
</body>

</html>