<?php

namespace Database\Seeders;

use App\Models\DistributionProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DistributionProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DistributionProduct::insert([
            [
                'product_name' => 'Americano',
                'product_image' => 'null',
                'product_quantity' => 20,
                'product_price' => 8000,
                'product_description' => 'null',
                'id_admin' => 1
            ],
            [
                'product_name' => 'Kopi Gula Aren',
                'product_image' => 'null',
                'product_quantity' => 20,
                'product_price' => 8000,
                'product_description' => 'null',
                'id_admin' => 1
            ],
            [
                'product_name' => 'Kopi Butterscoth',
                'product_image' => 'null',
                'product_quantity' => 20,
                'product_price' => 10000,
                'product_description' => 'null',
                'id_admin' => 1
            ],
            [
                'product_name' => 'Salted Caramel Latte',
                'product_image' => 'null',
                'product_quantity' => 20,
                'product_price' => 10000,
                'product_description' => 'null',
                'id_admin' => 1
            ]
        ]);
    }
}
