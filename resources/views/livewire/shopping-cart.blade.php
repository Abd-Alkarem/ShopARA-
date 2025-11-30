<?php

use App\Services\ShoppingCartService;
use Livewire\Volt\Component;

new class extends Component
{
    public array $cartItems = [];
    public float $cartTotal = 0;
    public int $cartCount = 0;

    public function mount(): void
    {
        $this->refreshCart();
    }

    /**
     * Refresh cart data.
     */
    public function refreshCart(): void
    {
        $cartService = app(ShoppingCartService::class);
        $this->cartItems = $cartService->getCartItems()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'item_id' => $item->item_id,
                    'name' => $item->item->name,
                    'price' => $item->price,
                    'formatted_price' => $item->item->formatted_current_price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->subtotal,
                    'formatted_subtotal' => $item->formatted_subtotal,
                    'image' => $item->item->image,
                    'slug' => $item->item->slug,
                ];
            })
            ->toArray();

        $this->cartTotal = auth()->user()->cart_total;
        $this->cartCount = auth()->user()->cart_items_count;
    }

    /**
     * Update item quantity.
     */
    public function updateQuantity(int $itemId, int $quantity): void
    {
        $cartService = app(ShoppingCartService::class);
        $result = $cartService->updateQuantity($itemId, $quantity);

        if ($result['success']) {
            $this->refreshCart();
            $this->dispatch('cart-updated', $result['cart_count']);
            session()->flash('message', $result['message']);
        } else {
            session()->flash('error', $result['message']);
        }
    }

    /**
     * Remove item from cart.
     */
    public function removeItem(int $itemId): void
    {
        $cartService = app(ShoppingCartService::class);
        $result = $cartService->removeItem($itemId);

        if ($result['success']) {
            $this->refreshCart();
            $this->dispatch('cart-updated', $result['cart_count']);
            session()->flash('message', $result['message']);
        } else {
            session()->flash('error', $result['message']);
        }
    }

    /**
     * Clear entire cart.
     */
    public function clearCart(): void
    {
        $cartService = app(ShoppingCartService::class);
        $result = $cartService->clearCart();

        if ($result['success']) {
            $this->refreshCart();
            $this->dispatch('cart-updated', 0);
            session()->flash('message', $result['message']);
        } else {
            session()->flash('error', $result['message']);
        }
    }
}; ?>

<div>
    <!-- Cart Header -->
    <div class="bg-white shadow-sm sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">
                    {{ __('Shopping Cart') }}
                    @if ($cartCount > 0)
                        <span class="ml-2 text-sm text-gray-500">({{ $cartCount }} {{ __('items') }})</span>
                    @endif
                </h3>
                @if ($cartCount > 0)
                    <button 
                        wire:click="clearCart"
                        wire:confirm="Are you sure you want to clear your entire cart?"
                        class="text-sm text-red-600 hover:text-red-500"
                    >
                        {{ __('Clear Cart') }}
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if (session()->has('message'))
        <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-md">
            <p class="text-sm text-green-800">{{ session('message') }}</p>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-md">
            <p class="text-sm text-red-800">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Cart Items -->
    @if ($cartCount > 0)
        <div class="mt-6 bg-white shadow-sm sm:rounded-lg">
            <div class="divide-y divide-gray-200">
                @foreach ($cartItems as $item)
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center">
                            <!-- Item Image -->
                            <div class="flex-shrink-0 h-20 w-20">
                                @if ($item['image'])
                                    <img class="h-20 w-20 rounded-lg object-cover" src="{{ $item['image'] }}" alt="{{ $item['name'] }}">
                                @else
                                    <div class="h-20 w-20 rounded-lg bg-gray-200 flex items-center justify-center">
                                        <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Item Details -->
                            <div class="ml-6 flex-1">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('items.show', $item['slug']) }}" class="hover:text-indigo-600">
                                                {{ $item['name'] }}
                                            </a>
                                        </h4>
                                        <p class="mt-1 text-sm text-gray-500">{{ $item['formatted_price'] }}</p>
                                    </div>
                                    <div class="ml-4 flex items-center space-x-4">
                                        <!-- Quantity Selector -->
                                        <div class="flex items-center space-x-2">
                                            <button 
                                                wire:click="updateQuantity({{ $item['item_id'] }}, {{ $item['quantity'] - 1 }})"
                                                class="p-1 text-gray-400 hover:text-gray-600"
                                                {{ $item['quantity'] <= 1 ? 'disabled' : '' }}
                                            >
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                </svg>
                                            </button>
                                            <span class="w-8 text-center text-sm font-medium">{{ $item['quantity'] }}</span>
                                            <button 
                                                wire:click="updateQuantity({{ $item['item_id'] }}, {{ $item['quantity'] + 1 }})"
                                                class="p-1 text-gray-400 hover:text-gray-600"
                                            >
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Subtotal -->
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $item['formatted_subtotal'] }}
                                        </div>

                                        <!-- Remove Button -->
                                        <button 
                                            wire:click="removeItem({{ $item['item_id'] }})"
                                            class="text-red-400 hover:text-red-500"
                                        >
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Cart Summary -->
            <div class="border-t border-gray-200 px-4 py-6 sm:px-6">
                <div class="flex justify-between text-base font-medium text-gray-900">
                    <p>{{ __('Total') }}</p>
                    <p>${{ number_format($cartTotal, 2) }}</p>
                </div>
                <div class="mt-6">
                    <button class="w-full flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700">
                        {{ __('Checkout') }}
                    </button>
                </div>
                <div class="mt-6 flex justify-center text-sm text-gray-500">
                    <p>
                        {{ __('or') }}
                        <a href="{{ route('home') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                            {{ __('Continue Shopping') }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart -->
        <div class="mt-6 bg-white shadow-sm sm:rounded-lg">
            <div class="px-4 py-12 sm:px-6 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('Your cart is empty') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('Add some items to get started!') }}</p>
                <div class="mt-6">
                    <a href="{{ route('home') }}" class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                        {{ __('Continue Shopping') }}
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
