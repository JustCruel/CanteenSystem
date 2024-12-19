<?php
session_start();
include "db.php"; // Ensure to include your database connection

header('Content-Type: application/json'); // Set the response type to JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_SESSION['first_name'];
    $middle_name = $_SESSION['middle_name'];
    $last_name = $_SESSION['last_name'];
    $fullname = $first_name . " " . $middle_name . " " . $last_name;
    $rfidCode = $_POST['rfid_code']; // Get the RFID code from the request
    $userName = ""; // Placeholder for user name
    $userId = null; // Initialize user ID
    $action = "Deactivated"; // Action description

    // Prepare and execute the statement to fetch user details associated with the RFID code
    $userQuery = $conn->prepare("SELECT id, first_name, middle_name, last_name FROM user WHERE rfid_code = ?");
    $userQuery->bind_param("s", $rfidCode); // Bind the parameter
    $userQuery->execute();
    $userResult = $userQuery->get_result();

    if ($userResult->num_rows > 0) {
        $userRow = $userResult->fetch_assoc();
        $userName = $userRow['first_name'] . " " . $userRow['middle_name'] . " " . $userRow['last_name'];
        $userId = $userRow['id']; // Get the user ID
    } else {
        echo json_encode(["status" => "error", "message" => "User with RFID $rfidCode not found."]);
        exit; // Stop further processing if user not found
    }

    // Prepare and execute the statement to update the user's activation status in the database
    $updateQuery = $conn->prepare("UPDATE user SET is_activated = 2 WHERE rfid_code = ?");
    $updateQuery->bind_param("s", $rfidCode); // Bind the parameter

    if ($updateQuery->execute() === TRUE) {
        // Insert the log entry into rfid_history table
        $actionTime = date('Y-m-d H:i:s'); // Current timestamp
        $logQuery = $conn->prepare("INSERT INTO rfid_history (rfid_code, user_name, action, action_time, user_id, modified) VALUES (?, ?, ?, ?, ?,?)");
        $logQuery->bind_param("ssssis", $rfidCode, $userName, $action, $actionTime, $userId, $fullname); // Bind parameters

        if ($logQuery->execute() === TRUE) {
            echo json_encode(["status" => "success", "message" => "User with RFID $rfidCode deactivated successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error logging action: " . $conn->error]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Error activating user: " . $conn->error]);
    }

    // Close statements and connection
    $userQuery->close();
    $updateQuery->close();
    $logQuery->close();
    $conn->close();
}
?>
