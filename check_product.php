<?php
include 'db.php'; // Include your database connection

if (isset($_GET['name'])) {
    $name = trim($_GET['name']);
    $stmt = $conn->prepare("SELECT * FROM products WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['exists' => true]); // Product exists
    } else {
        echo json_encode(['exists' => false]); // Product does not exist
    }
} else {
    echo json_encode(['exists' => false]); // No name provided
}
?>
