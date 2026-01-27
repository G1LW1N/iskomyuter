<?php
// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
    // you want to allow, and if so:
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    //header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        // may also be using PUT, PATCH, HEAD etc
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}
session_start();
// Check if the user is not logged in, redirect to login page
$user = isset($_SESSION["user"]) ? $_SESSION["user"] : null;
$userid = isset($_SESSION["id"]) ? $_SESSION["id"] : null;


if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html>

<head>


    <meta charset="utf-8" />
    <meta name="viewort" content="width=device-width, initial-scale=1.0">
    <title>Distance app</title>
    <!-- <link href="Content/bootstrap.min.css" rel="stylesheet"> -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Font Awesome Link -->
    <link rel="stylesheet" href="css/projectstyle.css">
   
</head>


<body>
    

        <div class="Mapping">
            <div class="container">
                <h1>Where are you going? </h1>
                <p> Streamline your daily commute with us </p>
                <form action="" class="row g-3">
                    <div class="col-2">
                        <label for="from" class="form-label"><i class="fas fa-circle-dot"></i></label>
                    </div>
                    <div class="col-10">
                        <input type="text" id="from" placeholder="Origin" class="form-control">
                    </div>

                    <div class="col-2">
                        <label for="to" class="form-label"><i class="fas fa-location-dot"></i></label>
                    </div>
                    <div class="col-10">
                        <input type="text" id="to" placeholder="Destination" class="form-control">
                    </div>

                    <div class="col-2">
                        <label for="mode" class="form-label"><i class="fas fa-car"></i></label>
                    </div>
                    <label>
                        <input type="radio" name="transportation" value="all"> All Modes
                    </label>
                    <label>
                        <input type="radio" name="transportation" value="driving" checked> Motorcyle / Taxi / Personal vehicle
                    </label>
                    <label>
                        <input type="radio" name="transportation" value="jeepney/bus"> Bus / Jeepney
                    </label>
                    <label>
                        <input type="radio" name="transportation" value="train"> LRT / MRT / PNR
                    </label>

                    <input type="hidden" id="user" name="user" value="<?php echo $user; ?>">
                    <input type="hidden" id="userid" name="userid" value="<?php echo $userid; ?>">

                    <div class="col-12 mt-3">
                        <button type="button" class="btn btn-info btn-lg" onclick="calcRoute()"><i
                                class="fas fa-diamond-turn-right"></i></button>
                    </div>

                </form>

                <div id="googleMap"></div>
                <div id="output"></div>
                <div class="col-12 mt-3">
                    <button type="button" class="btn btn-success btn-lg" onclick="saveRoute()"><i
                            class="fas fa-save"></i>
                        Save Route</button>

                <button  type="button" class="btn btn-warning" ><a style ="color:white; text-decoration:none;" href="index.php" >Dashboard</a></button>
                  
                </div>
                <div id="output2"></div>
            </div>
        </div>

        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAXSCzvgYFfLATJ2hA4suvBnhHyOcSjTnQ"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-rbs5n513fCq6QmA9z8JDL8pG1LdhgLz5w5t8xvYU6/DfOMAv/9iVbp4Pa9dHbIZ5"
            crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
        <script src="Route.js"></script>


    </section>

  

</body>

</html>