<div class="p-3">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Jadwal Tayang</h1>
        <p class="text-gray-600 mt-1">Kelola jadwal tayang film di setiap studio</p>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Film</label>
                <input type="text"
                       wire:model.live="search"
                       placeholder="Cari judul film..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Studio</label>
                <select wire:model.live="filterStudio"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Studio</option>
                    @foreach($studios as $studio)
                        <option value="{{ $studio->id }}">{{ $studio->nama_studio }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                <input type="date"
                       wire:model.live="filterTanggal"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="flex items-end">
                <a href="{{ route('admin.jadwal-tayang.create') }}"
                   class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-center">
                    <i class="fas fa-plus mr-2"></i>Tambah Jadwal
                </a>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Film
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Studio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Durasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($jadwalTayang as $jadwal)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($jadwal->film->poster)
                                        <img src="{{ Storage::url($jadwal->film->poster) }}"
                                             alt="{{ $jadwal->film->judul }}"
                                             class="w-12 h-16 object-cover rounded mr-3">
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $jadwal->film->judul }}</div>
                                        <div class="text-sm text-gray-500">{{ $jadwal->film->genre->nama_genre ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                    {{ $jadwal->studio->nama_studio }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($jadwal->tanggal_tayang)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $jadwal->jam_tayang->format('H:i') }} WIB
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $jadwal->film->durasi }} menit
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.jadwal-tayang.edit', $jadwal->id) }}"
                                       class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                                        <i class="fa-solid fa-edit mr-1"></i>
                                        Edit
                                    </a>
                                    <button onclick="confirmDeleteJadwal({{ $jadwal->id }})"
                                            wire:confirm="Apakah Anda yakin ingin menghapus jadwal ini?"
                                            class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors">
                                        <i class="fa-solid fa-trash mr-1"></i>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6"
                                class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-calendar-times text-4xl mb-3"></i>
                                <p>Tidak ada jadwal tayang</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $jadwalTayang->links() }}
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function confirmDeleteJadwal(id) {
            Swal.fire({
                title: "Yakin ingin menghapus?",
                text: "Jadwal tidak bisa dikembalikan setelah dihapus!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6b7280",
                confirmButtonText: "Ya, hapus!"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('delete', { id: id });
                }
            });
        }

        // Listener berhasil
        window.addEventListener('success', event => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: event.detail[0],
                timer: 2500,
                toast: true,
                position: 'top-end',
                showConfirmButton: false
            });
        });

        // Listener error
        window.addEventListener('error', event => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: event.detail[0],
            });
        });
    </script>

@endpush