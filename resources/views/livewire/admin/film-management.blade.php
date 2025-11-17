<div>
    <!-- Wrapper sederhana & konsisten -->
    <div class="p-6">
        <div class="mt-14">

            <!-- Header + Deskripsi -->
            <div class="mb-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Kelola Film</h1>
                        <p class="mt-1 text-gray-600">Kelola data film yang tayang di cinema</p>
                    </div>

                    <!-- Button Tambah di kanan header (seperti Studio) -->
                    <a href="{{ route('admin.film.create') }}"
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg flex items-center gap-2 transition duration-200">
                        <i class="fa-solid fa-plus"></i>
                        Tambah Film
                    </a>
                </div>
            </div>

            <!-- Success Alert -->
            @if (session()->has('success'))
                <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg flex items-center">
                    <i class="fa-solid fa-circle-check mr-3"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Error Alert -->
            @if (session()->has('error'))
                <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg flex items-center">
                    <i class="fa-solid fa-circle-xmark mr-3"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Search + Filter (grid seimbang) -->
            <div class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search dengan icon -->
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.300ms="search"
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Cari film atau sutradara...">
                        <i class="fa-solid fa-search absolute left-3 top-3.5 text-gray-400"></i>
                    </div>

                    <!-- Filter Status -->
                    <select wire:model.live="filterStatus"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Status</option>
                        <option value="tayang">Tayang</option>
                        <option value="segera">Segera Tayang</option>
                        <option value="selesai">Selesai</option>
                    </select>

                    <!-- Reset Filter (dipisah ke kanan) -->
                    <div class="flex justify-end">
                        <button wire:click="$set('search', '') ; $set('filterStatus', '')"
                            class="px-4 py-2.5 text-gray-600 hover:text-gray-900 flex items-center gap-2 transition">
                            <i class="fa-solid fa-arrow-rotate-left"></i>
                            Reset Filter
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs uppercase bg-gray-50 text-gray-600">
                            <tr>
                                <th class="px-6 py-4">NO</th>
                                <th class="px-6 py-4">POSTER</th>
                                <th class="px-6 py-4">JUDUL</th>
                                <th class="px-6 py-4">SUTRADARA</th>
                                <th class="px-6 py-4">GENRE</th>
                                <th class="px-6 py-4">RATING</th>
                                <th class="px-6 py-4">TAHUN</th>
                                <th class="px-6 py-4">STATUS</th>
                                <th class="px-6 py-4 text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($films as $index => $film)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-medium">
                                        {{ $films->firstItem() + $index }}
                                    </td>

                                    <!-- Poster -->
                                    <td class="px-6 py-4">
                                        @if ($film->poster)
                                            <img src="{{ asset('storage/' . $film->poster) }}"
                                                alt="{{ $film->judul }}"
                                                class="w-12 h-16 object-cover rounded-md shadow-sm">
                                        @else
                                            <div class="w-12 h-16 bg-gray-200 rounded-md flex items-center justify-center">
                                                <i class="fa-solid fa-image text-gray-400 text-xl"></i>
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Judul + Durasi -->
                                    <td class="px-6 py-4">
                                        <div class="font-semibold">{{ $film->judul }}</div>
                                        <div class="text-xs text-gray-500">{{ $film->durasi }}</div>
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ $film->sutradara->nama_sutradara ?? '-' }}
                                    </td>

                                    <!-- Genre Badges -->
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach ($film->genres as $genre)
                                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-700">
                                                    {{ $genre->nama_genre }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>

                                    <!-- Rating -->
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-800">
                                            {{ $film->rating }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4">{{ $film->tahun_rilis }}</td>

                                    <!-- Status Badge (warna tetap sama) -->
                                    <td class="px-6 py-4">
                                        @if ($film->status == 'tayang')
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Tayang</span>
                                        @elseif($film->status == 'segera')
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">Segera</span>
                                        @else
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Selesai</span>
                                        @endif
                                    </td>

                                    <!-- Action Buttons -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.film.edit', $film->id) }}"
                                                class="p-2.5 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition duration-200">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <button wire:click="openDeleteModal({{ $film->id }})"
                                                class="p-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg transition duration-200">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-16 text-gray-500">
                                        <i class="fa-solid fa-ticket text-5xl mb-4 block text-gray-300"></i>
                                        <p class="text-lg">Tidak ada data film</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $films->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Delete (kamu tinggal paste yang lama di sini) -->
    @if ($showDeleteModal)
        <!-- modal code kamu -->
    @endif
</div>