<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FilmController extends Controller
{
    // Tampilkan daftar film (untuk semua user)
    public function index()
    {
        $films = Film::with('genres')->latest()->paginate(12);
        return view('livewire.admin.film-index', compact('films'));
    }

    // Tampilkan detail film
    public function show($id)
    {
        $film = Film::with('genres')->findOrFail($id);
        return view('films.show', compact('film'));
    }

    // Form tambah film (admin only)
    public function create()
    {
        $genres = Genre::all();
        return view('films.create', compact('genres'));
    }

    // Simpan film baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sutradara_id' => 'required|exists:sutradara,bigint_unsigned',
            'judul' => 'required|string|max:200',
            'isi' => 'nullable|string',
            'durasi' => 'required|integer',
            'tahun_rilis' => 'required|string|max:255',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'rating' => 'nullable|in:SU,R13+,R17+,D21+',
            'status' => 'nullable|in:tayang,segera_tayang,selesai',
            'sinopsis' => 'nullable|string',
            'genres' => 'required|array',
            'genres.*' => 'exists:genre,bigint_unsigned'
        ]);

        // Upload poster
        if ($request->hasFile('poster')) {
            $validated['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $film = Film::create($validated);

        // Attach genres
        $film->genres()->attach($validated['genres']);

        return redirect()->route('films.index')->with('success', 'Film berhasil ditambahkan');
    }

    // Form edit film (admin only)
    public function edit($id)
    {
        $film = Film::with('genres')->findOrFail($id);
        $genres = Genre::all();
        return view('films.edit', compact('film', 'genres'));
    }

    // Update film
    public function update(Request $request, $id)
    {
        $film = Film::findOrFail($id);

        $validated = $request->validate([
            'sutradara_id' => 'required|exists:sutradara,bigint_unsigned',
            'judul' => 'required|string|max:200',
            'isi' => 'nullable|string',
            'durasi' => 'required|integer',
            'tahun_rilis' => 'required|string|max:255',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'rating' => 'nullable|in:SU,R13+,R17+,D21+',
            'status' => 'nullable|in:tayang,segera_tayang,selesai',
            'sinopsis' => 'nullable|string',
            'genres' => 'required|array',
            'genres.*' => 'exists:genre,bigint_unsigned'
        ]);

        // Upload poster baru jika ada
        if ($request->hasFile('poster')) {
            // Hapus poster lama
            if ($film->poster) {
                Storage::disk('public')->delete($film->poster);
            }
            $validated['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $film->update($validated);

        // Sync genres
        $film->genres()->sync($validated['genres']);

        return redirect()->route('films.index')->with('success', 'Film berhasil diupdate');
    }

    // Hapus film (admin only)
    public function destroy($id)
    {
        $film = Film::findOrFail($id);

        // Hapus poster
        if ($film->poster) {
            Storage::disk('public')->delete($film->poster);
        }

        // Hapus relasi genre
        $film->genres()->detach();

        $film->delete();

        return redirect()->route('films.index')->with('success', 'Film berhasil dihapus');
    }
}