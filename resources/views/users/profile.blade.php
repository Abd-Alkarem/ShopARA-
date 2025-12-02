<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $user->name }}'s Profile - ShopARA</title>

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
                        <a href="/" class="flex items-center">
                            <span class="text-2xl font-bold tracking-tight">
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

        <!-- User Profile Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- User Info Card -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex items-center space-x-4">
                    <!-- User Avatar -->
                    <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center">
                        <span class="text-indigo-600 font-bold text-2xl">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                    </div>
                    
                    <!-- User Details -->
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $user->name }}'s Profile</h1>
                        <p class="text-lg text-gray-600">{{ $user->email }}</p>
                        <div class="flex items-center space-x-4 mt-2">
                            <span class="text-sm text-gray-500">
                                Member since {{ $user->created_at->format('M j, Y') }}
                            </span>
                            @if($user->email_verified_at)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Verified
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    Pending
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Cart Summary -->
                    <div class="text-right">
                        <div class="text-2xl font-bold text-gray-900">{{ $cartItems->count() }}</div>
                        <div class="text-sm text-gray-600">Items in Cart</div>
                        @if($cartItems->count() > 0)
                            <div class="text-lg font-semibold text-indigo-600">
                                ${{ number_format($cartItems->sum(function($item) { return $item->quantity * $item->product->price; }), 2) }}
                            </div>
                            <div class="text-sm text-gray-600">Total Value</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Cart Items -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">
                        {{ $user->name }}'s Shopping Cart
                    </h2>

                    @forelse($cartItems as $cartItem)
                        <div class="border border-gray-200 rounded-lg p-4 mb-3 hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <!-- Product Image -->
                                    <div class="w-16 h-16 {{ $cartItem->product->category === 'laptop' ? 'bg-gradient-to-br from-blue-500 to-blue-700' : 'bg-gradient-to-br from-purple-500 to-pink-600' }} rounded-lg flex items-center justify-center flex-shrink-0">
                                        @if($cartItem->product->category === 'laptop')
                                            <svg class="w-8 h-8 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        @else
                                            <svg class="w-8 h-8 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        @endif
                                    </div>

                                    <!-- Product Details -->
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900">{{ $cartItem->product->name }}</h3>
                                        <p class="text-sm text-gray-600 mb-1">{{ Str::limit($cartItem->product->description, 80) }}</p>
                                        <div class="flex items-center space-x-4 text-sm">
                                            <span class="font-medium text-gray-900">{{ $cartItem->product->formatted_price }} each</span>
                                            <span class="text-gray-600">Quantity: {{ $cartItem->quantity }}</span>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $cartItem->product->category === 'laptop' ? 'text-blue-600 bg-blue-100' : 'text-purple-600 bg-purple-100' }}">
                                                {{ $cartItem->product->category_label }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Subtotal -->
                                <div class="text-right">
                                    <div class="text-lg font-semibold text-gray-900">
                                        {{ $cartItem->formatted_subtotal }}
                                    </div>
                                    <div class="text-sm text-gray-600">Subtotal</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $user->name }}'s cart is empty</h3>
                            <p class="text-gray-500">No items have been added to the cart yet.</p>
                        </div>
                    @endforelse

                    @if($cartItems->count() > 0)
                        <!-- Cart Summary -->
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="text-lg font-semibold text-gray-900">Total:</span>
                                    <span class="text-2xl font-bold text-gray-900 ml-2">
                                        ${{ number_format($cartItems->sum(function($item) { return $item->quantity * $item->product->price; }), 2) }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-600">
                                    {{ $cartItems->sum('quantity') }} items • {{ $cartItems->count() }} different products
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Back to Users -->
            <div class="mt-6">
                <a href="{{ route('users.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Users
                </a>
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
        </script>
    </body>
</html>
