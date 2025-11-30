<?php

use Livewire\Volt\Component;

new class extends Component
{
    public array $items = [];
    public string $search = '';
    public string $sortBy = 'name';
    public string $selectedCategory = 'all';
    public array $cart = [];
    public int $cartCount = 0;

    public function mount(): void
    {
        $this->loadItems();
        $this->loadCart();
    }

    /**
     * Load items with phones and computers data.
     */
    public function loadItems(): void
    {
        // Sample items data for phones and computers
        $sampleItems = [
            [
                'id' => 1,
                'name' => 'iPhone 15 Pro',
                'description' => 'Latest Apple iPhone with A17 Pro chip',
                'category' => 'Phones',
                'price' => '$999.99',
                'stock' => 50,
                'status' => 'Active',
                'created_at' => '2024-01-15',
            ],
            [
                'id' => 2,
                'name' => 'Samsung Galaxy S24',
                'description' => 'Premium Android smartphone with AI features',
                'category' => 'Phones',
                'price' => '$899.99',
                'stock' => 75,
                'status' => 'Active',
                'created_at' => '2024-01-20',
            ],
            [
                'id' => 3,
                'name' => 'Google Pixel 8',
                'description' => 'Google flagship with advanced AI camera',
                'category' => 'Phones',
                'price' => '$699.99',
                'stock' => 60,
                'status' => 'Active',
                'created_at' => '2024-02-01',
            ],
            [
                'id' => 4,
                'name' => 'OnePlus 12',
                'description' => 'Fast charging smartphone with Snapdragon 8 Gen 3',
                'category' => 'Phones',
                'price' => '$799.99',
                'stock' => 45,
                'status' => 'Active',
                'created_at' => '2024-02-10',
            ],
            [
                'id' => 5,
                'name' => 'MacBook Pro 16"',
                'description' => 'High-performance laptop with M3 Max chip',
                'category' => 'Computers',
                'price' => '$2499.99',
                'stock' => 30,
                'status' => 'Active',
                'created_at' => '2024-01-25',
            ],
            [
                'id' => 6,
                'name' => 'Dell XPS 15',
                'description' => 'Windows laptop with 4K OLED display',
                'category' => 'Computers',
                'price' => '$1799.99',
                'stock' => 40,
                'status' => 'Active',
                'created_at' => '2024-02-05',
            ],
            [
                'id' => 7,
                'name' => 'ThinkPad X1 Carbon',
                'description' => 'Business laptop with military-grade durability',
                'category' => 'Computers',
                'price' => '$1599.99',
                'stock' => 35,
                'status' => 'Active',
                'created_at' => '2024-02-15',
            ],
            [
                'id' => 8,
                'name' => 'Surface Laptop 5',
                'description' => 'Microsoft premium ultrabook with touchscreen',
                'category' => 'Computers',
                'price' => '$1299.99',
                'stock' => 55,
                'status' => 'Active',
                'created_at' => '2024-03-01',
            ],
            [
                'id' => 9,
                'name' => 'ASUS ROG Strix',
                'description' => 'Gaming laptop with RTX 4070 graphics',
                'category' => 'Computers',
                'price' => '$1999.99',
                'stock' => 25,
                'status' => 'Active',
                'created_at' => '2024-03-05',
            ],
        ];

        // Apply category filter
        if ($this->selectedCategory !== 'all') {
            $sampleItems = array_filter($sampleItems, function ($item) {
                return $item['category'] === $this->selectedCategory;
            });
        }

        // Apply search filter
        if ($this->search) {
            $sampleItems = array_filter($sampleItems, function ($item) {
                return stripos($item['name'], $this->search) !== false || 
                       stripos($item['description'], $this->search) !== false ||
                       stripos($item['category'], $this->search) !== false;
            });
        }

        // Apply sorting
        switch ($this->sortBy) {
            case 'name':
                usort($sampleItems, function ($a, $b) {
                    return strcmp($a['name'], $b['name']);
                });
                break;
            case 'price_low':
                usort($sampleItems, function ($a, $b) {
                    return floatval(str_replace('$', '', $a['price'])) - floatval(str_replace('$', '', $b['price']));
                });
                break;
            case 'price_high':
                usort($sampleItems, function ($a, $b) {
                    return floatval(str_replace('$', '', $b['price'])) - floatval(str_replace('$', '', $a['price']));
                });
                break;
            case 'stock':
                usort($sampleItems, function ($a, $b) {
                    return $b['stock'] - $a['stock'];
                });
                break;
        }

        $this->items = array_values($sampleItems);
    }

    /**
     * Load cart from session.
     */
    public function loadCart(): void
    {
        $this->cart = session()->get('cart', []);
        $this->cartCount = array_sum(array_column($this->cart, 'quantity'));
    }

    /**
     * Add item to cart.
     */
    public function addToCart(int $itemId): void
    {
        $item = collect($this->items)->firstWhere('id', $itemId);
        
        if (!$item) {
            session()->flash('error', 'Item not found.');
            return;
        }

        $cart = session()->get('cart', []);
        
        if (isset($cart[$itemId])) {
            $cart[$itemId]['quantity']++;
        } else {
            $cart[$itemId] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'category' => $item['category'],
                'quantity' => 1,
            ];
        }

        session()->put('cart', $cart);
        $this->loadCart();
        
        session()->flash('message', $item['name'] . ' added to cart!');
    }

    /**
     * Updated search.
     */
    public function updatedSearch(): void
    {
        $this->loadItems();
    }

    /**
     * Updated sort by.
     */
    public function updatedSortBy(): void
    {
        $this->loadItems();
    }

    /**
     * Updated selected category.
     */
    public function updatedSelectedCategory(): void
    {
        $this->loadItems();
    }

    /**
     * Get category counts.
     */
    public function getCategoryCountsProperty(): array
    {
        return [
            'all' => 9,
            'Phones' => 4,
            'Computers' => 5,
        ];
    }
}; ?>

