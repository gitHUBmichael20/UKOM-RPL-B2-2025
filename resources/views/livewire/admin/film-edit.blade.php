<div>
    <div class="p-4 sm:ml-64">
        <div class="p-4 mt-14">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('admin.film.management') }}" class="hover:text-blue-600">Film</a>
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                    <span>Edit Film</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Edit Film</h1>
            </div>

            @if (session()->has('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <i class="fa-solid fa-circle-xmark mr-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Form Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <form wire:submit.prevent="update">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <!-- Judul -->
                            <div>
                                <label for="judul"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Judul Film <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="judul" id="judul"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="Masukkan judul film">
                                @error('judul')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Sutradara -->
                            <div>
                                <label for="sutradara_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Sutradara <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="sutradara_id" id="sutradara_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">Pilih Sutradara</option>
                                    @foreach ($sutradaras as $sutradara)
                                        <option value="{{ $sutradara->id }}">{{ $sutradara->nama_sutradara }}</option>
                                    @endforeach
                                </select>
                                @error('sutradara_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Durasi -->
                            <div>
                                <label for="durasi"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Durasi <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="durasi" id="durasi"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="Contoh: 120 menit">
                                @error('durasi')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Rating -->
                            <div>
                                <label for="rating"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Rating <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="rating" id="rating"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">Pilih Rating</option>
                                    <option value="SU">SU - Semua Umur</option>
                                    <option value="R13+">R13+ - 13 Tahun ke atas</option>
                                    <option value="R17+">R17+ - 17 Tahun ke atas</option>
                                    <option value="D21+">D21+ - Dewasa 21 Tahun ke atas</option>
                                </select>
                                @error('rating')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tahun Rilis -->
                            <div>
                                <label for="tahun_rilis"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tahun Rilis <span class="text-red-500">*</span>
                                </label>
                                <input type="number" wire:model="tahun_rilis" id="tahun_rilis"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="Contoh: 2024" min="1900" max="2100">
                                @error('tahun_rilis')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Poster -->
                            <div>
                                <label for="poster"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Poster Film
                                </label>

                                <!-- Current Poster -->
                                @if ($oldPoster)
                                    <div class="mb-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Poster saat ini:</p>
                                        <img src="{{ asset('storage/' . $oldPoster) }}" alt="Current Poster"
                                            class="w-48 h-64 object-cover rounded-lg shadow">
                                    </div>
                                @endif

                                <input type="file" wire:model="poster" id="poster" accept="image/*"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @error('poster')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format: JPEG, JPG, PNG. Max: 2MB.
                                    Kosongkan jika tidak ingin mengganti.</p>

                                <!-- New Preview -->
                                @if ($poster)
                                    <div class="mt-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Preview poster baru:</p>
                                        <img src="{{ $poster->temporaryUrl() }}" alt="New Preview"
                                            class="w-48 h-64 object-cover rounded-lg shadow">
                                    </div>
                                @endif
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="status" id="status"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="tayang">Tayang</option>
                                    <option value="segera">Segera Tayang</option>
                                    <option value="selesai">Selesai</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Genre -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Genre <span class="text-red-500">*</span>
                                </label>
                                <div
                                    class="grid grid-cols-2 gap-2 p-4 border border-gray-300 rounded-lg dark:border-gray-600 max-h-48 overflow-y-auto">
                                    @foreach ($genres as $genre)
                                        <label class="flex items-center space-x-2 cursor-pointer">
                                            <input type="checkbox" wire:model="selectedGenres"
                                                value="{{ $genre->id }}"
                                                class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                                            <span
                                                class="text-sm text-gray-700 dark:text-gray-300">{{ $genre->nama_genre }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('selectedGenres')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Sinopsis (Full Width) -->
                        <div class="lg:col-span-2">
                            <label for="sinopsis"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Sinopsis <span class="text-red-500">*</span>
                            </label>
                            <textarea wire:model="sinopsis" id="sinopsis" rows="5"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan sinopsis film..."></textarea>
                            @error('sinopsis')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-3 pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-2">
                            <i class="fa-solid fa-save"></i>
                            Update
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