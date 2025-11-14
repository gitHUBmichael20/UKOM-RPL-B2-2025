<div class="p-6">
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

    <!-- Alert Messages -->
    @if (session()->has('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg"
             role="alert">
            <div class="flex">
                <i class="fa-solid fa-check-circle mr-3 mt-0.5"></i>
                <div>
                    <p class="font-bold">Berhasil!</p>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg"
             role="alert">
            <div class="flex">
                <i class="fa-solid fa-exclamation-circle mr-3 mt-0.5"></i>
                <div>
                    <p class="font-bold">Error!</p>
                    <p>{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

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

    <!-- studio Table -->
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
                                    <button wire:click="confirmDelete({{ $s->id }})"
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

        <!-- Pagination -->
        @if($studio->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $studio->links() }}
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-data="{ show: false }"
         x-show="show"
         @show-delete-modal.window="show = true"
         @hide-delete-modal.window="show = false"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                 @click="show = false"></div>

            <div x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                <div class="bg-white px-6 pt-6 pb-4">
                    <div class="flex items-center mb-4">
                        <div
                             class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                            <i class="fa-solid fa-exclamation-triangle text-red-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="text-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-2">
                            Hapus Studio
                        </h3>
                        <p class="text-sm text-gray-500">
                            Apakah Anda yakin ingin menghapus studio ini? Semua kursi yang terkait juga akan dihapus.
                            Aksi ini tidak dapat dibatalkan.
                        </p>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3">
                    <button wire:click="delete"
                            @click="show = false"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                        Ya, Hapus
                    </button>
                    <button @click="show = false"
                            class="bg-white hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg border border-gray-300 transition-colors">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Layout View Modal -->
    <div x-data="{ show: false }"
         x-show="show"
         @show-layout-modal.window="show = true"
         @hide-layout-modal.window="show = false"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                 @click="show = false"></div>

            <div x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-4xl sm:w-full max-h-[90vh] overflow-y-auto">

                @if($layoutStudio)
                    <div class="bg-white px-6 pt-6 pb-4">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-6 pb-4 border-b">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">
                                    Layout {{ $layoutStudio->nama_studio }}
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    <span class="font-medium">{{ $layoutStudio->kapasitas_kursi }} Kursi</span> •
                                    <span class="uppercase">{{ $layoutStudio->tipe_studio }}</span>
                                </p>
                            </div>
                            <button @click="show = false"
                                    class="text-gray-400 hover:text-gray-600">
                                <i class="fa-solid fa-times text-2xl"></i>
                            </button>
                        </div>

                        <!-- Screen -->
                        <div class="mb-8">
                            <div
                                 class="bg-gradient-to-b from-gray-800 to-gray-700 text-white text-center py-3 rounded-t-3xl shadow-lg">
                                <i class="fa-solid fa-film mr-2"></i>
                                <span class="font-medium">LAYAR</span>
                            </div>
                        </div>

                        <!-- Seats Layout -->
                        <div class="space-y-3">
                            @php
                                $kursiByBaris = $layoutStudio->kursi->groupBy(function ($kursi) {
                                    return substr($kursi->nomor_kursi, 0, 1);
                                })->sortKeys();
                            @endphp

                            @foreach($kursiByBaris as $baris => $kursiList)
                                <div class="flex items-center gap-3">
                                    <!-- Row Label -->
                                    <div class="w-8 text-center font-bold text-gray-700 text-lg">
                                        {{ $baris }}
                                    </div>

                                    <!-- Seats -->
                                    <div class="flex-1 flex justify-center items-center gap-2">
                                        @php
                                            $sortedKursi = $kursiList->sortBy(function ($kursi) {
                                                return (int) substr($kursi->nomor_kursi, 1);
                                            });
                                        @endphp

                                        @foreach($sortedKursi as $index => $kursi)
                                            @php
                                                $nomorKolom = (int) substr($kursi->nomor_kursi, 1);
                                            @endphp

                                            <!-- Seat -->
                                            <div class="group relative">
                                                <div
                                                     class="w-10 h-10 bg-green-500 hover:bg-green-600 rounded-t-lg flex items-center justify-center text-white text-xs font-medium cursor-pointer transition-all shadow-sm">
                                                    {{ $nomorKolom }}
                                                </div>
                                                <!-- Tooltip -->
                                                <div
                                                     class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10">
                                                    {{ $kursi->nomor_kursi }}
                                                </div>
                                            </div>

                                            <!-- Aisle/Gang after seat 5 -->
                                            @if($nomorKolom == 5)
                                                <div class="w-8 flex items-center justify-center text-gray-400">
                                                    <i class="fa-solid fa-grip-lines-vertical"></i>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>

                                    <!-- Row Label (Right) -->
                                    <div class="w-8 text-center font-bold text-gray-700 text-lg">
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

                    <div class="bg-gray-50 px-6 py-4 flex justify-end">
                        <button @click="show = false"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                            Tutup
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
