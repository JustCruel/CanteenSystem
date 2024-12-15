<?php
session_start(); // Start the session
require 'vendor/autoload.php'; // Include PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\IOFactory;

// Variables
$displayTable = false; 
$uploadedData = []; 
$alertMessage = ''; // Variable to hold alert messages
$alertType = ''; // Variable to hold alert type (success, error, info)

// Handle file preview
if (isset($_POST['preview'])) {
    if (!empty($_FILES['excelFile']['tmp_name'])) {
        $fileType = IOFactory::identify($_FILES['excelFile']['tmp_name']);
        $reader = IOFactory::createReader($fileType);
        $spreadsheet = $reader->load($_FILES['excelFile']['tmp_name']);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        // Parse data for display
        $uploadedData = array_slice($sheetData, 1); // Skip the header row
        $_SESSION['uploadedData'] = $uploadedData; // Store in session
        $displayTable = true; // Enable table display
    } else {
        $alertMessage = 'Please select a file first!';
        $alertType = 'error';
    }
}

// Handle file upload
if (isset($_POST['upload'])) {
    $db = new mysqli("localhost", "root", "", "canteenms");
    $skippedProducts = [];

    foreach ($_SESSION['uploadedData'] as $row) {
        $barcode = $db->real_escape_string($row[0]);
        $name = $db->real_escape_string($row[1]);
        $market_price = $db->real_escape_string($row[2]);
        $selling_price = $db->real_escape_string($row[3]);
        $quantity = $db->real_escape_string($row[4]);
        $expiry_date = $db->real_escape_string($row[5]);
        $category = $db->real_escape_string($row[6]);
        $image_name = $db->real_escape_string($row[7]);

        // Check for duplicate barcode and name
        $check = $db->query("SELECT * FROM products WHERE barcode = '$barcode' AND name = '$name'");
        if ($check->num_rows == 0) {
            $db->query("INSERT INTO products (barcode, name, market_price, selling_price, quantity, expiry_date, category, image) 
                        VALUES ('$barcode', '$name', '$market_price', '$selling_price', '$quantity', '$expiry_date', '$category', '$image_name')");
        } else {
            $skippedProducts[] = $name;
        }
    }

    if (empty($skippedProducts)) {
        $alertMessage = 'Products successfully uploaded to the database!';
        $alertType = 'success';
    } else {
        $skippedList = implode(", ", $skippedProducts);
        $alertMessage = "Some products were skipped as they already exist: $skippedList";
        $alertType = 'info';
    }

    // Clear the session data after upload
    unset($_SESSION['uploadedData']);
}

// Retrieve uploaded data from session if available
if (isset($_SESSION['uploadedData'])) {
    $uploadedData = $_SESSION['uploadedData'];
    $displayTable = true; // Enable table display
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload and Preview Products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <?php include 'sidebar.php'; ?>
   
<div class="container mt-5">
    <h3 class="text-center">Upload and Preview Excel File</h3>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="excelFile" class="form-label">Select Excel File:</label>
            <input type="file" class="form-control" id="excelFile" name="excelFile" accept=".xlsx, .xls" required>
        </div>
        <button type="submit" name="preview" class=" btn btn-primary">Preview File</button>
        <a href="product_template.xls" class="btn btn-secondary">Download Template</a>
    </form>

    <?php if ($displayTable): ?>
        <hr>
        <h4 class="text-center">Preview of Uploaded File</h4>
        <form method="post" onsubmit="confirmUpload(event);">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Barcode</th>
                        <th>Name</th>
                        <th>Market Price</th>
                        <th>Selling Price</th>
                        <th>Quantity</th>
                        <th>Expiry Date</th>
                        <th>Category</th>
                        <th>Image Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($uploadedData as $index => $row): ?>
                        <tr>
                            <?php 
                            $barcode = htmlspecialchars($row[0] ?? '');
                            $name = htmlspecialchars($row[1] ?? '');
                            $market_price = htmlspecialchars($row[2] ?? '');
                            $selling_price = htmlspecialchars($row[3] ?? '');
                            $quantity = htmlspecialchars($row[4] ?? '');
                            $expiry_date = htmlspecialchars($row[5] ?? '');
                            $category = htmlspecialchars($row[6] ?? '');
                            $image_name = htmlspecialchars($row[7] ?? '');
                            ?>
                            <td><input type="hidden" name="data[<?= $index ?>][barcode]" value="<?= $barcode ?>"><?= $barcode ?></td>
                            <td><input type="hidden" name="data[<?= $index ?>][name]" value="<?= $name ?>"><?= $name ?></td>
                            <td><input type="hidden" name="data[<?= $index ?>][market_price]" value="<?= $market_price ?>"><?= $market_price ?></td>
                            <td><input type="hidden" name="data[<?= $index ?>][selling_price]" value="<?= $selling_price ?>"><?= $selling_price ?></td>
                            <td><input type="hidden" name="data[<?= $index ?>][quantity]" value="<?= $quantity ?>"><?= $quantity ?></td>
                            <td><input type="hidden" name="data[<?= $index ?>][expiry_date]" value="<?= $expiry_date ?>"><?= $expiry_date ?></td>
                            <td><input type="hidden" name="data[<?= $index ?>][category]" value="<?= $category ?>"><?= $category ?></td>
                            <td><input type="hidden" name="data[<?= $index ?>][image_name]" value="<?= $image_name ?>"><?= $image_name ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" name="upload" class="btn btn-success">Upload to Database</button>
        </form>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmUpload(event) {
    event.preventDefault(); // Prevent the default form submission
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to upload these products to the database?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, upload it!',
        cancelButtonText: 'No, cancel!'
    }).then((result) => {
        if (result.isConfirmed) {
            // If confirmed, set the upload button name and submit the form
            const form = event.target; // Get the form element
            const uploadButton = document.createElement('input'); // Create a new input element
            uploadButton.type = 'hidden'; // Set the type to hidden
            uploadButton.name = 'upload'; // Set the name to 'upload'
            uploadButton.value = '1'; // Set a value (can be anything)
            form.appendChild(uploadButton); // Append the input to the form
            form.submit(); // Submit the form
        }
    });
}

// Display alert message if exists
const alertMessage = "<?php echo $alertMessage; ?>";
const alertType = "<?php echo $alertType; ?>";

if (alertMessage) {
    Swal.fire({
        title: alertType === 'success' ? 'Success' : alertType === 'error' ? 'Error' : 'Notice',
        text: alertMessage,
        icon: alertType,
    });
}
</script>
</body>
</html>