<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterProduk;

class MasterProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produk = [
            [
                'kode_produk' => 'PRD001',
                'nama_produk' => 'Resistor 10K',
                'unit' => 'Pcs',
                'kategori' => 'raw_material',
                'spesifikasi' => 'Resistor dengan nilai 10 kilo ohm, toleransi 5%',
                'harga' => 500,
                'is_active' => true,
            ],
            [
                'kode_produk' => 'PRD002',
                'nama_produk' => 'Kapasitor 100µF',
                'unit' => 'Pcs',
                'kategori' => 'raw_material',
                'spesifikasi' => 'Kapasitor elektrolitik 100 mikrofarad, 25V',
                'harga' => 1500,
                'is_active' => true,
            ],
            [
                'kode_produk' => 'PRD003',
                'nama_produk' => 'LED Merah 5mm',
                'unit' => 'Pcs',
                'kategori' => 'raw_material',
                'spesifikasi' => 'LED merah dengan standar 5mm, brightness tinggi',
                'harga' => 2000,
                'is_active' => true,
            ],
            [
                'kode_produk' => 'PRD004',
                'nama_produk' => 'Transistor NPN 2N2222',
                'unit' => 'Pcs',
                'kategori' => 'raw_material',
                'spesifikasi' => 'Transistor NPN TO-92 package',
                'harga' => 3000,
                'is_active' => true,
            ],
            [
                'kode_produk' => 'PRD005',
                'nama_produk' => 'IC Op-Amp LM358',
                'unit' => 'Pcs',
                'kategori' => 'raw_material',
                'spesifikasi' => 'Operational amplifier dual channel',
                'harga' => 5000,
                'is_active' => true,
            ],
        ];

        foreach ($produk as $p) {
            MasterProduk::updateOrCreate(
                ['kode_produk' => $p['kode_produk']],
                $p
            );
            $this->command->line("✓ Created/Updated: {$p['nama_produk']}");
        }

        $this->command->info('Master Produk seeder selesai!');
    }
}
