<?php
require_once 'config/database.php';
require_once 'includes/header.php';

// Fetch all products, optionally filter by category (including subcategories)
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : 0;

$query = "
    SELECT p.*, c1.name as category_name, c2.name as parent_category_name 
    FROM products p 
    LEFT JOIN categories c1 ON p.category_id = c1.id 
    LEFT JOIN categories c2 ON c1.parent_id = c2.id
";

if($category_id > 0) {
    // Retrieve subcategories of selected category
    $sub_query = "SELECT id FROM categories WHERE parent_id = $category_id";
    $sub_result = mysqli_query($conn, $sub_query);
    $category_ids = [$category_id];
    while($row = mysqli_fetch_assoc($sub_result)) {
        $category_ids[] = (int)$row['id'];
    }
    $category_ids_str = implode(',', $category_ids);
    $query .= " WHERE p.category_id IN ($category_ids_str)";
}
$result = mysqli_query($conn, $query);

// Fetch categories for the sidebar
$cat_query = "SELECT * FROM categories ORDER BY name ASC";
$cat_result = mysqli_query($conn, $cat_query);

$categories_tree = [];
$subcategories = [];

while ($cat = mysqli_fetch_assoc($cat_result)) {
    if ($cat['parent_id'] === null) {
        $categories_tree[$cat['id']] = $cat;
        $categories_tree[$cat['id']]['children'] = [];
    } else {
        $subcategories[] = $cat;
    }
}

// Assign subcategories to their parents
foreach ($subcategories as $sub) {
    if (isset($categories_tree[$sub['parent_id']])) {
        $categories_tree[$sub['parent_id']]['children'][] = $sub;
    }
}
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex flex-col md:flex-row gap-8">
        
        <!-- Sidebar: Categories -->
        <div class="w-full md:w-1/4">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 sticky top-24">
                <h3 class="text-lg font-bold text-primary mb-4 border-b pb-2">Categories</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="products.php" class="block py-1.5 text-gray-600 hover:text-accent <?php echo ($category_id == 0) ? 'font-bold text-accent' : ''; ?>">All Products</a>
                    </li>
                    <?php foreach ($categories_tree as $parent_cat): ?>
                        <li class="space-y-1">
                            <a href="products.php?category=<?php echo $parent_cat['id']; ?>" class="block py-1 text-gray-900 hover:text-accent font-semibold <?php echo ($category_id == $parent_cat['id']) ? 'text-accent font-bold' : ''; ?>">
                                <?php echo htmlspecialchars($parent_cat['name']); ?>
                            </a>
                            
                            <?php if (!empty($parent_cat['children'])): ?>
                                <ul class="pl-3 border-l border-gray-200 space-y-1 ml-1">
                                    <?php foreach ($parent_cat['children'] as $child_cat): ?>
                                        <li>
                                            <a href="products.php?category=<?php echo $child_cat['id']; ?>" class="block py-1 text-sm text-gray-500 hover:text-accent <?php echo ($category_id == $child_cat['id']) ? 'font-bold text-accent' : ''; ?>">
                                                <?php echo htmlspecialchars($child_cat['name']); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="w-full md:w-3/4">
            <h2 class="text-2xl font-extrabold text-primary mb-6">Our Collection</h2>
            
            <?php if(mysqli_num_rows($result) > 0): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-10 gap-x-6 xl:gap-x-8">
                    <?php while($product = mysqli_fetch_assoc($result)): ?>
                        <div class="group relative product-card bg-white rounded-lg p-4 border border-gray-50 shadow-sm">
                            <div class="w-full min-h-60 bg-gray-200 aspect-w-1 aspect-h-1 rounded-md overflow-hidden group-hover:opacity-75 lg:h-60 lg:aspect-none relative">
                                <img src="assets/images/<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full h-full object-center object-cover lg:w-full lg:h-full">
                            </div>
                            <div class="mt-4 flex justify-between">
                                <div>
                                    <h3 class="text-sm text-gray-700 font-medium line-clamp-1">
                                        <a href="product_details.php?id=<?php echo $product['id']; ?>">
                                            <span aria-hidden="true" class="absolute inset-0"></span>
                                            <?php echo htmlspecialchars($product['name']); ?>
                                        </a>
                                    </h3>
                                    <p class="mt-1 text-xs text-gray-500">
                                        <?php 
                                        if (isset($product['parent_category_name']) && $product['parent_category_name']) {
                                            echo htmlspecialchars($product['parent_category_name'] . ' > ' . $product['category_name']);
                                        } else {
                                            echo htmlspecialchars($product['category_name']);
                                        }
                                        ?>
                                    </p>
                                </div>
                                <p class="text-sm font-bold text-gray-900">$<?php echo number_format($product['price'], 2); ?></p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <p class="text-yellow-700">No products found in this category.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
