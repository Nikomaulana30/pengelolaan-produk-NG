<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\MasterProduk;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed admin user first
        $this->call([
            AdminSeeder::class,
        ]);

        // Call master data seeders
        $this->call([
            MasterProdukSeeder::class,
            MasterLokasiSeeder::class,
            InventoryStockSeeder::class,
        ]);

        // Seed Master Produk
        $this->seedMasterProduk();
    }

    /**
     * Seed Master Produk dengan data sample
     */
    private function seedMasterProduk(): void
    {
        $produks = [
            // Raw Material
            [
                'kode_produk' => 'RM-001',
                'nama_produk' => 'Steel Plate Grade A',
                'unit' => 'Pcs',
                'kategori' => 'raw_material',
                'spesifikasi' => 'Plat baja berkualitas tinggi untuk komponen utama',
                'harga' => 250000,
                'is_active' => true,
            ],
            [
                'kode_produk' => 'RM-002',
                'nama_produk' => 'Aluminum Coil',
                'unit' => 'Roll',
                'kategori' => 'raw_material',
                'spesifikasi' => 'Gulungan aluminium 1mm tebal untuk molding',
                'harga' => 500000,
                'is_active' => true,
            ],
            [
                'kode_produk' => 'RM-003',
                'nama_produk' => 'Brass Rod',
                'unit' => 'Kg',
                'kategori' => 'raw_material',
                'spesifikasi' => 'Batang kuningan untuk machining',
                'harga' => 85000,
                'is_active' => true,
            ],
            // WIP (Work In Progress)
            [
                'kode_produk' => 'WIP-001',
                'nama_produk' => 'Housing Assembly',
                'unit' => 'Pcs',
                'kategori' => 'wip',
                'spesifikasi' => 'Rakitan housing setengah jadi',
                'harga' => 450000,
                'is_active' => true,
            ],
            [
                'kode_produk' => 'WIP-002',
                'nama_produk' => 'PCB Assembly',
                'unit' => 'Pcs',
                'kategori' => 'wip',
                'spesifikasi' => 'Board PCB dengan komponen terpasang 90%',
                'harga' => 350000,
                'is_active' => true,
            ],
            [
                'kode_produk' => 'WIP-003',
                'nama_produk' => 'Machined Body',
                'unit' => 'Pcs',
                'kategori' => 'wip',
                'spesifikasi' => 'Body yang sudah dimachining, belum assembly',
                'harga' => 275000,
                'is_active' => true,
            ],
            // Finished Goods
            [
                'kode_produk' => 'FG-001',
                'nama_produk' => 'Control Unit Model A',
                'unit' => 'Pcs',
                'kategori' => 'finished_goods',
                'spesifikasi' => 'Unit kontrol akhir untuk sistem otomasi',
                'harga' => 1200000,
                'is_active' => true,
            ],
            [
                'kode_produk' => 'FG-002',
                'nama_produk' => 'Sensor Module',
                'unit' => 'Pcs',
                'kategori' => 'finished_goods',
                'spesifikasi' => 'Modul sensor digital dengan interface IoT',
                'harga' => 850000,
                'is_active' => true,
            ],
            [
                'kode_produk' => 'FG-003',
                'nama_produk' => 'Power Supply 24V',
                'unit' => 'Pcs',
                'kategori' => 'finished_goods',
                'spesifikasi' => 'Power supply industri 24V 10A dengan proteksi',
                'harga' => 650000,
                'is_active' => true,
            ],
            [
                'kode_produk' => 'FG-004',
                'nama_produk' => 'Communication Board',
                'unit' => 'Pcs',
                'kategori' => 'finished_goods',
                'spesifikasi' => 'Board komunikasi serial/Ethernet siap pakai',
                'harga' => 950000,
                'is_active' => true,
            ],
            // Old/Inactive product
            [
                'kode_produk' => 'FG-OLD-001',
                'nama_produk' => 'Legacy Control Panel',
                'unit' => 'Pcs',
                'kategori' => 'finished_goods',
                'spesifikasi' => 'Kontrol panel model lama (discontinued)',
                'harga' => 800000,
                'is_active' => false,
            ],
        ];

        foreach ($produks as $produk) {
            MasterProduk::create($produk);
        }
    }
}
