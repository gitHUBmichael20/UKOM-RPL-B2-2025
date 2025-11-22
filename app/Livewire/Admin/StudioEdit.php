<?php

namespace App\Livewire\Admin;

use App\Models\Studio;
use App\Models\Kursi;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class StudioEdit extends Component
{
    public $studioId;
    public $nama_studio;
    public $tipe_studio;
    public $jumlah_baris;
    public $jumlah_kolom;
    public $kapasitas_lama;
    public $kapasitasBaru = 0;

    public function mount($id)
    {
        $studio = Studio::with('kursi')->findOrFail($id);

        $this->studioId      = $studio->id;
        $this->nama_studio   = $studio->nama_studio;
        $this->tipe_studio   = $studio->tipe_studio;
        $this->kapasitas_lama = $studio->kapasitas_kursi;

        $this->detectCurrentLayout($studio);
        $this->hitungKapasitasBaru();
    }

    private function detectCurrentLayout($studio)
    {
        $kursi = $studio->kursi;

        if ($kursi->isEmpty()) {
            $this->jumlah_baris = 5;
            $this->jumlah_kolom = 10;
            return;
        }

        $barisUnik = $kursi->pluck('nomor_kursi')
            ->map(fn($n) => $n[0])
            ->unique()
            ->sort()
            ->values();

        $this->jumlah_baris = $barisUnik->count();

        $firstRowSeats = $kursi->filter(fn($k) => $k->nomor_kursi[0] === $barisUnik->first())
            ->pluck('nomor_kursi');

        $kolomNumbers = $firstRowSeats->map(fn($n) => (int)substr($n, 1))->sort()->values();
        $this->jumlah_kolom = $kolomNumbers->last() ?? 10;
    }

    public function updatedJumlahBaris()
    {
        $this->hitungKapasitasBaru();
    }
    public function updatedJumlahKolom()
    {
        $this->hitungKapasitasBaru();
    }

    private function hitungKapasitasBaru()
    {
        $this->kapasitasBaru = ($this->jumlah_baris && $this->jumlah_kolom)
            ? $this->jumlah_baris * $this->jumlah_kolom
            : 0;
    }

    protected function rules()
    {
        return [
            'nama_studio' => 'required|string|max:50|unique:studio,nama_studio,' . $this->studioId,
            'jumlah_baris' => 'required|integer|min:2|max:26',
            'jumlah_kolom' => 'required|integer|min:4|max:20',
        ];
    }

    protected $messages = [
        'nama_studio.required' => 'Nama studio wajib diisi',
        'nama_studio.unique' => 'Nama studio sudah digunakan',
        'jumlah_baris.required' => 'Jumlah baris wajib diisi',
        'jumlah_baris.min' => 'Minimal 2 baris',
        'jumlah_baris.max' => 'Maksimal 26 baris (A-Z)',
        'jumlah_kolom.required' => 'Jumlah kolom wajib diisi',
        'jumlah_kolom.min' => 'Minimal 4 kolom',
        'jumlah_kolom.max' => 'Maksimal 20 kolom',
    ];

    public function update()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $studio = Studio::findOrFail($this->studioId);

            $studio->update([
                'nama_studio'     => $this->nama_studio,
                'kapasitas_kursi' => $this->kapasitasBaru,
            ]);

            // Regenerate kursi kalau kapasitas/layout berubah
            if ($this->kapasitasBaru != $this->kapasitas_lama || $this->isLayoutChanged($studio)) {
                $studio->kursi()->delete();
                $this->generateKursi($studio);
            }

            DB::commit();
            session()->flash('success', 'Studio berhasil diperbarui!');
            return redirect()->route('admin.studio.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal: ' . $e->getMessage());
        }
    }

    private function isLayoutChanged($studio)
    {
        $currentSeats = $studio->kursi()->pluck('nomor_kursi')->toArray();
        $expectedSeats = $this->generateSeatNumbers();

        sort($currentSeats);
        sort($expectedSeats);

        return $currentSeats !== $expectedSeats;
    }

    private function generateSeatNumbers()
    {
        $seats = [];
        $hurufBaris = range('A', 'Z');

        $kolomKiri = ceil($this->jumlah_kolom / 2);

        for ($baris = 0; $baris < $this->jumlah_baris; $baris++) {
            $huruf = $hurufBaris[$baris];

            for ($i = 1; $i <= $kolomKiri; $i++) {
                $seats[] = $huruf . $i;
            }
            for ($i = $kolomKiri + 1; $i <= $this->jumlah_kolom; $i++) {
                $seats[] = $huruf . $i;
            }
        }

        return $seats;
    }

    private function generateKursi($studio)
    {
        $kursiData = [];
        $hurufBaris = range('A', 'Z');
        $kolomKiri = ceil($this->jumlah_kolom / 2);

        for ($baris = 0; $baris < $this->jumlah_baris; $baris++) {
            $huruf = $hurufBaris[$baris];

            // Kiri
            for ($i = 1; $i <= $kolomKiri; $i++) {
                $kursiData[] = [
                    'studio_id' => $studio->id,
                    'nomor_kursi' => $huruf . $i,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Kanan
            for ($i = $kolomKiri + 1; $i <= $this->jumlah_kolom; $i++) {
                $kursiData[] = [
                    'studio_id' => $studio->id,
                    'nomor_kursi' => $huruf . $i,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Kursi::insert($kursiData);
    }

    public function render()
    {
        return view('livewire.admin.studio-edit')
            ->layout('admin.layouts.app');
    }
}
