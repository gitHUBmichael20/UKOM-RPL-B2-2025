<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\JadwalTayang;
use App\Models\Studio;
use App\Models\Kursi;
use App\Models\Pemesanan;
use App\Models\DetailPemesanan;
use App\Models\HargaTiket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap as MidtransSnap;
use Exception;

class BookingController extends Controller
{
    /**
     * Show film details and available schedules
     */
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

    /**
     * Show seat selection page
     */
    public function seatSelection(Film $film, JadwalTayang $jadwalTayang)
    {
        if (!$jadwalTayang->masihBisaPesan()) {
            return redirect()
                ->route('pemesanan.show', $film)
                ->with('error', 'Maaf, booking untuk jadwal ini sudah ditutup.');
        }

        $jadwalTayang->load(['film', 'studio.kursi']);

        // Get booked seats
        $bookedSeats = DetailPemesanan::whereHas('pemesanan', function ($query) use ($jadwalTayang) {
            $query->where('jadwal_tayang_id', $jadwalTayang->id)
                ->whereIn('status_pembayaran', ['pending', 'lunas']);
        })->pluck('kursi_id')->toArray();

        $seats = $jadwalTayang->studio->kursi ?? collect();

        // Determine day type and price
        $isWeekend = in_array(Carbon::parse($jadwalTayang->tanggal_tayang)->dayOfWeek, [0, 6]);
        $tipeHari = $isWeekend ? 'weekend' : 'weekday';

        $hargaTiket = HargaTiket::where('tipe_studio', $jadwalTayang->studio->tipe_studio)
            ->where('tipe_hari', $tipeHari)
            ->first();

        // Fallback price if not found in database
        if (!$hargaTiket) {
            $hargaTiket = (object) ['harga' => $isWeekend ? 45000 : 35000];
        }

        return view('pemesanan.seat-selection', compact(
            'film',
            'jadwalTayang',
            'seats',
            'bookedSeats',
            'hargaTiket'
        ));
    }

