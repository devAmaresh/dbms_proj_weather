<?php
include ("dbconnection.php");
session_start();
include ("includes/authenticate.php");

// Function to calculate pagination offset
function getPaginationOffset($page, $limit)
{
    return ($page - 1) * $limit;
}

// Function to fetch events based on event timestamp
function fetchEvents($conn, $limit, $offset, $whereClause)
{
    $query = "SELECT we.*, loc.location_name, loc.pincode FROM weatherevents we
              INNER JOIN locations loc ON we.location_id = loc.location_id
              $whereClause
              ORDER BY event_timestamp ASC
              LIMIT $limit OFFSET $offset";
    return mysqli_query($conn, $query);
}

// Set the limit for pagination
$limit = 5;

// Get the current page number for upcoming events
$pageUpcoming = isset($_GET['page_upcoming']) ? $_GET['page_upcoming'] : 1;

// Calculate the offset for upcoming events
$offsetUpcoming = getPaginationOffset($pageUpcoming, $limit);

// Fetch upcoming events
$upcomingEvents = fetchEvents($conn, $limit, $offsetUpcoming, "WHERE event_timestamp > NOW()");

// Get the current page number for finished events
$pageFinished = isset($_GET['page_finished']) ? $_GET['page_finished'] : 1;

// Calculate the offset for finished events
$offsetFinished = getPaginationOffset($pageFinished, $limit);

