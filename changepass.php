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

// Fetch user details from session or use a parameter for the email (student_id)
$student_id = $_SESSION['email'];  // Assuming the email is stored in session

// Initialize variables for messages and alert type
$alert_type = '';
$message = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Input validation
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $message = "All fields are required!";
        $alert_type = 'error';
    } elseif ($new_password !== $confirm_password) {
        $message = "New passwords do not match!";
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
                    $message = "Password updated successfully!";
                    $alert_type = 'success';
                } else {
                    $message = "Error updating password: " . $conn->error;
                    $alert_type = 'error';
                }
            } else {
                $message = "Current password is incorrect!";
                $alert_type = 'error';
            }
        } else {
            $message = "User not found!";
            $alert_type = 'error';
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Password</title>
    <!-- Bootstrap 4 CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert 2 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.6/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Update Password</h2>
        
        <!-- Form -->
        <form action="" method="POST">
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" class="form-control" id="current_password" name="current_password" required>
            </div>
            
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Update Password</button>
        </form>
    </div>

    <!-- SweetAlert 2 JS (Place this at the end of the body) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.6/dist/sweetalert2.all.min.js"></script>

    <script>
        <?php if (!empty($message)): ?>
            Swal.fire({
                icon: '<?php echo $alert_type; ?>',
                title: '<?php echo $alert_type === "error" ? "Oops..." : "Success!"; ?>',
                text: '<?php echo $message; ?>',
                showConfirmButton: true,
                <?php if ($alert_type === 'success') echo 'timer: 1500,'; ?>
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = 'usersdashboard.php'; // Redirect to registeractivate.php
                }
            });
        <?php endif; ?>
    </script>
</body>
</html>
