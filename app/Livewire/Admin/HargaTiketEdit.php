<?php

namespace App\Livewire\Admin;

use App\Models\HargaTiket;
use Livewire\Component;

class HargaTiketEdit extends Component
{
    public $hargaTiketId;
    public $tipe_studio = '';
    public $tipe_hari = '';
    public $harga = '';

    protected $rules = [
        'tipe_studio' => 'required|in:regular,deluxe,imax',
        'tipe_hari' => 'required|in:weekday,weekend',
        'harga' => 'required|numeric|min:0'
    ];

    protected $messages = [
        'tipe_studio.required' => 'Tipe studio harus dipilih.',
        'tipe_studio.in' => 'Tipe studio tidak valid.',
        'tipe_hari.required' => 'Tipe hari harus dipilih.',
        'tipe_hari.in' => 'Tipe hari tidak valid.',
        'harga.required' => 'Harga harus diisi.',
        'harga.numeric' => 'Harga harus berupa angka.',
        'harga.min' => 'Harga minimal adalah 0.'
    ];

    public function mount($id)
    {
        // Cek apakah user adalah admin
        if (!isRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $hargaTiket = HargaTiket::findOrFail($id);
        $this->hargaTiketId = $hargaTiket->id;
        $this->tipe_studio = $hargaTiket->tipe_studio;
        $this->tipe_hari = $hargaTiket->tipe_hari;
        $this->harga = $hargaTiket->harga;
    }

    public function update()
    {
        $this->validate();

        try {
            $hargaTiket = HargaTiket::findOrFail($this->hargaTiketId);

            // Cek apakah kombinasi tipe_studio dan tipe_hari sudah ada (kecuali data yang sedang diedit)
            $exists = HargaTiket::where('tipe_studio', $this->tipe_studio)
                                ->where('tipe_hari', $this->tipe_hari)
                                ->where('id', '!=', $this->hargaTiketId)
                                ->exists();

            if ($exists) {
                session()->flash('error', 'Kombinasi tipe studio dan tipe hari sudah ada!');
                return;
            }

            $hargaTiket->update([
                'tipe_studio' => $this->tipe_studio,
                'tipe_hari' => $this->tipe_hari,
                'harga' => $this->harga
            ]);

            session()->flash('success', 'Harga tiket berhasil diupdate!');
            return redirect()->route('admin.harga-tiket.management');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengupdate harga tiket: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.harga-tiket-edit', [
            'tipeStudioOptions' => HargaTiket::TIPE_STUDIO,
            'tipeHariOptions' => HargaTiket::TIPE_HARI
        ])->layout('admin.layouts.app');
    }
}