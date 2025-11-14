<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin Q',
                'email' => 'q@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Admin App',
                'email' => 'admin@app.com',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ],

            [
                'name' => 'Kasir W',
                'email' => 'w@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'kasir',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Kasir App',
                'email' => 'kasir@app.com',
                'password' => Hash::make('12345678'),
                'role' => 'kasir',
                'email_verified_at' => now(),
            ],

            [
                'name' => 'Pelanggan A',
                'email' => 'a@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'pelanggan',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Customer App',
                'email' => 'cust@app.com',
                'password' => Hash::make('12345678'),
                'role' => 'pelanggan',
                'email_verified_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);
    }
}
