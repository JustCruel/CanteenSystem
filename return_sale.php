<?php
include 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

// Check if 'transaction_number' is provided
if (!isset($data['transaction_number']) || empty($data['transaction_number'])) {
    echo json_encode(['error' => 'Transaction number is required']);
    exit;
}

$transactionNumber = $data['transaction_number'];
$products = $data['products'];

foreach ($products as $product) {
    $productId = $product['product_id'];
    $returnQuantity = $product['quantity'];

    // Update inventory to restore quantity
    $sql = "UPDATE products SET quantity = quantity + ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $returnQuantity, $productId);
    if (!$stmt->execute()) {
        error_log("Failed to update inventory for product ID: $productId, Error: " . $stmt->error);
        continue; // Skip to the next product on error
    }

    // Update balance if paid via RFID (Optional)
    $sql = "SELECT paymethod, rfid_code FROM e_receipts WHERE transaction_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $transactionNumber); // Bind string for transaction_number
    $stmt->execute();
    $receiptResult = $stmt->get_result()->fetch_assoc();

    if (isset($receiptResult['paymethod']) && $receiptResult['paymethod'] == 'RFID') {
        $rfidCode = $receiptResult['rfid_code'];

        // Retrieve the product price for the return
        $sql = "SELECT selling_price FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $productId);
        $stmt->execute();
        $productPriceResult = $stmt->get_result()->fetch_assoc();

        if ($productPriceResult) {
            $productPrice = $productPriceResult['selling_price'];
            $returnAmount = $returnQuantity * $productPrice; // Calculate return amount

            // Update user balance
            $sql = "UPDATE user SET balance = balance + ? WHERE rfid_code = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('di', $returnAmount, $rfidCode); // Bind string for RFID code
            if (!$stmt->execute()) {
                error_log("Failed to update balance for RFID code: $rfidCode, Error: " . $stmt->error);
            }
        } else {
            error_log("Product price not found for product ID: $productId");
        }
    } else {
        error_log("Paymethod not found or not RFID for transaction number: $transactionNumber");
    }

    // Insert the return record into return_sale table
    $sql = "INSERT INTO return_sale (transaction_number, product_id, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sii', $transactionNumber, $productId, $returnQuantity); // Bind string for transaction_number
    if (!$stmt->execute()) {
        error_log("Failed to insert return record for product ID: $productId, Error: " . $stmt->error);
    }

    // Update the quantity in e_receipt_details table (decrease by returned quantity)
    $sql = "UPDATE e_receipt_details SET quantity_sold = quantity_sold - ? WHERE transaction_number = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sii', $returnQuantity, $transactionNumber, $productId); // Bind string for transaction_number
    if (!$stmt->execute()) {
        error_log("Failed to update quantity_sold in e_receipt_details for product ID: $productId, Error: " . $stmt->error);
    }

    // Check if the quantity has become zero, then delete from e_receipt_details
    $sql = "SELECT quantity_sold FROM e_receipt_details WHERE transaction_number = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $transactionNumber, $productId); // Bind string for transaction_number
    $stmt->execute();
    $productQuantityResult = $stmt->get_result()->fetch_assoc();

    if ($productQuantityResult['quantity_sold'] <= 0) {
        // Delete from e_receipt_details if quantity is zero or less
        $sql = "DELETE FROM e_receipt_details WHERE transaction_number = ? AND product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $transactionNumber, $productId); // Bind string for transaction_number
        if (!$stmt->execute()) {
            error_log("Failed to delete from e_receipt_details for product ID: $productId, Error: " . $stmt->error);
        }
    }

    // Check if there are any remaining records in e_receipt_details for this transaction
    $sql = "SELECT COUNT(*) as count FROM e_receipt_details WHERE transaction_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $transactionNumber); // Bind string for transaction_number
    $stmt->execute();
    $countResult = $stmt->get_result()->fetch_assoc();

    if ($countResult['count'] == 0) {
        // Only delete from e_receipts if there are no remaining details
        $sql = "DELETE FROM e_receipts WHERE transaction_number = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $transactionNumber); // Bind string for transaction_number
        if (!$stmt->execute()) {
            error_log("Failed to delete from e_receipts for transaction number: $transactionNumber, Error: " . $stmt->error);
        }
    }
}

// Return success response
echo json_encode(['success' => true]);
?>
