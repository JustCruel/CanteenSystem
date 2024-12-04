<?php
// Check if a session is already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'cstaff') {
    header("Location: login.php");
    exit();
}

include 'db.php'; // Database connection

// Initialize selected products in session
if (!isset($_SESSION['selected_products'])) {
    $_SESSION['selected_products'] = [];
}

// Fetch low stock products
$lowStockQuery = $conn->query("SELECT * FROM products WHERE quantity < 20");
$lowStockProducts = $lowStockQuery->fetchAll();

$products = []; // Initialize products variable
$total = 0; // Initialize total variable

// Check if a category is set for initial load
if (isset($_GET['category'])) {
    $category = htmlspecialchars($_GET['category']);
    $query = $conn->prepare("SELECT * FROM products WHERE category = :category");
    $query->bindParam(':category', $category);
    $query->execute();
    $products = $query->fetchAll();
} else {
    // Fetch all products if no category is set
    $query = $conn->query("SELECT * FROM products");
    $products = $query->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canteen Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/sidebar.css"> <!-- Your custom CSS -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            margin: 0;
            background-color: #f0f0f0; /* Light gray background */
            height: 100vh; /* Full height of the viewport */
        }

        .sidebar {
            width: 250px; /* Width of the sidebar */
            background-color: #ffffff; /* White background for sidebar */
            margin: 0;
            padding: 20px;
            color: #001f3f; /* Navy blue text */
            transition: width 0.3s ease;
            display: flex;
            flex-direction: column; /* Stack items vertically */
            height: 100vh; /* Full height */
            position: relative; /* Position relative for main content */
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

        .button-card:hover {
            background-color: #0056b3; /* Darker blue */
        }

        .main-content {
            flex: 1; /* This makes it take available space */
            margin: 0;
            padding: 20px;
            overflow-y: auto; /* Allows scrolling if content is too long */
            background-color: #ffffff; /* White background for main content */
            border-left: 1px solid #e0e0e0; /* Subtle border for separation */
            height: 100%; /* Ensure it takes full height of the parent */
        }

        .selected-products {
            width: 300px; /* Set a fixed width for the selected products */
            border: 1px solid #ccc;
            padding: 10px;
            margin-top: 20px;
        }

        .selected-products h4 {
            margin-bottom: 15px;
        }

        .product-card {
            cursor: pointer; /* Change cursor to pointer to indicate clickable area */
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px 0;
            transition: background-color 0.3s;
        }

        .product-card:hover {
            background-color: #f0f0f0; /* Change background on hover */
        }

        .available-stock {
            margin-top: 10px; /* Space between price and available stock */
            font-size: 0.9em; /* Slightly smaller font for available stock */
            color: #666; /* Lighter color for better visibility */
        }

        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <img src="assets/images/heading.png" alt="Holy Cross College Logo"> <!-- Updated with your logo -->
        <h2>Menu</h2>

        <h2>Categories</h2>
        <a class="button-card" href="javascript:void(0);" onclick="showContent('category.php?category=drinks')">
            <i class="fas fa-glass-cheers"></i>
            Drinks
        </a>
        <a class="button-card" href="javascript:void(0);" onclick="showContent('category.php?category=snacks')">
            <i class="fas fa-cookie"></i>
            Snacks
        </a>
        <a class="button-card" href="javascript:void(0);" onclick="showContent('category.php?category=meals')">
            <i class="fas fa-utensils"></i>
            Meals
        </a>
        <a class="button-card" href="javascript:void(0);" onclick="showContent('category.php?category=desserts')">
            <i class="fas fa-ice-cream"></i>
            Desserts
        </a>
        <a class="button-card" href="javascript:void(0);" onclick="showContent('category.php?category=breakfast')">
            <i class="fas fa-coffee"></i>
            Breakfast
        </a>
    </div>

    <div class="main-content" id="main-content">
        <?php if (!empty($lowStockProducts)): ?>
            <div class="alert alert-warning" role="alert">
                <strong>Warning!</strong> The following products have low stock:
                <ul>
                    <?php foreach ($lowStockProducts as $lowStockProduct): ?>
                        <li><?php echo htmlspecialchars($lowStockProduct['name']) . " (Quantity: " . htmlspecialchars($lowStockProduct['quantity']) . ")"; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="selected-products">
            <h4>Selected Products:</h4>
            <ul>
                <?php if (!empty($_SESSION['selected_products'])): ?>
                    <?php foreach ($_SESSION['selected_products'] as $item): ?>
                        <li><?php echo htmlspecialchars($item['name']); ?> (Qty: <?php echo $item['quantity']; ?>) - $<?php echo number_format($item['subtotal'], 2); ?></li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>No products selected.</li>
                <?php endif; ?>
            </ul>
        </div>

        <h1>Point of Sale</h1>
        <form id="pos-form" method="POST">
            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card" data-product-id="<?php echo $product['id']; ?>" data-product-name="<?php echo htmlspecialchars($product['name']); ?>" data-product-price="<?php echo $product['price']; ?>">
                        <img src="assets/images/<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                        <p>$<?php echo number_format($product['price'], 2); ?></p>
                        <div class="available-stock">Available Stock: <?php echo $product['quantity']; ?></div>
                        <input type="hidden" name="products[]" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="quantities[]" class="quantity-input" value="0">
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="submit" name="calculate" class="calculate-btn">Calculate Total</button>
        </form>

        <div id="total-display">
            <?php if ($total > 0): ?>
                <h3>Total: $<?php echo number_format($total, 2); ?></h3>
                <button class="confirm-btn" id="confirm-sale-btn" name="confirm">Confirm Sale</button>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function showContent(page) {
            const mainContent = document.getElementById('main-content');
            fetch(page)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(data => {
                    mainContent.innerHTML = data; // Inject the content here
                })
                .catch(error => {
                    mainContent.innerHTML = `<h1>Error loading page</h1><p>${error.message}</p>`;
                });
        }

        // Event listener for product selection
        document.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('click', function () {
                const productId = this.dataset.productId;
                const productName = this.dataset.productName;
                const productPrice = parseFloat(this.dataset.productPrice);
                let quantityInput = this.querySelector('.quantity-input');

                // Increment quantity
                let quantity = parseInt(quantityInput.value) + 1;
                quantityInput.value = quantity;

                // Update selected products in session
                const selectedProducts = document.querySelector('.selected-products ul');
                const listItem = document.createElement('li');
                listItem.textContent = `${productName} (Qty: ${quantity}) - $${(productPrice * quantity).toFixed(2)}`;
                selectedProducts.appendChild(listItem);
            });
        });

        document.getElementById('confirm-sale-btn').addEventListener('click', function() {
            Swal.fire({
                title: 'Confirm Sale',
                text: "Are you sure you want to complete this sale?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, confirm it!',
                cancelButtonText: 'No, cancel!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('pos-form').submit();
                }
            });
        });
    </script>
</body>
</html>
