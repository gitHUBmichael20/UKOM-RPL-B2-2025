<div>
    <!-- Wrapper sederhana seperti halaman Studio & form Tambah sebelumnya -->
    <div class="p-6">
        <div class="mt-14">
            <!-- Header Konsisten -->
            <div class="mb-6">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center space-x-2 text-gray-600 mb-1">
                            <a href="{{ route('admin.harga-tiket.management') }}" class="hover:text-blue-600">
                                <i class="fa-solid fa-ticket"></i> Harga Tiket
                            </a>
                            <span>/</span>
                            <span class="text-gray-900 font-medium">Edit Harga Tiket</span>
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900">Edit Harga Tiket</h1>
                        <p class="mt-1 text-gray-600">Ubah harga tiket bioskop berdasarkan tipe studio dan hari</p>
                    </div>
                </div>
            </div>

            <!-- Alert Messages -->
            @if (session()->has('error'))
                <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg">
                    <div class="flex items-center">
                        <i class="fa-solid fa-circle-exclamation mr-2"></i>
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <form wire:submit.prevent="update">
                    <div class="space-y-6">
                        <!-- Tipe Studio -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tipe Studio <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="tipe_studio"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                @error('tipe_studio') border-red-500 @enderror">
                                <option value="">Pilih Tipe Studio</option>
                                @foreach($tipeStudioOptions as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('tipe_studio')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tipe Hari -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tipe Hari <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="tipe_hari"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                @error('tipe_hari') border-red-500 @enderror">
                                <option value="">Pilih Tipe Hari</option>
                                @foreach($tipeHariOptions as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('tipe_hari')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Harga -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Harga <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                                    Rp
                                </span>
                                <input type="number" wire:model="harga" step="0.01" min="0"
                                    placeholder="Masukkan harga tiket"
                                    class="w-full pl-12 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                    @error('harga') border-red-500 @enderror">
                            </div>
                            @error('harga')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Contoh: 35000 untuk Rp 35.000</p>
                        </div>

                        <!-- Info Box -->
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fa-solid fa-circle-info text-blue-500"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        <strong>Catatan:</strong> Kombinasi tipe studio dan tipe hari harus unik. 
                                        Pastikan tidak ada duplikasi data.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 flex justify-end gap-3">
                        <a href="{{ route('admin.harga-tiket.management') }}"
                            class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition duration-200">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition duration-200 flex items-center">
                            <i class="fa-solid fa-save mr-2"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>