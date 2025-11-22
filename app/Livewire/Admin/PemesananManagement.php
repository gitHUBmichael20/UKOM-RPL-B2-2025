<?php

namespace App\Livewire\Admin;

use App\Models\Pemesanan;
use Livewire\Component;
use Livewire\WithPagination;

class PemesananManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';

    // 🔥 MODAL PROPERTIES (tambahan)
    public $showTicketModal = false;
    public $selectedTicket = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        try {
            $pemesanan = Pemesanan::findOrFail($id);
            $pemesanan->delete();

            $this->dispatch('success', 'Pemesanan berhasil dihapus.');
        } catch (\Exception $e) {
            $this->dispatch('error', 'Gagal menghapus pemesanan: ' . $e->getMessage());
        }
    }

    // 🔥 OPEN MODAL (tambahan)
    public function openTicketModal($id)
    {
        $this->selectedTicket = Pemesanan::with([
            'user',
            'jadwalTayang.film',
            'jadwalTayang.studio',
            'detailPemesanan.kursi',
        ])->find($id);

        $this->showTicketModal = true;
    }

    // 🔥 CLOSE MODAL (tambahan)
    public function closeTicketModal()
    {
        $this->showTicketModal = false;
        $this->selectedTicket = null;
    }

    public function render()
    {
        $query = Pemesanan::with([
            'user',
            'jadwalTayang.film',
            'jadwalTayang.studio',
            'detailPemesanan.kursi'
        ]);

        // Filter search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('kode_booking', 'like', '%' . $this->search . '%')
                    ->orWhereHas('jadwalTayang.film', function ($q) {
                        $q->where('judul', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('user', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        // Filter status
        if ($this->filterStatus) {
            $query->where('status_pembayaran', $this->filterStatus);
        }

        $pemesanan = $query->orderBy('tanggal_pemesanan', 'desc')
            ->paginate(10);

        return view('livewire.admin.pemesanan-management', compact('pemesanan'))
            ->layout('admin.layouts.app');
    }
}
