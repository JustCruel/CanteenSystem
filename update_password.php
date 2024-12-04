<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "canteenms";  // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();  // Assuming user session is already started for logged-in users

// Fetch user details from session or use a parameter for the student ID
$student_id = $_SESSION['email'];  // Assuming the student_id is stored in session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Input validation
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error = "All fields are required!";
        $alert_type = 'error';
    } elseif ($new_password !== $confirm_password) {
        $error = "New passwords do not match!";
        $alert_type = 'error';
    } else {
        // Check if the current password is correct
        $sql = "SELECT password FROM user WHERE email = '$student_id'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($current_password, $row['password'])) {
                // Hash new password
                $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update the password
                $update_sql = "UPDATE user SET password = '$new_hashed_password' WHERE email = '$student_id'";

                if ($conn->query($update_sql) === TRUE) {
                    $success = "Password updated successfully!";
                    $alert_type = 'success';
                } else {
                    $error = "Error updating password: " . $conn->error;
                    $alert_type = 'error';
                }
            } else {
                $error = "Current password is incorrect!";
                $alert_type = 'error';
            }
        } else {
            $error = "User not found!";
            $alert_type = 'error';
        }
    }
}

$conn->close();
?>