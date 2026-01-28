<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrator',
                'email' => 'admin@metinca.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => true,
            ],
            [
                'name' => 'PPIC Staff',
                'email' => 'ppic@metinca.com',
                'password' => Hash::make('ppic123'),
                'role' => 'ppic',
                'is_active' => true,
            ],
            [
                'name' => 'Warehouse Staff',
                'email' => 'warehouse@metinca.com',
                'password' => Hash::make('warehouse123'),
                'role' => 'warehouse',
                'is_active' => true,
            ],
            [
                'name' => 'Quality Staff',
                'email' => 'quality@metinca.com',
                'password' => Hash::make('quality123'),
                'role' => 'quality',
                'is_active' => true,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        $this->command->info('✅ Role-based users created successfully!');
        $this->command->table(
            ['Email', 'Password', 'Role'],
            [
                ['admin@metinca.com', 'admin123', 'Administrator'],
                ['ppic@metinca.com', 'ppic123', 'PPIC'],
                ['warehouse@metinca.com', 'warehouse123', 'Warehouse'],
                ['quality@metinca.com', 'quality123', 'Quality'],
            ]
        );
    }
}
