<?php

namespace App\Livewire;

use App\Models\Pemesanan;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CheckOrder extends Component
{
    public $orders = [];

    public function mount()
    {
        if (Auth::check()) {
            $this->orders = Pemesanan::with([
                'jadwalTayang.film.sutradara',
                'jadwalTayang.film.genres',
                'detailPemesanan.kursi'
            ])
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.check-order')
            ->layout('layouts.app'); // ← TAMBAH INI
    }
}
