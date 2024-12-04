// fetch_receipts.php
<?php
session_start(); // Start session to access user data
$user_id = $_SESSION['user_id']; // Assume user ID is stored in session

$sql = "SELECT * FROM receipts WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$receipts = [];
while ($row = $result->fetch_assoc()) {
    $receipts[] = $row;
}

echo json_encode($receipts);
$stmt->close();
$conn->close();
?>
