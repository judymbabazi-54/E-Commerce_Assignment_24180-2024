<?php
require_once 'config/database.php';
require_once 'includes/header.php';

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

// Security check: ensure order exists
$query = "SELECT * FROM orders WHERE id = $order_id";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 0) {
    echo "<div class='max-w-7xl mx-auto px-4 py-16'><h2 class='text-2xl text-red-600'>Order not found.</h2></div>";
    require_once 'includes/footer.php';
    exit;
}
?>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
    <div class="bg-white p-10 rounded-xl shadow-lg border border-gray-100">
        <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-green-100 mb-6">
            <svg class="h-12 w-12 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        
        <h1 class="text-4xl font-extrabold tracking-tight text-primary mb-2">Order Confirmed!</h1>
        <p class="text-lg text-gray-500 mb-8">Thank you for your purchase. Your order has been placed successfully.</p>
        
        <div class="bg-gray-50 rounded-lg p-6 inline-block w-full max-w-md border border-gray-200 mb-8 text-left">
            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Order Details</h3>
            <div class="flex justify-between items-center mb-2">
                <span class="text-gray-600">Order Number:</span>
                <span class="font-bold text-primary">#<?php echo str_pad($order_id, 6, '0', STR_PAD_LEFT); ?></span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Status:</span>
                <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-1 rounded-full uppercase">Pending</span>
            </div>
        </div>
        
        <div>
            <a href="products.php" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-accent hover:bg-blue-700 btn-primary">
                Continue Shopping
            </a>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
