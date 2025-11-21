<?php

namespace App\Livewire;

use App\Models\Film;
use Livewire\Component;
use Livewire\WithPagination;

class DashboardFilm extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'terbaru'; // terbaru, terlama, judul-az, judul-za
    public $selectedFilm = null;

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sort($type)
    {
        $this->sortBy = $type;
        $this->resetPage();
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
        $baseQuery = Film::with(['sutradara', 'genres'])
            ->whereIn('status', ['tayang', 'segera'])
            ->when($this->search, function ($q) {
                $q->where('judul', 'like', '%' . $this->search . '%')
                    ->orWhere('sinopsis', 'like', '%' . $this->search . '%')
                    ->orWhereHas('sutradara', fn($sq) => $sq->where('nama_sutradara', 'like', '%' . $this->search . '%'));
            });

        $sortedQuery = match ($this->sortBy) {
            'terlama'   => (clone $baseQuery)->orderBy('tahun_rilis', 'asc'),
            'terbaru'   => (clone $baseQuery)->orderBy('tahun_rilis', 'desc'),
            'judul-az'  => (clone $baseQuery)->orderBy('judul', 'asc'),
            'judul-za'  => (clone $baseQuery)->orderBy('judul', 'desc'),
            default     => (clone $baseQuery)->latest(),
        };


        $sedangTayang = (clone $sortedQuery)->where('status', 'tayang')->paginate(8, ['*'], 'tayang_page');
        $segeraTayang = (clone $sortedQuery)->where('status', 'segera')->paginate(8, ['*'], 'segera_page');

        return view('livewire.dashboard-film', [
            'sedangTayang' => $sedangTayang,
            'segeraTayang' => $segeraTayang,
        ]);
    }
}
