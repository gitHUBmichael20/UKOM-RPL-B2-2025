<?php

namespace App\Livewire\Admin\Laporan;

use Livewire\Component;
use App\Models\Pemesanan;
use App\Models\Film;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Index extends Component
{
    public $startDate;
    public $endDate;
    public $filterType = 'month'; // day, week, month, year, custom
    public $activeTab = 'penjualan'; // penjualan, transaksi, terlaris
    
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
        switch ($this->filterType) {
            case 'day':
                $this->startDate = Carbon::today()->format('Y-m-d');
                $this->endDate = Carbon::today()->format('Y-m-d');
                break;
            case 'week':
                $this->startDate = Carbon::now()->startOfWeek()->format('Y-m-d');
                $this->endDate = Carbon::now()->endOfWeek()->format('Y-m-d');
                break;
            case 'month':
                $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
                $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
                break;
            case 'year':
                $this->startDate = Carbon::now()->startOfYear()->format('Y-m-d');
                $this->endDate = Carbon::now()->endOfYear()->format('Y-m-d');
                break;
        }
    }
    
    public function updatedFilterType()
    {
        if ($this->filterType !== 'custom') {
            $this->setDefaultDates();
            $this->loadData();
        }
    }
    
    public function applyFilter()
    {
        $this->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);
        
        $this->loadData();
    }
    
    public function loadData()
    {
        $query = Pemesanan::whereBetween('created_at', [
            Carbon::parse($this->startDate)->startOfDay(),
            Carbon::parse($this->endDate)->endOfDay()
        ])->where('status_pembayaran', 'berhasil');
        
        $this->totalPenjualan = $query->sum('total_harga');
        $this->totalTransaksi = $query->count();
        $this->totalTiket = $query->sum('jumlah_tiket');
    }
    
    public function getLaporanPenjualanProperty()
    {
        // Tampilkan semua transaksi dengan berbagai status
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
        return Pemesanan::with(['jadwalTayang.film', 'user'])
            ->whereBetween('created_at', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ])
            ->select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('COUNT(*) as total_transaksi'),
                DB::raw('SUM(jumlah_tiket) as total_tiket'),
                DB::raw('SUM(CASE WHEN status_pembayaran = "berhasil" THEN total_harga ELSE 0 END) as total_pendapatan')
            )
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'desc')
            ->get();
    }
    
    public function getFilmTerlarisProperty()
    {
        return Pemesanan::with('jadwalTayang.film')
            ->whereBetween('created_at', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ])
            ->where('status_pembayaran', 'berhasil')
            ->select(
                'jadwal_tayang.film_id',
                DB::raw('COUNT(pemesanan.id) as total_transaksi'),
                DB::raw('SUM(pemesanan.jumlah_tiket) as total_tiket'),
                DB::raw('SUM(pemesanan.total_harga) as total_pendapatan')
            )
            ->join('jadwal_tayang', 'pemesanan.jadwal_tayang_id', '=', 'jadwal_tayang.id')
            ->groupBy('jadwal_tayang.film_id')
            ->orderBy('total_tiket', 'desc')
            ->limit(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.laporan.index')->layout('admin.layouts.app');
    }
}