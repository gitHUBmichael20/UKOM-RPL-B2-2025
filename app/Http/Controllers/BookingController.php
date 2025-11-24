<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\JadwalTayang;
use App\Models\Studio;
use App\Models\Kursi;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\Auth;
use App\Models\DetailPemesanan;
use App\Models\HargaTiket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function show(Film $film)
    {
        $film->load(['sutradara', 'genres']);
        $jadwalTayang = JadwalTayang::with(['studio'])
            ->where('film_id', $film->id)
            ->where('tanggal_tayang', '>=', now()->startOfDay()->format('Y-m-d'))
            ->orderBy('tanggal_tayang')
            ->orderBy('jam_tayang')
            ->get()
            ->map(function ($jadwal) {
                $jadwal->can_book = $jadwal->masihBisaPesan();
                $jadwal->is_past = $jadwal->lewatJadwal();
                return $jadwal;
            })
            ->groupBy('tanggal_tayang');

        return view('pemesanan.show', compact('film', 'jadwalTayang'));
    }

    public function seatSelection(Film $film, JadwalTayang $jadwalTayang)
    {
        if (! $jadwalTayang->masihBisaPesan()) {
            return redirect()->route('pemesanan.show', $film)
                ->withErrors(['error' => 'Maaf, booking untuk jadwal ini sudah ditutup.']);
        }

        $jadwalTayang->load(['film', 'studio.kursi']);
        $bookedSeats = DetailPemesanan::whereHas('pemesanan', function ($query) use ($jadwalTayang) {
            $query->where('jadwal_tayang_id', $jadwalTayang->id)
                ->whereIn('status_pembayaran', ['pending', 'lunas']);
        })->pluck('kursi_id')->toArray();

        $seats = $jadwalTayang->studio->kursi ?? collect();
        $isWeekend = in_array(Carbon::parse($jadwalTayang->tanggal_tayang)->dayOfWeek, [0, 6]);
        $tipeHari = $isWeekend ? 'weekend' : 'weekday';

        $hargaTiket = HargaTiket::where('tipe_studio', $jadwalTayang->studio->tipe_studio)
            ->where('tipe_hari', $tipeHari)
            ->first();

        if (!$hargaTiket) {
            $hargaTiket = (object) ['harga' => $isWeekend ? 45000 : 35000];
        }

        return view('pemesanan.seat-selection', compact('film', 'jadwalTayang', 'seats', 'bookedSeats', 'hargaTiket'));
    }

    public function payment(Request $request, Film $film, JadwalTayang $jadwalTayang)
    {
        $selectedSeatIds = $request->has('selected_seats')
            ? $request->input('selected_seats')
            : session('booking_data.selected_seats', []);

        if (empty($selectedSeatIds)) {
            return redirect()->route('pemesanan.seats', [$film, $jadwalTayang])
                ->withErrors(['error' => 'Tidak ada kursi yang dipilih.']);
        }

        $jadwalTayang->load(['film', 'studio']);

        // Validasi kursi masih available
        $alreadyBooked = DetailPemesanan::whereIn('kursi_id', $selectedSeatIds)
            ->whereHas('pemesanan', function ($q) use ($jadwalTayang) {
                $q->where('jadwal_tayang_id', $jadwalTayang->id)
                    ->whereIn('status_pembayaran', ['pending', 'lunas']);
            })->exists();

        if ($alreadyBooked) {
            return redirect()->route('pemesanan.seats', [$film, $jadwalTayang])
                ->withErrors(['error' => 'Maaf, salah satu kursi sudah dipesan orang lain.']);
        }

        $seats = Kursi::whereIn('id', $selectedSeatIds)->get();
        $isWeekend = in_array(Carbon::parse($jadwalTayang->tanggal_tayang)->dayOfWeek, [0, 6]);
        $tipeHari = $isWeekend ? 'weekend' : 'weekday';

        $hargaTiket = HargaTiket::where('tipe_studio', $jadwalTayang->studio->tipe_studio)
            ->where('tipe_hari', $tipeHari)
            ->firstOrFail();

        $totalHarga = $hargaTiket->harga * count($selectedSeatIds);

        // SIMPAN KE SESSION
        session()->put('booking_data', [
            'selected_seats' => $selectedSeatIds,
            'jadwal_tayang_id' => $jadwalTayang->id,
            'total_harga' => $totalHarga,
        ]);

        return view('pemesanan.payment', compact(
            'film',
            'jadwalTayang',
            'seats',
            'totalHarga',
            'hargaTiket'
        ));
    }

    /**
     * Create booking + Generate snap token + Return JSON
     */
    public function store(Request $request, Film $film, JadwalTayang $jadwalTayang)
    {
        $bookingData = session('booking_data');
        if (!$bookingData) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi habis. Silakan pilih kursi ulang.'
            ], 400);
        }

        // Cek kursi masih available
        $alreadyBooked = DetailPemesanan::whereIn('kursi_id', $bookingData['selected_seats'])
            ->whereHas('pemesanan', function ($q) use ($jadwalTayang) {
                $q->where('jadwal_tayang_id', $jadwalTayang->id)
                    ->whereIn('status_pembayaran', ['pending', 'lunas']);
            })->exists();

        if ($alreadyBooked) {
            session()->forget('booking_data');
            return response()->json([
                'success' => false,
                'message' => 'Kursi sudah dipesan orang lain!'
            ], 400);
        }

        DB::beginTransaction();
        try {
            $user = auth()->user();

            // Generate kode booking unik
            do {
                $kodeBooking = 'BK' . now()->format('Ymd') . strtoupper(Str::random(6));
            } while (Pemesanan::where('kode_booking', $kodeBooking)->exists());

            // MIDTRANS CONFIG
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production', false);
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            // CREATE SNAP TOKEN
            $params = [
                'transaction_details' => [
                    'order_id' => $kodeBooking,
                    'gross_amount' => (int) $bookingData['total_harga'],
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                ],
                'enabled_payments' => [
                    'credit_card',
                    'qris',
                    'gopay',
                    'shopeepay',
                    'dana',
                    'linkaja',
                    'ovo',
                    'bca_va',
                    'bri_va',
                    'bni_va',
                    'permata_va',
                    'mandiri_va',
                    'cimb_va'
                ],
                'expiry' => [
                    'start_time' => now()->format('Y-m-d H:i:s O'),
                    'unit' => 'hour',
                    'duration' => 2
                ]
            ];

            $snapToken = \Midtrans\Snap::createTransaction($params)->token;

            // BUAT PEMESANAN
            $pemesanan = Pemesanan::create([
                'kode_booking' => $kodeBooking,
                'user_id' => $user->id,
                'user_name' => $user->name,
                'jadwal_tayang_id' => $jadwalTayang->id,
                'jumlah_tiket' => count($bookingData['selected_seats']),
                'total_harga' => $bookingData['total_harga'],
                'metode_pembayaran' => null,
                'jenis_pemesanan' => 'online',
                'status_pembayaran' => 'pending',
                'tanggal_pemesanan' => now(),
                'snap_token' => $snapToken,
                'expired_at' => now()->addHours(2),
            ]);

            // Simpan detail kursi
            foreach ($bookingData['selected_seats'] as $seatId) {
                DetailPemesanan::create([
                    'pemesanan_id' => $pemesanan->id,
                    'kursi_id' => $seatId,
                ]);
            }

            DB::commit();
            session()->forget('booking_data');

            // RETURN SNAP TOKEN UNTUK FRONTEND
            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'booking_id' => $pemesanan->id,
                'success_url' => route('pemesanan.ticket', $pemesanan),
                'bookings_url' => route('pemesanan.my-bookings')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Midtrans Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function success(Pemesanan $pemesanan)
    {
        if ($pemesanan->user_id !== auth()->id()) {
            abort(403);
        }

        if ($pemesanan->status_pembayaran !== 'lunas') {
            $pemesanan->refresh();
        }

        $pemesanan->load(['jadwalTayang.film', 'jadwalTayang.studio', 'detailPemesanan.kursi', 'user']);

        return view('pemesanan.ticket-view', compact('pemesanan'));
    }

    public function ticket(Pemesanan $pemesanan)
    {
        if ($pemesanan->user_id !== auth()->id()) {
            abort(403);
        }

        $pemesanan->load(['jadwalTayang.film', 'jadwalTayang.studio', 'detailPemesanan.kursi', 'user']);

        return view('pemesanan.ticket', compact('pemesanan'));
    }

    public function myBookings()
    {
        $user = Auth::user();
        $bookings = Pemesanan::where('user_id', $user->id)
            ->with(['jadwalTayang.film', 'jadwalTayang.studio', 'detailPemesanan'])
            ->orderBy('tanggal_pemesanan', 'desc')
            ->get();

        return view('pemesanan.my-bookings', compact('bookings'));
    }

    /**
     * Download QR Code untuk tiket
     */
    public function downloadQR(Pemesanan $pemesanan)
    {
        if ($pemesanan->jenis_pemesanan !== 'online' || !$pemesanan->qr_code) {
            abort(404, 'QR Code not found');
        }

        // Cek apakah user adalah pemilik atau kasir
        if (auth()->user()->role !== 'kasir' && $pemesanan->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Path ke file QR code
        $qrPath = storage_path('app/public/' . $pemesanan->qr_code);

        if (!file_exists($qrPath)) {
            abort(404, 'QR Code file not found');
        }

        return response()->download($qrPath, 'QR_' . $pemesanan->kode_booking . '.png');
    }

    // BookingController.php
    public function cancel(Pemesanan $pemesanan)
    {
        if ($pemesanan->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if ($pemesanan->status_pembayaran !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Hanya pesanan pending yang bisa dibatalkan'], 400);
        }

        if ($pemesanan->expired_at && now()->greaterThan($pemesanan->expired_at)) {
            return response()->json(['success' => false, 'message' => 'Waktu pembayaran sudah habis'], 400);
        }

        DB::transaction(function () use ($pemesanan) {
            $pemesanan->update([
                'status_pembayaran' => 'batal',
            ]);
        });

        return response()->json(['success' => true, 'message' => 'Pesanan berhasil dibatalkan']);
    }
}
