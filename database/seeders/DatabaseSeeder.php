<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Sutradara;
use App\Models\Genre;
use App\Models\Film;
use App\Models\FilmGenre;
use App\Models\Studio;
use App\Models\Kursi;
use App\Models\HargaTiket;
use App\Models\JadwalTayang;
use App\Models\Pemesanan;
use App\Models\DetailPemesanan;

class DatabaseSeeder extends Seeder
{
  private function downloadImage($url, $folder, $filename)
  {
    try {
      $response = Http::timeout(30)->get($url);
      if ($response->successful()) {
        $path = $folder . '/' . $filename;
        Storage::disk('public')->put($path, $response->body());
        return $path;
      }
    } catch (\Exception $e) {
      // Jika download gagal, gunakan gambar default
      \Log::error("Failed to download image: " . $e->getMessage());
    }
    return null;
  }

  public function run(): void
  {
    DB::disableQueryLog();
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');

    // Truncate semua tabel
    User::truncate();
    HargaTiket::truncate();
    Genre::truncate();
    Sutradara::truncate();
    Studio::truncate();
    Kursi::truncate();
    Film::truncate();
    FilmGenre::truncate();
    JadwalTayang::truncate();
    Pemesanan::truncate();
    DetailPemesanan::truncate();

    // Buat folder untuk penyimpanan gambar
    Storage::disk('public')->makeDirectory('images/posters');
    Storage::disk('public')->makeDirectory('images/sutradara');

    // ===================================================================
    // 1. USER
    // ===================================================================
    $wajibUsers = [
      ['name' => 'Admin Q',       'email' => 'q@gmail.com',    'password' => Hash::make('12345678'), 'role' => 'admin',     'email_verified_at' => now()],
      ['name' => 'Admin App',     'email' => 'admin@app.com',  'password' => Hash::make('12345678'), 'role' => 'admin',     'email_verified_at' => now()],
      ['name' => 'Kasir W',       'email' => 'w@gmail.com',    'password' => Hash::make('12345678'), 'role' => 'kasir',     'email_verified_at' => now()],
      ['name' => 'Kasir App',     'email' => 'kasir@app.com',  'password' => Hash::make('12345678'), 'role' => 'kasir',     'email_verified_at' => now()],
      ['name' => 'Pelanggan A',   'email' => 'a@gmail.com',    'password' => Hash::make('12345678'), 'role' => 'pelanggan', 'email_verified_at' => now()],
      ['name' => 'Customer App',  'email' => 'cust@app.com',   'password' => Hash::make('12345678'), 'role' => 'pelanggan', 'email_verified_at' => now()],
    ];

    foreach ($wajibUsers as $u) {
      User::create($u);
    }

    $namaPelanggan = ['Budi Santoso', 'Siti Nurhaliza', 'Ahmad Dhani', 'Rina Wijaya', 'Dika Pratama', 'Laras Putri', 'Fajar Nugroho', 'Intan Permata', 'Rizky Febrian', 'Nadia Zahra'];
    foreach ($namaPelanggan as $i => $nama) {
      User::create([
        'name'              => $nama,
        'email'             => strtolower(str_replace(' ', '', $nama)) . ($i + 1) . '@gmail.com',
        'password'          => Hash::make('12345678'),
        'role'              => 'pelanggan',
        'email_verified_at' => now(),
      ]);
    }

    // ===================================================================
    // 2. HARGA TIKET
    // ===================================================================
    $hargaTikets = [
      ['tipe_studio' => 'regular', 'tipe_hari' => 'weekday',  'harga' => 35000],
      ['tipe_studio' => 'regular', 'tipe_hari' => 'weekend',  'harga' => 45000],
      ['tipe_studio' => 'deluxe',  'tipe_hari' => 'weekday',  'harga' => 50000],
      ['tipe_studio' => 'deluxe',  'tipe_hari' => 'weekend',  'harga' => 65000],
      ['tipe_studio' => 'imax',    'tipe_hari' => 'weekday',  'harga' => 75000],
      ['tipe_studio' => 'imax',    'tipe_hari' => 'weekend',  'harga' => 95000],
    ];
    foreach ($hargaTikets as $h) {
      HargaTiket::create($h);
    }

    // ===================================================================
    // 3. GENRE
    // ===================================================================
    $genres = ['Action', 'Adventure', 'Animation', 'Comedy', 'Crime', 'Drama', 'Fantasy', 'Horror', 'Romance', 'Sci-Fi', 'Thriller', 'Mystery', 'Biography', 'Musical', 'Superhero'];
    foreach ($genres as $g) {
      Genre::create(['nama_genre' => $g]);
    }

    // ===================================================================
    // 4. SUTRADARA (15 orang) - Download gambar dari thispersondoesnotexist
    // ===================================================================
    $sutradaraList = ['Joko Anwar', 'Hanung Bramantyo', 'Angga Dwimas Sasongko', 'Gareth Evans', 'Riri Riza', 'Christopher Nolan', 'James Cameron', 'Denis Villeneuve', 'Bong Joon-ho', 'Makoto Shinkai', 'Rako Prijanto', 'Fajar Nugros', 'Upi Avianto', 'Timo Tjahjanto', 'Kamila Andini'];

    $sutradaraModels = [];
    foreach ($sutradaraList as $nama) {
      $sutradara = Sutradara::create([
        'nama_sutradara' => $nama,
        'foto_profil'    => null,
        'biografi'       => "Sutradara {$nama} yang telah menyutradarai banyak film terkenal dengan berbagai genre."
      ]);
      $sutradaraModels[] = $sutradara;
    }

    // Download foto profil sutradara
    foreach ($sutradaraModels as $sutradara) {
      $filename = 'sutradara_' . Str::slug($sutradara->nama_sutradara) . '.jpg';
      $imagePath = $this->downloadImage(
        'https://thispersondoesnotexist.com/',
        'images/sutradara',
        $filename
      );

      if ($imagePath) {
        $sutradara->update(['foto_profil' => $imagePath]);
      }

      // Delay untuk menghindari rate limiting
      usleep(500000); // 0.5 detik
    }

    // ===================================================================
    // 5. STUDIO + KURSI
    // ===================================================================
    $studios = [
      ['nama_studio' => 'Studio 1', 'kapasitas_kursi' => 200, 'tipe_studio' => 'regular'],
      ['nama_studio' => 'Studio 2', 'kapasitas_kursi' => 120, 'tipe_studio' => 'deluxe'],
      ['nama_studio' => 'Studio 3', 'kapasitas_kursi' => 80,  'tipe_studio' => 'imax'],
    ];

    foreach ($studios as $s) {
      $studio = Studio::create($s);

      $maxRows = match (true) {
        $s['kapasitas_kursi'] == 200 => 10,
        $s['kapasitas_kursi'] == 120 => 6,
        $s['kapasitas_kursi'] == 80  => 4,
        default => 10
      };

      $rows = range('A', chr(ord('A') + $maxRows - 1));

      foreach ($rows as $rIndex => $row) {
        for ($col = 1; $col <= 20; $col++) {
          if (($rIndex * 20 + $col) > $s['kapasitas_kursi']) break 2;
          Kursi::create([
            'studio_id'   => $studio->id,
            'nomor_kursi' => $row . $col
          ]);
        }
      }
    }

    // ===================================================================
    // 6. FILM (30 film) - Download poster dari Picsum Photos
    // ===================================================================
    $films = [
      // Tayang (15)
      ['Pengabdi Setan 2: Communion', 2022, 'R13+', 'Horror'],
      ['KKN di Desa Penari', 2022, 'R13+', 'Horror'],
      ['Agak Laen', 2024, 'SU', 'Comedy'],
      ['Dilan 1991', 2019, 'SU', 'Romance'],
      ['Sewu Dino', 2023, 'R13+', 'Horror'],
      ['Miracle in Cell No.7', 2022, 'SU', 'Drama'],
      ['Avatar: The Way of Water', 2022, 'SU', 'Adventure'],
      ['Spider-Man: Across the Spider-Verse', 2023, 'SU', 'Animation'],
      ['Dune: Part Two', 2024, 'R13+', 'Sci-Fi'],
      ['Oppenheimer', 2023, 'D17+', 'Biography'],
      ['Parasite', 2019, 'D17+', 'Thriller'],
      ['Your Name.', 2016, 'SU', 'Animation'],
      ['Qodrat', 2022, 'R13+', 'Horror'],
      ['Ivanna', 2022, 'R13+', 'Horror'],
      ['Satu Untuk Selamanya', 2024, 'SU', 'Drama'],

      // Segera tayang (15)
      ['Munkar', 2025, 'R13+', 'Horror'],
      ['Lembayung', 2025, 'SU', 'Drama'],
      ['Deadpool & Wolverine', 2024, 'D17+', 'Action'],
      ['Joker: Folie à Deux', 2024, 'D17+', 'Crime'],
      ['Gladiator II', 2024, 'D17+', 'Action'],
      ['Mufasa: The Lion King', 2024, 'SU', 'Animation'],
      ['Sonic the Hedgehog 3', 2024, 'SU', 'Adventure'],
      ['Kraven the Hunter', 2024, 'D17+', 'Action'],
      ['Nosferatu', 2024, 'D17+', 'Horror'],
      ['Captain America: Brave New World', 2025, 'SU', 'Action'],
      ['Thunderbolts', 2025, 'R13+', 'Action'],
      ['Fantastic Four', 2025, 'SU', 'Adventure'],
      ['Borderless', 2025, 'SU', 'Drama'],
      ['The Amateur', 2025, 'R13+', 'Thriller'],
      ['Blade', 2025, 'D17+', 'Action'],
    ];

    $filmModels = [];
    foreach ($films as $i => $f) {
      $status = $i < 15 ? 'tayang' : 'segera';

      $film = Film::create([
        'sutradara_id' => rand(1, 15),
        'judul'        => $f[0],
        'durasi'       => rand(105, 185),
        'sinopsis'     => "Sinopsis film {$f[0]} ({$f[1]}) - {$status} di Absolute Cinema. Film ini menceritakan tentang petualangan yang menarik dan penuh dengan kejutan yang tidak terduga.",
        'rating'       => $f[2],
        'tahun_rilis'  => $f[1],
        'status'       => $status,
        'poster'       => null,
      ]);

      $filmModels[] = $film;

      // Genre acak 1-3
      $randomGenres = Genre::inRandomOrder()->limit(rand(1, 3))->pluck('id');
      foreach ($randomGenres as $genreId) {
        FilmGenre::create([
          'film_id'  => $film->id,
          'genre_id' => $genreId
        ]);
      }
    }

    // Download poster film
    foreach ($filmModels as $film) {
      $filename = 'poster_' . Str::slug($film->judul) . '.jpg';
      $imagePath = $this->downloadImage(
        'https://picsum.photos/750/1000?random=' . $film->id,
        'images/posters',
        $filename
      );

      if ($imagePath) {
        $film->update(['poster' => $imagePath]);
      }

      // Delay untuk menghindari rate limiting
      usleep(300000); // 0.3 detik
    }

    // ===================================================================
    // 7. JADWAL TAYANG (14 hari ke depan + 3 hari kemarin)
    // ===================================================================
    $jamTayang = ['10:00', '12:30', '15:00', '17:30', '20:00', '22:30'];
    $studioIds = Studio::pluck('id')->toArray();

    for ($d = -3; $d < 11; $d++) {
      $tanggal = Carbon::now()->addDays($d)->format('Y-m-d');

      foreach ($studioIds as $studioId) {
        $filmsToday = Film::where('status', 'tayang')
          ->inRandomOrder()
          ->limit(rand(3, 5))
          ->get();

        foreach ($filmsToday as $film) {
          JadwalTayang::create([
            'film_id'        => $film->id,
            'studio_id'      => $studioId,
            'tanggal_tayang' => $tanggal,
            'jam_tayang'     => $jamTayang[array_rand($jamTayang)],
          ]);
        }
      }
    }

    // ===================================================================
    // 8. PEMESANAN (40 transaksi)
    // ===================================================================
    $metode = ['cash', 'bank_transfer', 'debit', 'qris', 'ewallet'];
    $statusPembayaran = ['lunas', 'pending', 'redeemed'];

    for ($i = 0; $i < 40; $i++) {
      $jadwal = JadwalTayang::inRandomOrder()->first();
      $studio = $jadwal->studio;
      $isWeekend = Carbon::parse($jadwal->tanggal_tayang)->isWeekend();

      $harga = HargaTiket::where('tipe_studio', $studio->tipe_studio)
        ->where('tipe_hari', $isWeekend ? 'weekend' : 'weekday')
        ->first()->harga;

      $jumlahTiket = rand(1, 6);
      $isOnline = rand(0, 1);
      $user = $isOnline ? User::where('role', 'pelanggan')->inRandomOrder()->first() : null;
      $kasir = $isOnline ? null : User::where('role', 'kasir')->inRandomOrder()->first();

      // Kode booking unik 100%
      $kodeBooking = 'BK' . date('Ymd') . strtoupper(Str::random(8));

      $pemesanan = Pemesanan::create([
        'kode_booking'      => $kodeBooking,
        'user_id'           => $user?->id,
        'user_name'         => $user ? null : 'Walk-in Customer',
        'jadwal_tayang_id'  => $jadwal->id,
        'jumlah_tiket'      => $jumlahTiket,
        'total_harga'       => $harga * $jumlahTiket,
        'metode_pembayaran' => $metode[array_rand($metode)],
        'jenis_pemesanan'   => $isOnline ? 'online' : 'offline',
        'status_pembayaran' => $statusPembayaran[array_rand($statusPembayaran)],
        'tanggal_pemesanan' => now()->subHours(rand(1, 500)),
        'kasir_id'          => $kasir?->id,
        'expired_at'        => $isOnline ? now()->addHours(2) : null,
      ]);

      // Ambil kursi yang masih kosong untuk jadwal ini
      $takenSeats = DetailPemesanan::whereHas('pemesanan', function ($q) use ($jadwal) {
        $q->where('jadwal_tayang_id', $jadwal->id);
      })->pluck('kursi_id');

      $availableSeats = Kursi::where('studio_id', $jadwal->studio_id)
        ->whereNotIn('id', $takenSeats)
        ->inRandomOrder()
        ->limit($jumlahTiket)
        ->get();

      foreach ($availableSeats as $kursi) {
        DetailPemesanan::create([
          'pemesanan_id' => $pemesanan->id,
          'kursi_id'     => $kursi->id,
        ]);
      }
    }

    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    DB::enableQueryLog();

    $this->command->info('Seeder berhasil dijalankan!');
    $this->command->info('Gambar poster disimpan di: storage/app/public/images/posters/');
    $this->command->info('Gambar sutradara disimpan di: storage/app/public/images/sutradara/');
  }
}