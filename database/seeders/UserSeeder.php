<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Pengasuh (Super Admin)
        User::create([
            'name' => 'Admin',
            'email' => 'admin@al-jawahir.com',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'pengasuh',
            'phone' => '081234567890',
            'address' => 'Pondok Pesantren Salafiyah Al-Jawahir',
            'is_active' => true,
        ]);

        // Create Admin Staff
        User::create([
            'name' => 'Staff Admin',
            'email' => 'staff@al-jawahir.com',
            'username' => 'staff',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567891',
            'address' => 'Pondok Pesantren Salafiyah Al-Jawahir',
            'is_active' => true,
        ]);

        // Create Additional Admin
        User::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad@al-jawahir.com',
            'username' => 'ahmad',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567892',
            'address' => 'Pondok Pesantren Salafiyah Al-Jawahir',
            'is_active' => true,
        ]);
    }
}
