<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Studio;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Film
        $totalFilm = Film::count();
        
        // Film baru bulan ini
        $newFilmThisMonth = Film::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // Pemesanan hari ini
        $bookingsToday = Pemesanan::whereDate('created_at', Carbon::today())->count();
        
        // Pemesanan kemarin
        $bookingsYesterday = Pemesanan::whereDate('created_at', Carbon::yesterday())->count();
        
        // Persentase perubahan pemesanan
        $bookingPercentageChange = $bookingsYesterday > 0 
            ? round((($bookingsToday - $bookingsYesterday) / $bookingsYesterday) * 100) 
            : 0;

        // Pendapatan hari ini
        $revenueToday = Pemesanan::whereDate('created_at', Carbon::today())
            ->sum('total_harga');
        
        // Pendapatan kemarin
        $revenueYesterday = Pemesanan::whereDate('created_at', Carbon::yesterday())
            ->sum('total_harga');
        
        // Persentase perubahan pendapatan
        $revenuePercentageChange = $revenueYesterday > 0 
            ? round((($revenueToday - $revenueYesterday) / $revenueYesterday) * 100) 
            : 0;

        // Total Studio
        $totalStudio = Studio::count();
        
        // Studio berdasarkan tipe
        $imax = Studio::where('tipe_studio', 'imax')->count();
        $deluxe = Studio::where('tipe_studio', 'deluxe')->count();
        $regular = Studio::where('tipe_studio', 'regular')->count();

        // Pemesanan terbaru - ambil dari kasir atau user yang ada datanya
        $recentBookings = Pemesanan::leftJoin('users', 'pemesanan.user_id', '=', 'users.id')
            ->leftJoin('users as kasir', 'pemesanan.kasir_id', '=', 'kasir.id')
            ->select(
                'pemesanan.id',
                'pemesanan.kode_booking',
                'pemesanan.user_id',
                'pemesanan.kasir_id',
                'pemesanan.jadwal_tayang_id',
                'pemesanan.total_harga',
                'pemesanan.status_pembayaran',
                'pemesanan.jenis_pemesanan',
                'pemesanan.created_at',
                'users.name as user_name',
                'kasir.name as kasir_name'
            )
            ->orderBy('pemesanan.id', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', [
            'totalFilm' => $totalFilm,
            'newFilmThisMonth' => $newFilmThisMonth,
            'bookingsToday' => $bookingsToday,
            'bookingPercentageChange' => $bookingPercentageChange,
            'revenueToday' => $revenueToday,
            'revenuePercentageChange' => $revenuePercentageChange,
            'totalStudio' => $totalStudio,
            'premiumStudio' => $imax,
            'regularStudio' => $deluxe,
            'recentBookings' => $recentBookings,
        ]);
    }
}