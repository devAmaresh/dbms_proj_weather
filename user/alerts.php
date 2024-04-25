<?php
include ("dbconnection.php");
session_start();
include ("includes/authenticate.php");

// Update database if mark as read button is pressed
if (isset($_POST['mark_read']) && isset($_POST['notification_id'])) {
 $notification_id = $_POST['notification_id'];
 // Update the database to mark the notification as read
 $query = "UPDATE notifications SET is_read = 1 WHERE notification_id = $notification_id";
 if (mysqli_query($conn, $query)) {
  echo "<script>alert('Notification marked as read.')</script>";
 } else {
  echo "<script>alert('Failed to mark notification as read.')</script>";
 }
}

// Retrieve alerts corresponding to the logged-in user from the database and join with locations table
$user_id = $_SESSION['user_id'];
$query = "SELECT w.*, l.location_name, n.*
          FROM weatherevents AS w 
          JOIN locations AS l ON w.location_id = l.location_id 
          JOIN notifications AS n ON w.event_id = n.event_id
          WHERE n.user_id = $user_id AND n.is_read = 0";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Alert Page</title>
 <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
 <?php
 include_once ("includes/sidebar.php");
 ?>
 <div class="grid grid-cols-12">
  <div class="col-span-3"></div>
  <div class="col-span-9 pt-2 pr-10">
   <h2 class="text-center text-2xl font-bold p-5 pl-0">Alerts</h2>
   <table class="border-collapse border-2 border-gray-400  ">
    <thead>
     <tr>
      <th class="border border-gray-400 px-4 py-2">SL No</th>
      <th class="border border-gray-400 px-4 py-2">Location</th>
      <th class="border border-gray-400 px-4 py-2">Event Type</th>
      <th class="border border-gray-400 px-4 py-2">Timestamp</th>
      <th class="border border-gray-400 px-4 py-2">Severity Level</th>
      <th class="border border-gray-400 px-4 py-2">Description</th>
      <th class="border border-gray-400 px-4 py-2">Actions</th>
     </tr>
    </thead>
    <tbody>
     <?php
     $sl_no = 1;
     if (mysqli_num_rows($result) == 0) {
      echo "<tr><td colspan='7' class='text-center'>No alerts found.</td></tr>";
     }
     while ($row = mysqli_fetch_assoc($result)): ?>
      <tr class="hover:bg-zinc-200">
       <td class=" border border-gray-400 px-4 py-2"><?php echo $sl_no++; ?></td>
       <td class=" border border-gray-400 px-4 py-2"><?php echo $row['location_name']; ?></td>
       <td class=" border border-gray-400 px-4 py-2"><?php echo $row['event_type']; ?></td>
       <td class=" border border-gray-400 px-4 py-2"><?php echo $row['event_timestamp']; ?></td>
       <td class=" border border-gray-400 px-4 py-2 <?php echo (($row['severity_level']) > 5) ? 'text-red-500 font-semibold' : ''; ?>"><?php echo $row['severity_level']; ?></td>
       <td class="border border-gray-400 px-4 py-2"><?php echo $row['description']; ?></td>


       <td class=" border border-gray-400 px-4 py-2">
        <?php if (!$row['is_read']): ?>
         <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
          <input type="hidden" name="notification_id" value="<?php echo $row['notification_id']; ?>">
          <button type="submit" name="mark_read"
           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Mark as Read</button>
         </form>
        <?php endif; ?>
       </td>
      </tr>
     <?php endwhile; ?>
    </tbody>
   </table>
  </div>
 </div>
</body>

</html>