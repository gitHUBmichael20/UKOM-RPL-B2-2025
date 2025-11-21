<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TheaterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data (except users)
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('detail_pemesanan')->truncate();
        DB::table('pemesanan')->truncate();
        DB::table('jadwal_tayang')->truncate();
        DB::table('kursi')->truncate();
        DB::table('harga_tiket')->truncate();
        DB::table('studio')->truncate();
        DB::table('film_genre')->truncate();
        DB::table('film')->truncate();
        DB::table('sutradara')->truncate();
        DB::table('genre')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Create storage directories
        $this->createDirectories();

        // Seed data in correct order
        $this->seedHargaTiket();
        $genreIds = $this->seedGenres();
        $sutradaraIds = $this->seedSutradara();
        $filmIds = $this->seedFilms($sutradaraIds);
        $this->seedFilmGenres($filmIds, $genreIds);
        $studioIds = $this->seedStudios();
        $this->seedKursi($studioIds);
        $jadwalTayangIds = $this->seedJadwalTayang($filmIds, $studioIds);
        $this->seedPemesanan($jadwalTayangIds);
    }

    private function createDirectories(): void
    {
        $directories = [
            'public/images/sutradara',
            'public/images/posters',
        ];

        foreach ($directories as $directory) {
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
        }
    }

    private function downloadImage($url, $filename, $folder): ?string
    {
        try {
            $imageContent = file_get_contents($url);
            if ($imageContent === false) {
                return null;
            }

            $publicPath = "images/{$folder}/{$filename}";
            $fullPath = public_path($publicPath);

            // Ensure directory exists
            $directory = dirname($fullPath);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            file_put_contents($fullPath, $imageContent);

            return "/{$publicPath}";
        } catch (\Exception $e) {
            return null;
        }
    }

    private function seedHargaTiket(): void
    {
        $hargaTiket = [
            ['tipe_studio' => 'regular', 'tipe_hari' => 'weekday', 'harga' => 35000.00],
            ['tipe_studio' => 'regular', 'tipe_hari' => 'weekend', 'harga' => 45000.00],
            ['tipe_studio' => 'deluxe', 'tipe_hari' => 'weekday', 'harga' => 55000.00],
            ['tipe_studio' => 'deluxe', 'tipe_hari' => 'weekend', 'harga' => 65000.00],
            ['tipe_studio' => 'imax', 'tipe_hari' => 'weekday', 'harga' => 75000.00],
            ['tipe_studio' => 'imax', 'tipe_hari' => 'weekend', 'harga' => 85000.00],
        ];

        DB::table('harga_tiket')->insert($hargaTiket);
    }

    private function seedGenres(): array
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

        $genreIds = [];
        foreach ($genres as $genre) {
            $id = DB::table('genre')->insertGetId([
                'nama_genre' => $genre['nama_genre'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $genreIds[] = $id;
        }

        return $genreIds;
    }

    private function seedSutradara(): array
    {
        $sutradaras = [
            [
                'nama_sutradara' => 'Christopher Nolan',
                'foto_profil' => 'https://picsum.photos/200/200?random=101',
                'biografi' => 'Christopher Nolan is a British-American film director known for his Hollywood blockbusters with complex storytelling.'
            ],
            [
                'nama_sutradara' => 'Steven Spielberg',
                'foto_profil' => 'https://picsum.photos/200/200?random=102',
                'biografi' => 'Steven Spielberg is an American film director, considered one of the founding pioneers of the New Hollywood era.'
            ],
            [
                'nama_sutradara' => 'Quentin Tarantino',
                'foto_profil' => 'https://picsum.photos/200/200?random=103',
                'biografi' => 'Quentin Tarantino is an American film director known for nonlinear storylines, dark humor, and stylized violence.'
            ],
            [
                'nama_sutradara' => 'Martin Scorsese',
                'foto_profil' => 'https://picsum.photos/200/200?random=104',
                'biografi' => 'Martin Scorsese is an American film director known for his contributions to the New Hollywood era.'
            ],
            [
                'nama_sutradara' => 'James Cameron',
                'foto_profil' => 'https://picsum.photos/200/200?random=105',
                'biografi' => 'James Cameron is a Canadian film director best known for making science fiction and epic films.'
            ],
            [
                'nama_sutradara' => 'Bong Joon-ho',
                'foto_profil' => 'https://picsum.photos/200/200?random=106',
                'biografi' => 'Bong Joon-ho is a South Korean film director who gained international acclaim for his genre-mixing films.'
            ],
            [
                'nama_sutradara' => 'Hayao Miyazaki',
                'foto_profil' => 'https://picsum.photos/200/200?random=107',
                'biografi' => 'Hayao Miyazaki is a Japanese animator and co-founder of Studio Ghibli.'
            ],
            [
                'nama_sutradara' => 'Denis Villeneuve',
                'foto_profil' => 'https://picsum.photos/200/200?random=108',
                'biografi' => 'Denis Villeneuve is a Canadian film director known for his atmospheric and visually striking films.'
            ]
        ];

        $sutradaraIds = [];
        foreach ($sutradaras as $sutradara) {
            $id = DB::table('sutradara')->insertGetId([
                'nama_sutradara' => $sutradara['nama_sutradara'],
                'foto_profil' => $sutradara['foto_profil'],
                'biografi' => $sutradara['biografi'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $sutradaraIds[] = $id;
        }

        return $sutradaraIds;
    }

    private function seedFilms(array $sutradaraIds): array
    {
        $films = [
            [
                'judul' => 'Inception',
                'durasi' => 148,
                'sinopsis' => 'A thief who steals corporate secrets through dream-sharing technology is given the inverse task of planting an idea into the mind of a C.E.O.',
                'rating' => 'R13+',
                'tahun_rilis' => 2010,
                'status' => 'tayang'
            ],
            [
                'judul' => 'The Dark Knight',
                'durasi' => 152,
                'sinopsis' => 'Batman must accept one of the greatest psychological tests when the Joker wreaks havoc on Gotham.',
                'rating' => 'R13+',
                'tahun_rilis' => 2008,
                'status' => 'tayang'
            ],
            [
                'judul' => 'Parasite',
                'durasi' => 132,
                'sinopsis' => 'Greed and class discrimination threaten the relationship between the wealthy Park family and the destitute Kim clan.',
                'rating' => 'R17+',
                'tahun_rilis' => 2019,
                'status' => 'tayang'
            ],
            [
                'judul' => 'Spirited Away',
                'durasi' => 125,
                'sinopsis' => 'A young girl wanders into a world of gods, witches, and spirits where humans are changed into beasts.',
                'rating' => 'SU',
                'tahun_rilis' => 2001,
                'status' => 'tayang'
            ],
            [
                'judul' => 'Dune: Part Two',
                'durasi' => 166,
                'sinopsis' => 'Paul Atreides continues his journey on Arrakis, uniting with the Fremen to fight against the Harkonnens.',
                'rating' => 'R13+',
                'tahun_rilis' => 2024,
                'status' => 'tayang'
            ],
            [
                'judul' => 'Oppenheimer',
                'durasi' => 180,
                'sinopsis' => 'The story of American scientist J. Robert Oppenheimer and his role in the development of the atomic bomb.',
                'rating' => 'R17+',
                'tahun_rilis' => 2023,
                'status' => 'tayang'
            ],
            [
                'judul' => 'The Marvels',
                'durasi' => 105,
                'sinopsis' => 'Carol Danvers teams up with two other superheroes to save the universe.',
                'rating' => 'R13+',
                'tahun_rilis' => 2023,
                'status' => 'tayang'
            ],
            [
                'judul' => 'Wonka',
                'durasi' => 116,
                'sinopsis' => 'The story of how Willy Wonka became the world\'s greatest inventor and chocolatier.',
                'rating' => 'SU',
                'tahun_rilis' => 2023,
                'status' => 'tayang'
            ],
            [
                'judul' => 'Mission: Impossible 7',
                'durasi' => 163,
                'sinopsis' => 'Ethan Hunt and his IMF team must track down a terrifying new weapon that threatens humanity.',
                'rating' => 'R13+',
                'tahun_rilis' => 2023,
                'status' => 'tayang'
            ],
            [
                'judul' => 'Avatar: The Way of Water',
                'durasi' => 192,
                'sinopsis' => 'Jake Sully lives with his newfound family on Pandora, but a new threat forces him to protect his home.',
                'rating' => 'R13+',
                'tahun_rilis' => 2022,
                'status' => 'tayang'
            ],
            [
                'judul' => 'John Wick: Chapter 4',
                'durasi' => 169,
                'sinopsis' => 'John Wick uncovers a path to defeating The High Table, but must face new enemies with powerful alliances.',
                'rating' => 'R17+',
                'tahun_rilis' => 2023,
                'status' => 'tayang'
            ],
            [
                'judul' => 'Spider-Man: Across the Spider-Verse',
                'durasi' => 140,
                'sinopsis' => 'Miles Morales catapults across the Multiverse, where he encounters a team of Spider-People.',
                'rating' => 'SU',
                'tahun_rilis' => 2023,
                'status' => 'tayang'
            ]
        ];

        $filmIds = [];
        foreach ($films as $index => $film) {
            // Download and store movie poster
            $posterPath = $this->downloadImage(
                "https://picsum.photos/400/600?random=20" . ($index + 1),
                "poster_" . ($index + 1) . ".jpg",
                "posters"
            );

            $id = DB::table('film')->insertGetId([
                'sutradara_id' => $sutradaraIds[array_rand($sutradaraIds)],
                'judul' => $film['judul'],
                'durasi' => $film['durasi'],
                'sinopsis' => $film['sinopsis'],
                'poster' => $posterPath,
                'rating' => $film['rating'],
                'tahun_rilis' => $film['tahun_rilis'],
                'status' => $film['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $filmIds[] = $id;
        }

        return $filmIds;
    }

    private function seedFilmGenres(array $filmIds, array $genreIds): void
    {
        $filmGenreMappings = [
            // Action films
            0 => [0, 17, 4],   // Inception: Action, Superhero, Fantasy
            1 => [0, 17, 12],  // Dark Knight: Action, Superhero, Crime
            4 => [0, 8, 4],    // Dune 2: Action, Sci-Fi, Fantasy
            8 => [0, 1, 12],   // Mission Impossible: Action, Adventure, Crime
            9 => [0, 1, 8],    // Avatar 2: Action, Adventure, Sci-Fi
            10 => [0, 12, 17], // John Wick: Action, Crime, Superhero

            // Animation/Family
            3 => [10, 4, 13],  // Spirited Away: Animation, Fantasy, Family
            11 => [10, 17, 1], // Spider-Verse: Animation, Superhero, Adventure

            // Drama/Thriller
            2 => [3, 6, 9],    // Parasite: Drama, Mystery, Thriller
            5 => [3, 16, 9],   // Oppenheimer: Drama, Biographical, Thriller

            // Adventure/Fantasy
            6 => [1, 17, 8],   // The Marvels: Adventure, Superhero, Sci-Fi
            7 => [3, 4, 13],   // Wonka: Drama, Fantasy, Family
        ];

        $filmGenres = [];
        foreach ($filmIds as $index => $filmId) {
            if (isset($filmGenreMappings[$index])) {
                $selectedGenres = $filmGenreMappings[$index];
            } else {
                // Fallback: assign 2-3 random genres
                $selectedGenres = array_rand($genreIds, rand(2, 3));
                if (!is_array($selectedGenres)) {
                    $selectedGenres = [$selectedGenres];
                }
            }

            foreach ($selectedGenres as $genreIndex) {
                $filmGenres[] = [
                    'film_id' => $filmId,
                    'genre_id' => $genreIds[$genreIndex],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('film_genre')->insert($filmGenres);
    }

    private function seedStudios(): array
    {
        $studios = [
            ['nama_studio' => 'Studio 1', 'kapasitas_kursi' => 120, 'tipe_studio' => 'regular'],
            ['nama_studio' => 'Studio 2', 'kapasitas_kursi' => 100, 'tipe_studio' => 'regular'],
            ['nama_studio' => 'Studio 3', 'kapasitas_kursi' => 80, 'tipe_studio' => 'deluxe'],
            ['nama_studio' => 'Studio 4', 'kapasitas_kursi' => 60, 'tipe_studio' => 'deluxe'],
            ['nama_studio' => 'Studio IMAX', 'kapasitas_kursi' => 150, 'tipe_studio' => 'imax'],
        ];

        $studioIds = [];
        foreach ($studios as $studio) {
            $id = DB::table('studio')->insertGetId([
                'nama_studio' => $studio['nama_studio'],
                'kapasitas_kursi' => $studio['kapasitas_kursi'],
                'tipe_studio' => $studio['tipe_studio'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $studioIds[] = $id;
        }

        return $studioIds;
    }

    private function seedKursi(array $studioIds): void
    {
        $kursiData = [];

        foreach ($studioIds as $studioId) {
            $studio = DB::table('studio')->where('id', $studioId)->first();
            $kapasitas = $studio->kapasitas_kursi;

            $rows = range('A', 'Z');
            $currentRow = 0;
            $seatNumber = 1;

            for ($i = 1; $i <= $kapasitas; $i++) {
                $row = $rows[$currentRow];
                $nomor_kursi = $row . sprintf('%02d', $seatNumber);

                $kursiData[] = [
                    'studio_id' => $studioId,
                    'nomor_kursi' => $nomor_kursi,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $seatNumber++;
                if ($seatNumber > 10) { // 10 seats per row
                    $seatNumber = 1;
                    $currentRow++;
                }
            }
        }

        // Insert in chunks to avoid memory issues
        foreach (array_chunk($kursiData, 100) as $chunk) {
            DB::table('kursi')->insert($chunk);
        }
    }

    private function seedJadwalTayang(array $filmIds, array $studioIds): array
    {
        $jadwalIds = [];

        // Create schedules for the next 14 days
        for ($day = 0; $day < 14; $day++) {
            $date = Carbon::now()->addDays($day);

            foreach ($filmIds as $filmId) {
                // Each film gets 2-4 screenings per day
                $numScreenings = rand(2, 4);
                $screeningTimes = [];

                // Generate unique screening times for this film
                for ($i = 0; $i < $numScreenings; $i++) {
                    do {
                        $hour = rand(10, 22); // Between 10 AM and 10 PM
                        $minute = [0, 15, 30, 45][rand(0, 3)];
                        $time = sprintf('%02d:%02d:00', $hour, $minute);
                    } while (in_array($time, $screeningTimes));

                    $screeningTimes[] = $time;

                    $id = DB::table('jadwal_tayang')->insertGetId([
                        'film_id' => $filmId,
                        'studio_id' => $studioIds[array_rand($studioIds)],
                        'tanggal_tayang' => $date->format('Y-m-d'),
                        'jam_tayang' => $time,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $jadwalIds[] = $id;
                }
            }
        }

        return $jadwalIds;
    }

    private function seedPemesanan(array $jadwalTayangIds): void
    {
        // Get user IDs for customers and cashiers
        $pelangganIds = DB::table('users')->where('role', 'pelanggan')->pluck('id')->toArray();
        $kasirIds = DB::table('users')->where('role', 'kasir')->pluck('id')->toArray();

        if (empty($pelangganIds) || empty($kasirIds)) {
            echo "No users found for seeding bookings. Please run UserSeeder first.\n";
            return;
        }

        $detailPemesananData = [];

        // Phase 1: Ensure each schedule has at least 25 booked seats
        foreach ($jadwalTayangIds as $jadwalTayangId) {
            $jadwal = DB::table('jadwal_tayang')->where('id', $jadwalTayangId)->first();

            if (!$jadwal) continue;

            $studio = DB::table('studio')->where('id', $jadwal->studio_id)->first();

            if (!$studio) continue;

            // Count currently booked seats for this schedule
            $currentBookedCount = DB::table('detail_pemesanan')
                ->join('pemesanan', 'detail_pemesanan.pemesanan_id', '=', 'pemesanan.id')
                ->where('pemesanan.jadwal_tayang_id', $jadwalTayangId)
                ->whereIn('pemesanan.status_pembayaran', ['lunas', 'pending'])
                ->count();

            $seatsNeeded = max(0, 25 - $currentBookedCount);

            if ($seatsNeeded > 0) {
                // Get available seats for this schedule
                $availableKursi = DB::table('kursi')
                    ->where('studio_id', $jadwal->studio_id)
                    ->whereNotIn('id', function ($query) use ($jadwalTayangId) {
                        $query->select('kursi_id')
                            ->from('detail_pemesanan')
                            ->join('pemesanan', 'detail_pemesanan.pemesanan_id', '=', 'pemesanan.id')
                            ->where('pemesanan.jadwal_tayang_id', $jadwalTayangId)
                            ->whereIn('pemesanan.status_pembayaran', ['lunas', 'pending']);
                    })
                    ->pluck('id')
                    ->toArray();

                if (count($availableKursi) > 0) {
                    // Take only the needed number of seats (max available)
                    $seatsToBook = min($seatsNeeded, count($availableKursi));
                    $selectedKursi = array_slice($availableKursi, 0, $seatsToBook);

                    // Calculate price based on studio type and day type
                    $tanggalTayang = Carbon::parse($jadwal->tanggal_tayang);
                    $isWeekend = in_array($tanggalTayang->dayOfWeek, [0, 6]);
                    $tipeHari = $isWeekend ? 'weekend' : 'weekday';

                    $hargaTiket = DB::table('harga_tiket')
                        ->where('tipe_studio', $studio->tipe_studio)
                        ->where('tipe_hari', $tipeHari)
                        ->first();

                    $totalHarga = $hargaTiket ? $hargaTiket->harga * $seatsToBook : 35000 * $seatsToBook;

                    // Generate kode_booking
                    $bookingNumber = count($detailPemesananData) + 1;
                    $kodeBooking = 'BK' . date('md') . str_pad($bookingNumber, 6, '0', STR_PAD_LEFT);
                    $kodeBooking = substr($kodeBooking, 0, 14);

                    $pemesananId = DB::table('pemesanan')->insertGetId([
                        'kode_booking' => $kodeBooking,
                        'user_id' => $pelangganIds[array_rand($pelangganIds)],
                        'jadwal_tayang_id' => $jadwalTayangId,
                        'jumlah_tiket' => $seatsToBook,
                        'total_harga' => $totalHarga,
                        'metode_pembayaran' => 'transfer',
                        'jenis_pemesanan' => 'online',
                        'status_pembayaran' => 'lunas', // Mark as paid for guaranteed seats
                        'tanggal_pemesanan' => Carbon::now()->subDays(rand(1, 3)),
                        'kasir_id' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Create detail pemesanan for each seat
                    foreach ($selectedKursi as $kursiId) {
                        $detailPemesananData[] = [
                            'pemesanan_id' => $pemesananId,
                            'kursi_id' => $kursiId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }

                    echo "Added {$seatsToBook} seats for schedule {$jadwalTayangId} (Studio: {$studio->nama_studio})\n";
                }
            }
        }

        // Phase 2: Add additional random bookings (existing logic)
        $additionalBookings = 30; // Additional random bookings

        for ($i = 0; $i < $additionalBookings; $i++) {
            $jadwalTayangId = $jadwalTayangIds[array_rand($jadwalTayangIds)];
            $jadwal = DB::table('jadwal_tayang')->where('id', $jadwalTayangId)->first();

            if (!$jadwal) continue;

            $studio = DB::table('studio')->where('id', $jadwal->studio_id)->first();

            if (!$studio) continue;

            // Get available seats for this schedule
            $availableKursi = DB::table('kursi')
                ->where('studio_id', $jadwal->studio_id)
                ->whereNotIn('id', function ($query) use ($jadwalTayangId) {
                    $query->select('kursi_id')
                        ->from('detail_pemesanan')
                        ->join('pemesanan', 'detail_pemesanan.pemesanan_id', '=', 'pemesanan.id')
                        ->where('pemesanan.jadwal_tayang_id', $jadwalTayangId)
                        ->whereIn('pemesanan.status_pembayaran', ['lunas', 'pending']);
                })
                ->pluck('id')
                ->toArray();

            if (empty($availableKursi)) continue;

            $jumlahTiket = rand(1, min(4, count($availableKursi)));
            $selectedKursi = array_slice($availableKursi, 0, $jumlahTiket);

            // Calculate price based on studio type and day type
            $tanggalTayang = Carbon::parse($jadwal->tanggal_tayang);
            $isWeekend = in_array($tanggalTayang->dayOfWeek, [0, 6]);
            $tipeHari = $isWeekend ? 'weekend' : 'weekday';

            $hargaTiket = DB::table('harga_tiket')
                ->where('tipe_studio', $studio->tipe_studio)
                ->where('tipe_hari', $tipeHari)
                ->first();

            $totalHarga = $hargaTiket ? $hargaTiket->harga * $jumlahTiket : 35000 * $jumlahTiket;

            $statusPembayaran = ['pending', 'lunas'][rand(0, 1)];
            $jenisPemesanan = ['online', 'offline'][rand(0, 1)];
            $metodePembayaran = ['cash', 'transfer', 'qris', 'debit'][rand(0, 3)];

            // Generate kode_booking
            $bookingNumber = count($detailPemesananData) + $i + 1;
            $kodeBooking = 'BK' . date('md') . str_pad($bookingNumber, 6, '0', STR_PAD_LEFT);
            $kodeBooking = substr($kodeBooking, 0, 14);

            $pemesananId = DB::table('pemesanan')->insertGetId([
                'kode_booking' => $kodeBooking,
                'user_id' => $pelangganIds[array_rand($pelangganIds)],
                'jadwal_tayang_id' => $jadwalTayangId,
                'jumlah_tiket' => $jumlahTiket,
                'total_harga' => $totalHarga,
                'metode_pembayaran' => $metodePembayaran,
                'jenis_pemesanan' => $jenisPemesanan,
                'status_pembayaran' => $statusPembayaran,
                'tanggal_pemesanan' => Carbon::now()->subDays(rand(0, 7)),
                'kasir_id' => $jenisPemesanan === 'offline' ? $kasirIds[array_rand($kasirIds)] : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create detail pemesanan for each seat
            foreach ($selectedKursi as $kursiId) {
                $detailPemesananData[] = [
                    'pemesanan_id' => $pemesananId,
                    'kursi_id' => $kursiId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert all detail pemesanan in chunks
        foreach (array_chunk($detailPemesananData, 100) as $chunk) {
            DB::table('detail_pemesanan')->insert($chunk);
        }

        echo "Seeding completed. Total bookings created: " . count($detailPemesananData) . " seat reservations\n";

        // Verify each schedule has at least 25 booked seats
        $this->verifyMinimumSeats($jadwalTayangIds);
    }

    private function verifyMinimumSeats(array $jadwalTayangIds): void
    {
        echo "\nVerifying minimum 25 seats per schedule:\n";

        foreach ($jadwalTayangIds as $jadwalTayangId) {
            $bookedCount = DB::table('detail_pemesanan')
                ->join('pemesanan', 'detail_pemesanan.pemesanan_id', '=', 'pemesanan.id')
                ->where('pemesanan.jadwal_tayang_id', $jadwalTayangId)
                ->whereIn('pemesanan.status_pembayaran', ['lunas', 'pending'])
                ->count();

            $jadwal = DB::table('jadwal_tayang')->where('id', $jadwalTayangId)->first();
            $studio = $jadwal ? DB::table('studio')->where('id', $jadwal->studio_id)->first() : null;

            $studioName = $studio ? $studio->nama_studio : 'Unknown';
            $status = $bookedCount >= 25 ? '✓' : '✗';

            echo "Schedule {$jadwalTayangId} ({$studioName}): {$bookedCount} seats {$status}\n";
        }
    }
}
