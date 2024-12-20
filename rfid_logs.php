<?php
include "db.php";
include 'sidebarmis.php'; // Ensure to include your database connection

// Determine the filters from the request
$nameFilter = isset($_GET['name']) ? $_GET['name'] : '';
$typeFilter = isset($_GET['type']) ? $_GET['type'] : '';
$dateFilter = isset($_GET['date']) ? $_GET['date'] : '';

// Determine the current page number
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1); // Ensure the page is at least 1

// Set the number of entries per page
$entriesPerPage = 10;

// Calculate the offset for the SQL query
$offset = ($page - 1) * $entriesPerPage;

// Build the SQL query with pagination and filters
$sql = "SELECT rfid_code, user_name, action, action_time, user_id, modified FROM rfid_history";
$whereClauses = [];

if ($nameFilter !== '') {
    $whereClauses[] = "user_name LIKE '%$nameFilter%'";
}
if ($typeFilter !== '') {
    $whereClauses[] = "action = '$typeFilter'";
}
if ($dateFilter !== '') {
    $sql .= " WHERE action_time = '$dateFilter'";
}

if (!empty($whereClauses)) {
    $sql .= " WHERE " . implode(' AND ', $whereClauses);
}

$sql .= " ORDER BY action_time DESC LIMIT $entriesPerPage OFFSET $offset"; // Fetch logs with limits
$result = $conn->query($sql);

// Get the total number of logs for pagination
$countSql = "SELECT COUNT(*) as total FROM rfid_history";
if (!empty($whereClauses)) {
    $countSql .= " WHERE " . implode(' AND ', $whereClauses);
}
$countResult = $conn->query($countSql);
$totalEntries = $countResult->fetch_assoc()['total'];

// Calculate the total number of pages
$totalPages = ceil($totalEntries / $entriesPerPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activation/Deactivation Logs</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <style>
        body {
            overflow-x: hidden; /* Prevent horizontal scroll on body */
        }
        .container {
            margin-top: 20px;
        }
        table {
            margin-top: 20px;
            width: 100%; /* Extend the table to fill the container */
        }
        .table-responsive {
            max-height: 600px; /* Set maximum height for the table */
            overflow-y: auto; /* Enable vertical scroll */
        }
        .pagination {
            margin-top: 20px;
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
    <div class="main-content">
        <h1 class="text-center">Activation/Deactivation Logs</h1>

        <!-- Filter Form -->
        <form id="filterForm" method="GET" class="mb-4">
            <div class="form-row">
                <div class="col">
                    <input type="text" name="name" class="form-control" placeholder="Search by Name" value="<?php echo htmlspecialchars($nameFilter); ?>" oninput="autoSubmit()">
                </div>
                <div class="col">
                    <select name="type" class="form-control" onchange="autoSubmit()">
                        <option value="">All Types</option>
                        <option value="Activated" <?php echo $typeFilter == 'Activated' ? 'selected' : ''; ?>>Activated</option>
                        <option value="Deactivated" <?php echo $typeFilter == 'Deactivated' ? 'selected' : ''; ?>>Deactvated</option>
                    </select>
                </div>
                <div class="col">
                    <input type="date" name="date" class="form-control" value="<?php echo htmlspecialchars($dateFilter); ?>" onchange="autoSubmit()">
                </div>
            </div>
        </form>

        <!-- Table wrapped in a responsive container -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>RFID Code</th>
                        <th>User Name</th>
                        <th>Action</th>
                        <th>Action Time</th>
                        <th>User ID</th>
                        <th>Modified By</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if ($result->num_rows > 0) {
                    // Output data for each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["rfid_code"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["user_name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["action"]) . "</td>";
                        // Format action_time to display month as a full name
                        $formattedDate = date("F j, Y, g:i a", strtotime($row["action_time"]));
                        echo "<td>" . htmlspecialchars($formattedDate) . "</td>";
                        echo "<td>" . htmlspecialchars($row["user_id"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["modified"]) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No logs found</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>

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

                <!-- Page Numbers -->
                <?php
                // Determine range of pages to display around the current page
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

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
       
    </div>
</body>
</html>
