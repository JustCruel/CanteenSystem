<?php
// Database connection
$servername = "localhost"; // Change if necessary
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "canteenms"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch total sales and total products sold today
$totalSales = 0;
$totalProductsSold = 0;

$salesSql = "SELECT SUM(total) as totalSales, SUM(quantity_sold) as totalProductsSold FROM sales WHERE sale_date = CURDATE()";
$salesResult = $conn->query($salesSql);

if ($salesResult->num_rows > 0) {
    $salesRow = $salesResult->fetch_assoc();
    $totalSales = $salesRow['totalSales'] ? $salesRow['totalSales'] : 0;
    $totalProductsSold = $salesRow['totalProductsSold'] ? $salesRow['totalProductsSold'] : 0;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/css/stylespos.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="logo">My Canteen</div>
            <nav>
                <ul>
                    <li><a href="index.php">Dashboard</a></li>
                    <li><a href="pos.php">POS</a></li>
                    <li><a href="#">Categories</a></li>
                    <li><a href="#">Inventory</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="main-content">
            <section class="dashboard-section">
                <h2>Dashboard</h2>
                <div class="dashboard-info">
                    <div>Total Sales Today: ₱<?= number_format($totalSales, 2) ?></div>
                    <div>Total Products Sold Today: <?= $totalProductsSold ?></div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
