<?php
require_once 'config/database.php';
require_once 'includes/header.php';
?>

<div class="bg-gradient-to-b from-white to-gray-50/50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Hero Section -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-bold tracking-widest text-accent uppercase bg-blue-100 px-3.5 py-1.5 rounded-full border border-blue-200">Our Story</span>
            <h1 class="text-4xl sm:text-5xl font-extrabold text-primary tracking-tight mt-4 mb-6 leading-tight">
                Redefining Urban Fashion in Rwanda
            </h1>
            <p class="text-lg text-gray-600 leading-relaxed">
                Founded with a passion for minimalism, modern aesthetics, and quality, Urban Style Rwanda is a curated platform bringing you premium streetwear and casual apparel designed for comfort and style.
            </p>
        </div>

        <!-- Two Column Story Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-24">
            <div class="relative rounded-2xl overflow-hidden shadow-xl aspect-w-4 aspect-h-3 bg-gray-100 group">
                <img src="assets/images/hero_banner.jpg" alt="Urban Style Studio" class="w-full h-full object-cover object-center transform group-hover:scale-105 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-primary/30 to-transparent"></div>
            </div>
            <div class="space-y-6">
                <h2 class="text-2xl sm:text-3xl font-bold text-primary tracking-tight">Crafting Identity Through Style</h2>
                <p class="text-gray-600 leading-relaxed">
                    At Urban Style, we believe apparel is more than just clothing—it's a form of self-expression. We began our journey in Kigali, noticing a gap in the local market for easily accessible, minimalist streetwear that doesn't compromise on fabric weight and fit quality.
                </p>
                <p class="text-gray-600 leading-relaxed">
                    Every piece in our inventory is carefully curated, keeping in mind the dynamic lifestyle of the modern urban trendsetter. From heavyweight tees to structured hoodies, we prioritize clean silhouettes and long-lasting craftsmanship.
                </p>
                <div class="pt-4 flex items-center space-x-8 border-t border-gray-200">
                    <div>
                        <span class="block text-3xl font-extrabold text-accent">100%</span>
                        <span class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Premium Quality</span>
                    </div>
                    <div>
                        <span class="block text-3xl font-extrabold text-accent">Kigali</span>
                        <span class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Based Studio</span>
                    </div>
                    <div>
                        <span class="block text-3xl font-extrabold text-accent">24/7</span>
                        <span class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Fast Delivery</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Core Values Section -->
        <div class="mb-24">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl font-bold text-primary tracking-tight">Our Core Pillars</h2>
                <p class="text-gray-500 mt-2">What sets Urban Style Rwanda apart in the fashion industry.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Pillar 1 -->
                <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-accent flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-primary mb-3">Uncompromising Quality</h3>
                    <p class="text-gray-500 leading-relaxed text-sm">
                        We meticulously source heavy fabrics, durable stitching, and rich colors that retain their form wash after wash. Every item undergoes rigorous checking.
                    </p>
                </div>

                <!-- Pillar 2 -->
                <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-green-50 text-success flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-primary mb-3">Accessible Fashion</h3>
                    <p class="text-gray-500 leading-relaxed text-sm">
                        Great design shouldn't be gated by exorbitant prices. We keep our margins fair and operations streamlined to bring you world-class aesthetics at local rates.
                    </p>
                </div>

                <!-- Pillar 3 -->
                <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-primary mb-3">Community First</h3>
                    <p class="text-gray-500 leading-relaxed text-sm">
                        Urban Style is built by local designers, logistics partners, and fashion lovers. We're proud to empower and represent the modern spirit of Kigali.
                    </p>
                </div>
            </div>
        </div>

        <!-- Vision and Mission -->
        <div class="bg-primary text-white rounded-3xl p-8 sm:p-12 lg:p-16 relative overflow-hidden shadow-xl mb-12">
            <!-- Background glows -->
            <div class="absolute -top-40 -left-40 w-96 h-96 rounded-full bg-blue-500/10 blur-[100px] pointer-events-none"></div>
            <div class="absolute -bottom-40 -right-40 w-96 h-96 rounded-full bg-accent/25 blur-[120px] pointer-events-none"></div>
            
            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div>
                    <h3 class="text-xs font-bold tracking-widest text-accent uppercase mb-2">Our Mission</h3>
                    <p class="text-xl sm:text-2xl font-medium leading-relaxed text-gray-100">
                        "To inspire confidence and simplify shopping by offering carefully curated minimalist urban streetwear that lasts."
                    </p>
                </div>
                <div>
                    <h3 class="text-xs font-bold tracking-widest text-accent uppercase mb-2">Our Vision</h3>
                    <p class="text-xl sm:text-2xl font-medium leading-relaxed text-gray-100">
                        "To become the leading online streetwear brand in East Africa, known for premium aesthetics and absolute customer satisfaction."
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
