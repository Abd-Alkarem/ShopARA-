<?php

use Livewire\Volt\Component;

new class extends Component
{
    public array $cart = [];
    public float $total = 0;

    public function mount(): void
    {
        $this->loadCart();
    }

    /**
     * Load cart from session.
     */
    public function loadCart(): void
    {
        $this->cart = session()->get('cart', []);
        $this->calculateTotal();
    }

    /**
     * Calculate cart total.
     */
    public function calculateTotal(): void
    {
        $this->total = 0;
        foreach ($this->cart as $item) {
            $this->total += floatval(str_replace('$', '', $item['price'])) * $item['quantity'];
        }
    }

    /**
     * Update item quantity.
     */
    public function updateQuantity(int $itemId, int $quantity): void
    {
        if ($quantity <= 0) {
            $this->removeItem($itemId);
            return;
        }

        $cart = session()->get('cart', []);
        
        if (isset($cart[$itemId])) {
            $cart[$itemId]['quantity'] = $quantity;
            session()->put('cart', $cart);
            $this->loadCart();
        }
    }

    /**
     * Remove item from cart.
     */
    public function removeItem(int $itemId): void
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$itemId])) {
            unset($cart[$itemId]);
            session()->put('cart', $cart);
            $this->loadCart();
            session()->flash('message', 'Item removed from cart!');
        }
    }

    /**
     * Clear entire cart.
     */
    public function clearCart(): void
    {
        session()->forget('cart');
        $this->loadCart();
        session()->flash('message', 'Cart cleared!');
    }

    /**
     * Get item subtotal.
     */
    public function getSubtotal(int $itemId): string
    {
        if (!isset($this->cart[$itemId])) {
            return '$0.00';
        }

        $item = $this->cart[$itemId];
        $subtotal = floatval(str_replace('$', '', $item['price'])) * $item['quantity'];
        return '$' . number_format($subtotal, 2);
    }
}; ?>

<div>
    <!-- Cart Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900">{{ __('Shopping Cart') }}</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">
                        {{ count($this->cart) }} {{ __('items') }}
                    </span>
                    @if (count($this->cart) > 0)
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
    </div>

    <!-- Success/Error Messages -->
    @if (session()->has('message'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="p-4 bg-green-50 border border-green-200 rounded-md">
                <p class="text-sm text-green-800">{{ session('message') }}</p>
            </div>
        </div>
    @endif

    <!-- Cart Items -->
    @if (count($this->cart) > 0)
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
                        @foreach ($this->cart as $itemId => $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $item['name'] }}</div>
                                        <div class="text-sm text-gray-500">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item['category'] === 'Phones' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $item['category'] }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item['price'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <button 
                                            wire:click="updateQuantity({{ $itemId }}, {{ $item['quantity'] - 1 }})"
                                            class="p-1 text-gray-400 hover:text-gray-600"
                                            {{ $item['quantity'] <= 1 ? 'disabled' : '' }}
                                        >
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                            </svg>
                                        </button>
                                        <span class="w-8 text-center text-sm font-medium">{{ $item['quantity'] }}</span>
                                        <button 
                                            wire:click="updateQuantity({{ $itemId }}, {{ $item['quantity'] + 1 }})"
                                            class="p-1 text-gray-400 hover:text-gray-600"
                                        >
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $this->getSubtotal($itemId) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button 
                                        wire:click="removeItem({{ $itemId }})"
                                        class="text-red-600 hover:text-red-900"
                                    >
                                        {{ __('Remove') }}
                                    </button>
                                </td>
                            </tr>
                        @endforeach
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
                        <p>${{ number_format($this->total, 2) }}</p>
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
    @else
        <!-- Empty Cart -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('Your cart is empty') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('Add some items to get started!') }}</p>
                <div class="mt-6">
                    <a href="{{ route('items') }}" class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                        {{ __('Continue Shopping') }}
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
