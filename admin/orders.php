<?php
require_once '../config/database.php';
require_once 'includes/auth.php';

// Handle Update Status
if(isset($_POST['update_status'])) {
    $order_id = (int)$_POST['order_id'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    mysqli_query($conn, "UPDATE orders SET status = '$status' WHERE id = $order_id");
    header('Location: orders.php');
    exit;
}

require_once 'includes/header.php';

// Fetch Orders
$orders = mysqli_query($conn, "SELECT o.*, c.full_name, c.email FROM orders o JOIN customers c ON o.customer_id = c.id ORDER BY o.id DESC");
?>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Manage Orders</h1>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="p-6 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php while($order = mysqli_fetch_assoc($orders)): ?>
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">#<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <?php echo htmlspecialchars($order['full_name']); ?><br>
                        <span class="text-xs text-gray-400"><?php echo htmlspecialchars($order['email']); ?></span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">$<?php echo number_format($order['total_amount'], 2); ?></td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $order['status'] == 'Completed' ? 'bg-green-100 text-green-800' : ($order['status'] == 'Cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                            <?php echo $order['status']; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <form action="orders.php" method="POST" class="flex items-center">
                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                            <select name="status" class="text-xs border-gray-300 focus:border-accent focus:ring-accent rounded-md shadow-sm mr-2 p-1.5 border bg-white text-gray-700 font-medium">
                                <option value="Pending" <?php if($order['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                <option value="Completed" <?php if($order['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                                <option value="Cancelled" <?php if($order['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                            </select>
                            <button type="submit" name="update_status" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-accent hover:bg-blue-700 text-white rounded-md text-xs font-semibold shadow-sm transition-all duration-200">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 3v5h-5"></path>
                                </svg>
                                Update
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
