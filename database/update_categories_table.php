<?php
require_once dirname(__DIR__) . '/config/database.php';

echo "Altering table 'categories' for subcategories support...\n";

// 1. Check if parent_id column already exists
$checkColumnQuery = "SHOW COLUMNS FROM categories LIKE 'parent_id'";
$checkColumnResult = mysqli_query($conn, $checkColumnQuery);

if (mysqli_num_rows($checkColumnResult) === 0) {
    // Column does not exist, let's alter table to add parent_id column and foreign key constraint
    $alterQuery = "ALTER TABLE categories 
                   ADD COLUMN parent_id INT NULL DEFAULT NULL AFTER id, 
                   ADD CONSTRAINT fk_parent_category FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE CASCADE";
    
    if (mysqli_query($conn, $alterQuery)) {
        echo "Successfully added 'parent_id' column and constraint fk_parent_category to 'categories' table.\n";
    } else {
        die("Error altering categories table: " . mysqli_error($conn) . "\n");
    }
} else {
    echo "Column 'parent_id' already exists in 'categories' table.\n";
}

// 2. Add some sample subcategories for testing if they do not exist
echo "Checking/seeding sample subcategories...\n";

// Helper function to check and insert subcategory
function insertSubcategoryIfMissing($conn, $parentName, $subName, $subDesc) {
    // Get parent ID
    $parentQuery = "SELECT id FROM categories WHERE name = '" . mysqli_real_escape_string($conn, $parentName) . "' AND parent_id IS NULL LIMIT 1";
    $parentRes = mysqli_query($conn, $parentQuery);
    
    if ($parentRes && mysqli_num_rows($parentRes) > 0) {
        $parent = mysqli_fetch_assoc($parentRes);
        $parentId = $parent['id'];
        
        // Check if subcategory already exists
        $subQuery = "SELECT id FROM categories WHERE name = '" . mysqli_real_escape_string($conn, $subName) . "' AND parent_id = $parentId LIMIT 1";
        $subRes = mysqli_query($conn, $subQuery);
        
        if (mysqli_num_rows($subRes) === 0) {
            $insertQuery = "INSERT INTO categories (parent_id, name, description) VALUES ($parentId, '" . mysqli_real_escape_string($conn, $subName) . "', '" . mysqli_real_escape_string($conn, $subDesc) . "')";
            if (mysqli_query($conn, $insertQuery)) {
                echo "Added subcategory '$subName' under parent category '$parentName'.\n";
            } else {
                echo "Error adding subcategory '$subName': " . mysqli_error($conn) . "\n";
            }
        } else {
            echo "Subcategory '$subName' already exists under '$parentName'.\n";
        }
    } else {
        echo "Parent category '$parentName' not found or is a subcategory. Skipping seeding for '$subName'.\n";
    }
}

insertSubcategoryIfMissing($conn, 'T-Shirts', 'Graphic Tees', 'T-shirts with premium printed graphics.');
insertSubcategoryIfMissing($conn, 'T-Shirts', 'Plain Basics', 'Comfortable everyday solid color tees.');
insertSubcategoryIfMissing($conn, 'Hoodies', 'Pullover Hoodies', 'Heavyweight classic pullover style hoodies.');
insertSubcategoryIfMissing($conn, 'Hoodies', 'Zip-Up Hoodies', 'Comfortable zippered hoodies.');
insertSubcategoryIfMissing($conn, 'Jeans', 'Slim Fit Jeans', 'Form-fitting modern denim.');
insertSubcategoryIfMissing($conn, 'Jeans', 'Regular Fit Jeans', 'Classic straight-leg cut jeans.');

echo "Category structure update completed.\n";
?>
