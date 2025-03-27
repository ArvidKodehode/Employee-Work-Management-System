<?php
session_start(); // Start the session to access session variables.
session_unset(); // Unset all session variables.
session_destroy(); // Destroy the session completely.

// Also delete localStorage via JavaScript (fallback if it exists).
echo "<script>
  localStorage.removeItem('user'); // Remove the 'user' item from localStorage.
  window.location.href = 'index.html'; // Redirect to the index.html page.
</script>";
exit; // Terminate the script execution.
