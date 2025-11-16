<div>
    <div class="p-4 sm:ml-64">
        <div class="p-4 mt-14">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('admin.genre.management') }}" class="hover:text-blue-600">Genre</a>
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                    <span>Tambah Genre</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Tambah Genre</h1>
            </div>

            <!-- Form Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 max-w-2xl">
                <form wire:submit.prevent="save">
                    <div class="mb-6">
                        <label for="nama_genre"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nama Genre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="nama_genre" id="nama_genre"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Contoh: Action, Drama, Comedy">
                        @error('nama_genre')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Masukkan nama genre film (maksimal 50
                            karakter)</p>
                    </div>

                    <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-2">
                            <i class="fa-solid fa-save"></i>
                            Simpan
                        </button>
                        <button type="button" wire:click="cancel"
                            class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition flex items-center gap-2">
                            <i class="fa-solid fa-times"></i>
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>