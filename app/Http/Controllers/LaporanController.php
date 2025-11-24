<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Exports\LaporanPenjualanExport;
use App\Exports\LaporanTransaksiExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function exportPenjualan(Request $request)
{
    $start = $request->query('start');
    $end   = $request->query('end');

    if (!$start || !$end) {
        return back()->with('error', 'Parameter tanggal tidak valid');
    }

    $startDate = Carbon::createFromFormat('Y-m-d', $start)->startOfDay();
    $endDate   = Carbon::createFromFormat('Y-m-d', $end)->endOfDay();

    $data = Pemesanan::with(['jadwalTayang.film', 'jadwalTayang.studio', 'user'])
        ->whereBetween('created_at', [$startDate, $endDate])
        ->orderBy('created_at', 'desc')
        ->get();

    if ($data->isEmpty()) {
        return back()->with('error', 'Tidak ada data untuk diexport');
    }

    return (new LaporanPenjualanExport($data))->download();
}
    
    public function exportTransaksi(Request $request)
    {
        try {
            // Ambil parameter dari query string
            $start = $request->query('start');
            $end = $request->query('end');
            
            if (!$start || !$end) {
                return back()->with('error', 'Parameter tanggal tidak valid');
            }
            
            // Validasi tanggal
            $startDate = Carbon::createFromFormat('Y-m-d', $start)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $end)->endOfDay();
            
            // Ambil data transaksi harian
            $data = Pemesanan::whereBetween('created_at', [$startDate, $endDate])
                ->select(
                    DB::raw('DATE(created_at) as tanggal'),
                    DB::raw('COUNT(*) as total_transaksi'),
                    DB::raw('SUM(jumlah_tiket) as total_tiket'),
                    DB::raw('SUM(CASE WHEN status_pembayaran = "berhasil" THEN total_harga ELSE 0 END) as total_pendapatan')
                )
                ->groupBy('tanggal')
                ->orderBy('tanggal', 'desc')
                ->get();
            
            if ($data->isEmpty()) {
                return back()->with('error', 'Tidak ada data untuk diexport');
            }
            
            $fileName = 'Laporan-Transaksi-' . $start . '-' . $end . '.xlsx';
            
            return Excel::download(
                new LaporanTransaksiExport($data),
                $fileName
            );
        } catch (\Exception $e) {
            \Log::error('Export transaksi error: ' . $e->getMessage());
            return back()->with('error', 'Gagal export data: ' . $e->getMessage());
        }
    }
}