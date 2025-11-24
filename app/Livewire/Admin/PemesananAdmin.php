<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pemesanan;
use App\Models\DetailPemesanan;


class PemesananAdmin extends Component
{
    use WithPagination;

    public $search = '';
    public $filter = 'semua';
    public $perPage = 10;
    public $selectedPemesanan = null;
    public $showDetailModal = false;

    protected $queryString = ['search', 'filter'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilter()
    {
        $this->resetPage();
    }

    public function viewDetail($pemesananId)
    {
        $this->selectedPemesanan = Pemesanan::with([
            'user',
            'jadwalTayang.film',
            'jadwalTayang.studio',
            'detailPemesanan'
        ])->findOrFail($pemesananId);

        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedPemesanan = null;
    }

    public function batalkanPemesanan($pemesananId)
    {
        $pemesanan = Pemesanan::find($pemesananId);

        if (!$pemesanan) {
            session()->flash('error', 'Pemesanan tidak ditemukan');
            return;
        }

        // Cek apakah sudah lunas
        if ($pemesanan->status_pembayaran === 'lunas') {
            session()->flash('error', 'Pemesanan sudah lunas, tidak bisa dibatalkan');
            return;
        }

        // Update status menjadi dibatalkan
        $pemesanan->update([
            'status_pemesanan' => 'dibatalkan'
        ]);

        session()->flash('success', 'Pemesanan berhasil dibatalkan');
        $this->closeDetailModal();
    }

    public function render()
    {
        $query = Pemesanan::with(['user', 'detailPemesanan']);

        if ($this->search) {
            $query->where('kode_booking', 'like', '%' . $this->search . '%')
                ->orWhereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
        }

        if ($this->filter !== 'semua') {
            $query->where('status_pemesanan', $this->filter);
        }

        $pemesanans = $query->with(['user', 'jadwalTayang.film'])->latest()->paginate($this->perPage);

        return view('livewire.admin.pemesanan-admin', [
            'pemesanans' => $pemesanans,
        ])->layout('admin.layouts.app');
    }
}
