<?php

namespace App\Livewire\Admin;

use App\Models\HargaTiket;
use Livewire\Component;

class HargaTiketCreate extends Component
{
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

    public function mount()
    {
        // Cek apakah user adalah admin
        if (!isRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function save()
    {
        $this->validate();

        try {
            // Cek apakah kombinasi tipe_studio dan tipe_hari sudah ada
            $exists = HargaTiket::where('tipe_studio', $this->tipe_studio)
                                ->where('tipe_hari', $this->tipe_hari)
                                ->exists();

            if ($exists) {
                session()->flash('error', 'Kombinasi tipe studio dan tipe hari sudah ada!');
                return;
            }

            HargaTiket::create([
                'tipe_studio' => $this->tipe_studio,
                'tipe_hari' => $this->tipe_hari,
                'harga' => $this->harga
            ]);

            session()->flash('success', 'Harga tiket berhasil ditambahkan!');
            return redirect()->route('admin.harga-tiket.management');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menambahkan harga tiket: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.harga-tiket-create', [
            'tipeStudioOptions' => HargaTiket::TIPE_STUDIO,
            'tipeHariOptions' => HargaTiket::TIPE_HARI
        ])->layout('admin.layouts.app');
    }
}