<?php
include ("dbconnection.php");
session_start();
include ("includes/authenticate.php");
$user_id = $_SESSION['user_id'];
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 if (isset($_POST['present_password']) && !empty($_POST['present_password'])) {
  
  $present_password = $_POST['present_password'];
  $new_password = $_POST['new_password'];
  $sql = "SELECT password FROM users WHERE user_id = '$user_id'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  $pass = $row['password'];
  if($present_password==$pass){
  $sql="UPDATE users SET password = '$new_password' WHERE user_id = '$user_id'";
  $result = mysqli_query($conn, $sql);
  if($result){
   echo "<script>alert('Password updated successfully.')</script>";
  } else {
   $error_message = "Failed to update password. Please try again.";
  }
 }
 else{
 echo "<script>alert('Please provide correct present password.')</script>";
 }
 } else {
  $error_message = "Please provide your present password to update.";
 }
}


$query = "SELECT * FROM users WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);
if($result && mysqli_num_rows($result) > 0){
   $row = mysqli_fetch_assoc($result);
   $name = $row['name'];
   $email = $row['email'];
   $pincode = $row['pincode'];
   $password = $row['password'];

}
else {
 $error_message = "User data not found.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>User Profile</title>
 <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
 <?php
 include_once ("includes/sidebar.php");
 ?>
 <div class="grid grid-cols-12">
  <div class="col-span-3"></div>
  <!-- write the content here -->
  <div class="col-span-9 pt-2 pr-10">
   <h2 class="text-2xl font-bold mb-4">User Profile</h2>
   <?php if (isset($error_message)) { ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
     <strong class="font-bold">Error:</strong>
     <span class="block sm:inline"><?php echo $error_message; ?></span>
    </div>
   <?php } ?>
   <form action="" method="post" class="space-y-4">
    <div>
     <label for="name" class="block font-medium text-gray-700">Name:</label>
     <input type="text" id="name" name="name" value="<?php echo $name; ?>"
      class="mt-1 p-2 border border-gray-300 rounded-md w-full bg-zinc-200" readonly>
    </div>
    <div>
     <label for="email" class="block font-medium text-gray-700">Email:</label>
     <input type="email" id="email" name="email" value="<?php echo $email; ?>"
      class="mt-1 p-2 border border-gray-300 rounded-md w-full  bg-zinc-200" readonly>
    </div>
    <div>
     <label for="password" class="block font-medium text-gray-700">New Password:</label>
     <input type="password" id="new_password" name="new_password"
      class="mt-1 p-2 border border-gray-300 rounded-md w-full">
    </div>
    <div>
     <label for="present_password" class="block font-medium text-gray-700">Present Password:</label>
     <input type="password" id="present_password" name="present_password"
      class="mt-1 p-2 border border-gray-300 rounded-md w-full">
    </div>
    <div>
     <label for="pincode" class="block font-medium text-gray-700">Pincode:</label>
     <input type="text" id="pincode" name="pincode" value="<?php echo $pincode; ?>"
      class="mt-1 p-2 border border-gray-300 rounded-md w-full  bg-zinc-200" readonly>
    </div>
    <div>
     <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Update Profile</button>
    </div>
   </form>
  </div>
 </div>
</body>

</html>