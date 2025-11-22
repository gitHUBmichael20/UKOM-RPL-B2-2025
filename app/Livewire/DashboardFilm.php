<?php

namespace App\Livewire;

use App\Models\Film;
use Livewire\Component;

class DashboardFilm extends Component
{
    public $search = '';
    public $sortBy = 'terbaru';
    public $selectedFilm = null;

    // Pagination state
    public $perPageTayang = 12;
    public $perPageSegera = 12;

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        // Reset pagination saat search berubah
        $this->perPageTayang = 12;
        $this->perPageSegera = 12;
    }

    public function loadMoreTayang()
    {
        $this->perPageTayang += 12;
    }

    public function loadMoreSegera()
    {
        $this->perPageSegera += 12;
    }

    public function sort($type)
    {
        $this->sortBy = $type;
        // Reset pagination saat sort berubah
        $this->perPageTayang = 12;
        $this->perPageSegera = 12;
    }

    public function openModal($filmId)
    {
        $this->selectedFilm = Film::with(['sutradara', 'genres'])->findOrFail($filmId);
        $this->dispatch('open-modal');
    }

    public function closeModal()
    {
        $this->selectedFilm = null;
        $this->dispatch('close-modal');
    }

    public function render()
    {
        // Base query untuk Sedang Tayang
        $tayangQuery = Film::with(['sutradara', 'genres'])
            ->where('status', 'tayang')
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('judul', 'like', '%' . $this->search . '%')
                        ->orWhere('sinopsis', 'like', '%' . $this->search . '%')
                        ->orWhereHas('sutradara', fn($sq) => $sq->where('nama_sutradara', 'like', '%' . $this->search . '%'));
                });
            });

        // Base query untuk Segera Tayang
        $segeraQuery = Film::with(['sutradara', 'genres'])
            ->where('status', 'segera')
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('judul', 'like', '%' . $this->search . '%')
                        ->orWhere('sinopsis', 'like', '%' . $this->search . '%')
                        ->orWhereHas('sutradara', fn($sq) => $sq->where('nama_sutradara', 'like', '%' . $this->search . '%'));
                });
            });

        // Apply sorting
        $sortedTayangQuery = match ($this->sortBy) {
            'terlama'   => (clone $tayangQuery)->orderBy('tahun_rilis', 'asc'),
            'terbaru'   => (clone $tayangQuery)->orderBy('tahun_rilis', 'desc'),
            'judul-az'  => (clone $tayangQuery)->orderBy('judul', 'asc'),
            'judul-za'  => (clone $tayangQuery)->orderBy('judul', 'desc'),
            default     => (clone $tayangQuery)->latest(),
        };

        $sortedSegeraQuery = match ($this->sortBy) {
            'terlama'   => (clone $segeraQuery)->orderBy('tahun_rilis', 'asc'),
            'terbaru'   => (clone $segeraQuery)->orderBy('tahun_rilis', 'desc'),
            'judul-az'  => (clone $segeraQuery)->orderBy('judul', 'asc'),
            'judul-za'  => (clone $segeraQuery)->orderBy('judul', 'desc'),
            default     => (clone $segeraQuery)->latest(),
        };

        // Get films with limit
        $sedangTayangAll = $sortedTayangQuery->get();
        $segeraTayangAll = $sortedSegeraQuery->get();

        $sedangTayang = $sedangTayangAll->take($this->perPageTayang);
        $segeraTayang = $segeraTayangAll->take($this->perPageSegera);

        // Check if there are more items
        $hasMoreTayang = $sedangTayangAll->count() > $this->perPageTayang;
        $hasMoreSegera = $segeraTayangAll->count() > $this->perPageSegera;

        return view('livewire.dashboard-film', [
            'sedangTayang' => $sedangTayang,
            'segeraTayang' => $segeraTayang,
            'hasMoreTayang' => $hasMoreTayang,
            'hasMoreSegera' => $hasMoreSegera,
            'totalTayang' => $sedangTayangAll->count(),
            'totalSegera' => $segeraTayangAll->count(),
        ]);
    }
}
