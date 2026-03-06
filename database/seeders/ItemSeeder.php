<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Buku Tulis A5',          'stock' => 120, 'category' => 'Alat Tulis',      'description' => 'Buku tulis ukuran A5, 58 lembar'],
            ['name' => 'Pulpen Hitam',            'stock' => 8,   'category' => 'Alat Tulis',      'description' => 'Pulpen tinta hitam refillable'],
            ['name' => 'Laptop Asus VivoBook',    'stock' => 5,   'category' => 'Elektronik',      'description' => 'Laptop 14 inch, RAM 8GB, SSD 512GB'],
            ['name' => 'Mouse Wireless',          'stock' => 23,  'category' => 'Elektronik',      'description' => 'Mouse wireless 2.4GHz'],
            ['name' => 'Keyboard Mechanical',     'stock' => 0,   'category' => 'Elektronik',      'description' => 'Keyboard mechanical TKL'],
            ['name' => 'Air Mineral 600ml',       'stock' => 200, 'category' => 'Minuman',         'description' => 'Air mineral botol 600ml'],
            ['name' => 'Kopi Sachet Arabika',     'stock' => 75,  'category' => 'Minuman',         'description' => 'Kopi sachet arabika premium'],
            ['name' => 'Teh Celup Green Tea',     'stock' => 3,   'category' => 'Minuman',         'description' => 'Teh celup green tea, 25 pcs/box'],
            ['name' => 'Snack Wafer Coklat',      'stock' => 0,   'category' => 'Makanan',         'description' => 'Wafer berlapis coklat 100g'],
            ['name' => 'Beras Premium 5kg',       'stock' => 40,  'category' => 'Makanan',         'description' => 'Beras putih premium 5kg'],
            ['name' => 'Kaos Polo Pria',          'stock' => 60,  'category' => 'Pakaian',         'description' => 'Kaos polo bahan lacoste pique'],
            ['name' => 'Celana Jeans Slim',       'stock' => 7,   'category' => 'Pakaian',         'description' => 'Celana jeans slim fit'],
            ['name' => 'Sapu Ijuk',               'stock' => 15,  'category' => 'Peralatan Rumah', 'description' => 'Sapu ijuk tangkai panjang'],
            ['name' => 'Ember Plastik 20L',       'stock' => 30,  'category' => 'Peralatan Rumah', 'description' => 'Ember plastik kapasitas 20 liter'],
            ['name' => 'Paracetamol 500mg',       'stock' => 0,   'category' => 'Obat-obatan',     'description' => 'Tablet paracetamol 500mg, 10 blister'],
            ['name' => 'Vitamin C 1000mg',        'stock' => 45,  'category' => 'Obat-obatan',     'description' => 'Effervescent vitamin C 1000mg'],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}