<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Http\Request;

class FilmController extends Controller
{
    public function index()
    {
        $films = Film::with(['sutradara', 'genres'])
            ->where('status', 'tayang')
            ->orderBy('tahun_rilis', 'desc')
            ->get()
            ->map(function ($film) {
                return [
                    'id' => $film->id,
                    'title' => $film->judul,
                    'director' => $film->sutradara->nama_sutradara,
                    'duration' => $film->durasi . ' min',
                    'synopsis' => $film->sinopsis,
                    'poster' => $film->poster,
                    'rating' => $this->convertRatingToNumeric($film->rating),
                    'release_date' => $film->tahun_rilis . '-01-01', // Approximate release date
                    'status' => $film->status === 'tayang' ? 'Available' : 'Not Available',
                    'genre' => $film->genres->first()->nama_genre ?? 'Unknown',
                    'year' => $film->tahun_rilis,
                    'rating_original' => $film->rating
                ];
            });

        return view('dashboard', compact('films'));
    }

    public function show(Film $film)
    {
        $film->load(['sutradara', 'genres']);

        return response()->json([
            'id' => $film->id,
            'title' => $film->judul,
            'director' => $film->sutradara->nama_sutradara,
            'duration' => $film->durasi . ' min',
            'synopsis' => $film->sinopsis,
            'poster' => $film->poster,
            'rating' => $this->convertRatingToNumeric($film->rating),
            'release_date' => $film->tahun_rilis . '-01-01',
            'status' => $film->status === 'tayang' ? 'Available' : 'Not Available',
            'genres' => $film->genres->pluck('nama_genre')->toArray(),
            'year' => $film->tahun_rilis,
            'rating_original' => $film->rating
        ]);
    }

    private function convertRatingToNumeric($rating)
    {
        // Convert Indonesian rating system to approximate numeric rating
        $ratings = [
            'SU' => 8.5,    // Semua Umur
            'R13+' => 7.8,  // 13 tahun ke atas
            'R17+' => 7.2,  // 17 tahun ke atas  
            'D21+' => 6.8   // Dewasa 21 tahun ke atas
        ];

        return $ratings[$rating] ?? 7.5;
    }
}
