<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuItem;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Nasi Goreng',      'description' => 'Nasi goreng spesial kencur dan kemangi', 'price' => 25000, 'image_path' => 'images/nasi-goreng-kencur-kemangi.jpg'],
            ['name' => 'Mie Goreng',       'description' => 'Mie goreng saus tiram lezat',            'price' => 22000, 'image_path' => 'images/mie-goreng-saus-tiram.jpg'],
            ['name' => 'Nasi Ayam Goreng', 'description' => 'Ayam goreng kremes dengan nasi hangat',  'price' => 29000, 'image_path' => null],
            ['name' => 'Ayam Bakar',       'description' => 'Ayam bakar bumbu manis panggang',        'price' => 32000, 'image_path' => 'images/ayam-panggang.jpg'],
            ['name' => 'Ayam Geprek',      'description' => 'Ayam geprek sambel pedas mantap',        'price' => 28000, 'image_path' => 'images/ayam-geprek.webp'],
            ['name' => 'Sate Ayam',        'description' => 'Sate ayam bumbu kacang gurih',           'price' => 26000, 'image_path' => 'images/sate-ayam.jpg'],
        ];

        foreach ($items as $it) {
            MenuItem::updateOrCreate(['name' => $it['name']], $it + ['is_active' => true]);
        }
    }
}
