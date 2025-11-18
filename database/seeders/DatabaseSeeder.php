<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

use App\Models\User;
use App\Models\Genre;
use App\Models\Sutradara;
use App\Models\Film;
use App\Models\JadwalTayang;
use App\Models\Pemesanan;
use App\Models\DetailPemesanan;
use App\Models\Kursi;
use App\Models\Studio;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeder khusus yang sudah kamu punya → TIDAK DIHAPUS
        $this->call(UserSeeder::class);
        $this->call(HargaTiketSeeder::class);

        // Faker Indonesia
        $faker = Faker::create('id_ID');

        $genres = [
            'Action',
            'Adventure',
            'Comedy',
            'Drama',
            'Horror',
            'Romance',
            'Sci-Fi',
            'Thriller',
            'Animation',
            'Fantasy'
        ];

        foreach ($genres as $g) {
            Genre::create(['nama_genre' => $g]);
        }


        $sutradaraIds = [];
        for ($i = 0; $i < 10; $i++) {
            $sutradara = Sutradara::create([
                'nama_sutradara' => $faker->name(),
                'foto_profil' => null,
                'biografi' => $faker->text(200),
            ]);

            $sutradaraIds[] = $sutradara->id;
        }


        $filmIds = [];
        for ($i = 0; $i < 10; $i++) {
            $film = Film::create([
                'sutradara_id' => $faker->randomElement($sutradaraIds),
                'judul' => $faker->sentence(3),
                'durasi' => $faker->numberBetween(90, 180),
                'sinopsis' => $faker->text(300),
                'poster' => null,
                'rating' => $faker->randomElement(['SU', 'R13+', 'R17+', 'D21+']),
                'tahun_rilis' => $faker->year(),
                'status' => $faker->randomElement(['tayang', 'segera', 'selesai']),
            ]);

            $filmIds[] = $film->id;
        }

        $genreIds = Genre::pluck('id')->toArray();

        foreach ($filmIds as $fid) {
            $randomGenres = array_rand($genreIds, rand(1, 3));
            if (!is_array($randomGenres)) $randomGenres = [$randomGenres];

            foreach ($randomGenres as $idx) {
                \DB::table('film_genre')->insert([
                    'film_id' => $fid,
                    'genre_id' => $genreIds[$idx],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }


    }
}
