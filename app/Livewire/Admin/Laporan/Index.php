<?php

namespace App\Livewire\Admin\Laporan;

use Livewire\Component;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Index extends Component
{
    public $startDate;
    public $endDate;
    public $filterType = 'month';
    public $activeTab = 'penjualan';

    public $totalPenjualan = 0;
    public $totalTransaksi = 0;
    public $totalTiket = 0;

    public function mount()
    {
        $this->setDefaultDates();
        $this->loadData();
    }

    public function setDefaultDates()
    {
        if ($this->filterType === 'custom') {
            return;
        }

        match ($this->filterType) {
            'day'   => $this->startDate = $this->endDate = Carbon::today()->format('Y-m-d'),
            'week'  => [
                $this->startDate = Carbon::now()->startOfWeek()->format('Y-m-d'),
                $this->endDate   = Carbon::now()->endOfWeek()->format('Y-m-d')
            ],
            'year'  => [
                $this->startDate = Carbon::now()->startOfYear()->format('Y-m-d'),
                $this->endDate   = Carbon::now()->endOfYear()->format('Y-m-d')
            ],
            default => [
                $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d'),
                $this->endDate   = Carbon::now()->endOfMonth()->format('Y-m-d')
            ],
        };
    }

    public function updatedFilterType($value)
    {
        $this->filterType = $value;

        if ($value !== 'custom') {
            $this->setDefaultDates();
        }

        $this->loadData();
    }

    public function applyFilter()
    {
        $this->validate([
            'startDate' => 'required|date',
            'endDate'   => 'required|date|after_or_equal:startDate',
        ]);

        $this->filterType = 'custom';
        $this->loadData();
    }

    public function loadData()
    {
        try {
            // Query untuk semua transaksi yang BERHASIL (lunas/redeemed)
            $query = Pemesanan::whereBetween('created_at', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ])
            ->whereIn('status_pembayaran', ['berhasil', 'lunas', 'redeemed']); // Tambahkan status lain

            $this->totalPenjualan = (int) $query->clone()->sum('total_harga');
            $this->totalTransaksi = (int) $query->clone()->count();
            $this->totalTiket     = (int) $query->clone()->sum('jumlah_tiket');

            \Log::info('Load Data:', [
                'startDate' => $this->startDate,
                'endDate' => $this->endDate,
                'totalPenjualan' => $this->totalPenjualan,
                'totalTransaksi' => $this->totalTransaksi,
                'totalTiket' => $this->totalTiket
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading report data: ' . $e->getMessage());
        }
    }

    public function getLaporanPenjualanProperty()
    {
        return Pemesanan::with(['jadwalTayang.film', 'jadwalTayang.studio', 'user'])
            ->whereBetween('created_at', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getLaporanTransaksiProperty()
    {
        return Pemesanan::whereBetween('created_at', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ])
            ->select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('COUNT(*) as total_transaksi'),
                DB::raw('SUM(jumlah_tiket) as total_tiket'),
                DB::raw('SUM(CASE WHEN status_pembayaran IN ("berhasil", "lunas", "redeemed") THEN total_harga ELSE 0 END) as total_pendapatan')
            )
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'desc')
            ->get();
    }

    public function getFilmTerlarisProperty()
    {
        // Ambil data berdasarkan jadwal_tayang_id
        $results = Pemesanan::with(['jadwalTayang.film'])
            ->whereBetween('created_at', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ])
            ->whereIn('status_pembayaran', ['berhasil', 'lunas', 'redeemed'])
            ->select(
                'jadwal_tayang_id',
                DB::raw('COUNT(*) as total_transaksi'),
                DB::raw('SUM(jumlah_tiket) as total_tiket'),
                DB::raw('SUM(total_harga) as total_pendapatan')
            )
            ->groupBy('jadwal_tayang_id')
            ->orderBy('total_pendapatan', 'desc')
            ->limit(10)
            ->get();

        \Log::info('Film Terlaris Query:', [
            'count' => $results->count(),
            'data' => $results->toArray()
        ]);

        return $results;
    }

    public function render()
    {
        return view('livewire.admin.laporan.index')
            ->layout('admin.layouts.app');
    }
}