<?php
require_once '../config/database.php';
require_once 'includes/auth.php';

// Handle Add Product
if(isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category_id = (int)$_POST['category_id'];
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock_quantity'];
    
    mysqli_query($conn, "INSERT INTO products (name, description, category_id, price, stock_quantity) VALUES ('$name', '$description', $category_id, $price, $stock)");
    header('Location: products.php');
    exit;
}

// Handle Delete Product
if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM products WHERE id = $id");
    header('Location: products.php');
    exit;
}

// Handle Update Product
if(isset($_POST['update_product'])) {
    $id = (int)$_POST['product_id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category_id = (int)$_POST['category_id'];
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock_quantity'];
    
    mysqli_query($conn, "UPDATE products SET name = '$name', description = '$description', category_id = $category_id, price = $price, stock_quantity = $stock WHERE id = $id");
    header('Location: products.php');
    exit;
}

// Fetch Product to Edit
$edit_product = null;
if(isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $edit_query = mysqli_query($conn, "SELECT * FROM products WHERE id = $edit_id");
    if(mysqli_num_rows($edit_query) > 0) {
        $edit_product = mysqli_fetch_assoc($edit_query);
    }
}

require_once 'includes/header.php';

// Fetch Products with parent category names if they exist
$products = mysqli_query($conn, "
    SELECT p.*, c1.name as category_name, c2.name as parent_category_name 
    FROM products p 
    LEFT JOIN categories c1 ON p.category_id = c1.id 
    LEFT JOIN categories c2 ON c1.parent_id = c2.id 
    ORDER BY p.id DESC
");
// Fetch Categories for form, ordered hierarchically
$categories = mysqli_query($conn, "
    SELECT c1.id, c1.name, c2.name as parent_name 
    FROM categories c1 
    LEFT JOIN categories c2 ON c1.parent_id = c2.id 
    ORDER BY COALESCE(c1.parent_id, c1.id) ASC, c1.parent_id IS NOT NULL ASC, c1.name ASC
");
?>

<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Manage Products</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Add Product Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 lg:col-span-1 h-fit">
        <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">
            <?php echo $edit_product ? 'Edit Product' : 'Add New Product'; ?>
        </h3>
        <form action="products.php" method="POST">
            <?php if($edit_product): ?>
                <input type="hidden" name="product_id" value="<?php echo $edit_product['id']; ?>">
            <?php endif; ?>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Product Name</label>
                <input type="text" name="name" required value="<?php echo $edit_product ? htmlspecialchars($edit_product['name']) : ''; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent sm:text-sm p-2 border">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent sm:text-sm p-2 border"><?php echo $edit_product ? htmlspecialchars($edit_product['description']) : ''; ?></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Category</label>
                <select name="category_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent sm:text-sm p-2 border">
                    <?php while($cat = mysqli_fetch_assoc($categories)): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php if($edit_product && $edit_product['category_id'] == $cat['id']) echo 'selected'; ?>>
                            <?php 
                            if ($cat['parent_name']) {
                                echo htmlspecialchars($cat['parent_name'] . ' > ' . $cat['name']);
                            } else {
                                echo htmlspecialchars($cat['name']);
                            }
                            ?>
                        </option>
                    <?php endwhile; ?>
                    <?php 
                    // Reset the pointer in case we need it elsewhere
                    mysqli_data_seek($categories, 0); 
                    ?>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Price ($)</label>
                <input type="number" step="0.01" name="price" required value="<?php echo $edit_product ? htmlspecialchars($edit_product['price']) : ''; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent sm:text-sm p-2 border">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Stock Quantity</label>
                <input type="number" name="stock_quantity" required value="<?php echo $edit_product ? htmlspecialchars($edit_product['stock_quantity']) : ''; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent sm:text-sm p-2 border">
            </div>
            <?php if($edit_product): ?>
                <button type="submit" name="update_product" class="w-full bg-accent text-white py-2 px-4 rounded-md hover:bg-blue-700 font-medium transition-colors mb-2">Update Product</button>
                <a href="products.php" class="block w-full text-center bg-gray-100 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-200 font-medium transition-colors">Cancel</a>
            <?php else: ?>
                <button type="submit" name="add_product" class="w-full bg-accent text-white py-2 px-4 rounded-md hover:bg-blue-700 font-medium transition-colors">Add Product</button>
            <?php endif; ?>
        </form>
    </div>

    <!-- Product List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 lg:col-span-2">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Product Inventory</h3>
        </div>
        <div class="p-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php while($prod = mysqli_fetch_assoc($products)): ?>
                    <tr>
                        <td class="px-4 py-4 text-sm text-gray-500"><?php echo $prod['id']; ?></td>
                        <td class="px-4 py-4 text-sm font-medium text-gray-900"><?php echo htmlspecialchars($prod['name']); ?></td>
                        <td class="px-4 py-4 text-sm text-gray-500">
                            <?php 
                            if ($prod['parent_category_name']) {
                                echo htmlspecialchars($prod['parent_category_name'] . ' > ' . $prod['category_name']);
                            } else {
                                echo htmlspecialchars($prod['category_name']);
                            }
                            ?>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-500">$<?php echo $prod['price']; ?></td>
                        <td class="px-4 py-4 text-sm text-gray-500"><?php echo $prod['stock_quantity']; ?></td>
                        <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <a href="products.php?edit=<?php echo $prod['id']; ?>" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 hover:border-blue-300 rounded-md text-xs font-semibold shadow-sm transition-all duration-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </a>
                                <a href="products.php?delete=<?php echo $prod['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?')" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 border border-red-200 hover:border-red-300 rounded-md text-xs font-semibold shadow-sm transition-all duration-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
