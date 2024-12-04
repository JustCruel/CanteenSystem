<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "canteenms";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['activate'])) {
    $email = $conn->real_escape_string($_POST['email']);

    $sql = "UPDATE users SET is_activated=1 WHERE email='$email'";
    if ($conn->query($sql)) {
        echo "User activated successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
