<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Registration Form with Activation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/sidebar.css">
    <style>
      
    </style>
</head>
<body>
<div class="top-bar">
    <h1>Holy Cross College MIS</h1>
        <span class="hamburger" id="hamburger" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </span>
        <a href="logout.php" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <img src="assets/images/hcclogo.jpg" alt="Holy Cross College Logo"> 
        <h2>Menu</h2>
        <a href="misdashboard.php">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="activate_card.php">
            <i class="fas fa-tachometer-alt"></i> Activate RFID/Accounts
        </a>
        <!-- Inventory with Submenu -->
       <!-- <div class="menu-item">
            <a href="#inventorySubmenu" class="inventory-toggle" onclick="toggleSubmenu()">
                <i class="fas fa-cogs"></i> Inventory <i class="fas fa-chevron-down submenu-icon"></i>
            </a> 
            <div id="inventorySubmenu" class="submenu">
                <a href="cashierdashboard.php">Dashboard</a>
                <a href="usersload.php">Load RFID</a>
                <a href="transaction_history.php">Transaction History</a>
            </div>
        </div> -->
        <a href="registeractivate.php">
            <i class="fas fa-file-invoice"></i>Upload Student/Faculty List
        </a>
        <a href="rfid_logs.php">
            <i class="fas fa-file-invoice"></i>Activity Logs
        </a>
    </div>
    <div class="main-content">
        <h2 class="text-center">Bulk Registration</h2>
        <form action="registerr.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="file" class="form-label">Upload Excel File:</label>
                <input type="file" class="form-control" name="file" id="file" accept=".xlsx, .xls" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary" name="display">Display and Preview</button>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="sidebar.js"></script>
</body>
</html>
