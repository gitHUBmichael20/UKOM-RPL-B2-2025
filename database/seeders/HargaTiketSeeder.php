<?php

namespace Database\Seeders;

use App\Models\HargaTiket;
use Illuminate\Database\Seeder;

class HargaTiketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hargaTikets = [
            // Regular
            [
                'tipe_studio' => 'regular',
                'tipe_hari' => 'weekday',
                'harga' => 35000
            ],
            [
                'tipe_studio' => 'regular',
                'tipe_hari' => 'weekend',
                'harga' => 45000
            ],
            
            // Deluxe
            [
                'tipe_studio' => 'deluxe',
                'tipe_hari' => 'weekday',
                'harga' => 50000
            ],
            [
                'tipe_studio' => 'deluxe',
                'tipe_hari' => 'weekend',
                'harga' => 65000
            ],
            
            // IMAX
            [
                'tipe_studio' => 'imax',
                'tipe_hari' => 'weekday',
                'harga' => 75000
            ],
            [
                'tipe_studio' => 'imax',
                'tipe_hari' => 'weekend',
                'harga' => 95000
            ],
        ];

        foreach ($hargaTikets as $hargaTiket) {
            HargaTiket::create($hargaTiket);
        }
    }
}