<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Your Ticket') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Progress Steps -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-2">
                        <div
                            class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center font-semibold">
                            ✓
                        </div>
                        <span class="font-medium text-green-600">Movie & Time</span>
                    </div>
                    <div class="h-1 w-12 bg-green-500"></div>
                    <div class="flex items-center space-x-2">
                        <div
                            class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center font-semibold">
                            ✓
                        </div>
                        <span class="font-medium text-green-600">Seat Selection</span>
                    </div>
                    <div class="h-1 w-12 bg-green-500"></div>
                    <div class="flex items-center space-x-2">
                        <div
                            class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center font-semibold">
                            ✓
                        </div>
                        <span class="font-medium text-green-600">Payment</span>
                    </div>
                    <div class="h-1 w-12 bg-green-500"></div>
                    <div class="flex items-center space-x-2">
                        <div
                            class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center font-semibold">
                            ✓
                        </div>
                        <span class="font-medium text-green-600">Your Ticket</span>
                    </div>
                </div>
            </div>

            <!-- Offline Badge -->
            @if ($pemesanan->jenis_pemesanan === 'offline')
                <div class="bg-orange-50 border-l-4 border-orange-500 p-4 mb-6 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fa-solid fa-info-circle text-orange-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-orange-800">
                                Pemesanan Offline - Pembayaran Lunas
                            </p>
                            <p class="text-sm text-orange-700 mt-1">
                                Tiket ini sudah dibayar melalui kasir. Silakan tunjukkan tiket ini di bioskop.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Ticket -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border-2 border-gray-200">
                <!-- Ticket Header -->
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6 text-white">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-2xl font-bold">{{ $pemesanan->jadwalTayang->film->judul }}</h1>
                            <p class="text-blue-100 mt-1">Booking Code: <span
                                    class="font-mono font-bold">{{ $pemesanan->kode_booking }}</span></p>
                        </div>
                        <div class="text-right">
                            <div class="bg-white bg-opacity-20 rounded-lg px-3 py-1 text-sm">
                                {{ strtoupper($pemesanan->jadwalTayang->studio->tipe_studio) }}
                            </div>
                            <p class="mt-2 text-blue-100">Status:
                                <span
                                    class="font-semibold capitalize {{ $pemesanan->status_pembayaran == 'lunas' ? 'text-green-300' : 'text-yellow-300' }}">
                                    {{ $pemesanan->status_pembayaran }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Ticket Body -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-500">DATE & TIME</h3>
                                <p class="text-lg font-semibold">
                                    {{ \Carbon\Carbon::parse($pemesanan->jadwalTayang->tanggal_tayang)->format('l, M j, Y') }}
                                    • {{ \Carbon\Carbon::parse($pemesanan->jadwalTayang->jam_tayang)->format('g:i A') }}
                                </p>
                            </div>

                            <div>
                                <h3 class="text-sm font-semibold text-gray-500">STUDIO</h3>
                                <p class="text-lg font-semibold">{{ $pemesanan->jadwalTayang->studio->nama_studio }}</p>
                            </div>

                            <div>
                                <h3 class="text-sm font-semibold text-gray-500">SEATS</h3>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @foreach ($pemesanan->detailPemesanan as $detail)
                                        @if ($detail->kursi)
                                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-semibold">
                                                {{ $detail->kursi->nomor_kursi }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-500">BOOKING DETAILS</h3>
                                <div class="mt-2 space-y-2">
                                    <div class="flex justify-between">
                                        <span>Booking Date:</span>
                                        <span
                                            class="font-semibold">{{ \Carbon\Carbon::parse($pemesanan->created_at)->format('M j, Y g:i A') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Tickets:</span>
                                        <span class="font-semibold">{{ $pemesanan->jumlah_tiket }} tickets</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Payment Method:</span>
                                        <span
                                            class="font-semibold capitalize">{{ $pemesanan->metode_pembayaran }}</span>
                                    </div>
                                    @if ($pemesanan->jenis_pemesanan === 'offline')
                                        <div class="flex justify-between">
                                            <span>Type:</span>
                                            <span class="font-semibold capitalize text-orange-600">{{ $pemesanan->jenis_pemesanan }}</span>
                                        </div>
                                        @if ($pemesanan->kasir_id)
                                            <div class="flex justify-between">
                                                <span>Cashier:</span>
                                                <span class="font-semibold">{{ \App\Models\User::find($pemesanan->kasir_id)->name ?? 'N/A' }}</span>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            <div class="border-t pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-semibold">Total Amount</span>
                                    <span class="text-2xl font-bold text-blue-600">Rp
                                        {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Barcode Area -->
@if ($pemesanan->jenis_pemesanan === 'online')
    <div class="mt-8 border-t pt-6">
        <div class="text-center">
            <div class="bg-gray-100 p-4 rounded-lg inline-block">
                <!-- Barcode untuk online -->
                <div class="text-xs font-mono tracking-widest opacity-50">
                    {{ $pemesanan->kode_booking }}
                </div>
                <div class="flex justify-center mt-2 space-x-1">
                    @for ($i = 0; $i < 20; $i++)
                        <div class="w-1 h-8 bg-black"></div>
                    @endfor
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-2">Present this ticket at the cinema</p>
        </div>
    </div>
@else
    <div class="mt-8 border-t pt-6">
        <div class="text-center">
            <div class="bg-gray-100 p-6 rounded-lg inline-block">
                <p class="text-sm text-gray-600 mb-2">Booking Code</p>
                <div class="text-3xl font-bold font-mono tracking-wider text-gray-800">
                    {{ $pemesanan->kode_booking }}
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-4">
                <i class="fa-solid fa-ticket mr-1"></i>
                Present this booking code at the cinema counter
            </p>
        </div>
    </div>
@endif

            <!-- Action Buttons -->
            <div class="mt-6 flex justify-center space-x-4 no-print">
                <a href="{{ route('dashboard') }}"
                    class="bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-700 transition">
                    Back to Dashboard
                </a>
                <button onclick="window.print()"
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                    Print Ticket
                </button>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .bg-white {
                background: white !important;
            }
        }
    </style>
    @if (session('auto_print_ticket'))
    <script>
        window.addEventListener('load', function() {
            window.print();
        });
    </script>
@endif
</x-app-layout>