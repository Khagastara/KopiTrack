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
                'product_image' => 'product-images/americano.jpg',
                'product_quantity' => 20,
                'product_price' => 8000,
                'product_description'
                => 'Kopi espresso klasik yang diencerkan dengan air panas, memberikan rasa kopi yang kuat namun tidak terlalu pekat. Cocok untuk penikmat kopi hitam murni.',
                'id_admin' => 1
            ],
            [
                'product_name' => 'Kopi Gula Aren',
                'product_image' => 'product-images/es-kopi-susu-gula-aren.jpeg',
                'product_quantity' => 20,
                'product_price' => 8000,
                'product_description'
                => 'Perpaduan sempurna antara espresso dengan gula aren asli, menciptakan rasa manis alami dengan aroma karamel khas. Disajikan dengan tekstur creamy yang lembut.',
                'id_admin' => 1
            ],
            [
                'product_name' => 'Kopi Butterscoth',
                'product_image' => 'product-images/kopi-butterscoth.jpg',
                'product_quantity' => 20,
                'product_price' => 10000,
                'product_description' => 'Espresso premium dengan sirup butterscotch legit, menghasilkan harmoni rasa kopi yang bold dengan sentuhan mentega karamel yang creamy.',
                'id_admin' => 1
            ],
            [
                'product_name' => 'Salted Caramel Latte',
                'product_image' => 'product-images/salted-caramel.jpg',
                'product_quantity' => 20,
                'product_price' => 10000,
                'product_description'
                => 'Minuman kopi susu dengan caramel sauce premium dan sedikit garam laut, menciptakan paduan sempurna antara manis, gurih, dan creamy.',
                'id_admin' => 1
            ]
        ]);
    }
}
