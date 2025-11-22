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

        $jadwalTayang = JadwalTayang::with(['studio.hargaTiket'])
            ->where('film_id', $film->id)
            ->where('tanggal_tayang', '>=', now()->subDays(1)->format('Y-m-d'))
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

        // Get booked seats for this schedule
        $bookedSeats = DetailPemesanan::whereHas('pemesanan', function ($query) use ($jadwalTayang) {
            $query->where('jadwal_tayang_id', $jadwalTayang->id)
                ->whereIn('status_pembayaran', ['pending', 'lunas']);
        })->pluck('kursi_id')->toArray();

        // Get all seats for this studio
        $seats = $jadwalTayang->studio->kursi ?? collect();

        // Calculate price
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
        $request->validate([
            'selected_seats' => 'required|array|min:1',
            'selected_seats.*' => 'exists:kursi,id'
        ]);

        $jadwalTayang->load(['film', 'studio']);

        $selectedSeatIds = $request->selected_seats;
        $seats = Kursi::whereIn('id', $selectedSeatIds)->get();

        // Check if seats are still available
        $alreadyBooked = DetailPemesanan::whereIn('kursi_id', $selectedSeatIds)
            ->whereHas('pemesanan', function ($query) use ($jadwalTayang) {
                $query->where('jadwal_tayang_id', $jadwalTayang->id)
                    ->whereIn('status_pembayaran', ['pending', 'lunas']);
            })->exists();

        if ($alreadyBooked) {
            return back()->withErrors(['error' => 'Some selected seats are no longer available. Please try again.']);
        }

        // Calculate price
        $isWeekend = in_array(Carbon::parse($jadwalTayang->tanggal_tayang)->dayOfWeek, [0, 6]);
        $tipeHari = $isWeekend ? 'weekend' : 'weekday';
        $hargaTiket = HargaTiket::where('tipe_studio', $jadwalTayang->studio->tipe_studio)
            ->where('tipe_hari', $tipeHari)
            ->first();

        $totalHarga = $hargaTiket->harga * count($selectedSeatIds);

        // Store selected seats in session for the store method
        session()->put('selected_seats', $selectedSeatIds);
        session()->put('jadwal_tayang_id', $jadwalTayang->id);

        return view('pemesanan.payment', compact('film', 'jadwalTayang', 'seats', 'totalHarga', 'hargaTiket'));
    }

    public function store(Request $request, Film $film, JadwalTayang $jadwalTayang)
    {
        $request->validate([
            'metode_pembayaran' => 'required|in:cash,transfer,qris,debit'
        ]);

        try {
            DB::beginTransaction();

            // Get data dari session atau langsung dari request
            $selectedSeatIds = session()->get('selected_seats');

            // Fallback: jika session hilang, coba dari form hidden (optional)
            if (!$selectedSeatIds && $request->has('selected_seats')) {
                $selectedSeatIds = $request->selected_seats;
            }

            if (!$selectedSeatIds) {
                return redirect()->route('pemesanan.seats', [$film, $jadwalTayang])
                    ->withErrors(['error' => 'Please select seats first.']);
            }

            $user = auth()->user();

            // Check if seats are still available
            $alreadyBooked = DetailPemesanan::whereIn('kursi_id', $selectedSeatIds)
                ->whereHas('pemesanan', function ($query) use ($jadwalTayang) {
                    $query->where('jadwal_tayang_id', $jadwalTayang->id)
                        ->whereIn('status_pembayaran', ['pending', 'lunas']);
                })->exists();

            if ($alreadyBooked) {
                return back()->withErrors(['error' => 'Some selected seats are no longer available. Please try again.']);
            }

            // Calculate price
            $isWeekend = in_array(Carbon::parse($jadwalTayang->tanggal_tayang)->dayOfWeek, [0, 6]);
            $tipeHari = $isWeekend ? 'weekend' : 'weekday';
            $hargaTiket = HargaTiket::where('tipe_studio', $jadwalTayang->studio->tipe_studio)
                ->where('tipe_hari', $tipeHari)
                ->first();

            $totalHarga = $hargaTiket->harga * count($selectedSeatIds);

            // Generate booking code
            do {
                $kodeBooking = 'BK' . now()->format('Ymd') . strtoupper(Str::random(6));
            } while (Pemesanan::where('kode_booking', $kodeBooking)->exists());

            // Create booking
            $pemesanan = Pemesanan::create([
                'kode_booking' => $kodeBooking,
                'user_id' => $user->id,
                'user_name' => $user->name,
                'jadwal_tayang_id' => $jadwalTayang->id,
                'jumlah_tiket' => count($selectedSeatIds),
                'total_harga' => $totalHarga,
                'metode_pembayaran' => $request->metode_pembayaran,
                'jenis_pemesanan' => 'online',
                'status_pembayaran' => 'pending',
                'tanggal_pemesanan' => now(),
                'kasir_id' => null,
            ]);

            // Create seat details
            foreach ($selectedSeatIds as $kursiId) {
                DetailPemesanan::create([
                    'pemesanan_id' => $pemesanan->id,
                    'kursi_id' => $kursiId,
                ]);
            }

            // Clear session
            session()->forget(['selected_seats', 'jadwal_tayang_id']);

            DB::commit();

            return redirect()->route('pemesanan.success', $pemesanan->id)
                ->with('success', 'Booking confirmed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to create booking: ' . $e->getMessage()]);
        }
    }

    public function success(Pemesanan $pemesanan)
    {
        if ($pemesanan->user_id !== auth()->id()) {
            abort(403);
        }

        $pemesanan->load(['jadwalTayang.film', 'jadwalTayang.studio', 'detailPemesanan.kursi']);
        return view('pemesanan.success', compact('pemesanan'));
    }

    public function ticket(Pemesanan $pemesanan)
    {
        if ($pemesanan->user_id !== auth()->id()) {
            abort(403);
        }

        $pemesanan->load(['jadwalTayang.film', 'jadwalTayang.studio', 'detailPemesanan.kursi', 'user']);
        return view('pemesanan.ticket', compact('pemesanan'));
    }

    /**
     * Show user's booking history
     */
    public function myBookings()
    {
        $user = Auth::user();
        $bookings = Pemesanan::where('user_id', $user->id)
            ->with(['jadwalTayang', 'detailPemesanan']) // eager loading
            ->orderBy('tanggal_pemesanan', 'desc')
            ->get();

        return view('pemesanan.my-bookings', compact('bookings'));
    }

    /**
     * Show specific booking details
     */
    public function bookingDetails(Pemesanan $pemesanan)
    {
        if ($pemesanan->user_id !== auth()->id()) {
            abort(403);
        }

        $pemesanan->load([
            'jadwalTayang.film.sutradara',
            'jadwalTayang.studio',
            'detailPemesanan.kursi',
            'user'
        ]);

        return view('pemesanan.booking-details', compact('pemesanan'));
    }

    /**
     * Show ticket for specific booking
     */
    public function viewTicket(Pemesanan $pemesanan)
    {
        if ($pemesanan->user_id !== auth()->id()) {
            abort(403);
        }

        $pemesanan->load([
            'jadwalTayang.film.sutradara',
            'jadwalTayang.studio',
            'detailPemesanan.kursi',
            'user'
        ]);

        return view('pemesanan.ticket-view', compact('pemesanan'));
    }
}
