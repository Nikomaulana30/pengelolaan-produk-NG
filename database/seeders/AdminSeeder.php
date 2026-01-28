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
        // Cek apakah admin sudah ada
        $adminExists = User::where('email', 'admin@metinca.local')->exists();
        
        if (!$adminExists) {
            User::create([
                'name' => 'Administrator',
                'email' => 'admin@metinca.local',
                'email_verified_at' => now(),
                'password' => Hash::make('admin123456'),
            ]);
            
            $this->command->info('✓ Admin user berhasil dibuat');
            $this->command->line('Email: admin@metinca.local');
            $this->command->line('Password: admin123456');
        } else {
            $this->command->warn('✓ Admin user sudah ada');
        }
    }
}
