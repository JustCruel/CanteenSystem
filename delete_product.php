<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = $conn->prepare("DELETE FROM products WHERE id = ?");
    $query->execute([$id]);

    header("Location: inventory.php");
}
?>
