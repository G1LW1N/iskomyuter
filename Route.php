<?php
// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    exit(0);
}

session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$user = isset($_SESSION["user"]) ? $_SESSION["user"] : null;
$userid = isset($_SESSION["id"]) ? $_SESSION["id"] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Route Planner - Iskomyuter</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Leaflet Routing Machine CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <!-- Leaflet Control Geocoder CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .route-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Header */
        .route-header {
            background: #fff;
            padding: 20px 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .route-header h1 {
            color: #2c3e50;
            font-size: 28px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .route-header h1 i {
            color: #667eea;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }

        .btn-back {
            padding: 10px 25px;
            background: #fff;
            color: #667eea;
            border: 2px solid #667eea;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: #667eea;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        /* Main Content */
        .route-content {
            display: grid;
            grid-template-columns: 450px 1fr;
            gap: 30px;
        }

        /* Form Card */
        .form-card {
            background: #fff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            height: fit-content;
        }

        .form-card h2 {
            color: #2c3e50;
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        /* Input Groups */
        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            color: #555;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .input-group label i {
            color: #667eea;
            font-size: 18px;
        }

        .input-group input[type="text"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e8e8e8;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .input-group input[type="text"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        /* Transport Mode Section */
        .transport-section {
            margin-top: 25px;
        }

        .transport-section h3 {
            color: #2c3e50;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .transport-section h3 i {
            color: #667eea;
        }

        .transport-options {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .transport-option {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border: 2px solid #e8e8e8;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .transport-option:hover {
            border-color: #667eea;
            background: #f8f9ff;
        }

        .transport-option input[type="radio"] {
            margin-right: 12px;
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .transport-option:has(input:checked) {
            border-color: #667eea;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 25px;
        }

        .btn-calculate {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .btn-calculate:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-save {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .btn-save.show {
            display: flex;
        }

        .btn-save:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(40, 167, 69, 0.4);
        }

        /* Map Card */
        .map-card {
            background: #fff;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        #googleMap {
            width: 100%;
            height: 600px;
            border-radius: 10px;
            background: #f5f5f5;
        }

        /* Output */
        #output {
            margin-top: 20px;
            padding: 20px;
            background: #f8f9ff;
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }

        #output2 {
            margin-top: 15px;
            padding: 15px;
            border-radius: 10px;
        }

        .success-message {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, rgba(32, 201, 151, 0.1) 100%);
            border-left: 4px solid #28a745;
            color: #155724;
        }

        .error-message {
            background: linear-gradient(135deg, rgba(244, 67, 54, 0.1) 0%, rgba(233, 30, 99, 0.1) 100%);
            border-left: 4px solid #f44336;
            color: #721c24;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .route-content {
                grid-template-columns: 1fr;
            }

            .form-card {
                max-width: 100%;
            }
        }

        @media (max-width: 768px) {
            .route-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .route-header h1 {
                font-size: 22px;
            }

            .header-actions {
                width: 100%;
                justify-content: center;
            }

            #googleMap {
                height: 400px;
            }
        }
    </style>
</head>

<body>
    <div class="route-container">
        <!-- Header -->
        <div class="route-header">
            <h1><i class='bx bx-navigation'></i> Route Planner</h1>
            <div class="header-actions">
                <a href="index.php" class="btn-back">
                    <i class='bx bx-arrow-back'></i> Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="route-content">
            <!-- Form Card -->
            <div class="form-card">
                <h2>Plan Your Journey</h2>
                
                <form>
                    <div class="input-group">
                        <label for="from">
                            <i class='bx bx-current-location'></i> Starting Point
                        </label>
                        <input type="text" id="from" placeholder="Enter your origin" autocomplete="off">
                    </div>

                    <div class="input-group">
                        <label for="to">
                            <i class='bx bx-map-pin'></i> Destination
                        </label>
                        <input type="text" id="to" placeholder="Enter your destination" autocomplete="off">
                    </div>

                    <div class="transport-section">
                        <h3><i class='bx bx-car'></i> Transportation Mode</h3>
                        <div class="transport-options">
                            <label class="transport-option">
                                <input type="radio" name="transportation" value="all">
                                <span>All Modes</span>
                            </label>
                            <label class="transport-option">
                                <input type="radio" name="transportation" value="driving" checked>
                                <span>Motorcycle / Taxi / Personal Vehicle</span>
                            </label>
                            <label class="transport-option">
                                <input type="radio" name="transportation" value="jeepney/bus">
                                <span>Bus / Jeepney</span>
                            </label>
                            <label class="transport-option">
                                <input type="radio" name="transportation" value="train">
                                <span>LRT / MRT / PNR</span>
                            </label>
                        </div>
                    </div>

                    <input type="hidden" id="user" name="user" value="<?php echo htmlspecialchars($user); ?>">
                    <input type="hidden" id="userid" name="userid" value="<?php echo htmlspecialchars($userid); ?>">

                    <div class="action-buttons">
                        <button type="button" class="btn-calculate" onclick="calcRoute()">
                            <i class='bx bx-navigation'></i> Calculate Route
                        </button>
                        <button type="button" class="btn-save" onclick="saveRoute()">
                            <i class='bx bx-save'></i> Save Route
                        </button>
                    </div>
                </form>

                <div id="output2"></div>
            </div>

            <!-- Map Card -->
            <div class="map-card">
                <div id="googleMap"></div>
                <div id="output"></div>
            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Leaflet Routing Machine JS -->
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
    <!-- Leaflet Control Geocoder JS -->
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    
    <script src="Route.js"></script>
</body>
</html>