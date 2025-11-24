<!-- resources/views/livewire/kasir/pemesanan-kasir.blade.php -->

<div class="py-8 px-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Kasir - Pemesanan Tiket</h1>
        <p class="text-gray-600 mt-2">Kelola pemesanan tiket dan pelanggan</p>
    </div>

    <!-- Alert Messages -->
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- Tabs -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="flex border-b border-gray-200">
            <button wire:click="switchTab('buat-pemesanan')"
                    class="flex-1 px-6 py-4 font-medium text-center border-b-2 transition
                    {{ $tab === 'buat-pemesanan' ? 'text-teal-600 border-teal-600' : 'text-gray-600 border-transparent hover:text-gray-900' }}">
                <i class="fas fa-plus-circle mr-2"></i>Buat Pemesanan Offline
            </button>
            <button wire:click="switchTab('hari-ini')"
                    class="flex-1 px-6 py-4 font-medium text-center border-b-2 transition
                    {{ $tab === 'hari-ini' ? 'text-teal-600 border-teal-600' : 'text-gray-600 border-transparent hover:text-gray-900' }}">
                <i class="fas fa-calendar-day mr-2"></i>Pemesanan Hari Ini
            </button>
        </div>
    </div>

    <!-- TAB: Buat Pemesanan Offline -->
    @if ($tab === 'buat-pemesanan')
        <div class="space-y-6">
            <!-- Progress Steps -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center">
                    @for ($i = 1; $i <= 3; $i++)
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-semibold text-white
                                {{ $formStep >= $i ? 'bg-teal-600' : 'bg-gray-300' }}">
                                @if ($formStep > $i)
                                    ✓
                                @else
                                    {{ $i }}
                                @endif
                            </div>
                            <span class="ml-2 font-medium {{ $formStep >= $i ? 'text-teal-600' : 'text-gray-500' }}">
                                @switch($i)
                                    @case(1)
                                        Pilih Jadwal
                                    @break
                                    @case(2)
                                        Pilih Kursi
                                    @break
                                    @case(3)
                                        Konfirmasi
                                    @break
                                @endswitch
                            </span>
                        </div>
                        @if ($i < 3)
                            <div class="flex-1 h-1 mx-2 {{ $formStep > $i ? 'bg-teal-600' : 'bg-gray-300' }}"></div>
                        @endif
                    @endfor
                </div>
            </div>

            <!-- STEP 1: Pilih Jadwal -->
            @if ($formStep === 1)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Pilih Film & Jadwal Tayang</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse ($jadwals as $jadwal)
                            <button wire:click="selectJadwal({{ $jadwal['id'] }})"
                                    class="p-4 border-2 border-gray-200 rounded-lg hover:border-teal-500 hover:bg-teal-50 transition text-left">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900">{{ $jadwal['film']['judul'] }}</h3>
                                        <p class="text-sm text-gray-600 mt-1">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ \Carbon\Carbon::parse($jadwal['tanggal_tayang'])->format('d M Y') }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ \Carbon\Carbon::parse($jadwal['jam_tayang'])->format('H:i') }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            <i class="fas fa-video mr-1"></i>
                                            {{ $jadwal['studio']['nama_studio'] }}
                                        </p>
                                    </div>
                                </div>
                            </button>
                        @empty
                            <div class="col-span-2 p-6 text-center bg-gray-50 rounded-lg">
                                <p class="text-gray-500">Tidak ada jadwal tayang tersedia</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif

            <!-- STEP 2: Pilih Kursi -->
            @if ($formStep === 2 && $selectedJadwal)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Pilih Kursi -->
                    <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-bold text-gray-900">Pilih Kursi</h2>
                            <div class="flex gap-4 text-sm">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                                    <span>Tersedia</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                                    <span>Terpesan</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-blue-500 rounded mr-2"></div>
                                    <span>Dipilih</span>
                                </div>
                            </div>
                        </div>

                        <!-- Screen -->
                        <div class="mb-8 text-center">
                            <div class="bg-gray-800 text-white py-3 rounded-lg mx-auto max-w-md">
                                <span class="font-semibold">SCREEN</span>
                            </div>
                            <p class="text-sm text-gray-500 mt-2">Layar Utama</p>
                        </div>

                        <!-- Seat Map -->
                        <div class="flex justify-center overflow-x-auto">
                            <div class="space-y-3">
                                @if($selectedJadwal && isset($selectedJadwal['studio']['id']))
                                    @php
                                        $kursis = \App\Models\Kursi::where('studio_id', $selectedJadwal['studio']['id'])
                                            ->get()
                                            ->sortBy(function($item) {
                                                $row = substr($item->nomor_kursi, 0, 1);
                                                $col = (int)substr($item->nomor_kursi, 1);
                                                return $row . str_pad($col, 3, '0', STR_PAD_LEFT);
                                            });
                                        
                                        $kursiByBaris = $kursis->groupBy(function ($kursi) {
                                            return substr($kursi->nomor_kursi, 0, 1);
                                        })->sortKeys();

                                        $totalKolom = $kursis->max(function ($kursi) {
                                            return (int) substr($kursi->nomor_kursi, 1);
                                        });

                                        $gangPosition = $totalKolom ? ceil($totalKolom / 2) : 6;
                                    @endphp

                                    @foreach($kursiByBaris as $baris => $kursiList)
                                        <div class="flex items-center justify-center gap-2">
                                            <!-- Label Baris Kiri -->
                                            <div class="w-6 text-center font-bold text-gray-700 text-sm flex-shrink-0">
                                                {{ $baris }}
                                            </div>

                                            <!-- Kursi Bagian Kiri (Sebelum Gang) -->
                                            <div class="flex items-center gap-1.5">
                                                @php
                                                    $sortedKursi = $kursiList->sortBy(function ($kursi) {
                                                        return (int) substr($kursi->nomor_kursi, 1);
                                                    });
                                                @endphp

                                                @foreach($sortedKursi as $kursi)
                                                    @php
                                                        $nomorKolom = (int) substr($kursi->nomor_kursi, 1);
                                                        $isBooked = in_array($kursi->id, $bookedSeats);
                                                        $isSelected = in_array($kursi->id, $selectedKursi);
                                                    @endphp

                                                    @if($nomorKolom <= $gangPosition)
                                                        <button wire:click.prevent="toggleKursi({{ $kursi->id }})"
                                                                type="button"
                                                                class="w-8 h-8 rounded text-xs font-semibold transition
                                                                {{ $isBooked ? 'bg-red-500 cursor-not-allowed' : ($isSelected ? 'bg-blue-500 text-white' : 'bg-green-500 hover:bg-green-600 text-white') }}"
                                                                {{ $isBooked ? 'disabled' : '' }}
                                                                title="{{ $kursi->nomor_kursi }}">
                                                                {{ $nomorKolom }}
                                                        </button>
                                                    @endif
                                                @endforeach
                                            </div>

                                            <!-- Gang / Aisle -->
                                            <div class="w-8 flex items-center justify-center text-gray-400 flex-shrink-0">
                                                <i class="fa-solid fa-grip-lines-vertical text-sm"></i>
                                            </div>

                                            <!-- Kursi Bagian Kanan (Setelah Gang) -->
                                            <div class="flex items-center gap-1.5">
                                                @foreach($sortedKursi as $kursi)
                                                    @php
                                                        $nomorKolom = (int) substr($kursi->nomor_kursi, 1);
                                                        $isBooked = in_array($kursi->id, $bookedSeats);
                                                        $isSelected = in_array($kursi->id, $selectedKursi);
                                                    @endphp

                                                    @if($nomorKolom > $gangPosition)
                                                        <button wire:click.prevent="toggleKursi({{ $kursi->id }})"
                                                                type="button"
                                                                class="w-8 h-8 rounded text-xs font-semibold transition
                                                                {{ $isBooked ? 'bg-red-500 cursor-not-allowed' : ($isSelected ? 'bg-blue-500 text-white' : 'bg-green-500 hover:bg-green-600 text-white') }}"
                                                                {{ $isBooked ? 'disabled' : '' }}
                                                                title="{{ $kursi->nomor_kursi }}">
                                                                {{ $nomorKolom }}
                                                        </button>
                                                    @endif
                                                @endforeach
                                            </div>

                                            <!-- Label Baris Kanan -->
                                            <div class="w-6 text-center font-bold text-gray-700 text-sm flex-shrink-0">
                                                {{ $baris }}
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="py-10 text-center text-gray-500">
                                        Memuat kursi...
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Selected Seats -->
                        @if (count($selectedKursi) > 0)
                            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                                <h3 class="font-semibold text-blue-800 mb-2">Kursi Terpilih</h3>
                                <div class="flex flex-wrap gap-2">
                                    @php
                                        $selectedSeatsNumbers = \App\Models\Kursi::whereIn('id', $selectedKursi)->pluck('nomor_kursi')->toArray();
                                    @endphp
                                    @foreach ($selectedSeatsNumbers as $seatNum)
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                            {{ $seatNum }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Summary -->
                    <div class="bg-white rounded-lg shadow p-6 h-fit">
                        <h3 class="font-bold text-gray-900 mb-4">Ringkasan</h3>
                        
                        <div class="space-y-3 text-sm mb-4">
                            <div>
                                <span class="text-gray-600">Film</span>
                                <p class="font-semibold">{{ $selectedJadwal['film']['judul'] }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Tanggal & Waktu</span>
                                <p class="font-semibold">
                                    {{ \Carbon\Carbon::parse($selectedJadwal['tanggal_tayang'])->format('d M Y') }},
                                    {{ \Carbon\Carbon::parse($selectedJadwal['jam_tayang'])->format('H:i') }}
                                </p>
                            </div>
                            <div>
                                <span class="text-gray-600">Studio</span>
                                <p class="font-semibold">{{ $selectedJadwal['studio']['nama_studio'] }}</p>
                            </div>
                        </div>

                        <div class="border-t pt-4 space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Jumlah Tiket</span>
                                <span class="font-semibold">{{ count($selectedKursi) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Harga per Tiket</span>
                                <span class="font-semibold">
                                    Rp {{ number_format($hargaTiket->harga ?? 0, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="border-t pt-2 flex justify-between font-bold">
                                <span>Total</span>
                                <span class="text-teal-600">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="mt-6 space-y-2">
                            <button wire:click="proceedToConfirm"
                                    {{ count($selectedKursi) === 0 ? 'disabled' : '' }}
                                    class="w-full bg-teal-600 hover:bg-teal-700 disabled:bg-gray-400 text-white font-semibold py-2 px-4 rounded-lg transition">
                                Lanjut ke Konfirmasi
                            </button>
                            <button wire:click="switchTab('buat-pemesanan')"
                                    class="w-full bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- STEP 3: Konfirmasi -->
            @if ($formStep === 3 && $selectedJadwal)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Empty left side -->
                    <div class="lg:col-span-2"></div>

                    <!-- Summary Final -->
                    <div class="bg-white rounded-lg shadow p-6 h-fit">
                        <h3 class="font-bold text-gray-900 mb-4">Ringkasan Pemesanan</h3>

                        <div class="space-y-3 text-sm mb-4">
                            <div>
                                <span class="text-gray-600">Film</span>
                                <p class="font-semibold text-sm">{{ $selectedJadwal['film']['judul'] }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Jadwal</span>
                                <p class="font-semibold text-sm">
                                    {{ \Carbon\Carbon::parse($selectedJadwal['tanggal_tayang'])->format('d M Y H:i') }}
                                </p>
                            </div>
                            <div>
                                <span class="text-gray-600">Studio</span>
                                <p class="font-semibold text-sm">{{ $selectedJadwal['studio']['nama_studio'] }}</p>
                            </div>
                        </div>

                        <div class="border-t pt-3 space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span>Tiket</span>
                                <span class="font-semibold">{{ count($selectedKursi) }}x</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Harga/Tiket</span>
                                <span>Rp {{ number_format($hargaTiket->harga ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between font-bold border-t pt-2">
                                <span>Total</span>
                                <span class="text-teal-600">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="mt-6 space-y-2">
                            <button wire:click="simpanPemesananOffline"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                                <i class="fas fa-save mr-2"></i>Simpan Pemesanan
                            </button>
                            <button wire:click="$set('formStep', 2)"
                                    class="w-full bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition">
                                Kembali
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- TAB: Pemesanan Hari Ini -->
    @if ($tab === 'hari-ini')
        <div class="space-y-6">
            <!-- Search -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                        <input type="text"
                               wire:model.live="search"
                               placeholder="Cari kode booking atau nama"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Items Per Halaman</label>
                        <select wire:model.live="perPage"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                </div>
            </div>

<!-- Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Kode Booking</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Pelanggan</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Film</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Total Harga</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Jenis Pemesanan</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status Pembayaran</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($pemesanans as $pemesanan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $pemesanan->kode_booking }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="font-medium">
                                {{ $pemesanan->user?->name ?? $pemesanan->user_name ?? 'Offline' }}
                            </div>
                            @if($pemesanan->user?->email)
                                <div class="text-xs text-gray-500">{{ $pemesanan->user->email }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ $pemesanan->created_at->format('d M Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            @if($pemesanan->jadwalTayang?->film)
                                {{ $pemesanan->jadwalTayang->film->judul }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                {{ $pemesanan->jenis_pemesanan === 'offline' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                {{ $pemesanan->jenis_pemesanan === 'offline' ? 'Offline' : 'Online' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                {{ $pemesanan->status_pembayaran === 'lunas' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $pemesanan->status_pembayaran === 'lunas' ? 'Lunas' : 'Pending' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center text-sm">
                            <button wire:click="viewDetail({{ $pemesanan->id }})"
                                    class="text-teal-600 hover:text-teal-900 font-medium">
                                Detail
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                            Tidak ada pemesanan hari ini
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $pemesanans->links() }}
            </div>
        </div>
    @endif

    <!-- Detail Modal -->
    @if ($showDetailModal && $selectedPemesanan)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="sticky top-0 bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4 flex items-center justify-between">
                    <h2 class="text-xl font-bold text-white">Detail Pemesanan</h2>
                    <button wire:click="closeDetailModal"
                            class="text-white hover:bg-teal-800 p-1 rounded">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Booking Info -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kode Booking</label>
                            <p class="text-lg font-semibold">{{ $selectedPemesanan->kode_booking }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Pesan</label>
                            <p class="text-lg font-semibold">{{ $selectedPemesanan->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    <!-- Data Pelanggan -->
                    <div class="border-t pt-4">
                        <h3 class="text-lg font-semibold mb-4">Data Pelanggan</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Nama</span>
                                <p class="font-semibold">
                                    {{ $selectedPemesanan->user?->name ?? $selectedPemesanan->user_name ?? 'Offline' }}
                                </p>
                            </div>
                            <div>
                                <span class="text-gray-600">Email</span>
                                <p class="font-semibold">
                                    {{ $selectedPemesanan->user?->email ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <span class="text-gray-600">No. Telepon</span>
                                <p class="font-semibold">
                                    {{ $selectedPemesanan->user?->phone ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <span class="text-gray-600">Jenis Pemesanan</span>
                                <p class="font-semibold">
                                    <span
                                          class="px-3 py-1 text-xs font-semibold rounded-full
                            {{ $selectedPemesanan->jenis_pemesanan === 'offline' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $selectedPemesanan->jenis_pemesanan === 'offline' ? 'Offline (Kasir)' : 'Online' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Ticket Detail -->
                    <div class="border-t pt-4">
                        <h3 class="text-lg font-semibold mb-4">Detail Tiket</h3>
                        <div class="space-y-3">
                            @php
                                $jadwal = $selectedPemesanan->jadwalTayang;
                                $hargaPerTiket = $selectedPemesanan->jumlah_tiket > 0 
                                    ? $selectedPemesanan->total_harga / $selectedPemesanan->jumlah_tiket 
                                    : 0;
                            @endphp

                            @foreach ($selectedPemesanan->detailPemesanan as $detail)
                                @php
                                    $kursi = \App\Models\Kursi::find($detail->kursi_id);
                                @endphp
                                <div class="bg-gray-50 p-4 rounded-lg text-sm">
                                    <div class="font-bold text-teal-600">{{ $jadwal->film->judul }}</div>
                                    <div class="grid grid-cols-2 gap-4 mt-2">
                                        <div>
                                            <span class="text-gray-600">Tanggal</span><br>
                                            <span class="font-medium">{{ \Carbon\Carbon::parse($jadwal->tanggal_tayang)->format('d M Y') }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Jam</span><br>
                                            <span class="font-medium">{{ \Carbon\Carbon::parse($jadwal->jam_tayang)->format('H:i') }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Studio</span><br>
                                            <span class="font-medium">{{ $jadwal->studio->nama_studio }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Kursi</span><br>
                                            <span class="font-bold text-teal-600 text-lg">
                                                {{ $kursi?->nomor_kursi ?? 'Kursi tidak ditemukan' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-3 border-t border-gray-200 flex justify-between">
                                        <span class="text-gray-600">Harga per Tiket</span>
                                        <span class="font-bold">Rp {{ number_format($hargaPerTiket, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="border-t pt-4 bg-teal-50 p-4 rounded-lg">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold">Total</span>
                            <span class="text-2xl font-bold text-teal-600">
                                Rp {{ number_format($selectedPemesanan->total_harga, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <button wire:click="closeDetailModal"
                            class="w-full bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 rounded-lg">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>