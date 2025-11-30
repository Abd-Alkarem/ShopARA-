<?php

use App\Models\Category;
use App\Models\Item;
use App\Services\ShoppingCartService;
use Livewire\Volt\Component;

new class extends Component
{
    public array $items = [];
    public array $categories = [];
    public string $selectedCategory = '';
    public string $search = '';
    public string $sortBy = 'name';
    public int $cartCount = 0;

    public function mount(): void
    {
        $this->categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->pluck('name', 'slug')
            ->toArray();
        
        $this->loadItems();
        $this->cartCount = auth()->user()->cart_items_count;
    }

    /**
     * Load items based on filters.
     */
    public function loadItems(): void
    {
        $query = Item::with('category')
            ->where('is_active', true);

        if ($this->selectedCategory) {
            $category = Category::where('slug', $this->selectedCategory)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        switch ($this->sortBy) {
            case 'price_low':
                $query->orderBy('current_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('current_price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('name', 'asc');
        }

        $this->items = $query->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'slug' => $item->slug,
                    'description' => $item->description,
                    'price' => $item->formatted_price,
                    'sale_price' => $item->formatted_sale_price,
                    'current_price' => $item->formatted_current_price,
                    'is_on_sale' => $item->is_on_sale,
                    'image' => $item->image,
                    'category' => $item->category->name,
                    'stock_quantity' => $item->stock_quantity,
                    'is_featured' => $item->is_featured,
                ];
            })
            ->toArray();
    }

    /**
     * Add item to cart.
     */
    public function addToCart(int $itemId): void
    {
        $cartService = app(ShoppingCartService::class);
        $result = $cartService->addItem($itemId);

        if ($result['success']) {
            $this->cartCount = $result['cart_count'];
            $this->dispatch('cart-updated', $this->cartCount);
            session()->flash('message', $result['message']);
        } else {
            session()->flash('error', $result['message']);
        }
    }

    /**
     * Updated selected category.
     */
    public function updatedSelectedCategory(): void
    {
        $this->loadItems();
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
}; ?>

<div>
    <!-- Shop Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900">{{ __('Shop') }}</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">
                        {{ count($this->items) }} {{ __('products found') }}
                    </span>
                    <div class="relative">
                        <a href="{{ route('cart') }}" class="flex items-center space-x-2 text-gray-700 hover:text-indigo-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <span class="bg-indigo-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                {{ $cartCount }}
                            </span>
                        </a>
                    </div>
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

    <!-- Filters and Search -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Search') }}
                    </label>
                    <input
                        type="text"
                        wire:model.live="search"
                        placeholder="{{ __('Search products...') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Category') }}
                    </label>
                    <select
                        wire:model.live="selectedCategory"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                        <option value="">{{ __('All Categories') }}</option>
                        @foreach ($categories as $slug => $name)
                            <option value="{{ $slug }}">{{ $name }}</option>
                        @endforeach
                    </select>
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
                        <option value="newest">{{ __('Newest') }}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        @if (count($this->items) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($this->items as $item)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <!-- Product Image -->
                        <div class="relative">
                            @if ($item['image'])
                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            
                            @if ($item['is_on_sale'])
                                <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded">
                                    {{ __('Sale') }}
                                </span>
                            @endif
                            
                            @if ($item['is_featured'])
                                <span class="absolute top-2 left-2 bg-indigo-600 text-white text-xs px-2 py-1 rounded">
                                    {{ __('Featured') }}
                                </span>
                            @endif
                        </div>

                        <!-- Product Info -->
                        <div class="p-4">
                            <div class="mb-2">
                                <span class="text-xs text-gray-500">{{ $item['category'] }}</span>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">
                                <a href="{{ route('items.show', $item['slug']) }}" class="hover:text-indigo-600">
                                    {{ $item['name'] }}
                                </a>
                            </h3>
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                {{ Str::limit($item['description'], 80) }}
                            </p>
                            
                            <!-- Price -->
                            <div class="mb-3">
                                @if ($item['is_on_sale'])
                                    <span class="text-lg font-bold text-indigo-600">{{ $item['current_price'] }}</span>
                                    <span class="text-sm text-gray-500 line-through ml-2">{{ $item['price'] }}</span>
                                @else
                                    <span class="text-lg font-bold text-gray-900">{{ $item['current_price'] }}</span>
                                @endif
                            </div>

                            <!-- Stock Status -->
                            <div class="mb-3">
                                @if ($item['stock_quantity'] > 0)
                                    <span class="text-xs text-green-600">{{ __('In Stock') }} ({{ $item['stock_quantity'] }})</span>
                                @else
                                    <span class="text-xs text-red-600">{{ __('Out of Stock') }}</span>
                                @endif
                            </div>

                            <!-- Add to Cart Button -->
                            <button
                                wire:click="addToCart({{ $item['id'] }})"
                                {{ $item['stock_quantity'] <= 0 ? 'disabled' : '' }}
                                class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors duration-200"
                            >
                                @if ($item['stock_quantity'] > 0)
                                    {{ __('Add to Cart') }}
                                @else
                                    {{ __('Out of Stock') }}
                                @endif
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- No Products Found -->
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('No products found') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('Try adjusting your search or filter criteria') }}</p>
            </div>
        @endif
    </div>
</div>