<div>
    <!-- Items Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900">{{ __('Items') }}</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">
                        {{ count($this->items) }} {{ __('items found') }}
                    </span>
                    <div class="relative">
                        <a href="{{ route('cart') }}" class="flex items-center space-x-2 text-gray-700 hover:text-indigo-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            @if ($cartCount > 0)
                                <span class="bg-indigo-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    </div>
                    <button class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                        {{ __('Add New Item') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if (session()->has('message'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="p-4 bg-green-50 border border-green-200 rounded-md">
                <p class="text-sm text-green-800">{{ session('message') }}</p>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="p-4 bg-red-50 border border-red-200 rounded-md">
                <p class="text-sm text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Category Tabs -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="bg-white shadow rounded-lg">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 px-6" aria-label="Categories">
                    <button 
                        wire:click="$set('selectedCategory', 'all')"
                        class="{{ $selectedCategory === 'all' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-4 px-1 border-b-2 font-medium text-sm"
                    >
                        {{ __('All Items') }}
                        <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2.5 rounded-full text-xs">
                            {{ $this->categoryCounts['all'] }}
                        </span>
                    </button>
                    <button 
                        wire:click="$set('selectedCategory', 'Phones')"
                        class="{{ $selectedCategory === 'Phones' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-4 px-1 border-b-2 font-medium text-sm"
                    >
                        {{ __('Phones') }}
                        <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2.5 rounded-full text-xs">
                            {{ $this->categoryCounts['Phones'] }}
                        </span>
                    </button>
                    <button 
                        wire:click="$set('selectedCategory', 'Computers')"
                        class="{{ $selectedCategory === 'Computers' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-4 px-1 border-b-2 font-medium text-sm"
                    >
                        {{ __('Computers') }}
                        <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2.5 rounded-full text-xs">
                            {{ $this->categoryCounts['Computers'] }}
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
                        wire:model.live="search"
                        placeholder="{{ __('Search items...') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                </div>

                <!-- Sort By -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Sort By') }}
                    </label>
                    <select
                        wire:model.live="sortBy"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
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
        @if (count($this->items) > 0)
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
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($this->items as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $item['name'] }}</div>
                                        <div class="text-sm text-gray-500">{{ $item['description'] }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item['category'] === 'Phones' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $item['category'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item['price'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm {{ $item['stock'] > 10 ? 'text-green-600' : ($item['stock'] > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ $item['stock'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $item['status'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button 
                                        wire:click="addToCart({{ $item['id'] }})"
                                        class="text-green-600 hover:text-green-900 mr-3"
                                        {{ $item['stock'] <= 0 ? 'disabled' : '' }}
                                    >
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <!-- No Items Found -->
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">
                    {{ $selectedCategory === 'all' ? __('No items found') : __('No items found in this category') }}
                </h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('Try adjusting your search criteria') }}</p>
                <div class="mt-6">
                    <button class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        {{ __('Add New Item') }}
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
