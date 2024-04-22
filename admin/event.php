<?php
include ("dbconnection.php");
session_start();
include ("includes/authenticate.php");

// Fetch locations from the database
$query = "SELECT * FROM Locations";
$result = mysqli_query($conn, $query);

if (isset($_POST['set_alert'])) {
    $event_type = $_POST['event_type'];
    $location_id = $_POST['location_id'];
    $severity_level = $_POST['severity_level'];
    $alert_message = $_POST['alert_message'];
    $event_timestamp = $_POST['event_timestamp'];

    // Insert alert into database
    $query = "INSERT INTO weatherevents (location_id, event_type, event_timestamp, severity_level, description) VALUES ('$location_id', '$event_type', '$event_timestamp', '$severity_level', '$alert_message')";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Alert set successfully');</script>";
    } else {
        echo "<script>alert('Error setting alert');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Event Alert</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../src/output.css" />
    <script>
        function validateForm() {
            var eventType = document.getElementById('event_type').value;
            var locationId = document.getElementById('location_id').value;
            var severityLevel = document.getElementById('severity_level').value;
            var alertMessage = document.getElementById('alert_message').value;
            var eventTimestamp = document.getElementById('event_timestamp').value;

            if (eventType === "" || locationId === "" || severityLevel === "" || alertMessage === "" || eventTimestamp === "") {
                alert("Please fill all the fields.");
                return false;
            }
            return true;
    </script>
</head>

<body>
    <?php include_once ("includes/sidebar.php"); ?>
    <div class="grid grid-cols-12">
        <div class="col-span-3"></div>
        <div class="col-span-9 pt-2 w-[80%]">
            <h1 class="text-2xl font-semibold">Set Event Alert</h1>
            <form action="#" method="POST" class="mt-4">
                <div class="mt-4">
                    <label for="event_type" class="block text-sm font-medium leading-6 text-gray-900">Event Type</label>
                    <select id="event_type" name="event_type" required
                        class="p-1.5 block w-full border-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="storm">Storm</option>
                        <option value="earthquake">Earthquake</option>
                        <!-- Add more event types as needed -->
                    </select>
                </div>
                <div class="mt-4">
                    <label for="location_id" class="block text-sm font-medium leading-6 text-gray-900">Location</label>
                    <select id="location_id" name="location_id" required
                        class="p-1.5 block w-full border-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <option value="<?php echo $row['location_id']; ?>"><?php echo $row['location_name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <!-- 
                <div class="mt-4">
                    <label for="severity_level" class="block text-sm font-medium leading-6 text-gray-900">Severity Level</label>
                    <select id="severity_level" name="severity_level" required class="p-1.5 block w-full border-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                        -->
                <div class="mt-4">
                    <label for="severity_level" class="block text-sm font-medium text-gray-700">Severity Level</label>
                    <input type="range" id="severity_level" name="severity_level" min="0" max="10" step="1" value="5"
                        class="slider w-full">
                </div>
                <div class="mt-4">
                    <label for="alert_message" class="block text-sm font-medium leading-6 text-gray-900">Alert
                        Message</label>
                    <textarea id="alert_message" name="alert_message" rows="4" required
                        class="p-1.5 border-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                </div>
                <div class="mt-4">
                    <label for="event_timestamp" class="block text-sm font-medium leading-6 text-gray-900">Event
                        Timestamp</label>
                    <input type="datetime-local" id="event_timestamp" name="event_timestamp" required
                        class="p-1.5 block w-full border-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <button type="submit" name="set_alert" class="mt-4 bg-indigo-600 text-white py-2 px-4 rounded">Set
                    Alert</button>
            </form>
        </div>
    </div>
</body>

</html>