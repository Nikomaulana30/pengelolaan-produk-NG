<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterCustomer;
use App\Models\MasterPriorityLevel;
use App\Models\MasterJenisComplaint;
use App\Models\MasterKondisiBarang;
use App\Models\MasterJenisRetur;
use App\Models\MasterDefectType;
use App\Models\MasterRcaCategory;
use App\Models\MasterDisposisi;
use App\Models\MasterVendor;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Master Customers
        $customers = [
            [
                'kode_customer' => 'TOYOTA01',
                'nama_customer' => 'Toyota Motor Indonesia',
                'email_customer' => 'procurement@toyota.co.id',
                'telepon_customer' => '021-5555-1001',
                'alamat_customer' => 'Jl. Yos Sudarso, Sunter, Jakarta Utara',
                'kategori_customer' => 'vip',
                'payment_terms' => '30_days',
                'credit_limit' => 5000000000,
                'contact_person' => 'Budi Santoso',
                'phone_contact_person' => '0812-3456-7890'
            ],
            [
                'kode_customer' => 'HONDA01',
                'nama_customer' => 'Honda Prospect Motor',
                'email_customer' => 'purchasing@honda.co.id',
                'telepon_customer' => '021-5555-1002',
                'alamat_customer' => 'Jl. Laksda Yos Sudarso, Sunter, Jakarta Utara',
                'kategori_customer' => 'vip',
                'payment_terms' => '30_days',
                'credit_limit' => 3000000000,
                'contact_person' => 'Sari Indah',
                'phone_contact_person' => '0813-7890-1234'
            ],
            [
                'kode_customer' => 'SUZUKI01',
                'nama_customer' => 'Suzuki Indomobil Motor',
                'email_customer' => 'vendor@suzuki.co.id',
                'telepon_customer' => '021-5555-1003',
                'alamat_customer' => 'Jl. Danau Sunter Utara, Jakarta Utara',
                'kategori_customer' => 'regular',
                'payment_terms' => '45_days',
                'credit_limit' => 2000000000,
                'contact_person' => 'Ahmad Rahman',
                'phone_contact_person' => '0814-5678-9012'
            ]
        ];

        foreach ($customers as $customer) {
            MasterCustomer::firstOrCreate(['kode_customer' => $customer['kode_customer']], $customer);
        }

        // Seed Priority Levels
        $priorities = [
            [
                'kode_priority' => 'URGENT',
                'nama_priority' => 'Urgent',
                'deskripsi' => 'Critical issue requiring immediate attention',
                'sla_hours' => 4,
                'color_code' => '#dc3545',
                'sort_order' => 1
            ],
            [
                'kode_priority' => 'HIGH',
                'nama_priority' => 'High',
                'deskripsi' => 'High priority issue',
                'sla_hours' => 24,
                'color_code' => '#fd7e14',
                'sort_order' => 2
            ],
            [
                'kode_priority' => 'MEDIUM',
                'nama_priority' => 'Medium',
                'deskripsi' => 'Medium priority issue',
                'sla_hours' => 72,
                'color_code' => '#ffc107',
                'sort_order' => 3
            ],
            [
                'kode_priority' => 'LOW',
                'nama_priority' => 'Low',
                'deskripsi' => 'Low priority issue',
                'sla_hours' => 168,
                'color_code' => '#28a745',
                'sort_order' => 4
            ]
        ];

        foreach ($priorities as $priority) {
            MasterPriorityLevel::firstOrCreate(['kode_priority' => $priority['kode_priority']], $priority);
        }

        echo "✅ Master data seeded successfully!\n";
    }
}
