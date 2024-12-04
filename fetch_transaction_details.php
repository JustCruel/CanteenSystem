<?php 
include 'db.php';

// Fetch all receipts
$allReceipts = [];
$noReceipts = true; // Default to no receipts

$query = $conn->query("
    SELECT er.id AS receipt_id, er.transaction_number, er.sale_date, er.total_amount
    FROM e_receipts er
");

if ($query && $query->num_rows > 0) {
    while ($row = $query->fetch_assoc()) {
        $allReceipts[] = $row;
    }
    $noReceipts = false; // Set to false if receipts are found
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Receipts</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .transaction-list {
            max-width: 800px;
            margin: auto;
        }
        .transaction-item {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            margin-bottom: 10px;
            padding: 15px;
            cursor: pointer;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }
        .transaction-item:hover {
            transform: scale(1.02);
        }
        .no-transactions {
            text-align: center;
            font-size: 1.25rem;
            color: #777;
        }
    </style>
</head>
<body>
<?php include 'sidebaruser.php'; ?>

<div class="main-content">
    <div class="transaction-list">
        <h1>E-Receipts</h1>
        <?php if ($noReceipts): ?>
            <div class="no-transactions">No transactions available.</div>
        <?php else: ?>
            <?php foreach ($allReceipts as $receipt): ?>
    <div class="transaction-item" data-bs-toggle="modal" data-bs-target="#receiptModal" 
         data-transaction-id="<?php echo htmlspecialchars($receipt['receipt_id']); ?>">
        <strong>Transaction Number:</strong> <?php echo htmlspecialchars($receipt['transaction_number']); ?><br>
        <strong>Date:</strong> <?php echo date('d-M-Y h:i:s A', strtotime($receipt['sale_date'])); ?><br>
        <strong>Total Amount:</strong> ₱<?php echo number_format($receipt['total_amount'], 2); ?>
    </div>
<?php endforeach; ?>

        <?php endif; ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="receiptModalLabel">Transaction Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity Sold</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody id="transactionDetails"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.transaction-item').forEach(item => {
        item.addEventListener('click', function() {
            const receiptId = this.getAttribute('data-transaction-id');
            fetchTransactionDetails(receiptId);
        });
    });

    function fetchTransactionDetails(receiptId) {
        const detailsContainer = document.getElementById('transactionDetails');
        detailsContainer.innerHTML = '<tr><td colspan="3">Loading...</td></tr>';
        
        fetch(`fetch_transaction_details.php?receipt_id=${receiptId}`)
            .then(response => response.json())
            .then(data => {
                detailsContainer.innerHTML = data.map(item => `
                    <tr>
                        <td>${item.product_name}</td>
                        <td>${item.quantity_sold}</td>
                        <td>₱${parseFloat(item.total).toFixed(2)}</td>
                    </tr>
                `).join('');
            })
            .catch(error => {
                detailsContainer.innerHTML = '<tr><td colspan="3">Error loading details.</td></tr>';
            });
    }
</script>
</body>
</html>
