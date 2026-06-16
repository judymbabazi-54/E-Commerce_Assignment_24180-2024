<?php
require_once '../config/database.php';
require_once 'includes/auth.php';

// Check if demo parameter is present
$using_mock_data = isset($_GET['demo']) && $_GET['demo'] == '1';

if ($using_mock_data) {
    // Overall Stats Mock
    $total_revenue = 4850.50;
    $sales_count = 23;
    $average_order = 210.89;

    // Monthly Sales Mock
    $monthly_labels = ['Jan 2026', 'Feb 2026', 'Mar 2026', 'Apr 2026', 'May 2026', 'Jun 2026'];
    $monthly_data = [450.00, 780.50, 1200.00, 950.00, 1600.00, 2100.00];

    // Category Mock
    $category_labels = ['T-Shirts', 'Hoodies', 'Jeans'];
    $category_data = [1640.00, 1980.50, 1230.00];

    // Order Status Mock
    $status_labels = ['Pending', 'Completed', 'Cancelled'];
    $status_data = [3, 23, 2];

    // Top Products Mock
    $product_labels = ['Graphic Black Tee', 'Slim Fit Blue Jeans', 'Grey Pullover Hoodie', 'Classic White T-Shirt', 'Pullover Style Hoodie'];
    $product_data = [15, 12, 8, 6, 4];
    $is_database_empty = false;
} else {
    // 1. Fetch Overall Analytics totals (include Pending and Completed orders, exclude Cancelled)
    $total_revenue_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_amount) as total FROM orders WHERE status != 'Cancelled'"));
    $total_revenue = floatval($total_revenue_result['total'] ?? 0);

    $sales_count_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM orders WHERE status != 'Cancelled'"));
    $sales_count = intval($sales_count_result['count'] ?? 0);

    $average_order_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT AVG(total_amount) as avg FROM orders WHERE status != 'Cancelled'"));
    $average_order = floatval($average_order_result['avg'] ?? 0);

    // 2. Fetch Monthly Sales Trend
    $monthly_query = "
        SELECT DATE_FORMAT(created_at, '%b %Y') as month_label, SUM(total_amount) as monthly_sales 
        FROM orders 
        WHERE status != 'Cancelled' 
        GROUP BY YEAR(created_at), MONTH(created_at) 
        ORDER BY YEAR(created_at) ASC, MONTH(created_at) ASC
    ";
    $monthly_res = mysqli_query($conn, $monthly_query);
    $monthly_labels = [];
    $monthly_data = [];
    while ($row = mysqli_fetch_assoc($monthly_res)) {
        $monthly_labels[] = $row['month_label'];
        $monthly_data[] = floatval($row['monthly_sales']);
    }

    // 3. Fetch Category Distribution
    $category_query = "
        SELECT c.name as category_name, SUM(oi.quantity * oi.price_at_purchase) as total_sales 
        FROM order_items oi 
        JOIN products p ON oi.product_id = p.id 
        JOIN categories c ON p.category_id = c.id 
        JOIN orders o ON oi.order_id = o.id 
        WHERE o.status != 'Cancelled' 
        GROUP BY c.id
    ";
    $category_res = mysqli_query($conn, $category_query);
    $category_labels = [];
    $category_data = [];
    while ($row = mysqli_fetch_assoc($category_res)) {
        $category_labels[] = $row['category_name'];
        $category_data[] = floatval($row['total_sales']);
    }

    // 4. Fetch Order Status Distribution
    $status_query = "SELECT status, COUNT(*) as status_count FROM orders GROUP BY status";
    $status_res = mysqli_query($conn, $status_query);
    $status_labels = [];
    $status_data = [];
    while ($row = mysqli_fetch_assoc($status_res)) {
        $status_labels[] = $row['status'];
        $status_data[] = intval($row['status_count']);
    }

    // 5. Fetch Top Selling Products
    $products_query = "
        SELECT p.name as product_name, SUM(oi.quantity) as total_quantity 
        FROM order_items oi 
        JOIN products p ON oi.product_id = p.id 
        JOIN orders o ON oi.order_id = o.id
        WHERE o.status != 'Cancelled'
        GROUP BY p.id 
        ORDER BY total_quantity DESC 
        LIMIT 5
    ";
    $products_res = mysqli_query($conn, $products_query);
    $product_labels = [];
    $product_data = [];
    while ($row = mysqli_fetch_assoc($products_res)) {
        $product_labels[] = $row['product_name'];
        $product_data[] = intval($row['total_quantity']);
    }

    // Determine if database is empty of transactions
    $is_database_empty = ($sales_count === 0);
}

require_once 'includes/header.php';
?>

