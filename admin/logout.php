<?php
// Require database config which starts the session
require_once '../config/database.php';

// Unset only the admin session variables to keep any customer cart data intact
unset($_SESSION['admin_logged_in']);
unset($_SESSION['admin_id']);
unset($_SESSION['admin_username']);

// Redirect to login page
header('Location: login.php');
exit;
?>
