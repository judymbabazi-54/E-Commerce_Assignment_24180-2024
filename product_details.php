<?php
require_once 'config/database.php';
require_once 'includes/header.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$query = "
    SELECT p.*, c1.name as category_name, c1.parent_id, c2.name as parent_category_name 
    FROM products p 
    LEFT JOIN categories c1 ON p.category_id = c1.id 
    LEFT JOIN categories c2 ON c1.parent_id = c2.id 
    WHERE p.id = $product_id
";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 0) {
    echo "<div class='max-w-7xl mx-auto px-4 py-16'><h2 class='text-2xl text-red-600'>Product not found.</h2></div>";
    require_once 'includes/footer.php';
    exit;
}

$product = mysqli_fetch_assoc($result);
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <nav class="flex text-sm text-gray-500 mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="index.php" class="hover:text-primary">Home</a>
            </li>
            <?php if ($product['parent_id']): ?>
                <li>
                    <div class="flex items-center">
                        <span class="mx-2">/</span>
                        <a href="products.php?category=<?php echo $product['parent_id']; ?>" class="hover:text-primary"><?php echo htmlspecialchars($product['parent_category_name']); ?></a>
                    </div>
                </li>
            <?php endif; ?>
            <li>
                <div class="flex items-center">
                    <span class="mx-2">/</span>
                    <a href="products.php?category=<?php echo $product['category_id']; ?>" class="hover:text-primary"><?php echo htmlspecialchars($product['category_name']); ?></a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <span class="mx-2">/</span>
                    <span class="text-gray-900 font-medium"><?php echo htmlspecialchars($product['name']); ?></span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="lg:grid lg:grid-cols-2 lg:gap-x-8 xl:gap-x-16">
        <!-- Product Image -->
        <div class="lg:max-w-lg lg:self-end">
            <div class="rounded-lg overflow-hidden bg-gray-100 aspect-w-1 aspect-h-1 shadow-md">
                <img src="<?php echo get_image_url($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full h-full object-center object-cover">
            </div>
        </div>

        <!-- Product Info -->
        <div class="mt-10 px-4 sm:px-0 sm:mt-16 lg:mt-0">
            <h1 class="text-3xl font-extrabold tracking-tight text-primary"><?php echo htmlspecialchars($product['name']); ?></h1>
            <div class="mt-3">
                <p class="text-3xl text-gray-900 font-bold">$<?php echo number_format($product['price'], 2); ?></p>
            </div>

            <div class="mt-6">
                <h3 class="sr-only">Description</h3>
                <div class="text-base text-gray-700 space-y-6">
                    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>
            </div>

            <div class="mt-8 border-t border-gray-200 pt-8">
                <form action="cart_action.php" method="POST" class="flex flex-col gap-4">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    
                    <div class="flex items-center">
                        <label for="quantity" class="text-sm font-medium text-gray-700 mr-4">Quantity:</label>
                        <select id="quantity" name="quantity" class="max-w-full rounded-md border border-gray-300 py-1.5 text-base leading-5 font-medium text-gray-700 text-left shadow-sm focus:outline-none focus:ring-1 focus:ring-accent focus:border-accent sm:text-sm">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>

                    <?php if($product['stock_quantity'] > 0): ?>
                        <p class="text-sm text-success flex items-center">
                            <svg class="h-5 w-5 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            In Stock
                        </p>
                        <button type="submit" class="mt-4 w-full bg-accent border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent btn-primary transition-all">
                            Add to Cart
                        </button>
                    <?php else: ?>
                        <p class="text-sm text-red-600 flex items-center">Out of Stock</p>
                        <button type="button" disabled class="mt-4 w-full bg-gray-300 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white cursor-not-allowed">
                            Out of Stock
                        </button>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
