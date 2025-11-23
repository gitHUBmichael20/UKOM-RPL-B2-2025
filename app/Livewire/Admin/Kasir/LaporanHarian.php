<?php

namespace App\Livewire\Admin\Kasir;

use Livewire\Component;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanHarian extends Component
{
    public $selectedDate;
    public $totalPenjualan = 0;
    public $totalTransaksi = 0;
    public $totalTiket = 0;
    
    public function mount()
    {
        $this->selectedDate = Carbon::today()->format('Y-m-d');
        $this->loadData();
    }
    
    public function updatedSelectedDate()
    {
        $this->loadData();
    }
    
    public function loadData()
    {
        // Ambil semua transaksi, bukan hanya yang berhasil
        $query = Pemesanan::whereDate('created_at', $this->selectedDate);
        
        // Hitung total dari transaksi berhasil saja
        $this->totalPenjualan = Pemesanan::whereDate('created_at', $this->selectedDate)
            ->where('status_pembayaran', 'berhasil')
            ->sum('total_harga');
            
        $this->totalTransaksi = $query->count();
        $this->totalTiket = Pemesanan::whereDate('created_at', $this->selectedDate)
            ->where('status_pembayaran', 'berhasil')
            ->sum('jumlah_tiket');
    }
    
    public function getLaporanTransaksiProperty()
    {
        // Tampilkan semua transaksi, tidak hanya yang berhasil
        return Pemesanan::with(['jadwalTayang.film', 'jadwalTayang.studio', 'user'])
            ->whereDate('created_at', $this->selectedDate)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.kasir.laporan-harian')->layout('admin.layouts.app');
    }
}