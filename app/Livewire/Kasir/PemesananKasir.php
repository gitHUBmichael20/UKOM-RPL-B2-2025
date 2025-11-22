<?php
// app/Livewire/Kasir/PemesananKasir.php

namespace App\Livewire\Kasir;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pemesanan;
use App\Models\DetailPemesanan;
use App\Models\JadwalTayang;
use App\Models\HargaTiket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;

#[Layout('admin.layouts.app')]


class PemesananKasir extends Component
{
    use WithPagination;

    public $tab = 'buat-pemesanan'; // buat-pemesanan, hari-ini, detail
    public $search = '';
    public $filterHariIni = true;
    public $perPage = 10;
    public $selectedPemesanan = null;
    public $showDetailModal = false;

    // Form data untuk buat pemesanan
    public $formStep = 1;
    public $selectedJadwal = null;
    public $selectedKursi = [];
    public $totalHarga = 0;
    public $hargaTiket = null;
    public $bookedSeats = [];
    public $jadwals = [];
    protected $queryString = ['tab', 'search'];

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

    public function loadUsers()
    {
        $this->users = User::where('role', 'user')
            ->select('id', 'name', 'email', 'phone')
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
        $this->loadBookedSeats();
        $this->hargaTiket = HargaTiket::where('tipe_studio', $jadwal->studio->tipe_studio)->first();
        $this->formStep = 2;
    }

    public function loadBookedSeats()
    {
        if (!$this->selectedJadwal) return;

        $this->bookedSeats = DetailPemesanan::where('jadwal_tayang_id', $this->selectedJadwal['id'])
            ->whereHas('pemesanan', function ($q) {
                $q->whereIn('status_pembayaran', ['pending', 'lunas']);
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
        if ($this->hargaTiket && count($this->selectedKursi) > 0) {
            $this->totalHarga = count($this->selectedKursi) * $this->hargaTiket->harga;
        } else {
            $this->totalHarga = 0;
        }
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
                'user_id' => null,
                'kode_booking' => $kodeBooking,
                'jadwal_tayang_id' => $this->selectedJadwal['id'],
                'jumlah_tiket' => count($this->selectedKursi),
                'total_harga' => $this->totalHarga,
                'tanggal_pemesanan' => now(),
                'status_pemesanan' => 'dikonfirmasi',
                'status_pembayaran' => 'lunas',
                'metode_pembayaran' => 'cash',
                'tanggal_pembayaran' => now(),
                'jenis_pemesanan' => 'offline',
                'kasir_id' => auth()->id(),
                'catatan' => 'Pemesanan offline dari kasir'
            ]);

            foreach ($this->selectedKursi as $kursiId) {
                DetailPemesanan::create([
                    'pemesanan_id' => $pemesanan->id,
                    'jadwal_tayang_id' => $this->selectedJadwal['id'],
                    'kursi_id' => $kursiId,
                    'harga' => $this->hargaTiket->harga
                ]);
            }

            session()->flash('success', 'Pemesanan offline berhasil dibuat! Kode: ' . $pemesanan->kode_booking);
            $this->resetForm();
            $this->tab = 'hari-ini';
        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function viewDetail($pemesananId)
    {
        $this->selectedPemesanan = Pemesanan::with(['user', 'detailPemesanan.jadwalTayang.film', 'detailPemesanan.jadwalTayang.studio'])
            ->find($pemesananId);
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

        $query = Pemesanan::with(['user', 'detailPemesanan']);

        if ($this->filterHariIni) {
            $query->whereDate('created_at', today());
        }

        if ($this->search) {
            $query->where('kode_booking', 'like', '%' . $this->search . '%')
                ->orWhereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
        }

        $pemesanans = $query->latest()->paginate($this->perPage);

        return view('livewire.kasir.pemesanan-kasir', [
            'pemesanans' => $pemesanans,
        ])->layout('admin.layouts.app');
    }
}
