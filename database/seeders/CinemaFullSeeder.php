<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Carbon\Carbon;

class CinemaFullSeeder extends Seeder
{
  public function run()
  {
    $faker = Faker::create('id_ID');

    // ====================================================================
    // 1. Studio + Kursi (sama seperti sebelumnya)
    // ====================================================================
    $studios = [
      ['nama_studio' => 'Studio 1', 'kapasitas_kursi' => 80, 'tipe_studio' => 'regular'],
      ['nama_studio' => 'Studio 2', 'kapasitas_kursi' => 80, 'tipe_studio' => 'regular'],
      ['nama_studio' => 'Studio 3', 'kapasitas_kursi' => 60, 'tipe_studio' => 'deluxe'],
      ['nama_studio' => 'Studio 4', 'kapasitas_kursi' => 60, 'tipe_studio' => 'deluxe'],
      ['nama_studio' => 'Studio IMAX', 'kapasitas_kursi' => 100, 'tipe_studio' => 'imax'],
    ];

    $studioIds = [];
    foreach ($studios as $s) {
      $id = DB::table('studio')->insertGetId([
        'nama_studio'     => $s['nama_studio'],
        'kapasitas_kursi' => $s['kapasitas_kursi'],
        'tipe_studio'     => $s['tipe_studio'],
        'created_at'      => now(),
        'updated_at'      => now(),
      ]);
      $studioIds[] = $id;

      $rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
      foreach ($rows as $row) {
        for ($i = 1; $i <= 10; $i++) {
          DB::table('kursi')->insert([
            'studio_id'    => $id,
            'nomor_kursi'  => $row . $i,
            'created_at'   => now(),
            'updated_at'   => now(),
          ]);
        }
      }
    }

    // ====================================================================
    // 2. Sutradara (15 orang)
    // ====================================================================
    $sutradaraNames = [
      'Joko Anwar',
      'Garin Nugroho',
      'Riri Riza',
      'Hanung Bramantyo',
      'Angga Dwimas Sasongko',
      'Timo Tjahjanto',
      'Mouly Surya',
      'Kamila Andini',
      'Ifa Isfansyah',
      'Upi Avianto',
      'Nia Dinata',
      'Fajar Nugros',
      'Yandy Laurens',
      'Sabrina',
      'Eddie Cahyono'
    ];

    $sutradaraIds = [];
    foreach ($sutradaraNames as $name) {
      $id = DB::table('sutradara')->insertGetId([
        'nama_sutradara' => $name,
        'biografi'       => $faker->paragraph(4),
        'created_at'     => now(),
        'updated_at'     => now(),
      ]);
      $sutradaraIds[] = $id;
    }

    // ====================================================================
    // 3. Film — TOTAL 45 FILM
    // ====================================================================
    $filmTitles = [
      // Indonesia (horor, drama, komedi, action)
      'Pengabdi Setan 3',
      'KKN di Desa Penari 2',
      'Sewu Dino',
      'Qodrat',
      'Ivanna',
      'Perempuan Tanah Jahanam',
      'Danur 4',
      'Asih 3',
      'Makmum 3',
      'Rumah Kentang: The Beginning',
      'Gundala',
      'Sri Asih',
      'Godam & Tira',
      'Virgo and The Sparklings',
      'Satria Dewa: Gatotkaca',
      'Dilan 1991',
      'Ada Apa Dengan Cinta 3',
      'Mile 22',
      'Nanti Kita Cerita Tentang Hari Ini 2',
      'Buya Hamka',
      'Tenggelamnya Kapal van der Wijck',
      'Habibie & Ainun 4',
      'Laskar Pelangi 2',
      'Warkop DKI Reborn 5',
      'My Stupid Boss 3',
      'Comic 8: Last Warriors',
      'Single: Part 3',
      'Susuk: Kutukan Kecantikan',
      'Kisah Tanah Jawa: Pocong Gundul',
      'Pamali: Dusun Pocong',
      'Agak Laen',
      'Pasutri Gaje',
      'Trinil: Kembalikan Tubuhku',
      'Ancika: Dia yang Bersamaku 1995',
      'Ali & Ratu Ratu Queens 2',
      'Dear Nathan: Thank You Salma',
      'London Love Story 4',

      // Luar yang sering tayang di bioskop Indonesia
      'Avatar: The Way of Water',
      'Spider-Man: Across the Spider-Verse',
      'Oppenheimer',
      'Dune: Part Two',
      'Deadpool & Wolverine',
      'Inside Out 2',
      'Kung Fu Panda 4',
      'Godzilla x Kong: The New Empire',
      'Joker: Folie à Deux',
      'Mufasa: The Lion King',
      'Fast & Furious 11',
      'Mission: Impossible – Dead Reckoning Part Two'
    ];

    $filmIds = [];
    foreach ($filmTitles as $title) {
      $id = DB::table('film')->insertGetId([
        'sutradara_id'  => $faker->randomElement($sutradaraIds),
        'judul'         => $title,
        'durasi'        => $faker->numberBetween(90, 195),
        'sinopsis'      => $faker->paragraphs(3, true),
        'poster'        => 'default_poster.png',
        'rating'        => $faker->randomElement(['SU', 'R13+', 'D17+', 'D21+']),
        'tahun_rilis'   => $faker->numberBetween(2018, 2025),
        'status'        => $faker->randomElement(['tayang', 'segera', 'selesai']),
        'created_at'    => now(),
        'updated_at'    => now(),
      ]);

      // Random 1–4 genre (asumsi genre id 1-20 sudah ada)
      $jumlahGenre = $faker->numberBetween(1, 4);
      $genres = $faker->randomElements(range(1, 20), $jumlahGenre);
      foreach ($genres as $g) {
        DB::table('film_genre')->insert([
          'film_id'    => $id,
          'genre_id'   => $g,
          'created_at' => now(),
          'updated_at' => now(),
        ]);
      }

      $filmIds[] = $id;
    }

    // ====================================================================
    // 4. Jadwal Tayang (14 hari ke depan)
    // ====================================================================
    $jamTayang = ['10:00:00', '12:30:00', '15:00:00', '17:30:00', '20:00:00', '22:00:00'];
    $jadwalIds = [];

    for ($hari = 0; $hari < 14; $hari++) {
      $tanggal = Carbon::today()->addDays($hari);
      foreach ($studioIds as $studioId) {
        foreach ($jamTayang as $jam) {
          if ($faker->boolean(25)) continue; // ~75% ada jadwal

          $jadwalId = DB::table('jadwal_tayang')->insertGetId([
            'film_id'        => $faker->randomElement($filmIds),
            'studio_id'      => $studioId,
            'tanggal_tayang' => $tanggal->format('Y-m-d'),
            'jam_tayang'     => $jam,
            'created_at'     => now(),
            'updated_at'     => now(),
          ]);
          $jadwalIds[] = $jadwalId;
        }
      }
    }

    // ====================================================================
    // 5. Users – 60 pelanggan (biar lebih rame)
    // ====================================================================
    $pelangganIds = [];
    for ($i = 0; $i < 60; $i++) {
      $id = DB::table('users')->insertGetId([
        'name'              => $faker->name,
        'email'             => $faker->unique()->safeEmail,
        'password'          => bcrypt('password123'),
        'phone'             => $faker->phoneNumber,
        'role'              => 'pelanggan',
        'email_verified_at' => now(),
        'created_at'        => now(),
        'updated_at'        => now(),
      ]);
      $pelangganIds[] = $id;
    }

    // ====================================================================
    // 6. Pemesanan + Detail Kursi (400 pemesanan)
    // ====================================================================
    for ($i = 0; $i < 400; $i++) {
      $jadwalId   = $faker->randomElement($jadwalIds);
      $jadwal     = DB::table('jadwal_tayang')->where('id', $jadwalId)->first();
      $studioId   = $jadwal->studio_id;
      $tipeStudio = DB::table('studio')->where('id', $studioId)->value('tipe_studio');
      $isWeekend  = in_array(Carbon::parse($jadwal->tanggal_tayang)->dayOfWeek, [0, 6]) ? 'weekend' : 'weekday';

      $harga = DB::table('harga_tiket')
        ->where('tipe_studio', $tipeStudio)
        ->where('tipe_hari', $isWeekend)
        ->value('harga') ?? 50000;

      $jumlahTiket = $faker->numberBetween(1, 6);
      $totalHarga  = $harga * $jumlahTiket;
      $jenis       = $faker->randomElement(['online', 'offline']);
      $kasirId     = ($jenis === 'offline') ? $faker->numberBetween(2, 6) : null;

      $kodeBooking = strtoupper(Str::random(3)) . $faker->unique()->numberBetween(10000, 99999);

      $pemesananId = DB::table('pemesanan')->insertGetId([
        'kode_booking'       => $kodeBooking,
        'user_id'            => $faker->randomElement($pelangganIds),
        'jadwal_tayang_id'   => $jadwalId,
        'jumlah_tiket'       => $jumlahTiket,
        'total_harga'        => $totalHarga,
        'metode_pembayaran'  => $faker->randomElement(['cash', 'transfer', 'qris', 'debit']),
        'jenis_pemesanan'    => $jenis,
        'status_pembayaran'  => $faker->randomElement(['pending', 'lunas', 'redeemed', 'batal']),
        'tanggal_pemesanan'  => $faker->dateTimeBetween('-45 days', 'now'),
        'kasir_id'           => $kasirId,
        'created_at'         => now(),
        'updated_at'         => now(),
      ]);

      // Kursi yang sudah dibooking di jadwal ini
      $bookedSeats = DB::table('detail_pemesanan')
        ->join('pemesanan', 'detail_pemesanan.pemesanan_id', '=', 'pemesanan.id')
        ->where('pemesanan.jadwal_tayang_id', $jadwalId)
        ->pluck('kursi_id');

      $availableSeats = DB::table('kursi')
        ->where('studio_id', $studioId)
        ->whereNotIn('id', $bookedSeats)
        ->inRandomOrder()
        ->limit($jumlahTiket)
        ->pluck('id');

      foreach ($availableSeats as $kursiId) {
        DB::table('detail_pemesanan')->insert([
          'pemesanan_id' => $pemesananId,
          'kursi_id'     => $kursiId,
          'created_at'   => now(),
          'updated_at'   => now(),
        ]);
      }
    }

    $this->command->info('Seeder selesai! 45 film, 60 pelanggan, ±400 pemesanan sudah dibuat.');
  }
}
