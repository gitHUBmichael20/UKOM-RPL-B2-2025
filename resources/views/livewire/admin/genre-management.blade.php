<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Kelola Genre</h2>
        <p class="text-gray-600 mt-1">Kelola data genre film</p>
    </div>

    <!-- Search & Add Button -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div class="relative w-full md:w-96">
            <input type="text"
                   wire:model.live.debounce.300ms="search"
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                   placeholder="Cari genre...">
            <i class="fa-solid fa-search absolute left-3 top-3.5 text-gray-400"></i>
        </div>

        <button wire:click="openCreateModal"
                class="w-full md:w-auto px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg flex items-center justify-center gap-2 transition-colors">
            <i class="fa-solid fa-plus"></i>
            Tambah Genre
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase bg-gray-50 text-gray-700 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nama Genre</th>
                        <th class="px-6 py-4">Jumlah Film</th>
                        <th class="px-6 py-4">Dibuat</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($genres as $index => $genre)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-900 font-medium">
                                {{ $genres->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-tag text-purple-500"></i>
                                    <span class="font-semibold text-gray-900">{{ $genre->nama_genre }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <i class="fa-solid fa-film mr-1"></i>{{ $genre->filmGenres->count() }} film
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                {{ $genre->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="openEditModal({{ $genre->id }})"
                                            class="p-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition-colors"
                                            title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button onclick="confirmDelete({{ $genre->id }})"
                                            class="p-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors"
                                            title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5"
                                class="text-center py-12 text-gray-500">
                                <i class="fa-solid fa-tags text-4xl mb-3 block text-gray-300"></i>
                                <p class="font-medium">Tidak ada data genre</p>
                                <p class="text-sm mt-1">Silakan tambahkan genre baru</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($genres->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $genres->links() }}
            </div>
        @endif
    </div>

    <!-- Create/Edit Modal (dipercantik & konsisten) -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto"
             x-data
             x-init="$el.showModal()"
             wire:ignore.self>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Overlay -->
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                     wire:click="closeModal"></div>

                <!-- Modal Content -->
                <div
                     class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="save">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fa-solid fa-tag mr-2 text-blue-600"></i>
                                {{ $modalMode === 'create' ? 'Tambah Genre' : 'Edit Genre' }}
                            </h3>

                            <div class="mb-4">
                                <label for="nama_genre"
                                       class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Genre <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       wire:model="nama_genre"
                                       id="nama_genre"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama_genre') border-red-500 @enderror"
                                       placeholder="Contoh: Action">
                                @error('nama_genre')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Masukkan nama genre film (maksimal 50 karakter)</p>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                            <button type="submit"
                                    class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                <i class="fa-solid fa-save mr-2"></i>
                                {{ $modalMode === 'create' ? 'Simpan' : 'Update' }}
                            </button>
                            <button type="button"
                                    wire:click="closeModal"
                                    class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors">
                                <i class="fa-solid fa-times mr-2"></i>
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Genre?',
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
