<?php 
include 'db.php';

// Ensure transaction_number is provided and is valid
if (!isset($_GET['transaction_number']) || empty($_GET['transaction_number'])) {
    echo json_encode(['error' => 'Transaction number is required']);
    exit();
}

$transactionNumber = $_GET['transaction_number'];

// Fetch the transaction details based on the transaction number with quantity_sold > 0
$sql = "SELECT p.id AS product_id, p.name AS product_name, SUM(ed.quantity_sold) AS quantity
        FROM e_receipt_details ed
        JOIN products p ON ed.product_id = p.id
        JOIN e_receipts er ON er.transaction_number = ? 
        WHERE ed.e_receipt_id = er.id
        GROUP BY p.id, p.name
        HAVING quantity > 0";  // This filters out products with quantity sold 0

$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $transactionNumber);  // 's' for string type if transaction_number is a string
$stmt->execute();
$result = $stmt->get_result();

// Check if any products are found
$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

// Return the products as JSON
if (!empty($products)) {
    echo json_encode(['products' => $products, 'transaction_id' => $transactionNumber]);
} else {
    echo json_encode(['error' => 'No products found for this transaction.']);
}
?>