// Fetch finished events
$finishedEvents = fetchEvents($conn, $limit, $offsetFinished, "WHERE event_timestamp <= NOW()");
// Process delete event
if (isset($_POST['delete_event'])) {
    $eventId = $_POST['event_id'];
    $deleteQuery = "DELETE FROM weatherevents WHERE event_id = $eventId";
    $result = mysqli_query($conn, $deleteQuery);
    if ($result) {
        // Event deleted successfully
        echo "<script>alert('Event deleted successfully.');</script>";
        // Redirect or refresh the page to reflect changes
        echo "<script>window.location.href = 'eventls.php';</script>";
    } else {
        // Error occurred while deleting event
        echo "<script>alert('Error deleting event: " . mysqli_error($conn) . "');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../src/output.css" />
    <style>
        .pagination a {
            display: inline-block;
            padding: 8px 16px;
            margin: 0 4px;
            background-color: #f2f2f2;
            color: #333;
            border-radius: 4px;
            text-decoration: none;
        }

        .pagination a:hover {
            background-color: #ddd;
        }

        .pagination .active {
            background-color: #333;
            color: white;
        }
    </style>
</head>

<body>
    <?php include_once ("includes/sidebar.php"); ?>
    <div class="grid grid-cols-12 pb-4">
        <div class="col-span-3"></div>
        <div class="col-span-9 pt-2 w-[80%]">
            <div class="text-center font-bold text-xl p-4">Upcoming Events</div>
            <?php
            if (mysqli_num_rows($upcomingEvents) > 0) {
                echo "<table class='border-collapse border-2 border-black'>";
                echo "<thead><tr><th class='border border-zinc-400 px-4 py-2'>S.No.</th><th class='border border-zinc-400 px-4 py-2'>Event Type</th><th class='border border-zinc-400 px-4 py-2'>Address</th><th class='border border-zinc-400 px-4 py-2'>Pincode</th><th class='border border-zinc-400 px-4 py-2'>Severity Level</th><th class='border border-zinc-400 px-4 py-2'>Event Timestamp</th><th class='border border-zinc-400 px-4 py-2'>Action</th></tr></thead>";
                echo "<tbody>";
                $serial = $offsetUpcoming + 1;
                while ($row = mysqli_fetch_assoc($upcomingEvents)) {
                    echo "<tr>";
                    echo "<td class='border border-zinc-400 px-4 py-2'>$serial</td>";
                    echo "<td class='border border-zinc-400 px-4 py-2'>" . $row['event_type'] . "</td>";
                    echo "<td class='border border-zinc-400 px-4 py-2'>" . $row['location_name'] . "</td>";
                    echo "<td class='border border-zinc-400 px-4 py-2'>" . $row['pincode'] . "</td>";
                    echo "<td class='border border-zinc-400 px-4 py-2'>" . $row['severity_level'] . "</td>";
                    echo "<td class='border border-zinc-400 px-4 py-2'>" . $row['event_timestamp'] . "</td>";
                    echo "<td class='border border-zinc-400 px-4 py-2'><form method='post'><input type='hidden' name='event_id' value='" . $row['event_id'] . "'><button type='submit' name='delete_event' class='bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded'>Delete</button></form></td>";
                    echo "</tr>";
                    $serial++;
                }
                echo "</tbody>";
                echo "</table>";

                // Pagination for upcoming events
                $totalUpcomingEvents = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM weatherevents WHERE event_timestamp > NOW()"));
                $totalPagesUpcoming = ceil($totalUpcomingEvents / $limit);
                echo "<div class='mt-4 pagination'>";
                if ($pageUpcoming > 1) {
                    echo "<a href='?page_upcoming=" . ($pageUpcoming - 1) . "'>&laquo; Prev</a>";
                }
                for ($i = 1; $i <= $totalPagesUpcoming; $i++) {
                    $active = ($i == $pageUpcoming) ? "active" : "";
                    echo "<a href='?page_upcoming=$i' class='$active'>$i</a>";
                }
                if ($pageUpcoming < $totalPagesUpcoming) {
                    echo "<a href='?page_upcoming=" . ($pageUpcoming + 1) . "'>Next &raquo;</a>";
                }
                echo "</div>";
            } else {
                echo "<p>No upcoming events.</p>";
            }
            ?>

            <div class="text-center font-bold text-xl p-4">Finished Events</div>
            <?php
            if (mysqli_num_rows($finishedEvents) > 0) {
                echo "<table class='border-collapse border-2 border-black'>";
                echo "<thead><tr><th class='border border-zinc-400 px-4 py-2'>S.No.</th><th class='border border-zinc-400 px-4 py-2'>Event Type</th><th class='border border-zinc-400 px-4 py-2'>Address</th><th class='border border-zinc-400 px-4 py-2'>Pincode</th><th class='border border-zinc-400 px-4 py-2'>Severity Level</th><th class='border border-zinc-400 px-4 py-2'>Event Timestamp</th><th class='border border-zinc-400 px-4 py-2'>Action</th></tr></thead>";
                echo "<tbody>";
                $serial = $offsetFinished + 1;
                while ($row = mysqli_fetch_assoc($finishedEvents)) {
                    echo "<tr>";
                    echo "<td class='border border-zinc-400 px-4 py-2'>$serial</td>";
                    echo "<td class='border border-zinc-400 px-4 py-2'>" . $row['event_type'] . "</td>";
                    echo "<td class='border border-zinc-400 px-4 py-2'>" . $row['location_name'] . "</td>";
                    echo "<td class='border border-zinc-400 px-4 py-2'>" . $row['pincode'] . "</td>";
                    echo "<td class='border border-zinc-400 px-4 py-2'>" . $row['severity_level'] . "</td>";
                    echo "<td class='border border-zinc-400 px-4 py-2'>" . $row['event_timestamp'] . "</td>";
                    echo "<td class='border border-zinc-400 px-4 py-2'><form method='post'><input type='hidden' name='event_id' value='" . $row['event_id'] . "'><button type='submit' name='delete_event' class='bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded'>Delete</button></form></td>";
                    echo "</tr>";
                    $serial++;
                }
                echo "</tbody>";
                echo "</table>";

                // Pagination for finished events
                $totalFinishedEvents = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM weatherevents WHERE event_timestamp <= NOW()"));
                $totalPagesFinished = ceil($totalFinishedEvents / $limit);
                echo "<div class='mt-4 pagination'>";
                if ($pageFinished > 1) {
                    echo "<a href='?page_finished=" . ($pageFinished - 1) . "'>&laquo; Prev</a>";
                }
                for ($i = 1; $i <= $totalPagesFinished; $i++) {
                    $active = ($i == $pageFinished) ? "active" : "";
                    echo "<a href='?page_finished=$i' class='$active'>$i</a>";
                }
                if ($pageFinished < $totalPagesFinished) {
                    echo "<a href='?page_finished=" . ($pageFinished + 1) . "'>Next &raquo;</a>";
                }
                echo "</div>";
            } else {
                echo "<p>No finished events.</p>";
            }
            ?>
        </div>
    </div>
</body>

</html>