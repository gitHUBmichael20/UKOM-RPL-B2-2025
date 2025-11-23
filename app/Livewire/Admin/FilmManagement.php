<?php

namespace App\Livewire\Admin;

use App\Models\Film;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class FilmManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        $films = Film::with(['sutradara', 'genres'])
            ->when($this->search, function ($query) {
                $query->where('judul', 'like', '%' . $this->search . '%')
                    ->orWhere('sinopsis', 'like', '%' . $this->search . '%')
                    ->orWhereHas('sutradara', function ($q) {
                        $q->where('nama_sutradara', 'like', '%' . $this->search . '%');
                    });
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.film-management', [
            'films' => $films
        ])->layout('admin.layouts.app');
    }

    public function delete($id)
    {
        try {
            $film = Film::findOrFail($id);

            // Cek apakah film sedang digunakan di jadwal tayang
            $jadwalCount = $film->jadwalTayangs()->count();

            if ($jadwalCount > 0) {
                $this->dispatch('error', 'Film tidak dapat dihapus karena sedang digunakan pada jadwal tayang!');
                return;
            }

            // Hapus poster jika ada
            if ($film->poster && Storage::disk('public')->exists($film->poster)) {
                Storage::disk('public')->delete($film->poster);
            }

            $film->delete();

            $this->dispatch('success', 'Film berhasil dihapus!');
        } catch (\Exception $e) {
            // Tambahkan log untuk debugging
            \Log::error('Error deleting film: ' . $e->getMessage());
            $this->dispatch('error', 'Terjadi kesalahan saat menghapus film: ' . $e->getMessage());
        }
    }
}
