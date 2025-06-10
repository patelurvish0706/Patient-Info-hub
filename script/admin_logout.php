<?php
session_start();

// Destroy all admin session data
session_unset();
session_destroy();

// Optional: Redirect to login page
header("Location: ../manage.html");
exit;
?>
