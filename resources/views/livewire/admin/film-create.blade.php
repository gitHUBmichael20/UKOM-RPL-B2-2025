<div>
    <!-- Wrapper sederhana & konsisten dengan semua halaman sebelumnya -->
    <div class="p-6">
        <div class="mt-14">

            <!-- Header + Breadcrumb + Deskripsi -->
            <div class="mb-6">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center space-x-2 text-gray-600 mb-1">
                            <a href="{{ route('admin.film.management') }}" class="hover:text-blue-600 flex items-center gap-2">
                                <i class="fa-solid fa-film"></i> Film
                            </a>
                            <span>/</span>
                            <span class="text-gray-900 font-medium">Tambah Film</span>
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900">Tambah Film</h1>
                        <p class="mt-1 text-gray-600">Tambahkan film baru yang akan tayang di bioskop</p>
                    </div>
                </div>
            </div>

            <!-- Error Alert -->
            @if (session()->has('error'))
                <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg flex items-center">
                    <i class="fa-solid fa-circle-exclamation mr-3"></i>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <form wire:submit.prevent="save">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <!-- Judul -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Judul Film <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="judul"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('judul') border-red-500 @enderror"
                                    placeholder="Masukkan judul film">
                                @error('judul')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Sutradara -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Sutradara <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="sutradara_id"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sutradara_id') border-red-500 @enderror">
                                    <option value="">Pilih Sutradara</option>
                                    @foreach ($sutradaras as $sutradara)
                                        <option value="{{ $sutradara->id }}">{{ $sutradara->nama_sutradara }}</option>
                                    @endforeach
                                </select>
                                @error('sutradara_id')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Durasi -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Durasi <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="durasi"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('durasi') border-red-500 @enderror"
                                    placeholder="Contoh: 120 menit">
                                @error('durasi')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Rating -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Rating <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="rating"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('rating') border-red-500 @enderror">
                                    <option value="">Pilih Rating</option>
                                    <option value="SU">SU - Semua Umur</option>
                                    <option value="R13+">R13+ - 13 Tahun ke atas</option>
                                    <option value="R17+">R17+ - 17 Tahun ke atas</option>
                                    <option value="D21+">D21+ - Dewasa 21 Tahun ke atas</option>
                                </select>
                                @error('rating')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tahun Rilis -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tahun Rilis <span class="text-red-500">*</span>
                                </label>
                                <input type="number" wire:model="tahun_rilis" min="1900" max="2100"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tahun_rilis') border-red-500 @enderror"
                                    placeholder="Contoh: 2024">
                                @error('tahun_rilis')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Poster -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Poster Film <span class="text-red-500">*</span>
                                </label>
                                <input type="file" wire:model="poster" accept="image/*"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('poster') border-red-500 @enderror">
                                @error('poster')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Format: JPEG, JPG, PNG. Max: 2MB</p>

                                <!-- Preview Poster -->
                                @if ($poster)
                                    <div class="mt-4">
                                        <img src="{{ $poster->temporaryUrl() }}" alt="Preview Poster"
                                            class="w-48 h-64 object-cover rounded-lg shadow-md border">
                                    </div>
                                @endif
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="status"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror">
                                    <option value="tayang">Tayang</option>
                                    <option value="segera">Segera Tayang</option>
                                    <option value="selesai">Selesai</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Genre -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Genre <span class="text-red-500">*</span>
                                </label>
                                <div class="p-4 border rounded-lg max-h-48 overflow-y-auto space-y-3">
                                    @foreach ($genres as $genre)
                                        <label class="flex items-center space-x-3 cursor-pointer">
                                            <input type="checkbox" wire:model="selectedGenres" value="{{ $genre->id }}"
                                                class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                                            <span class="text-sm text-gray-700">{{ $genre->nama_genre }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('selectedGenres')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Sinopsis Full Width -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Sinopsis <span class="text-red-500">*</span>
                            </label>
                            <textarea wire:model="sinopsis" rows="6"
                                class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sinopsis') border-red-500 @enderror"
                                placeholder="Masukkan sinopsis film..."></textarea>
                            @error('sinopsis')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 flex justify-end gap-3">
                        <button type="button" wire:click="cancel"
                            class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition duration-200">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition duration-200 flex items-center gap-2">
                            <i class="fa-solid fa-save mr-2"></i>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>