<?php
include ("dbconnection.php");
session_start();
include ("includes/authenticate.php");

if (isset($_POST['add_location'])) {
    if (($_POST['location_name']) == "" && ($_POST['latitude']) == "" && isset($_POST['longitude'])) {
        echo "<script>alert('Please select the location  ');</script>";
    } else {
        $location_name = $_POST['location_name'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        $pincode = $_POST['pincode'];
        // Insert location into database
        $query = "INSERT INTO Locations (location_name, latitude, longitude , pincode) VALUES ('$location_name', '$latitude', '$longitude' ,'$pincode')";
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Location added successfully');</script>";
        } else {
            echo "<script>alert('Error adding location');</script>";
        }
    }
}
if (isset($_POST['delete_location'])) {
    $location_id = $_POST['location_id'];
    $query = "DELETE FROM Locations WHERE location_id = '$location_id'";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Location deleted successfully');</script>";
    } else {
        echo "<script>alert('Error deleting location');</script>";
    }
}

// Fetch all locations from the database
$query = "SELECT * FROM Locations";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Location</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../src/output.css" />
    <!-- Include Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <!-- Include Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>

<body>
    <?php include_once ("includes/sidebar.php"); ?>
    <div class="grid grid-cols-12">
        <div class="col-span-3"></div>
        <div class="col-span-9 pt-2">
            <h1 class="text-2xl font-semibold mb-4">Add Location</h1>
            <div id="loading-indicator1" class="w-[80%] text-center align-middle hidden">
                <div class="inline-block animate-spin rounded-full h-10 w-10 border-t-2 border-b-2 border-gray-900">
                </div>
                <div class="text-center"> Loading...</div>

            </div>
            <div id="map" class="w-[80%] h-64">
                <div id="loading-indicator"
                    style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); display:none;">
                    Loading...
                </div>
            </div>
            <form action="#" method="POST" class="mt-4">
                <input type="hidden" id="latitude" name="latitude">
                <input type="hidden" id="longitude" name="longitude">
                <label for="pincode" class="block text-sm font-medium leading-6 text-gray-900">Pincode</label>
                <input type="text" id="pincode" name="pincode"
                    class="block w-[40%] p-1.5 border-2  rounded-md border-zinc-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <div class="mt-4">
                    <label for="location_name" class="block text-sm font-medium leading-6 text-gray-900">Location
                        Name</label>
                    <input type="text" id="location_name" name="location_name" required readonly
                        class="block w-[80%] p-1.5 border-2  rounded-md border-zinc-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <button type="submit" name="add_location" class="mt-4 bg-indigo-600 text-white py-2 px-4 rounded">Add
                    New Location</button>
            </form>

            <div class="mt-5 w-[80%]">
                <div class="text-xl font-semibold text-zinc-700 p-4 pl-0">Locations</div>
                <!-- Display locations in a table -->
                <div class="border-2 border-black rounded-lg p-1 mb-4">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-zinc-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">
                                    Location Name
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">
                                    Pincode
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">
                                    Latitude
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">
                                    Longitude
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Delete</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            // Display all locations
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<tr>';
                                echo '<td class="px-1 py-4"><input class="p-0.5 w-full rounded-md border-[1px] border-black" value="' . htmlspecialchars($row['location_name']) . '" readonly/></td>';
                                echo '<td class="px-6 py-4 whitespace-nowrap">' . $row['pincode'] . '</td>';
                                echo '<td class="px-6 py-4 whitespace-nowrap">' . $row['latitude'] . '</td>';
                                echo '<td class="px-6 py-4 whitespace-nowrap">' . $row['longitude'] . '</td>';
                                echo '<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">';
                                echo '<form action="#" method="POST">';
                                echo '<input type="hidden" name="location_id" value="' . $row['location_id'] . '">';
                                echo '<button type="submit" name="delete_location" class="text-red-600 hover:text-red-900">Delete</button>';
                                echo '</form>';
                                echo '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div></div>
            </div>
        </div>
    </div>

    <!-- Include location.js script -->
    <script src="helper/location.js"></script>
</body>

</html>