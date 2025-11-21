<?php

namespace App\Livewire\Admin;

use App\Models\Film;
use App\Models\Genre;
use App\Models\Sutradara;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class FilmEdit extends Component
{
    use WithFileUploads;

    public $filmId;
    public $sutradara_id = '';
    public $judul = '';
    public $durasi = '';
    public $sinopsis = '';
    public $poster;
    public $oldPoster;
    public $rating = '';
    public $tahun_rilis = '';
    public $status = 'tayang';
    public $selectedGenres = [];

    protected $rules = [
        'sutradara_id' => 'required|exists:sutradara,id',
        'judul' => 'required|string|max:200',
        'durasi' => 'required|integer',
        'sinopsis' => 'required|string',
        'poster' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        'rating' => 'required|string|in:SU,R13+,D17+,D21+',
        'tahun_rilis' => 'required|integer|min:1900|max:2100',
        'status' => 'required|string|in:tayang,segera,selesai',
        'selectedGenres' => 'required|array|min:1',
    ];

    protected $messages = [
        'sutradara_id.required' => 'Sutradara wajib dipilih',
        'judul.required' => 'Judul film wajib diisi',
        'durasi.required' => 'Durasi wajib diisi',
        'sinopsis.required' => 'Sinopsis wajib diisi',
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

    public function mount($id)
    {
        $film = Film::with('genres')->findOrFail($id);
        
        $this->filmId = $film->id;
        $this->sutradara_id = $film->sutradara_id;
        $this->judul = $film->judul;
        $this->durasi = $film->durasi;
        $this->sinopsis = $film->sinopsis;
        $this->oldPoster = $film->poster;
        $this->rating = $film->rating;
        $this->tahun_rilis = $film->tahun_rilis;
        $this->status = $film->status;
        $this->selectedGenres = $film->genres->pluck('id')->toArray();
    }

    public function render()
    {
        $sutradaras = Sutradara::orderBy('nama_sutradara')->get();
        $genres = Genre::orderBy('nama_genre')->get();

        return view('livewire.admin.film-edit', [
            'sutradaras' => $sutradaras,
            'genres' => $genres
        ])->layout('admin.layouts.app');
    }

    public function update()
    {
        $this->validate();

        try {
            $film = Film::findOrFail($this->filmId);
            
            $data = [
                'sutradara_id' => $this->sutradara_id,
                'judul' => $this->judul,
                'durasi' => $this->durasi,
                'sinopsis' => $this->sinopsis,
                'rating' => $this->rating,
                'tahun_rilis' => $this->tahun_rilis,
                'status' => $this->status,
            ];

            // Jika ada poster baru
            if ($this->poster) {
                // Hapus poster lama
                if ($this->oldPoster && Storage::disk('public')->exists($this->oldPoster)) {
                    Storage::disk('public')->delete($this->oldPoster);
                }
                
                // Upload poster baru
                $data['poster'] = $this->poster->store('posters', 'public');
            }

            // Update film
            $film->update($data);

            // Sync genres
            $film->genres()->sync($this->selectedGenres);

            session()->flash('success', 'Film berhasil diperbarui!');
            
            return redirect()->route('admin.film.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('admin.film.index');
    }
}