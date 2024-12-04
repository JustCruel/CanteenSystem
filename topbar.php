<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'DM Sans', sans-serif;
            margin: 0;
            padding: 0;
        }

        .top-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #007bff; /* Dark navy blue */
            padding: 10px 20px;
            color: white;
            position: fixed; /* Fix the navbar at the top */
            width: calc(100% - 250px); /* Adjust width based on sidebar width */
            top: 0; /* Position at the top */
            z-index: 1000; /* Ensure it stays above other content */
            margin-left: 250px; /* Adjust based on the width of your sidebar */
        }

        .logo a {
            text-decoration: none;
            font-size: 24px;
            color: white;
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 20px; /* Space between links */
        }

        .nav-links li a {
            text-decoration: none;
            color: white;
            padding: 8px 15px;
            transition: background-color 0.3s;
        }

        .nav-links li a:hover {
            background-color: #2c3e50; /* Lighter navy on hover */
            border-radius: 5px;
        }

        /* Sidebar styles */
        .sidebar {
            position: fixed; /* Fixed sidebar */
            width: 250px; /* Width of the sidebar */
            height: 100%; /* Full height */
            background-color: #34495e; /* Sidebar background */
            z-index: 999; /* Higher z-index to stay above the content */
        }

        /* Main content area */
        .content {
            margin-left: 250px; /* Align content next to sidebar */
            padding: 80px 20px 20px; /* Add padding to prevent overlap with top-nav */
            background-color: #f4f4f4; /* Light background for content */
            min-height: calc(100vh - 80px); /* Full height minus navbar height */
            overflow-y: auto; /* Allow scrolling if content exceeds viewport */
        }
    </style>
    <title>Top Navigation Bar</title>
</head>
<body>


<div class="top-nav">
    <div class="logo">
        <a href="#">YourLogo</a>
    </div>
    <ul class="nav-links">
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="inventory.php">Inventory</a></li>
        <li><a href="pos.php">POS</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>


</body>
</html>
