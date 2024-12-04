<?php 
require __DIR__ . '/vendor/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

// Function to print the receipt
function printReceipt($transactionData,$receiptData) {
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
        foreach ($transactionData['items'] as $item) {
            $printer->text(sprintf("%-18s %2d x %-6s = P%-7s\n", $item['name'], $item['quantity'], number_format($item['price'], 2), number_format($item['price'] * $item['quantity'], 2)));
        }

        // Print total amount
        $printer->text("----------------------------\n");
        $printer->text(sprintf("%-12s P%-7s\n", "Total:", number_format($transactionData['total_amount'], 2)));

        // Print transaction number and sale date
        $printer->text("Transaction No: " . $transactionData['transaction_number'] . "\n");
        $printer->text("Date: " . $transactionData['sale_date'] . "\n");

        // Print thank you message
        $printer->feed(2);
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("Thank you for your purchase!\n");
        $printer->text("Visit us again! \n");

        // Feed and cut the paper
        $printer->feed(2);
        $printer->cut();
        $printer->close();
    } catch (Exception $e) {
        echo "Could not print: " . $e->getMessage();
    }
}

// Receive receipt data from AJAX request
$receiptData = json_decode(file_get_contents('php://input'), true);

// Debugging: Output received receipt data
var_dump($receiptData);

// Call the print receipt function
printReceipt($receiptData);
?>
