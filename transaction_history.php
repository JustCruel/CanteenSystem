<?php
session_start();
include 'db.php';
 // Include your database connection

// Determine the current page number
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1); // Ensure the page number is at least 1

// Set the number of entries per page
$entriesPerPage = 10;

// Calculate the offset for the SQL query
$offset = ($page - 1) * $entriesPerPage;

// Get the total number of transactions
$countQuery = "SELECT COUNT(*) as total FROM transactionslnd";
$countResult = $conn->query($countQuery);
$totalEntries = $countResult->fetch_assoc()['total'];

// Calculate the total number of pages
$totalPages = ceil($totalEntries / $entriesPerPage);

// Fetch the transactions for the current page
$query = "SELECT * FROM transactionslnd ORDER BY transaction_date DESC LIMIT $entriesPerPage OFFSET $offset";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .pagination {
            justify-content: center;
        }
    </style>
</head>
<body>

<?php include 'sidebarcash.php'; ?>
<div class="container mt-5">
    <h1 class="text-center">Transaction History</h1>
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>RFID Code</th>
                <th>User Name</th>
                <th>Transaction Type</th>
                <th>Amount</th>
                <th>Transaction Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['rfid_code']); ?></td>
                        <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                        <td><?php echo htmlspecialchars(ucfirst($row['transaction_type'])); ?></td>
                        <td>P<?php echo number_format($row['amount'], 2); ?></td>
                        <td><?php echo $row['transaction_date']; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No transactions found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

   <!-- Pagination Links -->
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
        <!-- First Page Button -->
        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=1" aria-label="First">
                <span aria-hidden="true">««</span>
            </a>
        </li>

        <!-- Previous Page Button -->
        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>

        <!-- Page Numbers (showing a limited range around the current page) -->
        <?php 
        $start = max(1, $page - 2); // Start showing pages from 2 pages before the current page
        $end = min($totalPages, $page + 2); // End showing pages 2 pages after the current page
        
        for ($i = $start; $i <= $end; $i++): ?>
            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <!-- Next Page Button -->
        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>

        <!-- Last Page Button -->
        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $totalPages ?>" aria-label="Last">
                <span aria-hidden="true">»»</span>
            </a>
        </li>
    </ul>
</nav>

</div>
</body>
</html>
