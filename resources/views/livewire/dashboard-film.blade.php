<div>
    <!-- Header -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Film Tersedia</h1>
                <p class="text-gray-600 mt-1">Pilih film favoritmu dan pesan tiket sekarang!</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <input type="text"
                       wire:model.live.debounce.500ms="search"
                       placeholder="Cari film atau sutradara..."
                       class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />

                <div class="relative">
                    <select wire:model.live="sortBy" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="terbaru">📅 Terbaru</option>
                        <option value="terlama">📅 Terlama</option>
                        <option value="judul-az">🔤 Judul A-Z</option>
                        <option value="judul-za">🔤 Judul Z-A</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Sedang Tayang -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                <span class="w-2 h-8 bg-blue-600 rounded"></span>
                Sedang Tayang
            </h2>
            @if($sedangTayang->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
                    @foreach($sedangTayang as $film)
                            <div wire:click="openModal({{ $film->id }})"
                                 class="group cursor-pointer transform hover:-translate-y-2 transition-all duration-300">
                                <div class="relative overflow-hidden rounded-lg shadow-lg">
                                    <img src="{{ $film->poster ? asset('storage/' . $film->poster) : asset('images/posters/placeholder.jpg') }}"
                                         alt="{{ $film->judul }}"
                                         class="w-full aspect-[2/3] object-cover group-hover:scale-110 transition duration-500">
                                    <div
                                         class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition">
                                    </div>
                                    <div
                                         class="absolute bottom-0 left-0 right-0 p-4 text-white transform translate-y-4 group-hover:translate-y-0 transition">
                                        <h3 class="font-bold text-sm line-clamp-1">{{ $film->judul }}</h3>
                                        <p class="text-xs opacity-90">{{ $film->tahun_rilis }}</p>
                                    </div>
                                </div>
                                <div class="mt-3 text-center">
                                    <span
                                          class="inline-block px-3 py-1 text-xs font-medium rounded-full
                                                            {{ $film->rating === 'SU' ? 'bg-green-100 text-green-800' :
                        ($film->rating === 'R13+' ? 'bg-yellow-100 text-yellow-800' :
                            ($film->rating === 'R17+' ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800')) }}">
                                        {{ $film->rating }}
                                    </span>
                                </div>
                            </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $sedangTayang->withQueryString()->links() }}
                </div>
            @else
                <p class="text-gray-500 text-center py-12">Tidak ada film yang sedang tayang saat ini.</p>
            @endif
        </section>

        <!-- Segera Tayang -->
        <section>
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                <span class="w-2 h-8 bg-purple-600 rounded"></span>
                Segera Tayang
            </h2>
            @if($segeraTayang->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
                    @foreach($segeraTayang as $film)
                        <div wire:click="openModal({{ $film->id }})"
                             class="group cursor-pointer transform hover:-translate-y-2 transition-all duration-300 opacity-90">
                            <div class="relative overflow-hidden rounded-lg shadow-lg">
                                <img src="{{ $film->poster ? asset('storage/' . $film->poster) : asset('images/posters/placeholder.jpg') }}"
                                     alt="{{ $film->judul }}"
                                     class="w-full aspect-[2/3] object-cover group-hover:scale-110 transition duration-500 brightness-75">
                                <div class="absolute inset-0 bg-black/40"></div>
                                <div class="absolute top-4 left-4">
                                    <span class="bg-purple-600 text-white px-3 py-1 rounded-full text-xs font-bold">Coming
                                        Soon</span>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h3 class="font-semibold text-gray-800 line-clamp-1">{{ $film->judul }}</h3>
                                <p class="text-xs text-gray-500">{{ $film->tahun_rilis }} • {{ $film->durasi }} menit</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $segeraTayang->withQueryString()->links() }}
                </div>
            @else
                <p class="text-gray-500 text-center py-12">Belum ada film yang akan segera tayang.</p>
            @endif
        </section>
    </div>

    <!-- Modal Detail Film -->
    @if($selectedFilm)
        <div class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 p-4"
             wire:click="closeModal">
            <div class="bg-white rounded-2xl max-w-5xl w-full max-h-[90vh] overflow-y-auto"
                 wire:click.stop>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <h2 class="text-3xl font-bold text-gray-900">{{ $selectedFilm->judul }}</h2>
                        <button wire:click="closeModal"
                                class="text-gray-500 hover:text-gray-700">
                            <svg class="w-8 h-8"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="grid lg:grid-cols-3 gap-8">
                        <div>
                            <img src="{{ $selectedFilm->poster ? asset('storage/' . $selectedFilm->poster) : asset('images/posters/placeholder.jpg') }}"
                                 alt="{{ $selectedFilm->judul }}"
                                 class="w-full rounded-xl shadow-xl aspect-[2/3] object-cover">
                        </div>

                        <div class="lg:col-span-2 space-y-6">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-500">Durasi</p>
                                    <p class="font-semibold">{{ $selectedFilm->durasi }} menit</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Tahun Rilis</p>
                                    <p class="font-semibold">{{ $selectedFilm->tahun_rilis }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Rating Usia</p>
                                    <span class="inline-block px-3 py-1 rounded-full text-sm font-bold
                                                {{ $selectedFilm->rating === 'SU' ? 'bg-green-100 text-green-800' :
            ($selectedFilm->rating === 'R13+' ? 'bg-yellow-100 text-yellow-800' :
                ($selectedFilm->rating === 'R17+' ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800')) }}">
                                        {{ $selectedFilm->rating }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-gray-500">Sutradara</p>
                                    <p class="font-semibold">{{ $selectedFilm->sutradara?->nama_sutradara ?? '-' }}</p>
                                </div>
                            </div>

                            <div>
                                <p class="text-gray-500 text-sm">Genre</p>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @foreach($selectedFilm->genres as $genre)
                                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">
                                            {{ $genre->nama_genre }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <p class="text-gray-500 text-sm mb-2">Sinopsis</p>
                                <p class="text-gray-700 leading-relaxed">
                                    {{ $selectedFilm->sinopsis ?? 'Tidak ada sinopsis.' }}
                                </p>
                            </div>

                            <div class="pt-6 border-t flex justify-end gap-4">
                                <button wire:click="closeModal"
                                        class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50">
                                    Tutup
                                </button>
                                <a href="{{ route('pemesanan.show', $selectedFilm->id) }}"
                                   class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-3 font-medium">
                                    <svg class="w-5 h-5"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                                        </path>
                                    </svg>
                                    Pesan Tiket
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Listen to open modal -->
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('open-modal', () => {
                document.body.style.overflow = 'hidden';
            });
        });
    </script>
</div>