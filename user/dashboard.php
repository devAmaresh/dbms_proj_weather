<?php
include("dbconnection.php");
session_start();
include("includes/authenticate.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Admin Dashboard</title>
 <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
 Welcome to Dashboard
 Name : <?php echo $_SESSION['user_name']; ?>
 
 <a href="logout.php">Logout</a> 
</body>
</html>