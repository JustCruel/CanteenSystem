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

// Pagination variables
$limit = 10; // number of transactions per page
$page = isset($_GET['page']) ? $_GET['page'] : 1; // current page
$offset = ($page - 1) * $limit;

$salesQuery = "SELECT sales.transaction_number, 
                      products.name AS product_name, 
                      sales.quantity_sold, 
                      sales.sale_date, 
                      products.market_price, 
                      products.selling_price,
                      sales.payment_method,
                      sales.username
               FROM sales 
               JOIN products ON sales.product_id = products.id 
               ORDER BY sales.sale_date DESC
               LIMIT 10 OFFSET $offset";


// Check for filter
if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];
    switch ($filter) {
        case 'today':
            $salesQuery = "SELECT sales.transaction_number, 
                                      products.name AS product_name, 
                                      sales.quantity_sold, 
                                      sales.sale_date, 
                                      products.market_price, 
                                      products.selling_price 
                               FROM sales 
                               JOIN products ON sales.product_id = products.id 
                               WHERE DATE(sale_date) = '$today'
                               ORDER BY sales.sale_date DESC
                               LIMIT $limit OFFSET $offset";
            break;
        case 'week':
            $salesQuery = "SELECT sales.transaction_number, 
                                      products.name AS product_name, 
                                      sales.quantity_sold, 
                                      sales.sale_date, 
                                      products.market_price, 
                                      products.selling_price 
                               FROM sales 
                               JOIN products ON sales.product_id = products.id 
                               WHERE sale_date BETWEEN '$weekStart' AND '$weekEnd'
                               ORDER BY sales.sale_date DESC
                               LIMIT $limit OFFSET $offset";
            break;
        case 'month':
            $salesQuery = "SELECT sales.transaction_number, 
                                      products.name AS product_name, 
                                      sales.quantity_sold, 
                                      sales.sale_date, 
                                      products.market_price, 
                                      products.selling_price 
                               FROM sales 
                               JOIN products ON sales.product_id = products.id 
                               WHERE sale_date BETWEEN '$monthStart' AND '$monthEnd'
                               ORDER BY sales.sale_date DESC
                               LIMIT $limit OFFSET $offset";
            break;
        case 'year':
            $salesQuery = "SELECT sales.transaction_number, 
                                      products.name AS product_name, 
                                      sales.quantity_sold, 
                                      sales.sale_date, 
                                      products.market_price, 
                                      products.selling_price 
                               FROM sales 
                               JOIN products ON sales.product_id = products.id 
                               WHERE sale_date BETWEEN '$yearStart' AND '$yearEnd'
                               ORDER BY sales.sale_date DESC
                               LIMIT $limit OFFSET $offset";
            break;
    }
}

// Initialize variables
$sales = [];
$search = isset($_GET['search']) ? $_GET['search'] : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

// Base query
$baseQuery = "SELECT sales.transaction_number, 
                     products.name AS product_name, 
                     sales.quantity_sold, 
                     sales.sale_date, 
                     products.market_price, 
                     products.selling_price,
                     sales.payment_method,
                     sales.username
              FROM sales 
              JOIN products ON sales.product_id = products.id";

// Where clause
$whereClause = "";

// Apply search
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $whereClause .= " WHERE (products.name LIKE '%$search%' OR sales.transaction_number LIKE '%$search%')";
}

// Apply filter
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

// Combine queries
$salesQuery = $baseQuery . $whereClause . " ORDER BY sales.sale_date DESC LIMIT $limit OFFSET $offset";

// Execute the query and populate $sales
$salesResult = $conn->query($salesQuery);
if ($salesResult) {
    while ($row = $salesResult->fetch_assoc()) {
        $sales[] = [
            'transaction_number' => $row['transaction_number'],
            'product_name' => $row['product_name'],
            'quantity_sold' => $row['quantity_sold'],
            'sale_date' => $row['sale_date'],
            'selling_price' => $row['selling_price'],
            'total' => number_format($row['selling_price'] * $row['quantity_sold'], 2),
            'payment_method' => $row['payment_method'],
            'username' => $row['username'],
        ];
    }
}

