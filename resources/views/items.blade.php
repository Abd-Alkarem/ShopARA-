<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Items') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <!-- Items Header -->
        <div class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-gray-900">{{ __('Items') }}</h1>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-500">
                            9 {{ __('items found') }}
                        </span>
                        <div class="relative">
                            <a href="{{ route('cart') }}" class="flex items-center space-x-2 text-gray-700 hover:text-indigo-600">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <span id="cart-count" class="bg-indigo-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">0</span>
                            </a>
                        </div>
                        <button class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                            {{ __('Add New Item') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        <div id="success-message" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 hidden">
            <div class="p-4 bg-green-50 border border-green-200 rounded-md">
                <p class="text-sm text-green-800" id="success-text"></p>
            </div>
        </div>

        <!-- Category Tabs -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="bg-white shadow rounded-lg">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8 px-6" aria-label="Categories">
                        <button onclick="filterItems('all')" class="category-tab border-indigo-500 text-indigo-600 py-4 px-1 border-b-2 font-medium text-sm" data-category="all">
                            {{ __('All Items') }}
                            <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2.5 rounded-full text-xs">
                                9
                            </span>
                        </button>
                        <button onclick="filterItems('Phones')" class="category-tab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 border-b-2 font-medium text-sm" data-category="Phones">
                            {{ __('Phones') }}
                            <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2.5 rounded-full text-xs">
                                4
                            </span>
                        </button>
                        <button onclick="filterItems('Computers')" class="category-tab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 border-b-2 font-medium text-sm" data-category="Computers">
                            {{ __('Computers') }}
                            <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2.5 rounded-full text-xs">
                                5
                            </span>
                        </button>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Search') }}
                        </label>
                        <input
                            type="text"
                            id="search-input"
                            onkeyup="searchItems()"
                            placeholder="{{ __('Search items...') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                    </div>

                    <!-- Sort By -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Sort By') }}
                        </label>
                        <select id="sort-select" onchange="sortItems()" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="name">{{ __('Name') }}</option>
                            <option value="price_low">{{ __('Price: Low to High') }}</option>
                            <option value="price_high">{{ __('Price: High to Low') }}</option>
                            <option value="stock">{{ __('Stock Quantity') }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Name') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Category') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Price') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Stock') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Status') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody id="items-table-body" class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50" data-category="Phones">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">iPhone 15 Pro</div>
                                    <div class="text-sm text-gray-500">Latest Apple iPhone with A17 Pro chip</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Phones
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                $999.99
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-green-600">50</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="addToCart(1, 'iPhone 15 Pro', '$999.99', 'Phones')" class="text-green-600 hover:text-green-900 mr-3">
                                    {{ __('Add to Cart') }}
                                </button>
                                <button class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    {{ __('Edit') }}
                                </button>
                                <button class="text-red-600 hover:text-red-900">
                                    {{ __('Delete') }}
                                </button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50" data-category="Phones">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">Samsung Galaxy S24</div>
                                    <div class="text-sm text-gray-500">Premium Android smartphone with AI features</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Phones
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                $899.99
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-green-600">75</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="addToCart(2, 'Samsung Galaxy S24', '$899.99', 'Phones')" class="text-green-600 hover:text-green-900 mr-3">
                                    {{ __('Add to Cart') }}
                                </button>
                                <button class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    {{ __('Edit') }}
                                </button>
                                <button class="text-red-600 hover:text-red-900">
                                    {{ __('Delete') }}
                                </button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50" data-category="Phones">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">Google Pixel 8</div>
                                    <div class="text-sm text-gray-500">Google flagship with advanced AI camera</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Phones
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                $699.99
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-green-600">60</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="addToCart(3, 'Google Pixel 8', '$699.99', 'Phones')" class="text-green-600 hover:text-green-900 mr-3">
                                    {{ __('Add to Cart') }}
                                </button>
                                <button class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    {{ __('Edit') }}
                                </button>
                                <button class="text-red-600 hover:text-red-900">
                                    {{ __('Delete') }}
                                </button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50" data-category="Phones">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">OnePlus 12</div>
                                    <div class="text-sm text-gray-500">Fast charging smartphone with Snapdragon 8 Gen 3</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Phones
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                $799.99
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-green-600">45</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="addToCart(4, 'OnePlus 12', '$799.99', 'Phones')" class="text-green-600 hover:text-green-900 mr-3">
                                    {{ __('Add to Cart') }}
                                </button>
                                <button class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    {{ __('Edit') }}
                                </button>
                                <button class="text-red-600 hover:text-red-900">
                                    {{ __('Delete') }}
                                </button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50" data-category="Computers">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">MacBook Pro 16"</div>
                                    <div class="text-sm text-gray-500">High-performance laptop with M3 Max chip</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Computers
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                $2499.99
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-green-600">30</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="addToCart(5, 'MacBook Pro 16\"', '$2499.99', 'Computers')" class="text-green-600 hover:text-green-900 mr-3">
                                    {{ __('Add to Cart') }}
                                </button>
                                <button class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    {{ __('Edit') }}
                                </button>
                                <button class="text-red-600 hover:text-red-900">
                                    {{ __('Delete') }}
                                </button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50" data-category="Computers">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">Dell XPS 15</div>
                                    <div class="text-sm text-gray-500">Windows laptop with 4K OLED display</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Computers
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                $1799.99
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-green-600">40</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="addToCart(6, 'Dell XPS 15', '$1799.99', 'Computers')" class="text-green-600 hover:text-green-900 mr-3">
                                    {{ __('Add to Cart') }}
                                </button>
                                <button class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    {{ __('Edit') }}
                                </button>
                                <button class="text-red-600 hover:text-red-900">
                                    {{ __('Delete') }}
                                </button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50" data-category="Computers">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">ThinkPad X1 Carbon</div>
                                    <div class="text-sm text-gray-500">Business laptop with military-grade durability</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Computers
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                $1599.99
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-green-600">35</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="addToCart(7, 'ThinkPad X1 Carbon', '$1599.99', 'Computers')" class="text-green-600 hover:text-green-900 mr-3">
                                    {{ __('Add to Cart') }}
                                </button>
                                <button class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    {{ __('Edit') }}
                                </button>
                                <button class="text-red-600 hover:text-red-900">
                                    {{ __('Delete') }}
                                </button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50" data-category="Computers">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">Surface Laptop 5</div>
                                    <div class="text-sm text-gray-500">Microsoft premium ultrabook with touchscreen</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Computers
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                $1299.99
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-green-600">55</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="addToCart(8, 'Surface Laptop 5', '$1299.99', 'Computers')" class="text-green-600 hover:text-green-900 mr-3">
                                    {{ __('Add to Cart') }}
                                </button>
                                <button class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    {{ __('Edit') }}
                                </button>
                                <button class="text-red-600 hover:text-red-900">
                                    {{ __('Delete') }}
                                </button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50" data-category="Computers">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">ASUS ROG Strix</div>
                                    <div class="text-sm text-gray-500">Gaming laptop with RTX 4070 graphics</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Computers
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                $1999.99
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-green-600">25</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="addToCart(9, 'ASUS ROG Strix', '$1999.99', 'Computers')" class="text-green-600 hover:text-green-900 mr-3">
                                    {{ __('Add to Cart') }}
                                </button>
                                <button class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    {{ __('Edit') }}
                                </button>
                                <button class="text-red-600 hover:text-red-900">
                                    {{ __('Delete') }}
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
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
        
        function updateCartCount() {
            const count = Object.values(cart).reduce((sum, item) => sum + item.quantity, 0);
            const cartCountElement = document.getElementById('cart-count');
            if (count > 0) {
                cartCountElement.textContent = count;
                cartCountElement.classList.remove('hidden');
            } else {
                cartCountElement.classList.add('hidden');
            }
        }

        function addToCart(id, name, price, category) {
            // Double-check user is authenticated
            if (userId === null) {
                alert('Please log in to add items to cart.');
                return;
            }
            
            if (cart[id]) {
                cart[id].quantity++;
            } else {
                cart[id] = {
                    id: id,
                    name: name,
                    price: price,
                    category: category,
                    quantity: 1
                };
            }
            
            localStorage.setItem(cartKey, JSON.stringify(cart));
            updateCartCount();
            
            // Show success message
            const messageDiv = document.getElementById('success-message');
            const messageText = document.getElementById('success-text');
            messageText.textContent = name + ' added to cart!';
            messageDiv.classList.remove('hidden');
            
            // Hide message after 3 seconds
            setTimeout(() => {
                messageDiv.classList.add('hidden');
            }, 3000);
        }

        function filterItems(category) {
            const rows = document.querySelectorAll('#items-table-body tr');
            const tabs = document.querySelectorAll('.category-tab');
            
            // Update tab styles
            tabs.forEach(tab => {
                if (tab.dataset.category === category) {
                    tab.classList.remove('border-transparent', 'text-gray-500');
                    tab.classList.add('border-indigo-500', 'text-indigo-600');
                } else {
                    tab.classList.remove('border-indigo-500', 'text-indigo-600');
                    tab.classList.add('border-transparent', 'text-gray-500');
                }
            });
            
            // Filter rows
            rows.forEach(row => {
                if (category === 'all' || row.dataset.category === category) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function searchItems() {
            const searchTerm = document.getElementById('search-input').value.toLowerCase();
            const rows = document.querySelectorAll('#items-table-body tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        }

        function sortItems() {
            const sortBy = document.getElementById('sort-select').value;
            const tbody = document.getElementById('items-table-body');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            
            rows.sort((a, b) => {
                switch(sortBy) {
                    case 'name':
                        return a.querySelector('td:first-child .text-gray-900').textContent.localeCompare(
                            b.querySelector('td:first-child .text-gray-900').textContent
                        );
                    case 'price_low':
                        const priceA = parseFloat(a.querySelector('td:nth-child(3)').textContent.replace('$', ''));
                        const priceB = parseFloat(b.querySelector('td:nth-child(3)').textContent.replace('$', ''));
                        return priceA - priceB;
                    case 'price_high':
                        const priceHighA = parseFloat(a.querySelector('td:nth-child(3)').textContent.replace('$', ''));
                        const priceHighB = parseFloat(b.querySelector('td:nth-child(3)').textContent.replace('$', ''));
                        return priceHighB - priceHighA;
                    case 'stock':
                        const stockA = parseInt(a.querySelector('td:nth-child(4) span').textContent);
                        const stockB = parseInt(b.querySelector('td:nth-child(4) span').textContent);
                        return stockB - stockA;
                    default:
                        return 0;
                }
            });
            
            rows.forEach(row => tbody.appendChild(row));
        }

        // Initialize cart count on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
        });
    </script>
</x-app-layout>
