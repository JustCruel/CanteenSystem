<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canteen Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            margin: 0;
            background-color: #f4f7fc;
            height: 100vh;
            flex-direction: column;
        }

        /* Top bar style */
        .top-bar {
            
            background-color: #fafafa;
            color: #333;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 2;
            box-shadow: 10px 10px 10px rgba(0, 0, 0, 0.1);
            height: 70px;
        }

        .top-bar h1 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
    color: #023B87;
    letter-spacing: -0.1em;
    text-align: left;
    margin-left: -16px; /* Use negative margin to shift it left */
}

/* Hide h1 when sidebar is closed */
/* Hide h1 when sidebar is closed (on smaller screens) */
.top-bar h1.open {
    display: none;
}




        .top-bar .logout-btn {
            color: white;
            background-color: #005cbf;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            position: absolute;
            top: 10px;
            right: 20px;
        }

        .top-bar .logout-btn:hover {
            background-color: #003366;
        }

        /* Sidebar style */
        .sidebar {
            margin-top: 70px;
            width: 250px;
            background-color: #023B87;
            color: #333;
            height: 100vh;
            padding-top: 20px;
            position: fixed;
            transition: all 0.3s ease;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            z-index: 1;
            overflow-y: auto;
            left: -250px;
        }

        .sidebar.open {
            left: 0;
        }

        .hamburger {
            display: block;
            font-size: 24px;
            color: #333;
            background-color: transparent;
            padding: 10px;
            cursor: pointer;
            z-index: 3;
            position: fixed;
            top: 15px;
            left: 10px;
            transition: left 0.3s ease;
        }

        .hamburger.open {
            left: 260px;
        }
        .sidebar img {
    width: 180px;  /* Set the width of the image */
    height: 180px;  /* Set the height of the image */
    display: block;
    margin: 0 auto 20px;
    background-color: white;
    padding: 10px;
    border-radius: 50%;  /* Makes the image circular */
}


        .sidebar h2 {
            color: #ffffff;
            font-size: 1.3rem;
            text-align: center;
            margin-bottom: 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #ffffff;
            padding: 12px 20px;
            margin: 8px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .sidebar a:hover, .sidebar a.active {
            background-color: #e6f0ff;
            color: #007bff;
            transform: translateX(5px);
        }

        .sidebar a i {
            margin-right: 12px;
            font-size: 1.2rem;
            width: 20px;
            text-align: center;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            background-color: #ffffff;
            min-height: 100vh;
            box-sizing: border-box;
            margin-top: 70px;
        }

        /* Submenu styles */
        .submenu {
            display: none;
            flex-direction: column;
            margin-left: 20px;
            padding-left: 15px;
            border-left: 2px solid #007bff;
        }

        .submenu.open {
            display: flex;
        }

        .submenu a {
            font-size: 0.9rem;
            color: #ffffff;
            padding: 8px 0;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .submenu a:hover {
            color: #007bff;
        }

        .submenu-icon {
            margin-left: auto;
            transition: transform 0.3s ease;
        }

        .inventory-toggle {
            display: flex;
            align-items: center;
        }

        /* Rotate arrow when submenu opens */
        .submenu.open ~ .inventory-toggle .submenu-icon {
            transform: rotate(180deg);
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow-x: hidden;
                transition: width 0.3s ease;
            }

            .sidebar.open {
                width: 250px;
            }

            .hamburger {
                left: 10px;
            }

            .hamburger.open {
                left: 260px;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

    <!-- Top bar -->
    <div class="top-bar">
        <h1>Holy Cross College CMS</h1>
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
        <a href="dashboard.php">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <!-- Inventory with Submenu -->
        <div class="menu-item">
            <a href="#inventorySubmenu" class="inventory-toggle" onclick="toggleSubmenu()">
                <i class="fas fa-cogs"></i> Inventory <i class="fas fa-chevron-down submenu-icon"></i>
            </a>
            <div id="inventorySubmenu" class="submenu">
                <a href="inventory.php">Inventory</a>
                <a href="add_product.php">Add Product</a>
                <a href="bulk_upload_products.php">Bulk Upload Product</a>
               
               
            </div>
        </div>
        <a href="sales_report.php">
            <i class="fas fa-file-invoice"></i> Sales Report
        </a>
        <a href="sales_logs.php">
            <i class="fas fa-file-invoice"></i> Sales Logs
        </a>
    </div>

    <!-- Main Content -->


    <script>
        // Function to toggle the sidebar
       // Function to toggle the sidebar
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const hamburger = document.getElementById('hamburger');
    const mainContent = document.querySelector('.main-content');
    const h1 = document.querySelector('.top-bar h1');  // Select the h1 element
    
    sidebar.classList.toggle('open');
    hamburger.classList.toggle('open');
    mainContent.classList.toggle('sidebar-open');
    h1.classList.toggle('open');  // Toggle the visibility of the h1
}

// Initialize sidebar and hamburger state
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const hamburger = document.getElementById('hamburger');
    const mainContent = document.querySelector('.main-content');
    const h1 = document.querySelector('.top-bar h1');  // Select the h1 element

    if (window.innerWidth > 768) {
        sidebar.classList.add('open');
        hamburger.classList.add('open');
        mainContent.classList.add('sidebar-open');
        h1.classList.remove('open');  // Ensure h1 is always visible on large screens
    }
});

// Handle window resize
window.addEventListener('resize', function() {
    const sidebar = document.getElementById('sidebar');
    const hamburger = document.getElementById('hamburger');
    const mainContent = document.querySelector('.main-content');
    const h1 = document.querySelector('.top-bar h1');  // Select the h1 element
    if (window.innerWidth > 768) {
        sidebar.classList.add('open');
        hamburger.classList.add('open');
        mainContent.classList.add('sidebar-open');
        h1.classList.remove('open');  // Ensure h1 is visible when the screen is large
    } else {
        sidebar.classList.remove('open');
        hamburger.classList.remove('open');
        mainContent.classList.remove('sidebar-open');
        h1.classList.add('open');  // Hide h1 on smaller screens
    }
});


        // Function to toggle the inventory submenu
        function toggleSubmenu() {
            const submenu = document.getElementById('inventorySubmenu');
            submenu.classList.toggle('open');
        }
    </script>
</body>
</html>
