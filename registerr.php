<?php
require 'vendor/autoload.php'; // Automatically loads all dependencies

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "canteenms";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Upload</title>

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Bootstrap CSS (optional for styling) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

<?php
if (isset($_POST['display'])) {
    $file = $_FILES['file']['tmp_name'];
    $spreadsheet = IOFactory::load($file);
    $worksheet = $spreadsheet->getActiveSheet();
    $rows = $worksheet->toArray();

    echo "<h2 class='text-center mt-4'>Preview of Uploaded Users</h2>";
    echo "<form action='registerr.php' method='post'>";
    echo "<table class='table table-bordered mt-4'>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>";

    foreach ($rows as $index => $row) {
        if ($index === 0) continue; // Skip header row

        $student_id = htmlspecialchars($row[5]); // Student Number
        $first_name = htmlspecialchars($row[2]); // First Name
        $middle_name = htmlspecialchars($row[3]); // Middle Name
        $last_name = htmlspecialchars($row[1]);   // Last Name
        $email = htmlspecialchars($row[6]);       // Email

        echo "<tr>
                <td><input type='hidden' name='students[$index][student_id]' value='$student_id'>$student_id</td>
                <td><input type='hidden' name='students[$index][first_name]' value='$first_name'>$first_name</td>
                <td><input type='hidden' name='students[$index][middle_name]' value='$middle_name'>$middle_name</td>
                <td><input type='hidden' name='students[$index][last_name]' value='$last_name'>$last_name</td>
                <td><input type='hidden' name='students[$index][email]' value='$email'>$email</td>
            </tr>";
    }

    echo "</tbody></table>";
    echo "<div class='text-center'>
            <button type='submit' name='upload' class='btn btn-primary mt-3'>Confirm and Upload to Database</button>
          </div>";
    echo "</form>";
}

if (isset($_POST['upload'])) {
    $students = $_POST['students'];

    foreach ($students as $student) {
        $student_id = $conn->real_escape_string($student['student_id']);
        $first_name = $conn->real_escape_string($student['first_name']);
        $middle_name = $conn->real_escape_string($student['middle_name']);
        $last_name = $conn->real_escape_string($student['last_name']);
        $email = $conn->real_escape_string($student['email']);
        $rfid_code = "";  // Default RFID Code, if required
        $password = password_hash("mcmy_1946", PASSWORD_DEFAULT); // Default password
        $balance = "0.00"; // Default balance
        $user_type = "user"; // Default user type

        // Check if the user already exists
        $check_sql = "SELECT id FROM user WHERE student_id = '$student_id' OR email = '$email'";
        $result = $conn->query($check_sql);

        if ($result->num_rows > 0) {
            // User already exists, skip insertion
            continue;
        }

        // Insert the user if they do not exist
        $sql = "INSERT INTO user (student_id, first_name, middle_name, last_name, email, password, balance, user_type, is_activated) 
                VALUES ('$student_id', '$first_name', '$middle_name', '$last_name', '$email', '$password', '$balance', '$user_type', 0)";
        $conn->query($sql);
    }

    // Include SweetAlert2 after the operation
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.all.min.js'></script>";
    echo "<script>
            Swal.fire({
                title: 'Success!',
                text: 'Users have been successfully uploaded to the database.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = 'registeractivate.php'; // Redirect to registeractivate.php
                }
            });
          </script>";
}

$conn->close();
?>

<!-- Bootstrap JS (optional for functionality) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
