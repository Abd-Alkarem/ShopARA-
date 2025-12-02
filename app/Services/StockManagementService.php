<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

class StockManagementService
{
    /**
     * Decrease item stock by specified amount.
     */
    public function decreaseItemStock(int $itemId, int $quantity): void
    {
        // Load current stock adjustments
        $stockAdjustments = session()->get('stock_adjustments', []);
        
        // Get current stock for this item
        $currentStock = $stockAdjustments[$itemId] ?? $this->getOriginalStock($itemId);
        
        // Calculate new stock (don't go below 0)
        $newStock = max(0, $currentStock - $quantity);
        
        // Update stock adjustments
        $stockAdjustments[$itemId] = $newStock;
        session()->put('stock_adjustments', $stockAdjustments);
    }

    /**
     * Increase item stock by specified amount.
     */
    public function increaseItemStock(int $itemId, int $quantity): void
    {
        // Load current stock adjustments
        $stockAdjustments = session()->get('stock_adjustments', []);
        
        // Get current stock for this item
        $currentStock = $stockAdjustments[$itemId] ?? $this->getOriginalStock($itemId);
        
        // Calculate new stock
        $newStock = $currentStock + $quantity;
        
        // Update stock adjustments
        $stockAdjustments[$itemId] = $newStock;
        session()->put('stock_adjustments', $stockAdjustments);
    }

    /**
     * Get original stock for sample items.
     */
    private function getOriginalStock(int $itemId): int
    {
        $sampleItems = $this->getSampleItems();
        $sampleItem = collect($sampleItems)->firstWhere('id', $itemId);
        
        return $sampleItem ? $sampleItem['stock'] : 0;
    }

    /**
     * Get current stock for an item.
     */
    public function getItemStock(int $itemId): int
    {
        // Check stock adjustments first (for both sample and custom items)
        $stockAdjustments = session()->get('stock_adjustments', []);
        if (isset($stockAdjustments[$itemId])) {
            return $stockAdjustments[$itemId];
        }
        
        // Check custom items
        $customItems = session()->get('custom_items', []);
        $customItem = collect($customItems)->firstWhere('id', $itemId);
        
        if ($customItem) {
            return $customItem['stock'];
        }
        
        // For sample items, return original stock
        return $this->getOriginalStock($itemId);
    }

    /**
     * Get sample items data.
     */
    private function getSampleItems(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'iPhone 15 Pro',
                'description' => 'Latest Apple iPhone with A17 Pro chip',
                'category' => 'Phones',
                'price' => '$999.99',
                'stock' => 50,
                'status' => 'Active',
                'created_at' => '2024-01-15',
                'specifications' => [
                    'screen_size' => '6.1"',
                    'ram' => '8GB',
                    'storage' => '128GB',
                    'processor' => 'A17 Pro',
                    'camera' => '48MP Main',
                    'battery' => '3274mAh',
                ],
            ],
            [
                'id' => 2,
                'name' => 'Samsung Galaxy S24',
                'description' => 'Flagship Android phone with AI features',
                'category' => 'Phones',
                'price' => '$799.99',
                'stock' => 30,
                'status' => 'Active',
                'created_at' => '2024-01-20',
                'specifications' => [
                    'screen_size' => '6.2"',
                    'ram' => '8GB',
                    'storage' => '256GB',
                    'processor' => 'Snapdragon 8 Gen 3',
                    'camera' => '50MP Main',
                    'battery' => '4000mAh',
                ],
            ],
            [
                'id' => 3,
                'name' => 'MacBook Pro 14"',
                'description' => 'Professional laptop with M3 Pro chip',
                'category' => 'Laptops',
                'price' => '$1999.99',
                'stock' => 25,
                'status' => 'Active',
                'created_at' => '2024-01-10',
                'specifications' => [
                    'screen_size' => '14.2"',
                    'ram' => '18GB',
                    'storage' => '512GB SSD',
                    'processor' => 'Apple M3 Pro',
                    'graphics' => 'Apple Integrated GPU',
                    'battery' => '16 hours',
                ],
            ],
            [
                'id' => 4,
                'name' => 'Dell XPS 15',
                'description' => 'High-performance Windows laptop',
                'category' => 'Laptops',
                'price' => '$1799.99',
                'stock' => 20,
                'status' => 'Active',
                'created_at' => '2024-01-12',
                'specifications' => [
                    'screen_size' => '15.6"',
                    'ram' => '16GB',
                    'storage' => '1TB SSD',
                    'processor' => 'Intel Core i7',
                    'graphics' => 'NVIDIA GeForce RTX 4050',
                    'battery' => '10 hours',
                ],
            ],
            [
                'id' => 5,
                'name' => 'Google Pixel 8',
                'description' => 'AI-powered Android phone',
                'category' => 'Phones',
                'price' => '$699.99',
                'stock' => 35,
                'status' => 'Active',
                'created_at' => '2024-01-18',
                'specifications' => [
                    'screen_size' => '6.2"',
                    'ram' => '8GB',
                    'storage' => '128GB',
                    'processor' => 'Google Tensor G3',
                    'camera' => '50MP Main',
                    'battery' => '4524mAh',
                ],
            ],
            [
                'id' => 6,
                'name' => 'HP Spectre x360',
                'description' => 'Convertible 2-in-1 laptop',
                'category' => 'Laptops',
                'price' => '$1499.99',
                'stock' => 15,
                'status' => 'Active',
                'created_at' => '2024-01-22',
                'specifications' => [
                    'screen_size' => '13.5"',
                    'ram' => '16GB',
                    'storage' => '512GB SSD',
                    'processor' => 'Intel Core i7',
                    'graphics' => 'Intel Iris Xe',
                    'battery' => '12 hours',
                ],
            ],
        ];
    }
}
