<?php

namespace Database\Seeders;

use App\Models\Merchant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Merchant::insert([
            [
                'merchant_name' => 'Merchant Tester I',
                'phone_number' => '08217482189',
                'id_account' => 2
            ],
            [
                'merchant_name' => 'Merchant Tester II',
                'phone_number' => '01743812923',
                'id_account' => 3
            ]
        ]);
    }
}
