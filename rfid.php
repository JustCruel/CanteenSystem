<?php
session_start();
$host = 'localhost'; // Your database host
$db = 'rfidtest';  // Your database name
$user = 'root';      // Your database username
$pass = '';          // Your database password

// Create a new PDO connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Function to get balance by RFID code
function getRFIDBalance($rfid_code, $pdo) {
    $stmt = $pdo->prepare("SELECT balance FROM rfid_cards WHERE rfid_code = ?");
    $stmt->execute([$rfid_code]);
    return $stmt->fetchColumn(); // Returns the balance or false if not found
}

// Function to update balance (e.g., add or subtract funds)
function updateRFIDBalance($rfid_code, $amount, $pdo) {
    $stmt = $pdo->prepare("UPDATE rfid_cards SET balance = balance + ? WHERE rfid_code = ?");
    return $stmt->execute([$amount, $rfid_code]);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['rfid'])) {
    $rfid = htmlspecialchars($_POST['rfid']);
    // Retrieve the current balance for the RFID card
    $balance = getRFIDBalance($rfid, $pdo);
    
    if ($balance !== false) {
        // Store RFID and balance in session for further processing
        $_SESSION['rfid'] = $rfid;
        $_SESSION['balance'] = $balance;

        // Maintain a list of scanned RFID codes in session
        $_SESSION['rfid_list'] = [$rfid];  // Always replace the current RFID with the new one
    } else {
        $error = "RFID not found in the system!";
    }
}

// Handle updating the balance (e.g., adding or deducting funds)
if (isset($_POST['update_balance']) && isset($_SESSION['rfid'])) {
    $amount = floatval($_POST['amount']);
    if (updateRFIDBalance($_SESSION['rfid'], $amount, $pdo)) {
        // Update session balance after adding/deducting
        $_SESSION['balance'] += $amount;
        $success = "Balance updated successfully!";
    } else {
        $error = "Failed to update balance!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>RFID Balance System</title>
    <script>
        // Automatically clear the RFID input field after submission
        window.onload = function() {
            var rfidInput = document.getElementById('rfid');
            rfidInput.value = ''; // Clear the RFID input field
        };
    </script>
</head>
<body>
    <h2>Tap your RFID Card</h2>

    <form action="" method="post">
        <label for="rfid">RFID Code:</label>
        <!-- RFID input will be automatically cleared after form submission -->
        <input type="text" id="rfid" name="rfid" autofocus required>
        <input type="submit" value="Submit">
    </form>

    <?php if (isset($balance)): ?>
        <h3>Current Balance: ₱<?php echo number_format($_SESSION['balance'], 2); ?></h3>

        <!-- Form to add or deduct balance -->
        <form action="" method="post">
            <label for="amount">Update Balance (₱):</label>
            <input type="number" step="0.01" id="amount" name="amount" required>
            <input type="submit" name="update_balance" value="Update Balance">
        </form>

        <?php if (isset($success)) echo "<p style='color: green;'>$success</p>"; ?>
    <?php elseif (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <!-- Display list of scanned RFID codes -->
    <?php if (isset($_SESSION['rfid_list']) && count($_SESSION['rfid_list']) > 0): ?>
        <h3>Scanned RFID Code:</h3>
        <ul>
            <?php foreach ($_SESSION['rfid_list'] as $scanned_rfid): ?>
                <li><?php echo htmlspecialchars($scanned_rfid); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>
