<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin user for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking existing users...');
        $userCount = User::count();
        $this->info("Total users: {$userCount}");

        if ($userCount > 0) {
            $this->info("\nExisting users:");
            foreach (User::all() as $user) {
                $this->line("- {$user->email} (Role: {$user->role})");
            }
        }

        $this->info("\nCreating admin user...");
        
        $user = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $this->info("\n✅ Admin user created/updated successfully!");
        $this->line("Email: admin@example.com");
        $this->line("Password: password123");
        $this->line("Role: admin");

        return 0;
    }
}
