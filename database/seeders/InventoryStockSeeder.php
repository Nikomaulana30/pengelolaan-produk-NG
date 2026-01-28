<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InventoryStock;
use App\Models\MasterProduk;
use App\Models\MasterLokasi;

class InventoryStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some products and locations
        $products = MasterProduk::active()->take(5)->get();
        $locations = MasterLokasi::active()->take(4)->get();

        if ($products->isEmpty() || $locations->isEmpty()) {
            $this->command->warn('Tidak ada produk atau lokasi yang aktif. Seeder dihentikan.');
            return;
        }

        // Create inventory combinations
        $inventories = [
            // Produk 1: Di multiple lokasi
            ['product_idx' => 0, 'location_idx' => 0, 'quantity' => 100, 'reserved' => 20, 'status' => 'good'],
            ['product_idx' => 0, 'location_idx' => 1, 'quantity' => 50, 'reserved' => 10, 'status' => 'good'],
            ['product_idx' => 0, 'location_idx' => 2, 'quantity' => 30, 'reserved' => 5, 'status' => 'quarantine'],

            // Produk 2: Di multiple lokasi
            ['product_idx' => 1, 'location_idx' => 0, 'quantity' => 75, 'reserved' => 15, 'status' => 'good'],
            ['product_idx' => 1, 'location_idx' => 1, 'quantity' => 40, 'reserved' => 8, 'status' => 'good'],
            ['product_idx' => 1, 'location_idx' => 3, 'quantity' => 20, 'reserved' => 0, 'status' => 'quarantine'],

            // Produk 3: Di multiple lokasi
            ['product_idx' => 2, 'location_idx' => 1, 'quantity' => 200, 'reserved' => 50, 'status' => 'good'],
            ['product_idx' => 2, 'location_idx' => 2, 'quantity' => 150, 'reserved' => 30, 'status' => 'good'],

            // Produk 4: Di satu lokasi
            ['product_idx' => 3, 'location_idx' => 0, 'quantity' => 80, 'reserved' => 10, 'status' => 'good'],

            // Produk 5: Di multiple lokasi dengan status bervariasi
            ['product_idx' => 4, 'location_idx' => 2, 'quantity' => 60, 'reserved' => 20, 'status' => 'good'],
            ['product_idx' => 4, 'location_idx' => 3, 'quantity' => 25, 'reserved' => 0, 'status' => 'quarantine'],
        ];

        foreach ($inventories as $inv) {
            $product = $products->get($inv['product_idx']);
            $location = $locations->get($inv['location_idx']);

            if ($product && $location) {
                InventoryStock::create([
                    'product_id' => $product->id,
                    'location_id' => $location->id,
                    'quantity' => $inv['quantity'],
                    'reserved_quantity' => $inv['reserved'],
                    'status' => $inv['status'],
                    'notes' => 'Seeded data - ' . $inv['status'] . ' status',
                ]);

                $this->command->line("✓ Created inventory: {$product->nama_barang} di {$location->nama_lokasi} ({$inv['quantity']} unit)");
            }
        }

        $this->command->info('Inventory Stock seeder selesai!');
    }
}
