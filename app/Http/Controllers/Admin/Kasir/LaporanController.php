<?php

namespace App\Http\Controllers\Admin\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanHarianKasirExport;

class LaporanController extends Controller
{
    public function exportHarian(Request $request)
    {
        $date = Carbon::parse($request->date);
        
        $data = Pemesanan::with(['jadwalTayang.film', 'jadwalTayang.studio', 'user'])
            ->whereDate('created_at', $date)
            ->where('status_pembayaran', 'berhasil')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $filename = 'laporan-harian-kasir-' . $date->format('Y-m-d') . '.xlsx';
        
        return Excel::download(new LaporanHarianKasirExport($data, $date), $filename);
    }
}