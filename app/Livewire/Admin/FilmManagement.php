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
    public $showDeleteModal = false;
    
    // Delete
    public $deleteId;

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
                      ->orWhereHas('sutradara', function($q) {
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

    public function openDeleteModal($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        try {
            $film = Film::findOrFail($this->deleteId);
            
            // Cek apakah film sedang digunakan di jadwal tayang
            if ($film->jadwalTayangs()->count() > 0) {
                session()->flash('error', 'Film tidak dapat dihapus karena sedang digunakan pada jadwal tayang!');
                $this->closeDeleteModal();
                return;
            }

            // Hapus poster jika ada
            if ($film->poster && Storage::disk('public')->exists($film->poster)) {
                Storage::disk('public')->delete($film->poster);
            }

            // Hapus relasi genre
            $film->genres()->detach();
            
            // Hapus film
            $film->delete();
            
            session()->flash('success', 'Film berhasil dihapus!');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menghapus film!');
        }

        $this->closeDeleteModal();
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deleteId = null;
    }
}