<!-- Import Chart.js Library via CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="flex flex-col space-y-8 pb-12">
    <!-- Section Title & Mock Badge -->
    <div class="flex items-center justify-between border-b pb-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Analytics Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">Real-time metrics, product trends, and transaction distributions.</p>
        </div>
        
    </div>

    <?php if ($is_database_empty): ?>
        <!-- Database Empty Banner Notification -->
        <div class="bg-blue-50 border-l-4 border-accent p-6 rounded-xl flex items-start space-x-4">
            <svg class="h-6 w-6 text-accent shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h4 class="text-md font-bold text-blue-900">Your Database is Currently Empty of Orders</h4>
                <p class="text-sm text-blue-700 mt-1">
                    There are no orders logged in your database yet. The charts below are displaying blank/zero states representing your live data.
                    You can try placing an order on the storefront and refreshing this dashboard to see real-time updates!
                </p>
                <div class="mt-4">
                    <a href="analytics.php?demo=1" class="inline-flex items-center px-4 py-2 rounded-lg bg-accent text-white text-xs font-semibold hover:bg-blue-700 transition-colors shadow-sm">
                        ✨ Enable Demo Data for Presentation
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Summary Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex flex-col justify-between">
            <div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Total Sales Revenue</span>
                <span class="text-3xl font-extrabold text-gray-900 block mt-2">$<?php echo number_format($total_revenue, 2); ?></span>
            </div>
            
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex flex-col justify-between">
            <div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Completed Orders</span>
                <span class="text-3xl font-extrabold text-gray-900 block mt-2"><?php echo $sales_count; ?></span>
            </div>
            
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex flex-col justify-between">
            <div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Average Order Value</span>
                <span class="text-3xl font-extrabold text-gray-900 block mt-2">$<?php echo number_format($average_order, 2); ?></span>
            </div>
           
        </div>
    </div>

    <!-- Charts Grid: Row 1 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Chart Card 1: Monthly Sales Trend (Line Chart) -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex flex-col">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Monthly Sales Revenue ($)</h3>
            <div class="flex-1 min-h-[300px]">
                <canvas id="monthlySalesChart"></canvas>
            </div>
        </div>

        <!-- Chart Card 2: Top Selling Products (Bar Chart) -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex flex-col">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Top 5 Best-Selling Products (Units)</h3>
            <div class="flex-1 min-h-[300px]">
                <canvas id="topProductsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Charts Grid: Row 2 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Chart Card 3: Revenue Share by Category (Doughnut Chart) -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex flex-col items-center">
            <h3 class="text-lg font-bold text-gray-800 mb-4 w-full text-left">Revenue Share by Category ($)</h3>
            <div class="relative w-full max-w-[280px] min-h-[280px] flex items-center justify-center">
                <canvas id="categoryDistributionChart"></canvas>
            </div>
        </div>

        <!-- Chart Card 4: Order Status Distribution (Pie Chart) -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex flex-col items-center">
            <h3 class="text-lg font-bold text-gray-800 mb-4 w-full text-left">Order Status Distribution</h3>
            <div class="relative w-full max-w-[280px] min-h-[280px] flex items-center justify-center">
                <canvas id="orderStatusChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Render Chart.js Configurations -->
<script>
    // Theme Colors
    const primaryBlue = '#2563EB';
    const lightPurple = '#8B5CF6';
    const darkGray = '#1E293B';
    const successGreen = '#10B981';
    const warningYellow = '#F59E0B';
    const dangerRed = '#EF4444';

    // 1. Line Chart: Monthly Sales
    new Chart(document.getElementById('monthlySalesChart'), {
        type: 'line',
        data: {
            labels: <?php echo json_encode($monthly_labels); ?>,
            datasets: [{
                label: 'Monthly Revenue ($)',
                data: <?php echo json_encode($monthly_data); ?>,
                borderColor: primaryBlue,
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                borderWidth: 3,
                tension: 0.3,
                fill: true,
                pointBackgroundColor: primaryBlue,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.05)' },
                    ticks: { callback: value => '$' + value }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

    // 2. Bar Chart: Top Selling Products
    new Chart(document.getElementById('topProductsChart'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($product_labels); ?>,
            datasets: [{
                label: 'Units Sold',
                data: <?php echo json_encode($product_data); ?>,
                backgroundColor: [primaryBlue, lightPurple, successGreen, warningYellow, dangerRed],
                borderRadius: 6,
                barThickness: 24
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.05)' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

    // 3. Doughnut Chart: Category Distribution
    new Chart(document.getElementById('categoryDistributionChart'), {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($category_labels); ?>,
            datasets: [{
                data: <?php echo json_encode($category_data); ?>,
                backgroundColor: [primaryBlue, warningYellow, lightPurple, successGreen],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, padding: 15 } }
            }
        }
    });

    // 4. Pie Chart: Order Status Distribution
    new Chart(document.getElementById('orderStatusChart'), {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($status_labels); ?>,
            datasets: [{
                data: <?php echo json_encode($status_data); ?>,
                backgroundColor: [warningYellow, successGreen, dangerRed],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, padding: 15 } }
            }
        }
    });
</script>

<?php require_once 'includes/footer.php'; ?>
