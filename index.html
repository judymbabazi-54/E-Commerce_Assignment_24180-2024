<?php
require_once 'config/database.php';
require_once 'includes/header.php';

// Fetch featured products (latest 4)
$query = "SELECT * FROM products ORDER BY id DESC LIMIT 4";
$result = mysqli_query($conn, $query);

// Fetch settings
$settings_result = mysqli_query($conn, "SELECT * FROM settings");
$settings = [];
while($row = mysqli_fetch_assoc($settings_result)) {
    $settings[$row['setting_key']] = $row['setting_value'];
}
?>

<!-- Hero Section -->
<div class="relative w-full min-h-[600px] bg-accent flex items-center overflow-hidden">
    
    <!-- Large Curved Wave (Bottom Right) -->
    <div class="absolute bottom-0 right-0 w-full md:w-[70%] h-[70%] md:h-[90%] pointer-events-none">
        <!-- Secondary colored wave (White/Off-white) -->
        <svg viewBox="0 0 100 100" preserveAspectRatio="none" class="absolute bottom-0 right-0 w-full h-full text-secondary" style="transform: scaleX(-1);">
            <path d="M0,100 C30,70 40,0 100,0 L100,100 Z" fill="currentColor"></path>
        </svg>
        <!-- Shadow/Accent wave behind it -->
        <svg viewBox="0 0 100 100" preserveAspectRatio="none" class="absolute bottom-0 right-0 w-full h-full text-primary opacity-10 -z-10 translate-x-4 -translate-y-4" style="transform: scaleX(-1);">
            <path d="M0,100 C30,70 40,0 100,0 L100,100 Z" fill="currentColor"></path>
        </svg>
    </div>

    <!-- Small decorative elements -->
    <div class="absolute top-20 left-20 text-white/30 text-2xl font-bold">+</div>
    <div class="absolute bottom-20 left-1/4 text-white/30 text-xl font-bold">+</div>
    <div class="absolute top-1/4 right-1/3 text-primary/10 text-4xl font-bold">x</div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex flex-col md:flex-row items-center justify-between py-16 md:py-0">
        
        <!-- Left Side: Typography -->
        <div class="w-full md:w-1/2 text-left pt-10 md:pt-0 z-20">
            <!-- Angled Subtitle Badge -->
            <div class="inline-block bg-yellow-400 text-primary font-bold px-4 py-1 text-sm tracking-widest uppercase transform -rotate-6 mb-4 shadow-[4px_4px_0_rgba(17,24,39,1)]">
                Urban Style
            </div>
            
            <!-- Huge Angled Title -->
            <h1 class="font-black text-white leading-none transform -rotate-6 drop-shadow-lg mb-2 italic">
                <?php 
                    $title_size = isset($settings['hero_title_size']) ? $settings['hero_title_size'] : 'large';
                    if($title_size == 'small') {
                        $top_size = "text-5xl md:text-6xl lg:text-7xl";
                        $bottom_size = "text-4xl md:text-5xl lg:text-6xl";
                    } elseif($title_size == 'medium') {
                        $top_size = "text-6xl md:text-7xl lg:text-8xl";
                        $bottom_size = "text-5xl md:text-6xl lg:text-7xl";
                    } else { // large
                        $top_size = "text-6xl md:text-8xl lg:text-9xl";
                        $bottom_size = "text-5xl md:text-7xl lg:text-8xl";
                    }

                    $title = htmlspecialchars($settings['hero_title']);
                    $words = explode(' ', $title);
                    if(count($words) >= 2) {
                        $first_word = array_shift($words);
                        $rest = implode(' ', $words);
                        echo "<span class='block $top_size'>$first_word</span>";
                        echo "<span class='block text-primary $bottom_size mt-1'>$rest</span>";
                    } else {
                        echo "<span class='block $top_size'>$title</span>";
                    }
                ?>
            </h1>

            <p class="mt-8 text-white/90 text-lg md:text-xl font-medium max-w-md transform -rotate-3">
                <?php echo htmlspecialchars($settings['hero_subtitle']); ?>
            </p>

            <!-- Buttons -->
            <div class="mt-10 flex items-center space-x-6 transform -rotate-3">
                <a href="products.php" class="bg-yellow-400 text-primary px-8 py-3 rounded-full font-bold hover:bg-yellow-300 transition shadow-[0_4px_14px_0_rgba(0,0,0,0.39)] text-sm uppercase tracking-wider border-2 border-primary">
                    Shop Now
                </a>
                <a href="products.php" class="text-white font-bold hover:text-yellow-400 transition-colors">
                    More Products &rarr;
                </a>
            </div>
        </div>

        <!-- Right Side: Product Image -->
        <div class="w-full md:w-1/2 mt-16 md:mt-0 flex justify-center md:justify-end relative z-20">
            <div class="relative w-80 h-80 md:w-[600px] md:h-[600px]">
                <!-- Using mix-blend-multiply to drop the white background of the image so it sits perfectly on the wave -->
                <img src="assets/images/<?php echo htmlspecialchars($settings['hero_image']); ?>" alt="Hero Product" class="w-full h-full object-contain filter drop-shadow-2xl mix-blend-multiply transition-transform hover:-translate-y-4 duration-500 cursor-pointer">
            </div>
        </div>

    </div>
</div>

<!-- Featured Products Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h2 class="text-3xl font-extrabold tracking-tight text-primary mb-8 text-center">Trending Now</h2>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-y-10 gap-x-6 xl:gap-x-8">
        <?php while($product = mysqli_fetch_assoc($result)): ?>
            <div class="group relative product-card bg-white rounded-lg p-4">
                <div class="w-full min-h-80 bg-gray-200 aspect-w-1 aspect-h-1 rounded-md overflow-hidden group-hover:opacity-75 lg:h-80 lg:aspect-none relative">
                    <img src="assets/images/<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full h-full object-center object-cover lg:w-full lg:h-full">
                </div>
                <div class="mt-4 flex justify-between">
                    <div>
                        <h3 class="text-sm text-gray-700 font-medium">
                            <a href="product_details.php?id=<?php echo $product['id']; ?>">
                                <span aria-hidden="true" class="absolute inset-0"></span>
                                <?php echo htmlspecialchars($product['name']); ?>
                            </a>
                        </h3>
                    </div>
                    <p class="text-sm font-bold text-gray-900">$<?php echo number_format($product['price'], 2); ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
