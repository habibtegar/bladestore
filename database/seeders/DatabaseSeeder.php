<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $customer = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Demo Customer',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );
        if ($customer->role !== 'customer') {
            $customer->update(['role' => 'customer']);
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin BladeStore',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
        if ($admin->role !== 'admin') {
            $admin->update(['role' => 'admin']);
        }

        $this->call([
            ProductSeeder::class,
        ]);
    }
}
