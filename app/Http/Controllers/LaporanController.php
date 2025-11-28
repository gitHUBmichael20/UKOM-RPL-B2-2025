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
}