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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and process product form data
    $name = trim($_POST['name']);
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
            // Insert new category if it doesn't exist
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
                        // Category already exists, set a flag
                        echo "<script>Swal.fire({
                            icon: 'warning',
                            title: 'Category Exists',
                            text: 'The category \"".$category_name."\" already exists!',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        });</script>";
                        return; // Prevent further execution
                    }

                    // Insert the new category into the database
                    $insertCategoryQuery = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
                    $insertCategoryQuery->bind_param("s", $category_name);
                    $insertCategoryQuery->execute();
                    $categoryAdded = true; // Set flag for new category added
                } else {
                    // Handle the case where the new category name is empty
                    echo "<script>Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'New category name cannot be empty!',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });</script>";
                    return; // Prevent further execution
                }
            }

            // Get the image name before inserting into the database
            $image_name = basename($_FILES["image"]["name"]); // Store in a variable

            // Insert the product along with the category into the database
            $query = $conn->prepare("INSERT INTO products (name, market_price, selling_price, quantity, image, expiry_date, category) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $query->bind_param("sssssss", $name, $market_price, $selling_price, $quantity, $image_name, $expiry, $category); // Use the variable
            if ($query->execute()) {
                $productAdded = true; // Set flag for product added
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<?php include 'sidebar.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="main-content">
    <h2>Add New Product</h2>
    <form id="productForm" method="POST" enctype="multipart/form-data">
        <label for="name">Product Name:</label>
        <input type="text" name="name" required>
        
        <label for="market_price">Market Price:</label>
        <input type="number" step="1" name="market_price" value="1" required>
        
        <label for="selling_price">Selling Price:</label>
        <input type="number" step="1" name="selling_price" value="1" required>
        
        <label for="quantity">Product Quantity:</label>
        <input type="number" name="quantity" value="1" required>
        
        <label for="expiry">Expiry Date:</label>
        <input type="date" name="expiry" required>

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
        <a href="inventory.php" class="btn btn-secondary">Back to Inventory</a>
    </form>

    <script>
        // Show alert based on PHP variables
        <?php if ($productExists): ?>
            Swal.fire({
                icon: 'warning',
                title: 'Product Exists',
                text: 'The product "<?php echo htmlspecialchars($name); ?>" already exists!',
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
                newCategoryInput.style.display = "block";
                newCategoryLabel.style.display = "block";
            } else {
                newCategoryInput.style.display = "none";
                newCategoryLabel.style.display = "none";
            }
        });

        // Add confirmation before submitting the form
        document.getElementById('productForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to add this product?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, add it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit(); // Programmatically submit the form
                }
            });
        });
    </script>
</div>

<style>
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1000; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }
    .modal-content {
        background-color: #fefefe;
        margin: 15% auto; /* 15% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        width: 80%; /* Could be more or less, depending on screen size */
    }
    .close-button {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }
    .close-button:hover,
    .close-button:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
</style>
