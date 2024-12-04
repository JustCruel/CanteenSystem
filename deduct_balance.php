<?php
session_start();
include 'db.php'; // Include your database connection

$response = ['success' => false, 'error' => null];

// Check if the request is POST and handle the RFID input
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the required fields are set
    if (isset($_POST['rfid']) && isset($_POST['amount']) && isset($_POST['action'])) {
        $rfid_code = trim($_POST['rfid']);
        $amount = floatval($_POST['amount']);
        $action = $_POST['action'];

        if ($amount <= 0) {
            $response['error'] = "Invalid amount.";
            echo json_encode($response);
            exit;
        }

        // Fetch user details based on the RFID
        $stmt = $conn->prepare("SELECT id, first_name, last_name, balance FROM user WHERE rfid_code = ?");
        $stmt->bind_param("s", $rfid_code);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if user exists
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Determine new balance based on action (load or deduct)
            if ($action == 'load') {
                $new_balance = $user['balance'] + $amount;
            } elseif ($action == 'deduct') {
                $new_balance = $user['balance'] - $amount;
                if ($new_balance < 0) {
                    $response['error'] = "Insufficient balance.";
                    echo json_encode($response);
                    exit;
                }
            } else {
                $response['error'] = "Invalid action.";
                echo json_encode($response);
                exit;
            }

            // Update user balance in the database
            $update_stmt = $conn->prepare("UPDATE user SET balance = ? WHERE id = ?");
            $update_stmt->bind_param("di", $new_balance, $user['id']);

            // Execute the update query
            if ($update_stmt->execute()) {
                // Record the transaction
                $transaction_stmt = $conn->prepare("INSERT INTO transactionslnd (rfid_code, user_name, transaction_type, amount) VALUES (?, ?, ?, ?)");
                $user_name = $user['first_name'] . ' ' . $user['last_name']; 
                $transaction_type = $action == 'load' ? 'load' : 'deduct';
                $transaction_stmt->bind_param("sssd", $rfid_code, $user_name, $transaction_type, $amount);
                
                if ($transaction_stmt->execute()) {
                    $response['success'] = "Balance successfully " . ($action == 'load' ? 'loaded' : 'deducted') . " P" . number_format($amount, 2);
                } else {
                    $response['error'] = "Could not record transaction. Please try again.";
                }
            } else {
                $response['error'] = "Could not update balance. Please try again.";
            }
        } else {
            $response['error'] = "RFID not found.";
        }
    } else {
        $response['error'] = "Missing required parameters.";
    }

    echo json_encode($response);
    exit;
}
