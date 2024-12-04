<?php
// Example of reading from serial port (Linux /dev/ttyUSB0)
$serial_port = '/dev/ttyUSB0'; // Adjust this to your actual serial port
exec("stty -F $serial_port 9600"); // Set serial port settings (baud rate, etc.)

// Open the serial port for reading
$fp = fopen($serial_port, "r");
if ($fp) {
    $rfid = fgets($fp, 128); // Read the RFID data
    fclose($fp);

    echo "RFID Code: " . htmlspecialchars($rfid);

    // Process the RFID tag ID here
} else {
    echo "Failed to open RFID reader!";
}
?>
