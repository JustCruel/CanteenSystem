<?php 
session_start();

// Assuming you have stored the user role in the session like this:
$role = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : '';

// Check if the user is 'cstaff' and redirect to 'pos.php' if canceled, otherwise to 'dashboard.php'
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <script>
        // Pass the role from PHP to JavaScript
        var userRole = "<?php echo $role; ?>";

        // SweetAlert confirmation for logout
        Swal.fire({
            title: 'Are you sure?',
            text: "You will be logged out!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, log me out!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, redirect to logout.php to destroy the session
                window.location.href = 'logout.php?action=confirm';
            } else {
                // If canceled, check user role and redirect accordingly
                if (userRole === 'cstaff') {
                // Redirect to POS page for cstaff
                window.location.href = 'pos.php';
            } else if (userRole === 'cmanager') {
                // Redirect to dashboard for cmanager
                window.location.href = 'inventory.php';
            } else if (userRole === 'cashier') {
                // Redirect to cashier dashboard for cashier
                window.location.href = 'cashierdashboard.php';
            } else if (userRole === 'users') {
                // Redirect to users dashboard for users
                window.location.href = 'usersdashboard.php';
            }
            }
        });
    </script>
</body>
</html>

<?php
// Process the session destruction only when the user confirms the logout
if (isset($_GET['action']) && $_GET['action'] == 'confirm') {
    session_unset(); // Remove all session variables
    session_destroy(); // Destroy the session
    header('Location: login.php'); // Redirect to login page
    exit();
}
?>
