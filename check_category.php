<?php
include 'db.php'; // Include your database connection

if (isset($_GET['name'])) {
    $name = trim($_GET['name']);
    $stmt = $conn->prepare("SELECT * FROM categories WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['exists' => true]); // Category exists
    } else {
        echo json_encode(['exists' => false]); // Category does not exist
    }
} else {
    echo json_encode(['exists' => false]); // No name provided
}
?>
