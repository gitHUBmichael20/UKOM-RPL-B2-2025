<?php
// app/Http/Controllers/PemesananController.php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\JadwalTayang;
use App\Models\HargaTiket;
use App\Models\Kursi;
use App\Models\DetailPemesanan;
use Illuminate\Http\Request;

class PemesananController extends Controller
{
    public function selectSeats(Film $film, JadwalTayang $jadwalTayang, Request $request)
    {
        // Pastikan jadwal masih valid
        if ($jadwalTayang->film_id !== $film->id) {
            abort(404);
        }

        // SELALU ambil fresh dari database (jangan cache)
        // Tambahkan header no-cache
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Ambil semua kursi dari studio, sorted
        $seats = Kursi::where('studio_id', $jadwalTayang->studio_id)
            ->get()
            ->sortBy(function($item) {
                $row = substr($item->nomor_kursi, 0, 1);
                $col = (int)substr($item->nomor_kursi, 1);
                return $row . str_pad($col, 3, '0', STR_PAD_LEFT);
            })
            ->values();

        // AMBIL FRESH DARI DB - JANGAN CACHE
        // Ambil kursi dari detail_pemesanan (confirmed/paid)
        $bookedFromDetail = DetailPemesanan::where('jadwal_tayang_id', $jadwalTayang->id)
            ->get()
            ->pluck('kursi_id')
            ->toArray();

        // JUGA ambil kursi dari pemesanan online yang pending/menunggu pembayaran
        $bookedFromPending = DetailPemesanan::where('jadwal_tayang_id', $jadwalTayang->id)
            ->whereHas('pemesanan', function ($q) {
                $q->where('status_pembayaran', 'pending')
                  ->orWhere('status_pemesanan', 'menunggu_konfirmasi');
            })
            ->get()
            ->pluck('kursi_id')
            ->toArray();

        // Merge semua booked seats
        $bookedSeats = array_unique(array_merge($bookedFromDetail, $bookedFromPending));

        // DEBUG: Log untuk cek
        \Log::info('=== SELECT SEATS ===');
        \Log::info('Jadwal ID: ' . $jadwalTayang->id);
        \Log::info('From detail: ' . json_encode($bookedFromDetail));
        \Log::info('From pending: ' . json_encode($bookedFromPending));
        \Log::info('Total booked seats: ' . count($bookedSeats));

        // Ambil harga tiket berdasarkan tipe studio
        $hargaTiket = HargaTiket::where('tipe_studio', $jadwalTayang->studio->tipe_studio)->first();

        if (!$hargaTiket) {
            abort(500, 'Harga tiket tidak ditemukan untuk tipe studio: ' . $jadwalTayang->studio->tipe_studio);
        }

        // Force refresh browser dengan random parameter
        $refreshToken = uniqid();

        return view('pemesanan.select-seats', [
            'film' => $film,
            'jadwalTayang' => $jadwalTayang,
            'seats' => $seats,
            'bookedSeats' => $bookedSeats,
            'hargaTiket' => $hargaTiket,
            'refreshToken' => $refreshToken,
        ]);
    }

    public function payment(Film $film, JadwalTayang $jadwalTayang, Request $request)
    {
        $selectedSeats = $request->get('selected_seats', []);

        if (empty($selectedSeats)) {
            return redirect()->back()
                ->with('error', 'Pilih minimal 1 kursi')
                ->with('refresh', true); // Force refresh
        }

        $hargaTiket = HargaTiket::where('tipe_studio', $jadwalTayang->studio->tipe_studio)->first();
        $totalPrice = count($selectedSeats) * $hargaTiket->harga;

        // Validasi ulang: cek kursi tidak ada yang double booked
        $bookedFromDetail = DetailPemesanan::where('jadwal_tayang_id', $jadwalTayang->id)
            ->pluck('kursi_id')
            ->toArray();

        $bookedFromPending = DetailPemesanan::where('jadwal_tayang_id', $jadwalTayang->id)
            ->whereHas('pemesanan', function ($q) {
                $q->where('status_pembayaran', 'pending')
                  ->orWhere('status_pemesanan', 'menunggu_konfirmasi');
            })
            ->pluck('kursi_id')
            ->toArray();

        $allBooked = array_unique(array_merge($bookedFromDetail, $bookedFromPending));

        $conflict = array_intersect($selectedSeats, $allBooked);
        if (!empty($conflict)) {
            \Log::warning('Double booking conflict detected! Seats: ' . implode(', ', $conflict));
            return redirect()->back()
                ->with('error', 'Kursi ' . implode(', ', $conflict) . ' sudah terpesan oleh user lain. Silahkan pilih ulang.')
                ->with('refresh', true);
        }

        // Simpan ke session untuk digunakan di payment page
        session([
            'selected_seats' => $selectedSeats,
            'film_id' => $film->id,
            'jadwal_tayang_id' => $jadwalTayang->id,
            'total_price' => $totalPrice,
        ]);

        return redirect()->route('pemesanan.payment-page', [$film, $jadwalTayang]);
    }
}