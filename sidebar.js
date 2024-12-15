// Function to toggle the sidebar
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const hamburger = document.getElementById('hamburger');
    const mainContent = document.querySelector('.main-content');
    const h1 = document.querySelector('.top-bar h1');  // Select the h1 element

    sidebar.classList.toggle('open');
    hamburger.classList.toggle('open');
    mainContent.classList.toggle('sidebar-open'); // This class should adjust the margin
    h1.classList.toggle('open');  // Toggle the visibility of the h1
}

// Initialize sidebar and hamburger state
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const hamburger = document.getElementById('hamburger');
    const mainContent = document.querySelector('.main-content');
    const h1 = document.querySelector('.top-bar h1');  // Select the h1 element

    if (window.innerWidth > 768) {
        sidebar.classList.add('open');
        hamburger.classList.add('open');
        mainContent.classList.add('sidebar-open'); // Adjust margin for main content
        h1.classList.remove('open');  // Ensure h1 is always visible on large screens
    }
});

// Handle window resize
window.addEventListener('resize', function() {
    const sidebar = document.getElementById('sidebar');
    const hamburger = document.getElementById('hamburger');
    const mainContent = document.querySelector('.main-content');
    const h1 = document.querySelector('.top-bar h1');  // Select the h1 element

    if (window.innerWidth > 768) {
        sidebar.classList.add('open');
        hamburger.classList.add('open');
        mainContent.classList.add('sidebar-open'); // Adjust margin for main content
        h1.classList.remove('open');  // Ensure h1 is visible when the screen is large
    } else {
        sidebar.classList.remove('open');
        hamburger.classList.remove('open');
        mainContent.classList.remove('sidebar-open'); // Reset margin for main content
        h1.classList.add('open');  // Hide h1 on smaller screens
    }
});

// Function to toggle the inventory submenu
function toggleSubmenu() {
    const submenu = document.getElementById('inventorySubmenu');
    submenu.classList.toggle('open');
}