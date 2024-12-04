<?php
require __DIR__ . '/vendor/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

try {
    // Replace with the correct shared printer path
    $printerPath = "smb://DESKTOP-K55P7CC/POS-58";
    // Ensure double backslashes

    // Create a WindowsPrintConnector for the shared printer
    $connector = new WindowsPrintConnector($printerPath);

    // Initialize the printer
    $printer = new Printer($connector);

    // Print a sample receipt
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->text("POS-58 Test Print\n");
    $printer->text("=====================\n");
    $printer->text("Hello World!\n");
    $printer->text("Date: " . date("Y-m-d H:i:s") . "\n");
    $printer->feed(2);

    // Add some item lines
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer->text("Item 1: $10.00\n");
    $printer->text("Item 2: $5.00\n");
    $printer->feed(2);

    // Print a thank you message
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->text("Thank you for shopping!\n");
    $printer->feed(2);

    // Cut the paper
    $printer->cut();

    // Close the printer connection
    $printer->close();

    echo "Printed successfully!";
} catch (Exception $e) {
    echo "Could not print: " . $e->getMessage();
}
?>