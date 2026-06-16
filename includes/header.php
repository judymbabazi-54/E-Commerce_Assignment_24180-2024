<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urban Style Rwanda</title>
    <!-- Tailwind CSS (CDN for simplicity) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#111827',
                        secondary: '#F9FAFB',
                        accent: '#2563EB',
                        success: '#10B981',
                    }
                }
            }
        }
    </script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-secondary text-primary antialiased flex flex-col min-h-screen">

<!-- Navigation Bar -->
<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="index.php" class="text-2xl font-bold tracking-tight text-primary">
                    URBAN<span class="text-accent">STYLE</span>
                </a>
            </div>
            
            <!-- Desktop Menu -->
            <nav class="hidden md:flex space-x-8">
                <a href="index.php" class="text-gray-600 hover:text-accent px-3 py-2 text-sm font-medium transition-colors">Home</a>
                <a href="products.php" class="text-gray-600 hover:text-accent px-3 py-2 text-sm font-medium transition-colors">Shop</a>
                <a href="about.php" class="text-gray-600 hover:text-accent px-3 py-2 text-sm font-medium transition-colors">About Us</a>
                <a href="cart.php" class="text-gray-600 hover:text-accent px-3 py-2 text-sm font-medium transition-colors flex items-center">
                    Cart
                    <!-- Cart count badge -->
                    <span class="ml-1 bg-accent text-white text-xs font-bold px-2 py-0.5 rounded-full">
                        <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
                    </span>
                </a>
            </nav>

            <!-- Mobile menu button -->
            <div class="flex items-center md:hidden">
                <button type="button" class="text-gray-600 hover:text-primary focus:outline-none" aria-controls="mobile-menu" aria-expanded="false" id="mobile-menu-button">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="md:hidden hidden bg-white border-t" id="mobile-menu">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="index.php" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-accent hover:bg-gray-50">Home</a>
            <a href="products.php" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-accent hover:bg-gray-50">Shop</a>
            <a href="about.php" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-accent hover:bg-gray-50">About Us</a>
            <a href="cart.php" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-accent hover:bg-gray-50">
                Cart (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)
            </a>
        </div>
    </div>
</header>

<!-- Main Content Area Starts Here -->
<main class="flex-grow">
