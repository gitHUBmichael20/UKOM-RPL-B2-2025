<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Harga Tiket</h2>
            <p class="text-gray-600 mt-1">Kelola harga tiket berdasarkan tipe studio dan hari</p>
        </div>
        @if(isRole('admin'))
            <a href="{{ route('admin.harga-tiket.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                <i class="fa-solid fa-plus"></i>
                Tambah Harga Tiket
            </a>
        @endif
    </div>

    <!-- Alert Messages -->
    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg">
            <div class="flex items-center">
                <i class="fa-solid fa-circle-check mr-2"></i>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg">
            <div class="flex items-center">
                <i class="fa-solid fa-circle-exclamation mr-2"></i>
                <p>{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <!-- Search -->
            <div class="md:col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                <div class="relative">
                    <i class="fa-solid fa-search absolute left-3 top-3.5 text-gray-400"></i>
                    <input type="text" 
                           wire:model.live="search" 
                           placeholder="Cari harga tiket..."
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <!-- Filter Tipe Studio -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Studio</label>
                <select wire:model.live="filterTipeStudio"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Tipe Studio</option>
                    @foreach($tipeStudioOptions as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Tipe Hari -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Hari</label>
                <select wire:model.live="filterTipeHari"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Hari</option>
                    @foreach($tipeHariOptions as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Reset Filter -->
        <div class="flex justify-end">
            <button wire:click="resetFilters" 
                    class="text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1 transition-colors">
                <i class="fa-solid fa-rotate-right"></i>
                Reset Filter
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            No
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tipe Studio
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tipe Hari
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Harga
                        </th>
                        @if(isRole('admin'))
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($hargaTikets as $index => $hargaTiket)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $hargaTikets->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $badgeColor = [
                                        'regular' => 'bg-gray-100 text-gray-800',
                                        'deluxe' => 'bg-purple-100 text-purple-800',
                                        'imax' => 'bg-yellow-100 text-yellow-800'
                                    ][$hargaTiket->tipe_studio] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeColor }}">
                                    {{ strtoupper($hargaTiket->tipe_studio) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $dayBadgeColor = $hargaTiket->tipe_hari == 'weekday' 
                                        ? 'bg-green-100 text-green-800' 
                                        : 'bg-orange-100 text-orange-800';
                                @endphp
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $dayBadgeColor }}">
                                    {{ ucfirst($hargaTiket->tipe_hari) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $hargaTiket->formatted_harga }}
                            </td>
                            @if(isRole('admin'))
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.harga-tiket.edit', $hargaTiket->id) }}"
                                           class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                                            <i class="fa-solid fa-edit mr-1"></i>
                                            Edit
                                        </a>
                                        <button wire:click="delete({{ $hargaTiket->id }})"
                                                wire:confirm="Apakah Anda yakin ingin menghapus harga tiket ini?"
                                                class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors">
                                            <i class="fa-solid fa-trash mr-1"></i>
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ isRole('admin') ? '5' : '4' }}" 
                                class="px-6 py-12 text-center text-gray-500">
                                <i class="fa-solid fa-ticket text-6xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-medium">Belum ada data harga tiket</p>
                                <p class="text-sm mt-1">Klik tombol "Tambah Harga Tiket" untuk menambahkan harga tiket baru</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($hargaTikets->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $hargaTikets->links() }}
            </div>
        @endif
    </div>
</div>