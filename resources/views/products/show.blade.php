<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $product->name }} - ShopARA</title>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="/favicon.svg">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
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
                        @if(auth()->check())
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
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                Log in
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Product Details -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-8">
                    <!-- Product Image -->
                    <div class="flex items-center justify-center">
                        <div class="w-full h-96 {{ $product->category === 'laptop' ? 'bg-gradient-to-br from-blue-500 to-blue-700' : 'bg-gradient-to-br from-purple-500 to-pink-600' }} rounded-lg flex items-center justify-center">
                            @if($product->category === 'laptop')
                                <svg class="w-32 h-32 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            @else
                                <svg class="w-32 h-32 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            @endif
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="flex flex-col justify-between">
                        <div>
                            <!-- Breadcrumb -->
                            <nav class="flex mb-4" aria-label="Breadcrumb">
                                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                                    <li class="inline-flex items-center">
                                        <a href="/" class="text-gray-700 hover:text-gray-900">
                                            Home
                                        </a>
                                    </li>
                                    <li>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </li>
                                    <li class="inline-flex items-center">
                                        <a href="#" class="text-gray-700 hover:text-gray-900">
                                            {{ $product->category_label }}
                                        </a>
                                    </li>
                                    <li>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </li>
                                    <li aria-current="page">
                                        <div class="text-gray-500">{{ $product->name }}</div>
                                    </li>
                                </ol>
                            </nav>

                            <!-- Title and Category -->
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                            <div class="flex items-center space-x-4 mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $product->category === 'laptop' ? 'text-blue-600 bg-blue-100' : 'text-purple-600 bg-purple-100' }}">
                                    {{ $product->category_label }}
                                </span>
                                @if($product->isInStock())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $product->stock }} in stock
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                        Out of stock
                                    </span>
                                @endif
                            </div>

                            <!-- Price -->
                            <div class="mb-6">
                                <span class="text-4xl font-bold text-gray-900">{{ $product->formatted_price }}</span>
                            </div>

                            <!-- Description -->
                            <div class="mb-8">
                                <h2 class="text-lg font-semibold text-gray-900 mb-2">Description</h2>
                                <p class="text-gray-600 leading-relaxed">
                                    {{ $product->description ?: 'No description available for this product.' }}
                                </p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="space-y-4">
                            @if(auth()->check())
                                @if($product->isInStock())
                                    <button onclick="addToCart({{ $product->id }})" 
                                            data-product-id="{{ $product->id }}"
                                            class="w-full bg-indigo-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-indigo-700 transition">
                                        Add to Cart
                                    </button>
                                @else
                                    <button disabled 
                                            class="w-full bg-gray-400 text-gray-200 py-3 px-6 rounded-lg font-medium cursor-not-allowed">
                                        Out of Stock
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" 
                                   class="w-full bg-gray-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-gray-700 transition text-center block">
                                    Login to Add to Cart
                                </a>
                            @endif

                            <div class="flex justify-center">
                                <a href="/" class="text-center text-gray-600 hover:text-gray-900 font-medium py-2 transition">
                                    ← Continue Shopping
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="border-t border-gray-200 p-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Product Details</h3>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li><strong>Category:</strong> {{ $product->category_label }}</li>
                                <li><strong>Stock:</strong> {{ $product->stock }} units</li>
                                <li><strong>Availability:</strong> 
                                    @if($product->isInStock())
                                        <span class="text-green-600">Available</span>
                                    @else
                                        <span class="text-red-600 font-medium">Out of Stock</span>
                                    @endif
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Shipping & Returns</h3>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li>• Free shipping on orders over $50</li>
                                <li>• 30-day return policy</li>
                                <li>• 1-year manufacturer warranty</li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Customer Support</h3>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li>• 24/7 customer service</li>
                                <li>• Technical support available</li>
                                <li>• Extended warranty options</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Update cart count in header
                        updateCartCount(data.cart_count);
                        
                        // Update stock display on product page
                        updateStockDisplay(data.remaining_stock, data.stock_status, productId);
                        
                        // Store whether item is out of stock for the setTimeout check
                        const isOutOfStock = data.stock_status !== 'in_stock';
                        
                        // Show success message
                        showNotification(data.message, 'success');
                        
                        // If out of stock, don't show "Added!" state, update immediately
                        if (isOutOfStock) {
                            button.disabled = true;
                            button.className = 'w-full bg-gray-400 text-gray-200 py-3 px-6 rounded-lg font-medium cursor-not-allowed';
                            button.textContent = 'Out of Stock';
                            button.removeAttribute('onclick');
                        } else {
                            // Reset button for in-stock items
                            button.textContent = 'Added!';
                            button.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
                            button.classList.add('bg-green-600');
                            
                            setTimeout(() => {
                                button.textContent = originalText;
                                button.classList.remove('bg-green-600', 'opacity-75');
                                button.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
                                button.disabled = false;
                            }, 1500);
                        }
                    } else {
                        // Show error message
                        showNotification(data.message, 'error');
                        
                        // Reset button
                        button.textContent = originalText;
                        button.classList.remove('opacity-75');
                        button.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('An error occurred. Please try again.', 'error');
                    
                    // Reset button
                    button.textContent = originalText;
                    button.classList.remove('opacity-75');
                    button.disabled = false;
                });
            }

            function updateStockDisplay(newStock, stockStatus, productId) {
                // Update stock in the additional info section
                const stockInfoElements = document.querySelectorAll('li strong');
                stockInfoElements.forEach(element => {
                    if (element.textContent === 'Stock:') {
                        const li = element.parentElement;
                        li.innerHTML = `<strong>Stock:</strong> ${newStock} units`;
                    }
                    if (element.textContent === 'Availability:') {
                        const li = element.parentElement;
                        if (stockStatus === 'in_stock') {
                            li.innerHTML = `<strong>Availability:</strong> <span class="text-green-600">Available</span>`;
                        } else {
                            li.innerHTML = `<strong>Availability:</strong> <span class="text-red-600 font-medium">Out of Stock</span>`;
                        }
                    }
                });

                // Update the stock badge in the product info section
                const stockBadge = document.querySelector('.inline-flex.items-center.px-3.py-1.rounded-full.text-sm.font-medium.bg-green-100, .inline-flex.items-center.px-3.py-1.rounded-full.text-sm.font-medium.bg-red-100');
                if (stockBadge) {
                    if (stockStatus === 'in_stock') {
                        stockBadge.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800';
                        stockBadge.innerHTML = `
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            ${newStock} in stock
                        `;
                    } else {
                        stockBadge.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800';
                        stockBadge.innerHTML = `
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            Out of stock
                        `;
                    }
                }

                // Button update is now handled in the main fetch function to prevent conflicts
            }

            function updateCartCount(count) {
                const cartIcon = document.querySelector('a[href="{{ route('cart.index') }}"]');
                let countBadge = cartIcon.querySelector('.absolute');
                
                if (count > 0) {
                    if (!countBadge) {
                        countBadge = document.createElement('span');
                        countBadge.className = 'absolute -top-2 -right-2 bg-indigo-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center';
                        cartIcon.appendChild(countBadge);
                    }
                    countBadge.textContent = count;
                } else if (countBadge) {
                    countBadge.remove();
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
