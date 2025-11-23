<div>
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Kelola Film</h2>
        <p class="text-gray-600 mt-1">Kelola data film yang tayang di cinema</p>
    </div>

    <!-- Search + Filter + Add Button -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
        <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
            <!-- Search -->
            <div class="relative">
                <input type="text"
                       wire:model.live.debounce.300ms="search"
                       class="w-full sm:w-96 pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Cari film atau sutradara...">
                <i class="fa-solid fa-search absolute left-3 top-3.5 text-gray-400"></i>
            </div>

            <!-- Filter Status -->
            <select wire:model.live="filterStatus"
                    class="px-4 py-2.5 pe-8 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Semua Status</option>
                <option value="tayang">Tayang</option>
                <option value="segera">Segera Tayang</option>
                <option value="selesai">Selesai</option>
            </select>
        </div>

        <!-- Add Button -->
        @if(auth()->user()->role === 'admin')
        <a href="{{ route('admin.film.create') }}"
           class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg flex items-center gap-2 transition-colors">
            <i class="fa-solid fa-plus"></i>
            Tambah Film
        </a>
        @endif
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase bg-gray-50 text-gray-700 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Poster</th>
                        <th class="px-6 py-4">Judul</th>
                        <th class="px-6 py-4">Sutradara</th>
                        <th class="px-6 py-4">Genre</th>
                        <th class="px-6 py-4">Rating</th>
                        <th class="px-6 py-4">Tahun</th>
                        <th class="px-6 py-4">Status</th>
                        @if(auth()->user()->role === 'admin')
    <th class="px-6 py-4 text-center">Aksi</th>
@endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($films as $index => $film)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-900 font-medium">
                                {{ $films->firstItem() + $index }}
                            </td>

                            <!-- Poster -->
                            <td class="px-6 py-4">
                                <img src="{{ $film->poster ? asset('storage/' . $film->poster) : asset('storage/default_poster.png') }}"
                                     alt="{{ $film->judul }}"
                                     class="w-12 h-16 object-cover rounded-md shadow-sm">
                            </td>

                            <!-- Judul + Durasi -->
                            <td class="px-6 py-4">
                                <div class="text-gray-900 font-semibold">{{ $film->judul }}</div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <i class="fa-solid fa-clock mr-1"></i>{{ $film->durasi }} menit
                                </div>
                            </td>

                            <!-- Sutradara -->
                            <td class="px-6 py-4 text-gray-700">
                                {{ $film->sutradara->nama_sutradara ?? '-' }}
                            </td>

                            <!-- Genre -->
                            <td class="px-6 py-4 max-w-[300px]">
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
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ $film->rating }}
                                </span>
                            </td>

                            <!-- Tahun -->
                            <td class="px-6 py-4 text-gray-700">{{ $film->tahun_rilis }}</td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($film->status == 'tayang')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                        <i class="fa-solid fa-circle-dot mr-1"></i>Tayang
                                    </span>
                                @elseif($film->status == 'segera')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">
                                        <i class="fa-solid fa-clock mr-1"></i>Segera
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                        <i class="fa-solid fa-check-circle mr-1"></i>Selesai
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            @if(auth()->user()->role === 'admin')
<td class="px-6 py-4">
    <div class="flex items-center justify-center gap-2">
        <a href="{{ route('admin.film.edit', $film->id) }}"
           class="p-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition-colors"
           title="Edit">
            <i class="fa-solid fa-pen-to-square"></i>
        </a>

        <button onclick="confirmDelete({{ $film->id }})"
                class="p-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors"
                title="Hapus">
            <i class="fa-solid fa-trash"></i>
        </button>
    </div>
</td>
@endif

                        </tr>
                    @empty
                        <tr>
                            <td colspan="9"
                                class="text-center py-12 text-gray-500">
                                <i class="fa-solid fa-film text-4xl mb-3 block text-gray-300"></i>
                                <p class="font-medium">Tidak ada data film</p>
                                <p class="text-sm mt-1">Silakan tambahkan film baru</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($films->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $films->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Film?',
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