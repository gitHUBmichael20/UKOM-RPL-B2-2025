<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Select Screening Time') }}
        </h2>
    </x-slot>

    <!-- Progress Steps - Responsive -->
    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 mt-4 sm:mt-8 mb-4 sm:mb-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Desktop View -->
        <div class="hidden md:flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center font-semibold text-sm">
                    ✓
                </div>
                <span class="font-medium text-green-600 text-sm lg:text-base">Current Movie</span>
            </div>
            <div class="h-1 w-8 lg:w-12 bg-green-500"></div>
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">
                    2
                </div>
                <span class="font-medium text-blue-600 text-sm lg:text-base">Showing Time</span>
            </div>
            <div class="h-1 w-8 lg:w-12 bg-gray-300"></div>
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-semibold text-sm">
                    3
                </div>
                <span class="font-medium text-gray-500 text-sm lg:text-base">Seat & Payment</span>
            </div>
            <div class="h-1 w-8 lg:w-12 bg-gray-300"></div>
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-semibold text-sm">
                    4
                </div>
                <span class="font-medium text-gray-500 text-sm lg:text-base">Your Ticket</span>
            </div>
        </div>

        <!-- Mobile View -->
        <div class="flex md:hidden justify-between items-center">
            <div class="flex flex-col items-center flex-1">
                <div class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center font-semibold text-xs">
                    ✓
                </div>
                <span class="font-medium text-green-600 text-xs mt-1 text-center">Movie</span>
            </div>
            <div class="h-0.5 w-6 bg-green-500 -mt-4"></div>
            <div class="flex flex-col items-center flex-1">
                <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-xs">
                    2
                </div>
                <span class="font-medium text-blue-600 text-xs mt-1 text-center">Time</span>
            </div>
            <div class="h-0.5 w-6 bg-gray-300 -mt-4"></div>
            <div class="flex flex-col items-center flex-1">
                <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-semibold text-xs">
                    3
                </div>
                <span class="font-medium text-gray-500 text-xs mt-1 text-center">Seat</span>
            </div>
            <div class="h-0.5 w-6 bg-gray-300 -mt-4"></div>
            <div class="flex flex-col items-center flex-1">
                <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-semibold text-xs">
                    4
                </div>
                <span class="font-medium text-gray-500 text-xs mt-1 text-center">Ticket</span>
            </div>
        </div>
    </div>

    <div class="py-4 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Movie Info - Responsive -->
            <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 mb-4 sm:mb-8">
                <div class="flex flex-col sm:flex-row gap-4 sm:gap-6">
                    <img src="{{ $film->poster ? asset($film->poster) : asset('storage/default_poster.png') }}" 
                         alt="{{ $film->judul }}" 
                         class="w-24 sm:w-32 rounded-lg mx-auto sm:mx-0">
                    <div class="flex-1">
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 text-center sm:text-left">
                            {{ $film->judul }}
                        </h1>
                        <p class="text-sm sm:text-base text-gray-600 mt-2 text-center sm:text-left">
                            {{ $film->genres->pluck('nama_genre')->implode(', ') }} • {{ $film->durasi }} min • {{ $film->rating }}
                        </p>
                        <p class="text-sm sm:text-base text-gray-700 mt-3 text-center sm:text-left">
                            {{ Str::limit($film->sinopsis, 200) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Schedule Selection - Responsive -->
            <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
                <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Available Screenings</h2>

                @foreach ($jadwalTayang as $date => $schedules)
                    <div class="mb-6 sm:mb-8">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4">
                            {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}
                        </h3>

                        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-2 sm:gap-3">
                            @foreach ($schedules as $schedule)
                                @php
                                    $canBook = $schedule->can_book;
                                    $isPast = $schedule->is_past;
                                @endphp

                                @if($canBook)
                                    <a href="{{ route('pemesanan.seats', ['film' => $film->id, 'jadwalTayang' => $schedule->id]) }}"
                                    class="border-2 border-gray-200 rounded-lg p-2 sm:p-3 hover:border-blue-500 hover:bg-blue-50 transition cursor-pointer active:scale-95">
                                        <div class="text-center">
                                            <div class="text-sm sm:text-base font-bold text-gray-900">
                                                {{ \Carbon\Carbon::parse($schedule->jam_tayang)->format('g:i A') }}
                                            </div>
                                            <div class="text-xs text-gray-600 mt-0.5 sm:mt-1">
                                                Rp {{ number_format($schedule->harga_tiket, 0, ',', '.') }}
                                            </div>
                                            <div class="text-xs text-blue-600 font-medium mt-0.5">
                                                {{ strtoupper($schedule->studio->tipe_studio) }}
                                            </div>
                                        </div>
                                    </a>
                                @else
                                    <div class="border-2 border-gray-200 rounded-lg p-2 sm:p-3 bg-gray-50 opacity-60 relative cursor-not-allowed">
                                        <div class="text-center">
                                            <div class="text-sm sm:text-base font-bold text-gray-500 line-through">
                                                {{ \Carbon\Carbon::parse($schedule->jam_tayang)->format('g:i A') }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-0.5 sm:mt-1">
                                                Rp {{ number_format($schedule->harga_tiket, 0, ',', '.') }}
                                            </div>
                                            <div class="text-xs text-gray-400 font-medium mt-0.5">
                                                {{ strtoupper($schedule->studio->tipe_studio) }}
                                            </div>
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <span class="bg-red-500 text-white px-1.5 sm:px-2 py-0.5 sm:py-1 rounded text-xs font-bold">
                                                    @if($isPast && !$canBook)
                                                        SEDANG TAYANG
                                                    @else
                                                        BOOKING DITUTUP
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach

                @if ($jadwalTayang->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-gray-500 text-base sm:text-lg">No available screenings for this movie.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>