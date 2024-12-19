<?php
session_start();
include "db.php";
require 'vendor/autoload.php'; // Automatically loads all dependencies

if (isset($_POST['register'])) {
    $student_id = $conn->real_escape_string($_POST['student_id']);
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $middle_name = $conn->real_escape_string($_POST['middle_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $rfid_code = "";  // Default RFID Code, if required
    $password = password_hash("mcmy_1946", PASSWORD_DEFAULT); // Default password
    $balance = "0.00"; // Default balance
    $user_type = "user"; // Default user type

    // Check if the user already exists
    $check_sql = "SELECT id FROM user WHERE student_id = '$student_id' OR email = '$email'";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        // User already exists, set session error message
        $_SESSION['alert'] = [
            'title' => 'Error!',
            'text' => 'User  already exists with this Student ID or Email.',
            'icon' => 'error'
        ];
    } else {
        // Insert the user if they do not exist
        $sql = "INSERT INTO user (student_id, first_name, middle_name, last_name, email, password, balance, user_type, is_activated) 
                VALUES ('$student_id', '$first_name', '$middle_name', '$last_name', '$email', '$password', '$balance', '$user_type', 0)";
        if ($conn->query($sql) === TRUE) {
            // Success
            $_SESSION['alert'] = [
                'title' => 'Success!',
                'text' => 'Student has been successfully registered.',
                'icon' => 'success'
            ];
        } else {
            // Error in insertion
            $_SESSION['alert'] = [
                'title' => 'Error!',
                'text' => 'There was an error registering the student. Please try again.',
                'icon' => 'error'
            ];
        }
    }

    // Redirect to the same page to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manual Student Registration</title>

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Bootstrap CSS (optional for styling) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php include 'sidebarmis.php'; ?>
<div class="container mt-5">
    <h2 class="text-center">Register Student Manually</h2>
    <form action="" method="post" onsubmit="return confirmRegistration();">
    <div class="mb-3">
    <label for="student_id" class="form-label">Student ID</label>
    <input type="text" class="form-control" id="student_id" name="student_id" required maxlength="9" pattern="\d{9}" title="Student ID must be exactly 9 digits">
</div>

<script>
    // Prevent non-numeric characters from being entered
    document.getElementById('student_id').addEventListener('input', function (e) {
        let value = e.target.value;
        
        // Remove any non-digit character
        e.target.value = value.replace(/\D/g, '').slice(0, 9); // Keep only digits and limit to 9 characters
    });
</script>


        <div class="mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" required>
        </div>
        <div class="mb-3">
            <label for="middle_name" class="form-label">Middle Name</label>
            <input type="text" class="form-control" id="middle_name" name="middle_name">
        </div>
        <div class="mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <button type="submit" name="register" class="btn btn-primary">Register Student</button>
    </form>
</div>

<!-- Bootstrap JS (optional for functionality) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function confirmRegistration() {
        return confirm("Are you sure you want to register this student?");
    }
</script>

<?php if (isset($_SESSION['alert'])): ?>
<script>
    Swal.fire({
        title: '<?php echo $_SESSION['alert']['title']; ?>',
        text: '<?php echo $_SESSION['alert']['text']; ?>',
        icon: '<?php echo $_SESSION['alert']['icon']; ?>',
        confirmButtonText: 'OK'
    }).then((result) => {
        if (result.isConfirmed) {
           // Reload the page to clear the alert
        }
    });
</script>
<?php 
    unset($_SESSION['alert']); // Clear the alert after displaying
endif; 
?>

</body>
</html>