// Fetch total sales and revenue for different periods
$totalSalesQuery = "SELECT 
                        SUM(selling_price * quantity_sold) AS total_sales,
                        SUM(quantity_sold) AS total_products_sold,
                        SUM(selling_price - market_price) AS total_revenue 
                    FROM sales 
                    JOIN products ON sales.product_id = products.id";

$totalSales = $conn->query($totalSalesQuery)->fetch_assoc();

// Get total number of sales for pagination
$totalSalesQuery = "SELECT COUNT(*) AS total_sales FROM sales";
$totalSalesResult = $conn->query($totalSalesQuery);
$totalSalesRow = $totalSalesResult->fetch_assoc();
$totalSalesCount = $totalSalesRow['total_sales'];
$totalPages = ceil($totalSalesCount / $limit); // Calculate total pages
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
        table {
            margin-top: 20px;
            font-size: 0.9rem;
            
        }
        .no-sales {
            text-align: center;
            padding: 20px;
            font-size: 1.2rem;
            color: #999;
        }
        .pagination {
            margin-top: 20px;
            justify-content: center;
        }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>
    <div class="main-content">
        <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
        <h2>Sales Logs</h2>

        <!-- Search form -->
        <form id="searchForm" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" id="searchInput" name="search" class="form-control" placeholder="Search by product name or transaction number" value="<?php echo htmlspecialchars($search); ?>">
            </div>
        </form>

        <!-- Display sales data -->
        <?php if (!empty($sales)): ?>
    <?php 
    // Group sales by transaction number
    $groupedSales = [];
    foreach ($sales as $sale) {
        $groupedSales[$sale['transaction_number']][] = $sale;
    }

    // Render grouped sales
    foreach ($groupedSales as $transactionNumber => $transactionSales): ?>
       
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Transaction Number</th> <!-- Add header for transaction number -->
                    <th>Products</th>
                    <th>Total</th>
                    <th>Payment Method</th>
                    <th>Buyer</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $transactionNumber; ?></td> <!-- Display transaction number -->
                    <td>
                        <?php 
                        // Display all product names for this transaction in one cell
                        $products = [];
                        foreach ($transactionSales as $sale) {
                            $products[] = $sale['product_name'];
                        }
                        echo implode(', ', $products);
                        ?>
                    </td>
                    <td>₱<?php 
                        // Calculate the total for all products in this transaction
                        $total = 0;
                        foreach ($transactionSales as $sale) {
                            $total += $sale['selling_price'] * $sale['quantity_sold'];
                        }
                        echo number_format($total, 2);
                    ?></td>
                    <td><?php echo ucfirst($transactionSales[0]['payment_method']); ?></td>
                    <td><?php echo $transactionSales[0]['username']; ?></td>
                </tr>
            </tbody>
        </table>
    <?php endforeach; ?>
<?php else: ?>
    <p>No sales found for the given search criteria.</p>
<?php endif; ?>

        <!-- Pagination -->
       <!-- Pagination -->
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
        <!-- Previous button -->
        <li class="page-item <?php echo $page == 1 ? 'disabled' : ''; ?>">
            <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo $search; ?>&filter=<?php echo $filter; ?>" tabindex="-1">&lt;</a>
        </li>

        <?php
        // Show a limited number of page links
        $startPage = max(1, $page - 1); // Ensure the first page is 1
        $endPage = min($totalPages, $page + 1); // Ensure the last page is $totalPages

        // Display pages within the range
        for ($i = $startPage; $i <= $endPage; $i++):
        ?>
            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>&filter=<?php echo $filter; ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>

        <!-- Next button -->
        <li class="page-item <?php echo $page == $totalPages ? 'disabled' : ''; ?>">
            <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo $search; ?>&filter=<?php echo $filter; ?>">&gt;</a>
        </li>
    </ul>
</nav>


    </div>
</body>
</html>
