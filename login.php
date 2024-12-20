<?php  
session_start();
include 'db.php'; // Assumes you're using mysqli connection in db.php

// Check if the connection was successful (optional debug)
if (!$conn) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = ''; // Initialize an error message variable
$login_success = false; // Initialize login success flag

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email']; // Change variable to email
    $password = $_POST['password'];

    // Check if email and password are not empty
    if (!empty($email) && !empty($password)) {
        // Prepare the query
        $query = $conn->prepare("SELECT id, first_name, middle_name, last_name, email, password, user_type FROM user WHERE email = BINARY ?");
        $query->bind_param("s", $email); // Bind only the email
        $query->execute();
        $query->store_result(); // Store result to check if user exists
    
        // Check if a user was found
        if ($query->num_rows > 0) {
            // Bind result variables
            $query->bind_result($id, $first_name,$middle_name,$last_name, $db_email, $hashed_password, $user_type);
            $query->fetch(); // Fetch the result
    
            // Verify the password
            if (password_verify($password, $hashed_password)) {
                // Set session variables
                $_SESSION['user_id'] = $id;
                $_SESSION['first_name'] = $first_name;
                $_SESSION['middle_name'] = $middle_name;
                $_SESSION['last_name'] = $last_name;
                $_SESSION['email'] = $db_email; 
                $_SESSION['user_type'] = $user_type;  
                $login_success = true;
    
                // Redirect based on user type
                if ($user_type == 'cstaff') {
                    $redirect_url = "pos.php";
                } elseif ($user_type == 'cmanager') {
                    $redirect_url = "dashboard.php";
                } elseif ($user_type == 'cashier') {
                    $redirect_url = "cashierdashboard.php";
                } elseif ($user_type == 'user') {
                    $redirect_url = "usersdashboard.php";
                } elseif ($user_type == 'mis') {
                    $redirect_url = "misdashboard.php";
                }
            } else {
                $error_message = "Invalid Login Credentials!!!";
            }
        } else {
            $error_message = "Invalid Login Credentials!!";
        }
    }
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/login.css">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const passwordToggle = document.getElementById('toggle-password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordToggle.src = 'assets/images/eye-off.png'; // Path to "eye-off" icon
            } else {
                passwordInput.type = 'password';
                passwordToggle.src = 'assets/images/eye.png'; // Path to "eye" icon
            }
        }

        window.onload = function() {
            const passwordInput = document.getElementById('password');
            const passwordToggle = document.getElementById('toggle-password');
            passwordToggle.src = passwordInput.type === 'password' ? 'assets/images/eye.png' : 'assets/images/eye-off.png';

            // Check if login was successful
            // Check if login was successful
<?php if ($login_success): ?>
    swal({
        title: "Login Succesfully!",
        text: " Welcome, <?php echo $_SESSION['first_name'], " ",$_SESSION['middle_name'], " ",$_SESSION['last_name']; ?>!",
        icon: "success",
        timer: 2000,
        buttons: false,
    }, function() {
        window.location.href = "<?php echo $redirect_url; ?>"; // Redirect after alert
    });
<?php endif; ?>

        }
    </script>

</head>
<body>
    <div class="login-container">
        <h2>Welcome</h2>
        <?php if ($error_message): ?>
            <div class="error-card">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <div class="input-group">
                <label for="email">Email:</label>
                <input type="text" id="email" placeholder="Email" name="email" required>
            </div>
            <div class="input-group">
                <label for="password">Password:</label>
                <div class="password-container">
                    <input type="password" id="password" placeholder="Password" name="password" required>
                    <img id="toggle-password" src="assets/images/eye.png" alt="Toggle Password Visibility" onclick="togglePasswordVisibility()" class="toggle-password-icon">
                </div>
            </div>
            <button type="submit">Login</button> <br>

            <br> 
            <br>
            <a class="home" href="index.php">Homepage</a>
        </form>
    </div>
</body>
</html>
