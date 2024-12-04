<?php
include "db.php"; // Ensure to include your database connection

header('Content-Type: application/json'); // Set the response type to JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input for security
    if (isset($_POST['rfid_code']) && !empty($_POST['rfid_code'])) {
        $rfidCode = $_POST['rfid_code']; // Get the RFID code from the request
    } else {
        echo json_encode(["status" => "error", "message" => "RFID code is required."]);
        exit;
    }

    // Placeholder variables for user data
    $userName = ""; 
    $userId = null; 
    $action = "Activated"; // Action description

    // Prepare and execute the statement to fetch user details associated with the RFID code
    $userQuery = $conn->prepare("SELECT id, first_name, middle_name, last_name FROM user WHERE rfid_code = ?");
    $userQuery->bind_param("s", $rfidCode); // Bind the parameter
    $userQuery->execute();
    $userResult = $userQuery->get_result();

    // Check if the user exists
    if ($userResult->num_rows > 0) {
        $userRow = $userResult->fetch_assoc();
        $userName = $userRow['first_name'] . " " . $userRow['middle_name'] . " " . $userRow['last_name'];
        $userId = $userRow['id']; // Get the user ID
    } else {
        echo json_encode(["status" => "error", "message" => "User with RFID $rfidCode not found."]);
        exit; // Stop further processing if user not found
    }

    // Check if the user is already activated
    $checkStatusQuery = $conn->prepare("SELECT is_activated FROM user WHERE rfid_code = ?");
    $checkStatusQuery->bind_param("s", $rfidCode); // Bind the parameter
    $checkStatusQuery->execute();
    $statusResult = $checkStatusQuery->get_result();
    $statusRow = $statusResult->fetch_assoc();

    if ($statusRow['is_activated'] == 1) {
        echo json_encode(["status" => "error", "message" => "User with RFID $rfidCode is already activated."]);
        exit; // Stop if the user is already activated
    }

    // Prepare and execute the statement to update the user's activation status in the database
    $updateQuery = $conn->prepare("UPDATE user SET is_activated = 1 WHERE rfid_code = ?");
    $updateQuery->bind_param("s", $rfidCode); // Bind the parameter

    if ($updateQuery->execute() === TRUE) {
        // Insert the log entry into rfid_history table
        $actionTime = date('Y-m-d H:i:s'); // Current timestamp
        $logQuery = $conn->prepare("INSERT INTO rfid_history (rfid_code, user_name, action, action_time, user_id) VALUES (?, ?, ?, ?, ?)");
        $logQuery->bind_param("ssssi", $rfidCode, $userName, $action, $actionTime, $userId); // Bind parameters

        if ($logQuery->execute() === TRUE) {
            echo json_encode(["status" => "success", "message" => "User with RFID $rfidCode activated successfully."]);
        } else {
            // Log entry failure
            echo json_encode(["status" => "error", "message" => "Error logging action: " . $conn->error]);
        }
    } else {
        // Error updating user status
        echo json_encode(["status" => "error", "message" => "Error activating user: " . $conn->error]);
    }

    // Close statements and connection
    $userQuery->close();
    $checkStatusQuery->close();
    $updateQuery->close();
    $logQuery->close();
    $conn->close();
}
?>
