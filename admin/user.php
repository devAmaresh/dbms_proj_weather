<?php
session_start();
include ("includes/authenticate.php");
include ("dbconnection.php");

// Fetch users who have unread notifications along with the severity level of the event, event timestamp, event type, and location address
$query = "SELECT users.user_id, users.name, users.last_login, notifications.notification_timestamp, weatherevents.severity_level, weatherevents.event_timestamp, weatherevents.event_type, locations.location_name
          FROM users
          INNER JOIN notifications ON users.user_id = notifications.user_id
          INNER JOIN weatherevents ON notifications.event_id = weatherevents.event_id
          INNER JOIN locations ON weatherevents.location_id = locations.location_id
          WHERE notifications.is_read = 0";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>User Management</title>
 <link rel="stylesheet" href="../src/output.css" />
 <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
 <?php include_once ("includes/sidebar.php"); ?>
 <div class="grid grid-cols-12">
  <div class="col-span-3"></div>
  <div class="col-span-9 pt-2 w-[80%]">
   <h1 class="text-2xl font-semibold text-center p-4 text-red-500">Users with Unread Notifications</h1>
   <div class="overflow-x-auto">
    <table class="min-w-full">
     <thead>
      <tr>
       <th
        class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-left text-xs leading-4 font-medium text-white uppercase tracking-wider">
        User ID</th>
       <th
        class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-left text-xs leading-4 font-medium text-white uppercase tracking-wider">
        Name</th>
       <th
        class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-left text-xs leading-4 font-medium text-white uppercase tracking-wider">
        Last Login</th>
       <th
        class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-left text-xs leading-4 font-medium text-white uppercase tracking-wider">
        Notification Timestamp</th>
       <th
        class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-left text-xs leading-4 font-medium text-white uppercase tracking-wider">
        Severity Level</th>
       <th
        class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-left text-xs leading-4 font-medium text-white uppercase tracking-wider">
        Event Timestamp</th>
       <th
        class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-left text-xs leading-4 font-medium text-white uppercase tracking-wider">
        Event Type</th>
       <th
        class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-left text-xs leading-4 font-medium text-white uppercase tracking-wider">
        Location Address</th>
      </tr>
     </thead>
     <tbody class="bg-zinc-200 divide-y divide-gray-200 dark:divide-gray-700">
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
       <tr>
        <td class="px-6 py-4 whitespace-no-wrap"><?php echo $row['user_id']; ?></td>
        <td class="px-6 py-4 whitespace-no-wrap"><?php echo $row['name']; ?></td>
        <td class="px-6 py-4 whitespace-no-wrap"><?php echo $row['last_login']; ?></td>
        <td class="px-6 py-4 whitespace-no-wrap"><?php echo $row['notification_timestamp']; ?></td>
        <td class="px-6 py-4 whitespace-no-wrap"><?php echo $row['severity_level']; ?></td>
        <td class="px-6 py-4 whitespace-no-wrap"><?php echo $row['event_timestamp']; ?></td>
        <td class="px-6 py-4 whitespace-no-wrap"><?php echo $row['event_type']; ?></td>
        <td class="px-6 py-4 whitespace-no-wrap"><?php echo $row['location_name']; ?></td>
       </tr>
      <?php endwhile; ?>
     </tbody>
    </table>
   </div>
  </div>
 </div>
</body>

</html>