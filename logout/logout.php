<?php
session_start(); // Start the session

// Unset all session variables
$_SESSION = []; // Clear all session variables
session_destroy(); // Destroy the session

// Use JavaScript to update localStorage and then redirect to the login page
echo "<script>
     alert('You have been logged out successfully.'); // Display popup message
    localStorage.setItem('isLoggedIn', 'false'); // Set isLoggedIn to false
    window.location.href = '../index.html'; // Redirect to the login page
</script>";

exit();
