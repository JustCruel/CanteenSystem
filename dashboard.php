<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'cmanager') {
    header("Location: login.php");
    exit();
}



include 'db.php'; // Database connection
include 'sidebar.php';// Include your sidebar

$userQuery = $conn->query("SELECT * FROM user WHERE id = '" . $_SESSION['user_id'] . "' ");
$userData = $userQuery->fetch_assoc();

// Set the session variables
$_SESSION['first_name'] = $userData['first_name'];

// Fetch total sales
$salesQuery = $conn->query("SELECT SUM(total) AS total_sales FROM sales");
$totalSales = ($salesQuery->fetch_assoc())['total_sales'];

// Fetch total products
$productsQuery = $conn->query("SELECT COUNT(*) AS total_products FROM products");
$totalProducts = ($productsQuery->fetch_assoc())['total_products'];

// Fetch total customers (students)
$studentsQuery = $conn->query("SELECT COUNT(*) AS total_customers FROM user WHERE user_type = 'user'");
$totalCustomers = ($studentsQuery->fetch_assoc())['total_customers'];

// Fetch total quantity sold
$quantitySoldQuery = $conn->query("SELECT SUM(quantity_sold) AS total_quantity_sold FROM sales");
$totalQuantitySold = ($quantitySoldQuery->fetch_assoc())['total_quantity_sold'];

// Fetch sales data for the last 30 days
$salesDataQuery = $conn->query("SELECT DATE(sale_date) AS date, SUM(total) AS total FROM sales WHERE sale_date >= NOW() - INTERVAL 30 DAY GROUP BY DATE(sale_date)");
$salesData = [];
while ($row = $salesDataQuery->fetch_assoc()) {
    $salesData[] = $row;
}

// Fetch product quantities
$productQuantityQuery = $conn->query("SELECT name, quantity FROM products");
$productQuantities = [];
while ($row = $productQuantityQuery->fetch_assoc()) {
    $productQuantities[$row['name']] = $row['quantity'];
}

// Fetch low stock products
$lowStockQuery = $conn->query("SELECT name, quantity FROM products WHERE quantity < 20");
$lowStockProducts = [];
while ($row = $lowStockQuery->fetch_assoc()) {
    $lowStockProducts[] = $row;
}

