<?php
session_start();
include 'db.php';

// Determine the current page number
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1); // Ensure the page number is at least 1

// Set the number of entries per page
$entriesPerPage = 10;

// Calculate the offset for the SQL query
$offset = ($page - 1) * $entriesPerPage;

// Initialize filter variables
$nameFilter = isset($_GET['name']) ? $_GET['name'] : '';
$typeFilter = isset($_GET['type']) ? $_GET['type'] : '';
$dateFilter = isset($_GET['date']) ? $_GET['date'] : '';

// Build the base query
$query = "SELECT * FROM transactionslnd WHERE 1=1";

// Add filters to the query
if (!empty($nameFilter)) {
    $query .= " AND user_name LIKE '%" . $conn->real_escape_string($nameFilter) . "%'";
}
if (!empty($typeFilter)) {
    $query .= " AND transaction_type = '" . $conn->real_escape_string($typeFilter) . "'";
}
if (!empty($dateFilter)) {
    $query .= " AND DATE(transaction_date) = '" . $conn->real_escape_string($dateFilter) . "'";
}

// Count the total number of transactions after applying filters
$countQuery = "SELECT COUNT(*) as total FROM ($query) as filtered";
$countResult = $conn->query($countQuery);
$totalEntries = $countResult->fetch_assoc()['total'];

// Calculate the total number of pages
$totalPages = ceil($totalEntries / $entriesPerPage);

// Fetch the transactions for the current page
$query .= " ORDER BY transaction_date DESC LIMIT $entriesPerPage OFFSET $offset";
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
    <script>
        // Function to submit the form automatically when inputs change
        function autoSubmit() {
            document.getElementById('filterForm').submit();
        }
    </script>
</head>
<body>

<?php include 'sidebarcash.php'; ?>
<div class="container mt-5">
    <h1 class="text-center">Transaction History</h1>

    <!-- Filter Form -->
    <form id="filterForm" method="GET" class="mb-4">
        <div class="form-row">
            <div class="col">
                <input type="text" name="name" class="form-control" placeholder="Search by Name" value="<?php echo htmlspecialchars($nameFilter); ?>" oninput="autoSubmit()">
            </div>
            <div class="col">
                <select name="type" class="form-control" onchange="autoSubmit()">
                    <option value="">All Types</option>
                    <option value="load" <?php echo $typeFilter == 'load' ? 'selected' : ''; ?>>Load</option>
                    <option value="deduct" <?php echo $typeFilter == 'deduct' ? 'selected' : ''; ?>>Deduct</option>
                </select>
            </div>
            <div class="col">
                <input type="date" name="date" class="form-control" value="<?php echo htmlspecialchars($dateFilter); ?>" onchange="autoSubmit()">
            </div>
        </div>
    </form>

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
            <a class="page-link" href="?page=1&name=<?= urlencode($nameFilter) ?>&type=<?= urlencode($typeFilter) ?>&date=<?= urlencode($dateFilter) ?>" aria-label="First">
                <span aria-hidden="true">««</span>
            </a>
        </li>

        <!-- Previous Page Button -->
        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $page - 1 ?>&name=<?= urlencode($nameFilter) ?>&type=<?= urlencode($typeFilter) ?>&date=<?= urlencode($dateFilter) ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>

        <!-- Page Numbers (showing a limited range around the current page) -->
        <?php 
        $start = max(1, $page - 2); // Start showing pages from 2 pages before the current page
        $end = min($totalPages, $page + 2); // End showing pages 2 pages after the current page
        
        for ($i = $start; $i <= $end; $i++): ?>
            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>&name=<?= urlencode($nameFilter) ?>&type=<?= urlencode($typeFilter) ?>&date=<?= urlencode($dateFilter) ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <!-- Next Page Button -->
        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $page + 1 ?>&name=<?= urlencode($nameFilter) ?>&type=<?= urlencode($typeFilter) ?>&date=<?= urlencode($dateFilter) ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>

        <!-- Last Page Button -->
        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $totalPages ?>&name=<?= urlencode($nameFilter) ?>&type=<?= urlencode($typeFilter) ?>&date=<?= urlencode($dateFilter) ?>" aria-label="Last">
                <span aria-hidden="true">»»</span>
            </a>
        </li>
    </ul>
</nav>

</div>
</body>
</html>