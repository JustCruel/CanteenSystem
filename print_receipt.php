<?php 
require __DIR__ . '/vendor/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

// Function to print the receipt
function printReceipt($cartItems, $totalAmount, $transactionNumber, $cashAmount, $change) {
    try {
        $printerPath = "smb://DESKTOP-K55P7CC/POS-58"; // Path to the printer
        $connector = new WindowsPrintConnector($printerPath);
        $printer = new Printer($connector);

        // Print receipt header
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("***** Holy Cross College *****\n");
        $printer->text("      Canteen Receipt      \n");
        $printer->text("============================\n");

        // Print cart items
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        foreach ($cartItems as $item) {
            $printer->text(sprintf("%-18s %2d x %-6s = P%-7s\n", $item['name'], $item['quantity'], number_format($item['price'], 2), number_format($item['price'] * $item['quantity'], 2)));
        }

        // Print total amount
        $printer->text("----------------------------\n");
        $printer->text(sprintf("%-12s P%-7s\n", "Cash Amount:", number_format($cashAmount, 2)));
        $printer->text(sprintf("%-12s P%-7s\n", "Total      :", number_format($totalAmount, 2)));
        $printer->text(sprintf("%-12s P%-7s\n", "Change     :", number_format($change, 2)));

        // Print transaction number
         // Print transaction number

        // Print thank you message
        $printer->feed(2);
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("Thank you for your purchase!\n");
        $printer->text("Visit us again! \n");
        $printer->text("TN: {$transactionNumber}\n");

        // Feed and cut the paper
        $printer->feed();
        $printer->cut();
        $printer->close();
    } catch (Exception $e) {
        echo "Could not print: " . $e->getMessage();
    }
}

// Receive sale data from the front end
$saleData = json_decode(file_get_contents('php://input'), true);

// Validate and process the received data
if (isset($saleData['items']) && isset($saleData['totalAmount']) && isset($saleData['transaction_number']) && isset($saleData['cashAmount']) && isset($saleData['change'])) {
    $cartItems = $saleData['items'];
    $totalAmount = $saleData['totalAmount'];
    $transactionNumber = $saleData['transaction_number']; 
    $cashAmount = $saleData['cashAmount'];// Get transaction number
    $change = $saleData['change'];

    // Call the print receipt function with transaction number
    printReceipt($cartItems, $totalAmount, $transactionNumber, $cashAmount, $change);

    // Respond to indicate the receipt was processed
    echo json_encode(['success' => true]);
} else {
    // If data is missing, return an error
    echo json_encode(['success' => false, 'message' => 'Invalid sale data']);
}

?>
