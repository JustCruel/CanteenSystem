<?php
include 'db.php';

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$salesQuery = "SELECT sales.*, products.name AS product_name, products.market_price, products.selling_price 
               FROM sales 
               JOIN products ON sales.product_id = products.id 
               WHERE (products.name LIKE '%$search%' OR sales.transaction_number LIKE '%$search%') 
               ORDER BY sale_date DESC";

$salesResult = $conn->query($salesQuery);
$sales = [];
while ($row = $salesResult->fetch_assoc()) {
    $sales[] = $row;
}

if (count($sales) > 0) {
    foreach ($sales as $sale) {
        echo "<tr>
                <td>{$sale['transaction_number']}</td>
                <td>{$sale['product_name']}</td>
                <td>{$sale['quantity_sold']}</td>
                <td>{$sale['sale_date']}</td>
                <td>₱" . number_format($sale['market_price'], 2) . "</td>
                <td>₱" . number_format($sale['selling_price'], 2) . "</td>
                <td>₱" . number_format($sale['selling_price'] * $sale['quantity_sold'], 2) . "</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='7' class='text-center'>No sales records found.</td></tr>";
}
?>
