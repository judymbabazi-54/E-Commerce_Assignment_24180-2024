<?php
// Start the session for shopping cart usage later
session_start();

if (file_exists(__DIR__ . '/db_production.php')) {
    include __DIR__ . '/db_production.php';
} else {
    $host = getenv('DB_HOST') ?: 'localhost';
    $username = getenv('DB_USER') ?: 'root';
    $password = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : '';
    $database = getenv('DB_NAME') ?: 'urban_style_store';
}

if (empty($database)) {
    die("Database configuration error: The database name is empty. Please verify that you have added the 'PROD_DB_NAME' secret in your GitHub repository settings and pushed the code.");
}

// Create a connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function get_image_url($image_url) {
    if (empty($image_url)) {
        return 'assets/images/default.jpg';
    }
    if (strpos($image_url, 'http://') === 0 || strpos($image_url, 'https://') === 0) {
        return $image_url;
    }
    
    // Check if we are inside the admin directory
    $current_dir = basename(dirname($_SERVER['PHP_SELF']));
    $prefix = ($current_dir === 'admin' || $current_dir === 'includes') ? '../assets/images/' : 'assets/images/';
    return $prefix . $image_url;
}
?>
