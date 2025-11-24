<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeederOld extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            GenreSeeder::class,
            HargaTiketSeeder::class,
        ]);
    }
}