<x-app-layout>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-8 px-4 sm:px-0">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">My Bookings</h1>
                        <p class="text-gray-600 mt-1">Manage and view your movie bookings</p>
                    </div>
                </div>
            </div>

            <!-- Bookings Content -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($bookings->isEmpty())
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <div
                                class="mx-auto w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No bookings yet</h3>
                            <p class="text-gray-500 mb-6">Start by booking your first movie experience!</p>
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                                    </path>
                                </svg>
                                Browse Movies
                            </a>
                        </div>
                    @else
                        <!-- Bookings List -->
                        <div class="space-y-4">
                            @foreach ($bookings as $booking)
                                <div
                                    class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-all duration-300">
                                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                        <!-- Booking Info -->
                                        <div class="flex-1">
                                            <div
                                                class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-3 mb-3">
                                                        <h3 class="text-xl font-bold text-gray-900">
                                                            {{ $booking->jadwalTayang->film->judul ?? 'Movie Title' }}
                                                        </h3>
                                                        <span
                                                            class="px-3 py-1 text-sm font-medium rounded-full 
                                                    @if ($booking->status_pembayaran === 'paid') bg-green-100 text-green-800
                                                    @elseif($booking->status_pembayaran === 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($booking->status_pembayaran === 'failed') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                            {{ ucfirst($booking->status_pembayaran) }}
                                                        </span>
                                                    </div>

                                                    <!-- Booking Details Grid -->
                                                    <div
                                                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-sm text-gray-600">
                                                        <div class="flex items-center gap-2">
                                                            <svg class="w-4 h-4 text-gray-400" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                </path>
                                                            </svg>
                                                            <span>{{ $booking->tanggal_pemesanan->format('d M Y') }}</span>
                                                        </div>

                                                        <div class="flex items-center gap-2">
                                                            <svg class="w-4 h-4 text-gray-400" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                </path>
                                                            </svg>
                                                            <span>{{ $booking->jadwalTayang->waktu_tayang ?? '--:--' }}</span>
                                                        </div>

                                                        <div class="flex items-center gap-2">
                                                            <svg class="w-4 h-4 text-gray-400" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                                </path>
                                                            </svg>
                                                            <span>{{ $booking->jumlah_tiket }} tickets</span>
                                                        </div>

                                                        <div class="flex items-center gap-2">
                                                            <svg class="w-4 h-4 text-gray-400" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                                                </path>
                                                            </svg>
                                                            <span class="font-semibold text-gray-900">Rp
                                                                {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                                                        </div>
                                                    </div>

                                                    <!-- Additional Info -->
                                                    <div
                                                        class="mt-4 flex flex-wrap items-center gap-4 text-sm text-gray-500">
                                                        <span
                                                            class="font-mono bg-gray-100 px-3 py-1 rounded-md text-xs">
                                                            #{{ $booking->kode_booking }}
                                                        </span>
                                                        <span class="flex items-center gap-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                                                                </path>
                                                            </svg>
                                                            Seats:
                                                            @if ($booking->detailPemesanan && $booking->detailPemesanan->count() > 0)
                                                                {{ $booking->detailPemesanan->pluck('kursi')->join(', ') }}
                                                            @else
                                                                Not assigned
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="flex flex-col sm:flex-row gap-3">
                                            @if ($booking->status_pembayaran === 'paid')
                                                <a href="{{ route('pemesanan.view-ticket', $booking->id) }}"
                                                    class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition shadow-sm">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                        </path>
                                                    </svg>
                                                    View Ticket
                                                </a>
                                            @elseif($booking->status_pembayaran === 'pending')
                                                <a href="#"
                                                    class="inline-flex items-center justify-center px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition shadow-sm">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                                        </path>
                                                    </svg>
                                                    Complete Payment
                                                </a>
                                            @endif

                                            <a href="{{ url('/pemesanan/ticket/' . $booking->id) }}"
                                                class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                    </path>
                                                </svg>
                                                Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Stats Section -->
                        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div
                                class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                                <div class="flex items-center">
                                    <div class="p-3 bg-blue-500 rounded-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-600">Total Bookings</p>
                                        <p class="text-2xl font-bold text-gray-900">{{ $bookings->count() }}</p>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
                                <div class="flex items-center">
                                    <div class="p-3 bg-green-500 rounded-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-600">Completed</p>
                                        <p class="text-2xl font-bold text-gray-900">
                                            {{ $bookings->where('status_pembayaran', 'paid')->count() }}</p>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-6 border border-yellow-200">
                                <div class="flex items-center">
                                    <div class="p-3 bg-yellow-500 rounded-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-600">Pending</p>
                                        <p class="text-2xl font-bold text-gray-900">
                                            {{ $bookings->where('status_pembayaran', 'pending')->count() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .booking-card {
            transition: all 0.3s ease;
        }

        .booking-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</x-app-layout>
