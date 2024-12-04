<?php 
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include your database connection
include 'db.php'; 

// Check if the database connection was successful
if (!$conn) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['success' => false, 'error' => 'Database connection failed.']);
    exit;
}

// Check if the barcode parameter is set
if (isset($_GET['barcode'])) {
    $barcode = trim($_GET['barcode']); // Trim whitespace

    // Validate the barcode
    if (strlen($barcode) < 1) {
        http_response_code(400); // Bad Request
        echo json_encode(['success' => false, 'error' => 'Invalid barcode format.']);
        exit;
    }

    // Prepare and execute the SQL query
    $stmt = $conn->prepare("SELECT id, name, selling_price, quantity FROM products WHERE barcode = ?");

    // Check for statement preparation failure
    if (!$stmt) {
        http_response_code(500); // Internal Server Error
        echo json_encode(['success' => false, 'error' => 'Statement preparation failed: ' . $conn->error]);
        exit;
    }

    // Bind parameters
    $stmt->bind_param("s", $barcode);
    
    // Execute the statement
    if (!$stmt->execute()) {
        http_response_code(500); // Internal Server Error
        echo json_encode(['success' => false, 'error' => 'Query execution failed: ' . $stmt->error]);
        exit;
    }

    // Get the result
    $result = $stmt->get_result();

    // Set the content type to JSON
    header('Content-Type: application/json');

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        echo json_encode(['success' => true, 'product' => $product]); // Return product data as JSON
    } else {
        http_response_code(404); // Not Found
        echo json_encode(['success' => false, 'error' => 'Product not found.']);
    }

    // Close the statement
    $stmt->close();
} else {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'error' => 'Invalid request.']);
}

// Close the database connection
$conn->close();
?>
