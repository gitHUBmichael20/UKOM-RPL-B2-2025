<!-- resources/views/livewire/admin/pemesanan-admin.blade.php -->

<div class="py-8 px-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Kelola Pemesanan</h1>
        <p class="text-gray-600 mt-2">Lihat dan kelola semua pemesanan pelanggan</p>
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

    <!-- Search & Filter -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                <input type="text"
                       wire:model.live="search"
                       placeholder="Cari kode booking atau nama"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
            </div>

            <!-- Filter Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter Status</label>
                <select wire:model.live="filter"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <option value="semua">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="dikonfirmasi">Dikonfirmasi</option>
                    <option value="dibatalkan">Dibatalkan</option>
                </select>
            </div>

            <!-- Items Per Page -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Per Halaman</label>
                <select wire:model.live="perPage"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
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
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                            {{ $pemesanan->kode_booking }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <div class="font-medium text-gray-900">
                                                {{ $pemesanan->user?->name ?? $pemesanan->user_name ?? 'Offline' }}
                                            </div>
                                            @if($pemesanan->user?->email)
                                                <div class="text-xs text-gray-500">{{ $pemesanan->user->email }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $pemesanan->created_at->format('d M Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 font-medium">
                                            @if($pemesanan->jadwalTayang?->film)
                                                {{ $pemesanan->jadwalTayang->film->judul }}
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                            Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full
                                                {{ $pemesanan->jenis_pemesanan === 'offline'
                        ? 'bg-yellow-100 text-yellow-800'
                        : 'bg-green-100 text-green-800' }}">
                                                {{ $pemesanan->jenis_pemesanan === 'offline' ? 'Offline' : 'Online' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full
                                                {{ $pemesanan->status_pembayaran === 'lunas'
                        ? 'bg-green-100 text-green-800'
                        : ($pemesanan->status_pembayaran === 'pending'
                            ? 'bg-yellow-100 text-yellow-800'
                            : 'bg-red-100 text-red-800') }}">
                                                {{ $pemesanan->status_pembayaran === 'lunas' ? 'Lunas' : ucfirst($pemesanan->status_pembayaran) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center text-sm">
                                            <button wire:click="viewDetail({{ $pemesanan->id }})"
                                                    class="text-teal-600 hover:text-teal-800 font-medium transition">
                                                Detail
                                            </button>
                                        </td>
                                    </tr>
                    @empty
                        <tr>
                            <td colspan="8"
                                class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-3 block"></i>
                                Tidak ada data pemesanan
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

    <!-- Detail Modal -->
    @if ($showDetailModal && $selectedPemesanan)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div
                     class="sticky top-0 bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4 flex items-center justify-between border-b">
                    <h2 class="text-xl font-bold text-white">Detail Pemesanan</h2>
                    <button wire:click="closeDetailModal"
                            class="text-white hover:bg-teal-800 p-1 rounded">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Booking Info -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kode Booking</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $selectedPemesanan->kode_booking }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Pesan</label>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $selectedPemesanan->created_at->format('d M Y H:i') }}
                            </p>
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

                    <!-- Ticket Details -->
                    <div class="border-t pt-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Tiket</h3>
                        <div class="space-y-3">
                            @php
                                $jadwal = $selectedPemesanan->jadwalTayang;
                                $hargaPerTiket = $selectedPemesanan->jumlah_tiket > 0
                                    ? $selectedPemesanan->total_harga / $selectedPemesanan->jumlah_tiket
                                    : 0;
                            @endphp

                            @foreach ($selectedPemesanan->detailPemesanan as $detail)
                                @php $kursi = \App\Models\Kursi::find($detail->kursi_id); @endphp
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <span class="text-gray-600">Film</span>
                                            <p class="font-semibold">{{ $jadwal->film->judul }}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Jadwal</span>
                                            <p class="font-semibold">
                                                {{ \Carbon\Carbon::parse($jadwal->tanggal_tayang)->format('d M Y') }}
                                                {{ \Carbon\Carbon::parse($jadwal->jam_tayang)->format('H:i') }}
                                            </p>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Studio</span>
                                            <p class="font-semibold">{{ $jadwal->studio->nama_studio }}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Kursi</span>
                                            <p class="font-bold text-teal-600 text-lg">
                                                {{ $kursi?->nomor_kursi ?? 'Kursi tidak ditemukan' }}
                                            </p>
                                        </div>
                                        <div class="col-span-2">
                                            <span class="text-gray-600">Harga per Tiket</span>
                                            <p class="font-semibold">Rp {{ number_format($hargaPerTiket, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="border-t pt-4 grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status Pemesanan</label>
                            <span class="inline-block mt-1 px-3 py-1 text-xs font-semibold rounded-full
                                                    @if ($selectedPemesanan->status_pemesanan === 'pending')
                                                        bg-yellow-100 text-yellow-800
                                                    @elseif ($selectedPemesanan->status_pemesanan === 'dikonfirmasi')
                                                        bg-green-100 text-green-800
                                                    @else
                                                        bg-red-100 text-red-800
                                                    @endif
                                                ">
                                {{ ucfirst($selectedPemesanan->status_pemesanan) }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status Pembayaran</label>
                            <span class="inline-block mt-1 px-3 py-1 text-xs font-semibold rounded-full
                                                    @if ($selectedPemesanan->status_pembayaran === 'lunas')
                                                        bg-green-100 text-green-800
                                                    @elseif ($selectedPemesanan->status_pembayaran === 'pending')
                                                        bg-yellow-100 text-yellow-800
                                                    @else
                                                        bg-red-100 text-red-800
                                                    @endif
                                                ">
                                {{ ucfirst($selectedPemesanan->status_pembayaran) }}
                            </span>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="border-t pt-4 bg-teal-50 p-4 rounded-lg">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-900">Total Harga</span>
                            <span class="text-2xl font-bold text-teal-600">
                                Rp {{ number_format($selectedPemesanan->total_harga, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <!-- Action Button -->
                    @if ($selectedPemesanan->status_pembayaran !== 'lunas' && $selectedPemesanan->status_pemesanan !== 'dibatalkan')
                        <div class="border-t pt-4 flex gap-3">
                            <button wire:click="closeDetailModal"
                                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition">
                                Tutup
                            </button>
                            <button wire:click="batalkanPemesanan({{ $selectedPemesanan->id }})"
                                    wire:confirm="Yakin ingin membatalkan pemesanan ini?"
                                    class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                                Batalkan Pemesanan
                            </button>
                        </div>
                    @else
                        <div class="border-t pt-4">
                            <button wire:click="closeDetailModal"
                                    class="w-full bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition">
                                Tutup
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>