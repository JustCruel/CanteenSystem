<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canteen Management System</title>
    <!-- Bootstrap & Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/styles.css"> <!-- Your custom CSS -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            margin: 0;
            background-color: #f0f0f0; /* Light gray background */
            height: 100vh; /* Full height of the viewport */
        }

        .sidebar {
            width: 250px; /* Sidebar width */
            background-color: #ffffff; /* White background for sidebar */
            padding: 20px;
            color: #001f3f; /* Navy blue text */
            transition: width 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .sidebar img {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
        }

        .sidebar h2 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            text-align: center;
        }

        .button-card {
            display: flex;
            align-items: center;
            background-color: #007bff; /* Light blue */
            color: white;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
            text-decoration: none;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }

        .button-card i {
            margin-right: 10px;
        }

        .button-card:hover {
            background-color: #0056b3; /* Darker blue */
        }

        .main-content {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background-color: #ffffff; /* White background for main content */
            border-left: 1px solid #e0e0e0;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <img src="assets/images/heading.png" alt="Holy Cross College Logo"> <!-- Your logo -->
        <h2>Menu</h2>

        <!-- Navigation Links -->
        <a class="button-card" href="cashierdashboard.php">
            <i class="fas fa-tachometer-alt"></i>
            Dashboard
        </a>
        <a class="button-card" href="usersload.php">
            <i class="fas fa-box"></i>
            Load RFID
        </a>
        <a class="button-card" href="activate_card.php">
            <i class="fas fa-file-invoice"></i>
            Activate RFID/Account
        </a>
        <a class="button-card" href="disable_card.php">
            <i class="fas fa-file-invoice"></i>
            Disabled RFID/Account
        </a>
        <a class="button-card" href="registeractivate.php">
            <i class="fas fa-exclamation-triangle"></i>
            Activate RFID and Account
        </a>
        <a class="button-card" href="transaction_history.php">
            <i class="fas fa-exclamation-triangle"></i>
           Transaction History
        </a>
        <a class="button-card" href="rfid_logs.php">
            <i class="fas fa-exclamation-triangle"></i>
           Activity Logs
        </a>
        <a class="button-card" href="logout.php">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </a>
    </div>

    <!-- Optional JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
