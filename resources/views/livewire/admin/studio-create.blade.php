<div class="p-3">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-600 mb-4">
            <a href="{{ route('admin.studio.index') }}"
               class="hover:text-blue-600">Studio</a>
            <i class="fa-solid fa-chevron-right mx-2 text-xs"></i>
            <span class="text-gray-900">Tambah Studio</span>
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Tambah Studio Baru</h2>
        <p class="text-gray-600 mt-1">Tentukan layout kursi berdasarkan jumlah baris dan kolom</p>
    </div>

    <!-- Alert Messages -->
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
            <form wire:submit.prevent="save"
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
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama_studio') border-red-500 @enderror"
                           placeholder="Contoh: Studio 1">
                    @error('nama_studio')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipe Studio -->
                <div class="mb-6">
                    <label for="tipe_studio"
                           class="block text-sm font-medium text-gray-700 mb-2">
                        Tipe Studio <span class="text-red-500">*</span>
                    </label>
                    <select id="tipe_studio"
                            wire:model="tipe_studio"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tipe_studio') border-red-500 @enderror">
                        <option value="regular">Regular</option>
                        <option value="deluxe">Deluxe</option>
                        <option value="imax">IMAX</option>
                    </select>
                    @error('tipe_studio')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Layout Kursi -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg border-2 border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fa-solid fa-border-all mr-2 text-blue-600"></i>
                        Layout Kursi
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Jumlah Baris -->
                        <div>
                            <label for="jumlah_baris"
                                   class="block text-sm font-medium text-gray-700 mb-2">
                                Jumlah Baris <span class="text-red-500">*</span>
                            </label>
                            <input type="number"
                                   id="jumlah_baris"
                                   wire:model.live="jumlah_baris"
                                   min="2"
                                   max="26"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('jumlah_baris') border-red-500 @enderror"
                                   placeholder="2 - 26 baris">
                            @error('jumlah_baris')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Huruf A-Z (max 26 baris)</p>
                        </div>

                        <!-- Jumlah Kolom -->
                        <div>
                            <label for="jumlah_kolom"
                                   class="block text-sm font-medium text-gray-700 mb-2">
                                Jumlah Kolom <span class="text-red-500">*</span>
                            </label>
                            <input type="number"
                                   id="jumlah_kolom"
                                   wire:model.live="jumlah_kolom"
                                   min="4"
                                   max="20"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('jumlah_kolom') border-red-500 @enderror"
                                   placeholder="4 - 20 kolom">
                            @error('jumlah_kolom')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Gang otomatis di tengah (boleh ganjil)</p>
                        </div>
                    </div>

                    <!-- Total Kapasitas -->
                    @if($jumlah_baris && $jumlah_kolom)
                        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-blue-900">Total Kapasitas:</span>
                                <span class="text-2xl font-bold text-blue-600">
                                    {{ $this->kapasitasKursi }} kursi
                                </span>
                            </div>
                            <p class="text-xs text-blue-700 mt-1">
                                {{ $jumlah_baris }} baris × {{ $jumlah_kolom }} kolom
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex gap-3 pt-6 border-t border-gray-200">
                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white px-6 py-3 rounded-lg font-medium transition-colors flex items-center gap-2">
                        <span wire:loading.remove
                              wire:target="save">
                            <i class="fa-solid fa-save"></i>
                            Simpan Studio
                        </span>
                        <span wire:loading
                              wire:target="save">
                            <i class="fa-solid fa-spinner fa-spin"></i>
                            Menyimpan...
                        </span>
                    </button>
                    <a href="{{ route('admin.studio.index') }}"
                       class="bg-white hover:bg-gray-50 text-gray-700 px-6 py-3 rounded-lg font-medium border border-gray-300 transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>

        <!-- Info Panel -->
        <div class="lg:col-span-1">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 sticky top-6">
                <div class="flex items-center mb-4">
                    <i class="fa-solid fa-lightbulb text-blue-600 text-2xl mr-3"></i>
                    <h3 class="text-lg font-semibold text-blue-900">Info Layout</h3>
                </div>

                <div class="space-y-4 text-sm text-blue-800">
                    <div>
                        <p class="font-medium mb-1">📐 Format Nomor Kursi:</p>
                        <p>[Huruf Baris][Nomor Kolom]</p>
                        <p class="text-xs mt-1">Contoh: A1, A2, B1, B2</p>
                    </div>

                    <div>
                        <p class="font-medium mb-1">🪑 Layout dengan Gang:</p>
                        <p>Kursi dibagi dua dengan gang di tengah</p>
                        <p class="text-xs mt-1">Genap: A1-A5 [Gang] A6-A10</p>
                        <p class="text-xs">Ganjil: A1-A4 [Gang] A5-A9</p>
                    </div>

                    <div>
                        <p class="font-medium mb-1">🔤 Baris:</p>
                        <p>Menggunakan huruf A-Z</p>
                        <p class="text-xs mt-1">Maksimal 26 baris</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>