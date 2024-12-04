<?php
// Include your database connection
include 'd_connection.php';

if (isset($_POST['user_id']) && isset($_POST['amount'])) {
    $userId = $_POST['user_id'];
    $amount = $_POST['amount'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Query to deduct balance
        $query = "UPDATE user SET balance = balance - ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("di", $amount, $userId);
        $stmt->execute();

        // Check if update was successful
        if ($stmt->affected_rows > 0) {
            $conn->commit();
            echo json_encode(['success' => true]);
        } else {
            $conn->rollback();
            echo json_encode(['success' => false, 'message' => 'Failed to deduct balance.']);
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error occurred: ' . $e->getMessage()]);
    }
}
?>
