<?php  
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php'; // Assumes you're using mysqli connection in db.php

// Initialize variables
$product = null;
$error_message = null;
$update_success = false;
$no_change = false; // Flag for no change

// Check if a product ID is provided
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Fetch product details by ID
    $query = "SELECT id, barcode, name, quantity, image, expiry_date, market_price, selling_price, category FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // If product is found
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Product not found!";
        exit();
    }
} else {
    echo "No product ID provided!";
    exit();
}

// Handle the form submission to update the product details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = $_POST['name'];
    $new_selling_price = $_POST['selling_price'];

    // Check if the new product name already exists
    $check_query = "SELECT id FROM products WHERE name = ? AND id != ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("si", $new_name, $product_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $error_message = "The product name already exists. Please choose a different name.";
    } else {
        // Initialize variable for the image
        $new_image = $product['image']; // Keep the old image by default

        // Check if a new image file is uploaded
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            // Define the target directory and file path
            $target_dir = "assets/images/";
            $new_image_name = basename($_FILES["image"]["name"]);
            $target_file = $target_dir . $new_image_name;

            // Validate file type
            $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($fileType, $allowedTypes)) {
                // Move the uploaded file to the target directory
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $new_image = $new_image_name; // Store just the image name for the database
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            } else {
                echo "Only JPG, JPEG, PNG & GIF files are allowed.";
            }
        }

        // Check if there are any changes before updating
        if ($new_name != $product['name'] || $new_selling_price != $product['selling_price'] || $new_image != $product['image']) {
            // Update query to modify the product details
            $update_query = "UPDATE products SET name = ?, selling_price = ?, image = ? WHERE id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("sdsi", $new_name, $new_selling_price, $new_image, $product_id);

            if ($stmt->execute()) {
                $_SESSION['update_success'] = true; // Set a success session variable
            } else {
                echo "Error updating product!";
            }
        } else {
            $no_change = true; // Set the flag to true if no change is detected
        }
    }
}

 // Include sidebar
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="main-content">
<a href="inventory.php" class="btn btn-secondary">Back to Inventory</a>
    <h2>Edit Product: <?php echo htmlspecialchars($product['name']); ?></h2>
    <form id="editForm" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
            <label for="barcode" class="form-label">Barcode</label>
            <input type="text" class="form-control" id="barcode" name="barcode" value="<?php echo htmlspecialchars($product['barcode']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="market_price" class="form-label">Market Price</label>
            <input type="text" class="form-control" id="market_price" value="<?php echo htmlspecialchars($product['market_price']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="text" class="form-control" id="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label for="expiry_date" class="form-label">Expiry Date</label>
            <input type="date" class="form-control" id="expiry_date" value="<?php echo htmlspecialchars($product['expiry_date']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label for="selling_price" class="form-label">Selling Price</label>
            <input type="number" step="0.01" class="form-control" id="selling_price" name="selling_price" value="<?php echo htmlspecialchars($product['selling_price']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Product Image</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
            <img src="assets/images/<?php echo htmlspecialchars($product['image']); ?>" alt="Current Image" class="img-thumbnail mt-2" width="150">
        </div>
        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>
</div>

<script>
    // Attach a submit event to the form
    document.getElementById('editForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the form from submitting immediately

        // Show a confirmation alert
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to update the product details?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, update it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form if confirmed
                this.submit();
            } else {
              // If canceled, don't submit the form
            }
        });
    });

    <?php if ($error_message): ?>
    // Show error alert if there is an error
    Swal.fire({
        icon: 'error',
        title: 'Product Name Exists',
        text: '<?php echo addslashes($error_message); ?>',
        confirmButtonText: 'OK'
    });
    <?php endif; ?>

    <?php if (isset($_SESSION['update_success'])): ?>
Swal.fire({
    icon: 'success',
    title: 'Success!',
    text: 'Product has been successfully updated.',
    confirmButtonText: 'OK'
}).then(() => {
    window.location.href = 'inventory.php'; // Redirect after the alert is confirmed
});
<?php unset($_SESSION['update_success']); // Remove session variable after use ?>
<?php endif; ?>

    <?php if ($no_change): ?>
    // Show a notification if no changes were made
    Swal.fire({
        icon: 'info',
        title: 'No Changes Detected',
        text: 'No changes were made to the product details.',
        confirmButtonText: 'OK'
    });
    <?php endif; ?>
</script>
</body>
</html>
