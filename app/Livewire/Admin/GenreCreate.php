<?php

namespace App\Livewire\Admin;

use App\Models\Genre;
use Livewire\Component;

class GenreCreate extends Component
{
    public $nama_genre = '';

    protected $rules = [
        'nama_genre' => 'required|string|max:50',
    ];

    protected $messages = [
        'nama_genre.required' => 'Nama genre wajib diisi',
        'nama_genre.max' => 'Nama genre maksimal 50 karakter',
    ];

    public function render()
    {
        return view('livewire.admin.genre-create')->layout('layouts.app');
    }

    public function save()
    {
        $this->validate();

        Genre::create([
            'nama_genre' => $this->nama_genre,
        ]);

        session()->flash('success', 'Genre berhasil ditambahkan!');
        
        return redirect()->route('admin.genre.management');
    }

    public function cancel()
    {
        return redirect()->route('admin.genre.management');
    }
}