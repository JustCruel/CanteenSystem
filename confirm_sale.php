<?php
// Include your database connection
include 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Manila');
// Set content type to JSON
header('Content-Type: application/json');

// Get the sale data from the request
$saleData = json_decode(file_get_contents("php://input"), true);

// Check if the sale data is provided
if (empty($saleData) || !isset($saleData['items'])) {
    echo json_encode(["success" => false, "message" => "No sale data provided."]);
    exit;
}

// Check if payment method is cash or RFID
$paymentMethod = isset($saleData['paymentMethod']) ? $saleData['paymentMethod'] : null;
$rfid = isset($saleData['rfid']) ? $saleData['rfid'] : null;
$totalAmount = 0; // Initialize total amount

// Initialize an array to store the sales data
$sales = [];
$transactionNumber = "HCC-" . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
$saleDate = date("Y-m-d H:i:s");

// Loop through the cart items
foreach ($saleData['items'] as $item) {
    // Validate item properties
    if (!isset($item["id"], $item["quantity"], $item["price"])) {
        echo json_encode(["success" => false, "message" => "Invalid sale data."]);
        exit;
    }

    // Calculate total for this item
    $saleTotal = $item["price"] * $item["quantity"];
    $totalAmount += $saleTotal;

    // Prepare the sales data
    $sale = [
        "product_id" => $item["id"],
        "quantity_sold" => $item["quantity"],
        "total" => $saleTotal,
        "sale_date" => $saleDate,
        "transaction_number" => $transactionNumber  // Add the transaction number here
    ];

    // Add the sales data to the array
    $sales[] = $sale;
}

