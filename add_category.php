<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php'; // Database connection

$response = ['success' => false, 'exists' => false];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_name = $_POST['category_name'];

    // Check if the category already exists
    $check_query = $conn->prepare("SELECT COUNT(*) FROM categories WHERE name = ?");
    $check_query->execute([$category_name]);
    $exists = $check_query->fetchColumn() > 0;

    if ($exists) {
        $response['exists'] = true; // Category already exists
    } else {
        // Insert the new category into the database
        $query = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        if ($query->execute([$category_name])) {
            $response['success'] = true;
            $response['category_name'] = $category_name; // Return the category name
        }
    }
}

header('Content-Type: application/json');
echo json_encode($response);
