<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ReturnWorkflowUsersSeeder extends Seeder
{
    public function run()
    {
        // Staff Export Import
        User::updateOrCreate(
            ['email' => 'staff.exim@metinca.local'],
            [
                'name' => 'Ahmad Staff Export Import',
                'role' => 'staff_exim',
                'password' => Hash::make('password123'),
                'is_active' => true,
                'phone' => '081234567890'
            ]
        );

        // Warehouse Staff
        User::updateOrCreate(
            ['email' => 'warehouse.staff@metinca.local'],
            [
                'name' => 'Sari Warehouse Staff',
                'role' => 'warehouse_staff',
                'password' => Hash::make('password123'),
                'is_active' => true,
                'phone' => '081234567891'
            ]
        );

        // Quality Manager
        User::updateOrCreate(
            ['email' => 'quality.manager@metinca.local'],
            [
                'name' => 'Dr. Bambang Quality Manager',
                'role' => 'quality_manager',
                'password' => Hash::make('password123'),
                'is_active' => true,
                'phone' => '081234567892'
            ]
        );

        // Production Manager
        User::updateOrCreate(
            ['email' => 'production.manager@metinca.local'],
            [
                'name' => 'Joko Production Manager',
                'role' => 'production_manager',
                'password' => Hash::make('password123'),
                'is_active' => true,
                'phone' => '081234567893'
            ]
        );

        $this->command->info('✓ Return workflow users created successfully');
    }
}