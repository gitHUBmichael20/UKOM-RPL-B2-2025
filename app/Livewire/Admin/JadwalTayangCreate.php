<?php

namespace App\Livewire\Admin;

use App\Models\Film;
use App\Models\JadwalTayang;
use App\Models\Studio;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JadwalTayangCreate extends Component
{
    public $film_id;
    public $studio_id;
    public $tanggal_tayang;
    public $jam_tayang = [];
    public $film_duration = 0;
    public $new_jam = '';

    public function mount()
    {
        $this->tanggal_tayang = now()->format('Y-m-d');
    }

    protected function rules()
    {
        return [
            'film_id' => 'required|exists:film,id',
            'studio_id' => 'required|exists:studio,id',
            'tanggal_tayang' => 'required|date|after_or_equal:today',
            'jam_tayang' => 'required|array|min:1',
            'jam_tayang.*' => 'required|date_format:H:i',
        ];
    }

    protected $messages = [
        'film_id.required' => 'Film harus dipilih',
        'studio_id.required' => 'Studio harus dipilih',
        'tanggal_tayang.required' => 'Tanggal tayang harus diisi',
        'tanggal_tayang.after_or_equal' => 'Tanggal tayang tidak boleh kurang dari hari ini',
        'jam_tayang.required' => 'Minimal harus ada 1 jam tayang',
        'jam_tayang.min' => 'Minimal harus ada 1 jam tayang',
        'jam_tayang.*.required' => 'Jam tayang harus diisi',
        'jam_tayang.*.date_format' => 'Format jam tidak valid (HH:mm)',
    ];

    public function updatedFilmId($value)
    {
        if ($value) {
            $film = Film::find($value);
            $this->film_duration = $film ? $film->durasi : 0;
        } else {
            $this->film_duration = 0;
        }
    }

    public function addJamTayang()
    {
        if (empty($this->new_jam)) {
            $this->addError('new_jam', 'Jam tayang harus diisi');
            return;
        }

        // Validasi format waktu
        if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $this->new_jam)) {
            $this->addError('new_jam', 'Format jam tidak valid (HH:mm)');
            return;
        }

        // Cek duplikat
        if (in_array($this->new_jam, $this->jam_tayang)) {
            $this->addError('new_jam', 'Jam tayang sudah ditambahkan');
            return;
        }

        // Cek konflik dengan jadwal yang sudah ada di form
        $conflict = $this->checkScheduleConflictInForm($this->new_jam);
        if ($conflict) {
            $this->addError('new_jam', $conflict);
            return;
        }

        // Cek konflik dengan data di database
        if ($this->film_id && $this->studio_id && $this->tanggal_tayang) {
            $dbConflict = $this->checkDatabaseConflict($this->new_jam);
            if ($dbConflict) {
                $this->addError('new_jam', $dbConflict);
                return;
            }
        }

        $this->jam_tayang[] = $this->new_jam;

        // Sort jam tayang
        sort($this->jam_tayang);

        $this->new_jam = '';
        $this->resetErrorBag('new_jam');
    }

    public function removeJamTayang($index)
    {
        unset($this->jam_tayang[$index]);
        $this->jam_tayang = array_values($this->jam_tayang);
    }

    private function checkScheduleConflictInForm($newTime)
    {
        if ($this->film_duration == 0) {
            return null;
        }

        $newStart = Carbon::parse($newTime);
        $newEnd = $newStart->copy()->addMinutes($this->film_duration);

        foreach ($this->jam_tayang as $existingTime) {
            $existingStart = Carbon::parse($existingTime);
            $existingEnd = $existingStart->copy()->addMinutes($this->film_duration);

            // Cek overlap
            if ($newStart->lt($existingEnd) && $newEnd->gt($existingStart)) {
                return "Bentrok dengan jadwal jam {$existingTime} (film selesai {$existingEnd->format('H:i')})";
            }
        }

        return null;
    }

    private function checkDatabaseConflict($newTime)
    {
        if ($this->film_duration == 0) {
            return null;
        }

        $newStart = Carbon::parse($newTime);
        $newEnd = $newStart->copy()->addMinutes($this->film_duration);

        // Ambil semua jadwal di studio dan tanggal yang sama
        $existingSchedules = JadwalTayang::where('studio_id', $this->studio_id)
            ->where('tanggal_tayang', $this->tanggal_tayang)
            ->with('film')
            ->get();

        foreach ($existingSchedules as $schedule) {
            $existingStart = Carbon::parse($schedule->jam_tayang);
            $existingEnd = $existingStart->copy()->addMinutes($schedule->film->durasi);

            // Cek overlap
            if ($newStart->lt($existingEnd) && $newEnd->gt($existingStart)) {
                return "Bentrok dengan film '{$schedule->film->judul}' jam {$existingStart->format('H:i')} - {$existingEnd->format('H:i')}";
            }
        }

        return null;
    }

    public function save()
    {
        $this->validate();

        if (empty($this->jam_tayang)) {
            $this->addError('jam_tayang', 'Minimal harus ada 1 jam tayang');
            return;
        }

        try {
            DB::beginTransaction();

            $created = 0;
            $errors = [];

            foreach ($this->jam_tayang as $jam) {
                // Double check konflik database sebelum save
                $conflict = $this->checkDatabaseConflict($jam);

                if ($conflict) {
                    $errors[] = "Jam {$jam}: {$conflict}";
                    continue;
                }

                JadwalTayang::create([
                    'film_id' => $this->film_id,
                    'studio_id' => $this->studio_id,
                    'tanggal_tayang' => $this->tanggal_tayang,
                    'jam_tayang' => $jam,
                ]);

                $created++;
            }

            DB::commit();

            if ($created > 0) {
                $message = $created . ' jadwal tayang berhasil ditambahkan';
                if (!empty($errors)) {
                    $message .= '. Namun ' . count($errors) . ' jadwal gagal: ' . implode(', ', $errors);
                }
                session()->flash('success', $message);
                return redirect()->route('admin.jadwal-tayang.index');
            } else {
                DB::rollBack();
                session()->flash('error', 'Tidak ada jadwal yang berhasil ditambahkan. Error: ' . implode(', ', $errors));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $films = Film::where('status', 'tayang')->get();
        $studios = Studio::all();

        return view('livewire.admin.jadwal-tayang-create', [
            'films' => $films,
            'studios' => $studios,
        ])->layout('admin.layouts.app');
    }
}
