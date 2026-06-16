<?php
require_once '../config/database.php';
require_once 'includes/auth.php';

// Handle Add Category
if(isset($_POST['add_category'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $parent_id = $_POST['parent_id'] !== '' ? (int)$_POST['parent_id'] : 'NULL';
    
    mysqli_query($conn, "INSERT INTO categories (parent_id, name, description) VALUES ($parent_id, '$name', '$desc')");
    header('Location: categories.php');
    exit;
}

// Handle Delete Category
if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM categories WHERE id = $id");
    header('Location: categories.php');
    exit;
}

// Handle Update Category
if(isset($_POST['update_category'])) {
    $id = (int)$_POST['category_id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $parent_id = $_POST['parent_id'] !== '' ? (int)$_POST['parent_id'] : 'NULL';
    
    mysqli_query($conn, "UPDATE categories SET parent_id = $parent_id, name = '$name', description = '$desc' WHERE id = $id");
    header('Location: categories.php');
    exit;
}

// Fetch Category to Edit
$edit_category = null;
if(isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $edit_query = mysqli_query($conn, "SELECT * FROM categories WHERE id = $edit_id");
    if(mysqli_num_rows($edit_query) > 0) {
        $edit_category = mysqli_fetch_assoc($edit_query);
    }
}

require_once 'includes/header.php';

// Fetch Categories ordered hierarchically
$categories = mysqli_query($conn, "
    SELECT c1.*, c2.name as parent_name 
    FROM categories c1 
    LEFT JOIN categories c2 ON c1.parent_id = c2.id 
    ORDER BY COALESCE(c1.parent_id, c1.id) ASC, c1.parent_id IS NOT NULL ASC, c1.name ASC
");
?>

<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Manage Categories</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Add Category Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 lg:col-span-1 h-fit">
        <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">
            <?php echo $edit_category ? 'Edit Category' : 'Add New Category'; ?>
        </h3>
        <form action="categories.php" method="POST">
            <?php if($edit_category): ?>
                <input type="hidden" name="category_id" value="<?php echo $edit_category['id']; ?>">
            <?php endif; ?>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Category Name</label>
                <input type="text" name="name" required value="<?php echo $edit_category ? htmlspecialchars($edit_category['name']) : ''; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent sm:text-sm p-2 border">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Parent Category (Optional)</label>
                <select name="parent_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent sm:text-sm p-2 border">
                    <option value="">None (Top-Level Category)</option>
                    <?php 
                    $parent_categories = mysqli_query($conn, "SELECT * FROM categories WHERE parent_id IS NULL ORDER BY name ASC");
                    while($p_cat = mysqli_fetch_assoc($parent_categories)): 
                        if ($edit_category && $p_cat['id'] == $edit_category['id']) continue;
                    ?>
                        <option value="<?php echo $p_cat['id']; ?>" <?php if($edit_category && $edit_category['parent_id'] == $p_cat['id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($p_cat['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <p class="mt-1 text-xs text-gray-400">Select a parent category if this is a subcategory.</p>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent sm:text-sm p-2 border"><?php echo $edit_category ? htmlspecialchars($edit_category['description']) : ''; ?></textarea>
            </div>
            <?php if($edit_category): ?>
                <button type="submit" name="update_category" class="w-full bg-accent text-white py-2 px-4 rounded-md hover:bg-blue-700 font-medium transition-colors mb-2">Update Category</button>
                <a href="categories.php" class="block w-full text-center bg-gray-100 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-200 font-medium transition-colors">Cancel</a>
            <?php else: ?>
                <button type="submit" name="add_category" class="w-full bg-accent text-white py-2 px-4 rounded-md hover:bg-blue-700 font-medium transition-colors">Add Category</button>
            <?php endif; ?>
        </form>
    </div>

    <!-- Category List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 lg:col-span-2">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Category List</h3>
        </div>
        <div class="p-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php while($cat = mysqli_fetch_assoc($categories)): ?>
                    <tr class="<?php echo $cat['parent_id'] ? 'bg-gray-50/50' : 'bg-white'; ?>">
                        <td class="px-4 py-4 text-sm text-gray-500"><?php echo $cat['id']; ?></td>
                        <td class="px-4 py-4 text-sm font-medium text-gray-900">
                            <?php if($cat['parent_id']): ?>
                                <span class="text-gray-400 font-normal pl-4 mr-1">└─ </span>
                                <span class="text-gray-700 italic"><?php echo htmlspecialchars($cat['name']); ?></span>
                            <?php else: ?>
                                <span class="font-semibold text-gray-900"><?php echo htmlspecialchars($cat['name']); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-500">
                            <?php if($cat['parent_id']): ?>
                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded mr-2 font-medium">Sub of <?php echo htmlspecialchars($cat['parent_name']); ?></span>
                            <?php endif; ?>
                            <?php echo htmlspecialchars($cat['description']); ?>
                        </td>
                        <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <a href="categories.php?edit=<?php echo $cat['id']; ?>" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 hover:border-blue-300 rounded-md text-xs font-semibold shadow-sm transition-all duration-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </a>
                                <a href="categories.php?delete=<?php echo $cat['id']; ?>" onclick="return confirm('Deleting a category will delete all its products. Are you sure?')" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 border border-red-200 hover:border-red-300 rounded-md text-xs font-semibold shadow-sm transition-all duration-200">
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
