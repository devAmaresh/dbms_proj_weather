<?php
include ("dbconnection.php");
session_start();
include("includes/authenticate.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Dashboard</title>
 <link rel="stylesheet" href="../src/output.css" />
</head>

<body>
 <?php
 include_once ("includes/sidebar.php");
 ?>
 <div class="grid grid-cols-12">
  <div class="col-span-3"></div>
  <!-- write the content here -->
  <div class="col-span-9 pt-2">
   <div class="text-center font-bold">Welcome <?php echo $_SESSION['admin_name']?></div>
  </div>

 </div>

</body>

</html>