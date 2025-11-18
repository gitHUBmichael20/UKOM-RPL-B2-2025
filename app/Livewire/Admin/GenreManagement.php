<?php

namespace App\Livewire\Admin;

use App\Models\Genre;
use Livewire\Component;
use Livewire\WithPagination;

class GenreManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $modalMode = 'create';

    // Form fields
    public $genreId;
    public $nama_genre = '';

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        return [
            'nama_genre' => 'required|string|max:50|unique:genre,nama_genre,' . $this->genreId,
        ];
    }

    protected $messages = [
        'nama_genre.required' => 'Nama genre wajib diisi',
        'nama_genre.max' => 'Nama genre maksimal 50 karakter',
        'nama_genre.unique' => 'Genre ini sudah ada, silakan gunakan nama lain',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $genres = Genre::query()
            ->when($this->search, function ($query) {
                $query->where('nama_genre', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.genre-management', [
            'genres' => $genres
        ])->layout('admin.layouts.app');
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->modalMode = 'create';
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $genre = Genre::findOrFail($id);
        $this->genreId = $genre->id;
        $this->nama_genre = $genre->nama_genre;

        $this->modalMode = 'edit';
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->modalMode === 'create') {
            Genre::create([
                'nama_genre' => $this->nama_genre,
            ]);

            $this->dispatch('success', 'Genre berhasil ditambahkan!');
        } else {
            $genre = Genre::findOrFail($this->genreId);
            $genre->update([
                'nama_genre' => $this->nama_genre,
            ]);

            $this->dispatch('success', 'Genre berhasil diperbarui!');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        try {
            $genre = Genre::findOrFail($id);

            // Cek apakah genre sedang digunakan di film
            if ($genre->filmGenres()->count() > 0) {
                $this->dispatch('error', 'Genre tidak dapat dihapus karena sedang digunakan pada film!');
                return;
            }

            $genre->delete();
            $this->dispatch('success', 'Genre berhasil dihapus!');
        } catch (\Exception $e) {
            $this->dispatch('error', 'Terjadi kesalahan saat menghapus genre!');
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->genreId = null;
        $this->nama_genre = '';
        $this->resetErrorBag();
    }
}
