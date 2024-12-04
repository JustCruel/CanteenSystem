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
$query = "SELECT id, name,barcode, quantity, image, expiry_date, market_price, selling_price, category FROM products ORDER BY id DESC";

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

include 'sidebar.php'; // Include the sidebar
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap">
    <style>
        /* Custom styles */
        body {
            font-family: 'DM Sans', sans-serif,bold; /* Set the font for the body */
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
            font-size: 14px; /* Set font size for table cells */
            text-align: center; /* Center text horizontally */
            vertical-align: middle; /* Center text vertically */
            font-weight: bold;
            font-size: 15px;
        }
        .table thead th {
            background-color: #007bff;
            color: white;
            border-bottom: 2px solid #007bff; /* Add a horizontal line below the header */
            font-weight: 700; /* Make header font bold */
        }
        .table tbody tr {
            border-bottom: 1px solid #dee2e6; /* Add horizontal borders */
        }
        .table tbody tr:hover {
            background-color: #f1f1f1;
        }
        .table img {
            border-radius: 5px;
        }
        .alert {
            margin-bottom: 20px;
        }
        #searchInput {
            width: 300px;
            margin-left: 10px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 0;
            font-size: 16px;
            font-weight: 600;
            color: white;
            background-color: #007bff; /* Primary color */
            border: none;
            border-radius: 5px;
            text-decoration: none; /* Remove underline from link */
            transition: background-color 0.3s, transform 0.2s;
        }
        .btn:hover {
            background-color: #0056b3; /* Darker shade for hover */
            transform: translateY(-2px); /* Slight lift on hover */
        }
        .btn:active {
            background-color: #004494; /* Even darker shade when active */
            transform: translateY(0); /* Reset on click */
        }
        .table tbody {
            display: block;
            height: 600px; /* Set a fixed height for the table body */
            overflow-y: scroll; /* Enable vertical scrolling */
            width: 100%; /* Ensure the table takes up the full width */
        }
        .table thead, .table tbody tr {
            display: table;
            width: 100%; /* Ensure that the header and rows align */
            table-layout: fixed; /* Make sure the table layout is consistent */
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="inventory.php" class="btn btn-primary">Back to inventory</a>
            <input type="text" id="searchInput" class="form-control" placeholder="Search for products...">
        </div>

        <h2>Inventory</h2>

        <?php
        // Initialize an array for low stock product names
        $lowStockProducts = [];

        // Check for low stock products
        foreach ($availableProducts as $product) {
            if ($product['quantity'] < 20) {
                $lowStockProducts[] = htmlspecialchars($product['name']); // Add the product name to the array
            }
        }

        // Display a low stock alert if necessary
        if (!empty($lowStockProducts)) {
            $lowStockList = implode(", ", $lowStockProducts); // Create a string from the array
            echo '<div class="alert alert-warning" role="alert">
                    Warning: The following products have low stock (below 20): ' . $lowStockList . '.
                  </div>';
        }
        ?>

        <table class="table" id="productTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Barcode</th>
                    <th>Market Price</th>
                    <th>Selling Price</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Expiry Date</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($availableProducts as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td><?php echo htmlspecialchars($product['barcode']); ?></td>
                    <td><?php echo htmlspecialchars($product['market_price']); ?></td>
                    <td><?php echo htmlspecialchars($product['selling_price']); ?></td>
                    <td><?php echo htmlspecialchars($product['category']); ?></td>
                    <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($product['expiry_date']); ?></td>
                    <td>
                        <?php if ($product['image']): ?>
                            <img src="<?php echo 'assets/images/' . htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" width="50">
                        <?php else: ?>
                            No Image
                        <?php endif; ?>
                    </td>
                    <td>
                
                        <button class="btn btn-success btn-sm replenish-btn" data-id="<?php echo $product['id']; ?>" data-name="<?php echo htmlspecialchars($product['name']); ?>">Replenish</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Replenish Modal -->
        <div class="modal fade" id="replenishModal" tabindex="-1" aria-labelledby="replenishModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="replenishModalLabel">Replenish Stock</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="replenishForm" method="POST" action="replenish_stock.php">
                            <input type="hidden" name="product_id" id="product_id">
                            <div class="mb-3">
                                <label for="product_name" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="product_name" name="product_name" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="replenish_quantity" class="form-label">Quantity to Add</label>
                                <input type="number" class="form-control" id="replenish_quantity" name="replenish_quantity" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Replenish</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
    // Show replenish modal with product details
    document.querySelectorAll('.replenish-btn').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-id');
            const productName = this.getAttribute('data-name');
            document.getElementById('product_id').value = productId;
            document.getElementById('product_name').value = productName;
            new bootstrap.Modal(document.getElementById('replenishModal')).show();
        });
    });

    // Implement search functionality for both product name and category
    document.getElementById('searchInput').addEventListener('input', function () {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('#productTable tbody tr');

        rows.forEach(row => {
            const productName = row.querySelector('td:first-child').textContent.toLowerCase(); // Get product name
            const productCategory = row.querySelector('td:nth-child(4)').textContent.toLowerCase(); // Get product category

            // Show the row if either the product name or category matches the search input
            if (productName.includes(searchValue) || productCategory.includes(searchValue)) {
                row.style.display = ''; // Show row
            } else {
                row.style.display = 'none'; // Hide row
            }
        });
    });

    // SweetAlert confirmation before form submission
    document.getElementById('replenishForm').addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent the default form submission

        const productName = document.getElementById('product_name').value;
        const replenishQuantity = document.getElementById('replenish_quantity').value;

        Swal.fire({
            title: `Confirm Replenishment`,
            text: `Are you sure you want to add ${replenishQuantity} units to ${productName}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, replenish it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form if confirmed
                this.submit();
            }
        });
    });
</script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (isset($_SESSION['success_message'])): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Stock Replenished',
            text: '<?php echo $_SESSION['success_message']; ?>',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'inventory.php'; // Redirect after OK
            }
        });
    </script>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '<?php echo $_SESSION['error_message']; ?>',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'inventory.php'; // Redirect after OK
            }
        });
    </script>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>
    </div>
</body>
</html>
