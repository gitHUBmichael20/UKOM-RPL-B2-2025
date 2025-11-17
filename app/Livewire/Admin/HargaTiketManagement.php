<?php

namespace App\Livewire\Admin;

use App\Models\HargaTiket;
use Livewire\Component;
use Livewire\WithPagination;

class HargaTiketManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $filterTipeStudio = '';
    public $filterTipeHari = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterTipeStudio' => ['except' => ''],
        'filterTipeHari' => ['except' => '']
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterTipeStudio()
    {
        $this->resetPage();
    }

    public function updatingFilterTipeHari()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        // Cek apakah user adalah admin
        if (!isRole('admin')) {
            session()->flash('error', 'Anda tidak memiliki akses untuk menghapus harga tiket.');
            return;
        }

        try {
            $hargaTiket = HargaTiket::findOrFail($id);
            $hargaTiket->delete();

            session()->flash('success', 'Harga tiket berhasil dihapus!');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus harga tiket: ' . $e->getMessage());
        }
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterTipeStudio = '';
        $this->filterTipeHari = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = HargaTiket::query();

        // Filter berdasarkan search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('tipe_studio', 'like', '%' . $this->search . '%')
                  ->orWhere('tipe_hari', 'like', '%' . $this->search . '%')
                  ->orWhere('harga', 'like', '%' . $this->search . '%');
            });
        }

        // Filter berdasarkan tipe studio
        if ($this->filterTipeStudio) {
            $query->where('tipe_studio', $this->filterTipeStudio);
        }

        // Filter berdasarkan tipe hari
        if ($this->filterTipeHari) {
            $query->where('tipe_hari', $this->filterTipeHari);
        }

        $hargaTikets = $query->latest()->paginate(10);

        return view('livewire.admin.harga-tiket-management', data: [
            'hargaTikets' => $hargaTikets,
            'tipeStudioOptions' => HargaTiket::TIPE_STUDIO,
            'tipeHariOptions' => HargaTiket::TIPE_HARI
        ])->layout('admin.layouts.app');
    }
}