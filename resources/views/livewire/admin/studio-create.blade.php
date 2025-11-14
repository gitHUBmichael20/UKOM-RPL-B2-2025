<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-600 mb-4">
            <a href="{{ route('admin.studio.index') }}"
               class="hover:text-blue-600">Studio</a>
            <i class="fa-solid fa-chevron-right mx-2 text-xs"></i>
            <span class="text-gray-900">Tambah Studio</span>
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Tambah Studio Baru</h2>
        <p class="text-gray-600 mt-1">Isi form di bawah untuk menambahkan studio dan kursi akan digenerate otomatis</p>
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

                <!-- Kapasitas Kursi -->
                <div class="mb-6">
                    <label for="kapasitas_kursi"
                           class="block text-sm font-medium text-gray-700 mb-2">
                        Kapasitas Kursi <span class="text-red-500">*</span>
                    </label>
                    <input type="number"
                           id="kapasitas_kursi"
                           wire:model.live="kapasitas_kursi"
                           min="20"
                           max="200"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kapasitas_kursi') border-red-500 @enderror"
                           placeholder="Minimal 20, Maksimal 200">
                    @error('kapasitas_kursi')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">
                        <i class="fa-solid fa-info-circle mr-1"></i>
                        Kursi akan digenerate otomatis dengan format: [BARIS][NOMOR] (A1, A2, dst) dengan gang di tengah
                    </p>
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
                    <h3 class="text-lg font-semibold text-blue-900">Info Layout Kursi</h3>
                </div>

                <div class="space-y-4 text-sm text-blue-800">
                    <div>
                        <p class="font-medium mb-1">📐 Format Nomor Kursi:</p>
                        <p>[Huruf Baris][Nomor] - Contoh: A1, A2, B1</p>
                    </div>

                    <div>
                        <p class="font-medium mb-1">🪑 Layout Per Baris:</p>
                        <p>5 kursi (kiri) + Gang + 5 kursi (kanan)</p>
                        <p class="text-xs mt-1">Total: 10 kursi per baris</p>
                    </div>

                    <div>
                        <p class="font-medium mb-1">🔤 Nama Baris:</p>
                        <p>Menggunakan huruf A-Z secara berurutan</p>
                    </div>

                    @if($kapasitas_kursi)
                        <div class="mt-4 pt-4 border-t border-blue-300">
                            <p class="font-medium mb-2">Preview dengan {{ $kapasitas_kursi }} kursi:</p>
                            @php
                                $seatsPerRow = 10;
                                $jumlahBaris = ceil($kapasitas_kursi / $seatsPerRow);
                                $hurufBaris = range('A', 'Z');
                            @endphp
                            <div class="bg-white rounded p-3 text-xs">
                                <p class="text-gray-600 mb-2">
                                    • Jumlah baris: {{ $jumlahBaris }} baris<br>
                                    • Baris terakhir: {{ $hurufBaris[min($jumlahBaris - 1, 25)] }}<br>
                                    • Layout: {{ $hurufBaris[0] }}1-{{ $hurufBaris[0] }}5, Gang,
                                    {{ $hurufBaris[0] }}6-{{ $hurufBaris[0] }}10
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>