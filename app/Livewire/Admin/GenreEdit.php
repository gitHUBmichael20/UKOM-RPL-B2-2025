<?php

namespace App\Livewire\Admin;

use App\Models\Genre;
use Livewire\Component;

class GenreEdit extends Component
{
    public $genreId;
    public $nama_genre = '';

    protected $rules = [
        'nama_genre' => 'required|string|max:50',
    ];

    protected $messages = [
        'nama_genre.required' => 'Nama genre wajib diisi',
        'nama_genre.max' => 'Nama genre maksimal 50 karakter',
    ];

    public function mount($id)
    {
        $genre = Genre::findOrFail($id);
        $this->genreId = $genre->id;
        $this->nama_genre = $genre->nama_genre;
    }

    public function render()
    {
        return view('livewire.admin.genre-edit')->layout('layouts.app');
    }

    public function update()
    {
        $this->validate();

        $genre = Genre::findOrFail($this->genreId);
        $genre->update([
            'nama_genre' => $this->nama_genre,
        ]);

        session()->flash('success', 'Genre berhasil diperbarui!');
        
        return redirect()->route('admin.genre.management');
    }

    public function cancel()
    {
        return redirect()->route('admin.genre.management');
    }
}