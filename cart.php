<?php
require_once 'config/database.php';
require_once 'includes/header.php';

// Check if cart is empty
$cart_empty = empty($_SESSION['cart']);
$cart_items = [];
$cart_total = 0;

if(!$cart_empty) {
    // We need to fetch product details for items in the cart
    $product_ids = implode(',', array_keys($_SESSION['cart']));
    
    // Only query if there are valid IDs (to prevent SQL errors)
    if(!empty($product_ids)) {
        $query = "SELECT * FROM products WHERE id IN ($product_ids)";
        $result = mysqli_query($conn, $query);
        
        while($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $quantity = $_SESSION['cart'][$id];
            $subtotal = $row['price'] * $quantity;
            $cart_total += $subtotal;
            
            $row['quantity'] = $quantity;
            $row['subtotal'] = $subtotal;
            $cart_items[] = $row;
        }
    } else {
        $cart_empty = true;
    }
}
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-extrabold tracking-tight text-primary mb-8">Shopping Cart</h1>

    <?php if($cart_empty): ?>
        <div class="text-center py-16 bg-white rounded-lg shadow-sm border border-gray-100">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Your cart is empty</h3>
            <p class="mt-1 text-sm text-gray-500">Looks like you haven't added anything to your cart yet.</p>
            <div class="mt-6">
                <a href="products.php" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-accent hover:bg-blue-700 btn-primary">
                    Start Shopping
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="mt-8 lg:grid lg:grid-cols-12 lg:gap-x-12 lg:items-start">
            <div class="lg:col-span-8">
                <ul role="list" class="border-t border-b border-gray-200 divide-y divide-gray-200">
                    <?php foreach($cart_items as $item): ?>
                        <li class="flex py-6 sm:py-10 bg-white px-4 mb-2 rounded-lg shadow-sm border border-gray-50">
                            <div class="flex-shrink-0">
                                <img src="<?php echo get_image_url($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="w-24 h-24 rounded-md object-center object-cover sm:w-32 sm:h-32">
                            </div>

                            <div class="ml-4 flex-1 flex flex-col justify-between sm:ml-6">
                                <div class="relative pr-9 sm:grid sm:grid-cols-2 sm:gap-x-6 sm:pr-0">
                                    <div>
                                        <div class="flex justify-between">
                                            <h3 class="text-lg font-medium text-primary">
                                                <a href="product_details.php?id=<?php echo $item['id']; ?>" class="hover:text-accent">
                                                    <?php echo htmlspecialchars($item['name']); ?>
                                                </a>
                                            </h3>
                                        </div>
                                        <p class="mt-1 text-sm font-medium text-gray-900">$<?php echo number_format($item['price'], 2); ?></p>
                                    </div>

                                    <div class="mt-4 sm:mt-0 sm:pr-9">
                                        <!-- Update Quantity Form -->
                                        <form action="cart_action.php" method="POST" class="flex items-center">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                            <label for="quantity-<?php echo $item['id']; ?>" class="sr-only">Quantity, <?php echo htmlspecialchars($item['name']); ?></label>
                                            <input id="quantity-<?php echo $item['id']; ?>" name="quantity" type="number" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['stock_quantity']; ?>" class="max-w-[4rem] rounded-md border border-gray-300 py-1.5 text-base leading-5 font-medium text-gray-700 text-left shadow-sm focus:outline-none focus:ring-1 focus:ring-accent focus:border-accent sm:text-sm mr-2 text-center">
                                            <button type="submit" class="text-sm font-medium text-accent hover:text-blue-700">Update</button>
                                        </form>

                                        <div class="absolute top-0 right-0">
                                            <!-- Remove from Cart Form -->
                                            <form action="cart_action.php" method="POST">
                                                <input type="hidden" name="action" value="remove">
                                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                                <button type="submit" class="-m-2 p-2 inline-flex text-gray-400 hover:text-red-500 transition-colors">
                                                    <span class="sr-only">Remove</span>
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-4 flex text-sm text-gray-700 space-x-2">
                                    <strong>Subtotal:</strong> <span>$<?php echo number_format($item['subtotal'], 2); ?></span>
                                </p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                
                <div class="mt-4 flex justify-between items-center">
                    <form action="cart_action.php" method="POST">
                        <input type="hidden" name="action" value="clear">
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium flex items-center">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Clear Cart
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order summary -->
            <section aria-labelledby="summary-heading" class="mt-16 bg-white rounded-lg px-4 py-6 sm:p-6 lg:p-8 lg:mt-0 lg:col-span-4 border border-gray-100 shadow-sm sticky top-24">
                <h2 id="summary-heading" class="text-lg font-medium text-gray-900 border-b pb-4">Order summary</h2>

                <dl class="mt-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-600">Subtotal</dt>
                        <dd class="text-sm font-medium text-gray-900">$<?php echo number_format($cart_total, 2); ?></dd>
                    </div>
                    <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                        <dt class="flex items-center text-sm text-gray-600">
                            <span>Shipping estimate</span>
                        </dt>
                        <dd class="text-sm font-medium text-gray-900">Free</dd>
                    </div>
                    <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                        <dt class="text-base font-bold text-gray-900">Order total</dt>
                        <dd class="text-xl font-bold text-gray-900">$<?php echo number_format($cart_total, 2); ?></dd>
                    </div>
                </dl>

                <div class="mt-6">
                    <a href="checkout.php" class="w-full bg-accent border border-transparent rounded-md shadow-sm py-3 px-4 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-accent flex justify-center items-center transition-colors btn-primary">
                        Proceed to Checkout
                    </a>
                </div>
            </section>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
