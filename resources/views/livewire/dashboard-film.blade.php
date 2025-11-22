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
                                                        }}">{{ $film->rating }}</span>
                                            </div>
                                        </div>
                                    </div>
                        @endforeach

                        @if($hasMoreTayang)
                            <div wire:click="loadMoreTayang"
                                 class="flex-none w-40 sm:w-48 cursor-pointer bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg shadow-lg snap-start flex items-center justify-center hover:scale-105 transition aspect-[2/3]">
                                <div class="text-center text-white p-4">
                                    <i class="fa-solid fa-plus text-5xl mb-2"></i>
                                    <p class="font-bold text-sm">Lihat Lebih</p>
                                    <p class="text-xs opacity-90 mt-1">{{ $totalTayang - $sedangTayang->count() }} lagi</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Scroll Buttons -->
                    <button onclick="document.getElementById('tayang-container').scrollBy({left: -400, behavior: 'smooth'})"
                            class="hidden lg:flex absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 bg-white/90 hover:bg-white shadow-xl rounded-full w-12 h-12 items-center justify-center opacity-0 group-hover:opacity-100 transition z-10">
                        <i class="fa-solid fa-chevron-left text-xl"></i>
                    </button>
                    <button onclick="document.getElementById('tayang-container').scrollBy({left: 400, behavior: 'smooth'})"
                            class="hidden lg:flex absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 bg-white/90 hover:bg-white shadow-xl rounded-full w-12 h-12 items-center justify-center opacity-0 group-hover:opacity-100 transition z-10">
                        <i class="fa-solid fa-chevron-right text-xl"></i>
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
                                                        }}">{{ $film->rating }}</span>
                                            </div>
                                        </div>
                                    </div>
                        @endforeach

                        @if($hasMoreSegera)
                            <div wire:click="loadMoreSegera"
                                 class="flex-none w-40 sm:w-48 cursor-pointer bg-gradient-to-br from-purple-500 to-purple-700 rounded-lg shadow-lg snap-start flex items-center justify-center hover:scale-105 transition aspect-[2/3]">
                                <div class="text-center text-white p-4">
                                    <i class="fa-solid fa-plus text-5xl mb-2"></i>
                                    <p class="font-bold text-sm">Lihat Lebih</p>
                                    <p class="text-xs opacity-90 mt-1">{{ $totalSegera - $segeraTayang->count() }} lagi</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <button onclick="document.getElementById('segera-container').scrollBy({left: -400, behavior: 'smooth'})"
                            class="hidden lg:flex absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 bg-white/90 hover:bg-white shadow-xl rounded-full w-12 h-12 items-center justify-center opacity-0 group-hover:opacity-100 transition z-10">
                        <i class="fa-solid fa-chevron-left text-xl"></i>
                    </button>
                    <button onclick="document.getElementById('segera-container').scrollBy({left: 400, behavior: 'smooth'})"
                            class="hidden lg:flex absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 bg-white/90 hover:bg-white shadow-xl rounded-full w-12 h-12 items-center justify-center opacity-0 group-hover:opacity-100 transition z-10">
                        <i class="fa-solid fa-chevron-right text-xl"></i>
                    </button>
                </div>
            @else
                <p class="text-gray-500 text-center py-12">Belum ada film yang akan segera tayang.</p>
            @endif
        </section>
    </div>

    @if($selectedFilm)
        <div class="fixed inset-0 bg-black/80 flex items-end lg:items-center justify-center z-50 p-0 lg:p-4 overflow-y-auto"
             wire:click="closeModal">

            <div class="lg:hidden absolute inset-0"
                 wire:click="closeModal"></div>

            <div class="bg-white rounded-t-3xl lg:rounded-2xl w-full max-w-5xl max-h-[92vh] lg:max-h-[90vh] overflow-y-auto relative flex flex-col"
                 wire:click.stop
                 x-data="{ dragged: false }"
                 @touchstart="dragged = false"
                 @touchmove="dragged = true"
                 @touchend="if(!dragged) $event.target.closest('[wire\\:click]').click()">

                <!-- Drag handle mobile -->
                <div class="lg:hidden sticky top-0 z-10 flex justify-center pt-4 pb-2 bg-white">
                    <div class="w-12 h-1.5 bg-gray-300 rounded-full"></div>
                </div>

                <div class="px-5 lg:px-8 pt-2 lg:pt-6 pb-8 lg:pb-10 flex-1">
                    <div class="flex justify-between items-start mb-5 lg:mb-8">
                        <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 leading-tight">
                            {{ $selectedFilm->judul }}
                        </h2>
                        <button wire:click="closeModal"
                                class="text-gray-500 hover:text-gray-800 transition shrink-0 ml-4">
                            <i class="fa-solid fa-xmark text-3xl lg:text-4xl"></i>
                        </button>
                    </div>

                    <div class="grid lg:grid-cols-3 gap-6 lg:gap-8">
                        <!-- Poster - diperkecil di mobile -->
                        <div class="lg:col-span-1 flex justify-center">
                            <img src="{{ $selectedFilm->poster ? asset('storage/' . $selectedFilm->poster) : asset('storage/default_poster.png') }}"
                                 alt="{{ $selectedFilm->judul }}"
                                 class="w-full max-w-[180px] lg:max-w-none rounded-xl lg:rounded-2xl shadow-2xl aspect-[2/3] object-cover">
                        </div>

                        <!-- Info -->
                        <div class="lg:col-span-2 space-y-6 lg:space-y-7">
                            <div class="grid grid-cols-2 gap-4 text-sm lg:text-base">
                                <div>
                                    <p class="text-gray-500 text-xs lg:text-sm">Durasi</p>
                                    <p class="font-semibold text-gray-900">{{ $selectedFilm->durasi }} menit</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs lg:text-sm">Tahun Rilis</p>
                                    <p class="font-semibold text-gray-900">{{ $selectedFilm->tahun_rilis }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs lg:text-sm">Rating Usia</p>
                                    <span
                                          class="inline-block mt-1 px-3 py-1.5 rounded-full text-xs lg:text-sm font-bold
                                            {{ $selectedFilm->rating === 'SU' ? 'bg-green-100 text-green-800' :
            ($selectedFilm->rating === 'R13+' ? 'bg-yellow-100 text-yellow-800' :
                ($selectedFilm->rating === 'D17+' ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800')) }}">
                                        {{ $selectedFilm->rating }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs lg:text-sm">Sutradara</p>
                                    <p class="font-semibold text-gray-900">
                                        {{ $selectedFilm->sutradara?->nama_sutradara ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <div>
                                <p class="text-gray-500 text-xs lg:text-sm mb-2">Genre</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($selectedFilm->genres as $genre)
                                        <span class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-full text-xs lg:text-sm">
                                            {{ $genre->nama_genre }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <p class="text-gray-500 text-xs lg:text-sm mb-3">Sinopsis</p>
                                <p class="text-gray-700 leading-relaxed text-sm lg:text-base">
                                    {{ $selectedFilm->sinopsis ?? 'Tidak ada sinopsis.' }}
                                </p>
                            </div>

                            <!-- Action Buttons -->
                            <div
                                 class="pt-6 lg:pt-8 border-t border-gray-200 flex flex-col sm:flex-row gap-3 lg:gap-4 
                                                fixed lg:static bottom-0 left-0 right-0 bg-white p-4 lg:p-0 border-t lg:border-0 z-20">
                                <button wire:click="closeModal"
                                        class="w-full sm:w-auto px-6 py-3.5 border border-gray-300 rounded-xl hover:bg-gray-50 font-medium transition">
                                    Tutup
                                </button>
                                <a href="{{ route('pemesanan.show', $selectedFilm->id) }}"
                                   class="w-full sm:w-auto px-8 py-3.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 
                                              flex items-center justify-center gap-3 font-medium transition shadow-lg shadow-blue-600/30">
                                    <i class="fa-solid fa-ticket-simple"></i>
                                    Pesan Tiket
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('livewire:init', () => {
                let startY = 0;
                const modal = document.querySelector('[wire\\:click="closeModal"] > div');

                if (modal && window.innerWidth < 1024) {
                    modal.addEventListener('touchstart', e => startY = e.touches[0].clientY);
                    modal.addEventListener('touchmove', e => {
                        const currentY = e.touches[0].clientY;
                        if (currentY > startY + 50) {
                            document.body.style.overflow = '';
                            Livewire.emit('close-modal');
                        }
                    });
                }
            });
        </script>
    @endif

    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('open-modal', () => document.body.style.overflow = 'hidden');
            Livewire.on('close-modal', () => document.body.style.overflow = '');
        });
    </script>
</div>