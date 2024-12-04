<?php
// Database connection
$host = 'localhost';
$db_name = 'canteenms';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to fetch categories
    $stmt = $conn->prepare("SELECT name FROM categories"); // Adjust table name if needed
    $stmt->execute();

    // Fetch categories
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return JSON response
    echo json_encode($categories);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Connection failed: ' . $e->getMessage()]);
}
?>
