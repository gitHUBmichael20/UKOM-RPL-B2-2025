<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Select Your Seats') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                            class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold">
                            2
                        </div>
                        <span class="font-medium text-blue-600">Seat Selection</span>
                    </div>
                    <div class="h-1 w-12 bg-gray-300"></div>
                    <div class="flex items-center space-x-2">
                        <div
                            class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-semibold">
                            3
                        </div>
                        <span class="font-medium text-gray-500">Payment</span>
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Movie Details & Seat Selection -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Movie Details Card -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="flex-shrink-0">
                                <img src="{{ asset('storage/' . $film->poster) ?? 'storage/default_poster.png' }}" alt="{{ $film->judul }}"
                                    class="w-40 rounded-lg shadow-md">
                            </div>
                            <div class="flex-grow">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h1 class="text-2xl font-bold text-gray-900">{{ $film->judul }}</h1>
                                        <div class="flex items-center space-x-4 mt-2">
                                            <span
                                                class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                                {{ $film->rating }}
                                            </span>
                                            <span class="text-gray-600">
                                                {{ $film->genres ? $film->genres->pluck('nama_genre')->implode(', ') : 'No genres' }}
                                            </span>
                                            <span class="text-gray-600">• {{ $film->durasi }} min</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-500">Director:</span>
                                        <span class="ml-2 font-medium">{{ $film->sutradara->nama_sutradara }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Release Year:</span>
                                        <span class="ml-2 font-medium">{{ $film->tahun_rilis }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Screening Details -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Screening Details</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="text-gray-500 text-sm">Date & Time</div>
                                <div class="font-semibold">
                                    {{ \Carbon\Carbon::parse($jadwalTayang->tanggal_tayang)->format('D, M j, Y') }},
                                    {{ \Carbon\Carbon::parse($jadwalTayang->jam_tayang)->format('g:i A') }}
                                </div>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="text-gray-500 text-sm">Theater</div>
                                <div class="font-semibold">{{ $jadwalTayang->studio->nama_studio }}</div>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="text-gray-500 text-sm">Studio Type</div>
                                <div class="font-semibold">{{ strtoupper($jadwalTayang->studio->tipe_studio) }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Seat Selection -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-bold text-gray-900">Select Your Seats</h2>
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-green-500 rounded-sm mr-2"></div>
                                    <span class="text-sm text-gray-600">Tersedia</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-red-500 rounded-sm mr-2"></div>
                                    <span class="text-sm text-gray-600">Sudah Dipesan</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-blue-500 rounded-sm mr-2"></div>
                                    <span class="text-sm text-gray-600">Dipilih</span>
                                </div>
                            </div>
                        </div>

                        <!-- Seat Map -->
<!-- Seat Map - Mirip Layout Admin Studio -->
<div class="flex flex-col items-center">
    <form id="seatForm" action="{{ route('pemesanan.payment', [$film, $jadwalTayang]) }}" method="GET">
        @php
            // Group seats by row (A, B, C, dst)
            $kursiByBaris = $seats->groupBy(function ($kursi) {
                return substr($kursi->nomor_kursi, 0, 1);
            })->sortKeys();

            // Tentukan jumlah kolom maksimal (misal: 1-12)
            $totalKolom = $seats->max(function ($kursi) {
                return (int) substr($kursi->nomor_kursi, 1);
            }) ?: 12;

            // Gang (lorong) di tengah
            $gangPosition = ceil($totalKolom / 2);
        @endphp

        <!-- Screen -->
        <div class="mb-10">
            <div class="bg-gradient-to-b from-gray-800 to-gray-700 text-white text-center py-4 rounded-t-3xl shadow-lg px-8">
                <i class="fa-solid fa-film mr-2"></i>
                <span class="font-bold text-lg">SCREEN</span>
            </div>
            <p class="text-center text-sm text-gray-500 mt-3">All eyes this way please →</p>
        </div>

        <!-- Seats Layout -->
        <div class="space-y-4 max-w-4xl w-full">
            @foreach($kursiByBaris as $baris => $kursiList)
                <div class="flex items-center justify-center gap-3">
                    <!-- Row Label Kiri -->
                    <div class="w-8 text-center font-bold text-gray-700 text-lg">
                        {{ $baris }}
                    </div>

                    <!-- Seats -->
                    <div class="flex items-center gap-2">
                        @php
                            $sortedKursi = $kursiList->sortBy(function ($kursi) {
                                return (int) substr($kursi->nomor_kursi, 1);
                            });
                        @endphp

                        @foreach($sortedKursi as $kursi)
                            @php
                                $nomorKolom = (int) substr($kursi->nomor_kursi, 1);
                                $isBooked = in_array($kursi->id, $bookedSeats);
                                $seatId = $kursi->id;
                                $seatNumber = $kursi->nomor_kursi;
                            @endphp

                            <div class="group relative">
                                <input
                                    type="checkbox"
                                    name="selected_seats[]"
                                    value="{{ $seatId }}"
                                    id="seat-{{ $seatId }}"
                                    class="hidden seat-checkbox"
                                    {{ $isBooked ? 'disabled' : '' }}
                                >

                                <label
                                    for="seat-{{ $seatId }}"
                                    class="seat-label block cursor-pointer transition-all duration-200 transform hover:scale-110"
                                    data-seat-number="{{ $seatNumber }}"
                                >
                                    <div class="
                                        w-10 h-10 rounded-t-lg flex items-center justify-center text-white font-bold text-xs shadow-md
                                        @if($isBooked)
                                            bg-red-600 cursor-not-allowed
                                        @elseif(request()->input('selected_seats') && in_array($seatId, request()->input('selected_seats') ?? []))
                                            bg-blue-600 ring-4 ring-blue-300
                                        @else
                                            bg-green-600 hover:bg-green-700
                                        @endif
                                    ">
                                        {{ $nomorKolom }}
                                    </div>

                                    <!-- Tooltip -->
                                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-1.5 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-20 pointer-events-none">
                                        {{ $seatNumber }}
                                        <div class="absolute top-full left-1/2 transform -translate-x-1/2 -mt-1 border-4 border-transparent border-t-gray-900"></div>
                                    </div>
                                </label>
                            </div>

                            <!-- Gang (lorong tengah) -->
                            @if($nomorKolom == $gangPosition && $loop->last == false)
                                <div class="w-12 flex items-center justify-center text-gray-400">
                                    <i class="fa-solid fa-grip-lines-vertical text-2xl"></i>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Row Label Kanan -->
                    <div class="w-8 text-center font-bold text-gray-700 text-lg">
                        {{ $baris }}
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Legend -->
        <div class="mt-10 flex justify-center gap-8 text-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-600 rounded-t-lg shadow-md"></div>
                <span class="text-gray-700">Tersedia</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-600 rounded-t-lg ring-4 ring-blue-300 shadow-md"></div>
                <span class="text-gray-700">Dipilih</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-600 rounded- rounded-t-lg shadow-md"></div>
                <span class="text-gray-700">Sudah Dipesan</span>
            </div>
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-grip-lines-vertical text-gray-400 text-2xl"></i>
                <span class="text-gray-700">Gang</span>
            </div>
        </div>

        <!-- Selected Seats Summary (tetap) -->
        <div id="selectedSeats" class="mt-8 p-5 bg-blue-50 rounded-xl hidden border border-blue-200">
            <h3 class="font-bold text-blue-900 mb-3 flex items-center gap-2">
                <i class="fa-solid fa-ticket-alt"></i>
                Kursi yang Dipilih
            </h3>
            <div id="selectedSeatsList" class="flex flex-wrap gap-2"></div>
        </div>

        <!-- Continue Button -->
                        <div class="mt-8 flex justify-center">
                                    <button type="submit" id="continueBtn"
                                        class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-bold py-4 px-10 rounded-xl text-lg shadow-lg transition-all transform hover:scale-105 disabled:transform-none">
                                        <i class="fa-solid fa-arrow-right mr-2"></i>
                                        Lanjut ke Pembayaran
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Order Summary -->
                <div class="space-y-6 sticky top-6 h-fit">
                    <!-- Order Summary -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Order Summary</h2>

                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Movie</span>
                                <span class="font-medium">{{ $film->judul }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Date & Time</span>
                                <span class="font-medium">
                                    {{ \Carbon\Carbon::parse($jadwalTayang->tanggal_tayang)->format('M j, Y') }},
                                    {{ \Carbon\Carbon::parse($jadwalTayang->jam_tayang)->format('g:i A') }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Studio</span>
                                <span class="font-medium">{{ $jadwalTayang->studio->nama_studio }}</span>
                            </div>

                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between mb-2">
                                    <span class="text-gray-600">Tickets</span>
                                    <span id="ticketCount" class="font-medium">0</span>
                                </div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-gray-600">Seats</span>
                                    <span id="seatsList" class="font-medium">-</span>
                                </div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-gray-600">Price per ticket</span>
                                    <span class="font-medium">Rp
                                        {{ number_format($hargaTiket->harga, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between text-lg font-bold">
                                    <span>Total</span>
                                    <span id="totalPrice">Rp 0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const pricePerTicket = {{ $hargaTiket->harga }};
        const selectedSeatsContainer = document.getElementById('selectedSeats');
        const selectedSeatsList = document.getElementById('selectedSeatsList');
        const ticketCount = document.getElementById('ticketCount');
        const seatsList = document.getElementById('seatsList');
        const totalPrice = document.getElementById('totalPrice');
        const continueBtn = document.getElementById('continueBtn');

        let selectedSeats = [];

        document.querySelectorAll('.seat-checkbox').forEach(checkbox => {
            if (checkbox.disabled) return;

            checkbox.addEventListener('change', function () {
                const label = this.nextElementSibling.querySelector('.seat-label > div');
                const seatNumber = this.nextElementSibling.dataset.seatNumber;

                if (this.checked) {
                    selectedSeats.push({ id: this.value, number: seatNumber });
                    label.classList.remove('bg-green-600', 'hover:bg-green-700');
                    label.classList.add('bg-blue-600', 'ring-4', 'ring-blue-300');
                } else {
                    selectedSeats = selectedSeats.filter(s => s.id !== this.value);
                    label.classList.remove('bg-blue-600', 'ring-4', 'ring-blue-300');
                    label.classList.add('bg-green-600', 'hover:bg-green-700');
                }

                updateSummary();
            });
        });

        function updateSummary() {
            const count = selectedSeats.length;

            if (count > 0) {
                selectedSeatsContainer.classList.remove('hidden');
                selectedSeatsList.innerHTML = '';
                selectedSeats.forEach(seat => {
                    const badge = document.createElement('span');
                    badge.className = 'bg-blue-600 text-white px-4 py-2 rounded-full font-bold';
                    badge.textContent = seat.number;
                    selectedSeatsList.appendChild(badge);
                });

                ticketCount.textContent = count;
                seatsList.textContent = selectedSeats.map(s => s.number).join(', ');
                totalPrice.textContent = `Rp ${(count * pricePerTicket).toLocaleString('id-ID')}`;
                continueBtn.disabled = false;
            } else {
                selectedSeatsContainer.classList.add('hidden');
                ticketCount.textContent = '0';
                seatsList.textContent = '-';
                totalPrice.textContent = 'Rp 0';
                continueBtn.disabled = true;
            }
        }

        // Restore selection dari URL (jika kembali dari payment)
        @if(request()->has('selected_seats'))
            @foreach(request()->input('selected_seats') as $id)
                document.getElementById('seat-{{ $id }}')?.dispatchEvent(new Event('change'));
            @endforeach
        @endif
    });
</script>
</x-app-layout>
