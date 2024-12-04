<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php'; // Assumes you're using mysqli connection in db.php

// Check if a product ID is provided
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Fetch product details by ID
    $query = "SELECT id, name, quantity, image, expiry_date, market_price, selling_price, category FROM products WHERE id = ?";
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

// Handle the form submission to update the selling price
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_selling_price = $_POST['selling_price'];

    // Update query to modify only the selling price
    $update_query = "UPDATE products SET selling_price = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("di", $new_selling_price, $product_id);

    if ($stmt->execute()) {
        $_SESSION['update_success'] = true; // Set a success session variable
        header("Location: inventory.php"); // Redirect back to inventory
        exit();
    } else {
        echo "Error updating product!";
    }
}

include 'sidebar.php'; // Include sidebar
?>
