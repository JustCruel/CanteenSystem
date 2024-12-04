<?php
// Include your database connection
include 'db.php';

if (isset($_POST['rfid'])) {
    $rfid = $_POST['rfid'];

    // Query to get user information
    $query = "SELECT id, balance FROM user WHERE rfid_code = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $rfid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $userInfo = $result->fetch_assoc();
        echo json_encode($userInfo);
    } else {
        echo json_encode(null); // No user found
    }
}
?>
