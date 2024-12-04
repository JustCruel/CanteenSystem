<?php  
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch the inventory data
$query = "SELECT id, name, quantity, image, expiry_date, market_price, selling_price, category FROM products ORDER BY id DESC";

$result = $conn->query($query);

// Initialize the products arrays
$availableProducts = [];
$outOfStockProducts = [];

if ($result->num_rows > 0) {
    // Fetch all products as an associative array
    while ($row = $result->fetch_assoc()) {
        if ($row['quantity'] > 0) {
            $availableProducts[] = $row; // Add available product
        } else {
            $outOfStockProducts[] = $row; // Add out-of-stock product
        }
    }
}

// Handle restock action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['restock'])) {
    $productId = intval($_POST['product_id']);
    $restockAmount = intval($_POST['restock_amount']);

    // Update the product quantity in the database
    $updateQuery = "UPDATE products SET quantity = quantity + ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ii", $restockAmount, $productId);
    if ($stmt->execute()) {
        echo '<script>alert("Product restocked successfully!");</script>';
    } else {
        echo '<script>alert("Failed to restock product.");</script>';
    }
    $stmt->close();
}

include 'sidebar.php'; // Include the sidebar
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap">
    <style>
        /* Custom styles */
        body {
            font-family: 'DM Sans', sans-serif;
        }
        .main-content {
            padding: 20px;
        }
        .table {
            border-collapse: collapse; /* Prevent double borders */
        }
        .table th, .table td {
            border: none; /* Remove all borders */
            padding: 10px; /* Add padding for better spacing */
            text-align: center; /* Center text horizontally */
        }
        .alert {
            margin-bottom: 20px;
        }
        #searchInput {
            width: 300px;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="add_product.php" class="btn btn-primary">Add New Product</a>
            <input type="text" id="searchInput" class="form-control" placeholder="Search for products...">
        </div>

       

        <h2>Out of Stock Products</h2>
        <table class="table" id="outOfStockTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($outOfStockProducts as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td>
                        <form action="" method="POST" class="d-inline">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <input type="number" name="restock_amount" min="1" placeholder="Amount" required>
                            <button type="submit" name="restock" class="btn btn-success btn-sm">Restock</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            var filter = this.value.toLowerCase();
            var rows = document.querySelectorAll('#productTable tbody tr');

            rows.forEach(function(row) {
                var productName = row.cells[0].textContent.toLowerCase();
                if (productName.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
