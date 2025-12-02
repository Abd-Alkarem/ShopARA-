<?php

use Livewire\Volt\Component;
use App\Services\StockManagementService;

new class extends Component
{
    public array $items = [];
    public string $search = '';
    public string $sortBy = 'name';
    public string $selectedCategory = 'all';
    public array $cart = [];
    public int $cartCount = 0;
    
    // Modal state
    public bool $showAddItemModal = false;
    public bool $showEditItemModal = false;
    public string $newItemName = '';
    public string $newItemDescription = '';
    public string $newItemCategory = 'Phones';
    public string $newItemPrice = '';
    public int $newItemStock = 0;
    
    // Specification fields
    public string $newItemScreenSize = '';
    public string $newItemRam = '';
    public string $newItemStorage = '';
    public string $newItemProcessor = '';
    public string $newItemGraphics = '';
    public string $newItemCamera = '';
    public string $newItemBattery = '';
    
    // Edit state
    public int $editingItemId = 0;
    public string $editItemName = '';
    public string $editItemDescription = '';
    public string $editItemCategory = 'Phones';
    public string $editItemPrice = '';
    public int $editItemStock = 0;
    public string $editItemScreenSize = '';
    public string $editItemRam = '';
    public string $editItemStorage = '';
    public string $editItemProcessor = '';
    public string $editItemGraphics = '';
    public string $editItemCamera = '';
    public string $editItemBattery = '';

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
        // Load custom items from session first
        $customItems = session()->get('custom_items', []);
        
        // Load stock adjustments from session
        $stockAdjustments = session()->get('stock_adjustments', []);
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
            [
                'id' => 7,
                'name' => 'OnePlus 12',
                'description' => 'Flagship killer with fast charging',
                'category' => 'Phones',
                'price' => '$799.99',
                'stock' => 40,
                'status' => 'Active',
                'created_at' => '2024-01-25',
                'specifications' => [
                    'screen_size' => '6.8"',
                    'ram' => '16GB',
                    'storage' => '256GB',
                    'processor' => 'Snapdragon 8 Gen 3',
                    'camera' => '50MP Main + 48MP Ultrawide',
                    'battery' => '5400mAh',
                ],
            ],
            [
                'id' => 8,
                'name' => 'Lenovo ThinkPad X1 Carbon',
                'description' => 'Business ultrabook with durability',
                'category' => 'Laptops',
                'price' => '$1899.99',
                'stock' => 18,
                'status' => 'Active',
                'created_at' => '2024-01-28',
                'specifications' => [
                    'screen_size' => '14"',
                    'ram' => '16GB',
                    'storage' => '1TB SSD',
                    'processor' => 'Intel Core i7',
                    'graphics' => 'Intel Iris Xe',
                    'battery' => '15 hours',
                ],
            ],
            [
                'id' => 9,
                'name' => 'Xiaomi 14 Pro',
                'description' => 'Premium phone with Leica camera',
                'category' => 'Phones',
                'price' => '$699.99',
                'stock' => 32,
                'status' => 'Active',
                'created_at' => '2024-02-01',
                'specifications' => [
                    'screen_size' => '6.7"',
                    'ram' => '12GB',
                    'storage' => '256GB',
                    'processor' => 'Snapdragon 8 Gen 3',
                    'camera' => '50MP Leica Main',
                    'battery' => '4880mAh',
                ],
            ],
            [
                'id' => 10,
                'name' => 'ASUS ROG Zephyrus G16',
                'description' => 'Gaming laptop with RTX graphics',
                'category' => 'Laptops',
                'price' => '$2199.99',
                'stock' => 12,
                'status' => 'Active',
                'created_at' => '2024-02-05',
                'specifications' => [
                    'screen_size' => '16"',
                    'ram' => '32GB',
                    'storage' => '2TB SSD',
                    'processor' => 'Intel Core i9',
                    'graphics' => 'NVIDIA GeForce RTX 4070',
                    'battery' => '8 hours',
                ],
            ],
            [
                'id' => 11,
                'name' => 'Nothing Phone (2)',
                'description' => 'Transparent design with unique interface',
                'category' => 'Phones',
                'price' => '$599.99',
                'stock' => 28,
                'status' => 'Active',
                'created_at' => '2024-02-08',
                'specifications' => [
                    'screen_size' => '6.7"',
                    'ram' => '12GB',
                    'storage' => '256GB',
                    'processor' => 'Snapdragon 8 Gen 2',
                    'camera' => '50MP Main',
                    'battery' => '4700mAh',
                ],
            ],
            [
                'id' => 12,
                'name' => 'Microsoft Surface Laptop 5',
                'description' => 'Premium Windows laptop with touchscreen',
                'category' => 'Laptops',
                'price' => '$1599.99',
                'stock' => 22,
                'status' => 'Active',
                'created_at' => '2024-02-12',
                'specifications' => [
                    'screen_size' => '13.5"',
                    'ram' => '16GB',
                    'storage' => '512GB SSD',
                    'processor' => 'Intel Core i7',
                    'graphics' => 'Intel Iris Xe',
                    'battery' => '18 hours',
                ],
            ],
            [
                'id' => 13,
                'name' => 'Sony Xperia 1 V',
                'description' => 'Professional camera phone',
                'category' => 'Phones',
                'price' => '$1299.99',
                'stock' => 16,
                'status' => 'Active',
                'created_at' => '2024-02-15',
                'specifications' => [
                    'screen_size' => '6.5"',
                    'ram' => '12GB',
                    'storage' => '256GB',
                    'processor' => 'Snapdragon 8 Gen 2',
                    'camera' => '48MP Main + 12MP Ultrawide + 12MP Telephoto',
                    'battery' => '5000mAh',
                ],
            ],
            [
                'id' => 14,
                'name' => 'Razer Blade 15',
                'description' => 'Gaming laptop with RGB keyboard',
                'category' => 'Laptops',
                'price' => '$2499.99',
                'stock' => 8,
                'status' => 'Active',
                'created_at' => '2024-02-18',
                'specifications' => [
                    'screen_size' => '15.6"',
                    'ram' => '32GB',
                    'storage' => '1TB SSD',
                    'processor' => 'Intel Core i9',
                    'graphics' => 'NVIDIA GeForce RTX 4070',
                    'battery' => '6 hours',
                ],
            ],
            [
                'id' => 15,
                'name' => 'Motorola Edge+ (2023)',
                'description' => 'Premium phone with massive battery',
                'category' => 'Phones',
                'price' => '$799.99',
                'stock' => 24,
                'status' => 'Active',
                'created_at' => '2024-02-22',
                'specifications' => [
                    'screen_size' => '6.7"',
                    'ram' => '8GB',
                    'storage' => '512GB',
                    'processor' => 'Snapdragon 8 Gen 2',
                    'camera' => '50MP Main',
                    'battery' => '5100mAh',
                ],
            ],
            [
                'id' => 16,
                'name' => 'Apple MacBook Air M2',
                'description' => 'Thin and light laptop with M2 chip',
                'category' => 'Laptops',
                'price' => '$1299.99',
                'stock' => 30,
                'status' => 'Active',
                'created_at' => '2024-02-25',
                'specifications' => [
                    'screen_size' => '13.6"',
                    'ram' => '8GB',
                    'storage' => '256GB SSD',
                    'processor' => 'Apple M2',
                    'graphics' => 'Apple Integrated GPU',
                    'battery' => '18 hours',
                ],
            ],
            [
                'id' => 17,
                'name' => 'Google Pixel Fold',
                'description' => 'Innovative foldable phone',
                'category' => 'Phones',
                'price' => '$1799.99',
                'stock' => 10,
                'status' => 'Active',
                'created_at' => '2024-02-28',
                'specifications' => [
                    'screen_size' => '7.6"',
                    'ram' => '12GB',
                    'storage' => '256GB',
                    'processor' => 'Google Tensor G2',
                    'camera' => '48MP Main',
                    'battery' => '4821mAh',
                ],
            ],
            [
                'id' => 18,
                'name' => 'Alienware x16',
                'description' => 'High-end gaming laptop',
                'category' => 'Laptops',
                'price' => '$2799.99',
                'stock' => 6,
                'status' => 'Active',
                'created_at' => '2024-03-01',
                'specifications' => [
                    'screen_size' => '16"',
                    'ram' => '32GB',
                    'storage' => '2TB SSD',
                    'processor' => 'Intel Core i9',
                    'graphics' => 'NVIDIA GeForce RTX 4080',
                    'battery' => '4 hours',
                ],
            ],
            [
                'id' => 19,
                'name' => 'Samsung Galaxy Z Fold 5',
                'description' => 'Premium foldable phone',
                'category' => 'Phones',
                'price' => '$1799.99',
                'stock' => 14,
                'status' => 'Active',
                'created_at' => '2024-03-05',
                'specifications' => [
                    'screen_size' => '7.6"',
                    'ram' => '12GB',
                    'storage' => '512GB',
                    'processor' => 'Snapdragon 8 Gen 2 for Galaxy',
                    'camera' => '50MP Main',
                    'battery' => '4400mAh',
                ],
            ],
            [
                'id' => 20,
                'name' => 'LG Gram 17',
                'description' => 'Lightweight large-screen laptop',
                'category' => 'Laptops',
                'price' => '$1699.99',
                'stock' => 11,
                'status' => 'Active',
                'created_at' => '2024-03-08',
                'specifications' => [
                    'screen_size' => '17"',
                    'ram' => '16GB',
                    'storage' => '1TB SSD',
                    'processor' => 'Intel Core i7',
                    'graphics' => 'Intel Iris Xe',
                    'battery' => '20 hours',
                ],
            ],
            [
                'id' => 21,
                'name' => 'Oppo Find X6 Pro',
                'description' => 'Camera-focused flagship phone',
                'category' => 'Phones',
                'price' => '$999.99',
                'stock' => 20,
                'status' => 'Active',
                'created_at' => '2024-03-12',
                'specifications' => [
                    'screen_size' => '6.8"',
                    'ram' => '16GB',
                    'storage' => '256GB',
                    'processor' => 'Snapdragon 8 Gen 2',
                    'camera' => '50MP Main + 50MP Ultrawide + 50MP Telephoto',
                    'battery' => '5000mAh',
                ],
            ],
            [
                'id' => 22,
                'name' => 'Dell XPS 13 Plus',
                'description' => 'Compact premium ultrabook',
                'category' => 'Laptops',
                'price' => '$1499.99',
                'stock' => 17,
                'status' => 'Active',
                'created_at' => '2024-03-15',
                'specifications' => [
                    'screen_size' => '13.4"',
                    'ram' => '16GB',
                    'storage' => '512GB SSD',
                    'processor' => 'Intel Core i7',
                    'graphics' => 'Intel Iris Xe',
                    'battery' => '14 hours',
                ],
            ],
        ];

        // Apply stock adjustments to sample items
        foreach ($sampleItems as &$item) {
            if (isset($stockAdjustments[$item['id']])) {
                // Use the adjusted stock from session
                $item['stock'] = $stockAdjustments[$item['id']];
            }
        }

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

        // Merge custom items with sample items
        $allItems = array_merge($customItems, $sampleItems);

        $this->items = array_values($allItems);
    }

    /**
     * Load cart from session.
     */
    public function loadCart(): void
    {
        if (auth()->check()) {
            // Use user-specific cart key
            $cartKey = 'cart_user_' . auth()->id();
            $this->cart = session()->get($cartKey, []);
            
            // Check if there's a preserved cart from previous logout
            $preservedCart = session()->get('temp_cart_preserve', []);
            $preservedUserId = session()->get('temp_user_id', 0);
            
            if (!empty($preservedCart) && $preservedUserId == auth()->id()) {
                // Restore the preserved cart
                $this->cart = $preservedCart;
                session()->put($cartKey, $preservedCart);
                
                // Clear the temporary preserved cart
                session()->forget('temp_cart_preserve');
                session()->forget('temp_user_id');
            }
        } else {
            // Fallback to guest cart
            $this->cart = session()->get('cart_guest', []);
        }
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

        // Check if item is in stock
        if ($item['stock'] <= 0) {
            session()->flash('error', 'Item is out of stock.');
            return;
        }

        if (auth()->check()) {
            // Use user-specific cart key
            $cartKey = 'cart_user_' . auth()->id();
            $cart = session()->get($cartKey, []);
            
            if (isset($cart[$itemId])) {
                $cart[$itemId]['quantity']++;
            } else {
                $cart[$itemId] = [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'category' => $item['category'],
                    'quantity' => 1,
                    'specifications' => $item['specifications'] ?? [],
                ];
            }

            session()->put($cartKey, $cart);
        } else {
            // Fallback to guest cart
            $cart = session()->get('cart_guest', []);
            
            if (isset($cart[$itemId])) {
                $cart[$itemId]['quantity']++;
            } else {
                $cart[$itemId] = [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'category' => $item['category'],
                    'quantity' => 1,
                    'specifications' => $item['specifications'] ?? [],
                ];
            }

            session()->put('cart_guest', $cart);
        }

        // Decrease stock in the item
        $this->decreaseItemStock($itemId, 1);

        $this->loadCart();
        
        session()->flash('message', $item['name'] . ' added to cart!');
    }

    /**
     * Decrease item stock by specified amount.
     */
    public function decreaseItemStock(int $itemId, int $quantity): void
    {
        $stockService = new StockManagementService();
        $stockService->decreaseItemStock($itemId, $quantity);
        
        // Update the items array to reflect the stock change from session
        $itemIndex = collect($this->items)->search(fn($item) => $item['id'] === $itemId);
        if ($itemIndex !== false) {
            // Get the updated stock from the service
            $this->items[$itemIndex]['stock'] = $stockService->getItemStock($itemId);
        }
    }

    /**
     * Increase item stock by specified amount.
     */
    public function increaseItemStock(int $itemId, int $quantity): void
    {
        $stockService = new StockManagementService();
        $stockService->increaseItemStock($itemId, $quantity);
        
        // Update the items array to reflect the stock change from session
        $itemIndex = collect($this->items)->search(fn($item) => $item['id'] === $itemId);
        if ($itemIndex !== false) {
            // Get the updated stock from the service
            $this->items[$itemIndex]['stock'] = $stockService->getItemStock($itemId);
        }
    }

    /**
     * Open edit item modal.
     */
    public function openEditItemModal(int $itemId): void
    {
        $item = collect($this->items)->firstWhere('id', $itemId);
        
        if (!$item) {
            session()->flash('error', 'Item not found.');
            return;
        }

        $this->editingItemId = $itemId;
        $this->editItemName = $item['name'];
        $this->editItemDescription = $item['description'];
        $this->editItemCategory = $item['category'];
        $this->editItemPrice = str_replace('$', '', $item['price']);
        $this->editItemStock = $item['stock'];
        
        // Load specifications
        if (isset($item['specifications'])) {
            $this->editItemScreenSize = $item['specifications']['screen_size'] ?? '';
            $this->editItemRam = $item['specifications']['ram'] ?? '';
            $this->editItemStorage = $item['specifications']['storage'] ?? '';
            $this->editItemProcessor = $item['specifications']['processor'] ?? '';
            $this->editItemGraphics = $item['specifications']['graphics'] ?? '';
            $this->editItemCamera = $item['specifications']['camera'] ?? '';
            $this->editItemBattery = $item['specifications']['battery'] ?? '';
        }
        
        $this->showEditItemModal = true;
    }

    /**
     * Close edit item modal.
     */
    public function closeEditItemModal(): void
    {
        $this->showEditItemModal = false;
        $this->reset([
            'editingItemId', 'editItemName', 'editItemDescription', 'editItemCategory', 'editItemPrice', 'editItemStock',
            'editItemScreenSize', 'editItemRam', 'editItemStorage', 'editItemProcessor', 
            'editItemGraphics', 'editItemCamera', 'editItemBattery'
        ]);
    }

    /**
     * Update existing item.
     */
    public function updateItem(): void
    {
        // Base validation for all categories
        $validationRules = [
            'editItemName' => 'required|string|min:2|max:255',
            'editItemDescription' => 'required|string|min:5|max:500',
            'editItemCategory' => 'required|in:Phones,Laptops',
            'editItemPrice' => 'required|regex:/^\$?\d+(\.\d{1,2})?$/|min:0.01',
            'editItemStock' => 'required|integer|min:0|max:999999',
            'editItemScreenSize' => 'required|string|max:20',
            'editItemRam' => 'required|string|max:20',
            'editItemStorage' => 'required|string|max:20',
            'editItemProcessor' => 'required|string|max:100',
        ];

        // Category-specific validation
        if ($this->editItemCategory === 'Phones') {
            $validationRules['editItemCamera'] = 'nullable|string|max:50';
            $validationRules['editItemBattery'] = 'nullable|string|max:20';
        } else {
            $validationRules['editItemGraphics'] = 'nullable|string|max:50';
            $validationRules['editItemBattery'] = 'nullable|string|max:20';
        }

        $this->validate($validationRules, [
            'editItemName.required' => 'Item name is required.',
            'editItemName.min' => 'Item name must be at least 2 characters.',
            'editItemDescription.required' => 'Description is required.',
            'editItemDescription.min' => 'Description must be at least 5 characters.',
            'editItemPrice.required' => 'Price is required.',
            'editItemPrice.regex' => 'Please enter a valid price (e.g., 99.99 or $99.99).',
            'editItemStock.required' => 'Stock quantity is required.',
            'editItemStock.min' => 'Stock cannot be negative.',
            'editItemScreenSize.required' => 'Screen size is required.',
            'editItemRam.required' => 'RAM is required.',
            'editItemStorage.required' => 'Storage is required.',
            'editItemProcessor.required' => 'Processor is required.',
        ]);

        // Format price
        $price = $this->editItemPrice;
        if (!str_starts_with($price, '$')) {
            $price = '$' . number_format(floatval($price), 2);
        }

        // Build specifications array based on category
        $specifications = [
            'screen_size' => $this->editItemScreenSize,
            'ram' => $this->editItemRam,
            'storage' => $this->editItemStorage,
            'processor' => $this->editItemProcessor,
        ];

        // Add category-specific specifications
        if ($this->editItemCategory === 'Phones') {
            if (!empty($this->editItemCamera)) {
                $specifications['camera'] = $this->editItemCamera;
            }
            if (!empty($this->editItemBattery)) {
                $specifications['battery'] = $this->editItemBattery;
            }
        } else {
            if (!empty($this->editItemGraphics)) {
                $specifications['graphics'] = $this->editItemGraphics;
            }
            if (!empty($this->editItemBattery)) {
                $specifications['battery'] = $this->editItemBattery;
            }
        }

        // Find and update the item
        $itemIndex = collect($this->items)->search(fn($item) => $item['id'] === $this->editingItemId);
        
        if ($itemIndex === false) {
            session()->flash('error', 'Item not found.');
            return;
        }

        // Update item
        $this->items[$itemIndex] = [
            'id' => $this->editingItemId,
            'name' => $this->editItemName,
            'description' => $this->editItemDescription,
            'category' => $this->editItemCategory,
            'price' => $price,
            'stock' => $this->editItemStock,
            'status' => 'Active',
            'created_at' => $this->items[$itemIndex]['created_at'],
            'specifications' => $specifications,
        ];

        // Save updated item to session if it's a custom item
        $customItems = session()->get('custom_items', []);
        $customItemIndex = collect($customItems)->search(fn($item) => $item['id'] === $this->editingItemId);
        
        if ($customItemIndex !== false) {
            $customItems[$customItemIndex] = $this->items[$itemIndex];
            session()->put('custom_items', $customItems);
        }

        // Close modal and show success message
        $this->closeEditItemModal();
        session()->flash('message', 'Item "' . $this->editItemName . '" has been updated successfully!');
    }
    public function openAddItemModal(): void
    {
        $this->reset([
            'newItemName', 'newItemDescription', 'newItemCategory', 'newItemPrice', 'newItemStock',
            'newItemScreenSize', 'newItemRam', 'newItemStorage', 'newItemProcessor', 
            'newItemGraphics', 'newItemCamera', 'newItemBattery'
        ]);
        $this->showAddItemModal = true;
    }

    /**
     * Close add item modal.
     */
    public function closeAddItemModal(): void
    {
        $this->showAddItemModal = false;
        $this->reset([
            'newItemName', 'newItemDescription', 'newItemCategory', 'newItemPrice', 'newItemStock',
            'newItemScreenSize', 'newItemRam', 'newItemStorage', 'newItemProcessor', 
            'newItemGraphics', 'newItemCamera', 'newItemBattery'
        ]);
    }

    /**
     * Add new item.
     */
    public function addNewItem(): void
    {
        // Base validation for all categories
        $validationRules = [
            'newItemName' => 'required|string|min:2|max:255',
            'newItemDescription' => 'required|string|min:5|max:500',
            'newItemCategory' => 'required|in:Phones,Laptops',
            'newItemPrice' => 'required|regex:/^\$?\d+(\.\d{1,2})?$/|min:0.01',
            'newItemStock' => 'required|integer|min:0|max:999999',
            'newItemScreenSize' => 'required|string|max:20',
            'newItemRam' => 'required|string|max:20',
            'newItemStorage' => 'required|string|max:20',
            'newItemProcessor' => 'required|string|max:100',
        ];

        // Category-specific validation
        if ($this->newItemCategory === 'Phones') {
            $validationRules['newItemCamera'] = 'nullable|string|max:50';
            $validationRules['newItemBattery'] = 'nullable|string|max:20';
        } else {
            $validationRules['newItemGraphics'] = 'nullable|string|max:50';
            $validationRules['newItemBattery'] = 'nullable|string|max:20';
        }

        $this->validate($validationRules, [
            'newItemName.required' => 'Item name is required.',
            'newItemName.min' => 'Item name must be at least 2 characters.',
            'newItemDescription.required' => 'Description is required.',
            'newItemDescription.min' => 'Description must be at least 5 characters.',
            'newItemPrice.required' => 'Price is required.',
            'newItemPrice.regex' => 'Please enter a valid price (e.g., 99.99 or $99.99).',
            'newItemStock.required' => 'Stock quantity is required.',
            'newItemStock.min' => 'Stock cannot be negative.',
            'newItemScreenSize.required' => 'Screen size is required.',
            'newItemRam.required' => 'RAM is required.',
            'newItemStorage.required' => 'Storage is required.',
            'newItemProcessor.required' => 'Processor is required.',
        ]);

        // Format price
        $price = $this->newItemPrice;
        if (!str_starts_with($price, '$')) {
            $price = '$' . number_format(floatval($price), 2);
        }

        // Generate new ID (start from 1000 to avoid conflicts with sample items)
        $customItems = session()->get('custom_items', []);
        $existingIds = array_merge(
            array_column($this->items, 'id'),
            array_column($customItems, 'id')
        );
        $newId = !empty($existingIds) ? max($existingIds) + 1 : 1000;

        // Build specifications array based on category
        $specifications = [
            'screen_size' => $this->newItemScreenSize,
            'ram' => $this->newItemRam,
            'storage' => $this->newItemStorage,
            'processor' => $this->newItemProcessor,
        ];

        // Add category-specific specifications
        if ($this->newItemCategory === 'Phones') {
            if (!empty($this->newItemCamera)) {
                $specifications['camera'] = $this->newItemCamera;
            }
            if (!empty($this->newItemBattery)) {
                $specifications['battery'] = $this->newItemBattery;
            }
        } else {
            if (!empty($this->newItemGraphics)) {
                $specifications['graphics'] = $this->newItemGraphics;
            }
            if (!empty($this->newItemBattery)) {
                $specifications['battery'] = $this->newItemBattery;
            }
        }

        // Create new item
        $newItem = [
            'id' => $newId,
            'name' => $this->newItemName,
            'description' => $this->newItemDescription,
            'category' => $this->newItemCategory,
            'price' => $price,
            'stock' => $this->newItemStock,
            'status' => 'Active',
            'created_at' => now()->format('Y-m-d'),
            'specifications' => $specifications,
        ];

        // Add to items array (in a real app, this would save to database)
        $this->items[] = $newItem;

        // Save to session for persistence
        $customItems = session()->get('custom_items', []);
        $customItems[] = $newItem;
        session()->put('custom_items', $customItems);

        // Close modal and show success message
        $this->closeAddItemModal();
        session()->flash('message', 'New item "' . $this->newItemName . '" has been added successfully!');
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
     * Delete item.
     */
    public function deleteItem(int $itemId): void
    {
        $item = collect($this->items)->firstWhere('id', $itemId);
        
        if (!$item) {
            session()->flash('error', 'Item not found.');
            return;
        }

        // Remove from session if it's a custom item
        $customItems = session()->get('custom_items', []);
        $customItemIndex = collect($customItems)->search(fn($item) => $item['id'] === $itemId);
        
        if ($customItemIndex !== false) {
            unset($customItems[$customItemIndex]);
            $customItems = array_values($customItems); // Re-index array
            session()->put('custom_items', $customItems);
            
            session()->flash('message', 'Item "' . $item['name'] . '" has been deleted successfully!');
        } else {
            // For sample items, just show a message that they can't be deleted
            session()->flash('error', 'Sample items cannot be deleted. Only custom items can be removed.');
            return;
        }

        // Reload items to update the display
        $this->loadItems();
    }

    /**
     * Get category counts.
     */
    public function getCategoryCountsProperty(): array
    {
        // Get all items without filters to calculate accurate counts
        $allItems = $this->getAllItems();
        
        return [
            'all' => count($allItems),
            'Phones' => count(array_filter($allItems, fn($item) => $item['category'] === 'Phones')),
            'Laptops' => count(array_filter($allItems, fn($item) => $item['category'] === 'Laptops')),
        ];
    }

    /**
     * Get all items without any filters.
     */
    private function getAllItems(): array
    {
        // Load custom items from session
        $customItems = session()->get('custom_items', []);
        
        // Sample items data (hardcoded)
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
                'specifications' => [
                    'screen_size' => '6.1"',
                    'ram' => '8GB',
                    'storage' => '256GB',
                    'processor' => 'A17 Pro',
                    'camera' => '48MP Main',
                    'battery' => '3274mAh'
                ]
            ],
            [
                'id' => 2,
                'name' => 'Samsung Galaxy S24',
                'description' => 'Flagship Android phone with amazing camera',
                'category' => 'Phones',
                'price' => '$899.99',
                'stock' => 45,
                'status' => 'Active',
                'created_at' => '2024-01-20',
                'specifications' => [
                    'screen_size' => '6.2"',
                    'ram' => '8GB',
                    'storage' => '256GB',
                    'processor' => 'Snapdragon 8 Gen 3',
                    'camera' => '50MP Main',
                    'battery' => '4000mAh'
                ]
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
                'specifications' => [
                    'screen_size' => '6.2"',
                    'ram' => '8GB',
                    'storage' => '128GB',
                    'processor' => 'Google Tensor G3',
                    'camera' => '50MP Main',
                    'battery' => '4575mAh'
                ]
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
                'specifications' => [
                    'screen_size' => '6.8"',
                    'ram' => '12GB',
                    'storage' => '256GB',
                    'processor' => 'Snapdragon 8 Gen 3',
                    'camera' => '50MP Main',
                    'battery' => '5400mAh'
                ]
            ],
            [
                'id' => 5,
                'name' => 'MacBook Pro 16"',
                'description' => 'High-performance laptop with M3 Max chip',
                'category' => 'Laptops',
                'price' => '$2499.99',
                'stock' => 30,
                'status' => 'Active',
                'created_at' => '2024-01-25',
                'specifications' => [
                    'screen_size' => '16.2"',
                    'ram' => '36GB',
                    'storage' => '1TB SSD',
                    'processor' => 'Apple M3 Max',
                    'graphics' => '40-core GPU',
                    'battery' => '22 hours'
                ]
            ],
            [
                'id' => 6,
                'name' => 'Dell XPS 15',
                'description' => 'Windows laptop with 4K OLED display',
                'category' => 'Laptops',
                'price' => '$1799.99',
                'stock' => 40,
                'status' => 'Active',
                'created_at' => '2024-02-05',
                'specifications' => [
                    'screen_size' => '15.6"',
                    'ram' => '16GB',
                    'storage' => '512GB SSD',
                    'processor' => 'Intel Core i7-13700H',
                    'graphics' => 'NVIDIA RTX 4050',
                    'battery' => '10 hours'
                ]
            ],
            [
                'id' => 7,
                'name' => 'ThinkPad X1 Carbon',
                'description' => 'Business laptop with military-grade durability',
                'category' => 'Laptops',
                'price' => '$1599.99',
                'stock' => 35,
                'status' => 'Active',
                'created_at' => '2024-02-15',
                'specifications' => [
                    'screen_size' => '14"',
                    'ram' => '16GB',
                    'storage' => '512GB SSD',
                    'processor' => 'Intel Core i7-1365U',
                    'graphics' => 'Intel Iris Xe',
                    'battery' => '15 hours'
                ]
            ],
            [
                'id' => 8,
                'name' => 'Surface Laptop 5',
                'description' => 'Microsoft premium ultrabook with touchscreen',
                'category' => 'Laptops',
                'price' => '$1299.99',
                'stock' => 55,
                'status' => 'Active',
                'created_at' => '2024-03-01',
                'specifications' => [
                    'screen_size' => '13.5"',
                    'ram' => '8GB',
                    'storage' => '256GB SSD',
                    'processor' => 'Intel Core i5-1235U',
                    'graphics' => 'Intel Iris Xe',
                    'battery' => '18 hours'
                ]
            ],
            [
                'id' => 9,
                'name' => 'Samsung Galaxy S24 Ultra',
                'description' => 'Premium Android phone with S Pen and 200MP camera',
                'category' => 'Phones',
                'price' => '$1199.99',
                'stock' => 25,
                'status' => 'Active',
                'created_at' => '2024-03-15',
                'specifications' => [
                    'screen_size' => '6.8"',
                    'ram' => '12GB',
                    'storage' => '256GB',
                    'processor' => 'Snapdragon 8 Gen 3',
                    'camera' => '200MP Main',
                    'battery' => '5000mAh'
                ]
            ],
            [
                'id' => 10,
                'name' => 'iPhone 15 Pro Max',
                'description' => 'Apple flagship with titanium design and A17 Pro chip',
                'category' => 'Phones',
                'price' => '$1199.99',
                'stock' => 30,
                'status' => 'Active',
                'created_at' => '2024-03-20',
                'specifications' => [
                    'screen_size' => '6.7"',
                    'ram' => '8GB',
                    'storage' => '256GB',
                    'processor' => 'Apple A17 Pro',
                    'camera' => '48MP Main',
                    'battery' => '4422mAh'
                ]
            ],
            [
                'id' => 11,
                'name' => 'Google Pixel 8 Pro',
                'description' => 'AI-powered smartphone with advanced photography',
                'category' => 'Phones',
                'price' => '$999.99',
                'stock' => 40,
                'status' => 'Active',
                'created_at' => '2024-03-25',
                'specifications' => [
                    'screen_size' => '6.7"',
                    'ram' => '12GB',
                    'storage' => '128GB',
                    'processor' => 'Google Tensor G3',
                    'camera' => '50MP Main',
                    'battery' => '5050mAh'
                ]
            ],
            [
                'id' => 12,
                'name' => 'Xiaomi 14 Pro',
                'description' => 'Flagship killer with Leica optics',
                'category' => 'Phones',
                'price' => '$899.99',
                'stock' => 35,
                'status' => 'Active',
                'created_at' => '2024-04-01',
                'specifications' => [
                    'screen_size' => '6.73"',
                    'ram' => '12GB',
                    'storage' => '256GB',
                    'processor' => 'Snapdragon 8 Gen 3',
                    'camera' => '50MP Leica',
                    'battery' => '4880mAh'
                ]
            ],
            [
                'id' => 13,
                'name' => 'ASUS ROG Zephyrus G16',
                'description' => 'Gaming laptop with RTX 4070 and OLED display',
                'category' => 'Laptops',
                'price' => '$1899.99',
                'stock' => 20,
                'status' => 'Active',
                'created_at' => '2024-04-05',
                'specifications' => [
                    'screen_size' => '16"',
                    'ram' => '16GB',
                    'storage' => '1TB SSD',
                    'processor' => 'Intel Core i9-13900H',
                    'graphics' => 'NVIDIA RTX 4070',
                    'battery' => '8 hours'
                ]
            ],
            [
                'id' => 14,
                'name' => 'HP Spectre x360 14',
                'description' => '2-in-1 convertible laptop with OLED touchscreen',
                'category' => 'Laptops',
                'price' => '$1449.99',
                'stock' => 28,
                'status' => 'Active',
                'created_at' => '2024-04-10',
                'specifications' => [
                    'screen_size' => '14"',
                    'ram' => '16GB',
                    'storage' => '512GB SSD',
                    'processor' => 'Intel Core i7-1355U',
                    'graphics' => 'Intel Iris Xe',
                    'battery' => '12 hours'
                ]
            ],
            [
                'id' => 15,
                'name' => 'Razer Blade 15',
                'description' => 'Premium gaming laptop with CNC aluminum chassis',
                'category' => 'Laptops',
                'price' => '$2099.99',
                'stock' => 18,
                'status' => 'Active',
                'created_at' => '2024-04-15',
                'specifications' => [
                    'screen_size' => '15.6"',
                    'ram' => '32GB',
                    'storage' => '1TB SSD',
                    'processor' => 'Intel Core i7-13800H',
                    'graphics' => 'NVIDIA RTX 4060',
                    'battery' => '6 hours'
                ]
            ],
            [
                'id' => 16,
                'name' => 'Lenovo Yoga 9i',
                'description' => 'Premium 2-in-1 with soundbar hinge',
                'category' => 'Laptops',
                'price' => '$1399.99',
                'stock' => 32,
                'status' => 'Active',
                'created_at' => '2024-04-20',
                'specifications' => [
                    'screen_size' => '14"',
                    'ram' => '16GB',
                    'storage' => '512GB SSD',
                    'processor' => 'Intel Core i7-1360P',
                    'graphics' => 'Intel Iris Xe',
                    'battery' => '14 hours'
                ]
            ],
            [
                'id' => 17,
                'name' => 'Sony Xperia 1 VI',
                'description' => 'Professional camera phone with 4K OLED display',
                'category' => 'Phones',
                'price' => '$1099.99',
                'stock' => 22,
                'status' => 'Active',
                'created_at' => '2024-04-25',
                'specifications' => [
                    'screen_size' => '6.5"',
                    'ram' => '12GB',
                    'storage' => '256GB',
                    'processor' => 'Snapdragon 8 Gen 3',
                    'camera' => '48MP Main',
                    'battery' => '5000mAh'
                ]
            ],
            [
                'id' => 18,
                'name' => 'MSI Stealth 16',
                'description' => 'Thin and light gaming laptop with RTX 4080',
                'category' => 'Laptops',
                'price' => '$2299.99',
                'stock' => 15,
                'status' => 'Active',
                'created_at' => '2024-05-01',
                'specifications' => [
                    'screen_size' => '16"',
                    'ram' => '32GB',
                    'storage' => '2TB SSD',
                    'processor' => 'Intel Core i9-13900H',
                    'graphics' => 'NVIDIA RTX 4080',
                    'battery' => '7 hours'
                ]
            ],
            [
                'id' => 19,
                'name' => 'Nothing Phone (2)',
                'description' => 'Unique transparent design with Glyph interface',
                'category' => 'Phones',
                'price' => '$699.99',
                'stock' => 38,
                'status' => 'Active',
                'created_at' => '2024-05-05',
                'specifications' => [
                    'screen_size' => '6.7"',
                    'ram' => '12GB',
                    'storage' => '256GB',
                    'processor' => 'Snapdragon 8+ Gen 1',
                    'camera' => '50MP Main',
                    'battery' => '4700mAh'
                ]
            ],
            [
                'id' => 20,
                'name' => 'Acer Swift 14',
                'description' => 'Ultralight laptop with AMD Ryzen 7 processor',
                'category' => 'Laptops',
                'price' => '$999.99',
                'stock' => 42,
                'status' => 'Active',
                'created_at' => '2024-05-10',
                'specifications' => [
                    'screen_size' => '14"',
                    'ram' => '16GB',
                    'storage' => '512GB SSD',
                    'processor' => 'AMD Ryzen 7 7840U',
                    'graphics' => 'AMD Radeon 780M',
                    'battery' => '11 hours'
                ]
            ],
            [
                'id' => 21,
                'name' => 'Motorola Edge+ (2024)',
                'description' => 'Curved display phone with massive battery',
                'category' => 'Phones',
                'price' => '$799.99',
                'stock' => 33,
                'status' => 'Active',
                'created_at' => '2024-05-15',
                'specifications' => [
                    'screen_size' => '6.67"',
                    'ram' => '12GB',
                    'storage' => '256GB',
                    'processor' => 'Snapdragon 8 Gen 3',
                    'camera' => '50MP Main',
                    'battery' => '5100mAh'
                ]
            ],
            [
                'id' => 22,
                'name' => 'LG Gram 17',
                'description' => 'Large lightweight laptop with 17" display',
                'category' => 'Laptops',
                'price' => '$1499.99',
                'stock' => 25,
                'status' => 'Active',
                'created_at' => '2024-05-20',
                'specifications' => [
                    'screen_size' => '17"',
                    'ram' => '16GB',
                    'storage' => '1TB SSD',
                    'processor' => 'Intel Core i7-1360P',
                    'graphics' => 'Intel Iris Xe',
                    'battery' => '16 hours'
                ]
            ]
        ];

        // Merge custom items with sample items
        $allItems = array_merge($customItems, $sampleItems);
        
        return $allItems;
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
                                <span class="bg-orange-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" wire:key="cart-count">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    </div>
                    <button 
                        wire:click="openAddItemModal"
                        class="bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all duration-200 shadow-soft hover:shadow-soft-lg"
                    >
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
                        wire:click="$set('selectedCategory', 'Laptops')"
                        class="{{ $selectedCategory === 'Laptops' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-4 px-1 border-b-2 font-medium text-sm"
                    >
                        {{ __('Laptops') }}
                        <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2.5 rounded-full text-xs">
                            {{ $this->categoryCounts['Laptops'] }}
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

    <!-- Items Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        @if (count($this->items) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($this->items as $item)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 overflow-hidden">
                        <!-- Card Header -->
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item['category'] === 'Phones' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $item['category'] }}
                                    </span>
                                    @if (isset($cart[$item['id']]))
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            {{ __('In Cart') }}
                                        </span>
                                    @endif
                                </div>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $item['status'] }}
                                </span>
                            </div>
                            
                            <!-- Item Name and Description -->
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $item['name'] }}</h3>
                            <p class="text-sm text-gray-600 mb-4">{{ $item['description'] }}</p>
                            
                            <!-- Specifications -->
                            <div class="mb-4">
                                <div class="flex flex-wrap gap-1">
                                    @if (isset($item['specifications']['screen_size']))
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ $item['specifications']['screen_size'] }}
                                        </span>
                                    @endif
                                    @if (isset($item['specifications']['ram']))
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-purple-100 text-purple-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ $item['specifications']['ram'] }}
                                        </span>
                                    @endif
                                    @if (isset($item['specifications']['storage']))
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                                            </svg>
                                            {{ $item['specifications']['storage'] }}
                                        </span>
                                    @endif
                                    @if (isset($item['specifications']['processor']))
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ $item['specifications']['processor'] }}
                                        </span>
                                    @endif
                                    @if (isset($item['specifications']['camera']))
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-pink-100 text-pink-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ $item['specifications']['camera'] }}
                                        </span>
                                    @endif
                                    @if (isset($item['specifications']['graphics']))
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-indigo-100 text-indigo-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                            </svg>
                                            {{ $item['specifications']['graphics'] }}
                                        </span>
                                    @endif
                                    @if (isset($item['specifications']['battery']))
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                            {{ $item['specifications']['battery'] }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Price and Stock -->
                            <div class="flex items-center justify-between mb-4">
                                @if ($item['stock'] <= 0)
                                    <div class="text-lg font-bold text-red-600">{{ __('Out of Stock') }}</div>
                                @else
                                    <div class="text-lg font-bold text-gray-900">{{ $item['price'] }}</div>
                                @endif
                                <div class="text-sm">
                                    <span class="text-gray-500">{{ __('Stock') }}:</span>
                                    <span class="text-sm font-medium {{ $item['stock'] > 10 ? 'text-green-600' : ($item['stock'] > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ $item['stock'] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card Footer Actions -->
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                            <div class="flex flex-col space-y-2">
                                @if ($item['stock'] > 0)
                                    @if (isset($cart[$item['id']]))
                                        <button 
                                            wire:click="addToCart({{ $item['id'] }})"
                                            class="inline-flex items-center justify-center text-white bg-green-600 hover:bg-green-700 px-4 py-2 border border-green-600 rounded-lg transition-colors duration-200"
                                        >
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            {{ __('In Cart (') . $cart[$item['id']]['quantity'] . __(')') }}
                                        </button>
                                    @else
                                        <button 
                                            wire:click="addToCart({{ $item['id'] }})"
                                            class="inline-flex items-center justify-center text-white bg-orange-600 hover:bg-orange-700 px-4 py-2 border border-orange-600 rounded-lg transition-colors duration-200"
                                        >
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                            </svg>
                                            {{ __('Add to Cart') }}
                                        </button>
                                    @endif
                                @else
                                    <button 
                                        disabled
                                        class="inline-flex items-center justify-center text-gray-500 bg-gray-300 px-4 py-2 border border-gray-300 rounded-lg cursor-not-allowed"
                                    >
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.314 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                        {{ __('Out of Stock') }}
                                    </button>
                                @endif
                                
                                <div class="flex space-x-2">
                                    <button 
                                        wire:click="openEditItemModal({{ $item['id'] }})"
                                        class="flex-1 text-indigo-600 hover:text-indigo-900 px-3 py-2 text-sm border border-indigo-300 rounded-lg hover:bg-indigo-50 transition-colors duration-200"
                                    >
                                        {{ __('Edit') }}
                                    </button>
                                    <button 
                                        wire:click="deleteItem({{ $item['id'] }})"
                                        class="flex-1 text-red-600 hover:text-red-900 px-3 py-2 text-sm border border-red-300 rounded-lg hover:bg-red-50 transition-colors duration-200"
                                    >
                                        {{ __('Delete') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
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
                    <button 
                        wire:click="openAddItemModal"
                        class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all duration-200"
                    >
                        {{ __('Add New Item') }}
                    </button>
                </div>
            </div>
        @endif
    </div>

    <!-- Add Item Modal -->
    @if ($showAddItemModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeAddItemModal"></div>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-soft-lg transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="addNewItem">
                        <div class="bg-white px-6 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="w-full">
                                    <h3 class="text-2xl leading-6 font-semibold text-gray-900 mb-6">
                                        {{ __('Add New Item') }}
                                    </h3>
                                    
                                    <!-- Validation Errors -->
                                    @if ($errors->any())
                                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                                            <ul class="text-sm text-red-600">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <div class="space-y-4">
                                        <!-- Item Name -->
                                        <div>
                                            <x-input-label for="newItemName" :value="__('Item Name')" />
                                            <x-text-input 
                                                wire:model="newItemName"
                                                id="newItemName"
                                                class="mt-1 block w-full"
                                                placeholder="{{ __('Enter item name...') }}"
                                                required
                                            />
                                            <x-input-error :messages="$errors->get('newItemName')" class="mt-2" />
                                        </div>

                                        <!-- Description -->
                                        <div>
                                            <x-input-label for="newItemDescription" :value="__('Description')" />
                                            <textarea 
                                                wire:model="newItemDescription"
                                                id="newItemDescription"
                                                rows="3"
                                                class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm transition-all duration-200"
                                                placeholder="{{ __('Enter item description...') }}"
                                                required
                                            ></textarea>
                                            <x-input-error :messages="$errors->get('newItemDescription')" class="mt-2" />
                                        </div>

                                        <!-- Category -->
                                        <div>
                                            <x-input-label for="newItemCategory" :value="__('Category')" />
                                            <select 
                                                wire:model.live="newItemCategory"
                                                id="newItemCategory"
                                                class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm transition-all duration-200"
                                                required
                                            >
                                                <option value="Phones">{{ __('Phones') }}</option>
                                                <option value="Laptops">{{ __('Laptops') }}</option>
                                            </select>
                                            <x-input-error :messages="$errors->get('newItemCategory')" class="mt-2" />
                                        </div>

                                        <!-- Price and Stock -->
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <x-input-label for="newItemPrice" :value="__('Price')" />
                                                <select 
                                                    wire:model="newItemPrice"
                                                    id="newItemPrice"
                                                    class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm transition-all duration-200"
                                                    required
                                                >
                                                    <option value="">{{ __('Select price...') }}</option>
                                                    @if ($newItemCategory === 'Laptops')
                                                        <option value="$299.99">$299.99</option>
                                                        <option value="$399.99">$399.99</option>
                                                        <option value="$499.99">$499.99</option>
                                                        <option value="$599.99">$599.99</option>
                                                        <option value="$699.99">$699.99</option>
                                                        <option value="$799.99">$799.99</option>
                                                        <option value="$899.99">$899.99</option>
                                                        <option value="$999.99">$999.99</option>
                                                        <option value="$1299.99">$1299.99</option>
                                                        <option value="$1499.99">$1499.99</option>
                                                        <option value="$1799.99">$1799.99</option>
                                                        <option value="$1999.99">$1999.99</option>
                                                        <option value="$2499.99">$2499.99</option>
                                                        <option value="$2999.99">$2999.99</option>
                                                    @else
                                                        <option value="$199.99">$199.99</option>
                                                        <option value="$299.99">$299.99</option>
                                                        <option value="$399.99">$399.99</option>
                                                        <option value="$499.99">$499.99</option>
                                                        <option value="$599.99">$599.99</option>
                                                        <option value="$699.99">$699.99</option>
                                                        <option value="$799.99">$799.99</option>
                                                        <option value="$899.99">$899.99</option>
                                                        <option value="$999.99">$999.99</option>
                                                        <option value="$1099.99">$1099.99</option>
                                                        <option value="$1299.99">$1299.99</option>
                                                        <option value="$1499.99">$1499.99</option>
                                                    @endif
                                                </select>
                                                <x-input-error :messages="$errors->get('newItemPrice')" class="mt-2" />
                                            </div>

                                            <div>
                                                <x-input-label for="newItemStock" :value="__('Stock Quantity')" />
                                                <select 
                                                    wire:model="newItemStock"
                                                    id="newItemStock"
                                                    class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm transition-all duration-200"
                                                    required
                                                >
                                                    <option value="">{{ __('Select stock...') }}</option>
                                                    <option value="0">0</option>
                                                    <option value="5">5</option>
                                                    <option value="10">10</option>
                                                    <option value="15">15</option>
                                                    <option value="20">20</option>
                                                    <option value="25">25</option>
                                                    <option value="30">30</option>
                                                    <option value="40">40</option>
                                                    <option value="50">50</option>
                                                    <option value="75">75</option>
                                                    <option value="100">100</option>
                                                    <option value="150">150</option>
                                                    <option value="200">200</option>
                                                </select>
                                                <x-input-error :messages="$errors->get('newItemStock')" class="mt-2" />
                                            </div>
                                        </div>

                                        <!-- Specifications Section -->
                                        <div class="border-t pt-4">
                                            <h4 class="text-lg font-medium text-gray-900 mb-4">{{ __('Specifications') }}</h4>
                                            
                                            <!-- Common specifications for both categories -->
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <x-input-label for="newItemScreenSize" :value="__('Screen Size')" />
                                                    @if ($newItemCategory === 'Laptops')
                                                        <select 
                                                            wire:model="newItemScreenSize"
                                                            id="newItemScreenSize"
                                                            class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm transition-all duration-200"
                                                            required
                                                        >
                                                            <option value="">{{ __('Select screen size...') }}</option>
                                                            <option value="13\"">13"</option>
                                                            <option value="14\"">14"</option>
                                                            <option value="15.6\"">15.6"</option>
                                                            <option value="16\"">16"</option>
                                                            <option value="17.3\"">17.3"</option>
                                                        </select>
                                                    @else
                                                        <select 
                                                            wire:model="newItemScreenSize"
                                                            id="newItemScreenSize"
                                                            class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm transition-all duration-200"
                                                            required
                                                        >
                                                            <option value="">{{ __('Select screen size...') }}</option>
                                                            <option value="5.4\"">5.4"</option>
                                                            <option value="5.8\"">5.8"</option>
                                                            <option value="6.1\"">6.1"</option>
                                                            <option value="6.5\"">6.5"</option>
                                                            <option value="6.7\"">6.7"</option>
                                                            <option value="6.8\"">6.8"</option>
                                                        </select>
                                                    @endif
                                                    <x-input-error :messages="$errors->get('newItemScreenSize')" class="mt-2" />
                                                </div>

                                                <div>
                                                    <x-input-label for="newItemRam" :value="__('RAM')" />
                                                    <select 
                                                        wire:model="newItemRam"
                                                        id="newItemRam"
                                                        class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm transition-all duration-200"
                                                        required
                                                    >
                                                        <option value="">{{ __('Select RAM...') }}</option>
                                                        @if ($newItemCategory === 'Laptops')
                                                            <option value="4GB">4GB</option>
                                                            <option value="8GB">8GB</option>
                                                            <option value="16GB">16GB</option>
                                                            <option value="32GB">32GB</option>
                                                            <option value="64GB">64GB</option>
                                                        @else
                                                            <option value="4GB">4GB</option>
                                                            <option value="6GB">6GB</option>
                                                            <option value="8GB">8GB</option>
                                                            <option value="12GB">12GB</option>
                                                            <option value="16GB">16GB</option>
                                                        @endif
                                                    </select>
                                                    <x-input-error :messages="$errors->get('newItemRam')" class="mt-2" />
                                                </div>

                                                <div>
                                                    <x-input-label for="newItemStorage" :value="__('Storage')" />
                                                    <select 
                                                        wire:model="newItemStorage"
                                                        id="newItemStorage"
                                                        class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm transition-all duration-200"
                                                        required
                                                    >
                                                        <option value="">{{ __('Select storage...') }}</option>
                                                        @if ($newItemCategory === 'Laptops')
                                                            <option value="128GB SSD">128GB SSD</option>
                                                            <option value="256GB SSD">256GB SSD</option>
                                                            <option value="512GB SSD">512GB SSD</option>
                                                            <option value="1TB SSD">1TB SSD</option>
                                                            <option value="2TB SSD">2TB SSD</option>
                                                        @else
                                                            <option value="64GB">64GB</option>
                                                            <option value="128GB">128GB</option>
                                                            <option value="256GB">256GB</option>
                                                            <option value="512GB">512GB</option>
                                                            <option value="1TB">1TB</option>
                                                        @endif
                                                    </select>
                                                    <x-input-error :messages="$errors->get('newItemStorage')" class="mt-2" />
                                                </div>

                                                <div>
                                                    <x-input-label for="newItemProcessor" :value="__('Processor')" />
                                                    <select 
                                                        wire:model="newItemProcessor"
                                                        id="newItemProcessor"
                                                        class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm transition-all duration-200"
                                                        required
                                                    >
                                                        <option value="">{{ __('Select processor...') }}</option>
                                                        @if ($newItemCategory === 'Laptops')
                                                            <option value="Intel Core i3">Intel Core i3</option>
                                                            <option value="Intel Core i5">Intel Core i5</option>
                                                            <option value="Intel Core i7">Intel Core i7</option>
                                                            <option value="Intel Core i9">Intel Core i9</option>
                                                            <option value="Apple M1">Apple M1</option>
                                                            <option value="Apple M2">Apple M2</option>
                                                            <option value="Apple M3">Apple M3</option>
                                                            <option value="AMD Ryzen 5">AMD Ryzen 5</option>
                                                            <option value="AMD Ryzen 7">AMD Ryzen 7</option>
                                                            <option value="AMD Ryzen 9">AMD Ryzen 9</option>
                                                        @else
                                                            <option value="A14 Bionic">A14 Bionic</option>
                                                            <option value="A15 Bionic">A15 Bionic</option>
                                                            <option value="A16 Bionic">A16 Bionic</option>
                                                            <option value="A17 Pro">A17 Pro</option>
                                                            <option value="Snapdragon 8 Gen 1">Snapdragon 8 Gen 1</option>
                                                            <option value="Snapdragon 8 Gen 2">Snapdragon 8 Gen 2</option>
                                                            <option value="Snapdragon 8 Gen 3">Snapdragon 8 Gen 3</option>
                                                            <option value="Google Tensor G2">Google Tensor G2</option>
                                                            <option value="Google Tensor G3">Google Tensor G3</option>
                                                        @endif
                                                    </select>
                                                    <x-input-error :messages="$errors->get('newItemProcessor')" class="mt-2" />
                                                </div>
                                            </div>

                                            <!-- Phone-specific specifications -->
                                            @if ($newItemCategory === 'Phones')
                                                <div class="mt-4 grid grid-cols-2 gap-4">
                                                    <div>
                                                        <x-input-label for="newItemCamera" :value="__('Camera')" />
                                                        <select 
                                                            wire:model="newItemCamera"
                                                            id="newItemCamera"
                                                            class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm transition-all duration-200"
                                                        >
                                                            <option value="">{{ __('Select camera...') }}</option>
                                                            <option value="12MP Main">12MP Main</option>
                                                            <option value="48MP Main">48MP Main</option>
                                                            <option value="50MP Main">50MP Main</option>
                                                            <option value="64MP Main">64MP Main</option>
                                                            <option value="108MP Main">108MP Main</option>
                                                            <option value="12MP Main + 12MP Ultrawide">12MP Main + 12MP Ultrawide</option>
                                                            <option value="48MP Main + 12MP Ultrawide">48MP Main + 12MP Ultrawide</option>
                                                            <option value="50MP Main + 12MP Ultrawide + 10MP Telephoto">50MP Main + 12MP Ultrawide + 10MP Telephoto</option>
                                                        </select>
                                                        <x-input-error :messages="$errors->get('newItemCamera')" class="mt-2" />
                                                    </div>

                                                    <div>
                                                        <x-input-label for="newItemBattery" :value="__('Battery Capacity')" />
                                                        <select 
                                                            wire:model="newItemBattery"
                                                            id="newItemBattery"
                                                            class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm transition-all duration-200"
                                                        >
                                                            <option value="">{{ __('Select battery...') }}</option>
                                                            <option value="3000mAh">3000mAh</option>
                                                            <option value="3200mAh">3200mAh</option>
                                                            <option value="3500mAh">3500mAh</option>
                                                            <option value="4000mAh">4000mAh</option>
                                                            <option value="4500mAh">4500mAh</option>
                                                            <option value="5000mAh">5000mAh</option>
                                                        </select>
                                                        <x-input-error :messages="$errors->get('newItemBattery')" class="mt-2" />
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Computer-specific specifications -->
                                            @if ($newItemCategory === 'Laptops')
                                                <div class="mt-4 grid grid-cols-2 gap-4">
                                                    <div>
                                                        <x-input-label for="newItemGraphics" :value="__('Graphics Card')" />
                                                        <select 
                                                            wire:model="newItemGraphics"
                                                            id="newItemGraphics"
                                                            class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm transition-all duration-200"
                                                        >
                                                            <option value="">{{ __('Select graphics...') }}</option>
                                                            <option value="Intel HD Graphics">Intel HD Graphics</option>
                                                            <option value="Intel Iris Xe">Intel Iris Xe</option>
                                                            <option value="NVIDIA GeForce RTX 3050">NVIDIA GeForce RTX 3050</option>
                                                            <option value="NVIDIA GeForce RTX 4050">NVIDIA GeForce RTX 4050</option>
                                                            <option value="NVIDIA GeForce RTX 4060">NVIDIA GeForce RTX 4060</option>
                                                            <option value="NVIDIA GeForce RTX 4070">NVIDIA GeForce RTX 4070</option>
                                                            <option value="AMD Radeon Graphics">AMD Radeon Graphics</option>
                                                            <option value="Apple Integrated GPU">Apple Integrated GPU</option>
                                                        </select>
                                                        <x-input-error :messages="$errors->get('newItemGraphics')" class="mt-2" />
                                                    </div>

                                                    <div>
                                                        <x-input-label for="newItemBattery" :value="__('Battery Life')" />
                                                        <select 
                                                            wire:model="newItemBattery"
                                                            id="newItemBattery"
                                                            class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm transition-all duration-200"
                                                        >
                                                            <option value="">{{ __('Select battery life...') }}</option>
                                                            <option value="4 hours">4 hours</option>
                                                            <option value="6 hours">6 hours</option>
                                                            <option value="8 hours">8 hours</option>
                                                            <option value="10 hours">10 hours</option>
                                                            <option value="12 hours">12 hours</option>
                                                            <option value="14 hours">14 hours</option>
                                                            <option value="16+ hours">16+ hours</option>
                                                        </select>
                                                        <x-input-error :messages="$errors->get('newItemBattery')" class="mt-2" />
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Modal Actions -->
                        <div class="bg-gray-50 px-6 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <x-primary-button type="submit" class="w-full sm:w-auto sm:ml-3">
                                {{ __('Add Item') }}
                            </x-primary-button>
                            
                            <x-secondary-button type="button" wire:click="closeAddItemModal" class="mt-3 sm:mt-0 w-full sm:w-auto">
                                {{ __('Cancel') }}
                            </x-secondary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Item Modal -->
    @if ($showEditItemModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeEditItemModal"></div>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-soft-lg transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="updateItem">
                        <div class="bg-white px-6 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="w-full">
                                    <h3 class="text-2xl leading-6 font-semibold text-gray-900 mb-6">
                                        {{ __('Edit Item') }}
                                    </h3>
                                    
                                    <!-- Validation Errors -->
                                    @if ($errors->any())
                                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                                            <ul class="text-sm text-red-600">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <div class="space-y-4">
                                        <!-- Item Name -->
                                        <div>
                                            <x-input-label for="editItemName" :value="__('Item Name')" />
                                            <x-text-input 
                                                wire:model="editItemName"
                                                id="editItemName"
                                                class="mt-1 block w-full"
                                                placeholder="{{ __('Enter item name...') }}"
                                                required
                                            />
                                            <x-input-error :messages="$errors->get('editItemName')" class="mt-2" />
                                        </div>

                                        <!-- Description -->
                                        <div>
                                            <x-input-label for="editItemDescription" :value="__('Description')" />
                                            <textarea 
                                                wire:model="editItemDescription"
                                                id="editItemDescription"
                                                rows="3"
                                                class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm transition-all duration-200"
                                                placeholder="{{ __('Enter item description...') }}"
                                                required
                                            ></textarea>
                                            <x-input-error :messages="$errors->get('editItemDescription')" class="mt-2" />
                                        </div>

                                        <!-- Category -->
                                        <div>
                                            <x-input-label for="editItemCategory" :value="__('Category')" />
                                            <select 
                                                wire:model.live="editItemCategory"
                                                id="editItemCategory"
                                                class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm transition-all duration-200"
                                                required
                                            >
                                                <option value="Phones" {{ $editItemCategory === 'Phones' ? 'selected' : '' }}>{{ __('Phones') }}</option>
                                                <option value="Laptops" {{ $editItemCategory === 'Laptops' ? 'selected' : '' }}>{{ __('Laptops') }}</option>
                                            </select>
                                            <x-input-error :messages="$errors->get('editItemCategory')" class="mt-2" />
                                        </div>

                                        <!-- Price and Stock -->
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <x-input-label for="editItemPrice" :value="__('Price')" />
                                                <select 
                                                    wire:model="editItemPrice"
                                                    id="editItemPrice"
                                                    class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm transition-all duration-200"
                                                    required
                                                >
                                                    <option value="">{{ __('Select price...') }}</option>
                                                    @if ($editItemCategory === 'Laptops')
                                                        <option value="$299.99" {{ $editItemPrice === '$299.99' ? 'selected' : '' }}>$299.99</option>
                                                        <option value="$399.99" {{ $editItemPrice === '$399.99' ? 'selected' : '' }}>$399.99</option>
                                                        <option value="$499.99" {{ $editItemPrice === '$499.99' ? 'selected' : '' }}>$499.99</option>
                                                        <option value="$599.99" {{ $editItemPrice === '$599.99' ? 'selected' : '' }}>$599.99</option>
                                                        <option value="$699.99" {{ $editItemPrice === '$699.99' ? 'selected' : '' }}>$699.99</option>
                                                        <option value="$799.99" {{ $editItemPrice === '$799.99' ? 'selected' : '' }}>$799.99</option>
                                                        <option value="$899.99" {{ $editItemPrice === '$899.99' ? 'selected' : '' }}>$899.99</option>
                                                        <option value="$999.99" {{ $editItemPrice === '$999.99' ? 'selected' : '' }}>$999.99</option>
                                                        <option value="$1299.99" {{ $editItemPrice === '$1299.99' ? 'selected' : '' }}>$1299.99</option>
                                                        <option value="$1499.99" {{ $editItemPrice === '$1499.99' ? 'selected' : '' }}>$1499.99</option>
                                                        <option value="$1799.99" {{ $editItemPrice === '$1799.99' ? 'selected' : '' }}>$1799.99</option>
                                                        <option value="$1999.99" {{ $editItemPrice === '$1999.99' ? 'selected' : '' }}>$1999.99</option>
                                                        <option value="$2499.99" {{ $editItemPrice === '$2499.99' ? 'selected' : '' }}>$2499.99</option>
                                                        <option value="$2999.99" {{ $editItemPrice === '$2999.99' ? 'selected' : '' }}>$2999.99</option>
                                                    @else
                                                        <option value="$199.99" {{ $editItemPrice === '$199.99' ? 'selected' : '' }}>$199.99</option>
                                                        <option value="$299.99" {{ $editItemPrice === '$299.99' ? 'selected' : '' }}>$299.99</option>
                                                        <option value="$399.99" {{ $editItemPrice === '$399.99' ? 'selected' : '' }}>$399.99</option>
                                                        <option value="$499.99" {{ $editItemPrice === '$499.99' ? 'selected' : '' }}>$499.99</option>
                                                        <option value="$599.99" {{ $editItemPrice === '$599.99' ? 'selected' : '' }}>$599.99</option>
                                                        <option value="$699.99" {{ $editItemPrice === '$699.99' ? 'selected' : '' }}>$699.99</option>
                                                        <option value="$799.99" {{ $editItemPrice === '$799.99' ? 'selected' : '' }}>$799.99</option>
                                                        <option value="$899.99" {{ $editItemPrice === '$899.99' ? 'selected' : '' }}>$899.99</option>
                                                        <option value="$999.99" {{ $editItemPrice === '$999.99' ? 'selected' : '' }}>$999.99</option>
                                                        <option value="$1099.99" {{ $editItemPrice === '$1099.99' ? 'selected' : '' }}>$1099.99</option>
                                                        <option value="$1299.99" {{ $editItemPrice === '$1299.99' ? 'selected' : '' }}>$1299.99</option>
                                                        <option value="$1499.99" {{ $editItemPrice === '$1499.99' ? 'selected' : '' }}>$1499.99</option>
                                                    @endif
                                                </select>
                                                <x-input-error :messages="$errors->get('editItemPrice')" class="mt-2" />
                                            </div>

                                            <div>
                                                <x-input-label for="editItemStock" :value="__('Stock Quantity')" />
                                                <select 
                                                    wire:model="editItemStock"
                                                    id="editItemStock"
                                                    class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm transition-all duration-200"
                                                    required
                                                >
                                                    <option value="">{{ __('Select stock...') }}</option>
                                                    <option value="0" {{ $editItemStock == 0 ? 'selected' : '' }}>0</option>
                                                    <option value="5" {{ $editItemStock == 5 ? 'selected' : '' }}>5</option>
                                                    <option value="10" {{ $editItemStock == 10 ? 'selected' : '' }}>10</option>
                                                    <option value="15" {{ $editItemStock == 15 ? 'selected' : '' }}>15</option>
                                                    <option value="20" {{ $editItemStock == 20 ? 'selected' : '' }}>20</option>
                                                    <option value="25" {{ $editItemStock == 25 ? 'selected' : '' }}>25</option>
                                                    <option value="30" {{ $editItemStock == 30 ? 'selected' : '' }}>30</option>
                                                    <option value="40" {{ $editItemStock == 40 ? 'selected' : '' }}>40</option>
                                                    <option value="50" {{ $editItemStock == 50 ? 'selected' : '' }}>50</option>
                                                    <option value="75" {{ $editItemStock == 75 ? 'selected' : '' }}>75</option>
                                                    <option value="100" {{ $editItemStock == 100 ? 'selected' : '' }}>100</option>
                                                    <option value="150" {{ $editItemStock == 150 ? 'selected' : '' }}>150</option>
                                                    <option value="200" {{ $editItemStock == 200 ? 'selected' : '' }}>200</option>
                                                </select>
                                                <x-input-error :messages="$errors->get('editItemStock')" class="mt-2" />
                                            </div>
                                        </div>

                                        <!-- Specifications Section -->
                                        <div class="border-t pt-4">
                                            <h4 class="text-lg font-medium text-gray-900 mb-4">{{ __('Specifications') }}</h4>
                                            
                                            <!-- Common specifications for both categories -->
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <x-input-label for="editItemScreenSize" :value="__('Screen Size')" />
                                                    @if ($editItemCategory === 'Laptops')
                                                        <select 
                                                            wire:model="editItemScreenSize"
                                                            id="editItemScreenSize"
                                                            class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm transition-all duration-200"
                                                            required
                                                        >
                                                            <option value="">{{ __('Select screen size...') }}</option>
                                                            <option value="13\"" {{ $editItemScreenSize === '13"' ? 'selected' : '' }}>13"</option>
                                                            <option value="14\"" {{ $editItemScreenSize === '14"' ? 'selected' : '' }}>14"</option>
                                                            <option value="15.6\"" {{ $editItemScreenSize === '15.6"' ? 'selected' : '' }}>15.6"</option>
                                                            <option value="16\"" {{ $editItemScreenSize === '16"' ? 'selected' : '' }}>16"</option>
                                                            <option value="17.3\"" {{ $editItemScreenSize === '17.3"' ? 'selected' : '' }}>17.3"</option>
                                                        </select>
                                                    @else
                                                        <select 
                                                            wire:model="editItemScreenSize"
                                                            id="editItemScreenSize"
                                                            class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm transition-all duration-200"
                                                            required
                                                        >
                                                            <option value="">{{ __('Select screen size...') }}</option>
                                                            <option value="5.4\"" {{ $editItemScreenSize === '5.4"' ? 'selected' : '' }}>5.4"</option>
                                                            <option value="5.8\"" {{ $editItemScreenSize === '5.8"' ? 'selected' : '' }}>5.8"</option>
                                                            <option value="6.1\"" {{ $editItemScreenSize === '6.1"' ? 'selected' : '' }}>6.1"</option>
                                                            <option value="6.5\"" {{ $editItemScreenSize === '6.5"' ? 'selected' : '' }}>6.5"</option>
                                                            <option value="6.7\"" {{ $editItemScreenSize === '6.7"' ? 'selected' : '' }}>6.7"</option>
                                                            <option value="6.8\"" {{ $editItemScreenSize === '6.8"' ? 'selected' : '' }}>6.8"</option>
                                                        </select>
                                                    @endif
                                                    <x-input-error :messages="$errors->get('editItemScreenSize')" class="mt-2" />
                                                </div>

                                                <div>
                                                    <x-input-label for="editItemRam" :value="__('RAM')" />
                                                    <select 
                                                        wire:model="editItemRam"
                                                        id="editItemRam"
                                                        class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm transition-all duration-200"
                                                        required
                                                    >
                                                        <option value="">{{ __('Select RAM...') }}</option>
                                                        @if ($editItemCategory === 'Laptops')
                                                            <option value="4GB" {{ $editItemRam === '4GB' ? 'selected' : '' }}>4GB</option>
                                                            <option value="8GB" {{ $editItemRam === '8GB' ? 'selected' : '' }}>8GB</option>
                                                            <option value="16GB" {{ $editItemRam === '16GB' ? 'selected' : '' }}>16GB</option>
                                                            <option value="32GB" {{ $editItemRam === '32GB' ? 'selected' : '' }}>32GB</option>
                                                            <option value="64GB" {{ $editItemRam === '64GB' ? 'selected' : '' }}>64GB</option>
                                                        @else
                                                            <option value="4GB" {{ $editItemRam === '4GB' ? 'selected' : '' }}>4GB</option>
                                                            <option value="6GB" {{ $editItemRam === '6GB' ? 'selected' : '' }}>6GB</option>
                                                            <option value="8GB" {{ $editItemRam === '8GB' ? 'selected' : '' }}>8GB</option>
                                                            <option value="12GB" {{ $editItemRam === '12GB' ? 'selected' : '' }}>12GB</option>
                                                            <option value="16GB" {{ $editItemRam === '16GB' ? 'selected' : '' }}>16GB</option>
                                                        @endif
                                                    </select>
                                                    <x-input-error :messages="$errors->get('editItemRam')" class="mt-2" />
                                                </div>

                                                <div>
                                                    <x-input-label for="editItemStorage" :value="__('Storage')" />
                                                    <select 
                                                        wire:model="editItemStorage"
                                                        id="editItemStorage"
                                                        class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm transition-all duration-200"
                                                        required
                                                    >
                                                        <option value="">{{ __('Select storage...') }}</option>
                                                        @if ($editItemCategory === 'Laptops')
                                                            <option value="128GB SSD" {{ $editItemStorage === '128GB SSD' ? 'selected' : '' }}>128GB SSD</option>
                                                            <option value="256GB SSD" {{ $editItemStorage === '256GB SSD' ? 'selected' : '' }}>256GB SSD</option>
                                                            <option value="512GB SSD" {{ $editItemStorage === '512GB SSD' ? 'selected' : '' }}>512GB SSD</option>
                                                            <option value="1TB SSD" {{ $editItemStorage === '1TB SSD' ? 'selected' : '' }}>1TB SSD</option>
                                                            <option value="2TB SSD" {{ $editItemStorage === '2TB SSD' ? 'selected' : '' }}>2TB SSD</option>
                                                        @else
                                                            <option value="64GB" {{ $editItemStorage === '64GB' ? 'selected' : '' }}>64GB</option>
                                                            <option value="128GB" {{ $editItemStorage === '128GB' ? 'selected' : '' }}>128GB</option>
                                                            <option value="256GB" {{ $editItemStorage === '256GB' ? 'selected' : '' }}>256GB</option>
                                                            <option value="512GB" {{ $editItemStorage === '512GB' ? 'selected' : '' }}>512GB</option>
                                                            <option value="1TB" {{ $editItemStorage === '1TB' ? 'selected' : '' }}>1TB</option>
                                                        @endif
                                                    </select>
                                                    <x-input-error :messages="$errors->get('editItemStorage')" class="mt-2" />
                                                </div>

                                                <div>
                                                    <x-input-label for="editItemProcessor" :value="__('Processor')" />
                                                    <select 
                                                        wire:model="editItemProcessor"
                                                        id="editItemProcessor"
                                                        class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm transition-all duration-200"
                                                        required
                                                    >
                                                        <option value="">{{ __('Select processor...') }}</option>
                                                        @if ($editItemCategory === 'Laptops')
                                                            <option value="Intel Core i3" {{ $editItemProcessor === 'Intel Core i3' ? 'selected' : '' }}>Intel Core i3</option>
                                                            <option value="Intel Core i5" {{ $editItemProcessor === 'Intel Core i5' ? 'selected' : '' }}>Intel Core i5</option>
                                                            <option value="Intel Core i7" {{ $editItemProcessor === 'Intel Core i7' ? 'selected' : '' }}>Intel Core i7</option>
                                                            <option value="Intel Core i9" {{ $editItemProcessor === 'Intel Core i9' ? 'selected' : '' }}>Intel Core i9</option>
                                                            <option value="Apple M1" {{ $editItemProcessor === 'Apple M1' ? 'selected' : '' }}>Apple M1</option>
                                                            <option value="Apple M2" {{ $editItemProcessor === 'Apple M2' ? 'selected' : '' }}>Apple M2</option>
                                                            <option value="Apple M3" {{ $editItemProcessor === 'Apple M3' ? 'selected' : '' }}>Apple M3</option>
                                                            <option value="AMD Ryzen 5" {{ $editItemProcessor === 'AMD Ryzen 5' ? 'selected' : '' }}>AMD Ryzen 5</option>
                                                            <option value="AMD Ryzen 7" {{ $editItemProcessor === 'AMD Ryzen 7' ? 'selected' : '' }}>AMD Ryzen 7</option>
                                                            <option value="AMD Ryzen 9" {{ $editItemProcessor === 'AMD Ryzen 9' ? 'selected' : '' }}>AMD Ryzen 9</option>
                                                        @else
                                                            <option value="A14 Bionic" {{ $editItemProcessor === 'A14 Bionic' ? 'selected' : '' }}>A14 Bionic</option>
                                                            <option value="A15 Bionic" {{ $editItemProcessor === 'A15 Bionic' ? 'selected' : '' }}>A15 Bionic</option>
                                                            <option value="A16 Bionic" {{ $editItemProcessor === 'A16 Bionic' ? 'selected' : '' }}>A16 Bionic</option>
                                                            <option value="A17 Pro" {{ $editItemProcessor === 'A17 Pro' ? 'selected' : '' }}>A17 Pro</option>
                                                            <option value="Snapdragon 8 Gen 1" {{ $editItemProcessor === 'Snapdragon 8 Gen 1' ? 'selected' : '' }}>Snapdragon 8 Gen 1</option>
                                                            <option value="Snapdragon 8 Gen 2" {{ $editItemProcessor === 'Snapdragon 8 Gen 2' ? 'selected' : '' }}>Snapdragon 8 Gen 2</option>
                                                            <option value="Snapdragon 8 Gen 3" {{ $editItemProcessor === 'Snapdragon 8 Gen 3' ? 'selected' : '' }}>Snapdragon 8 Gen 3</option>
                                                            <option value="Google Tensor G2" {{ $editItemProcessor === 'Google Tensor G2' ? 'selected' : '' }}>Google Tensor G2</option>
                                                            <option value="Google Tensor G3" {{ $editItemProcessor === 'Google Tensor G3' ? 'selected' : '' }}>Google Tensor G3</option>
                                                        @endif
                                                    </select>
                                                    <x-input-error :messages="$errors->get('editItemProcessor')" class="mt-2" />
                                                </div>
                                            </div>

                                            <!-- Phone-specific specifications -->
                                            @if ($editItemCategory === 'Phones')
                                                <div class="mt-4 grid grid-cols-2 gap-4">
                                                    <div>
                                                        <x-input-label for="editItemCamera" :value="__('Camera')" />
                                                        <select 
                                                            wire:model="editItemCamera"
                                                            id="editItemCamera"
                                                            class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm transition-all duration-200"
                                                        >
                                                            <option value="">{{ __('Select camera...') }}</option>
                                                            <option value="12MP Main" {{ $editItemCamera === '12MP Main' ? 'selected' : '' }}>12MP Main</option>
                                                            <option value="48MP Main" {{ $editItemCamera === '48MP Main' ? 'selected' : '' }}>48MP Main</option>
                                                            <option value="50MP Main" {{ $editItemCamera === '50MP Main' ? 'selected' : '' }}>50MP Main</option>
                                                            <option value="64MP Main" {{ $editItemCamera === '64MP Main' ? 'selected' : '' }}>64MP Main</option>
                                                            <option value="108MP Main" {{ $editItemCamera === '108MP Main' ? 'selected' : '' }}>108MP Main</option>
                                                            <option value="12MP Main + 12MP Ultrawide" {{ $editItemCamera === '12MP Main + 12MP Ultrawide' ? 'selected' : '' }}>12MP Main + 12MP Ultrawide</option>
                                                            <option value="48MP Main + 12MP Ultrawide" {{ $editItemCamera === '48MP Main + 12MP Ultrawide' ? 'selected' : '' }}>48MP Main + 12MP Ultrawide</option>
                                                            <option value="50MP Main + 12MP Ultrawide + 10MP Telephoto" {{ $editItemCamera === '50MP Main + 12MP Ultrawide + 10MP Telephoto' ? 'selected' : '' }}>50MP Main + 12MP Ultrawide + 10MP Telephoto</option>
                                                        </select>
                                                        <x-input-error :messages="$errors->get('editItemCamera')" class="mt-2" />
                                                    </div>

                                                    <div>
                                                        <x-input-label for="editItemBattery" :value="__('Battery Capacity')" />
                                                        <select 
                                                            wire:model="editItemBattery"
                                                            id="editItemBattery"
                                                            class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm transition-all duration-200"
                                                        >
                                                            <option value="">{{ __('Select battery...') }}</option>
                                                            <option value="3000mAh" {{ $editItemBattery === '3000mAh' ? 'selected' : '' }}>3000mAh</option>
                                                            <option value="3200mAh" {{ $editItemBattery === '3200mAh' ? 'selected' : '' }}>3200mAh</option>
                                                            <option value="3500mAh" {{ $editItemBattery === '3500mAh' ? 'selected' : '' }}>3500mAh</option>
                                                            <option value="4000mAh" {{ $editItemBattery === '4000mAh' ? 'selected' : '' }}>4000mAh</option>
                                                            <option value="4500mAh" {{ $editItemBattery === '4500mAh' ? 'selected' : '' }}>4500mAh</option>
                                                            <option value="5000mAh" {{ $editItemBattery === '5000mAh' ? 'selected' : '' }}>5000mAh</option>
                                                        </select>
                                                        <x-input-error :messages="$errors->get('editItemBattery')" class="mt-2" />
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Laptop-specific specifications -->
                                            @if ($editItemCategory === 'Laptops')
                                                <div class="mt-4 grid grid-cols-2 gap-4">
                                                    <div>
                                                        <x-input-label for="editItemGraphics" :value="__('Graphics Card')" />
                                                        <select 
                                                            wire:model="editItemGraphics"
                                                            id="editItemGraphics"
                                                            class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm transition-all duration-200"
                                                        >
                                                            <option value="">{{ __('Select graphics...') }}</option>
                                                            <option value="Intel HD Graphics" {{ $editItemGraphics === 'Intel HD Graphics' ? 'selected' : '' }}>Intel HD Graphics</option>
                                                            <option value="Intel Iris Xe" {{ $editItemGraphics === 'Intel Iris Xe' ? 'selected' : '' }}>Intel Iris Xe</option>
                                                            <option value="NVIDIA GeForce RTX 3050" {{ $editItemGraphics === 'NVIDIA GeForce RTX 3050' ? 'selected' : '' }}>NVIDIA GeForce RTX 3050</option>
                                                            <option value="NVIDIA GeForce RTX 4050" {{ $editItemGraphics === 'NVIDIA GeForce RTX 4050' ? 'selected' : '' }}>NVIDIA GeForce RTX 4050</option>
                                                            <option value="NVIDIA GeForce RTX 4060" {{ $editItemGraphics === 'NVIDIA GeForce RTX 4060' ? 'selected' : '' }}>NVIDIA GeForce RTX 4060</option>
                                                            <option value="NVIDIA GeForce RTX 4070" {{ $editItemGraphics === 'NVIDIA GeForce RTX 4070' ? 'selected' : '' }}>NVIDIA GeForce RTX 4070</option>
                                                            <option value="AMD Radeon Graphics" {{ $editItemGraphics === 'AMD Radeon Graphics' ? 'selected' : '' }}>AMD Radeon Graphics</option>
                                                            <option value="Apple Integrated GPU" {{ $editItemGraphics === 'Apple Integrated GPU' ? 'selected' : '' }}>Apple Integrated GPU</option>
                                                        </select>
                                                        <x-input-error :messages="$errors->get('editItemGraphics')" class="mt-2" />
                                                    </div>

                                                    <div>
                                                        <x-input-label for="editItemBattery" :value="__('Battery Life')" />
                                                        <select 
                                                            wire:model="editItemBattery"
                                                            id="editItemBattery"
                                                            class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm transition-all duration-200"
                                                        >
                                                            <option value="">{{ __('Select battery life...') }}</option>
                                                            <option value="4 hours" {{ $editItemBattery === '4 hours' ? 'selected' : '' }}>4 hours</option>
                                                            <option value="6 hours" {{ $editItemBattery === '6 hours' ? 'selected' : '' }}>6 hours</option>
                                                            <option value="8 hours" {{ $editItemBattery === '8 hours' ? 'selected' : '' }}>8 hours</option>
                                                            <option value="10 hours" {{ $editItemBattery === '10 hours' ? 'selected' : '' }}>10 hours</option>
                                                            <option value="12 hours" {{ $editItemBattery === '12 hours' ? 'selected' : '' }}>12 hours</option>
                                                            <option value="14 hours" {{ $editItemBattery === '14 hours' ? 'selected' : '' }}>14 hours</option>
                                                            <option value="16+ hours" {{ $editItemBattery === '16+ hours' ? 'selected' : '' }}>16+ hours</option>
                                                        </select>
                                                        <x-input-error :messages="$errors->get('editItemBattery')" class="mt-2" />
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Modal Actions -->
                        <div class="bg-gray-50 px-6 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <x-primary-button type="submit" class="w-full sm:w-auto sm:ml-3">
                                {{ __('Update Item') }}
                            </x-primary-button>
                            
                            <x-secondary-button type="button" wire:click="closeEditItemModal" class="mt-3 sm:mt-0 w-full sm:w-auto">
                                {{ __('Cancel') }}
                            </x-secondary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
