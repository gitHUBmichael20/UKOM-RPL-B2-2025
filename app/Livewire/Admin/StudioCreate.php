<?php

namespace App\Livewire\Admin;

use App\Models\Studio;
use App\Models\Kursi;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class StudioCreate extends Component
{
    public $nama_studio = '';
    public $jumlah_baris = '';
    public $jumlah_kolom = 10;
    public $tipe_studio = 'regular';

    protected $rules = [
        'nama_studio' => 'required|string|max:50|unique:studio,nama_studio',
        'jumlah_baris' => 'required|integer|min:2|max:26',
        'jumlah_kolom' => 'required|integer|min:4|max:20',
        'tipe_studio' => 'required|in:regular,deluxe,imax',
    ];

    protected $messages = [
        'nama_studio.required' => 'Nama studio wajib diisi',
        'nama_studio.unique' => 'Nama studio sudah digunakan',
        'jumlah_baris.required' => 'Jumlah baris wajib diisi',
        'jumlah_baris.min' => 'Minimal 2 baris',
        'jumlah_baris.max' => 'Maksimal 26 baris (A-Z)',
        'jumlah_kolom.required' => 'Jumlah kolom wajib diisi',
        'jumlah_kolom.min' => 'Minimal 4 kolom',
        'jumlah_kolom.max' => 'Maksimal 20 kolom',
        'tipe_studio.required' => 'Tipe studio wajib dipilih',
    ];

    public function getKapasitasKursiProperty()
    {
        if ($this->jumlah_baris && $this->jumlah_kolom) {
            return $this->jumlah_baris * $this->jumlah_kolom;
        }
        return 0;
    }

    public function save()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $kapasitas = $this->kapasitasKursi;

            $studio = Studio::create([
                'nama_studio' => $this->nama_studio,
                'kapasitas_kursi' => $kapasitas,
                'tipe_studio' => $this->tipe_studio,
            ]);

            $this->generateKursi($studio);

            DB::commit();

            session()->flash('success', "Studio berhasil ditambahkan dengan layout {$this->jumlah_baris} baris × {$this->jumlah_kolom} kolom ({$kapasitas} kursi)!");
            return redirect()->route('admin.studio.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal menambahkan studio: ' . $e->getMessage());
        }
    }

    private function generateKursi($studio)
    {
        $kursiData = [];
        $hurufBaris = range('A', 'Z');

        // Jika ganjil, sisi kiri lebih banyak 1
        $kolomKiri = ceil($this->jumlah_kolom / 2);

        for ($baris = 0; $baris < $this->jumlah_baris; $baris++) {
            $huruf = $hurufBaris[$baris];

            // Sisi kiri
            for ($i = 1; $i <= $kolomKiri; $i++) {
                $kursiData[] = [
                    'studio_id' => $studio->id,
                    'nomor_kursi' => $huruf . $i,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Sisi kanan
            for ($i = ($kolomKiri + 1); $i <= $this->jumlah_kolom; $i++) {
                $kursiData[] = [
                    'studio_id' => $studio->id,
                    'nomor_kursi' => $huruf . $i,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (!empty($kursiData)) {
            Kursi::insert($kursiData);
        }
    }

    public function render()
    {
        return view('livewire.admin.studio-create')
            ->layout('admin.layouts.app');
    }
}
