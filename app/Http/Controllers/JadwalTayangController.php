<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalTayang;
use App\Models\Film;
use App\Models\Studio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JadwalTayangController extends Controller
{
    public function index()
    {
        $jadwalTayang = JadwalTayang::with(['film', 'studio'])
            ->orderBy('tanggal_tayang', 'desc')
            ->orderBy('jam_tayang', 'asc')
            ->get();

        return view('admin.jadwal-tayang.index', compact('jadwalTayang'));
    }

    public function create()
    {
        $films = Film::where('status', 'tayang')->get();
        $studios = Studio::all();

        return view('admin.jadwal-tayang.create', compact('films', 'studios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'film_id' => 'required|exists:film,id',
            'studio_id' => 'required|exists:studio,id',
            'tanggal_tayang' => 'required|date|after_or_equal:today',
            'jam_tayang' => 'required|date_format:H:i',
        ]);

        try {
            DB::beginTransaction();

            // Check for schedule conflict
            $conflict = JadwalTayang::where('studio_id', $request->studio_id)
                ->where('tanggal_tayang', $request->tanggal_tayang)
                ->where('jam_tayang', $request->jam_tayang)
                ->exists();

            if ($conflict) {
                return back()->withErrors(['jam_tayang' => 'Jadwal bentrok dengan film lain di studio yang sama'])->withInput();
            }

            JadwalTayang::create($request->all());

            DB::commit();

            return redirect()->route('admin.jadwal-tayang.index')
                ->with('success', 'Jadwal tayang berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(JadwalTayang $jadwalTayang)
    {
        $jadwalTayang->load(['film', 'studio']);
        return view('admin.jadwal-tayang.show', compact('jadwalTayang'));
    }

    public function edit(JadwalTayang $jadwalTayang)
    {
        $films = Film::where('status', 'tayang')->get();
        $studios = Studio::all();

        return view('admin.jadwal-tayang.edit', compact('jadwalTayang', 'films', 'studios'));
    }

    public function update(Request $request, JadwalTayang $jadwalTayang)
    {
        $request->validate([
            'film_id' => 'required|exists:film,id',
            'studio_id' => 'required|exists:studio,id',
            'tanggal_tayang' => 'required|date|after_or_equal:today',
            'jam_tayang' => 'required|date_format:H:i',
        ]);

        try {
            DB::beginTransaction();

            // Check for schedule conflict (excluding current schedule)
            $conflict = JadwalTayang::where('studio_id', $request->studio_id)
                ->where('tanggal_tayang', $request->tanggal_tayang)
                ->where('jam_tayang', $request->jam_tayang)
                ->where('id', '!=', $jadwalTayang->id)
                ->exists();

            if ($conflict) {
                return back()->withErrors(['jam_tayang' => 'Jadwal bentrok dengan film lain di studio yang sama'])->withInput();
            }

            $jadwalTayang->update($request->all());

            DB::commit();

            return redirect()->route('admin.jadwal-tayang.index')
                ->with('success', 'Jadwal tayang berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(JadwalTayang $jadwalTayang)
    {
        try {
            // Check if there are existing bookings for this schedule
            $hasBookings = $jadwalTayang->pemesanan()->exists();

            if ($hasBookings) {
                return redirect()->route('admin.jadwal-tayang.index')
                    ->with('error', 'Tidak dapat menghapus jadwal karena sudah ada pemesanan');
            }

            $jadwalTayang->delete();

            return redirect()->route('admin.jadwal-tayang.index')
                ->with('success', 'Jadwal tayang berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('admin.jadwal-tayang.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function getFilmDuration($filmId)
    {
        $film = Film::find($filmId);
        return response()->json(['duration' => $film->durasi ?? 0]);
    }
}
