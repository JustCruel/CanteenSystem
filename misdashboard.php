<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include "db.php";
include "sidebarmis.php";



// Fetch counts for activation statuses
$query = "SELECT is_activated, COUNT(*) AS total FROM user GROUP BY is_activated";
$result = mysqli_query($conn, $query);

$statusCounts = [0 => 0, 1 => 0, 2 => 0]; // Initialize default counts
while ($row = mysqli_fetch_assoc($result)) {
    $statusCounts[(int)$row['is_activated']] = (int)$row['total'];
}

// Assign counts for chart data
$notActivatedCount = $statusCounts[0];
$activatedCount = $statusCounts[1];
$disabledCount = $statusCounts[2];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIS Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Custom hover effect on cards */
        .card {
            transition: transform 0.3s ease-in-out;
        }
        .card:hover {
            transform: scale(1.05);
        }
       /* General styling for the header */
       header.bg-primary {
    background-color: #023B87 !important; /* Force primary blue */
    color: #fff !important; /* Force white text */
    padding: 1rem;
    border-radius: 0.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

header h1 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: bold;
}

header .dropdown a {
    color: #fff !important;
    text-decoration: none;
    font-size: 1rem;
    transition: color 0.3s ease;
}

header .dropdown a:hover {
    color: #d1e7ff;
}


/* Tooltip customization */
[data-toggle="tooltip"] {
    cursor: pointer; /* Indicate interactivity */
}

/* Responsive design adjustments */
@media (max-width: 768px) {
    header.bg-primary {
        flex-direction: column; /* Stack elements */
        text-align: center; /* Center-align text */
    }

    header .dropdown {
        margin-top: 0.5rem; /* Add spacing for dropdown */
    }
}

    </style>
    <!-- Include ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>
<body class="bg-light">

<div class="main-content">
    <header class="bg-primary text-white p-4 rounded d-flex justify-content-between align-items-center">
        <h1 class="m-0">MIS DASHBOARD</h1>
        <div class="dropdown">
            <a href="#" class="text-white" id="profileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" data-placement="top" title="Profile Settings">
              
    </header>

    <main class="mt-4">
        <div class="row">
            <!-- Analytical Chart for RFID Card Status -->
            <div class="col-md-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h2 class="card-title text-primary"><i class="fas fa-chart-pie"></i> RFID Card Status</h2>
                        <div id="rfidCardStatusChart"></div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    // Analytical Chart for Activation Status
    var activationStatusOptions = {
        chart: {
            type: 'pie',
            height: 350
        },
        series: [<?= $notActivatedCount ?>, <?= $activatedCount ?>, <?= $disabledCount ?>],
        labels: [
            'Not Activated (<?= $notActivatedCount ?>)', 
            'Activated (<?= $activatedCount ?>)', 
            'Disabled (<?= $disabledCount ?>)'
        ],
        colors: ['#ffc107', '#28a745', '#dc3545'], // Yellow for Not Activated, Green for Activated, Red for Disabled
        dataLabels: {
            enabled: true,
            formatter: function (val, opts) {
                return opts.w.config.series[opts.seriesIndex] + " users"; // Show counts with 'users'
            },
            style: {
                fontSize: '14px',
                fontWeight: 'bold',
                colors: ['#ffffff']
            }
        },
        legend: {
            position: 'bottom'
        }
    };

    var activationStatusChart = new ApexCharts(document.querySelector("#rfidCardStatusChart"), activationStatusOptions);
    activationStatusChart.render();
</script>

<!-- jQuery and Bootstrap scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Enable tooltips
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
</body>
</html>
