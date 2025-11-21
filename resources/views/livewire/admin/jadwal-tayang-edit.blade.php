<div>
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-600 mb-4">
            <a href="{{ route('admin.jadwal-tayang.index') }}"
               class="hover:text-blue-600">Jadwal Tayang</a>
            <i class="fa-solid fa-chevron-right mx-2 text-xs"></i>
            <span class="text-gray-900">Edit Jadwal Tayang</span>
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Edit Jadwal Tayang</h2>
        <p class="text-gray-600 mt-1">Perbarui jadwal tayang film dengan validasi konflik waktu</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <form wire:submit="update">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Film dengan Tom Select -->
                <div wire:ignore>
                    <label for="film_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Film <span class="text-red-500">*</span>
                    </label>
                    <select id="film_id"
                            class="w-full px-2 py-2 border border-gray-300 rounded-lg @error('film_id') border-red-500 @enderror">
                        <option value="">Pilih Film</option>
                        @foreach($films as $film)
                            <option value="{{ $film->id }}" 
                                    data-durasi="{{ $film->durasi }}"
                                    {{ $film_id == $film->id ? 'selected' : '' }}>
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
                    <label for="studio_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Studio <span class="text-red-500">*</span>
                    </label>
                    <select id="studio_id"
                            class="w-full px-2 py-2 border border-gray-300 rounded-lg @error('studio_id') border-red-500 @enderror">
                        <option value="">Pilih Studio</option>
                        @foreach($studios as $studio)
                            <option value="{{ $studio->id }}"
                                    {{ $studio_id == $studio->id ? 'selected' : '' }}>
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

                <!-- Jam Tayang -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jam Tayang <span class="text-red-500">*</span>
                    </label>
                    <input type="time"
                           wire:model="jam_tayang"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('jam_tayang') border-red-500 @enderror">
                    @error('jam_tayang')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Info Durasi Film & Waktu Selesai -->
            @if($film_duration > 0)
                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-3"></i>
                        <div class="flex-1">
                            <p class="text-sm text-blue-800 font-medium mb-1">
                                Informasi Durasi Film
                            </p>
                            <div class="text-sm text-blue-700 space-y-1">
                                <div>• Durasi film: <strong>{{ $film_duration }} menit</strong></div>
                                @if($jam_tayang)
                                    <div>• Jam mulai: <strong>{{ $jam_tayang }} WIB</strong></div>
                                    <div>• Perkiraan selesai: <strong>{{ \Carbon\Carbon::parse($jam_tayang)->addMinutes($film_duration)->format('H:i') }} WIB</strong></div>
                                    <div class="text-xs text-blue-600 mt-2 italic">
                                        ⚠️ Sistem akan memvalidasi konflik jadwal berdasarkan rentang waktu ini
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Warning tentang validasi -->
            <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5 mr-3"></i>
                    <div class="text-sm text-yellow-800">
                        <strong>Catatan:</strong> Sistem akan memvalidasi konflik jadwal dengan mempertimbangkan durasi film. 
                        Pastikan tidak ada jadwal lain yang bentrok di studio dan tanggal yang sama.
                    </div>
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i>Perbarui Jadwal
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
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi Tom Select untuk Film
    const filmSelect = new TomSelect('#film_id', {
        placeholder: 'Pilih atau cari film...',
        allowEmptyOption: true,
        create: false,
        maxOptions: null,
        onChange: function(value) {
            // Sinkronisasi dengan Livewire
            @this.set('film_id', value);
            
            // Update durasi film
            if (value) {
                const option = this.options[value];
                if (option) {
                    const selectElement = document.querySelector('#film_id');
                    const selectedOption = selectElement.querySelector(`option[value="${value}"]`);
                    if (selectedOption) {
                        const durasi = selectedOption.dataset.durasi;
                        @this.set('film_duration', parseInt(durasi) || 0);
                    }
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
        onChange: function(value) {
            // Sinkronisasi dengan Livewire
            @this.set('studio_id', value);
        }
    });
});
</script>
@endpush