<?php

namespace Database\Seeders;

use App\Models\MasterVendor;
use App\Models\MasterProduk;
use Illuminate\Database\Seeder;

class ReturBarangTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test vendors
        $vendors = [
            [
                'kode_vendor' => 'V001',
                'nama_vendor' => 'PT. Supplier Terpercaya',
                'alamat_vendor' => 'Jl. Industri No. 123',
                'kota' => 'Jakarta',
                'provinsi' => 'DKI Jakarta',
                'kode_pos' => '12345',
                'telepon' => '021-1234567',
                'email' => 'info@supplier1.com',
                'person_in_charge' => 'Budi Santoso',
                'kebijakan_retur' => 'retur_fisik',
                'deskripsi' => 'Supplier terpercaya untuk komponen industri',
                'is_active' => true,
            ],
            [
                'kode_vendor' => 'V002',
                'nama_vendor' => 'CV. Distributor Elektronik',
                'alamat_vendor' => 'Jl. Perdagangan No. 456',
                'kota' => 'Surabaya',
                'provinsi' => 'Jawa Timur',
                'kode_pos' => '60123',
                'telepon' => '031-2345678',
                'email' => 'cs@distributor2.com',
                'person_in_charge' => 'Ahmad Wijaya',
                'kebijakan_retur' => 'debit_note',
                'deskripsi' => 'Distributor elektronik dan perangkat',
                'is_active' => true,
            ],
            [
                'kode_vendor' => 'V003',
                'nama_vendor' => 'PT. Pabrik Komponen',
                'alamat_vendor' => 'Jl. Manufaktur No. 789',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'kode_pos' => '40235',
                'telepon' => '022-3456789',
                'email' => 'sales@pabrik3.com',
                'person_in_charge' => 'Siti Rahmayana',
                'kebijakan_retur' => 'keduanya',
                'deskripsi' => 'Pabrik komponen berkualitas tinggi',
                'is_active' => true,
            ],
        ];

        foreach ($vendors as $vendor) {
            MasterVendor::updateOrCreate(
                ['kode_vendor' => $vendor['kode_vendor']],
                $vendor
            );
        }

        // Verify products exist and are active
        $produks = MasterProduk::where('is_active', true)->get();
        
        echo "\n✅ Seeder Completed!\n";
        echo "   - Vendors created: " . count($vendors) . "\n";
        echo "   - Active vendors: " . MasterVendor::where('is_active', true)->count() . "\n";
        echo "   - Active products: " . $produks->count() . "\n";
        echo "\n📝 Ready to test Retur Barang CRUD operations!\n";
    }
}
