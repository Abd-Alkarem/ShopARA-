<?php

namespace App\Services;

use App\Models\Item;
use App\Models\ShoppingCart;
use Illuminate\Support\Facades\Auth;

class ShoppingCartService
{
    /**
     * Add an item to the shopping cart.
     */
    public function addItem(int $itemId, int $quantity = 1): array
    {
        $user = Auth::user();
        $item = Item::findOrFail($itemId);

        if (!$item->is_active || $item->stock_quantity < $quantity) {
            return [
                'success' => false,
                'message' => 'Item is not available or insufficient stock.',
            ];
        }

        $cartItem = ShoppingCart::where('user_id', $user->id)
            ->where('item_id', $itemId)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($item->stock_quantity < $newQuantity) {
                return [
                    'success' => false,
                    'message' => 'Insufficient stock for this quantity.',
                ];
            }
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            ShoppingCart::create([
                'user_id' => $user->id,
                'item_id' => $itemId,
                'quantity' => $quantity,
                'price' => $item->current_price,
            ]);
        }

        return [
            'success' => true,
            'message' => 'Item added to cart successfully!',
            'cart_count' => $user->cart_items_count,
        ];
    }

    /**
     * Remove an item from the shopping cart.
     */
    public function removeItem(int $itemId): array
    {
        $user = Auth::user();
        
        $cartItem = ShoppingCart::where('user_id', $user->id)
            ->where('item_id', $itemId)
            ->first();

        if (!$cartItem) {
            return [
                'success' => false,
                'message' => 'Item not found in cart.',
            ];
        }

        $cartItem->delete();

        return [
            'success' => true,
            'message' => 'Item removed from cart successfully!',
            'cart_count' => $user->cart_items_count,
        ];
    }

    /**
     * Update item quantity in the shopping cart.
     */
    public function updateQuantity(int $itemId, int $quantity): array
    {
        $user = Auth::user();
        $item = Item::findOrFail($itemId);

        if ($quantity <= 0) {
            return $this->removeItem($itemId);
        }

        if ($item->stock_quantity < $quantity) {
            return [
                'success' => false,
                'message' => 'Insufficient stock for this quantity.',
            ];
        }

        $cartItem = ShoppingCart::where('user_id', $user->id)
            ->where('item_id', $itemId)
            ->first();

        if (!$cartItem) {
            return [
                'success' => false,
                'message' => 'Item not found in cart.',
            ];
        }

        $cartItem->quantity = $quantity;
        $cartItem->save();

        return [
            'success' => true,
            'message' => 'Cart updated successfully!',
            'cart_count' => $user->cart_items_count,
        ];
    }

    /**
     * Clear the entire shopping cart.
     */
    public function clearCart(): array
    {
        $user = Auth::user();
        
        ShoppingCart::where('user_id', $user->id)->delete();

        return [
            'success' => true,
            'message' => 'Cart cleared successfully!',
            'cart_count' => 0,
        ];
    }

    /**
     * Get all cart items for the current user.
     */
    public function getCartItems()
    {
        $user = Auth::user();
        
        return ShoppingCart::with('item')
            ->where('user_id', $user->id)
            ->get();
    }
}
