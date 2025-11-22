<div class="p-3">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-600 mb-4">
            <a href="{{ route('admin.studio.index') }}"
               class="hover:text-blue-600">Studio</a>
            <i class="fa-solid fa-chevron-right mx-2 text-xs"></i>
            <span class="text-gray-900">Edit Studio</span>
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Edit Studio</h2>
        <p class="text-gray-600 mt-1">Perbarui nama dan layout kursi studio</p>
    </div>

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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form -->
        <div class="lg:col-span-2">
            <form wire:submit.prevent="update"
                  class="bg-white rounded-lg shadow-sm p-6">
                <!-- Nama Studio -->
                <div class="mb-6">
                    <label for="nama_studio"
                           class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Studio <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="nama_studio"
                           wire:model="nama_studio"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('nama_studio') border-red-500 @enderror"
                           placeholder="Contoh: Studio 1">
                    @error('nama_studio') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Tipe Studio (Readonly) -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tipe Studio
                    </label>
                    <div class="px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 font-medium">
                        {{ ucfirst($tipe_studio) }}
                        <span class="text-xs text-gray-500 ml-2">(Tidak dapat diubah)</span>
                    </div>
                </div>

                <!-- Layout Kursi -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg border-2 border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fa-solid fa-border-all mr-2 text-blue-600"></i>
                        Layout Kursi Saat Ini
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Baris</label>
                            <input type="number"
                                   wire:model.live="jumlah_baris"
                                   min="2"
                                   max="26"
                                   placeholder="2 - 26"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg @error('jumlah_baris') border-red-500 @enderror">
                            @error('jumlah_baris') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Kolom</label>
                            <input type="number"
                                   wire:model.live="jumlah_kolom"
                                   min="4"
                                   max="20"
                                   placeholder="4 - 20"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg @error('jumlah_kolom') border-red-500 @enderror">
                            @error('jumlah_kolom') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Preview Kapasitas Baru -->
                    @if($kapasitasBaru > 0)
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-blue-900">Kapasitas Baru:</p>
                                    <p class="text-3xl font-bold text-blue-600">{{ $kapasitasBaru }} kursi</p>
                                    <p class="text-xs text-blue-700">{{ $jumlah_baris }} baris × {{ $jumlah_kolom }} kolom
                                    </p>
                                </div>
                                @if($kapasitasBaru != $kapasitas_lama)
                                    <div class="text-right">
                                        <p class="text-sm text-yellow-700 font-medium">
                                            Warning: Semua kursi akan digenerate ulang!
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex gap-3 pt-6 border-t border-gray-200">
                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2">
                        <span wire:loading.remove
                              wire:target="update">
                            <i class="fa-solid fa-save"></i> Update Studio
                        </span>
                        <span wire:loading
                              wire:target="update">
                            <i class="fa-solid fa-spinner fa-spin"></i> Mengupdate...
                        </span>
                    </button>
                    <a href="{{ route('admin.studio.index') }}"
                       class="bg-white hover:bg-gray-50 text-gray-700 px-6 py-3 rounded-lg font-medium border border-gray-300">
                        Batal
                    </a>
                </div>
            </form>
        </div>

        <!-- Info Panel -->
        <div class="lg:col-span-1">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 sticky top-6">
                <div class="flex items-center mb-4">
                    <i class="fa-solid fa-info-circle text-blue-600 text-2xl mr-3"></i>
                    <h3 class="text-lg font-semibold text-blue-900">Informasi</h3>
                </div>
                <div class="space-y-4 text-sm text-blue-800">
                    <div>
                        <p class="font-medium">Kapasitas Saat Ini:</p>
                        <p class="text-xl font-bold">{{ $kapasitas_lama }} kursi</p>
                    </div>
                    <div>
                        <p class="font-medium">Tipe Studio:</p>
                        <p class="font-bold text-lg">{{ ucfirst($tipe_studio) }}</p>
                        <p class="text-xs mt-1">Tipe tidak dapat diubah karena berpengaruh pada harga tiket</p>
                    </div>
                    <div class="pt-4 border-t border-blue-300">
                        <p class="font-medium mb-2">Catatan:</p>
                        <ul class="text-xs space-y-1 list-disc list-inside">
                            <li>Layout kursi menggunakan gang tengah</li>
                            <li>Nomor kursi: A1, A2, ..., B1, dst</li>
                            <li>Ubah baris/kolom → semua kursi akan di-regenerate</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>