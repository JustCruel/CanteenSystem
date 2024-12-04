<?php
// Database connection (update with your credentials)
$host = 'localhost';
$db = 'canteenms';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get RFID from the request
$rfid = $_GET['rfid'];

// Prepare and execute SQL query to fetch user data
$sql = "SELECT id, student_id, first_name, middle_name, last_name, email, rfid_code, balance FROM users WHERE rfid_code = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $rfid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch user data
    $userData = $result->fetch_assoc();
    echo json_encode($userData);
} else {
    echo json_encode(null); // No user found
}

$stmt->close();
$conn->close();
?>
