<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Use absolute path referencing for inclusion
require_once dirname(__DIR__) . '/config/database.php';

echo "Starting database update...\n";

// 1. Create Admins Table if it doesn't exist
$createTableQuery = "CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $createTableQuery)) {
    echo "Table 'admins' checked/created successfully.\n";
} else {
    die("Error creating table: " . mysqli_error($conn) . "\n");
}

// 2. Check if admin user already exists
$checkQuery = "SELECT id FROM admins WHERE username = 'admin'";
$checkResult = mysqli_query($conn, $checkQuery);

if (mysqli_num_rows($checkResult) === 0) {
    // Seed default admin (username: admin, password: admin123)
    $username = 'admin';
    $password = 'admin123';
    $email = 'admin@urbanstyle.com';
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $insertQuery = "INSERT INTO admins (username, password, email) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insertQuery);
    mysqli_stmt_bind_param($stmt, "sss", $username, $hashedPassword, $email);

    if (mysqli_stmt_execute($stmt)) {
        echo "Default admin user 'admin' seeded successfully with password 'admin123'.\n";
    } else {
        echo "Error seeding default admin user: " . mysqli_error($conn) . "\n";
    }
    mysqli_stmt_close($stmt);
} else {
    echo "Admin user 'admin' already exists. Seeding skipped.\n";
}

echo "Database update completed.\n";
?>
