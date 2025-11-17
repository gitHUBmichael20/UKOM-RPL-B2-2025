<?php

namespace App\Livewire\Admin;

use App\Models\Film;
use App\Models\Genre;
use App\Models\Sutradara;
use Livewire\Component;
use Livewire\WithFileUploads;

class FilmCreate extends Component
{
    use WithFileUploads;

    public $sutradara_id = '';
    public $judul = '';
    public $durasi = '';
    public $sinopsis = '';
    public $poster;
    public $rating = '';
    public $tahun_rilis = '';
    public $status = 'tayang';
    public $selectedGenres = [];

    protected $rules = [
        'sutradara_id' => 'required|exists:sutradara,id',
        'judul' => 'required|string|max:200',
        'durasi' => 'required|string|max:255',
        'sinopsis' => 'required|string',
        'poster' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        'rating' => 'required|string|in:SU,R13+,R17+,D21+',
        'tahun_rilis' => 'required|integer|min:1900|max:2100',
        'status' => 'required|string|in:tayang,segera,selesai',
        'selectedGenres' => 'required|array|min:1',
    ];

    protected $messages = [
        'sutradara_id.required' => 'Sutradara wajib dipilih',
        'judul.required' => 'Judul film wajib diisi',
        'durasi.required' => 'Durasi wajib diisi',
        'sinopsis.required' => 'Sinopsis wajib diisi',
        'poster.required' => 'Poster wajib diupload',
        'poster.image' => 'File harus berupa gambar',
        'poster.mimes' => 'Format gambar harus jpeg, jpg, atau png',
        'poster.max' => 'Ukuran gambar maksimal 2MB',
        'rating.required' => 'Rating wajib dipilih',
        'tahun_rilis.required' => 'Tahun rilis wajib diisi',
        'tahun_rilis.integer' => 'Tahun rilis harus berupa angka',
        'tahun_rilis.min' => 'Tahun rilis minimal 1900',
        'tahun_rilis.max' => 'Tahun rilis maksimal 2100',
        'status.required' => 'Status wajib dipilih',
        'selectedGenres.required' => 'Minimal pilih 1 genre',
        'selectedGenres.min' => 'Minimal pilih 1 genre',
    ];

    public function render()
    {
        $sutradaras = Sutradara::orderBy('nama_sutradara')->get();
        $genres = Genre::orderBy('nama_genre')->get();

        return view('livewire.admin.film-create', [
            'sutradaras' => $sutradaras,
            'genres' => $genres
        ])->layout('admin.layouts.app');
    }

    public function save()
    {
        $this->validate();

        try {
            // Upload poster
            $posterPath = $this->poster->store('posters', 'public');

            // Buat film
            $film = Film::create([
                'sutradara_id' => $this->sutradara_id,
                'judul' => $this->judul,
                'durasi' => $this->durasi,
                'sinopsis' => $this->sinopsis,
                'poster' => $posterPath,
                'rating' => $this->rating,
                'tahun_rilis' => $this->tahun_rilis,
                'status' => $this->status,
            ]);

            // Attach genres
            $film->genres()->attach($this->selectedGenres);

            session()->flash('success', 'Film berhasil ditambahkan!');
            
            return redirect()->route('admin.film.management');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('admin.film.management');
    }
}