<?php 
include 'sidebarmis.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Search</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/stylesdisable.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
    <script src="script.js" defer></script>
    <style>
        .hidden {
            display: none;
        }
        .table-responsive {
            height: calc(100vh - 200px);
            overflow-y: auto;
            overflow-x: hidden;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
        }
        .container {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }
        h1 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center">Student Records</h1>
        <div class="input-group mb-3">
            <input type="text" id="searchBar" class="form-control" placeholder="Search for names...">
            <div class="input-group-append">
                <button class="btn btn-primary" onclick="searchStudents()">Search</button>
            </div>
        </div>
        
        <h1 class="text-center">This Accounts is activated</h1>
        <div class="table-responsive">
            <table id="studentTable" class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>RFID</th>
                        <th>Student ID</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Balance</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include "db.php";

                    $sql = "SELECT rfid_code, student_id, first_name, middle_name, last_name, email, balance FROM user WHERE is_activated = 1";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["rfid_code"] . "</td>";
                            echo "<td>" . $row["student_id"] . "</td>";
                            echo "<td>" . $row["first_name"] . "</td>";
                            echo "<td>" . $row["middle_name"] . "</td>";
                            echo "<td>" . $row["last_name"] . "</td>";
                            echo "<td>" . $row["email"] . "</td>";
                            echo "<td>" . $row["balance"] . "</td>";
                            echo "<td><button class='btn btn-danger' onclick='disableUser(\"" . $row["rfid_code"] . "\")'>Disable</button></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8' class='text-center'>No records found</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
   function searchStudents() {
        const input = document.getElementById('searchBar');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('studentTable');
        const rows = table.getElementsByTagName('tr');

        for (let i = 1; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            let found = false;

            for (let j = 0; j < cells.length; j++) {
                if (cells[j]) {
                    const cellValue = cells[j].textContent || cells[j].innerText;
                    if (cellValue.toLowerCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
            }
            rows[i].style.display = found ? "" : "none"; 
        }
    }

    document.getElementById('searchBar').addEventListener('keyup', searchStudents);
        function disableUser(rfidCode) {
           
    Swal.fire({
        title: 'Are you sure?',
        text: "You are about to deactivate the user with RFID: " + rfidCode,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, activate it!'
    }).then((result) => {
        // Check if the user confirmed the action
        if (result.isConfirmed) {
            const xhr = new XMLHttpRequest(); // Create a new XMLHttpRequest
            xhr.open("POST", "disable_user.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    const response = JSON.parse(xhr.responseText); // Parse JSON response

                    // Display SweetAlert based on response
                    if (response.status === "success") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            timer: 3000, // Auto close after 3 seconds
                            showConfirmButton: false
                        }).then(() => {
                            location.reload(); // Reload the page to see the updated table
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message,
                            timer: 3000, // Auto close after 3 seconds
                            showConfirmButton: false
                        });
                    }
                }
            };

            xhr.send("rfid_code=" + rfidCode); // Send the RFID code to the server
        }
    });
}

    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
