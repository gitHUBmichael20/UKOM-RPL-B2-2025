<div>
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Kelola Pemesanan</h2>
        <p class="text-gray-600 mt-1">Kelola data pemesanan tiket film</p>
    </div>

    <!-- Search + Filter + Stats -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
        <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
            <!-- Search -->
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search"
                    class="w-full sm:w-96 pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Cari kode booking, film, atau customer...">
                <i class="fa-solid fa-search absolute left-3 top-3.5 text-gray-400"></i>
            </div>

            <!-- Filter Status -->
            <select wire:model.live="filterStatus"
                class="px-4 py-2.5 pe-8 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="paid">Lunas</option>
                <option value="failed">Gagal</option>
                <option value="cancelled">Dibatalkan</option>
            </select>
        </div>

        <!-- Stats -->
        <div class="flex items-center space-x-4 text-sm text-gray-600">
            <span class="flex items-center gap-1">
                <i class="fa-solid fa-receipt"></i>
                Total: {{ $pemesanan->total() }} pemesanan
            </span>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase bg-gray-50 text-gray-700 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Kode Booking</th>
                        <th class="px-6 py-4">Film & Jadwal</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Studio</th>
                        <th class="px-6 py-4">Tiket</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($pemesanan as $index => $booking)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-900 font-medium">
                                {{ $pemesanan->firstItem() + $index }}
                            </td>

                            <!-- Kode Booking -->
                            <td class="px-6 py-4">
                                <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded border">
                                    {{ $booking->kode_booking }}
                                </span>
                            </td>

                            <!-- Film & Jadwal -->
                            <td class="px-6 py-4">
                                <div class="text-gray-900 font-semibold">
                                    {{ $booking->jadwalTayang->film->judul ?? 'N/A' }}
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <i class="fa-solid fa-calendar mr-1"></i>
                                    {{ $booking->jadwalTayang ? \Carbon\Carbon::parse($booking->jadwalTayang->tanggal_tayang)->format('d M Y') : 'N/A' }}
                                    •
                                    <i class="fa-solid fa-clock mr-1"></i>
                                    {{ $booking->jadwalTayang ? \Carbon\Carbon::parse($booking->jadwalTayang->jam_tayang)->format('H:i') : 'N/A' }}
                                </div>
                            </td>

                            <!-- Customer -->
                            <td class="px-6 py-4 text-gray-700">
                                {{ $booking->user->name ?? 'N/A' }}
                            </td>

                            <!-- Studio -->
                            <td class="px-6 py-4 text-gray-700">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">
                                    {{ $booking->jadwalTayang->studio->nama_studio ?? 'N/A' }}
                                </span>
                            </td>

                            <!-- Tiket -->
                            <td class="px-6 py-4">
                                <div class="text-gray-900 font-semibold">{{ $booking->jumlah_tiket }} tiket</div>
                                <div class="text-xs text-gray-500 mt-1 font-mono">
                                    @if ($booking->detailPemesanan && $booking->detailPemesanan->count() > 0)
                                        {{ $booking->detailPemesanan->pluck('kursi.nomor_kursi')->join(', ') }}
                                    @else
                                        -
                                    @endif
                                </div>
                            </td>

                            <!-- Total -->
                            <td class="px-6 py-4 text-gray-700 font-semibold">
                                Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($booking->status_pembayaran == 'paid')
                                    <span
                                        class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                        <i class="fa-solid fa-check-circle mr-1"></i>Lunas
                                    </span>
                                @elseif($booking->status_pembayaran == 'pending')
                                    <span
                                        class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">
                                        <i class="fa-solid fa-clock mr-1"></i>Pending
                                    </span>
                                @elseif($booking->status_pembayaran == 'failed')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                        <i class="fa-solid fa-times-circle mr-1"></i>Gagal
                                    </span>
                                @else
                                    <span
                                        class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">
                                        <i class="fa-solid fa-ban mr-1"></i>Dibatalkan
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.pemesanan.edit', $booking->id) }}"
                                        class="p-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition-colors"
                                        title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <a href="{{ url('/pemesanan/ticket/' . $booking->id) }}" target="_blank"
                                        class="p-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors"
                                        title="Lihat Tiket">
                                        <i class="fa-solid fa-ticket"></i>
                                    </a>
                                    <button onclick="confirmDelete({{ $booking->id }})"
                                        class="p-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors"
                                        title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-12 text-gray-500">
                                <i class="fa-solid fa-receipt text-4xl mb-3 block text-gray-300"></i>
                                <p class="font-medium">Tidak ada data pemesanan</p>
                                <p class="text-sm mt-1">Belum ada pemesanan yang dibuat</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($pemesanan->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $pemesanan->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Pemesanan?',
                text: "Data yang sudah dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fa-solid fa-trash mr-2"></i>Hapus',
                cancelButtonText: '<i class="fa-solid fa-times mr-2"></i>Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('delete', id);
                }
            });
        }

        // Listen untuk flash messages dari Livewire
        window.addEventListener('success', event => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: event.detail[0],
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        });

        window.addEventListener('error', event => {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: event.detail[0],
                confirmButtonColor: '#dc2626'
            });
        });
    </script>
@endpush
