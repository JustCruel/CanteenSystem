<?php
session_start();
include 'db.php'; // Include your database connection
include 'sidebarcash.php';

$user = null;
$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = null;

    if (isset($_POST['rfid'])) {
        $rfid_code = trim($_POST['rfid']);

        // Fetch user details including is_activated status
        $stmt = $conn->prepare("SELECT id, first_name, last_name, balance, is_activated FROM user WHERE rfid_code = ?");
        $stmt->bind_param("s", $rfid_code);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Check if the user is deactivated
            if ($user['is_activated'] == 0) {
                $error = "This account is deactivated and cannot load or deduct balance.";
                $user = null; // Clear user data if deactivated
            }
        } else {
            $error = "RFID not found.";
        }
    }
}
?>


<!-- Sidebar Layout -->
<div class="main-content">
    <a href="cashierdashboard.php" class="btn btn-secondary">Back</a>
    <h1 class="text-center">Manage Balance</h1>
    <!-- Inside your form -->
    <form method="POST" class="mt-4" id="manageBalanceForm">
        <div class="form-group">
            <label for="rfid">Scan RFID:</label>
            <input type="text" class="form-control mb-3" id="rfid" name="rfid" autocomplete="off" autofocus required oninput="submitIfValid(this)">
        </div>
    </form>
    <?php if (isset($user)): ?>
    <div class="card">
        <h2 class="text-center">User Info</h2>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></p>
        <p><strong>Current Balance:</strong> P<?php echo number_format($user['balance'], 2); ?></p>
        <p><strong>Card Status:</strong> <?php echo ($user['is_activated'] == 1) ? "Activated" : "Deactivated"; ?></p>

        <?php if ($user['is_activated'] == 1): ?>
            <!-- Show Load/Deduct buttons only if activated -->
            <div class="text-center">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#loadDeductModal" data-action="load">Load Balance</button>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#loadDeductModal" data-action="deduct">Deduct Balance</button>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center">This account is deactivated and cannot load or deduct balance.</div>
        <?php endif; ?>
    </div>
<?php elseif (isset($error)): ?>
    <div class="alert alert-danger mt-3 text-center"><?php echo $error; ?></div>
<?php endif; ?>

</div>

<!-- Modal for Load/Deduct -->
<!-- Modal for Load/Deduct -->
<div class="modal fade" id="loadDeductModal" tabindex="-1" role="dialog" aria-labelledby="loadDeductModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="loadDeductModalLabel">Load or Deduct Balance</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- The form to handle Load or Deduct actions -->
        <form method="POST" id="loadDeductForm">
            <input type="hidden" name="rfid" value="<?php echo htmlspecialchars($rfid_code); ?>">
            <input type="hidden" name="action" id="actionInput"> <!-- Hidden input for action (load or deduct) -->

            <div class="form-group">
                <label for="amount">Amount:</label>
                
                <!-- Buttons for preset amounts -->
                

                <!-- Input field for custom amount -->
                <input type="number" class="form-control" id="amount" name="amount" min="1" required>
            </div>
            <div class="d-flex flex-wrap mb-2">
                    <?php 
                    $amounts = [50, 100, 150, 200, 250, 500, 600, 700, 800, 900, 1000];
                    foreach ($amounts as $amt) {
                        echo '<button type="button" class="btn btn-outline-primary m-1 preset-amount" data-amount="' . $amt . '">P' . $amt . '</button>';
                    }
                    ?>
                </div>
            <button type="submit" class="btn btn-primary btn-block">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Add SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css" rel="stylesheet">

<!-- Add SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.js"></script>

<!-- Bootstrap JS (make sure this is after jQuery) -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
    document.querySelectorAll('.preset-amount').forEach(button => {
        button.addEventListener('click', function() {
            const amount = this.getAttribute('data-amount');
            console.log("Button clicked with amount:", amount); // Debugging log
            document.getElementById('amount').value = amount; // Set the value in the amount input field
        });
    });

document.addEventListener('DOMContentLoaded', function() {
    // Handle the action selection when a button is clicked (Load or Deduct)
    $('#loadDeductModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var action = button.data('action'); // Extract action (load or deduct)

        // Set the action input to the corresponding value (load or deduct)
        $('#actionInput').val(action);

        // Change modal title dynamically based on action
        var modalTitle = (action == 'load') ? 'Load Balance' : 'Deduct Balance';
        $('.modal-title').text(modalTitle);
    });

    // Handle form submission for Load/Deduct
    document.getElementById('loadDeductForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const action = document.getElementById('actionInput').value;
        const amount = document.getElementById('amount').value;

        if (!amount || amount <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Amount',
                text: 'Please enter a valid amount.',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Confirm action (load or deduct)
        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to ${action} P${amount} to this account.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: `Yes, ${action} it!`,
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData(document.getElementById('loadDeductForm'));
                formData.append('action', action); // Add the action (load or deduct)

                let url = (action == 'load') ? 'load_balance.php' : 'deduct_balance.php'; // Decide which PHP file to submit to

                fetch(url, {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.success,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload(); // Reloads the page
                        });
                    } else if (data.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.error,
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An unexpected error occurred.',
                        confirmButtonText: 'OK'
                    });
                });
            }
        });
    });
});
</script>
