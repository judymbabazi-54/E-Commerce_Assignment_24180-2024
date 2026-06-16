<?php
require_once '../config/database.php';
require_once 'includes/auth.php';
require_once 'includes/header.php';

// Fetch Totals
$total_products = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM products"))['count'];
$total_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM orders"))['count'];
$total_customers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM customers"))['count'];

// Fetch Recent Orders
$recent_orders = mysqli_query($conn, "SELECT o.*, c.full_name FROM orders o JOIN customers c ON o.customer_id = c.id ORDER BY o.id DESC LIMIT 5");
?>

<h1 class="text-3xl font-bold text-gray-900 mb-8">Dashboard Overview</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 border-l-4 border-l-blue-500">
        <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider mb-1">Total Products</h3>
        <p class="text-3xl font-bold text-gray-900"><?php echo $total_products; ?></p>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 border-l-4 border-l-green-500">
        <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider mb-1">Total Orders</h3>
        <p class="text-3xl font-bold text-gray-900"><?php echo $total_orders; ?></p>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 border-l-4 border-l-purple-500">
        <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider mb-1">Total Customers</h3>
        <p class="text-3xl font-bold text-gray-900"><?php echo $total_customers; ?></p>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Recent Orders</h3>
    </div>
    <div class="p-6">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php while($order = mysqli_fetch_assoc($recent_orders)): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($order['full_name']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">$<?php echo number_format($order['total_amount'], 2); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            <?php echo $order['status']; ?>
                        </span>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
