<?php
// app/Livewire/Kasir/RedeemTiket.php

namespace App\Livewire\Kasir;

use Livewire\Component;
use App\Models\Pemesanan;

class RedeemTiket extends Component
{
    public $scanInput = '';
    public $selectedPemesanan = null;
    public $redeemedPemesanan = null; // Untuk tiket yang sudah di-redeem
    public $showDetailModal = false;
    public $showCameraModal = false;
    public $showTicketModal = false; // Modal untuk print tiket
    public $message = null;
    public $messageType = null;

    public function updatedScanInput($value)
    {
        if (empty($value)) return;
        $this->searchTicket($value);
    }

    public function openCamera()
    {
        $this->showCameraModal = true;
        $this->dispatch('camera-opened');
    }

    public function closeCamera()
    {
        $this->showCameraModal = false;
        $this->dispatch('camera-closed');
    }

    public function searchTicket($kodeBooking)
    {
        $kodeBooking = trim($kodeBooking);
        
        $pemesanan = Pemesanan::where('kode_booking', $kodeBooking)
            ->with([
                'user', 
                'jadwalTayang.film', 
                'jadwalTayang.studio', 
                'detailPemesanan.kursi'
            ])
            ->first();

        if (!$pemesanan) {
            $this->message = 'Tiket tidak ditemukan!';
            $this->messageType = 'error';
            $this->scanInput = '';
            return;
        }

        if ($pemesanan->status_pembayaran !== 'lunas') {
            $this->message = 'Status pembayaran belum lunas!';
            $this->messageType = 'error';
            $this->scanInput = '';
            return;
        }

        if ($pemesanan->status_redeem === 'redeemed') {
            $this->message = 'Tiket sudah di-redeem sebelumnya!';
            $this->messageType = 'info';
            $this->scanInput = '';
            return;
        }

        $this->selectedPemesanan = $pemesanan;
        $this->showDetailModal = true;
        $this->scanInput = '';
    }

    public function redeemTiket()
    {
        if (!$this->selectedPemesanan) {
            $this->message = 'Pilih tiket terlebih dahulu!';
            $this->messageType = 'error';
            return;
        }

        try {
            $this->selectedPemesanan->update([
                'status_redeem' => 'redeemed',
                'tanggal_redeem' => now()
            ]);

            $this->message = 'Tiket berhasil di-redeem!';
            $this->messageType = 'success';
            
            // Simpan data untuk modal ticket
            $this->redeemedPemesanan = $this->selectedPemesanan->fresh([
                'user', 
                'jadwalTayang.film', 
                'jadwalTayang.studio', 
                'detailPemesanan.kursi'
            ]);
            
            // Tutup modal detail, buka modal ticket
            $this->closeDetailModal();
            $this->showTicketModal = true;
            
        } catch (\Exception $e) {
            $this->message = 'Error: ' . $e->getMessage();
            $this->messageType = 'error';
        }
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedPemesanan = null;
    }

    public function closeTicketModal()
    {
        $this->showTicketModal = false;
        $this->redeemedPemesanan = null;
    }

    public function render()
    {
        return view('livewire.kasir.redeem-tiket')
            ->layout('admin.layouts.app');
    }
}