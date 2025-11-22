<div class="pb-20 lg:pb-0">
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
                    <select wire:model.live="sortBy"
                            class="px-4 pe-8 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="terbaru">Terbaru</option>
                        <option value="terlama">Terlama</option>
                        <option value="judul-az">Judul A-Z</option>
                        <option value="judul-za">Judul Z-A</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Sedang Tayang -->
        <section class="mb-12">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <span class="w-2 h-8 bg-blue-600 rounded"></span>
                    Sedang Tayang
                </h2>
                <span class="text-sm text-gray-600">
                    {{ $totalTayang }} film
                </span>
            </div>

            @if($sedangTayang->count() > 0)
                <div class="relative group">
                    <!-- Scroll Container -->
                    <div class="flex gap-4 overflow-x-auto pb-4 scrollbar-hide snap-x snap-mandatory scroll-smooth"
                         id="tayang-container">
                        @foreach($sedangTayang as $film)
                                    <div wire:click="openModal({{ $film->id }})"
                                         class="flex-none w-40 sm:w-48 cursor-pointer relative overflow-hidden rounded-lg shadow-lg snap-start group/card">

                                        <img src="{{ $film->poster ? asset('storage/' . $film->poster) : asset('storage/default_poster.png') }}"
                                             alt="{{ $film->judul }}"
                                             class="w-full aspect-[2/3] object-cover group-hover/card:scale-110 transition duration-500">

                                        <div
                                             class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent opacity-0 group-hover/card:opacity-100 transition-opacity duration-300 p-4 flex flex-col justify-end">
                                            <h3 class="font-bold text-white text-sm line-clamp-2">{{ $film->judul }}</h3>
                                            <div class="flex justify-between items-center mt-2">
                                                <span class="text-xs text-white/90">{{ $film->durasi }}m</span>
                                                <span class="px-2 py-0.5 text-xs font-bold rounded-full {{
                            $film->rating === 'SU' ? 'bg-green-500 text-white' :
                            ($film->rating === 'R13+' ? 'bg-yellow-500 text-white' :
                                ($film->rating === 'D17+' ? 'bg-orange-500 text-white' : 'bg-red-500 text-white'))
                                                        }}">
                                                    {{ $film->rating }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                        @endforeach

                        <!-- Load More Card -->
                        @if($hasMoreTayang)
                            <div wire:click="loadMoreTayang"
                                 class="flex-none w-40 sm:w-48 cursor-pointer bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg shadow-lg snap-start flex items-center justify-center hover:scale-105 transition aspect-[2/3]">
                                <div class="text-center text-white p-4">
                                    <svg class="w-12 h-12 mx-auto mb-2"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <p class="font-bold text-sm">Lihat Lebih</p>
                                    <p class="text-xs opacity-90 mt-1">{{ $totalTayang - $sedangTayang->count() }} lagi</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Scroll Buttons -->
                    <button onclick="document.getElementById('tayang-container').scrollBy({left: -400, behavior: 'smooth'})"
                            class="hidden lg:flex absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 bg-white/90 hover:bg-white shadow-xl rounded-full w-12 h-12 items-center justify-center opacity-0 group-hover:opacity-100 transition z-10">
                        <svg class="w-6 h-6"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button onclick="document.getElementById('tayang-container').scrollBy({left: 400, behavior: 'smooth'})"
                            class="hidden lg:flex absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 bg-white/90 hover:bg-white shadow-xl rounded-full w-12 h-12 items-center justify-center opacity-0 group-hover:opacity-100 transition z-10">
                        <svg class="w-6 h-6"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            @else
                <p class="text-gray-500 text-center py-12">Tidak ada film yang sedang tayang saat ini.</p>
            @endif
        </section>

        <!-- Segera Tayang -->
        <section>
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <span class="w-2 h-8 bg-purple-600 rounded"></span>
                    Segera Tayang
                </h2>
                <span class="text-sm text-gray-600">
                    {{ $totalSegera }} film
                </span>
            </div>

            @if($segeraTayang->count() > 0)
                <div class="relative group">
                    <!-- Scroll Container -->
                    <div class="flex gap-4 overflow-x-auto pb-4 scrollbar-hide snap-x snap-mandatory scroll-smooth"
                         id="segera-container">
                        @foreach($segeraTayang as $film)
                                    <div wire:click="openModal({{ $film->id }})"
                                         class="flex-none w-40 sm:w-48 cursor-pointer relative overflow-hidden rounded-lg shadow-lg snap-start group/card">

                                        <img src="{{ $film->poster ? asset('storage/' . $film->poster) : asset('storage/default_poster.png') }}"
                                             alt="{{ $film->judul }}"
                                             class="w-full aspect-[2/3] object-cover brightness-90 group-hover/card:scale-110 transition duration-500">

                                        <div class="absolute top-3 left-3 z-10">
                                            <span class="bg-purple-600 text-white px-2 py-1 rounded-full text-xs font-bold">Coming
                                                Soon</span>
                                        </div>

                                        <div
                                             class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent opacity-0 group-hover/card:opacity-100 transition-opacity duration-300 p-4 flex flex-col justify-end">
                                            <h3 class="font-bold text-white text-sm line-clamp-2">{{ $film->judul }}</h3>
                                            <div class="flex justify-between items-center mt-2">
                                                <span class="text-xs text-white/90">{{ $film->durasi }}m</span>
                                                <span class="px-2 py-0.5 text-xs font-bold rounded-full {{
                            $film->rating === 'SU' ? 'bg-green-500 text-white' :
                            ($film->rating === 'R13+' ? 'bg-yellow-500 text-white' :
                                ($film->rating === 'D17+' ? 'bg-orange-500 text-white' : 'bg-red-500 text-white'))
                                                        }}">
                                                    {{ $film->rating }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                        @endforeach

                        <!-- Load More Card -->
                        @if($hasMoreSegera)
                            <div wire:click="loadMoreSegera"
                                 class="flex-none w-40 sm:w-48 cursor-pointer bg-gradient-to-br from-purple-500 to-purple-700 rounded-lg shadow-lg snap-start flex items-center justify-center hover:scale-105 transition aspect-[2/3]">
                                <div class="text-center text-white p-4">
                                    <svg class="w-12 h-12 mx-auto mb-2"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <p class="font-bold text-sm">Lihat Lebih</p>
                                    <p class="text-xs opacity-90 mt-1">{{ $totalSegera - $segeraTayang->count() }} lagi</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Scroll Buttons -->
                    <button onclick="document.getElementById('segera-container').scrollBy({left: -400, behavior: 'smooth'})"
                            class="hidden lg:flex absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 bg-white/90 hover:bg-white shadow-xl rounded-full w-12 h-12 items-center justify-center opacity-0 group-hover:opacity-100 transition z-10">
                        <svg class="w-6 h-6"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button onclick="document.getElementById('segera-container').scrollBy({left: 400, behavior: 'smooth'})"
                            class="hidden lg:flex absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 bg-white/90 hover:bg-white shadow-xl rounded-full w-12 h-12 items-center justify-center opacity-0 group-hover:opacity-100 transition z-10">
                        <svg class="w-6 h-6"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
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
                            <img src="{{ $selectedFilm->poster ? asset('storage/' . $selectedFilm->poster) : asset('storage/default_poster.png') }}"
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
                                    <span
                                          class="inline-block px-3 py-1 rounded-full text-sm font-bold
                                            {{ $selectedFilm->rating === 'SU' ? 'bg-green-100 text-green-800' :
            ($selectedFilm->rating === 'R13+' ? 'bg-yellow-100 text-yellow-800' :
                ($selectedFilm->rating === 'D17+' ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800')) }}">
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

    <!-- CSS untuk scrollbar -->
    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    <!-- Listen to open modal -->
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('open-modal', () => {
                document.body.style.overflow = 'hidden';
            });

            Livewire.on('close-modal', () => {
                document.body.style.overflow = '';
            });
        });
    </script>
</div>