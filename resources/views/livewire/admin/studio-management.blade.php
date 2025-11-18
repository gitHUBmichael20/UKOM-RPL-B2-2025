<div class="p-3">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Studio</h2>
            <p class="text-gray-600 mt-1">Kelola studio dan kursi bioskop</p>
        </div>
        <a href="{{ route('admin.studio.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
            <i class="fa-solid fa-plus"></i>
            Tambah Studio
        </a>
    </div>

    <!-- Search Bar -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="relative">
            <i class="fa-solid fa-search absolute left-3 top-3.5 text-gray-400"></i>
            <input type="text"
                   wire:model.live.debounce.300ms="search"
                   placeholder="Cari studio..."
                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
    </div>

    <!-- Studio Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nama Studio
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tipe Studio
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kapasitas
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jumlah Kursi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($studio as $s)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <i class="fa-solid fa-video text-blue-600 mr-3"></i>
                                    <div class="text-sm font-medium text-gray-900">{{ $s->nama_studio }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $badgeColor = [
                                        'regular' => 'bg-gray-100 text-gray-800',
                                        'deluxe' => 'bg-purple-100 text-purple-800',
                                        'imax' => 'bg-red-100 text-red-800'
                                    ][$s->tipe_studio] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span
                                      class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeColor }}">
                                    {{ strtoupper($s->tipe_studio) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $s->kapasitas_kursi }} kursi
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    <i class="fa-solid fa-chair text-gray-400 mr-2"></i>
                                    {{ $s->kursi()->count() }} kursi
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2">
                                    <button wire:click="viewLayout({{ $s->id }})"
                                            class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-3 py-1.5 rounded-lg transition-colors">
                                        <i class="fa-solid fa-eye mr-1"></i>
                                        Layout
                                    </button>
                                    <a href="{{ route('admin.studio.edit', $s->id) }}"
                                       class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                                        <i class="fa-solid fa-edit mr-1"></i>
                                        Edit
                                    </a>
                                    <button onclick="confirmDelete({{ $s->id }}, '{{ $s->nama_studio }}')"
                                            class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors">
                                        <i class="fa-solid fa-trash mr-1"></i>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5"
                                class="px-6 py-12 text-center text-gray-500">
                                <i class="fa-solid fa-video text-6xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-medium">Belum ada studio</p>
                                <p class="text-sm mt-1">Klik tombol "Tambah Studio" untuk menambahkan studio baru</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($studio->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $studio->links() }}
            </div>
        @endif
    </div>

    <!-- Layout View Modal -->
    @if($showLayoutModal && $layoutStudio)
        <div class="fixed inset-0 z-50 overflow-y-auto"
             wire:key="layout-modal">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                     wire:click="closeLayoutModal"></div>

                <div
                     class="relative bg-white rounded-lg overflow-hidden shadow-xl transform transition-all w-full max-w-6xl max-h-[90vh] flex flex-col">
                    <div class="bg-white px-6 py-4 border-b sticky top-0 z-10">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">
                                    Layout {{ $layoutStudio->nama_studio }}
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    <span class="font-medium">{{ $layoutStudio->kapasitas_kursi }} Kursi</span> •
                                    <span class="uppercase">{{ $layoutStudio->tipe_studio }}</span>
                                </p>
                            </div>
                            <button wire:click="closeLayoutModal"
                                    class="text-gray-400 hover:text-gray-600 transition-colors">
                                <i class="fa-solid fa-times text-2xl"></i>
                            </button>
                        </div>
                    </div>

                    <div class="overflow-y-auto flex-1 px-6 py-4">
                        <!-- Screen -->
                        <div class="mb-8">
                            <div
                                 class="bg-gradient-to-b from-gray-800 to-gray-700 text-white text-center py-3 rounded-t-3xl shadow-lg">
                                <i class="fa-solid fa-film mr-2"></i>
                                <span class="font-medium">LAYAR</span>
                            </div>
                        </div>

                        <!-- Seats Layout -->
                        <div class="space-y-2">
                            @php
                                $kursiByBaris = $layoutStudio->kursi->groupBy(function ($kursi) {
                                    return substr($kursi->nomor_kursi, 0, 1);
                                })->sortKeys();

                                $totalKolom = $layoutStudio->kursi->max(function ($kursi) {
                                    return (int) substr($kursi->nomor_kursi, 1);
                                });

                                // Hitung posisi gang (setelah kursi ke berapa)
                                $gangPosition = ceil($totalKolom / 2);
                            @endphp

                            @foreach($kursiByBaris as $baris => $kursiList)
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Row Label Left -->
                                    <div class="w-6 text-center font-bold text-gray-700 text-sm flex-shrink-0">
                                        {{ $baris }}
                                    </div>

                                    <!-- Seats Container -->
                                    <div class="flex items-center gap-1.5">
                                        @php
                                            $sortedKursi = $kursiList->sortBy(function ($kursi) {
                                                return (int) substr($kursi->nomor_kursi, 1);
                                            });
                                        @endphp

                                        @foreach($sortedKursi as $kursi)
                                            @php
                                                $nomorKolom = (int) substr($kursi->nomor_kursi, 1);
                                            @endphp

                                            <!-- Seat -->
                                            <div class="group relative">
                                                <div
                                                     class="w-8 h-8 bg-green-500 hover:bg-green-600 rounded-t-lg flex items-center justify-center text-white text-xs font-medium cursor-pointer transition-all shadow-sm">
                                                    {{ $nomorKolom }}
                                                </div>
                                                <div
                                                     class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-20 pointer-events-none">
                                                    {{ $kursi->nomor_kursi }}
                                                </div>
                                            </div>

                                            <!-- Gang setelah kursi ke-gangPosition -->
                                            @if($nomorKolom == $gangPosition)
                                                <div class="w-6 flex items-center justify-center text-gray-400 flex-shrink-0">
                                                    <i class="fa-solid fa-grip-lines-vertical text-sm"></i>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>

                                    <!-- Row Label Right -->
                                    <div class="w-6 text-center font-bold text-gray-700 text-sm flex-shrink-0">
                                        {{ $baris }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Legend -->
                        <div class="mt-8 pt-6 border-t flex justify-center gap-8">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-green-500 rounded-t-lg"></div>
                                <span class="text-sm text-gray-700">Tersedia</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-grip-lines-vertical text-gray-400 text-xl"></i>
                                <span class="text-sm text-gray-700">Gang</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex justify-end border-t sticky bottom-0">
                        <button wire:click="closeLayoutModal"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id, namaStudio) {
            Swal.fire({
                title: 'Hapus Studio?',
                html: `Apakah Anda yakin ingin menghapus <strong>${namaStudio}</strong>?<br><small class="text-gray-600">Semua kursi terkait juga akan dihapus.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('delete', id);
                }
            });
        }
        document.addEventListener('livewire:init', () => {
            Livewire.on('studio-deleted', () => {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Studio berhasil dihapus',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            });
        });
    </script>
@endpush