    /**
     * Show payment page with selected seats
     */
    public function payment(Request $request, Film $film, JadwalTayang $jadwalTayang)
    {
        $selectedSeatIds = $request->has('selected_seats')
            ? $request->input('selected_seats')
            : (session('booking_data.selected_seats') ?? []);

        if (empty($selectedSeatIds)) {
            return redirect()
                ->route('pemesanan.seats', [$film, $jadwalTayang])
                ->with('error', 'Tidak ada kursi yang dipilih.');
        }

        $jadwalTayang->load(['film', 'studio']);

        // Validate seat availability
        $alreadyBooked = DetailPemesanan::whereIn('kursi_id', $selectedSeatIds)
            ->whereHas('pemesanan', function ($query) use ($jadwalTayang) {
                $query->where('jadwal_tayang_id', $jadwalTayang->id)
                    ->whereIn('status_pembayaran', ['pending', 'lunas']);
            })->exists();

        if ($alreadyBooked) {
            return redirect()
                ->route('pemesanan.seats', [$film, $jadwalTayang])
                ->with('error', 'Maaf, salah satu kursi sudah dipesan orang lain.');
        }

        $seats = Kursi::whereIn('id', $selectedSeatIds)->get();

        // Calculate price
        $isWeekend = in_array(Carbon::parse($jadwalTayang->tanggal_tayang)->dayOfWeek, [0, 6]);
        $tipeHari = $isWeekend ? 'weekend' : 'weekday';

        $hargaTiket = HargaTiket::where('tipe_studio', $jadwalTayang->studio->tipe_studio)
            ->where('tipe_hari', $tipeHari)
            ->firstOrFail();

        $totalHarga = $hargaTiket->harga * count($selectedSeatIds);

        // Store in session
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

    public function store(Request $request, Film $film, JadwalTayang $jadwalTayang)
    {
        $bookingData = session('booking_data');

        if (!$bookingData) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi habis. Silakan pilih kursi ulang.'
            ], 400);
        }

        // Double-check seat availability
        $alreadyBooked = DetailPemesanan::whereIn('kursi_id', $bookingData['selected_seats'])
            ->whereHas('pemesanan', function ($query) use ($jadwalTayang) {
                $query->where('jadwal_tayang_id', $jadwalTayang->id)
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
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Generate unique booking code
            do {
                $kodeBooking = 'BK' . now()->format('Ymd') . strtoupper(Str::random(6));
            } while (Pemesanan::where('kode_booking', $kodeBooking)->exists());

            // Config midtrans
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production', false);
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            // buat snap token
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
                    'duration' => 1
                ]
            ];

            $snapToken = \Midtrans\Snap::createTransaction($params)->token;

            // buat pemesannya
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
                'expired_at' => now()->addHours(1),
            ]);

            // Create seat details
            foreach ($bookingData['selected_seats'] as $seatId) {
                DetailPemesanan::create([
                    'pemesanan_id' => $pemesanan->id,
                    'kursi_id' => $seatId,
                ]);
            }

            DB::commit();
            session()->forget('booking_data');

            // Return snap token buat dipake frontend
            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'booking_id' => $pemesanan->id,
                'success_url' => route('pemesanan.ticket', $pemesanan),
                'bookings_url' => route('pemesanan.my-bookings')
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Booking Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Configure Midtrans settings
     */
    private function configureMidtrans(): void
    {
        MidtransConfig::$serverKey = config('midtrans.server_key');
        MidtransConfig::$isProduction = config('midtrans.is_production', false);
        MidtransConfig::$isSanitized = true;
        MidtransConfig::$is3ds = true;
    }

    /**
     * Create Snap transaction and return token
     */
    private function createSnapTransaction(string $orderId, float $amount, $user): string
    {
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $amount,
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

        $snapResponse = MidtransSnap::createTransaction($params);
        return $snapResponse->token;
    }

    /**
     * Show success page after payment
     */
    public function success(Pemesanan $pemesanan)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($pemesanan->user_id !== $user->id) {
            abort(403);
        }

        // Refresh payment status if not paid
        if ($pemesanan->status_pembayaran !== 'lunas') {
            $pemesanan->refresh();
        }

        $pemesanan->load([
            'jadwalTayang.film',
            'jadwalTayang.studio',
            'detailPemesanan.kursi',
            'user'
        ]);

        return view('pemesanan.ticket-view', compact('pemesanan'));
    }
    public function ticket(Pemesanan $pemesanan)
{
    $user = Auth::user();

    $isOwner = $pemesanan->user_id && $pemesanan->user_id === $user->id;
    $isKasir = $user->role === 'kasir' && $pemesanan->jenis_pemesanan === 'offline';

    if (!$isOwner && !$isKasir) {
        abort(403);
    }

    $pemesanan->load([
        'jadwalTayang.film',
        'jadwalTayang.studio',
        'detailPemesanan.kursi',
        'user'
    ]);

    if ($isKasir) {
        return view('livewire.kasir.tiket-pemesanan', compact('pemesanan'));
    }

    // User biasa, tampilkan view default
    return view('pemesanan.ticket', compact('pemesanan'));
}

    public function myBookings()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $bookings = Pemesanan::where('user_id', $user->id)
            ->with([
                'jadwalTayang.film',
                'jadwalTayang.studio',
                'detailPemesanan'
            ])
            ->orderBy('tanggal_pemesanan', 'desc')
            ->get();

        return view('pemesanan.my-bookings', compact('bookings'));
    }

    // Download QR Code untuk tiket
    public function downloadQR(Pemesanan $pemesanan)
    {
        if ($pemesanan->jenis_pemesanan !== 'online' || !$pemesanan->qr_code) {
            abort(404, 'QR Code tidak ditemukan');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Check if user is owner or cashier
        if ($user->role !== 'kasir' && $pemesanan->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        // QR code file path
        $qrPath = storage_path('app/public/' . $pemesanan->qr_code);

        if (!file_exists($qrPath)) {
            abort(404, 'File QR Code tidak ditemukan');
        }

        return response()->download($qrPath, 'QR_' . $pemesanan->kode_booking . '.png');
    }

    public function cancel(Pemesanan $pemesanan)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($pemesanan->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        if ($pemesanan->status_pembayaran !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pesanan pending yang bisa dibatalkan'
            ], 400);
        }

        if ($pemesanan->expired_at && now()->greaterThan($pemesanan->expired_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Waktu pembayaran sudah habis'
            ], 400);
        }

        DB::transaction(function () use ($pemesanan) {
            $pemesanan->update([
                'status_pembayaran' => 'batal',
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibatalkan'
        ]);
    }
}