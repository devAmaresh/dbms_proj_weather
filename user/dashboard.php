<?php
include("dbconnection.php");
session_start();
include("includes/authenticate.php");
$sql = "SELECT * FROM users WHERE user_id = " . $_SESSION['user_id'];
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$pincode = $row['pincode'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<?php include_once ("includes/sidebar.php"); ?>
<div class="grid grid-cols-12">
    <div class="col-span-3"></div>
    <!-- write the content here -->
    <div class="col-span-9 pt-2">
        <div class="text-center font-bold text-blue-400 text-2xl">Welcome <?php echo $_SESSION['user_name']?></div>
        <!-- Display weather information here -->
        <div id="weather" class="mt-8"></div>
    </div>
</div>

<!-- Link the JavaScript file -->
<script src="helper/weather.js"></script>

<script>
    // Call fetchWeather function with the user's pincode
    fetchWeather('<?php echo $pincode; ?>');
</script>
</body>
</html>
