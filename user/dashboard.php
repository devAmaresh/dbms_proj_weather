<?php
include ("dbconnection.php");
session_start();
include ("includes/authenticate.php");
$sql = "SELECT * FROM users WHERE user_id = " . $_SESSION['user_id'];
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$pincode = $row['pincode'];
$sql = "SELECT * FROM locations WHERE pincode = '$pincode'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if ($row) {
    $latitude = $row['latitude'];
    $longitude = $row['longitude'];
} else {
    $latitude = 20.5937;
    $longitude = 78.9629;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        #map { 
            height: 400px; 
            border: 2px solid #ccc; /* Add border to the map */
        }
        .weather-card {
            background-color: #f7fafc;
            border: 1px solid #edf2f7;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .weather-icon {
            font-size: 2rem;
            margin-right: 1rem;
        }
        .weather-info p {
            margin: 0;
        }
    </style>
</head>
<body class="bg-gray-100">
<?php include_once ("includes/sidebar.php"); ?>
<div class="grid grid-cols-12">
    <div class="col-span-3"></div>
    <!-- write the content here -->
    <div class="col-span-6 pt-2">
        <div class="text-center font-bold text-blue-400 text-2xl p-5">Welcome <?php echo $_SESSION['user_name']?></div>
        <!-- Map to select location -->
        <div id="map" class="border border-gray-300 rounded-lg mb-4"></div>
    </div>
    <div class="col-span-3 pt-2">
        <div class="mt-4">
            <h2 class="text-xl font-semibold mb-2 p-10"></h2>
            <div id="weather" class="flex flex-col items-center">
            </div>
        </div>
    </div>
</div>

<!-- Link the JavaScript file -->
<script src="helper/weather.js"></script>
<script>
    // Call fetchWeather function with the user's pincode
    fetchWeather('<?php echo $pincode; ?>');
</script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    var map = L.map('map').setView([<?php echo $latitude; ?>, <?php echo $longitude; ?>], 10);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);
    var marker = L.marker([<?php echo $latitude; ?>, <?php echo $longitude; ?>]).addTo(map);

    // Update marker position based on pincode
    var geocoder = L.Control.Geocoder.nominatim();
    geocoder.geocode('<?php echo $pincode; ?>, India', function(results) {
        if (results.length > 0) {
            map.setView([<?php echo $latitude; ?>, <?php echo $longitude; ?>], 12);
            marker.setLatLng([<?php echo $latitude; ?>, <?php echo $longitude; ?>]);
        }
    });

</script>
</body>
</html>
