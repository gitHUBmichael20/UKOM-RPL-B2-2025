@extends('layout.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
        <div class="max-w-2xl mx-auto px-4">
            <!-- Ticket -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border-4 border-blue-200">
                <!-- Ticket Header -->
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6 text-white">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-2xl font-bold">Movie Ticket</h1>
                            <p class="text-blue-100">Booking ID: {{ $pemesanan->kode_booking }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-blue-200">Status</div>
                            <div
                                class="text-lg font-bold {{ $pemesanan->status_pembayaran === 'lunas' ? 'text-green-300' : 'text-yellow-300' }}">
                                {{ strtoupper($pemesanan->status_pembayaran) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Movie Info -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center space-x-4">
                        <img src="{{ $pemesanan->jadwalTayang->film->poster ?? 'storage/default_poster.png' }}"
                            alt="{{ $pemesanan->jadwalTayang->film->judul }}" class="w-20 h-28 object-cover rounded-lg">
                        <div class="flex-1">
                            <h2 class="text-xl font-bold text-gray-900">{{ $pemesanan->jadwalTayang->film->judul }}</h2>
                            <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    {{ $pemesanan->jadwalTayang->film->rating }}
                                </span>
                                <span>{{ $pemesanan->jadwalTayang->film->durasi }} min</span>
                                <span>{{ $pemesanan->jadwalTayang->film->tahun_rilis }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Show Details -->
                <div class="p-6 border-b border-gray-200">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Show Details</h3>
                            <div class="mt-2 space-y-1">
                                <p class="text-lg font-bold text-gray-900">
                                    {{ \Carbon\Carbon::parse($pemesanan->jadwalTayang->tanggal_tayang)->format('l, F j, Y') }}
                                </p>
                                <p class="text-xl font-bold text-blue-600">
                                    {{ \Carbon\Carbon::parse($pemesanan->jadwalTayang->jam_tayang)->format('g:i A') }}
                                </p>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Theater</h3>
                            <div class="mt-2">
                                <p class="text-lg font-bold text-gray-900">
                                    {{ $pemesanan->jadwalTayang->studio->nama_studio }}</p>
                                <p class="text-sm text-gray-600 capitalize">
                                    {{ $pemesanan->jadwalTayang->studio->tipe_studio }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Seats -->
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Seats</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($pemesanan->detailPemesanan as $detail)
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-sm font-medium">
                                {{ $detail->kursi->nomor_kursi }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Customer Information</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Name</p>
                            <p class="font-semibold text-gray-900">{{ $pemesanan->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="font-semibold text-gray-900">{{ $pemesanan->user->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-600">Total Paid</p>
                            <p class="text-2xl font-bold text-gray-900">Rp
                                {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Booking Date</p>
                            <p class="font-semibold text-gray-900">
                                {{ \Carbon\Carbon::parse($pemesanan->tanggal_pemesanan)->format('M j, Y g:i A') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex justify-center space-x-4">
                <a href="{{ route('pemesanan.my-bookings') }}"
                    class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">
                    Back to My Bookings
                </a>
                <button onclick="window.print()"
                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Ticket
                </button>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .bg-white,
            .bg-white * {
                visibility: visible;
            }

            .bg-white {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                box-shadow: none !important;
                border: 2px solid #000 !important;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
@endsection