// If the payment method is cash, process without RFID
if ($paymentMethod === 'cash') {
    // Prepare the SQL query for sales
    $sql = "INSERT INTO sales (product_id, quantity_sold, total, sale_date, transaction_number, payment_method) VALUES ";
    $sql .= implode(", ", array_map(function($sale) use ($paymentMethod) {
        return "({$sale["product_id"]}, {$sale["quantity_sold"]}, {$sale["total"]}, '{$sale["sale_date"]}', '{$sale["transaction_number"]}', '{$paymentMethod}')";
    }, $sales));

    // Execute the SQL query for sales
    if ($conn->query($sql) === TRUE) {
        // Create e-receipt without RFID code
        $receiptData = [
            "total_amount" => $totalAmount,
            "sale_date" => $saleDate,
            "transaction_number" => $transactionNumber // Ensure transaction number is included
        ];

        // Prepare the SQL query for e_receipts with transaction_number
        $insertReceiptSql = "INSERT INTO e_receipts (total_amount, sale_date, transaction_number) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertReceiptSql);
        $stmt->bind_param("iss", $receiptData['total_amount'], $receiptData['sale_date'], $receiptData['transaction_number']);
        $stmt->execute();

        // Get the last inserted receipt ID
        $e_receipt_id = $stmt->insert_id;

        // Insert details into e_receipt_details
        foreach ($sales as $sale) {
            // Fetch product name
            $productQuery = "SELECT name FROM products WHERE id = ?";
            $productStmt = $conn->prepare($productQuery);
            $productStmt->bind_param("i", $sale['product_id']);
            $productStmt->execute();
            $productResult = $productStmt->get_result();
            $productName = $productResult->fetch_assoc()['name'];

            // Insert product details into e_receipt_details including transaction number
            $insertDetailSql = "INSERT INTO e_receipt_details (e_receipt_id, product_id, product_name, quantity_sold, total, transaction_number) VALUES (?, ?, ?, ?, ?, ?)";
            $detailStmt = $conn->prepare($insertDetailSql);
            $detailStmt->bind_param("iisids", $e_receipt_id, $sale['product_id'], $productName, $sale['quantity_sold'], $sale['total'], $transactionNumber);
            $detailStmt->execute();
        }

        // Now, update the stock quantity after successful sale
        foreach ($sales as $sale) {
            $updateStockSql = "UPDATE products SET quantity = quantity - {$sale["quantity_sold"]} WHERE id = {$sale["product_id"]}";
            if (!$conn->query($updateStockSql)) {
                echo json_encode(["success" => false, "message" => "Error updating stock: " . $conn->error]);
                exit;
            }
        }

        // Send back the transaction number in the response
        echo json_encode(["success" => true, "message" => "Sale confirmed successfully", "transaction_number" => $transactionNumber]);
    } else {
        echo json_encode(["success" => false, "message" => "Error confirming sale: " . $conn->error]);
    }
} else {
    // Get user balance using RFID if payment method is not cash
    $query = "SELECT first_name, last_name, balance, is_activated FROM user WHERE rfid_code = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $rfid);
    $stmt->execute();
    $result = $stmt->get_result();
    $paymentMethod = 'rfid';

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Check if RFID is activated
        if ($user['is_activated'] == 0) {
            echo json_encode(["success" => false, "message" => "RFID is disabled. Please contact support."]);
            exit;
        }

        $currentBalance = $user['balance'];
        $userName = $user['first_name'] . " " . $user['last_name'];  // Combine first and last name

        if ($currentBalance >= $totalAmount) {
            $newBalance = $currentBalance - $totalAmount;

            // Update the balance in the database
            $updateQuery = "UPDATE user SET balance = ? WHERE rfid_code = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("is", $newBalance, $rfid);
            if (!$updateStmt->execute()) {
                echo json_encode(['success' => false, 'message' => 'Error updating balance: ' . $updateStmt->error]);
                exit;
            }

            // Prepare the SQL query for sales, now including the user's name
            $sql = "INSERT INTO sales (product_id, quantity_sold, total, sale_date, transaction_number, payment_method, username) VALUES ";
            $sql .= implode(", ", array_map(function($sale) use ($paymentMethod, $userName) {
                return "({$sale["product_id"]}, {$sale["quantity_sold"]}, {$sale["total"]}, '{$sale["sale_date"]}', '{$sale["transaction_number"]}', '{$paymentMethod}', '{$userName}')";
            }, $sales));

            // Execute the SQL query for sales
            if ($conn->query($sql) === TRUE) {
                // Create e-receipt with RFID
                $receiptData = [
                    "rfid_code" => $rfid,
                    "total_amount" => $totalAmount,
                    "transaction_number" => $transactionNumber,
                    "sale_date" => $saleDate  // Add sale_date to e_receipts
                ];
                $insertReceiptSql = "INSERT INTO e_receipts (rfid_code, total_amount, transaction_number, sale_date) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($insertReceiptSql);
                $stmt->bind_param("siss", $receiptData['rfid_code'], $receiptData['total_amount'], $receiptData['transaction_number'], $receiptData['sale_date']);
                $stmt->execute();

                // Get the last inserted receipt ID
                $e_receipt_id = $stmt->insert_id;

                // Insert details into e_receipt_details
                foreach ($sales as $sale) {
                    // Fetch product name
                    $productQuery = "SELECT name FROM products WHERE id = ?";
                    $productStmt = $conn->prepare($productQuery);
                    $productStmt->bind_param("i", $sale['product_id']);
                    $productStmt->execute();
                    $productResult = $productStmt->get_result();
                    $productName = $productResult->fetch_assoc()['name'];

                    // Insert product details into e_receipt_details
                    $insertDetailSql = "INSERT INTO e_receipt_details (e_receipt_id, product_id, product_name, quantity_sold, total, transaction_number) VALUES (?, ?, ?, ?, ?, ?)";
                    $detailStmt = $conn->prepare($insertDetailSql);
                    $detailStmt->bind_param("iisids", $e_receipt_id, $sale['product_id'], $productName, $sale['quantity_sold'], $sale['total'], $transactionNumber);
                    $detailStmt->execute();
                }

                // Now, update the stock quantity after successful sale
                foreach ($sales as $sale) {
                    $updateStockSql = "UPDATE products SET quantity = quantity - {$sale["quantity_sold"]} WHERE id = {$sale["product_id"]}";
                    if (!$conn->query($updateStockSql)) {
                        echo json_encode(["success" => false, "message" => "Error updating stock: " . $conn->error]);
                        exit;
                    }
                }

                // Send back the transaction number in the response
                echo json_encode(["success" => true, "message" => "Sale confirmed successfully", "transaction_number" => $transactionNumber]);
            } else {
                echo json_encode(["success" => false, "message" => "Error confirming sale: " . $conn->error]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Insufficient balance."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "RFID not found."]);
    }
}

$conn->close();
?>