<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Setting;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // One Admin User
        User::factory()->create([
            'name' => 'Admin Invoice',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
        ]);

        // Default Shop Settings
        Setting::create([
            'shop_name' => 'Invoice Vola Printing',
            'shop_address' => 'Jl. Contoh No. 123, Kota Sini',
            'shop_phone' => '0812-3456-7890',
            'footer_note' => 'Barang yang sudah dibeli tidak dapat ditukar kecuali cacat produksi.',
        ]);
    }
}
