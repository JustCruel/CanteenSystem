<?php
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

// Function to check if RFID already exists
function checkRFIDExists($rfid_code, $pdo) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM rfid_cards WHERE rfid_code = ?");
    $stmt->execute([$rfid_code]);
    return $stmt->fetchColumn() > 0;
}

// Function to register a new RFID card
function registerRFIDCard($rfid_code, $owner_name, $initial_balance, $pdo) {
    $stmt = $pdo->prepare("INSERT INTO rfid_cards (rfid_code, owner_name, balance) VALUES (?, ?, ?)");
    return $stmt->execute([$rfid_code, $owner_name, $initial_balance]);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rfid_code = htmlspecialchars($_POST['rfid_code']);
    $owner_name = htmlspecialchars($_POST['owner_name']);
    $initial_balance = floatval($_POST['initial_balance']);

    // Check if RFID already exists
    if (checkRFIDExists($rfid_code, $pdo)) {
        $error = "RFID code already exists! Please use a different card.";
    } else {
        // Register the new RFID card
        if (registerRFIDCard($rfid_code, $owner_name, $initial_balance, $pdo)) {
            $success = "RFID card registered successfully!";
        } else {
            $error = "Failed to register RFID card.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>RFID Card Registration</title>
</head>
<body>
    <h2>RFID Card Registration</h2>

    <!-- Registration Form -->
    <form action="" method="post">
        <label for="rfid_code">RFID Code:</label>
        <input type="text" id="rfid_code" name="rfid_code" required><br><br>

        <label for="owner_name">Owner Name:</label>
        <input type="text" id="owner_name" name="owner_name" required><br><br>

        <label for="initial_balance">Initial Balance (₱):</label>
        <input type="number" step="0.01" id="initial_balance" name="initial_balance" value="0.00" required><br><br>

        <input type="submit" value="Register Card">
    </form>

    <!-- Display success or error messages -->
    <?php if (isset($success)) echo "<p style='color: green;'>$success</p>"; ?>
    <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
</body>
</html>
