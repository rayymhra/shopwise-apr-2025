<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'category_id' => 1,
            'name' => 'Kaos Polos',
            'description' => 'Kaos polos berbagai warna.',
            'price' => 50000,
            'stock' => 100,
        ]);

        Product::create([
            'category_id' => 2,
            'name' => 'Headphone Bluetooth',
            'description' => 'Headphone wireless kualitas tinggi.',
            'price' => 350000,
            'stock' => 50,
        ]);

        Product::create([
            'category_id' => 3,
            'name' => 'Coklat Premium',
            'description' => 'Coklat enak dengan rasa khas.',
            'price' => 25000,
            'stock' => 200,
        ]);
    }
}
