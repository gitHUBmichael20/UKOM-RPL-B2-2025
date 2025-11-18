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
        <p class="text-gray-600 mt-1">Perbarui informasi studio</p>
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

                    @if($kapasitas_kursi != $kapasitas_lama)
                        <div class="mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-sm text-yellow-800">
                                <i class="fa-solid fa-warning mr-1"></i>
                                <strong>Perhatian:</strong> Mengubah kapasitas akan menghapus semua kursi lama dan membuat
                                kursi baru!
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
                              wire:target="update">
                            <i class="fa-solid fa-save"></i>
                            Update Studio
                        </span>
                        <span wire:loading
                              wire:target="update">
                            <i class="fa-solid fa-spinner fa-spin"></i>
                            Mengupdate...
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
                    <i class="fa-solid fa-info-circle text-blue-600 text-2xl mr-3"></i>
                    <h3 class="text-lg font-semibold text-blue-900">Informasi</h3>
                </div>

                <div class="space-y-4 text-sm text-blue-800">
                    <div>
                        <p class="font-medium mb-1">📊 Kapasitas Saat Ini:</p>
                        <p>{{ $kapasitas_lama }} kursi</p>
                    </div>

                    @if($kapasitas_kursi && $kapasitas_kursi != $kapasitas_lama)
                        <div class="mt-4 pt-4 border-t border-blue-300">
                            <p class="font-medium mb-2">Preview Baru ({{ $kapasitas_kursi }} kursi):</p>
                            @php
                                $seatsPerRow = 10;
                                $jumlahBaris = ceil($kapasitas_kursi / $seatsPerRow);
                                $hurufBaris = range('A', 'Z');
                            @endphp
                            <div class="bg-white rounded p-3 text-xs">
                                <p class="text-gray-600 mb-2">
                                    • Jumlah baris: {{ $jumlahBaris }} baris<br>
                                    • Baris terakhir: {{ $hurufBaris[min($jumlahBaris - 1, 25)] }}<br>
                                    • Layout: 5 kiri + Gang + 5 kanan
                                </p>
                            </div>
                        </div>
                    @endif

                    <div class="mt-4 pt-4 border-t border-blue-300">
                        <p class="font-medium mb-1">ℹ️ Catatan:</p>
                        <ul class="list-disc list-inside space-y-1 text-xs">
                            <li>Kursi otomatis ter-generate saat simpan</li>
                            <li>Format: [Baris][Nomor] (A1, A2, dst)</li>
                            <li>Ada gang di tengah setiap baris</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>