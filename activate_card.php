<?php
include 'sidebarmis.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Search</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/stylesdisable.css"> <!-- Link to your CSS file -->
    <script src="script.js" defer></script> <!-- Link to your JavaScript file -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 Library -->
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

        /* Style for the waiting text box */
        #rfidInput {
            width: 100%;
            padding: 10px;
            font-size: 18px;
            margin-top: 20px;
            text-align: center;
            border: 2px solid #ccc;
            border-radius: 5px;
        }
        
        .swal2-input {
            z-index: 1051 !important; /* Ensure it's on top of any other elements */
            pointer-events: auto !important; /* Make sure the input is interactive */
        }

        /* Graying out disabled buttons */
.disabled-btn {
    background-color: #d6d6d6 !important;  /* Light gray */
    border-color: #ccc !important;         /* Lighter border */
    color: #888 !important;                /* Darker gray text */
    cursor: not-allowed !important;        /* Change cursor to indicate disabled state */
}

button:disabled {
    background-color: #d6d6d6 !important;
    border-color: #ccc !important;
    color: #888 !important;
    cursor: not-allowed !important;
}

        .modal-body {
        display: flex;
        justify-content: center;
        gap: 10px; /* Adjust the gap between buttons */
    }

    /* Optional: Align footer buttons */
    .modal-footer {
        justify-content: center; /* Center the close button */
    }

    /* Optional: Customize button width */
    .modal-body button {
        width: 150px; /* You can adjust the width of each button */
    }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center">Student Records</h1>
        <div class="input-group mb-3">
        <input type="text" id="searchBar" class="form-control" placeholder="Search for names..." oninput="searchStudents()">
   
        </div>
     
        <!-- Waiting RFID Scan Textbox -->
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
                        <th>Status</th> <!-- Add Status Column -->
                        <th>Actions</th> <!-- Modify Actions Column -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include "db.php";

                    // Modified SQL query to include is_activated column
                    $sql = "SELECT rfid_code, student_id, first_name, middle_name, last_name, email, balance, is_activated FROM user";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $hasRfid = !empty($row["rfid_code"]); // Check if RFID code exists
                            echo "<tr>";
                            echo "<td>" . $row["rfid_code"] . "</td>";
                            echo "<td>" . $row["student_id"] . "</td>";
                            echo "<td>" . $row["first_name"] . "</td>";
                            echo "<td>" . $row["middle_name"] . "</td>";
                            echo "<td>" . $row["last_name"] . "</td>";
                            echo "<td>" . $row["email"] . "</td>";
                            echo "<td>" . $row["balance"] . "</td>";
                            echo "<td>" . ($row["is_activated"] == 1 ? 'Activated' : 'Deactivated') . "</td>"; // Display status
                            echo "<td>";
                            echo "<button class='btn btn-primary' 
                            data-toggle='modal' 
                            data-target='#actionModal' 
                            data-student-id='" . addslashes($row["student_id"]) . "' 
                            onclick='showActionModal(\"" . addslashes($row["rfid_code"]) . "\", 
                                                     \"" . addslashes($row["first_name"] . " " . $row["last_name"]) . "\", 
                                                     this.getAttribute(\"data-student-id\"), 
                                                     " . $row["is_activated"] . ")'>
                            Action
                        </button>";
              
                        
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9' class='text-center'>No records found</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>

 <!-- Modal for Actions -->
<div class="modal fade" id="actionModal" tabindex="-1" aria-labelledby="actionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="actionModalLabel">Choose Action</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <button class="btn btn-success" id="activateBtn">Activate Account</button>
        <button class="btn btn-danger" id="deactivateBtn">Deactivate Account</button>
        <button class="btn btn-info" id="assignRfidBtn">Assign RFID</button>
        <button class="btn btn-warning" id="assignNewRfidBtn">Assign New RFID</button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


    <script>
        
        function showActionModal(rfidCode, studentName, studentId, isActivated) {
    $('#actionModal').modal('show');

    // Store the student ID in the button's data attribute for later use
    $('#assignRfidBtn').data('student-id', studentId); // Store student ID in the button's data attribute

    // Store the current RFID code to use in the assignNewRfid function
    $('#assignRfidBtn').data('current-rfid', rfidCode); // Store the current RFID code

    // Disable buttons based on the account's activation status
    if (isActivated == 1) {
        $('#activateBtn').addClass('disabled-btn').attr('disabled', true); // Disable Activate button
        $('#deactivateBtn').removeClass('disabled-btn').attr('disabled', false); // Enable Deactivate button
        $('#assignNewRfidBtn').removeClass('disabled-btn').attr('disabled', false); // Enable Assign New RFID button
    } else if (isActivated == 0) {
        $('#deactivateBtn').addClass('disabled-btn').attr('disabled', true); // Disable Deactivate button
        $('#activateBtn').removeClass('disabled-btn').attr('disabled', false); // Enable Activate button
        $('#assignNewRfidBtn').addClass('disabled-btn').attr('disabled', true); 
        $('#activateBtn').addClass('disabled-btn').attr('disabled', true);// Disable Assign New RFID button
    } else if (isActivated == 2) {
        $('#deactivateBtn').addClass('disabled-btn').attr('disabled', true); // Disable Deactivate button
        $('#activateBtn').removeClass('disabled-btn').attr('disabled', false); // Enable Activate button
        $('#assignNewRfidBtn').addClass('disabled-btn').attr('disabled', true); // Disable Assign New RFID button
    }

    // Disable "Assign RFID" button if the user already has an RFID assigned
    if (rfidCode) {
        $('#assignRfidBtn').addClass('disabled-btn').attr('disabled', true); // Disable Assign RFID button
    } else {
        $('#assignRfidBtn').removeClass('disabled-btn').attr('disabled', false); // Enable Assign RFID button
    }

    // Assign event handlers for each action with confirmation
    $('#activateBtn').off('click').on('click', function() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to activate this account?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, activate it!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                handleAction(rfidCode, 'activate');
            }
        });
    });

    $('#deactivateBtn').off('click').on('click', function() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to Deactivate this account?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Deactivate it!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                handleAction(rfidCode, 'deactivate');
            }
        });
    });

    $('#assignRfidBtn').off('click').on('click', function() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to assign an RFID to this account?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, assign it!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                assignRFID(rfidCode, 'assignRfid');
            }
        });
    });

    $('#assignNewRfidBtn').off('click').on('click', function() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to assign a new RFID to this account?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, assign new RFID!',
            cancelButtonText: 'No, cancel!'
 }).then((result) => {
            if (result.isConfirmed) {
                assignNewRfid(rfidCode, studentId);
            }
        });
    });
}