// Fetch most sold products (top 5)
$mostSoldProductsQuery = $conn->query("SELECT p.name, SUM(s.quantity_sold) AS total_sold 
                                        FROM sales s 
                                        JOIN products p ON s.product_id = p.id 
                                        GROUP BY p.id 
                                        ORDER BY total_sold DESC 
                                        LIMIT 15");
$mostSoldProducts = [];
while ($row = $mostSoldProductsQuery->fetch_assoc()) {
    $mostSoldProducts[$row['name']] = $row['total_sold'];
}
$mostSoldProducts = array_map('intval', $mostSoldProducts);



// Fetch categories
$categoriesQuery = $conn->query("SELECT DISTINCT category FROM products");
$categories = [];
while ($row = $categoriesQuery->fetch_assoc()) {
    $categories[] = $row['category'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <style>
    
        .card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
            margin-bottom: 20px;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-icon {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 10px;
        }

        .card-title {
            font-size: 1.5rem;
            margin-bottom: 5px;
        }

        .card-text {
            font-size: 1.25rem;
            font-weight: bold;
        }

        .alert {
            border-radius: 8px;
            padding: 15px;
            position: relative;
            margin-top: 20px;
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            display: flex;
            align-items: flex-start;
        }

        .alert .alert-icon {
            font-size: 24px;
            color: #856404;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .alert h4 {
            margin: 0;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .alert ul {
            list-style-type: none;
            padding-left: 0;
            margin: 0;
        }

        .alert li {
            padding: 5px 0;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .col-md-4 {
            flex: 1 1 30%;
            box-sizing: border-box;
            margin: 0 10px;
        }

        .col-md-12 {
            flex: 1 1 100%;
            margin-bottom: 20px;
        }

        .row {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.col-md-3 {
    flex: 1 1 22%; /* Make each card take up about 25% of the row */
    box-sizing: border-box;
    margin: 0 1%; /* Adjust margin to ensure four cards fit per row */
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .col-md-3 {
        flex: 1 1 48%; /* Adjust for smaller screens */
        margin-bottom: 20px;
    }
}

@media (max-width: 480px) {
    .col-md-3 {
        flex: 1 1 100%; /* Full-width for very small screens */
        margin-bottom: 20px;
    }
}


        .chart-container {
            position: relative;
            height: 40vh;
            width: 80vw;
            margin-top: 20px;
        }

        .main-content {
        padding: 20px;
        margin-top: 0; /* Remove top margin */
    }
    </style>
</head>
<body>



    <div class="main-content">
    <h1>Dashboard</h1>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['first_name']); ?>!</h2>
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="card-icon text-primary">
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                    <h5 class="card-title">Total Sales</h5>
                    <p class="card-text">₱<?php echo number_format($totalSales, 2); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="card-icon text-warning">
                        <i class="fas fa-chart-bar fa-2x"></i>
                    </div>
                    <h5 class="card-title">Total Quantity Sold</h5>
                    <p class="card-text"><?php echo $totalQuantitySold; ?> units</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="card-icon text-success">
                        <i class="fas fa-box fa-2x"></i>
                    </div>
                    <h5 class="card-title">Total Products</h5>
                    <p class="card-text"><?php echo $totalProducts; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="card-icon text-info">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <h5 class="card-title">Total Customers</h5>
                    <p class="card-text"><?php echo $totalCustomers; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Card for Total Sales Chart (Bar Chart) -->
    <div class="row">
        <!-- Total Sales Chart (Bar Chart) -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Sales (Last 30 Days)</h5>
                    <div id="salesBarChart"></div>
                </div>
            </div>
        </div>

        <!-- Quantity Sold Stacked Bar Chart -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Quantity Sold (Stacked Bar Chart)</h5>
                    <div id="quantitySoldStackedBarChart"></div>
                </div>
            </div>
        </div>

        <!-- Most Sold Products Pie Chart -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Most Sold Products</h5>
                    <div id="mostSoldProductsPieChart" style="height: 300px; width: 100%;"></div>
                </div>
            </div>
        </div>

        <!-- Most Sold Products by Category -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Most Sold Products by Category</h5>
                    <select id="categorySelect" class="form-control">
                        <option value="">-- Select Category --</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category; ?>"><?php echo htmlspecialchars($category); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div id="mostSoldProductsPieChartCategory" style="height: 300px; width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

    <script>

document.addEventListener("DOMContentLoaded", function() {
        // Auto-select the first category if available
        var categorySelect = document.getElementById('categorySelect');
        if (categorySelect.options.length > 1) { // Check if there are categories available
            categorySelect.selectedIndex = 1; // Select the first category (index 1)
            // Trigger change event to fetch data for the selected category
            categorySelect.dispatchEvent(new Event('change'));
        }
    });
        // Data
        var salesData = <?php echo json_encode($salesData); ?>;
        var labels = salesData.map(function (data) { return data.date; });
        var sales = salesData.map(function (data) { return data.total; });

        var productQuantities = <?php echo json_encode($productQuantities); ?>;
        var productNames = Object.keys(productQuantities);
        var productValues = Object.values(productQuantities);

        var mostSoldProducts = <?php echo json_encode($mostSoldProducts); ?>;
        var mostSoldLabels = Object.keys(mostSoldProducts);
        var mostSoldData = Object.values(mostSoldProducts);

        // Sales Bar Chart
        var salesOptions = {
            chart: {
                type: 'bar',
                height: 350
            },
            series: [{
                name: 'Total Sales',
                data: sales
            }],
            xaxis: {
                categories: labels
            },
            title: {
                text: 'Total Sales (Last 30 Days)',
                align: 'center'
            }
        };
        var salesBarChart = new ApexCharts(document.querySelector("#salesBarChart"), salesOptions);
        salesBarChart.render();

        // Quantity Sold Stacked Bar Chart
        var quantityOptions = {
            chart: {
                type: 'bar',
                stacked: true,
                height: 350
            },
            series: [{
                name: 'Quantity',
                data: productValues
            }],
            xaxis: {
                categories: productNames
            },
            title: {
                text: 'Total Quantity Sold',
                align: 'center'
            },
            fill: {
                opacity: 1
            },
            legend: {
                position: 'top',
                horizontalAlign: 'left',
                offsetX: 40
            }
        };
        var quantitySoldStackedBarChart = new ApexCharts(document.querySelector("#quantitySoldStackedBarChart"), quantityOptions);
        quantitySoldStackedBarChart.render();

        // Most Sold Products Pie Chart
      // Most Sold Products Pie Chart// Most Sold Products Donut Chart
     // Most Sold Products Pie Chart
// Most Sold Products Pie Chart
document.addEventListener("DOMContentLoaded", function() {
    // Prepare labels with product name and quantity sold
    var labelsWithQuantity = <?php echo json_encode(array_map(function($name, $quantity) {
        return $name . ' (' . $quantity . ' )';
    }, array_keys($mostSoldProducts), array_values($mostSoldProducts))); ?>;

    var options = {
        series: <?php echo json_encode(array_values($mostSoldProducts)); ?>, // Quantities
        chart: {
            type: 'pie',
            height: 300
        },
        labels: labelsWithQuantity, // Updated labels with name and quantity
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    var chart = new ApexCharts(document.querySelector("#mostSoldProductsPieChart"), options);
    chart.render().then(function() {
        console.log('Chart rendered successfully');
    }).catch(function(error) {
        console.error('Error rendering chart:', error);
    });
});
document.getElementById('categorySelect').addEventListener('change', function() {
    var selectedCategory = this.value;

    if (selectedCategory) {
        // Fetch most sold products for the selected category
        fetchMostSoldProductsByCategory(selectedCategory);
    }
});

function fetchMostSoldProductsByCategory(category) {
    // Send a request to the server to fetch products for the selected category
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'get_category_sales.php?category=' + category, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            updatePieChart(response);
        }
    };
    xhr.send();
}

function updatePieChart(data) {
    // Ensure series values are integers
    var chartData = {
        labels: data.labels,
        series: data.series.map(function(value) {
            return parseInt(value, 10); // Convert each value to an integer
        })
    };

    // Destroy the old chart if it exists
    if (window.mostSoldProductsCategoryChart) {
        window.mostSoldProductsCategoryChart.destroy();
    }

    // Create the new pie chart
    var options = {
        series: chartData.series,
        chart: {
            type: 'pie',
            height: 300
        },
        labels: chartData.labels,
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    window.mostSoldProductsCategoryChart = new ApexCharts(document.querySelector("#mostSoldProductsPieChartCategory"), options);
    window.mostSoldProductsCategoryChart.render();
}


    </script>

</body>
</html>
