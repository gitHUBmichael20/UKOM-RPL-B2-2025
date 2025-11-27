<?php
// app/Livewire/Kasir/PemesananKasir.php

namespace App\Livewire\Kasir;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pemesanan;
use App\Models\DetailPemesanan;
use App\Models\JadwalTayang;
use App\Models\HargaTiket;
use App\Models\Kursi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;

#[Layout('admin.layouts.app')]

class PemesananKasir extends Component
{
    use WithPagination;

    public $tab = 'buat-pemesanan';
    public $search = '';
    public $filterHariIni = true;
    public $perPage = 10;
    public $selectedPemesanan = null;
    public $showDetailModal = false;

    // Form data untuk buat pemesanan
    public $formStep = 1;
    public $selectedJadwal = null;
    public $selectedJadwalId = null;
    public $selectedKursi = [];
    public $totalHarga = 0;
    public $hargaTiket = null;
    public $bookedSeats = [];
    public $jadwals = [];

    protected $queryString = ['tab', 'search'];
    protected $listeners = ['$refresh'];

    public function mount()
    {
        if (auth()->user()->role !== 'kasir') {
            abort(403, 'Unauthorized');
        }

        $this->loadJadwals();
    }

    public function loadJadwals()
    {
        $this->jadwals = JadwalTayang::with(['film', 'studio'])
            ->where('tanggal_tayang', '>=', now()->toDateString())
            ->orderBy('tanggal_tayang')
            ->get()
            ->toArray();
    }

    public function switchTab($tabName)
    {
        $this->tab = $tabName;
        $this->formStep = 1;
        $this->resetForm();
    }

    public function selectJadwal($jadwalId)
    {
        $jadwal = JadwalTayang::with(['film', 'studio'])->find($jadwalId);

        if (!$jadwal) {
            session()->flash('error', 'Jadwal tidak ditemukan');
            return;
        }

        $this->selectedJadwal = $jadwal->toArray();
        $this->selectedJadwalId = $jadwal->id;
        $this->hargaTiket = HargaTiket::where('tipe_studio', $jadwal->studio->tipe_studio)->first();

        $this->loadBookedSeats();
        $this->calculateTotal();
        $this->formStep = 2;
    }

    public function loadBookedSeats()
    {
        if (!$this->selectedJadwalId) return;

        $this->bookedSeats = DetailPemesanan::whereHas('pemesanan', function ($q) {
            $q->where('jadwal_tayang_id', $this->selectedJadwalId);
        })
            ->pluck('kursi_id')
            ->toArray();
    }

    public function toggleKursi($kursiId)
    {
        if (in_array($kursiId, $this->bookedSeats)) {
            session()->flash('error', 'Kursi sudah terpesan');
            return;
        }

        if (in_array($kursiId, $this->selectedKursi)) {
            $this->selectedKursi = array_diff($this->selectedKursi, [$kursiId]);
        } else {
            $this->selectedKursi[] = $kursiId;
        }

        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->totalHarga = $this->hargaTiket
            ? count($this->selectedKursi) * $this->hargaTiket->harga
            : 0;
    }

    public function proceedToConfirm()
    {
        if (count($this->selectedKursi) === 0) {
            session()->flash('error', 'Pilih minimal 1 kursi');
            return;
        }

        $this->formStep = 3;
    }

    public function simpanPemesananOffline()
    {
        if (count($this->selectedKursi) === 0) {
            session()->flash('error', 'Tidak ada kursi yang dipilih');
            return;
        }

        try {

            do {
                $kodeBooking = 'BK' . now()->format('Ymd') . strtoupper(Str::random(6));
            } while (Pemesanan::where('kode_booking', $kodeBooking)->exists());

            $pemesanan = Pemesanan::create([
                'kode_booking' => $kodeBooking,
                'jadwal_tayang_id' => $this->selectedJadwalId,
                'jumlah_tiket' => count($this->selectedKursi),
                'total_harga' => $this->totalHarga,
                'tanggal_pemesanan' => now(),
                'status_pembayaran' => 'lunas',
                'jenis_pemesanan' => 'offline',
                'metode_pembayaran' => 'cash',
                'tanggal_pembayaran' => now(),
                'kasir_id' => auth()->id(),
                'catatan' => 'Pemesanan offline dari kasir'
            ]);

            foreach ($this->selectedKursi as $kursiId) {
                DetailPemesanan::create([
                    'pemesanan_id' => $pemesanan->id,
                    'kursi_id' => $kursiId,
                    'harga' => $this->hargaTiket->harga
                ]);
            }

            $this->resetForm();
            return redirect()->route('pemesanan.ticket', ['pemesanan' => $pemesanan->id]);

        } catch (\Exception $e) {
            \Log::error('Error simpan pemesanan offline: ' . $e->getMessage());
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function viewDetail($pemesananId)
    {
        $this->selectedPemesanan = Pemesanan::with([
            'user',
            'jadwalTayang.film',
            'jadwalTayang.studio',
            'detailPemesanan'
        ])->findOrFail($pemesananId);

        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedPemesanan = null;
    }

    public function resetForm()
    {
        $this->formStep = 1;
        $this->selectedJadwal = null;
        $this->selectedJadwalId = null;
        $this->selectedKursi = [];
        $this->totalHarga = 0;
        $this->hargaTiket = null;
        $this->bookedSeats = [];
    }

    public function render()
    {
        if ($this->tab === 'buat-pemesanan') {
            return view('livewire.kasir.pemesanan-kasir');
        }

        $query = Pemesanan::with(['user', 'detailPemesanan.jadwalTayang.film']);

        if ($this->filterHariIni) {
            $query->whereDate('created_at', today());
        }

        if ($this->search) {
            $query->where('kode_booking', 'like', '%' . $this->search . '%')
                ->orWhereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
        }

        $pemesanans = $query
            ->with(['user', 'jadwalTayang.film'])
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.kasir.pemesanan-kasir', [
            'pemesanans' => $pemesanans,
        ]);
    }
}