<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Register</title>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Register Student</h2>
        <form id="registrationForm" method="POST" class="mt-4">
            <div class="form-group">
                <label for="student_id">Student ID:</label>
                <input type="text" name="student_id" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="middle_name">Middle Name:</label>
                <input type="text" name="middle_name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="rfid_code">RFID Code:</label>
                <input type="text" name="rfid_code" class="form-control" id="rfid_code" readonly>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" class="form-control" id="password" required>
                <label id="lengthHelp" class="form-text"></label>
                <label id="capitalHelp" class="form-text"></label>
                <label id="numberHelp" class="form-text"></label>
                <label id="specialHelp" class="form-text"></label>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" class="form-control" id="confirm_password" required>
                <label id="confirmPasswordHelp" class="form-text text-muted"></label>
            </div>

            <div class="form-group">
                <label for="balance">Initial Balance:</label>
                <input type="number" name="balance" class="form-control" required min="0" step="1">
            </div>

            <button type="submit" class="btn btn-primary btn-block" id="registerButton">Register</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Password validation function
        function validatePassword(password) {
            const requirements = {
                length: password.length >= 8,
                capital: /[A-Z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[!@#$%^&*]/.test(password),
            };
            return requirements;
        }

        document.getElementById('password').addEventListener('input', function () {
            const password = this.value;
            const requirements = validatePassword(password);

            // Check password requirements and update labels
            document.getElementById('lengthHelp').textContent = requirements.length ? "✔ At least 8 characters." : "✖ At least 8 characters required.";
            document.getElementById('capitalHelp').textContent = requirements.capital ? "✔ At least 1 uppercase letter." : "✖ At least 1 uppercase letter required.";
            document.getElementById('numberHelp').textContent = requirements.number ? "✔ At least 1 number." : "✖ At least 1 number required.";
            document.getElementById('specialHelp').textContent = requirements.special ? "✔ At least 1 special character." : "✖ At least 1 special character required.";
            
            // Change text color based on validity
            document.getElementById('lengthHelp').style.color = requirements.length ? "green" : "red";
            document.getElementById('capitalHelp').style.color = requirements.capital ? "green" : "red";
            document.getElementById('numberHelp').style.color = requirements.number ? "green" : "red";
            document.getElementById('specialHelp').style.color = requirements.special ? "green" : "red";

            // Check if passwords match
            const confirmPassword = document.getElementById('confirm_password').value;
            document.getElementById('confirmPasswordHelp').textContent = (password === confirmPassword && password.length > 0) ? "✔ Passwords match." : "✖ Passwords do not match.";
            document.getElementById('confirmPasswordHelp').style.color = (password === confirmPassword && password.length > 0) ? "green" : "red";
        });

        document.getElementById('confirm_password').addEventListener('input', function () {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;

            // Check if passwords match
            document.getElementById('confirmPasswordHelp').textContent = (password === confirmPassword && password.length > 0) ? "✔ Passwords match." : "✖ Passwords do not match.";
            document.getElementById('confirmPasswordHelp').style.color = (password === confirmPassword && password.length > 0) ? "green" : "red";
        });

        document.getElementById('registrationForm').onsubmit = function(event) {
            event.preventDefault(); // Prevent default form submission

            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            // Check if passwords match and meet requirements
            if (validatePassword(password).length && validatePassword(password).capital && 
                validatePassword(password).number && validatePassword(password).special && 
                password === confirmPassword) {
                
                // Show SweetAlert for waiting RFID read
                Swal.fire({
                    title: "Waiting for RFID",
                    text: "Please scan your RFID card.",
                    icon: "info",
                    showCancelButton: false,
                    showConfirmButton: false
                });

                // Get the RFID code input field
                const rfidInput = document.getElementById('rfid_code');

                // Remove readonly attribute to allow editing
                rfidInput.removeAttribute('readonly');

                // Wait for RFID input
                const checkRFID = setInterval(() => {
                    if (rfidInput.value.trim() !== "") { // Check if RFID field is filled
                        clearInterval(checkRFID); // Stop checking
                        Swal.close(); // Close the SweetAlert
                        this.submit(); // Submit the form
                    }
                }, 1500); // Check every 1500 milliseconds

                // Focus on the RFID input to prepare for scan
                rfidInput.focus();
            } else {
                // Show error if passwords do not match or do not meet requirements
                Swal.fire({
                    title: 'Error!',
                    text: 'Passwords must match and meet the complexity requirements.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        };
    </script>
</body>
</html>
<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture the input values
    $student_id = $_POST['student_id'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $rfid_code = $_POST['rfid_code'];
    $password = $_POST['password'];
    $balance = $_POST['balance']; // Capture balance

    // Initialize variables for error messages
    $error_message = '';

    // Check for existing student_id
    $checkStudentIdQuery = "SELECT COUNT(*) as count FROM user WHERE student_id = ?";
    $stmt = $conn->prepare($checkStudentIdQuery);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $stmt->bind_result($studentIdCount);
    $stmt->fetch();
    $stmt->close();

    if ($studentIdCount > 0) {
        // If student ID exists, set the error message
        $error_message .= "Student ID already exists. Please use a different one.<br>";
    }

    // Check for existing email
    $checkEmailQuery = "SELECT COUNT(*) as count FROM user WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($emailCount);
    $stmt->fetch();
    $stmt->close();

    if ($emailCount > 0) {
        // If email exists, set the error message
        $error_message .= "Email already exists. Please use a different one.<br>";
    }

    // Check for existing RFID code
    $checkRFIDQuery = "SELECT COUNT(*) as count FROM user WHERE rfid_code = ?";
    $stmt = $conn->prepare($checkRFIDQuery);
    $stmt->bind_param("s", $rfid_code);
    $stmt->execute();
    $stmt->bind_result($rfidCount);
    $stmt->fetch();
    $stmt->close();

    if ($rfidCount > 0) {
        // If RFID code exists, set the error message
        $error_message .= "RFID Code already exists. Please use a different code.<br>";
    }

    // If there's any error message, show the alert
    if ($error_message !== '') {
        echo "
        <script>
            Swal.fire({
                title: 'Error!',
                html: '$error_message', // Use HTML to display multiple error messages
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>";
    } else {
        // Hash the password before storing
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the user into the database
        $insertQuery = "INSERT INTO user (student_id, first_name, middle_name, last_name, email, rfid_code, password, balance) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("sssssssd", $student_id, $first_name, $middle_name, $last_name, $email, $rfid_code, $hashed_password, $balance);
        $stmt->execute();
        $stmt->close();

        // Redirect or show a success message
        echo "
        <script>
            Swal.fire({
                title: 'Success!',
                text: 'Registration successful!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location = 'homepage.php'; // Redirect to index page
            });
        </script>";
    }
}
?>
