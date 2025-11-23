<?php

namespace App\Livewire\Admin;

use App\Models\Film;
use App\Models\JadwalTayang;
use App\Models\Studio;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JadwalTayangEdit extends Component
{
    public $jadwalId;
    public $film_id;
    public $studio_id;
    public $tanggal_tayang;
    public $jam_tayang;
    public $film_duration = 0;

    protected function rules()
    {
        return [
            'film_id' => 'required|exists:film,id',
            'studio_id' => 'required|exists:studio,id',
            'tanggal_tayang' => 'required|date|after_or_equal:today',
            'jam_tayang' => 'required|date_format:H:i',
        ];
    }

    protected $messages = [
        'film_id.required' => 'Film harus dipilih',
        'studio_id.required' => 'Studio harus dipilih',
        'tanggal_tayang.required' => 'Tanggal tayang harus diisi',
        'tanggal_tayang.after_or_equal' => 'Tanggal tayang tidak boleh kurang dari hari ini',
        'jam_tayang.required' => 'Jam tayang harus diisi',
        'jam_tayang.date_format' => 'Format jam tidak valid (HH:mm)',
    ];

    public function mount($id)
    {
        $jadwal = JadwalTayang::findOrFail($id);
        $this->jadwalId = $jadwal->id;
        $this->film_id = $jadwal->film_id;
        $this->studio_id = $jadwal->studio_id;
        $this->tanggal_tayang = Carbon::parse($jadwal->tanggal_tayang)->format('Y-m-d');
        $this->jam_tayang = Carbon::parse($jadwal->jam_tayang)->format('H:i');

        $film = Film::find($this->film_id);
        $this->film_duration = $film ? $film->durasi : 0;
    }

    public function updatedFilmId($value)
    {
        if ($value) {
            $film = Film::find($value);
            $this->film_duration = $film ? $film->durasi : 0;
        } else {
            $this->film_duration = 0;
        }
    }

    private function checkScheduleConflict()
    {
        if ($this->film_duration == 0) {
            return null;
        }

        $newStart = Carbon::parse($this->jam_tayang);
        $newEnd = $newStart->copy()->addMinutes($this->film_duration);

        // Ambil semua jadwal di studio dan tanggal yang sama (kecuali jadwal yang sedang diedit)
        $existingSchedules = JadwalTayang::where('studio_id', $this->studio_id)
            ->where('tanggal_tayang', $this->tanggal_tayang)
            ->where('id', '!=', $this->jadwalId)
            ->with('film')
            ->get();

        foreach ($existingSchedules as $schedule) {
            $existingStart = Carbon::parse($schedule->jam_tayang);
            $existingEnd = $existingStart->copy()->addMinutes($schedule->film->durasi);

            // Cek overlap: jadwal baru overlap dengan jadwal yang sudah ada
            if ($newStart->lt($existingEnd) && $newEnd->gt($existingStart)) {
                return "Bentrok dengan film '{$schedule->film->judul}' jam {$existingStart->format('H:i')} - {$existingEnd->format('H:i')}";
            }
        }

        return null;
    }

    public function update()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Check for schedule conflict with duration
            $conflict = $this->checkScheduleConflict();

            if ($conflict) {
                $this->addError('jam_tayang', $conflict);
                return;
            }

            $jadwal = JadwalTayang::findOrFail($this->jadwalId);
            $jadwal->update([
                'film_id' => $this->film_id,
                'studio_id' => $this->studio_id,
                'tanggal_tayang' => $this->tanggal_tayang,
                'jam_tayang' => $this->jam_tayang,
            ]);

            DB::commit();

            session()->flash('success', 'Jadwal tayang berhasil diperbarui');
            return redirect()->route('admin.jadwal-tayang.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $films = Film::where('status', 'tayang')->get();
        $studios = Studio::all();

        return view('livewire.admin.jadwal-tayang-edit', [
            'films' => $films,
            'studios' => $studios,
        ])->layout('admin.layouts.app');
    }
}
