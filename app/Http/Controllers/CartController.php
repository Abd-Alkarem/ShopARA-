<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = auth()->user()->cartItems()->with('product')->get();
        return view('cart.index', compact('cartItems'));
    }

    public function add(Request $request)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to add items to cart.'
            ], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        $product = Product::findOrFail($request->product_id);

        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Product is not available.'
            ], 400);
        }

        if ($product->stock <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Product is out of stock.'
            ], 400);
        }

        try {
            $cartItem = Cart::where('user_id', auth()->id())
                        ->where('product_id', $request->product_id)
                        ->first();

            if ($cartItem) {
                // Update existing cart item
                $currentCartQuantity = $cartItem->quantity;
                $requestedQuantity = $request->quantity;
                $newTotalQuantity = $currentCartQuantity + $requestedQuantity;
                
                // Check if the new total would exceed what's physically available
                if ($requestedQuantity > $product->stock) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Requested quantity exceeds available stock.'
                    ], 400);
                }
                
                // Decrease stock by the requested quantity
                $product->decrement('stock', $requestedQuantity);
                
                $cartItem->update(['quantity' => $newTotalQuantity]);
            } else {
                // Create new cart item and decrease stock
                if ($request->quantity > $product->stock) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Requested quantity exceeds available stock.'
                    ], 400);
                }
                
                $product->decrement('stock', $request->quantity);
                
                $cartItem = Cart::create([
                    'user_id' => auth()->id(),
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity
                ]);
            }

            $cartCount = auth()->user()->cartCount();

            $refreshedProduct = $product->fresh();
            $newStock = $refreshedProduct->stock;
            
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully!',
                'cart_count' => $cartCount,
                'remaining_stock' => $newStock,
                'stock_status' => $newStock > 0 ? 'in_stock' : 'out_of_stock'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding to cart: ' . $e->getMessage(),
                'debug' => [
                    'user_id' => auth()->id(),
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity
                ]
            ], 500);
        }
    }

    public function update(Request $request, Cart $cart)
    {
        if ($cart->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        $product = $cart->product;
        $currentStock = $product->stock;
        $oldQuantity = $cart->quantity;
        $newQuantity = $request->quantity;

        // Calculate stock difference
        $stockDifference = $oldQuantity - $newQuantity;

        if ($stockDifference > 0) {
            // Decreasing cart quantity (old > new), restore stock to inventory
            $product->increment('stock', abs($stockDifference));
        } elseif ($stockDifference < 0) {
            // Increasing cart quantity (new > old), check if enough stock available
            if ($newQuantity > $currentStock + $oldQuantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Requested quantity exceeds available stock.'
                ], 400);
            }
            // Remove stock from inventory
            $product->decrement('stock', abs($stockDifference));
        }

        $cart->update(['quantity' => $newQuantity]);

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully!',
            'subtotal' => $cart->formatted_subtotal,
            'cart_total' => auth()->user()->formatted_cart_total,
            'remaining_stock' => $product->fresh()->stock,
            'product_id' => $product->id
        ]);
    }

    public function remove(Cart $cart)
    {
        if ($cart->user_id !== auth()->id()) {
            abort(403);
        }

        // Restore stock when item is removed from cart
        $cart->product->increment('stock', $cart->quantity);

        $cart->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart!',
            'cart_count' => auth()->user()->cartCount(),
            'remaining_stock' => $cart->product->fresh()->stock,
            'product_id' => $cart->product->id
        ]);
    }

    public function clear()
    {
        try {
            // Check if user is authenticated
            if (!auth()->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $user = auth()->user();
            
            // Get cart items with products in a single query for better performance
            $cartItems = $user->cartItems()->with('product')->get();
            
            if ($cartItems->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cart is already empty!'
                ]);
            }
            
            \Log::info('Clearing cart for user ' . $user->id . ' with ' . $cartItems->count() . ' items');

            // Batch restore stock for better performance
            $stockUpdates = [];
            foreach ($cartItems as $cartItem) {
                if ($cartItem->product) {
                    $productId = $cartItem->product->id;
                    $quantity = $cartItem->quantity;
                    
                    if (!isset($stockUpdates[$productId])) {
                        $stockUpdates[$productId] = 0;
                    }
                    $stockUpdates[$productId] += $quantity;
                }
            }
            
            // Apply stock updates in batch
            foreach ($stockUpdates as $productId => $quantity) {
                \DB::table('products')
                    ->where('id', $productId)
                    ->increment('stock', $quantity);
                \Log::info('Restored ' . $quantity . ' stock for product ' . $productId);
            }

            // Delete all cart items for the user in a single query
            $deleted = $user->cartItems()->delete();
            \Log::info('Deleted ' . $deleted . ' cart items for user ' . $user->id);

            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully!',
                'debug' => [
                    'user_id' => $user->id,
                    'items_cleared' => $cartItems->count(),
                    'items_deleted' => $deleted,
                    'stock_updates' => count($stockUpdates)
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error clearing cart: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error clearing cart: ' . $e->getMessage(),
                'debug' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ], 500);
        }
    }
}
