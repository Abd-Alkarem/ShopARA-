<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Shopping Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <!-- Cart Header -->
        <div class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-gray-900">{{ __('Shopping Cart') }}</h1>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-500" id="cart-items-count">
                            {{ __('Loading...') }}
                        </span>
                        <button 
                            onclick="clearCart()"
                            class="text-sm text-red-600 hover:text-red-500 hidden"
                            id="clear-cart-btn"
                        >
                            {{ __('Clear Cart') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        <div id="success-message" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 hidden">
            <div class="p-4 bg-green-50 border border-green-200 rounded-md">
                <p class="text-sm text-green-800" id="success-text"></p>
            </div>
        </div>

        <!-- Cart Items -->
        <div id="cart-content">
            <!-- Content will be loaded by JavaScript -->
        </div>
    </div>

    <script>
        // Get user ID from Blade (passed from Laravel) with fallback
        const userId = {{ auth()->check() ? auth()->id() : 'null' }};
        
        // Only initialize cart if user is authenticated
        let cart = {};
        let cartKey = 'cart_guest';
        
        if (userId !== null) {
            cartKey = 'cart_user_' + userId;
            cart = JSON.parse(localStorage.getItem(cartKey)) || {};
        } else {
            // Fallback for unauthenticated users (shouldn't happen due to auth middleware)
            cart = JSON.parse(localStorage.getItem(cartKey)) || {};
        }
        
        function updateCartDisplay() {
            const cartItems = Object.values(cart);
            const cartContent = document.getElementById('cart-content');
            const cartItemsCount = document.getElementById('cart-items-count');
            const clearCartBtn = document.getElementById('clear-cart-btn');
            
            if (cartItems.length === 0) {
                // Show empty cart
                cartContent.innerHTML = `
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                        <div class="bg-white rounded-lg shadow p-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('Your cart is empty') }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ __('Add some items from the Items page to get started!') }}</p>
                            <div class="mt-6">
                                <a href="{{ route('items') }}" class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                                    {{ __('Continue Shopping') }}
                                </a>
                            </div>
                        </div>
                    </div>
                `;
                cartItemsCount.textContent = '0 items';
                clearCartBtn.classList.add('hidden');
            } else {
                // Show cart items
                let total = 0;
                let itemsHtml = '';
                
                cartItems.forEach(item => {
                    const subtotal = parseFloat(item.price.replace('$', '')) * item.quantity;
                    total += subtotal;
                    
                    itemsHtml += `
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">${item.name}</div>
                                    <div class="text-sm text-gray-500">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${item.category === 'Phones' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'}">
                                            ${item.category}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${item.price}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <button 
                                        onclick="updateQuantity(${item.id}, ${item.quantity - 1})"
                                        class="p-1 text-gray-400 hover:text-gray-600"
                                        ${item.quantity <= 1 ? 'disabled' : ''}
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                        </svg>
                                    </button>
                                    <span class="w-8 text-center text-sm font-medium">${item.quantity}</span>
                                    <button 
                                        onclick="updateQuantity(${item.id}, ${item.quantity + 1})"
                                        class="p-1 text-gray-400 hover:text-gray-600"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                $${subtotal.toFixed(2)}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button 
                                    onclick="removeItem(${item.id})"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    {{ __('Remove') }}
                                </button>
                            </td>
                        </tr>
                    `;
                });
                
                cartContent.innerHTML = `
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                        <div class="bg-white shadow rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Item') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Price') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Quantity') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Subtotal') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    ${itemsHtml}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Cart Summary -->
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                        <div class="bg-white shadow rounded-lg">
                            <div class="px-4 py-6 sm:px-6 sm:py-8">
                                <div class="flex justify-between text-base font-medium text-gray-900">
                                    <p>{{ __('Total') }}</p>
                                    <p>$${total.toFixed(2)}</p>
                                </div>
                                <div class="mt-6">
                                    <button class="w-full flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700">
                                        {{ __('Checkout') }}
                                    </button>
                                </div>
                                <div class="mt-6 flex justify-center text-sm text-gray-500">
                                    <p>
                                        {{ __('or') }}
                                        <a href="{{ route('items') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                                            {{ __('Continue Shopping') }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                const totalItems = cartItems.reduce((sum, item) => sum + item.quantity, 0);
                cartItemsCount.textContent = `${totalItems} ${totalItems === 1 ? 'item' : 'items'}`;
                clearCartBtn.classList.remove('hidden');
            }
        }

        function updateQuantity(itemId, quantity) {
            // Double-check user is authenticated
            if (userId === null) {
                alert('Please log in to manage your cart.');
                return;
            }
            
            if (quantity <= 0) {
                removeItem(itemId);
                return;
            }

            if (cart[itemId]) {
                cart[itemId].quantity = quantity;
                localStorage.setItem(cartKey, JSON.stringify(cart));
                updateCartDisplay();
            }
        }

        function removeItem(itemId) {
            // Double-check user is authenticated
            if (userId === null) {
                alert('Please log in to manage your cart.');
                return;
            }
            
            if (cart[itemId]) {
                delete cart[itemId];
                localStorage.setItem(cartKey, JSON.stringify(cart));
                updateCartDisplay();
                
                // Show success message
                showMessage('Item removed from cart!');
            }
        }

        function clearCart() {
            // Double-check user is authenticated
            if (userId === null) {
                alert('Please log in to manage your cart.');
                return;
            }
            
            if (confirm('Are you sure you want to clear your entire cart?')) {
                cart = {};
                localStorage.setItem(cartKey, JSON.stringify(cart));
                updateCartDisplay();
                showMessage('Cart cleared!');
            }
        }

        function showMessage(message) {
            const messageDiv = document.getElementById('success-message');
            const messageText = document.getElementById('success-text');
            messageText.textContent = message;
            messageDiv.classList.remove('hidden');
            
            setTimeout(() => {
                messageDiv.classList.add('hidden');
            }, 3000);
        }

        // Initialize cart display on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCartDisplay();
        });
    </script>
</x-app-layout>
