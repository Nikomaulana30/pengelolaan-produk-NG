<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterLokasi;

class MasterLokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lokasi = [
            [
                'kode_lokasi' => 'LOK001',
                'nama_lokasi' => 'Rak A1 - Zona A',
                'zona_gudang' => 'zona_a',
                'rack' => 'A1',
                'bin' => '01',
                'tipe_lokasi' => 'regular',
                'status_lokasi' => 'available',
                'kapasitas_maksimal' => 500,
                'deskripsi' => 'Lokasi penyimpanan regular untuk elektronik umum',
                'is_active' => true,
            ],
            [
                'kode_lokasi' => 'LOK002',
                'nama_lokasi' => 'Rak B2 - Zona B',
                'zona_gudang' => 'zona_b',
                'rack' => 'B2',
                'bin' => '02',
                'tipe_lokasi' => 'regular',
                'status_lokasi' => 'available',
                'kapasitas_maksimal' => 500,
                'deskripsi' => 'Lokasi penyimpanan regular zona B',
                'is_active' => true,
            ],
            [
                'kode_lokasi' => 'LOK003',
                'nama_lokasi' => 'Area Quarantine - Zona C',
                'zona_gudang' => 'zona_c',
                'rack' => 'QA',
                'bin' => '01',
                'tipe_lokasi' => 'karantina',
                'status_lokasi' => 'available',
                'kapasitas_maksimal' => 200,
                'deskripsi' => 'Area quarantine untuk produk yang perlu inspeksi',
                'is_active' => true,
            ],
            [
                'kode_lokasi' => 'LOK004',
                'nama_lokasi' => 'Rak D1 - Zona D',
                'zona_gudang' => 'zona_d',
                'rack' => 'D1',
                'bin' => '03',
                'tipe_lokasi' => 'regular',
                'status_lokasi' => 'available',
                'kapasitas_maksimal' => 500,
                'deskripsi' => 'Lokasi penyimpanan regular zona D',
                'is_active' => true,
            ],
        ];

        foreach ($lokasi as $l) {
            MasterLokasi::updateOrCreate(
                ['kode_lokasi' => $l['kode_lokasi']],
                $l
            );
            $this->command->line("✓ Created/Updated: {$l['nama_lokasi']}");
        }

        $this->command->info('Master Lokasi seeder selesai!');
    }
}
