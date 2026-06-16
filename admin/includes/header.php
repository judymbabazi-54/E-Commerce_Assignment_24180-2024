<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Urban Style</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#111827',
                        accent: '#2563EB',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 flex min-h-screen">

<!-- Sidebar -->
<aside class="w-64 bg-primary text-white flex flex-col">
    <div class="h-16 flex items-center justify-center border-b border-gray-800">
        <span class="text-xl font-bold tracking-tight">URBAN<span class="text-accent">STYLE</span> Admin</span>
    </div>
    <nav class="flex-1 px-4 py-6 space-y-2">
        <a href="index.php" class="block px-4 py-2 rounded-md hover:bg-gray-800 transition-colors">Dashboard</a>
        <a href="categories.php" class="block px-4 py-2 rounded-md hover:bg-gray-800 transition-colors">Categories</a>
        <a href="products.php" class="block px-4 py-2 rounded-md hover:bg-gray-800 transition-colors">Products</a>
        <a href="orders.php" class="block px-4 py-2 rounded-md hover:bg-gray-800 transition-colors">Orders</a>
        <a href="analytics.php" class="block px-4 py-2 rounded-md hover:bg-gray-800 transition-colors">Analytics</a>
        <a href="settings.php" class="block px-4 py-2 rounded-md hover:bg-gray-800 transition-colors">Settings</a>
    </nav>
    <div class="p-4 border-t border-gray-800">
        <a href="../index.php" class="text-sm text-gray-400 hover:text-white">&larr; Back to Store</a>
    </div>
</aside>

<!-- Main Content Area -->
<main class="flex-1 flex flex-col">
    <!-- Top Header -->
    <header class="h-16 bg-white shadow-sm flex items-center justify-between px-8">
        <h2 class="text-xl font-semibold text-gray-800">Admin Control Panel</h2>
        <div class="flex items-center space-x-4">
            <span class="text-sm text-gray-500">Logged in as <strong class="text-gray-700"><?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?></strong></span>
            <a href="logout.php" id="logout-btn" class="text-sm text-red-600 hover:text-red-800 font-semibold bg-red-50 hover:bg-red-100 px-3.5 py-1.5 rounded-md border border-red-200 hover:border-red-300 transition-all duration-200">Logout</a>
        </div>
    </header>
    
    <!-- Page Content -->
    <div class="p-8 flex-1 overflow-y-auto">
