<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php'; // Database connection

// Fetch categories from the database
$categories_query = $conn->query("SELECT * FROM categories");
$categories = [];
while ($row = $categories_query->fetch_array(MYSQLI_ASSOC)) {
    $categories[] = $row; // Collect each row into an array
}

$productAdded = false;
$productExists = false; // Flag to check if product exists
$categoryAdded = false; // Flag to check if a new category was added
$categoryExists = false; // Flag to check if category already exists
$errorMessage = ""; // Variable for error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and process product form data
    $name = trim($_POST['name']); // Product Name
    $barcode = trim($_POST['barcode']); // Correctly capturing barcode
    $market_price = trim($_POST['market_price']);
    $selling_price = trim($_POST['selling_price']);
    $quantity = trim($_POST['quantity']);
    $expiry = $_POST['expiry'];
    $category = $_POST['category']; // Capture the selected category

    // Check if the product already exists
    $checkProductQuery = $conn->prepare("SELECT * FROM products WHERE name = ?");
    $checkProductQuery->bind_param("s", $name);
    $checkProductQuery->execute();
    $result = $checkProductQuery->get_result();

    if ($result->num_rows > 0) {
        // Product exists
        $productExists = true; // Set the flag to true
    } else {
        // Validate and upload image
        $target_dir = "assets/images/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // If 'add_new' is selected, handle the new category logic
            if ($category === 'add_new') {
                // Collect new category name
                $category_name = isset($_POST['new_category_name']) ? trim($_POST['new_category_name']) : '';

                // Ensure category_name is not empty
                if (!empty($category_name)) {
                    // Check if category already exists
                    $checkCategoryQuery = $conn->prepare("SELECT * FROM categories WHERE name = ?");
                    $checkCategoryQuery->bind_param("s", $category_name);
                    $checkCategoryQuery->execute();
                    $categoryResult = $checkCategoryQuery->get_result();

                    if ($categoryResult->num_rows > 0) {
                        // Category already exists, show a SweetAlert
                        $categoryExists = true; // Flag to indicate category exists
                    } else {
                        // Insert the new category into the database
                        $insertCategoryQuery = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
                        $insertCategoryQuery->bind_param("s", $category_name);
                        if ($insertCategoryQuery->execute()) {
                            $categoryAdded = true; // Set flag for new category added
                            // Use the newly added category for product insertion
                            $category = $category_name; 
                        }
                    }
                } else {
                    $errorMessage = "New category name cannot be empty!";
                }
            } // End of 'add_new' check

            // Check if the category exists if it's not 'add_new'
            if ($categoryExists) {
                $errorMessage = "The selected category already exists!";
            }

            // Get the image name before inserting into the database
            $image_name = basename($_FILES["image"]["name"]); // Store in a variable

            // Insert the product along with the category into the database
            if (!$errorMessage) {
                $query = $conn->prepare("INSERT INTO products (barcode, name, market_price, selling_price, quantity, image, expiry_date, category) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $query->bind_param("ssssssss", $barcode, $name, $market_price, $selling_price, $quantity, $image_name, $expiry, $category); // Correct binding
                if ($query->execute()) {
                    $productAdded = true; // Set flag for product added
                } else {
                    $errorMessage = "Failed to add the product.";
                }
            }
        } else {
            $errorMessage = "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<?php include 'sidebar.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    /* Set the border color for textboxes */
input[type="text"], input[type="number"], input[type="date"], select {
    border: 1px solid #023B87;
    padding: 8px;
    font-size: 16px;
    color: #333;
    box-shadow: 1px 2px 10px rgba(0, 0, 0, 0.1);
}

/* Set the border color and background for the submit button */
button[type="submit"], .btn.btn-primary, .btn.btn-secondary { 
    background-color: #023B87;
    border: 2px solid #023B87;
    color: white;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
}

/* Change the border and background color on hover for the button */
button[type="submit"]:hover, .btn.btn-primary:hover {
    background-color: #01A4E0;
    border-color: #01A4E0;
}

</style>
<div class="main-content">
<a href="inventory.php" class="btn btn-secondary">Back to Inventory</a>
    <h2>Add New Product</h2>
    <form id="productForm" method="POST" enctype="multipart/form-data">
    <label for="barcode">Barcode:</label>
<input type="text" name="barcode" id="barcode" required>


        <label for="name">Product Name:</label>
        <input type="text" name="name" id="productName" required>
        
        <label for="market_price">Market Price:</label>
        <input type="number" step="1" name="market_price"  id="market_price" value="1" required>
        
        <label for="selling_price">Selling Price:</label>
        <input type="number" step="1" name="selling_price" id="selling_price" value="1" required>
        
        <label for="quantity">Product Quantity:</label>
        <input type="number" name="quantity" value="1" required>
        
        <label for="expiry">Expiry Date:</label>
<input type="date" name="expiry" id="expiry" required min="<?php echo date('Y-m-d'); ?>">


        <label for="category">Category:</label>
        <select name="category" id="categorySelect" required>
            <option value="">Select Category</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo htmlspecialchars($category['name']); ?>">
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
            <?php endforeach; ?>
            <option value="add_new">Add New Category</option>
        </select>
        
        <label for="new_category_name" id="newCategoryLabel" style="display:none;">New Category Name:</label>
        <input type="text" name="new_category_name" id="newCategoryName" style="display:none;">

        <label for="image">Upload Product Image:</label>
        <input type="file" name="image" accept="image/*" required>
        
        <button type="submit" class="btn btn-primary">Add Product</button>
        
    </form>

    <script>
        // Show confirmation dialog before form submission
        document.getElementById('productForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to add this product?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, add it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit(); // Submit the form if confirmed
                }
            });
        });


      
    
        // Show alert based on PHP variables
        <?php if ($productExists): ?>
            Swal.fire({
                icon: 'warning',
                title: 'Product Exists',
                text: 'The product "<?php echo htmlspecialchars($name); ?>" already exists!',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        <?php elseif ($errorMessage): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '<?php echo htmlspecialchars($errorMessage); ?>',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        <?php elseif ($productAdded): ?>
            Swal.fire({
                icon: 'success',
                title: 'Product Added',
                text: 'The product has been successfully added!',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'inventory.php';
                }
            });
        <?php elseif ($categoryAdded): ?>
            Swal.fire({
                icon: 'success',
                title: 'Category Added',
                text: 'The category has been successfully added!',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>

        // Show new category input if "Add New Category" is selected
        document.getElementById('categorySelect').addEventListener('change', function() {
            const newCategoryInput = document.getElementById('newCategoryName');
            const newCategoryLabel = document.getElementById('newCategoryLabel');

            if (this.value === 'add_new') {
                newCategoryInput.style.display = 'block';
                newCategoryLabel.style.display = 'block';
            } else {
                newCategoryInput.style.display = 'none';
                newCategoryLabel.style.display = 'none';
            }
        });



       // Check for existing product as user types
document.getElementById('barcode').addEventListener('input', function() {
    const barcode = this.value;

    // Perform AJAX request to check if the product exists
    if (barcode.length > 0) {
        fetch('check_barcode.php?name=' + encodeURIComponent(barcode))
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    // Clear the barcode input
                    this.value = ''; // Clear the input field

                    // Show warning alert
                    Swal.fire({
                        icon: 'warning',
                        title: 'Barcode Exists',
                        text: 'The product with barcode: "' + barcode + '" already exists!',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                }
            });
    }
});


document.addEventListener('DOMContentLoaded', function() {
    const sellingPriceInput = document.getElementById('selling_price');
    const marketPriceInput = document.getElementById('market_price');
    let alertShown = false; // Flag to track if the alert has been shown

    if (sellingPriceInput && marketPriceInput) {
        // Check when the input loses focus
        sellingPriceInput.addEventListener('blur', function() {
            const selling_price = parseFloat(this.value); // Parse the selling price as a float
            const market_price = parseFloat(marketPriceInput.value); // Get the market price value

            // Check if selling price is less than market price
            if (selling_price < market_price && !alertShown) {
                alertShown = true; // Set flag to true when alert is shown
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'Selling price cannot be less than the market price!',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Reset the flag when the alert is closed
                    alertShown = false;
                    sellingPriceInput.focus(); // Refocus on selling price input
                });
            } 
            // Check if selling price is equal to market price
            else if (selling_price === market_price && !alertShown) {
                alertShown = true; // Set flag to true when alert is shown
                Swal.fire({
                    icon: 'info', // Use info icon for equal case
                    title: 'Notice',
                    text: 'Selling price is equal to the market price!',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Reset the flag when the alert is closed
                    alertShown = false;
                    sellingPriceInput.focus(); // Refocus on selling price input
                });
            }
        });
    } else {
        console.error("Element(s) not found");
    }
});



          // Check for existing product as user types
          document.getElementById('productName').addEventListener('input', function() {
            const productName = this.value;

            // Perform AJAX request to check if the product exists
            if (productName.length > 0) {
                fetch('check_product.php?name=' + encodeURIComponent(productName))
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Product Exists',
                                text: 'The product "' + productName + '" already exists!',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
            }
        });

        document.getElementById('newCategoryName').addEventListener('input', function() {
            const newCategoryName = this.value;

            // Perform AJAX request to check if the category exists
            if (newCategoryName.length > 0) {
                fetch('check_category.php?name=' + encodeURIComponent(newCategoryName))
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Category Exists',
                                text: 'The category "' + newCategoryName + '" already exists!',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
            }
        });
    </script>
</div>