function assignNewRfid(rfidCode, studentId) {
    $('#actionModal').modal('hide'); 

    // Get the current RFID code from the button's data attribute
    const currentRfid = $('#assignRfidBtn').data('current-rfid');

    Swal.fire({
        title: 'Scan New RFID',
        input: 'text',
        inputAttributes: {
            autocapitalize: 'off',
            placeholder: 'Place the RFID card on the scanner...'
        },
        showCancelButton: true,
        confirmButtonText: 'Assign',
        showLoaderOnConfirm: true,
        preConfirm: (newRfid) => {
            return fetch('assign_new_rfid.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    studentId: studentId,
                    rfid: newRfid,
                    rfid_code: currentRfid // Now using the current RFID
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(response.statusText);
                }
                return response.json();
            })
            .catch(error => {
                Swal.showValidationMessage(`Request failed: ${error}`);
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        console.log(result);
        if (result.isConfirmed) {
            const data = result.value;
            if (data.success) {
                Swal.fire('Success!', data.message, 'success').then(() => {
                    // Handle success, e.g., reload or update UI
                    location.reload()
                });
            } else {
                Swal.fire('Error!', data.message, 'error'); // Show specific error
            }
        }
    });
}



function assignRFID(rfidCode, action) { 
    const actionMapping = {
        'assignRfid': 'assign_rfid.php'
    };

    if (action !== 'assignRfid') {
        console.error('Invalid action');
        return;
    }

    const studentId = $('#assignRfidBtn').data('student-id');
    console.log('Assign RFID to Student ID: ', studentId);

    if (!studentId) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Student ID',
            text: 'No student ID found. Please try again.',
            timer: 3000,
            showConfirmButton: false
        });
        return; 
    }
    $('#actionModal').modal('hide'); 
    Swal.fire({
        title: 'Scan RFID Code',
        input: 'text',
        inputPlaceholder: 'RFID Code will appear here...',
        showCancelButton: true,
        confirmButtonText: 'Assign RFID',
        
        preConfirm: () => {
            const rfidCode = Swal.getInput().value.trim(); // Use Swal.getInput()
            if (!rfidCode) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid RFID Code',
                    text: 'RFID code cannot be empty.',
                    timer: 3000,
                    showConfirmButton: false
                });
                return false; 
            }
            return rfidCode;
        }
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            const rfidCode = result.value.trim(); 

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "assign_rfid.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function() {
    if (xhr.readyState === XMLHttpRequest.DONE) {
        console.log(xhr.responseText);  // Log response to check it
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);

            if (response.status === "success") {
                Swal.fire({
                    icon: 'success',
                    title: response.message,
                    timer: 3000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload(); // Reload the page to show updated data
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message,
                    timer: 3000,
                    showConfirmButton: false
                });
            }
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Request Failed',
                text: 'There was an issue with the request. Please try again.',
                timer: 3000,
                showConfirmButton: false
            });
        }
    }
};


            xhr.send("student_id=" + studentId + "&rfid_code=" + encodeURIComponent(rfidCode));

        }
    });
}


// Handle action based on the action type
function handleAction(rfidCode, action) {
    const actionMapping = {
        'activate': 'activate_user.php',
        'deactivate': 'disable_user.php',
        'assignNewRfid': 'assign_new_rfid.php'
    };

    const xhr = new XMLHttpRequest();
    xhr.open("POST", actionMapping[action], true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            const response = JSON.parse(xhr.responseText);

            if (response.status === "success") {
                Swal.fire({
                    icon: 'success',
                    title: response.message,
                    timer: 3000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload(); // Reload the page to show updated data
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message,
                    timer: 3000,
                    showConfirmButton: false
                });
            }
        }
    };

    xhr.send("rfid_code=" + rfidCode);
}

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
    </script>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
