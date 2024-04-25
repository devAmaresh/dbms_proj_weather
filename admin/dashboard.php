<?php
include ("dbconnection.php");
session_start();
include ("includes/authenticate.php");

$query_high_severity = "SELECT COUNT(*) AS num_high_severity FROM weatherevents WHERE severity_level > 5";
$result_high_severity = mysqli_query($conn, $query_high_severity);

$row_high_severity = mysqli_fetch_assoc($result_high_severity);
$num_high_severity = $row_high_severity['num_high_severity'];

$query_unique_event_types = "SELECT COUNT(DISTINCT event_type) AS num_event_types FROM weatherevents";
$result_unique_event_types = mysqli_query($conn, $query_unique_event_types);
$row_unique_event_types = mysqli_fetch_assoc($result_unique_event_types);
$num_event_types = $row_unique_event_types['num_event_types'];

$query_pending_feedback = "SELECT COUNT(*) AS num_pending_feedback FROM feedback";
$result_pending_feedback = mysqli_query($conn, $query_pending_feedback);
$row_pending_feedback = mysqli_fetch_assoc($result_pending_feedback);
$num_pending_feedback = $row_pending_feedback['num_pending_feedback'];
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
 include_once ("includes/sidebar.php");
 ?>
 <div class="grid grid-cols-12">
  <div class="col-span-3"></div>
  <!-- write the content here -->
  <div class="col-span-9 pt-2">
   <div class="text-center font-bold text-3xl text-teal-400">Welcome <?php echo $_SESSION['admin_name'] ?></div>
   <div class="mt-8">
    <div class="grid grid-cols-2 gap-4">
     <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
      <h3 class="text-xl font-semibold text-gray-800 mb-4">Places with High Severity</h3>
      <p class="text-gray-600 mb-2">Number of Places: <?php echo $num_high_severity; ?></p>
      <!-- Bar Chart for Places with High Severity -->
      <canvas id="highSeverityChart" width="200" height="150"></canvas>
     </div>
     <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
      <h3 class="text-xl font-semibold text-gray-800 mb-4">Pending Feedback to Resolve</h3>
      <p class="text-gray-600 mb-2">Number of Feedback: <?php echo $num_pending_feedback; ?></p>
      <!-- Bar Chart for Pending Feedback -->
      <canvas id="pendingFeedbackChart" width="200" height="150"></canvas>
     </div>
     <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
      <h3 class="text-xl font-semibold text-gray-800 mb-4">Unique Event Types</h3>
      <!-- Bar Chart for Unique Event Types -->
      <canvas id="uniqueEventTypesChart" width="200" height="150"></canvas>
      <p class="text-gray-600 mt-2 mb-2">Number of Places Affected:</p>
      <ul class="list-disc ml-8">
       <?php
       // Query to get the event types and the number of places affected for each
       $query_event_places = "SELECT event_type, COUNT(*) AS num_places FROM weatherevents GROUP BY event_type";
       $result_event_places = mysqli_query($conn, $query_event_places);
       while ($row_event_places = mysqli_fetch_assoc($result_event_places)) {
        echo "<li>{$row_event_places['event_type']}: {$row_event_places['num_places']}</li>";
       }
       ?>
      </ul>
     </div>
    </div>
   </div>
  </div>
 </div>

 <!-- JavaScript for Chart.js -->
 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 <script>
 // Get the canvas elements
var highSeverityCtx = document.getElementById('highSeverityChart').getContext('2d');
var pendingFeedbackCtx = document.getElementById('pendingFeedbackChart').getContext('2d');
var uniqueEventTypesCtx = document.getElementById('uniqueEventTypesChart').getContext('2d');

// Function to dynamically calculate the scale for y-axis
function calculateScale(data) {
    var maxData = Math.max.apply(Math, data);
    if (maxData <= 10) {
        return 10;
    } else {
        return Math.ceil(maxData / 10) * 10;
    }
}

// Create the charts
var highSeverityChart = new Chart(highSeverityCtx, {
    type: 'bar',
    data: {
        labels: ['High Severity'],
        datasets: [{
            label: 'Number of Places',
            data: [<?php echo $num_high_severity; ?>],
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    stepSize: calculateScale([<?php echo $num_high_severity; ?>])
                }
            }]
        }
    }
});

var pendingFeedbackChart = new Chart(pendingFeedbackCtx, {
    type: 'bar',
    data: {
        labels: ['Pending Feedback'],
        datasets: [{
            label: 'Number of Feedback',
            data: [<?php echo $num_pending_feedback; ?>],
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    stepSize: calculateScale([<?php echo $num_pending_feedback; ?>])
                }
            }]
        }
    }
});

var uniqueEventTypesChart = new Chart(uniqueEventTypesCtx, {
    type: 'bar',
    data: {
        labels: ['Unique Event Types'],
        datasets: [{
            label: 'Number of Types',
            data: [<?php echo $num_event_types; ?>],
            backgroundColor: 'rgba(255, 206, 86, 0.2)',
            borderColor: 'rgba(255, 206, 86, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    stepSize: calculateScale([<?php echo $num_event_types; ?>])
                }
            }]
        }
    }
});

 </script>
</body>

</html>