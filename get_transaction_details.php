<?php 
include 'db.php';

// Ensure transaction_number is provided and is valid
if (!isset($_GET['transaction_number']) || empty($_GET['transaction_number'])) {
    echo json_encode(['error' => 'Transaction number is required']);
    exit();
}

$transaction_number = $_GET['transaction_number'];
$response = [];

// Fetch transaction details
$transactionQuery = $conn->prepare("SELECT total_amount FROM e_receipts WHERE transaction_number = ?");
$transactionQuery->bind_param("s", $transaction_number);
$transactionQuery->execute();
$transactionResult = $transactionQuery->get_result();

if ($transactionResult->num_rows > 0) {
    $transactionData = $transactionResult->fetch_assoc();

    // Fetch product details
    $productsQuery = $conn->prepare("
        SELECT p.name AS product_name, erd.quantity_sold, erd.total
        FROM e_receipt_details erd 
        JOIN products p ON erd.product_id = p.id 
        WHERE erd.e_receipt_id = (SELECT id FROM e_receipts WHERE transaction_number = ?)
    ");
    $productsQuery->bind_param("s", $transaction_number);
    $productsQuery->execute();
    $productsResult = $productsQuery->get_result();

    $products = [];
    while ($product = $productsResult->fetch_assoc()) {
        $products[] = $product;
    }

    $response = [
        'total_amount' => $transactionData['total_amount'],
        'products' => $products
    ];
} else {
    // If no transaction found, return empty products
    $response = [
        'total_amount' => 0,
        'products' => []
    ];
}

echo json_encode($response);

?>
