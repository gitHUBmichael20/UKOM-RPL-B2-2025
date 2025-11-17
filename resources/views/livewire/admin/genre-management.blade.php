<div>
    <!-- Wrapper konsisten: p-6 + mt-14 -->
    <div class="p-6">
        <div class="mt-14">

            <!-- Header + Tombol Tambah di kanan -->
            <div class="mb-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Kelola Genre</h1>
                        <p class="mt-1 text-gray-600">Kelola data genre film</p>
                    </div>
                    <button wire:click="openCreateModal"
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg flex items-center gap-2 transition duration-200">
                        <i class="fa-solid fa-plus"></i>
                        Tambah Genre
                    </button>
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
                    <i class="fa-solid fa-circle-exclamation mr-3"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Search (satu kolom penuh di mobile, seimbang di desktop) -->
            <div class="mb-6">
                <div class="relative max-w-md">
                    <input type="text" wire:model.live.debounce.300ms="search"
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Cari genre...">
                    <i class="fa-solid fa-search absolute left-3 top-3.5 text-gray-400"></i>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs uppercase bg-gray-50 text-gray-600">
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
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-medium">
                                        {{ $genres->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-900">
                                        {{ $genre->nama_genre }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                            {{ $genre->filmGenres->count() }} film
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $genre->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <button wire:click="openEditModal({{ $genre->id }})"
                                                class="p-2.5 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition duration-200">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <button wire:click="openDeleteModal({{ $genre->id }})"
                                                class="p-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg transition duration-200">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-16 text-gray-500">
                                        <i class="fa-solid fa-tags text-5xl mb-4 block text-gray-300"></i>
                                        <p class="text-lg">Tidak ada data genre</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $genres->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal (dipercantik & konsisten) -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6" wire:click.stop>
                <h3 class="text-xl font-semibold text-gray-900 mb-5">
                    {{ $modalMode === 'create' ? 'Tambah Genre' : 'Edit Genre' }}
                </h3>

                <form wire:submit.prevent="save">
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Genre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="nama_genre"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama_genre') border-red-500 @enderror"
                            placeholder="Masukkan nama genre">
                        @error('nama_genre')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" wire:click="closeModal"
                            class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition flex items-center gap-2">
                            <i class="fa-solid fa-save mr-2"></i>
                            {{ $modalMode === 'create' ? 'Simpan' : 'Update' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal (dipercantik & konsisten) -->
    @if ($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6" wire:click.stop>
                <div class="flex items-center gap-4 mb-5">
                    <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">Hapus Genre</h3>
                </div>

                <p class="text-gray-600 mb-6">
                    Apakah Anda yakin ingin menghapus genre "<strong>{{ $genreName ?? '' }}</strong>"?<br>
                    Data yang sudah dihapus tidak dapat dikembalikan.
                </p>

                <div class="flex justify-end gap-3">
                    <button type="button" wire:click="closeDeleteModal"
                        class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition">
                        Batal
                    </button>
                    <button type="button" wire:click="delete"
                        class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition flex items-center gap-2">
                        <i class="fa-solid fa-trash mr-2"></i>
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>