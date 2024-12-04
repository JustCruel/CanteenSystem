<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the RFID from the form
    $rfid_code = trim($_POST['rfid']);
    
    // Fetch user details based on the RFID
    $stmt = $conn->prepare("SELECT id, first_name, last_name, balance FROM user WHERE rfid_code = ?");
    $stmt->bind_param("s", $rfid_code);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userId = $user['id'];
        $userName = $user['first_name'] . ' ' . $user['last_name'];
        $userBalance = $user['balance'];
    } else {
        $error = "RFID not found.";
    }
    
    // Load balance logic (example: adding a fixed amount)
    if (isset($_POST['load_amount'])) {
        $amount = (float)$_POST['amount'];
        if ($amount > 0) {
            $newBalance = $userBalance + $amount;
            // Update the user's balance in the database
            $updateStmt = $conn->prepare("UPDATE user SET balance = ? WHERE id = ?");
            $updateStmt->bind_param("di", $newBalance, $userId);
            $updateStmt->execute();
            
            // Check for success
            if ($updateStmt->affected_rows > 0) {
                $success = "Balance updated successfully. New balance: $" . number_format($newBalance, 2);
            } else {
                $error = "Failed to update balance.";
            }
        } else {
            $error = "Please enter a valid amount.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Load Balance</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
    <a href="cashierdashboard.php" class="btn btn-secondary">Back</a>
        <h1 class="text-center">Load Balance</h1>
        <form method="POST" class="mt-4">
            <div class="form-group">
                <label for="rfid">Scan RFID:</label>
                <input type="text" class="form-control" id="rfid" name="rfid" autofocus required>
            </div>
            <button type="submit" class="btn btn-primary">Get User Info</button>
        </form>

        <?php if (isset($user)): ?>
            <h2 class="mt-5">User Info</h2>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($userName); ?></p>
            <p><strong>Current Balance:</strong> $<?php echo number_format($userBalance, 2); ?></p>
            
            <form method="POST" class="mt-4">
                <input type="hidden" name="rfid" value="<?php echo htmlspecialchars($rfid_code); ?>">
                <div class="form-group">
                    <label for="amount">Amount to Load:</label>
                    <input type="number" class="form-control" id="amount" name="amount" min="1" required>
                </div>
                <button type="submit" name="load_amount" class="btn btn-success">Load Balance</button>
            </form>

            <?php if (isset($success)): ?>
                <div class="alert alert-success mt-3"><?php echo $success; ?></div>
            <?php elseif (isset($error)): ?>
                <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
            <?php endif; ?>
        <?php elseif (isset($error)): ?>
            <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
