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

// Base query for total return amount
$returnBaseQuery = "SELECT SUM(products.selling_price * return_sale.quantity) AS total_return, return_date
                    FROM return_sale
                    JOIN products ON return_sale.product_id = products.id";

// Where clause for returns
$returnWhereClause = "";

// Apply filter based on selected time period for returns
if (!empty($filter)) {
    $filterClause = "";
    switch ($filter) {
        case 'today':
            $filterClause = "DATE(return_date) = '$today'";
            break;
        case 'week':
            $filterClause = "return_date BETWEEN '$weekStart' AND '$weekEnd'";
            break;
        case 'month':
            $filterClause = "return_date BETWEEN '$monthStart' AND '$monthEnd'";
            break;
        case 'year':
            $filterClause = "return_date BETWEEN '$yearStart' AND '$yearEnd'";
            break;
    }
    $returnWhereClause .= empty($returnWhereClause) ? " WHERE $filterClause" : " AND $filterClause";
}

// Combine the base query with where clause for returns
$returnQuery = $returnBaseQuery . $returnWhereClause . " GROUP BY return_date";

// Execute the query for returns
$returnResult = $conn->query($returnQuery);

// Initialize return amount to 0
$totalReturn = 0;
$returnData = []; // Initialize return data array

if ($returnResult) {
    while ($row = $returnResult->fetch_assoc()) {
        $returnData[] = ['date' => $row['return_date'], 'return' => $row['total_return']];
        // Add return to the total
        $totalReturn += $row['total_return'];
    }
}

// Format the total return amount
$totalReturnFormatted = number_format($totalReturn, 2);


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

// Initialize revenue to 0
$revenue = 0;
$revenueData = []; // Initialize revenue data array

if ($revenueResult) {
    while ($row = $revenueResult->fetch_assoc()) {
        $revenueData[] = ['date' => $row['sale_date'], 'revenue' => $row['total_revenue']];
        // Add revenue to the total
        $revenue += $row['total_revenue'];
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
        .revenue-card {
            padding: 20px;
            border-radius: 10px;
            background-color: #f9f9f9;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .revenue-card h4 {
            font-size: 1.5rem;
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
    <div class="revenue-card">
        <h4>Total Revenue for <?php echo ucfirst($filter ?: 'all time'); ?>:</h4>
        <p>₱<?php echo $revenueFormatted; ?></p>
    </div>
    <!-- Display total return -->
<div class="revenue-card">
    <h4>Total Returns for <?php echo ucfirst($filter ?: 'all time'); ?>:</h4>
    <p>₱<?php echo $totalReturnFormatted; ?></p>
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
            },
            tooltip: {
                y: {
                    formatter: function(value) {
                        return '₱' + value.toFixed(2);
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#revenue-chart"), options);
        chart.render();
    </script>

</div>
</body>
</html>
