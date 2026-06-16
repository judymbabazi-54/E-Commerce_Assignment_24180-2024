<?php
// Temporary script to reset the admin password to 'admin123'
require_once dirname(__DIR__) . '/config/database.php';

$username = 'admin';
$new_password = 'admin123';
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// Update password in the database
$query = "UPDATE admins SET password = ? WHERE username = ?";
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ss", $hashed_password, $username);
    if (mysqli_stmt_execute($stmt)) {
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "Success: The password for user 'admin' has been reset to 'admin123'.<br>";
            echo "<strong>IMPORTANT: Please delete this file (database/reset_password.php) immediately for security.</strong>";
        } else {
            echo "No changes made. Either the user 'admin' does not exist in the database, or the password was already set to this value.";
        }
    } else {
        echo "Error executing statement: " . mysqli_stmt_error($stmt);
    }
    mysqli_stmt_close($stmt);
} else {
    echo "Error preparing statement: " . mysqli_error($conn);
}
?>
