<?php
include 'db.php'; // Add your DB connection details here

// Fetch past transactions with products sold (quantity_sold > 0), ordered by the most recent transaction time
$sql = "SELECT DISTINCT er.transaction_number, er.sale_date
        FROM e_receipts er
        JOIN e_receipt_details ed ON er.id = ed.e_receipt_id
        WHERE ed.quantity_sold > 0
        ORDER BY er.sale_date DESC"; // Order by the transaction creation time

$result = $conn->query($sql);

$transactions = [];
while ($row = $result->fetch_assoc()) {
    $transactions[] = $row;
}

// Return the transactions as JSON
echo json_encode($transactions);
?>
