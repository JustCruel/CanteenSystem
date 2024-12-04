<?php  
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

// Get today's date
$today = date('Y-m-d');

// Define time period variables
$weekStart = date('Y-m-d', strtotime('monday this week'));
$weekEnd = date('Y-m-d', strtotime('sunday this week'));
$monthStart = date('Y-m-01');
$monthEnd = date('Y-m-t');
$yearStart = date('Y-01-01');
$yearEnd = date('Y-12-31');

// Initialize variables
$sales = [];
$search = isset($_GET['search']) ? $_GET['search'] : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

// Base query for total revenue
$baseQuery = "SELECT SUM(selling_price * quantity_sold) AS total_revenue, sale_date
              FROM sales 
              JOIN products ON sales.product_id = products.id";

// Where clause
$whereClause = "";

// Apply search filter
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $whereClause .= " WHERE (products.name LIKE '%$search%' OR sales.transaction_number LIKE '%$search%')";
}

// Apply filter based on selected time period
if (!empty($filter)) {
    $filterClause = "";
    switch ($filter) {
        case 'today':
            $filterClause = "DATE(sale_date) = '$today'";
            break;
        case 'week':
            $filterClause = "sale_date BETWEEN '$weekStart' AND '$weekEnd'";
            break;
        case 'month':
            $filterClause = "sale_date BETWEEN '$monthStart' AND '$monthEnd'";
            break;
        case 'year':
            $filterClause = "sale_date BETWEEN '$yearStart' AND '$yearEnd'";
            break;
    }
    $whereClause .= empty($whereClause) ? " WHERE $filterClause" : " AND $filterClause";
}

// Combine the base query with where clause
$revenueQuery = $baseQuery . $whereClause . " GROUP BY sale_date";

// Execute the query
$revenueResult = $conn->query($revenueQuery);
$revenue = 0;
$revenueData = [];
if ($revenueResult) {
    while ($row = $revenueResult->fetch_assoc()) {
        $revenueData[] = ['date' => $row['sale_date'], 'revenue' => $row['total_revenue']];
    }
}

// Format the revenue for today or selected period
$revenueFormatted = number_format($revenue, 2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revenue Report</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        .main-content {
            padding: 20px;
        }
        h2 {
            margin-bottom: 20px;
        }
        .alert {
            border-radius: 8px;
            margin: 20px 0;
        }
        .no-sales {
            text-align: center;
            padding: 20px;
            font-size: 1.2rem;
            color: #999;
        }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>
    <div class="main-content">
        <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
        <h2>Revenue Report</h2>

        <!-- Filter form -->
        <form id="filterForm" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group">
                        <select class="form-control" name="filter" onchange="this.form.submit()">
                            <option value="">Select Time Period</option>
                            <option value="today" <?php echo $filter == 'today' ? 'selected' : ''; ?>>Today</option>
                            <option value="week" <?php echo $filter == 'week' ? 'selected' : ''; ?>>This Week</option>
                            <option value="month" <?php echo $filter == 'month' ? 'selected' : ''; ?>>This Month</option>
                            <option value="year" <?php echo $filter == 'year' ? 'selected' : ''; ?>>This Year</option>
                        </select>
                    </div>
                </div>
            </div>
        </form>

        <!-- Display total revenue -->
        <div class="alert alert-info">
            <h4>Total Revenue:</h4>
            <p>₱<?php echo $revenueFormatted; ?></p>
        </div>

        <!-- ApexCharts for Revenue Graph -->
        <div id="revenue-chart"></div>

        <script>
            // Prepare data for ApexCharts
            var revenueData = <?php echo json_encode($revenueData); ?>;

            var dates = revenueData.map(function(item) { return item.date; });
            var revenues = revenueData.map(function(item) { return item.revenue; });

            var options = {
                chart: {
                    type: 'line',
                    height: 350
                },
                series: [{
                    name: 'Revenue',
                    data: revenues
                }],
                xaxis: {
                    categories: dates,
                    title: {
                        text: 'Date'
                    }
                },
                yaxis: {
                    title: {
                        text: 'Revenue (₱)'
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#revenue-chart"), options);
            chart.render();
        </script>

    </div>
</body>
</html>
