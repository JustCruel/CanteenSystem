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

// Check if the replenish form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = $_POST['product_id'];
    $replenishQuantity = $_POST['replenish_quantity'];

    // Validate input: ensure replenishQuantity is a valid positive integer
    if (filter_var($replenishQuantity, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) === false) {
        // Invalid input
        $_SESSION['error_message'] = 'Invalid quantity entered. Please enter a valid positive number.';
        header('Location: replenish.php');
        exit();
    }

    // Fetch the current quantity and name of the product
    $query = "SELECT name, quantity FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $productId);
    $stmt->execute();
    $stmt->bind_result($productName, $currentQuantity);
    $stmt->fetch();
    $stmt->close();

    if ($currentQuantity !== null) {
        // Calculate the new quantity
        $newQuantity = $currentQuantity + $replenishQuantity;

        // Update the product quantity in the database
        $updateQuery = "UPDATE products SET quantity = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param('ii', $newQuantity, $productId);
        if ($updateStmt->execute()) {
            // Set success message with product name and replenish quantity
            $_SESSION['success_message'] = "Successfully added $replenishQuantity units to $productName. The new quantity is $newQuantity.";
        } else {
            $_SESSION['error_message'] = 'Failed to replenish stock. Please try again.';
        }
        $updateStmt->close();
    } else {
        // Product not found
        $_SESSION['error_message'] = 'Product not found.';
    }

    // Redirect back to the replenish page to display SweetAlert
    header('Location: replenish.php');
    exit();
} else {
    // If accessed directly, redirect to the replenish page
    header('Location: replenish.php');
    exit();
}
?>
