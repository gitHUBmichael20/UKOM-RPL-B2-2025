<div>
    <!-- Wrapper konsisten dengan semua halaman admin -->
    <div class="p-3">
        <div class="mt-14">

            <!-- Header + Breadcrumb + Deskripsi -->
            <div class="mb-6">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center space-x-2 text-gray-600 mb-1">
                            <a href="{{ route('admin.genre.management') }}" class="hover:text-blue-600 flex items-center gap-2">
                                <i class="fa-solid fa-tags"></i> Genre
                            </a>
                            <span>/</span>
                            <span class="text-gray-900 font-medium">Edit Genre</span>
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900">Edit Genre</h1>
                        <p class="mt-1 text-gray-600">Ubah nama genre film</p>
                    </div>
                </div>
            </div>

            <!-- Form Card (max-w-2xl biar tidak terlalu lebar) -->
            <div class="bg-white rounded-lg shadow-sm p-6 max-w-2xl">
                <form wire:submit.prevent="update">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Genre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="nama_genre"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama_genre') border-red-500 @enderror"
                            placeholder="Contoh: Action, Drama, Comedy">
                        @error('nama_genre')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Masukkan nama genre film (maksimal 50 karakter)</p>
                    </div>

                    <!-- Action Buttons – persis sama seperti semua form admin lainnya -->
                    <div class="mt-8 flex justify-end gap-3">
                        <button type="button" wire:click="cancel"
                            class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition duration-200">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition duration-200 flex items-center gap-2">
                            <i class="fa-solid fa-save mr-2"></i>
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>