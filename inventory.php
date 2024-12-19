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

// Handle replenish form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['replenish_quantity'])) {
    $product_id = intval($_POST['product_id']);
    $replenish_quantity = intval($_POST['replenish_quantity']);

    // Update the quantity in the database
    $update_query = "UPDATE products SET quantity = quantity + ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param('ii', $replenish_quantity, $product_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $_SESSION['success_message'] = "Product replenished successfully!";
    } else {
        $_SESSION['error_message'] = "Failed to replenish product.";
    }
    header("Location: inventory.php");
    exit();
}

// Query to fetch the inventory data
$query = "SELECT id, name, barcode, quantity, image, expiry_date, market_price, selling_price, category FROM products ORDER BY id DESC";
$result = $conn->query($query);

$availableProducts = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $availableProducts[] = $row;
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
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
    width: 100%; /* Ensure the table spans the available width */
}

.table th, 
.table td {
    border: none; /* Remove all table borders */
    border-top: 1px solid white; /* Add white line at the top */
    border-left: 1px solid white; /* Add white line to the left */
    border-right: 1px solid white; /* Add white line to the right */
    padding: 10px; /* Add padding for better spacing */
    font-size: 15px; /* Set font size for table cells */
    text-align: center; /* Center text horizontally */
    vertical-align: middle; /* Center text vertically */
    font-weight: bold; /* Ensure text appears bold */
}

.table th:first-child, 
.table td:first-child {
    border-left: none; /* Remove the left border for the first column */
}

.table th:last-child, 
.table td:last-child {
    border-right: none; /* Remove the right border for the last column */
}

.table thead th {
    background-color: #023B86; /* Set header background color */
    color: white; /* Set header text color to white */
    font-weight: 700; /* Make header font bold */
}
.table tbody {
    color: white;
}
.table tbody td{
    color:black;
}
.table tbody tr {
    border-bottom: 1px solid #dee2e6; /* Add subtle horizontal borders */
}

.table tbody tr:hover {
    background-color: #f1f1f1; /* Highlight row on hover */
}

.table img {
    border-radius: 5px; /* Round corners for images */
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
    color: #023B87; /* Primary text color */
    background-color: #ffffff; /* Background color */
    border: 2px solid #023B87; /* Added border with color */
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
                    <div class="d-flex gap-1">
        <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#replenishModal<?php echo $product['id']; ?>">
            Replenish
        </button>
</button>

                    </td>
                </tr>
                <div class="modal fade" id="replenishModal<?php echo $product['id']; ?>" tabindex="-1" aria-labelledby="replenishModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="replenishModalLabel">Replenish Product - <?php echo htmlspecialchars($product['name']); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="" id="replenishForm<?php echo $product['id']; ?>">
                <div class="modal-body">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <!-- Add the product name field -->
                    <input type="hidden" id="product_name<?php echo $product['id']; ?>" value="<?php echo htmlspecialchars($product['name']); ?>">
                    <div class="mb-3">
                        <label for="replenishQuantity<?php echo $product['id']; ?>" class="form-label">Quantity to Replenish</label>
                        <input type="number" class="form-control" id="replenishQuantity<?php echo $product['id']; ?>" name="replenish_quantity" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success btn-sm btn-replenish" 
                        data-bs-toggle="modal" 
                        data-bs-target="#replenishModal<?php echo $product['id']; ?>"
                        data-form-id="replenishForm<?php echo $product['id']; ?>">
                        Replenish
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

                <?php endforeach; ?>
            </tbody>
        </table>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
     document.getElementById('searchInput').addEventListener('keyup', function() {
    var filter = this.value.toLowerCase();
    var rows = document.querySelectorAll('#productTable tbody tr');

    rows.forEach(function(row) {
        var productName = row.cells[0].textContent.toLowerCase();
        var barcode = row.cells[1].textContent.trim(); // Get the exact text without whitespace
        var category = row.cells[4].textContent.toLowerCase();

        // Debugging: Log product name, barcode, and category for each row
        console.log("Product Name:", productName, "Barcode:", barcode, "Category:", category);

        // Check if filter matches product name, category, or barcode (with toString() for barcode)
        if (productName.includes(filter) || category.includes(filter) || barcode.includes(filter)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});


function confirmReplenish(event, formId) {
    event.preventDefault(); // Prevent form submission
    const productId = formId.replace('replenishForm', ''); // Extract product ID from form ID
    const productName = document.getElementById('product_name' + productId).value;
    const replenishQuantity = document.getElementById('replenishQuantity' + productId).value;

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
            // If confirmed, submit the form
            document.getElementById(formId).submit();
        }
    });
}

// Bind the confirmation to each replenish button dynamically
document.querySelectorAll('.btn-replenish').forEach((button) => {
    button.addEventListener('click', function(event) {
        // Get the corresponding form ID for this button
        const formId = button.getAttribute('data-form-id');
        confirmReplenish(event, formId);
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
                window.location.href = 'replenish.php'; // Redirect after OK
            }
        });
    </script>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>
</body>
</html>
