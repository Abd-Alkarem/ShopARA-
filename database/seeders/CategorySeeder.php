<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create categories
        $electronics = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'description' => 'Electronic devices and gadgets',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $clothing = Category::create([
            'name' => 'Clothing',
            'slug' => 'clothing',
            'description' => 'Fashion and apparel',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $books = Category::create([
            'name' => 'Books',
            'slug' => 'books',
            'description' => 'Books and literature',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        // Create items for Electronics
        Item::create([
            'name' => 'Laptop Pro',
            'slug' => 'laptop-pro',
            'description' => 'High-performance laptop for professionals',
            'price' => 999.99,
            'sku' => 'LP-001',
            'stock_quantity' => 50,
            'is_active' => true,
            'is_featured' => true,
            'category_id' => $electronics->id,
        ]);

        Item::create([
            'name' => 'Smartphone X',
            'slug' => 'smartphone-x',
            'description' => 'Latest smartphone with advanced features',
            'price' => 699.99,
            'sale_price' => 599.99,
            'sku' => 'SP-002',
            'stock_quantity' => 100,
            'is_active' => true,
            'is_featured' => true,
            'category_id' => $electronics->id,
        ]);

        Item::create([
            'name' => 'Wireless Headphones',
            'slug' => 'wireless-headphones',
            'description' => 'Premium noise-cancelling headphones',
            'price' => 199.99,
            'sku' => 'WH-003',
            'stock_quantity' => 75,
            'is_active' => true,
            'category_id' => $electronics->id,
        ]);

        // Create items for Clothing
        Item::create([
            'name' => 'Cotton T-Shirt',
            'slug' => 'cotton-t-shirt',
            'description' => 'Comfortable 100% cotton t-shirt',
            'price' => 19.99,
            'sku' => 'CT-004',
            'stock_quantity' => 200,
            'is_active' => true,
            'category_id' => $clothing->id,
        ]);

        Item::create([
            'name' => 'Denim Jeans',
            'slug' => 'denim-jeans',
            'description' => 'Classic fit denim jeans',
            'price' => 49.99,
            'sku' => 'DJ-005',
            'stock_quantity' => 150,
            'is_active' => true,
            'category_id' => $clothing->id,
        ]);

        // Create items for Books
        Item::create([
            'name' => 'Programming Guide',
            'slug' => 'programming-guide',
            'description' => 'Comprehensive programming guide',
            'price' => 29.99,
            'sku' => 'PG-006',
            'stock_quantity' => 80,
            'is_active' => true,
            'category_id' => $books->id,
        ]);

        Item::create([
            'name' => 'Science Fiction Novel',
            'slug' => 'science-fiction-novel',
            'description' => 'Exciting science fiction story',
            'price' => 14.99,
            'sku' => 'SF-007',
            'stock_quantity' => 120,
            'is_active' => true,
            'category_id' => $books->id,
        ]);
    }
}
