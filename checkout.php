<?php
require_once 'config/database.php';
require_once 'includes/header.php';

// If cart is empty, redirect to shop
if(empty($_SESSION['cart'])) {
    header('Location: products.php');
    exit;
}

// Calculate total
$cart_total = 0;
$product_ids = implode(',', array_keys($_SESSION['cart']));
$query = "SELECT id, price FROM products WHERE id IN ($product_ids)";
$result = mysqli_query($conn, $query);
while($row = mysqli_fetch_assoc($result)) {
    $cart_total += $row['price'] * $_SESSION['cart'][$row['id']];
}
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="lg:grid lg:grid-cols-2 lg:gap-x-12 xl:gap-x-16">
        
        <!-- Checkout Form -->
        <div>
            <h2 class="text-2xl font-extrabold tracking-tight text-primary mb-6">Customer Information</h2>
            
            <form action="checkout_process.php" method="POST" class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                    <div class="sm:col-span-2">
                        <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <div class="mt-1">
                            <input type="text" id="full_name" name="full_name" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-accent focus:border-accent sm:text-sm p-2 border">
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <div class="mt-1">
                            <input type="email" id="email" name="email" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-accent focus:border-accent sm:text-sm p-2 border">
                        </div>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <div class="mt-1">
                            <input type="text" id="phone" name="phone" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-accent focus:border-accent sm:text-sm p-2 border">
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700">Delivery Address</label>
                        <div class="mt-1">
                            <textarea id="address" name="address" rows="3" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-accent focus:border-accent sm:text-sm p-2 border"></textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-8 border-t border-gray-200 pt-6">
                    <button type="submit" class="w-full bg-accent border border-transparent rounded-md shadow-sm py-3 px-4 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-accent btn-primary">
                        Confirm Order
                    </button>
                </div>
            </form>
        </div>

        <!-- Order Summary -->
        <div class="mt-10 lg:mt-0">
            <h2 class="text-2xl font-extrabold tracking-tight text-primary mb-6">Order Summary</h2>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                <dl class="space-y-4 text-sm text-gray-600">
                    <div class="flex items-center justify-between">
                        <dt>Total Items</dt>
                        <dd class="font-medium text-gray-900"><?php echo array_sum($_SESSION['cart']); ?></dd>
                    </div>
                    <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                        <dt class="text-base font-bold text-gray-900">Total Amount</dt>
                        <dd class="text-xl font-bold text-gray-900">$<?php echo number_format($cart_total, 2); ?></dd>
                    </div>
                </dl>
                <p class="mt-6 text-xs text-gray-500 text-center">Payment will be collected upon delivery.</p>
            </div>
        </div>

    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
