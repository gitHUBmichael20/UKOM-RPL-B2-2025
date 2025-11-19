<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = [
            ['nama_genre' => 'Action'],
            ['nama_genre' => 'Adventure'],
            ['nama_genre' => 'Comedy'],
            ['nama_genre' => 'Drama'],
            ['nama_genre' => 'Fantasy'],
            ['nama_genre' => 'Horror'],
            ['nama_genre' => 'Mystery'],
            ['nama_genre' => 'Romance'],
            ['nama_genre' => 'Sci-Fi'],
            ['nama_genre' => 'Thriller'],
            ['nama_genre' => 'Animation'],
            ['nama_genre' => 'Documentary'],
            ['nama_genre' => 'Crime'],
            ['nama_genre' => 'Family'],
            ['nama_genre' => 'Musical'],
            ['nama_genre' => 'Historical'],
            ['nama_genre' => 'Biographical'],
            ['nama_genre' => 'Superhero'],
            ['nama_genre' => 'Western'],
            ['nama_genre' => 'War']
        ];

        foreach ($genres as $genre) {
            DB::table('genre')->insert([
                'nama_genre' => $genre['nama_genre'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
