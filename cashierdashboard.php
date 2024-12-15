<?php
include "db.php";
include "sidebarcash.php";

// Aggregate totals for loading and deductions
$queryLoad = "SELECT SUM(amount) as total_load, DATE(transaction_date) as transaction_date 
              FROM transactionslnd 
              WHERE transaction_type = 'load' 
              GROUP BY DATE(transaction_date)";
$resultLoad = $conn->query($queryLoad);
$loadData = [];
$loadDates = [];
while ($row = $resultLoad->fetch_assoc()) {
    $loadData[] = $row['total_load'];
    $loadDates[] = $row['transaction_date'];
}

$queryDeduct = "SELECT SUM(amount) as total_deduct, DATE(transaction_date) as transaction_date 
                FROM transactionslnd 
                WHERE transaction_type = 'deduction' 
                GROUP BY DATE(transaction_date)";
$resultDeduct = $conn->query($queryDeduct);
$deductData = [];
while ($row = $resultDeduct->fetch_assoc()) {
    $deductData[] = $row['total_deduct'];
}

// Pass PHP data to JavaScript
$loadDataJson = json_encode($loadData);
$deductDataJson = json_encode($deductData);
$datesJson = json_encode($loadDates);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>
<body class="bg-light">

<div class="container mt-5">
    <!-- Dashboard Header -->


    <!-- Analytics Section -->
    <div class="card mt-4">
        <div class="card-body">
            <h3 class="text-center text-primary">Loading vs Deductions</h3>
            <div id="analyticsChart"></div>
        </div>
    </div>

    <!-- Action Buttons Section -->
    <main class="mt-4">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h2 class="card-title text-primary"><i class="fas fa-money-bill"></i> Loading Balance/Points</h2>
                        <button class="btn btn-primary mt-3" onclick="window.location.href='usersload.php'">Manage Users</button>
                    </div>
                </div>
            </div>
           
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h2 class="card-title text-primary"><i class="fas fa-history"></i> Transaction History</h2>
                        <button class="btn btn-primary mt-3" onclick="window.location.href='transaction_history.php'">View History</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    // Data from PHP
    const loadData = <?= $loadDataJson ?>;
    const deductData = <?= $deductDataJson ?>;
    const dates = <?= $datesJson ?>;

    // ApexCharts Options
    var options = {
        chart: {
            type: 'bar', // Set chart type to bar (column chart)
            height: 350
        },
        series: [
            {
                name: 'Total Loaded',
                data: loadData
            },
            {
                name: 'Total Deducted',
                data: deductData
            }
        ],
        xaxis: {
            categories: dates,
            title: {
                text: 'Dates'
            }
        },
        yaxis: {
            title: {
                text: 'Amount (PHP)'
            }
        },
        title: {
            text: 'Daily Loading and Deduction Trends',
            align: 'center'
        },
        colors: ['#008FFB', '#FF4560'], // Custom colors for better distinction
        dataLabels: {
            enabled: true
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '50%'
            }
        }
    };

    // Render Chart
    var chart = new ApexCharts(document.querySelector("#analyticsChart"), options);
    chart.render();
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
