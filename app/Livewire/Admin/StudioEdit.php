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
    public $kapasitas_kursi;
    public $tipe_studio;
    public $kapasitas_lama;

    public function mount($id)
    {
        $studio = Studio::findOrFail($id);

        $this->studioId = $studio->id;
        $this->nama_studio = $studio->nama_studio;
        $this->kapasitas_kursi = $studio->kapasitas_kursi;
        $this->tipe_studio = $studio->tipe_studio;
        $this->kapasitas_lama = $studio->kapasitas_kursi;
    }

    protected function rules()
    {
        return [
            'nama_studio' => 'required|string|max:50|unique:studio,nama_studio,' . $this->studioId,
            'kapasitas_kursi' => 'required|integer|min:20|max:200',
            'tipe_studio' => 'required|in:regular,deluxe,imax',
        ];
    }

    protected $messages = [
        'nama_studio.required' => 'Nama studio wajib diisi',
        'nama_studio.unique' => 'Nama studio sudah digunakan',
        'kapasitas_kursi.required' => 'Kapasitas kursi wajib diisi',
        'kapasitas_kursi.min' => 'Kapasitas minimal 20 kursi',
        'kapasitas_kursi.max' => 'Kapasitas maksimal 200 kursi',
        'tipe_studio.required' => 'Tipe studio wajib dipilih',
    ];

    public function update()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $studio = Studio::findOrFail($this->studioId);

            // Update studio
            $studio->update([
                'nama_studio' => $this->nama_studio,
                'kapasitas_kursi' => $this->kapasitas_kursi,
                'tipe_studio' => $this->tipe_studio,
            ]);

            // Regenerate kursi jika kapasitas berubah
            if ($this->kapasitas_kursi != $this->kapasitas_lama) {
                // Hapus kursi lama
                $studio->kursi()->delete();

                // Generate kursi baru
                $this->generateKursi($studio);
            }

            DB::commit();

            session()->flash('success', 'Studio berhasil diperbarui!');
            return redirect()->route('admin.studio.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal memperbarui studio: ' . $e->getMessage());
        }
    }

    private function generateKursi($studio)
    {
        $kapasitas = $studio->kapasitas_kursi;

        // Hitung jumlah baris dan kolom
        $seatsPerRow = 10; // 5 kiri + 5 kanan dengan gang tengah
        $jumlahBaris = ceil($kapasitas / $seatsPerRow);

        $kursiData = [];
        $hurufBaris = range('A', 'Z');
        $nomorKursi = 1;

        for ($baris = 0; $baris < $jumlahBaris; $baris++) {
            $huruf = $hurufBaris[$baris];

            // Sisi kiri (5 kursi)
            for ($i = 1; $i <= 5; $i++) {
                if ($nomorKursi > $kapasitas) break;

                $kursiData[] = [
                    'studio_id' => $studio->id,
                    'nomor_kursi' => $huruf . $i,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $nomorKursi++;
            }

            if ($nomorKursi > $kapasitas) break;

            // Sisi kanan (5 kursi)
            for ($i = 6; $i <= 10; $i++) {
                if ($nomorKursi > $kapasitas) break;

                $kursiData[] = [
                    'studio_id' => $studio->id,
                    'nomor_kursi' => $huruf . $i,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $nomorKursi++;
            }
        }

        // Insert kursi in batch
        if (!empty($kursiData)) {
            Kursi::insert($kursiData);
        }
    }

    public function render()
    {
        return view('livewire.admin.studio-edit')
            ->layout('admin.layouts.app');
    }
}