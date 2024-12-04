<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set content type to JSON
header('Content-Type: application/json');

// Database connection parameters
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'canteenms');

// Establish database connection
$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($connection->connect_error) {
    echo json_encode(['error' => 'Connection failed: ' . $connection->connect_error]);
    exit;
}

// Prepare the base SQL query
$sql = "SELECT * FROM products"; // Default query
$where_conditions = []; // Array to hold the conditions
$params = []; // Array to hold parameter values
$types = ""; // String to hold parameter types

// Validate and check if category is set and not 'all'
if (isset($_GET['category']) && $_GET['category'] !== 'all') {
    $category = $connection->real_escape_string($_GET['category']); // Sanitize input
    $where_conditions[] = "category = ?"; // Add category condition
    $params[] = $category;
    $types .= "s"; // Assuming category is a string
}

// Validate and check if search query is set
if (isset($_GET['search'])) {
    $search = '%' . $connection->real_escape_string($_GET['search']) . '%'; // Sanitize input
    $where_conditions[] = "name LIKE ?"; // Add search condition for name
    $params[] = $search; // Prepare search query
    $types .= "s"; // Assuming name is a string
}

// If there are any conditions, add them to the SQL query
if (!empty($where_conditions)) {
    $sql .= " WHERE " . implode(' AND ', $where_conditions); // Join conditions with AND
}

// Prepare the statement
$stmt = $connection->prepare($sql);

// Check if prepare was successful
if ($stmt === false) {
    echo json_encode(['error' => 'Prepare failed: ' . $connection->error]);
    exit;
}

// Bind parameters if there are any
if ($params) {
    $stmt->bind_param($types, ...$params);
}

// Execute the statement
if ($stmt->execute()) {
    $result = $stmt->get_result(); // Get the result set

    // Initialize products array
    $products = [];

    if ($result->num_rows > 0) {
        while ($product = $result->fetch_assoc()) {
            $products[] = $product;
        }
        echo json_encode($products); // Return products as JSON
    } else {
        echo json_encode([]); // Return an empty array if no products found
    }
} else {
    echo json_encode(['error' => 'Query error: ' . $stmt->error]); // Handle query error
}

// Close the statement and connection
$stmt->close();
$connection->close();
?>
