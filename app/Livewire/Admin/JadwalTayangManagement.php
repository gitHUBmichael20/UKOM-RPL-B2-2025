<?php

namespace App\Livewire\Admin;

use App\Models\JadwalTayang;
use Livewire\Component;
use Livewire\WithPagination;

class JadwalTayangManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStudio = '';
    public $filterTanggal = '';

    protected $listeners = ['delete' => 'delete'];

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStudio' => ['except' => ''],
        'filterTanggal' => ['except' => ''],
    ];

    public function mount()
    {
        if (empty($this->filterTanggal)) {
            $this->filterTanggal = now()->format('Y-m-d');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStudio()
    {
        $this->resetPage();
    }

    public function updatingFilterTanggal()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        try {
            $jadwal = JadwalTayang::findOrFail($id);

            if ($jadwal->pemesanan()->exists()) {
                $this->dispatch('error', 'Tidak dapat menghapus jadwal karena sudah ada pemesanan');
                return;
            }

            $jadwal->delete();
            $this->dispatch('success', 'Jadwal tayang berhasil dihapus');
        } catch (\Exception $e) {
            $this->dispatch('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = JadwalTayang::with(['film', 'studio']);

        if ($this->search) {
            $query->whereHas('film', function ($q) {
                $q->where('judul', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterStudio) {
            $query->where('studio_id', $this->filterStudio);
        }

        if ($this->filterTanggal) {
            $query->whereDate('tanggal_tayang', $this->filterTanggal);
        }

        $jadwalTayang = $query->orderBy('tanggal_tayang', 'desc')
            ->orderBy('jam_tayang', 'asc')
            ->paginate(10);

        $studios = \App\Models\Studio::all();

        return view('livewire.admin.jadwal-tayang-management', [
            'jadwalTayang' => $jadwalTayang,
            'studios' => $studios
        ])->layout('admin.layouts.app');
    }
}