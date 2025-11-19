<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Select Screening Time') }}
        </h2>
    </x-slot>

    <!-- Progress Steps -->
    <div class="bg-white rounded-lg shadow-sm p-6 mt-8 mb-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center font-semibold">
                    ✓
                </div>
                <span class="font-medium text-green-600">Current Movie</span>
            </div>
            <div class="h-1 w-12 bg-green-500"></div>
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold">
                    2
                </div>
                <span class="font-medium text-blue-600">Showing Time</span>
            </div>
            <div class="h-1 w-12 bg-gray-300"></div>
            <div class="flex items-center space-x-2">
                <div
                    class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-semibold">
                    3
                </div>
                <span class="font-medium text-gray-500">Seat Section & Payment</span>
            </div>
            <div class="h-1 w-12 bg-gray-300"></div>
            <div class="flex items-center space-x-2">
                <div
                    class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-semibold">
                    4
                </div>
                <span class="font-medium text-gray-500">Your Ticket</span>
            </div>
        </div>
    </div>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Movie Info -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <div class="flex gap-6">
                    <img src="{{ $film->poster }}" alt="{{ $film->judul }}" class="w-32 rounded-lg">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $film->judul }}</h1>
                        <p class="text-gray-600 mt-2">{{ $film->genres->pluck('nama_genre')->implode(', ') }} •
                            {{ $film->durasi }} min • {{ $film->rating }}</p>
                        <p class="text-gray-700 mt-3">{{ Str::limit($film->sinopsis, 200) }}</p>
                    </div>
                </div>
            </div>

            <!-- Schedule Selection -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Available Screenings</h2>

                @foreach ($jadwalTayang as $date => $schedules)
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}
                        </h3>

                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach ($schedules as $schedule)
                                <a href="{{ route('pemesanan.seats', ['film' => $film->id, 'jadwalTayang' => $schedule->id]) }}"
                                    class="border-2 border-gray-200 rounded-lg p-4 hover:border-blue-500 hover:bg-blue-50 transition cursor-pointer">
                                    <div class="text-center">
                                        <div class="text-lg font-bold text-gray-900">
                                            {{ \Carbon\Carbon::parse($schedule->jam_tayang)->format('g:i A') }}
                                        </div>
                                        <div class="text-sm text-gray-600 mt-1">
                                            {{ $schedule->studio->nama_studio }}
                                        </div>
                                        <div class="text-xs text-blue-600 font-medium mt-1">
                                            {{ strtoupper($schedule->studio->tipe_studio) }}
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                @if ($jadwalTayang->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-gray-500 text-lg">No available screenings for this movie.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
