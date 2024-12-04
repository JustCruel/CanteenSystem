<?php
include 'db.php'; // Database connection

if (isset($_GET['category'])) {
    $category = $_GET['category'];

    // Query to fetch most sold products for the selected category
    $query = "SELECT p.name, SUM(s.quantity_sold) AS total_sold 
              FROM sales s 
              JOIN products p ON s.product_id = p.id 
              WHERE p.category = ? 
              GROUP BY p.id 
              ORDER BY total_sold DESC 
              LIMIT 5"; // Fetch top 5 products

    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $category);
    $stmt->execute();
    $result = $stmt->get_result();

    $labels = [];
    $series = [];

    while ($row = $result->fetch_assoc()) {
        $labels[] = $row['name'] . ' (' . $row['total_sold'] . ')';
        $series[] = $row['total_sold'];
    }

    // Return the data in JSON format
    echo json_encode([
        'labels' => $labels,
        'series' => $series
    ]);

    $labels = array_map('intval', $labels);
}
?>
