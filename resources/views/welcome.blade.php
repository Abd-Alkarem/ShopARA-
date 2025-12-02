<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>ShopARA - Your Tech Store</title>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="/favicon.svg">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="/" class="flex items-center space-x-2">
                            <!-- Shopping bag SVG icon -->
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0">
                                <path d="M8 10C8 8.93913 8.42143 7.92172 9.17157 7.17157C9.92172 6.42143 10.9391 6 12 6H20C21.0609 6 22.0783 6.42143 22.8284 7.17157C23.5786 7.92172 24 8.93913 24 10V22C24 23.0609 23.5786 24.0783 22.8284 24.8284C22.0783 25.5786 21.0609 26 20 26H12C10.9391 26 9.92172 25.5786 9.17157 24.8284C8.42143 24.0783 8 23.0609 8 22V10Z" stroke="#4F46E5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M16 4V10" stroke="#4F46E5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12 10V12" stroke="#4F46E5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M20 10V12" stroke="#4F46E5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            
                            <!-- ShopARA text using CSS styling -->
                            <span class="text-xl font-bold tracking-tight">
                                <span class="text-indigo-600">Shop</span><span class="text-gray-800">ARA</span>
                            </span>
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        @auth
                            <div class="flex items-center space-x-4">
                                <!-- Cart Icon -->
                                <a href="{{ route('cart.index') }}" class="relative flex items-center text-gray-600 hover:text-gray-900 font-medium transition">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    @if(auth()->check() && auth()->user()->cartCount() > 0)
                                        <span class="absolute -top-2 -right-2 bg-indigo-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                            {{ auth()->user()->cartCount() }}
                                        </span>
                                    @endif
                                    <span class="ml-2">Cart</span>
                                </a>

                                <!-- Profile Dropdown -->
                                <div class="relative">
                                    <button onclick="toggleProfileMenu()" class="flex items-center text-gray-600 hover:text-gray-900 font-medium transition">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        {{ Auth::user()->name }}
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div id="profileMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                        <a href="{{ url('/profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t-lg">
                                            Profile Settings
                                        </a>
                                        <a href="{{ url('/dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Dashboard
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-100">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-b-lg">
                                                Log Out
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            @if (Route::has('login'))
                                <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 font-medium">Log in</a>
                            @endif
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-indigo-700 transition">Register</a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Welcome to ShopARA</h1>
                <p class="text-xl text-indigo-100 max-w-2xl mx-auto">Your one-stop shop for the latest laptops and phones at unbeatable prices.</p>
            </div>
        </div>

        <!-- Products Section -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">All Products</h2>
            
            <!-- Filter Section -->
            <div class="mb-8 flex justify-center">
                <div class="inline-flex rounded-lg border border-gray-200 bg-white p-1">
                    <button onclick="filterProducts('all')" class="filter-button px-4 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-100 transition">
                        All Items
                    </button>
                    <button onclick="filterProducts('laptop')" class="filter-button px-4 py-2 text-sm font-medium text-gray-500 rounded-md hover:text-gray-700 hover:bg-gray-100 transition">
                        Laptops
                    </button>
                    <button onclick="filterProducts('phone')" class="filter-button px-4 py-2 text-sm font-medium text-gray-500 rounded-md hover:text-gray-700 hover:bg-gray-100 transition">
                        Phones
                    </button>
                </div>
            </div>

            <!-- Products Grid -->
            <div id="productsGrid" class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($products as $product)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden flex flex-col h-full">
                        <a href="{{ route('products.show', $product) }}" class="block flex-shrink-0">
                            <div class="aspect-square {{ $product->category === 'laptop' ? 'bg-gradient-to-br from-blue-500 to-blue-700' : 'bg-gradient-to-br from-purple-500 to-pink-600' }} flex items-center justify-center">
                                @if($product->category === 'laptop')
                                    <svg class="w-24 h-24 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                @else
                                    <svg class="w-24 h-24 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                @endif
                            </div>
                        </a>
                        <div class="p-4 flex flex-col flex-grow">
                            <span class="inline-block px-2 py-1 text-xs font-medium {{ $product->category === 'laptop' ? 'text-blue-600 bg-blue-100' : 'text-purple-600 bg-purple-100' }} rounded-full mb-2 self-start">
                                {{ $product->category_label }}
                            </span>
                            <h3 class="font-semibold text-gray-900 mb-1 text-base leading-tight">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-600 mb-3 flex-grow line-clamp-2">{{ Str::limit($product->description, 80) }}</p>
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-xl font-bold text-gray-900">{{ $product->formatted_price }}</p>
                                <span class="text-sm {{ $product->isInStock() ? 'text-green-600' : 'text-red-600' }} whitespace-nowrap">
                                    {{ $product->isInStock() ? $product->stock . ' in stock' : 'Out of stock' }}
                                </span>
                            </div>
                            @auth
                            <button onclick="addToCart({{ $product->id }})" 
                                    class="add-to-cart-btn w-full {{ $product->isInStock() ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-gray-400 cursor-not-allowed' }} text-white py-2 px-4 rounded-lg font-medium transition mt-auto" 
                                    {{ !$product->isInStock() ? 'disabled' : '' }}
                                    data-product-id="{{ $product->id }}">
                                {{ $product->isInStock() ? 'Add to Cart' : 'Out of Stock' }}
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="block w-full bg-gray-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-gray-700 transition mt-auto text-center">
                                Login to Add to Cart
                            </a>
                        @endauth
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 text-lg">No products found.</p>
                    </div>
                @endforelse
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="text-center">
                    <span class="text-2xl font-bold tracking-tight">
                        <span class="text-indigo-400">Shop</span><span class="text-white">ARA</span>
                    </span>
                    <p class="mt-4 text-gray-400">&copy; {{ date('Y') }} ShopARA. All rights reserved.</p>
                </div>
            </div>
        </footer>

        <script>
            function toggleProfileMenu() {
                const menu = document.getElementById('profileMenu');
                menu.classList.toggle('hidden');
            }

            // Close the dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const button = event.target.closest('button[onclick="toggleProfileMenu()"]');
                const menu = document.getElementById('profileMenu');
                
                if (!button && !menu.contains(event.target)) {
                    menu.classList.add('hidden');
                }
            });

            // Listen for stock updates from cart page using localStorage
            function checkForStockUpdates() {
                const stockUpdate = localStorage.getItem('stockUpdate');
                if (stockUpdate) {
                    const data = JSON.parse(stockUpdate);
                    // Only process updates from the last 5 seconds to avoid stale data
                    if (Date.now() - data.timestamp < 5000) {
                        
                        const button = document.querySelector(`[data-product-id="${data.product_id}"]`);
                        if (button) {
                            // Remove permanent out-of-stock flag if it exists
                            button.removeAttribute('data-permanently-out-of-stock');
                            
                            // Update the stock display
                            updateProductStock(button, data.remaining_stock);
                            
                            // Reset button to proper state
                            if (data.remaining_stock > 0) {
                                button.textContent = 'Add to Cart';
                                button.classList.remove('bg-gray-400', 'cursor-not-allowed', 'opacity-75');
                                button.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
                                button.disabled = false;
                            } else {
                                button.textContent = 'Out of Stock';
                                button.classList.remove('bg-indigo-600', 'hover:bg-indigo-700', 'opacity-75');
                                button.classList.add('bg-gray-400', 'cursor-not-allowed');
                                button.disabled = true;
                                button.setAttribute('data-permanently-out-of-stock', 'true');
                            }
                        }
                    }
                    // Clear the processed update
                    localStorage.removeItem('stockUpdate');
                }
            }

            // Check for updates when page loads and periodically
            checkForStockUpdates();
            setInterval(checkForStockUpdates, 1000);

            // Product filtering
            function filterProducts(category) {
                const url = new URL(window.location);
                if (category === 'all') {
                    url.searchParams.delete('category');
                } else {
                    url.searchParams.set('category', category);
                }
                
                // Update active button state
                document.querySelectorAll('.filter-button').forEach(btn => {
                    btn.classList.remove('bg-gray-100', 'text-gray-700');
                    btn.classList.add('text-gray-500');
                });
                
                event.target.classList.remove('text-gray-500');
                event.target.classList.add('bg-gray-100', 'text-gray-700');
                
                // Fetch filtered products
                fetch(url.pathname + url.search)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newGrid = doc.getElementById('productsGrid');
                        document.getElementById('productsGrid').innerHTML = newGrid.innerHTML;
                    });
            }

            // Add to cart functionality
            function addToCart(productId) {
                const button = document.querySelector(`[data-product-id="${productId}"]`);
                const originalText = button.textContent;
                
                // Show loading state
                button.disabled = true;
                button.textContent = 'Adding...';
                button.classList.add('opacity-75');
                
                fetch('{{ route("cart.add") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: 1
                    })
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Update cart count in header
                        updateCartCount(data.cart_count);
                        
                        // Update stock display on the product card FIRST
                        const isOutOfStock = data.remaining_stock <= 0;
                        
                        updateProductStock(button, data.remaining_stock);
                        
                        // Show success message
                        showNotification(data.message, 'success');
                        
                        // Handle button text and styling based on stock status
                        if (!isOutOfStock) {
                            // Product still in stock - show "Added!" then reset
                            button.textContent = 'Added!';
                            button.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
                            button.classList.add('bg-green-600');
                            
                            setTimeout(() => {
                                // Check if button was marked as permanently out of stock
                                if (button.hasAttribute('data-permanently-out-of-stock')) {
                                    return;
                                }
                                
                                button.textContent = 'Add to Cart';
                                button.classList.remove('bg-green-600', 'opacity-75');
                                button.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
                                button.disabled = false;
                            }, 1500);
                        } else {
                            // Product out of stock - set to "Out of Stock" immediately (no green state)
                            
                            // First remove all possible states
                            button.classList.remove('bg-indigo-600', 'hover:bg-indigo-700', 'bg-green-600', 'opacity-75');
                            
                            // Then apply out-of-stock state
                            button.textContent = 'Out of Stock';
                            button.classList.add('bg-gray-400', 'cursor-not-allowed');
                            button.disabled = true;
                            
                            // Add a permanent flag to prevent any further changes
                            button.setAttribute('data-permanently-out-of-stock', 'true');
                        }
                    } else {
                        // Show error message
                        showNotification(data.message, 'error');
                        
                        // Reset button to original state
                        button.textContent = originalText;
                        button.classList.remove('opacity-75');
                        button.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('An error occurred. Please try again.', 'error');
                    
                    // Reset button to original state
                    button.textContent = originalText;
                    button.classList.remove('opacity-75');
                    button.disabled = false;
                });
            }

            function updateCartCount(count) {
                const cartIcon = document.querySelector('a[href="{{ route("cart.index") }}"]');
                const countBadge = cartIcon.querySelector('span');
                
                if (count > 0) {
                    if (!countBadge) {
                        const badge = document.createElement('span');
                        badge.className = 'absolute -top-2 -right-2 bg-indigo-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center';
                        badge.textContent = count;
                        cartIcon.appendChild(badge);
                    } else {
                        countBadge.textContent = count;
                    }
                } else if (countBadge) {
                    countBadge.remove();
                }
            }

            function updateProductStock(button, remainingStock) {
                // Check if button is permanently marked as out of stock
                if (button.hasAttribute('data-permanently-out-of-stock')) {
                    return;
                }
                
                const card = button.closest('.bg-white');
                const priceContainer = card.querySelector('.text-xl.font-bold.text-gray-900').parentElement;
                const stockElement = priceContainer.querySelector('span.whitespace-nowrap');
                
                if (stockElement) {
                    if (remainingStock > 0) {
                        stockElement.textContent = remainingStock + ' in stock';
                        stockElement.className = 'text-sm text-green-600 whitespace-nowrap';
                        
                        // Enable button if it was disabled (but don't change text or colors if not needed)
                        if (button.disabled) {
                            button.disabled = false;
                            button.classList.remove('bg-gray-400', 'cursor-not-allowed');
                            button.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
                        }
                    } else {
                        stockElement.textContent = 'Out of stock';
                        stockElement.className = 'text-sm text-red-600 whitespace-nowrap';
                        
                        // Only disable if not already permanently out of stock
                        if (!button.hasAttribute('data-permanently-out-of-stock')) {
                            button.disabled = true;
                            button.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
                            button.classList.add('bg-gray-400', 'cursor-not-allowed');
                        }
                    }
                }
            }

            function showNotification(message, type) {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
                    type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
                }`;
                notification.textContent = message;
                
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.remove();
                }, 3000);
            }
        </script>
    </body>
</html>