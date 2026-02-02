<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create main admin user
        $adminMetincaCom = User::where('email', 'admin@metinca.com')->first();
        if (!$adminMetincaCom) {
            User::create([
                'name' => 'Super Administrator',
                'email' => 'admin@metinca.com',
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]);
            
            $this->command->info('✓ Super Admin user created');
            $this->command->line('Email: admin@metinca.com');
            $this->command->line('Password: admin123');
        } else {
            $this->command->warn('✓ Admin user already exists (admin@metinca.com)');
        }

        // Keep local admin for development
        $adminLocal = User::where('email', 'admin@metinca.local')->first();
        if (!$adminLocal) {
            User::create([
                'name' => 'Administrator',
                'email' => 'admin@metinca.local',
                'email_verified_at' => now(),
                'password' => Hash::make('admin123456'),
                'role' => 'admin',
            ]);
            
            $this->command->info('✓ Local Admin user created');
            $this->command->line('Email: admin@metinca.local');
            $this->command->line('Password: admin123456');
        } else {
            $this->command->warn('✓ Local Admin user already exists');
        }
    }
}
