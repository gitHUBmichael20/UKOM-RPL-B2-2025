<div>
    <div class="p-4 sm:ml-64">
        <div class="p-4 mt-14">

            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-black mb-2">Kelola Film</h1>
                <p class="text-gray-600 dark:text-gray-400">Kelola data film yang tayang di cinema</p>
            </div>

            <!-- Alert -->
            @if (session()->has('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center">
                    <i class="fa-solid fa-circle-check mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg flex items-center">
                    <i class="fa-solid fa-circle-xmark mr-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Search + Filter + Add Button -->
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.300ms="search"
                            class="w-full sm:w-96 pl-10 pr-4 py-2.5 bg-gray-100 dark:bg-gray-700 border-0 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            placeholder="Cari film atau sutradara...">
                        <i class="fa-solid fa-search absolute left-3 top-3.5 text-gray-400"></i>
                    </div>

                    <select wire:model.live="filterStatus"
                        class="px-4 py-2.5 bg-gray-100 dark:bg-gray-700 border-0 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="tayang">Tayang</option>
                        <option value="segera">Segera Tayang</option>
                       s <option value="selesai">Selesai</option>
                    </select>
                </div>

                <a href="{{ route('admin.film.create') }}"
                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg flex items-center gap-2 transition">
                    <i class="fa-solid fa-plus"></i>
                    Tambah Film
                </a>
            </div>

            <!-- Table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
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
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($films as $index => $film)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-6 py-4 text-gray-900 dark:text-white font-medium">
                                        {{ $films->firstItem() + $index }}
                                    </td>

                                    <!-- Poster -->
                                    <td class="px-6 py-4">
                                        @if ($film->poster)
                                            <img src="{{ asset('storage/' . $film->poster) }}"
                                                alt="{{ $film->judul }}"
                                                class="w-12 h-16 object-cover rounded-md shadow-sm">
                                        @else
                                            <div class="w-12 h-16 bg-gray-200 dark:bg-gray-700 rounded-md flex items-center justify-center">
                                                <i class="fa-solid fa-image text-gray-400 text-xl"></i>
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Judul + Durasi -->
                                    <td class="px-6 py-4">
                                        <div class="text-gray-900 dark:text-white font-semibold">{{ $film->judul }}</div>
                                        <div class="text-xs text-gray-500">{{ $film->durasi }}</div>
                                    </td>

                                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                        {{ $film->sutradara->nama_sutradara ?? '-' }}
                                    </td>

                                    <!-- Genre -->
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
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-200">
                                            {{ $film->rating }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $film->tahun_rilis }}</td>

                                    <!-- Status -->
                                    <td class="px-6 py-4">
                                        @if ($film->status == 'tayang')
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                                Tayang
                                            </span>
                                        @elseif($film->status == 'segera')
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">
                                                Segera
                                            </span>
                                        @else
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                                Selesai
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Aksi -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.film.edit', $film->id) }}"
                                                class="p-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <button wire:click="openDeleteModal({{ $film->id }})"
                                                class="p-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-12 text-gray-500">
                                        <i class="fa-solid fa-inbox text-4xl mb-3 block"></i>
                                        <p>Tidak ada data film</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-3 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                    {{ $films->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Delete tetap sama, atau bisa dipercantik kalau mau -->
    @if ($showDeleteModal)
        <!-- modal code kamu yang lama sudah oke, tinggal copy paste -->
    @endif
</div>