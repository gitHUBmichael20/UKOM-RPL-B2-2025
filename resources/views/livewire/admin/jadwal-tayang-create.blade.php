<div>
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-600 mb-4">
            <a href="{{ route('admin.jadwal-tayang.index') }}"
               class="hover:text-blue-600">Jadwal Tayang</a>
            <i class="fa-solid fa-chevron-right mx-2 text-xs"></i>
            <span class="text-gray-900">Tambah Jadwal Tayang</span>
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Tambah Jadwal Tayang</h2>
        <p class="text-gray-600 mt-1">Buat jadwal tayang baru untuk film (bisa multiple jam tayang)</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <form wire:submit="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Film dengan Tom Select -->
                <div wire:ignore>
                    <label for="film_id"
                           class="block text-sm font-medium text-gray-700 mb-2">
                        Film <span class="text-red-500">*</span>
                    </label>
                    <select id="film_id"
                            class="w-full px-2 py-2 border border-gray-300 rounded-lg @error('film_id') border-red-500 @enderror">
                        <option value="">Pilih Film</option>
                        @foreach($films as $film)
                            <option value="{{ $film->id }}"
                                    data-durasi="{{ $film->durasi }}">
                                {{ $film->judul }} ({{ $film->durasi }} menit)
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('film_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror

                <!-- Studio dengan Tom Select -->
                <div wire:ignore>
                    <label for="studio_id"
                           class="block text-sm font-medium text-gray-700 mb-2">
                        Studio <span class="text-red-500">*</span>
                    </label>
                    <select id="studio_id"
                            class="w-full px-2 py-2 border border-gray-300 rounded-lg @error('studio_id') border-red-500 @enderror">
                        <option value="">Pilih Studio</option>
                        @foreach($studios as $studio)
                            <option value="{{ $studio->id }}">
                                {{ $studio->nama_studio }} ({{ $studio->kapasitas_kursi }} kursi)
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('studio_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror

                <!-- Tanggal Tayang -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Tayang <span class="text-red-500">*</span>
                    </label>
                    <input type="date"
                           wire:model="tanggal_tayang"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tanggal_tayang') border-red-500 @enderror">
                    @error('tanggal_tayang')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tambah Jam Tayang -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tambah Jam Tayang <span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-2">
                        <input type="time"
                               wire:model="new_jam"
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('new_jam') border-red-500 @enderror">
                        <button type="button"
                                wire:click="addJamTayang"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    @error('new_jam')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    @error('jam_tayang')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Info Durasi Film -->
            @if($film_duration > 0)
                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-blue-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        Durasi film: <strong>{{ $film_duration }} menit</strong>
                    </p>
                </div>
            @endif

            <!-- List Jam Tayang yang Sudah Ditambahkan -->
            @if(!empty($jam_tayang))
                <div class="mt-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">
                        Jam Tayang Terpilih ({{ count($jam_tayang) }})
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach($jam_tayang as $index => $jam)
                            <div
                                 class="flex items-center justify-between p-3 bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg">
                                <div class="flex-1">
                                    <div class="text-sm font-semibold text-blue-900">
                                        {{ $jam }} WIB
                                    </div>
                                    @if($film_duration > 0)
                                        <div class="text-xs text-blue-600 mt-1">
                                            Selesai: {{ \Carbon\Carbon::parse($jam)->addMinutes($film_duration)->format('H:i') }}
                                        </div>
                                    @endif
                                </div>
                                <button type="button"
                                        wire:click="removeJamTayang({{ $index }})"
                                        class="ml-2 text-red-600 hover:text-red-800 hover:bg-red-100 p-1.5 rounded transition">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-sm text-yellow-800">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Belum ada jam tayang yang ditambahkan. Silakan tambahkan minimal 1 jam tayang.
                    </p>
                </div>
            @endif

            <div class="mt-6 flex gap-3">
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                        @if(empty($jam_tayang))
                            disabled
                        @endif>
                    <i class="fas fa-save mr-2"></i>Simpan Semua Jadwal
                </button>
                <a href="{{ route('admin.jadwal-tayang.index') }}"
                   class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inisialisasi Tom Select untuk Film
            const filmSelect = new TomSelect('#film_id', {
                placeholder: 'Pilih atau cari film...',
                allowEmptyOption: true,
                create: false,
                maxOptions: null,
                onChange: function (value) {
                    @this.set('film_id', value);

                    if (value) {
                        const option = this.options[value];
                        if (option) {
                            const durasi = filmSelect.input.querySelector(`option[value="${value}"]`).dataset.durasi;
                            @this.set('film_duration', parseInt(durasi) || 0);
                        }
                    }
                }
            });

            // Inisialisasi Tom Select untuk Studio
            const studioSelect = new TomSelect('#studio_id', {
                placeholder: 'Pilih atau cari studio...',
                allowEmptyOption: true,
                create: false,
                maxOptions: null,
                onChange: function (value) {
                    @this.set('studio_id', value);
                }
            });
        });
    </script>
@endpush