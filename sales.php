<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

include 'sidebar.php';

// Fetch all products with total sold quantity
$query = $conn->query("
    SELECT p.id, p.name, p.quantity, 
           COALESCE(SUM(s.quantity_sold), 0) AS total_sold 
    FROM products p 
    LEFT JOIN sales s ON p.id = s.product_id 
    GROUP BY p.id
");
$products = $query->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $quantity_sold = (int)$_POST['quantity_sold']; // Cast to int for safety

    // Get the product details
    $productQuery = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $productQuery->execute([$product_id]);
    $product = $productQuery->fetch();

    if (!$product) {
        echo "<script>swal('Error!', 'Product not found!', 'error');</script>";
    } elseif ($product['quantity'] >= $quantity_sold) {
        $total = $quantity_sold * $product['price'];

        // Insert into sales table
        $saleQuery = $conn->prepare("INSERT INTO sales (product_id, quantity_sold, total) VALUES (?, ?, ?)");
        $saleQuery->execute([$product_id, $quantity_sold, $total]);

        // Update product quantity
        $updateQuery = $conn->prepare("UPDATE products SET quantity = quantity - ? WHERE id = ?");
        $updateQuery->execute([$quantity_sold, $product_id]);

        echo "<script>swal('Success!', 'Sale processed successfully! Total: $$total', 'success');</script>";
    } else {
        echo "<script>swal('Warning!', 'Not enough stock!', 'warning');</script>";
    }
}

?>



<div class="main-content">
        <h2>Process Sale</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="product">Product:</label>
                <select name="product_id" id="product" class="form-select" required onchange="updateQuantity()">
                    <option value="" disabled selected>Select a product</option>
                    <?php foreach ($products as $product): ?>
                    <option value="<?php echo $product['id']; ?>" data-quantity="<?php echo $product['quantity']; ?>">
                        <?php echo $product['name']; ?> 
                        (Available Stock: <?php echo $product['quantity']; ?>, Total Sold: <?php echo $product['total_sold']; ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="quantity_sold">Quantity Sold:</label>
                <input type="number" name="quantity_sold" id="quantity_sold" class="form-control" required min="1" readonly>
            </div>
            <button type="submit" class="btn btn-primary">Process Sale</button>
        </form>
    </div>
