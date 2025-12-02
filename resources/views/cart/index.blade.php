<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Shopping Cart - ShopARA</title>

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
                        <!-- Cart Icon -->
                        <a href="{{ route('cart.index') }}" class="relative flex items-center text-indigo-600 font-medium transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            @if(auth()->user()->cartCount() > 0)
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
                </div>
            </div>
        </nav>

        <!-- Cart Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h1>

            @forelse($cartItems as $cartItem)
                <div class="bg-white rounded-lg shadow-md p-6 mb-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <!-- Product Image -->
                            <div class="w-20 h-20 {{ $cartItem->product->category === 'laptop' ? 'bg-gradient-to-br from-blue-500 to-blue-700' : 'bg-gradient-to-br from-purple-500 to-pink-600' }} rounded-lg flex items-center justify-center flex-shrink-0">
                                @if($cartItem->product->category === 'laptop')
                                    <svg class="w-10 h-10 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                @else
                                    <svg class="w-10 h-10 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                @endif
                            </div>

                            <!-- Product Details -->
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $cartItem->product->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $cartItem->product->formatted_price }} each</p>
                                <span class="inline-block px-2 py-1 text-xs font-medium {{ $cartItem->product->category === 'laptop' ? 'text-blue-600 bg-blue-100' : 'text-purple-600 bg-purple-100' }} rounded-full mt-1">
                                    {{ $cartItem->product->category_label }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center space-x-4">
                            <!-- Quantity Controls -->
                            <div class="flex items-center space-x-2">
                                <button onclick="updateQuantity({{ $cartItem->id }}, {{ $cartItem->quantity - 1 }})" 
                                        class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-100 transition"
                                        {{ $cartItem->quantity <= 1 ? 'disabled' : '' }}>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                    </svg>
                                </button>
                                <span class="w-12 text-center font-medium">{{ $cartItem->quantity }}</span>
                                <button onclick="updateQuantity({{ $cartItem->id }}, {{ $cartItem->quantity + 1 }})" 
                                        class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-100 transition"
                                        {{ $cartItem->quantity >= $cartItem->product->stock ? 'disabled' : '' }}>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Subtotal -->
                            <div class="text-right">
                                <p class="font-semibold text-gray-900">{{ $cartItem->formatted_subtotal }}</p>
                                <button onclick="removeFromCart({{ $cartItem->id }})" class="text-red-600 hover:text-red-800 text-sm font-medium transition">
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">Your cart is empty</h2>
                    <p class="text-gray-600 mb-6">Looks like you haven't added any products to your cart yet.</p>
                    <a href="/" class="inline-flex items-center bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-indigo-700 transition">
                        Continue Shopping
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
            @endforelse

            @if($cartItems->count() > 0)
                <!-- Cart Summary -->
                <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-lg font-semibold text-gray-900">Total:</span>
                        <span class="text-2xl font-bold text-gray-900">{{ auth()->user()->formatted_cart_total }}</span>
                    </div>
                    <div class="flex space-x-4">
                        <button onclick="clearCart()" class="flex-1 bg-gray-200 text-gray-800 py-3 px-6 rounded-lg font-medium hover:bg-gray-300 transition">
                            Clear Cart
                        </button>
                        <button class="flex-1 bg-indigo-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-indigo-700 transition">
                            Proceed to Checkout
                        </button>
                    </div>
                </div>
            @endif
        </main>

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

            function updateQuantity(cartItemId, newQuantity) {
                if (newQuantity < 1) return;

                fetch(`{{ route('cart.update', ':id') }}`.replace(':id', cartItemId), {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        quantity: newQuantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Store stock update in localStorage for the main page to pick up
                        localStorage.setItem('stockUpdate', JSON.stringify({
                            remaining_stock: data.remaining_stock,
                            product_id: data.product_id,
                            timestamp: Date.now()
                        }));
                        
                        location.reload();
                    } else {
                        showNotification(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('An error occurred. Please try again.', 'error');
                });
            }

            function removeFromCart(cartItemId) {
                if (!confirm('Are you sure you want to remove this item from your cart?')) return;

                fetch(`{{ route('cart.remove', ':id') }}`.replace(':id', cartItemId), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Store stock update in localStorage for the main page to pick up
                        localStorage.setItem('stockUpdate', JSON.stringify({
                            remaining_stock: data.remaining_stock,
                            product_id: data.product_id,
                            timestamp: Date.now()
                        }));
                        
                        location.reload();
                    } else {
                        showNotification(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('An error occurred. Please try again.', 'error');
                });
            }

            function clearCart() {
                if (!confirm('Are you sure you want to clear your entire cart?')) return;

                // Show loading state
                const clearButton = event.target;
                const originalText = clearButton.textContent;
                clearButton.textContent = 'Clearing...';
                clearButton.disabled = true;

                fetch('{{ route("cart.clear") }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response ok:', response.ok);
                    
                    if (!response.ok) {
                        return response.text().then(text => {
                            console.log('Error response text:', text);
                            throw new Error(`HTTP error! status: ${response.status}, message: ${text}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Success data:', data);
                    if (data.success) {
                        showNotification(data.message, 'success');
                        // Update cart count in header to 0
                        updateCartCount(0);
                        // Immediately show empty cart state without reload
                        showEmptyCart();
                    } else {
                        showNotification(data.message || 'Cart clear failed', 'error');
                        // Restore button state
                        clearButton.textContent = originalText;
                        clearButton.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error: ' + error.message, 'error');
                    // Restore button state
                    clearButton.textContent = originalText;
                    clearButton.disabled = false;
                });
            }

            function showEmptyCart() {
                // Replace cart content with empty cart immediately
                const cartContent = document.querySelector('main');
                if (cartContent) {
                    cartContent.innerHTML = `
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                            <h1 class="text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h1>
                            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <h2 class="text-xl font-semibold text-gray-900 mb-2">Your cart is empty</h2>
                                <p class="text-gray-600 mb-6">Looks like you haven't added any products to your cart yet.</p>
                                <a href="/" class="inline-flex items-center bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-indigo-700 transition">
                                    Continue Shopping
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    `;
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

            function updateCartCount(count) {
                // Update cart count in the navigation
                const cartCountElement = document.querySelector('.absolute.-top-2.-right-2.bg-indigo-600');
                if (cartCountElement) {
                    if (count > 0) {
                        cartCountElement.textContent = count;
                        cartCountElement.classList.remove('hidden');
                    } else {
                        cartCountElement.classList.add('hidden');
                    }
                }
            }
        </script>
    </body>
</html>
