document.addEventListener('DOMContentLoaded', function () {
    const links = document.querySelectorAll('.nav-link');
    const contentDiv = document.getElementById('content');

    links.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault(); // Iwasan ang default na pag-refresh ng page
            const target = this.getAttribute('data-target');

            // I-clear ang current content
            contentDiv.innerHTML = '';

            // I-display ang nilalaman batay sa napiling item
            switch (target) {
                case 'home-dashboard':
                    contentDiv.innerHTML = `<h2>Home Dashboard</h2><p>Welcome back to the home dashboard!</p>`;
                    break;
                case 'user-profile':
                    contentDiv.innerHTML = `<h2>User Profile</h2><p>Update your profile information here.</p>`;
                    break;
                case 'loadbalance':
                    contentDiv.innerHTML = `<h2>Load Balance</h2><p>Your current load balance is <strong>$50.00</strong></p>`;
                    break;
                case 'transaction-history':
                    contentDiv.innerHTML = `<h2>Transaction History</h2><p>Your recent transactions are shown here.</p>`;
                    break;
                case 'activity-logs':
                    contentDiv.innerHTML = `<h2>Activity Logs</h2><p>Your activity logs are listed here.</p>`;
                    break;
                case 'total-spent-chart':
                    contentDiv.innerHTML = `<h2>Total Spent</h2><p>Your spending chart is displayed here.</p>`;
                    break;
                case 'reports':
                    contentDiv.innerHTML = `<h2>Reports</h2><p>Generate and view your reports here.</p>`;
                    break;
                case 'logout':
                    contentDiv.innerHTML = `<h2>Log Out</h2><p>You have successfully logged out.</p>`;
                    break;
                default:
                    contentDiv.innerHTML = `<h2>Select an option from the sidebar</h2><p>Click on the items in the sidebar to see their content here.</p>`;
            }
        });
    });
});
