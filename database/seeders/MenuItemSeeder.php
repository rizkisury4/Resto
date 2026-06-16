<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuItem;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Nasi Goreng', 'description' => 'Nasi goreng spesial', 'price' => 25000],
            ['name' => 'Mie Goreng', 'description' => 'Mie goreng lezat', 'price' => 22000],
            ['name' => 'Nasi Ayam Goreng', 'description' => 'Ayam goreng kremes', 'price' => 29000],
            ['name' => 'Ayam Bakar', 'description' => 'Ayam bakar bumbu manis', 'price' => 32000],
            ['name' => 'Ayam Geprek', 'description' => 'Ayam geprek pedas', 'price' => 28000],
            ['name' => 'Sate Ayam', 'description' => 'Sate ayam bumbu kacang', 'price' => 26000],
        ];

        foreach ($items as $it) {
            MenuItem::updateOrCreate(['name' => $it['name']], $it + ['is_active' => true]);
        }
    }
}
