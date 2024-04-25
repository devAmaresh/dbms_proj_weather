<?php
include("dbconnection.php");
session_start();
include("includes/authenticate.php");

// Check if the "Mark Resolved" button is clicked
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Delete the feedback entry from the database
    $deleteQuery = "DELETE FROM feedback WHERE id = $id";
    if (mysqli_query($conn, $deleteQuery)) {
       echo "<script>alert('Feedback entry marked as resolved.')</script>";
    } else {
        // Failed to delete the feedback entry
        echo "<script>alert('Failed to delete')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../src/output.css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <?php
    include_once("includes/sidebar.php");
    ?>
    <div class="grid grid-cols-12">
        <div class="col-span-3"></div>
        <!-- write the content here -->
        <div class="col-span-9 pt-2">
            <div class="text-center font-bold text-3xl text-zinc-700 p-10">
                Feedback Form Data
            </div>
            <div class="text-center ml-20">
                <?php
                // Fetch all feedback data from the database
                $query = "SELECT * FROM feedback";
                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    // Display feedback entries in a table
                    echo '<table class="table-auto border-2 border-black">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th class="px-4 py-2">Email</th>';
                    echo '<th class="px-4 py-2">Subject</th>';
                    echo '<th class="px-4 py-2">Message</th>';
                    echo '<th class="px-4 py-2">Action</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr class="hover:bg-zinc-200">';
                        echo '<td class="border px-4 py-2">' . $row['email'] . '</td>';
                        echo '<td class="border px-4 py-2">' . $row['subject'] . '</td>';
                        echo '<td class="border px-4 py-2">' . $row['message'] . '</td>';
                        echo '<td class="border px-4 py-2"><a href="inbox.php?id=' . $row['id'] . '" class="bg-blue-700 p-1 rounded-md text-white">Mark Resolved</a></td>';
                        echo '</tr>';
                    }

                    echo '</tbody>';
                    echo '</table>';
                } else {
                    echo '<p class="text-center text-3xl p-10">No feedback entries found.</p>';
                }

                // Close the database connection
                mysqli_close($conn);
                ?>
            </div>
        </div>
    </div>
</body>

</html>
