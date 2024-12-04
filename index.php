<!DOCTYPE html>
<html lang="tl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link sa iyong CSS file -->
    <style>
        /* Additional styles to hide content initially */
        .content-section {
            display: none; /* Hide all sections by default */
        }
        /* Keep this section visible by default */
        #home-dashboard {
            display: block; /* Show home dashboard by default */
        }
    </style>
</head>
<body>
    <header>
        User Dashboard
    </header>
    <div class="dashboard">
        <div class="sidebar">
            <ul>
                <li><a href="#" class="sidebar-link" data-target="home-dashboard">Home Dashboard</a></li>
                <li><a href="#" class="sidebar-link" data-target="user-profile">User Profile</a></li>
                <li><a href="#" class="sidebar-link" data-target="load-balance">Load Balance</a></li>
                <li><a href="#" class="sidebar-link" data-target="transaction-history">Transaction History</a></li>
                <li><a href="#" class="sidebar-link" data-target="activity-logs">Activity Logs</a></li>
                <li><a href="#" class="sidebar-link" data-target="total-spent">Total Spent</a></li>
                <li><a href="#" class="sidebar-link" data-target="reports">Reports</a></li>
                <li><a href="#" class="sidebar-link" data-target="logout">Log Out</a></li>
            </ul>
        </div>
        <div class="container">
            <main id="content">
                <div id="home-dashboard" class="content-section">
                    <h2>Welcome to Users Dashboard</h2>
                    <p>This is your home dashboard content.</p>
                </div>
                <div id="user-profile" class="content-section">
                    <h2>User Profile</h2>
                    <p>This is your user profile content.</p>
                </div>
                <div id="load-balance" class="content-section">
                    <h2>Load Balance</h2>
                    <p>This is your load balance content.</p>
                </div>
                <div id="transaction-history" class="content-section">
                    <h2>Transaction History</h2>
                    <p>This is your transaction history content.</p>
                </div>
                <div id="activity-logs" class="content-section">
                    <h2>Activity Logs</h2>
                    <p>This is your activity logs content.</p>
                </div>
                <div id="total-spent" class="content-section">
                    <h2>Total Spent</h2>
                    <p>This is your total spent content.</p>
                </div>
                <div id="reports" class="content-section">
                    <h2>Reports</h2>
                    <p>This is your reports content.</p>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Function to handle sidebar link clicks
        const links = document.querySelectorAll('.sidebar-link');
        const sections = document.querySelectorAll('.content-section');

        links.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default link behavior

                // Hide all sections
                sections.forEach(section => {
                    section.style.display = 'none';
                });

                // Show the selected section
                const target = this.getAttribute('data-target');
                document.getElementById(target).style.display = 'block';
            });
        });

        // Show the home dashboard by default on page load
        document.getElementById('home-dashboard').style.display = 'block';
    </script>
</body>
</html>
