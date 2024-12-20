<?php
session_start();
include "db.php";
require 'vendor/autoload.php'; // Automatically loads all dependencies

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    // Redirect to the registration page
    header("Location: registration_page.php"); // Change to your registration page
    exit;
}

$conn->close();
?>