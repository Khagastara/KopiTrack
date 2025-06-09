<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Account::insert([
            [
                'username' => 'admin123',
                'password' => Hash::make('12345679'),
                'email' => 'admintester@gmail.com'
            ],
            [
                'username' => 'tester1',
                'password' => Hash::make('12345679'),
                'email' => 'tester1@gmail.com'
            ],
            [
                'username' => 'tester2',
                'password' => Hash::make('12345679'),
                'email' => 'tester2@gmail.com'
            ]
        ]);
    }
}
