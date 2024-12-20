<?php
include "db.php"; // Ensure to include your database connection
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include PHPMailer's autoload file (only if using Composer)

header('Content-Type: application/json'); // Set the response type to JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the RFID code and student_id from the request
    $rfidCode = $_POST['rfid_code'];
    $studentId = $_POST['student_id'];

    if (isset($_POST['student_id']) && isset($_POST['rfid_code'])) {
        $rfidCode = $_POST['rfid_code'];
        $studentId = $_POST['student_id'];
    } else {
        echo json_encode(["status" => "error", "message" => "RFID code or student ID is empty."]);
        exit;
    }
    

    // Check if the RFID code already exists in the database
    $checkRfidQuery = $conn->prepare("SELECT id FROM user WHERE rfid_code = ?");
    $checkRfidQuery->bind_param("s", $rfidCode); // Bind RFID code parameter
    $checkRfidQuery->execute();
    $checkResult = $checkRfidQuery->get_result();

    if ($checkResult->num_rows > 0) {
        // If RFID code already exists, return an error message
        echo json_encode(["status" => "error", "message" => "This RFID code is already assigned to another user."]);
        $checkRfidQuery->close();
        $conn->close();
        exit; // Exit if the RFID code already exists
    }

    // Now, find the user by student_id and assign the RFID code
    $noRfidUserQuery = $conn->prepare("SELECT id, first_name, middle_name, last_name, email, password FROM user WHERE student_id = ? AND (rfid_code IS NULL OR rfid_code = '') LIMIT 1");
    $noRfidUserQuery->bind_param("s", $studentId);
    $noRfidUserQuery->execute();
    $noRfidResult = $noRfidUserQuery->get_result();

    if ($noRfidResult->num_rows > 0) {
        // If the user is found, get user data
        $userRow = $noRfidResult->fetch_assoc();
        $userName = $userRow['first_name'] . " " . $userRow['middle_name'] . " " . $userRow['last_name'];
        $userId = $userRow['id']; // Get the user ID
        $userEmail = $userRow['email']; // Get the user email
        $userPassword = $userRow['password']; // Get the user password

        // Create PHPMailer instance
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'jemusu96@gmail.com'; // Your email
            $mail->Password = 'aybfptvlrktcrfjx'; // Your email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('jemusu96@gmail.com', 'HCC Canteen Management System'); // Sender
            $mail->addAddress($userEmail, $userName); // Add the recipient

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Your Account Login Credentials';
            $mail->Body    = "
                <html>
                <head>
                    <title>Your Account Login Credentials</title>
                </head>
                <body>
                    <h3>Hello, $userName</h3>
                    <p>Your RFID code has been successfully assigned, and your account is activated.</p>
                    <p><strong>Your Login Credentials:</strong></p>
                    <p><strong>Email:</strong> $userEmail</p>
                    <p><strong>Password:</strong> mcmy_1946</p>
                    <p>We recommend you change your password after your first login.</p>
                </body>
                </html>
            ";

            // Send the email
            if ($mail->send()) {
                // After the email is successfully sent, update the user's RFID code in the database
                $updateRfidQuery = $conn->prepare("UPDATE user SET rfid_code = ?, is_activated = 1 WHERE id = ?");
                $updateRfidQuery->bind_param("si", $rfidCode, $userId); // Bind RFID and user ID
                if ($updateRfidQuery->execute()) {
                    echo json_encode(["status" => "success", "message" => "RFID code assigned successfully to user: " . $userName . " and successfully activated."]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Failed to assign RFID code to user: " . $conn->error]);
                }
                
                $updateRfidQuery->close();
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to send email with login credentials."]);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => "Mailer Error: {$mail->ErrorInfo}"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No user found with the specified student ID and without an RFID code."]);
    }

    // Close the query and connection
    $noRfidUserQuery->close();
    $conn->close();
}

?>
