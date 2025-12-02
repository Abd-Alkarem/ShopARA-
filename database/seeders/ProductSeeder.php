<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'MacBook Pro 14"',
                'description' => 'Apple MacBook Pro with M3 Pro chip, 18GB RAM, 512GB SSD. Perfect for professionals and creators.',
                'price' => 1999.00,
                'category' => 'laptop',
                'stock' => 15,
                'image' => 'macbook-pro-14.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'iPhone 15 Pro',
                'description' => 'Latest iPhone with A17 Pro chip, titanium design, and advanced camera system.',
                'price' => 999.00,
                'category' => 'phone',
                'stock' => 25,
                'image' => 'iphone-15-pro.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Dell XPS 15',
                'description' => 'Powerful Windows laptop with Intel Core i7, 16GB RAM, 1TB SSD, and NVIDIA RTX 4050.',
                'price' => 1799.00,
                'category' => 'laptop',
                'stock' => 12,
                'image' => 'dell-xps-15.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Samsung Galaxy S24',
                'description' => 'Flagship Android phone with Galaxy AI, premium camera, and stunning display.',
                'price' => 899.00,
                'category' => 'phone',
                'stock' => 20,
                'image' => 'samsung-galaxy-s24.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'ThinkPad X1 Carbon',
                'description' => 'Ultra-light business laptop with Intel Core i7, 16GB RAM, and military-grade durability.',
                'price' => 1499.00,
                'category' => 'laptop',
                'stock' => 8,
                'image' => 'thinkpad-x1-carbon.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Google Pixel 8 Pro',
                'description' => 'AI-powered smartphone with exceptional camera capabilities and clean Android experience.',
                'price' => 999.00,
                'category' => 'phone',
                'stock' => 18,
                'image' => 'pixel-8-pro.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'HP Spectre x360',
                'description' => 'Versatile 2-in-1 laptop with Intel Core i7, 16GB RAM, and stunning OLED display.',
                'price' => 1299.00,
                'category' => 'laptop',
                'stock' => 10,
                'image' => 'hp-spectre-x360.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'OnePlus 12',
                'description' => 'High-performance smartphone with fast charging, premium build, and excellent value.',
                'price' => 799.00,
                'category' => 'phone',
                'stock' => 22,
                'image' => 'oneplus-12.jpg',
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
