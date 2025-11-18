<div class="p-3">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-600 mb-4">
            <a href="{{ route('admin.film.index') }}"
               class="hover:text-blue-600">Film</a>
            <i class="fa-solid fa-chevron-right mx-2 text-xs"></i>
            <span class="text-gray-900">Edit Film</span>
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Edit Film</h2>
        <p class="text-gray-600 mt-1">Perbarui informasi film</p>
    </div>

    <!-- Alert Messages -->
    @if (session()->has('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg"
             role="alert">
            <div class="flex">
                <i class="fa-solid fa-circle-check mr-3 mt-0.5"></i>
                <div>
                    <p class="font-bold">Berhasil!</p>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

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

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form wire:submit.prevent="update">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Judul -->
                    <div>
                        <label for="judul"
                               class="block text-sm font-medium text-gray-700 mb-2">
                            Judul Film <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               wire:model="judul"
                               id="judul"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('judul') border-red-500 @enderror"
                               placeholder="Masukkan judul film">
                        @error('judul')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sutradara dengan Tom Select -->
                    <div wire:ignore>
                        <label for="sutradara_id"
                               class="block text-sm font-medium text-gray-700 mb-2">
                            Sutradara <span class="text-red-500">*</span>
                        </label>
                        <select id="sutradara_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg @error('sutradara_id') border-red-500 @enderror">
                            <option value="">Pilih Sutradara</option>
                            @foreach ($sutradaras as $sutradara)
                                <option value="{{ $sutradara->id }}"
                                        {{ $sutradara_id == $sutradara->id ? 'selected' : '' }}>
                                    {{ $sutradara->nama_sutradara }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('sutradara_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <!-- Durasi -->
                    <div>
                        <label for="durasi"
                               class="block text-sm font-medium text-gray-700 mb-2">
                            Durasi (dalam menit) <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               wire:model="durasi"
                               id="durasi"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('durasi') border-red-500 @enderror"
                               placeholder="Contoh: 120">
                        @error('durasi')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Rating -->
                    <div>
                        <label for="rating"
                               class="block text-sm font-medium text-gray-700 mb-2">
                            Rating <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="rating"
                                id="rating"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('rating') border-red-500 @enderror">
                            <option value="">Pilih Rating</option>
                            <option value="SU">SU - Semua Umur</option>
                            <option value="R13+">R13+ - 13 Tahun ke atas</option>
                            <option value="R17+">R17+ - 17 Tahun ke atas</option>
                            <option value="D21+">D21+ - Dewasa 21 Tahun ke atas</option>
                        </select>
                        @error('rating')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tahun Rilis -->
                    <div>
                        <label for="tahun_rilis"
                               class="block text-sm font-medium text-gray-700 mb-2">
                            Tahun Rilis <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               wire:model="tahun_rilis"
                               id="tahun_rilis"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tahun_rilis') border-red-500 @enderror"
                               placeholder="Contoh: 2024"
                               min="1900"
                               max="2100">
                        @error('tahun_rilis')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Poster - COMPACT VERSION -->
                    <div>
                        <label for="poster"
                               class="block text-sm font-medium text-gray-700 mb-2">
                            Poster Film
                        </label>

                        <!-- Single Preview Area -->
                        <div class="mb-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-xs text-gray-600 mb-2 font-medium">Preview Poster:</p>
                            @if ($poster)
                                <!-- Show NEW poster if uploaded -->
                                <img src="{{ $poster->temporaryUrl() }}"
                                     alt="Preview Poster Baru"
                                     class="w-48 h-64 object-cover rounded-lg shadow-md">
                            @elseif ($oldPoster)
                                <!-- Show OLD poster if no new upload -->
                                <img src="{{ asset('storage/' . $oldPoster) }}"
                                     alt="Preview Poster"
                                     class="w-48 h-64 object-cover rounded-lg shadow-md">
                            @else
                                <!-- No poster at all -->
                                <div class="w-48 h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <i class="fa-solid fa-image text-gray-400 text-4xl"></i>
                                </div>
                            @endif
                        </div>

                        <input type="file"
                               wire:model="poster"
                               id="poster"
                               accept="image/*"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('poster') border-red-500 @enderror">
                        @error('poster')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Format: JPEG, JPG, PNG. Max: 2MB. Kosongkan jika tidak
                            ingin mengganti.</p>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status"
                               class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="status"
                                id="status"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror">
                            <option value="tayang">Tayang</option>
                            <option value="segera">Segera Tayang</option>
                            <option value="selesai">Selesai</option>
                        </select>
                        @error('status')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Genre -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Genre <span class="text-red-500">*</span>
                        </label>
                        <div
                             class="grid grid-cols-2 gap-3 p-4 border border-gray-300 rounded-lg max-h-64 overflow-y-auto bg-gray-50">
                            @foreach ($genres as $genre)
                                <label
                                       class="flex items-center space-x-2 cursor-pointer hover:bg-white p-2 rounded transition-colors">
                                    <input type="checkbox"
                                           wire:model="selectedGenres"
                                           value="{{ $genre->id }}"
                                           class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500 border-gray-300">
                                    <span class="text-sm text-gray-700">{{ $genre->nama_genre }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('selectedGenres')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Sinopsis (Full Width) -->
                <div class="lg:col-span-2">
                    <label for="sinopsis"
                           class="block text-sm font-medium text-gray-700 mb-2">
                        Sinopsis <span class="text-red-500">*</span>
                    </label>
                    <textarea wire:model="sinopsis"
                              id="sinopsis"
                              rows="5"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sinopsis') border-red-500 @enderror"
                              placeholder="Masukkan sinopsis film..."></textarea>
                    @error('sinopsis')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-3 pt-6 mt-6 border-t border-gray-200">
                <button type="submit"
                        wire:loading.attr="disabled"
                        class="bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white px-6 py-3 rounded-lg font-medium transition-colors flex items-center gap-2">
                    <span wire:loading.remove
                          wire:target="update">
                        <i class="fa-solid fa-save"></i>
                        Update Film
                    </span>
                    <span wire:loading
                          wire:target="update">
                        <i class="fa-solid fa-spinner fa-spin"></i>
                        Mengupdate...
                    </span>
                </button>
                <a href="{{ route('admin.film.index') }}"
                   class="bg-white hover:bg-gray-50 text-gray-700 px-6 py-3 rounded-lg font-medium border border-gray-300 transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-times"></i>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi Tom Select untuk Sutradara
    const sutradaraSelect = new TomSelect('#sutradara_id', {
        placeholder: 'Pilih atau cari sutradara...',
        allowEmptyOption: true,
        create: false,
        maxOptions: null,
        onChange: function(value) {
            // Sinkronisasi dengan Livewire
            @this.set('sutradara_id', value);
        }
    });

    // Update Tom Select saat Livewire data berubah
    window.addEventListener('livewire:load', function() {
        Livewire.hook('message.processed', (message, component) => {
            if (sutradaraSelect) {
                sutradaraSelect.setValue(@this.sutradara_id);
            }
        });
    });
});
</script>
@endpush