<?php
include "db.php"; // Include database connection

header('Content-Type: application/json');

// Get the JSON input data
$data = json_decode(file_get_contents('php://input'), true);

// Ensure both 'studentId' and 'rfid' are provided
if (!isset($data['studentId'], $data['rfid'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing student ID or RFID.'
    ]);
    exit;
}

$studentId = $conn->real_escape_string($data['studentId']);
$newRfid = $conn->real_escape_string($data['rfid']);

// If 'rfid_code' is provided, use it; otherwise, return an error
if (isset($data['rfid_code'])) {
    $rfid_code = $conn->real_escape_string($data['rfid_code']);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Missing current RFID code.'
    ]);
    exit;
}

// Check if the student exists
$studentExistsSql = "SELECT * FROM user WHERE student_id = '$studentId'";
$studentExistsResult = $conn->query($studentExistsSql);

if ($studentExistsResult->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Student ID not found.'
    ]);
    exit;
}

// Check if the new RFID is already assigned
$checkRfidSql = "SELECT * FROM user WHERE rfid_code = '$newRfid'";
$checkRfidResult = $conn->query($checkRfidSql);

if ($checkRfidResult->num_rows > 0) {
    echo json_encode([
        'success' => false,
        'message' => 'RFID is already assigned to another user.'
    ]);
    exit;
}

// Check if the new RFID is the same as the current one
$currentRfidSql = "SELECT rfid_code FROM user WHERE student_id = '$studentId'";
$currentRfidResult = $conn->query($currentRfidSql);

if ($currentRfidResult->num_rows > 0) {
    $row = $currentRfidResult->fetch_assoc();
    if ($row['rfid_code'] === $newRfid) {
        echo json_encode([
            'success' => false,
            'message' => 'The new RFID matches the existing one. No update performed.'
        ]);
        exit;
    }
}

// Update the RFID
$updateSql = "UPDATE `user`
SET rfid_code = '$newRfid'
WHERE rfid_code = '$rfid_code'";
if ($conn->query($updateSql)) {
    if ($conn->affected_rows > 0) {
        echo json_encode([
            'success' => true,
            'message' => 'New RFID successfully assigned.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No rows were updated. Please check the student ID.'
        ]);
    }
} else {
    error_log("MySQL Error: " . $conn->error);
    echo json_encode([
        'success' => false,
        'message' => 'Error assigning RFID. Please try again.'
    ]);
}

$conn->close();
?>
