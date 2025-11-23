<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanPenjualanExport;
use App\Exports\LaporanTransaksiExport;

class LaporanController extends Controller
{
    public function exportPenjualan(Request $request)
    {
        $startDate = Carbon::parse($request->start)->startOfDay();
        $endDate = Carbon::parse($request->end)->endOfDay();
        
        $data = Pemesanan::with(['jadwalTayang.film', 'jadwalTayang.studio', 'user'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status_pembayaran', 'berhasil')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $filename = 'laporan-penjualan-' . $request->start . '-to-' . $request->end . '.xlsx';
        
        return Excel::download(new LaporanPenjualanExport($data), $filename);
    }
    
    public function exportTransaksi(Request $request)
    {
        $startDate = Carbon::parse($request->start)->startOfDay();
        $endDate = Carbon::parse($request->end)->endOfDay();
        
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
        
        $filename = 'laporan-transaksi-harian-' . $request->start . '-to-' . $request->end . '.xlsx';
        
        return Excel::download(new LaporanTransaksiExport($data), $filename);
    }
}