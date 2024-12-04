<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'user') {
    header("Location: login.php");
    exit();
}

include 'db.php'; // Database connection
include 'sidebaruser.php'; // Include your sidebar

// Fetch user information
$userQuery = $conn->query("SELECT * FROM user WHERE id = '" . $_SESSION['user_id'] . "'");
$userData = $userQuery->fetch_assoc();

// Set the session variables
$_SESSION['first_name'] = $userData['first_name'];

// Fetch user balance
$balanceQuery = $conn->query("SELECT balance FROM user WHERE id = '" . $_SESSION['user_id'] . "'");
$userBalance = ($balanceQuery->fetch_assoc())['balance'];

// Fetch the total number of distinct e-receipts for the user
$totalReceiptsQuery = $conn->query("
    SELECT COUNT(DISTINCT er.id) AS total_receipts
    FROM e_receipts er
    WHERE er.rfid_code = '" . $userData['rfid_code'] . "'
");
$totalReceiptsData = $totalReceiptsQuery->fetch_assoc();
$totalReceipts = $totalReceiptsData['total_receipts'];

// Fetch user receipts details
$allReceiptsQuery = $conn->query("
    SELECT er.id AS receipt_id, er.sale_date, er.total_amount, erd.product_id, 
           erd.quantity_sold, erd.total, p.name AS product_name
    FROM e_receipts er 
    JOIN e_receipt_details erd ON er.id = erd.e_receipt_id 
    JOIN products p ON erd.product_id = p.id 
    WHERE er.rfid_code = '" . $userData['rfid_code'] . "' 
    ORDER BY er.sale_date DESC
");

$allReceipts = [];
while ($row = $allReceiptsQuery->fetch_assoc()) {
    $allReceipts[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="your-styles.css"> <!-- Include your custom CSS file -->
    <style>
        .main-content {
            padding: 20px;
        }
        .card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
            margin-bottom: 20px;
            padding: 15px;
        }
        .card-title {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        .card-text {
            font-size: 1.25rem;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #e0e0e0;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f7f7f7;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <h1>User Dashboard</h1>
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['first_name']); ?>!</h2>

        <!-- Balance Card -->
        <div class="card">
            <h5 class="card-title">Current Balance</h5>
            <p class="card-text">₱<?php echo number_format($userBalance, 2); ?></p>
        </div>

        <!-- E-Receipt Summary Card -->
        <div class="card">
            <h5 class="card-title">E-Receipts Summary</h5>
            <p class="card-text">Total Receipts: <?php echo $totalReceipts; ?></p>
        </div>

        <!-- E-Receipts Table -->
 

    </div>
</body>
</html>
