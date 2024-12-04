<?php
session_start();
include 'db.php'; // Database connection

if (!isset($_GET['receipt_id'])) {
    echo json_encode(['success' => false, 'message' => 'Transaction number is required.']);
    exit();
}

$receiptId = $_GET['receipt_id'];

// Fetch transaction details
$query = $conn->prepare("
    SELECT er.id AS receipt_id, er.sale_date, er.total_amount, er.transaction_number,
           erd.product_id, erd.quantity_sold, erd.total, p.name AS product_name
    FROM e_receipts er 
    JOIN e_receipt_details erd ON er.id = erd.e_receipt_id 
    JOIN products p ON erd.product_id = p.id 
    WHERE er.id = ?
");
$query->bind_param("i", $receiptId);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    $products = [];
    $transactionData = $result->fetch_assoc();

    // Save transaction details before the loop
    $transactionNumber = $transactionData['transaction_number'];
    $saleDate = $transactionData['sale_date'];
    $totalAmount = $transactionData['total_amount'];

    do {
        $products[] = [
            'product_name' => $transactionData['product_name'],
            'quantity_sold' => $transactionData['quantity_sold'],
            'total' => $transactionData['total']
        ];
    } while ($transactionData = $result->fetch_assoc());

    // Output transaction details
    echo json_encode([
        'success' => true,
        'transaction_number' => $transactionNumber,
        'sale_date' => $saleDate,
        'total_amount' => $totalAmount,
        'products' => $products
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'No transaction found.']);
}
?>
