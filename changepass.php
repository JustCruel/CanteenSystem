<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "canteenms";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

// Fetch user details from session
$student_id = $_SESSION['email']; 

$alert_type = '';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $message = "All fields are required!";
        $alert_type = 'error';
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $new_password)) {
        $message = "Password must be at least 8 characters long, include uppercase, lowercase, a number, and a special character!";
        $alert_type = 'error';
    } elseif ($new_password !== $confirm_password) {
        $message = "New passwords do not match!";
        $alert_type = 'error';
    } else {
        $sql = "SELECT password FROM user WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($current_password, $row['password'])) {
                $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_sql = "UPDATE user SET password = ? WHERE email = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("ss", $new_hashed_password, $student_id);

                if ($update_stmt->execute()) {
                    $message = "Password updated successfully!";
                    $alert_type = 'success';
                } else {
                    $message = "Error updating password: " . $conn->error;
                    $alert_type = 'error';
                }

                $update_stmt->close();
            } else {
                $message = "Current password is incorrect!";
                $alert_type = 'error';
            }
        } else {
            $message = "User not found!";
            $alert_type = 'error';
        }
        $stmt->close();
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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.6/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .password-container {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Update Password</h2>
        <form action="changepass.php" method="POST">
            <div class="form-group">
                <a href="ereceipt.php" class="btn btn-secondary">Back to E-Receipt</a>
            </div>
            <div class="form-group password-container">
                <label for="current_password">Current Password</label>
                <input type="password" class="form-control" id="current_password" name="current_password" required>
                <span class="toggle-password" onclick="togglePassword('current_password')">👁️</span>
            </div>

            <div class="form-group password-container">
                <label for="new_password">New Password</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
                <span class="toggle-password" onclick="togglePassword('new_password')">👁️</span>
            </div>

            <div class="form-group password-container">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                <span class="toggle-password" onclick="togglePassword('confirm_password')">👁️</span>
            </div>

            <button type="submit" class="btn btn-primary">Update Password</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.6/dist/sweetalert2.all.min.js"></script>
    <script>
        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;
        }

        <?php if (!empty($message)): ?>
            Swal.fire({
                icon: '<?php echo $alert_type; ?>',
                title: '<?php echo $alert_type === "error" ? "Oops..." : "Success!"; ?>',
                text: '<?php echo $message; ?>',
                showConfirmButton: true
            }).then((result) => {
                if (result.isConfirmed && '<?php echo $alert_type; ?>' === 'success') {
                    window.location = 'usersdashboard.php';
                }
            });
        <?php endif; ?>
    </script>
</body>
</html>
