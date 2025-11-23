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
use Illuminate\Support\Facades\Redirect;

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

        // AMBIL SEMUA KURSI YANG SUDAH DIPESAN (BAIK STATUS APA PUN)
        // Karena kursi yang sudah di-detail tidak boleh dipesan ulang
        $this->bookedSeats = DetailPemesanan::where('jadwal_tayang_id', $this->selectedJadwalId)
            ->pluck('kursi_id')
            ->toArray();

        // DEBUG: Log untuk cek
        \Log::info('Booked seats for jadwal ' . $this->selectedJadwalId . ':', $this->bookedSeats);
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
            $pemesanan = Pemesanan::create([
                'user_id' => auth()->id(),
                'kode_booking' => 'BK' . now()->format('YmdHi') . rand(1000, 9999),
                'jadwal_tayang_id' => $this->selectedJadwalId,
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
                    'jadwal_tayang_id' => $this->selectedJadwalId,
                    'kursi_id' => $kursiId,
                    'harga' => $this->hargaTiket->harga
                ]);
            }

            $this->resetForm();
            session()->flash('success', 'Pemesanan berhasil! Tiket siap dicetak.');
            $this->tab = 'hari-ini';

            // Redirect ke tab hari-ini atau route khusus tiket jika ada
            // return redirect()->route('ticket.show', $pemesanan->kode_booking);
            // Jika route ticket.show tidak ada, gunakan salah satu di bawah:
            
            // OPSI 1: Redirect ke halaman hari-ini (paling aman)
            return redirect()->to(route('livewire', ['component' => 'kasir.pemesanan-kasir']) . '?tab=hari-ini')
                ->with('success', 'Pemesanan berhasil! Tiket siap dicetak. Kode: ' . $pemesanan->kode_booking);
            
            // OPSI 2: Jika ada route untuk print tiket
            // return redirect()->route('ticket.print', $pemesanan->id);
            
            // OPSI 3: Refresh halaman dengan flash message
            // return redirect()->back()->with('success', 'Pemesanan berhasil! Kode: ' . $pemesanan->kode_booking);

        } catch (\Exception $e) {
            \Log::error('Error simpan pemesanan offline: ' . $e->getMessage());
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
