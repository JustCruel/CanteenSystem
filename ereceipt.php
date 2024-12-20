<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'user') {
    header("Location: login.php");
    exit();
}

include 'db.php'; // Database connection

// Fetch user information
$userQuery = $conn->query("SELECT * FROM user WHERE id = '" . $_SESSION['user_id'] . "'");
$userData = $userQuery->fetch_assoc();

// Pagination logic
$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Get current page from URL, default to 1
$offset = ($page - 1) * $limit; // Calculate offset for SQL query

// Fetch all transactions and their product details with pagination
$allReceiptsQuery = $conn->query("
    SELECT er.id AS receipt_id, er.sale_date, er.total_amount, er.rfid_code, 
           er.transaction_number, 
           erd.product_id, erd.quantity_sold, erd.total, p.name AS product_name
    FROM e_receipts er 
    JOIN e_receipt_details erd ON er.id = erd.e_receipt_id 
    JOIN products p ON erd.product_id = p.id 
    WHERE er.rfid_code = '" . $userData['rfid_code'] . "' 
    ORDER BY er.sale_date DESC
    LIMIT $limit OFFSET $offset
");

$allReceipts = [];
while ($row = $allReceiptsQuery->fetch_assoc()) {
    $allReceipts[] = $row;
}

$noReceipts = empty($allReceipts);

// Get the total number of receipts to calculate the total number of pages
$totalReceiptsQuery = $conn->query("
    SELECT COUNT(DISTINCT er.id) AS total_receipts
    FROM e_receipts er 
    WHERE er.rfid_code = '" . $userData['rfid_code'] . "'
");
$totalReceipts = $totalReceiptsQuery->fetch_assoc()['total_receipts'];
$totalPages = ceil($totalReceipts / $limit); // Calculate total pages
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Receipts</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f6f9;
            height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        .main-content {
            flex: 1;
            overflow-y: auto;
            padding: 30px;
        }

        .container {
            max-width: 1200px;
            margin-top: 30px;
        }

        h1 {
            color: #343a40;
            margin-bottom: 20px;
        }

        .receipt-card {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.3s, box-shadow 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .receipt-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .receipt-card .transaction-id {
            font-weight: 600;
            color: #007bff;
        }

        .pagination {
            justify-content: center;
        }

        .pagination .page-item {
            margin: 0 5px;
        }

        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
            color: #fff;
        }

        .pagination .page-link {
            color: #007bff;
        }

        .modal-content {
            border-radius: 10px;
            padding: 20px;
        }

        .modal-header {
            border-bottom: 2px solid #007bff;
        }

        .modal-title {
            color: #007bff;
        }
    </style>
</head>
<body>
<?php include 'sidebaruser.php'; ?>
    <div class="main-content">
        <div class="container">
            <h1>E-Receipts</h1>
            <?php if ($noReceipts): ?>
                <div class="text-center text-muted">No receipts available.</div>
            <?php else: ?>
                <?php 
                $groupedReceipts = [];
                foreach ($allReceipts as $receipt) {
                    $groupedReceipts[$receipt['receipt_id']][] = $receipt;
                }

                foreach ($groupedReceipts as $receiptId => $items): ?>
                    <div class="receipt-card" data-transaction-number="<?php echo htmlspecialchars($items[0]['transaction_number']); ?>" sale_date="<?php echo date('d-M-Y H:i:s', strtotime($items[0]['sale_date'])); ?>">
                        <div><strong class="transaction-id-label">Transaction ID:</strong>  <?php echo htmlspecialchars($items[0]['transaction_number']); ?></div>
                        <div><strong>Date:</strong> <?php echo htmlspecialchars(date('d-M-Y H:i:s', strtotime($items[0]['sale_date']))); ?></div>
                        <div><strong>Total Amount:</strong> ₱<?php echo number_format($items[0]['total_amount'], 2); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <!-- Pagination controls -->
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                        <a class="page-link" href="?page=<?php echo max(1, $page - 1); ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php if ($page >= $totalPages) echo 'disabled'; ?>">
                        <a class="page-link" href="?page=<?php echo min($totalPages, $page + 1); ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="receiptModalLabel">Receipt Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="receipt-details"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
      document.querySelectorAll('.receipt-card').forEach(card => {
    card.addEventListener('click', function() {
        const transaction_number = this.getAttribute('data-transaction-number');
        const sale_date = this.getAttribute('sale_date');

        if (!transaction_number) {
            console.error('Transaction number is missing.');
            return;
        }

        fetch('get_transaction_details.php?transaction_number=' + transaction_number)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data && data.products) {
                    const receiptDetails = `
                        <p><strong>Transaction ID:</strong> ${transaction_number}</p>
                        <p><strong>Date:</strong> ${sale_date}</p>
                        <p><strong>Total Amount:</strong> ₱${parseFloat(data.total_amount).toFixed(2)}</p>
                        <h5>Purchased Products:</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.products.map(product => `
                                    <tr>
                                        <td>${product.product_name}</td>
                                        <td>${product.quantity_sold}</td>
                                        <td>₱${parseFloat(product.total).toFixed(2)}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>`;

                    document.getElementById('receipt-details').innerHTML = receiptDetails;
                    const receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
                    receiptModal.show();
                } else {
                    console.error('Invalid data structure returned.');
                }
            })
            .catch(error => console.error('Error fetching transaction details:', error));
    });
});


    </script>
</body>
</html>
