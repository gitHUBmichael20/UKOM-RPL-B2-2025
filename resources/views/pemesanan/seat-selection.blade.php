<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Select Your Seats') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Progress Steps - Responsive -->
            <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 mb-4 sm:mb-8">
                <!-- Desktop View -->
                <div class="hidden md:flex justify-between items-center">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center font-semibold text-sm">
                            ✓
                        </div>
                        <span class="font-medium text-green-600 text-sm lg:text-base">Film & Jadwal</span>
                    </div>
                    <div class="h-1 w-8 lg:w-12 bg-green-500"></div>
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">
                            2
                        </div>
                        <span class="font-medium text-blue-600 text-sm lg:text-base">Pilih Kursi</span>
                    </div>
                    <div class="h-1 w-8 lg:w-12 bg-gray-300"></div>
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-semibold text-sm">
                            3
                        </div>
                        <span class="font-medium text-gray-500 text-sm lg:text-base">Pembayaran</span>
                    </div>
                    <div class="h-1 w-8 lg:w-12 bg-gray-300"></div>
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-semibold text-sm">
                            4
                        </div>
                        <span class="font-medium text-gray-500 text-sm lg:text-base">Tiket Anda</span>
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
                        <span class="font-medium text-blue-600 text-xs mt-1 text-center">Seat</span>
                    </div>
                    <div class="h-0.5 w-6 bg-gray-300 -mt-4"></div>
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-semibold text-xs">
                            3
                        </div>
                        <span class="font-medium text-gray-500 text-xs mt-1 text-center">Pay</span>
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                <!-- Left Column - Movie Details & Pilih Kursi -->
                <div class="lg:col-span-2 space-y-4 sm:space-y-6">
                    <!-- Movie Details Card - Responsive -->
                    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row gap-4 sm:gap-6">
                            <div class="flex-shrink-0">
                                <img src="{{ asset('storage/' . $film->poster) ?? 'storage/default_poster.png' }}" 
                                     alt="{{ $film->judul }}"
                                     class="w-24 h-36 sm:w-32 sm:h-48 md:w-40 md:h-60 object-cover rounded-lg shadow-md mx-auto sm:mx-0">
                            </div>
                            <div class="flex-grow">
                                <div class="flex justify-between items-start">
                                    <div class="w-full">
                                        <h1 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 text-center sm:text-left">
                                            {{ $film->judul }}
                                        </h1>
                                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 sm:gap-3 mt-2">
                                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded">
                                                {{ $film->rating }}
                                            </span>
                                            <span class="text-xs sm:text-sm text-gray-600">
                                                {{ $film->genres ? $film->genres->pluck('nama_genre')->implode(', ') : 'No genres' }}
                                            </span>
                                            <span class="text-xs sm:text-sm text-gray-600">• {{ $film->durasi }} min</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3 sm:mt-4 grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3 text-xs sm:text-sm">
                                    <div class="text-center sm:text-left">
                                        <span class="text-gray-500">Director:</span>
                                        <span class="ml-2 font-medium">{{ $film->sutradara->nama_sutradara }}</span>
                                    </div>
                                    <div class="text-center sm:text-left">
                                        <span class="text-gray-500">Release Year:</span>
                                        <span class="ml-2 font-medium">{{ $film->tahun_rilis }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Screening Details - Responsive -->
                    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
                        <h2 class="text-base sm:text-lg md:text-xl font-bold text-gray-900 mb-3 sm:mb-4">Screening Details</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="border border-gray-200 rounded-lg p-3 sm:p-4 text-center sm:text-left">
                                <div class="text-gray-500 text-xs sm:text-sm mb-1">Date & Time</div>
                                <div class="font-semibold text-sm sm:text-base">
                                    {{ \Carbon\Carbon::parse($jadwalTayang->tanggal_tayang)->format('D, M j, Y') }}
                                </div>
                                <div class="font-semibold text-sm sm:text-base">
                                    {{ \Carbon\Carbon::parse($jadwalTayang->jam_tayang)->format('g:i A') }}
                                </div>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-3 sm:p-4 text-center sm:text-left">
                                <div class="text-gray-500 text-xs sm:text-sm mb-1">Theater</div>
                                <div class="font-semibold text-sm sm:text-base">{{ $jadwalTayang->studio->nama_studio }}</div>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-3 sm:p-4 text-center sm:text-left">
                                <div class="text-gray-500 text-xs sm:text-sm mb-1">Studio Type</div>
                                <div class="font-semibold text-sm sm:text-base">{{ strtoupper($jadwalTayang->studio->tipe_studio) }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Pilih Kursi - Responsive -->
                    <div class="bg-white rounded-lg shadow-sm p-3 sm:p-4 md:p-6">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6 gap-3">
                            <h2 class="text-base sm:text-lg md:text-xl font-bold text-gray-900">Select Your Seats</h2>
                            <div class="flex flex-wrap items-center gap-2 sm:gap-3 md:gap-4 text-xs sm:text-sm w-full sm:w-auto">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 sm:w-4 sm:h-4 bg-green-500 rounded-sm mr-1.5"></div>
                                    <span class="text-gray-600">Available</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 sm:w-4 sm:h-4 bg-red-500 rounded-sm mr-1.5"></div>
                                    <span class="text-gray-600">Booked</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 sm:w-4 sm:h-4 bg-blue-500 rounded-sm mr-1.5"></div>
                                    <span class="text-gray-600">Selected</span>
                                </div>
                            </div>
                        </div>

                        <!-- Seat Map - Responsive -->
                        <div class="flex flex-col items-center">
                            <form method="POST" action="{{ route('pemesanan.payment', [$film, $jadwalTayang]) }}" id="seatForm" class="w-full">
                                @csrf
                                @php
                                    $kursiByBaris = $seats->groupBy(function ($kursi) {
                                        return substr($kursi->nomor_kursi, 0, 1);
                                    })->sortKeys();

                                    $totalKolom = $seats->max(function ($kursi) {
                                        return (int) substr($kursi->nomor_kursi, 1);
                                    }) ?: 12;

                                    $gangPosition = ceil($totalKolom / 2);
                                @endphp

                                <!-- Screen - Responsive -->
                                <div class="mb-4 sm:mb-6 md:mb-8">
                                    <div class="bg-gradient-to-b from-gray-800 to-gray-700 text-white text-center py-2 sm:py-3 md:py-4 rounded-t-3xl shadow-lg px-4 sm:px-6 md:px-8">
                                        <i class="fa-solid fa-film mr-2 text-xs sm:text-sm md:text-base"></i>
                                        <span class="font-bold text-sm sm:text-base md:text-lg">SCREEN</span>
                                    </div>
                                    <p class="text-center text-xs sm:text-sm text-gray-500 mt-2">All eyes this way please →</p>
                                </div>

                                <!-- Seats Layout - Responsive with Horizontal Scroll on Mobile -->
                                <div class="w-full overflow-x-auto pb-4">
                                    <div class="inline-block min-w-full">
                                        <div class="space-y-1.5 sm:space-y-2 md:space-y-3 mx-auto" style="width: fit-content;">
                                            @foreach($kursiByBaris as $baris => $kursiList)
                                                <div class="flex items-center justify-center gap-1 sm:gap-2 md:gap-3">
                                                    <!-- Row Label Kiri -->
                                                    <div class="w-4 sm:w-6 md:w-8 text-center font-bold text-gray-700 text-xs sm:text-sm md:text-base">
                                                        {{ $baris }}
                                                    </div>

                                                    <!-- Seats -->
                                                    <div class="flex items-center gap-0.5 sm:gap-1 md:gap-2">
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
                                                                    class="seat-label block cursor-pointer transition-all duration-200 transform active:scale-90 sm:active:scale-95"
                                                                    data-seat-number="{{ $seatNumber }}"
                                                                >
                                                                    <div class="
                                                                        w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 rounded-t-lg flex items-center justify-center text-white font-bold text-[10px] sm:text-xs shadow-md
                                                                        @if($isBooked)
                                                                            bg-red-600 cursor-not-allowed
                                                                        @elseif(request()->input('selected_seats') && in_array($seatId, request()->input('selected_seats') ?? []))
                                                                            bg-blue-600 ring-2 ring-blue-300
                                                                        @else
                                                                            bg-green-600 sm:hover:bg-green-700
                                                                        @endif
                                                                    ">
                                                                        {{ $nomorKolom }}
                                                                    </div>

                                                                    <!-- Tooltip - Hidden on mobile, shown on hover for desktop -->
                                                                    <div class="hidden sm:block absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-20 pointer-events-none">
                                                                        {{ $seatNumber }}
                                                                        <div class="absolute top-full left-1/2 transform -translate-x-1/2 -mt-1 border-4 border-transparent border-t-gray-900"></div>
                                                                    </div>
                                                                </label>
                                                            </div>

                                                            <!-- Gang (lorong tengah) -->
                                                            @if($nomorKolom == $gangPosition && !$loop->last)
                                                                <div class="w-3 sm:w-6 md:w-8 flex items-center justify-center text-gray-400">
                                                                    <i class="fa-solid fa-grip-lines-vertical text-sm sm:text-lg md:text-xl"></i>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>

                                                    <!-- Row Label Kanan -->
                                                    <div class="w-4 sm:w-6 md:w-8 text-center font-bold text-gray-700 text-xs sm:text-sm md:text-base">
                                                        {{ $baris }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <!-- Helper text for mobile scroll -->
                                <p class="text-center text-xs text-gray-500 mt-2 sm:hidden">
                                    ← Swipe to see all seats →
                                </p>

                                <!-- Selected Seats Summary - Responsive -->
                                <div id="selectedSeats" class="mt-4 sm:mt-6 md:mt-8 p-3 sm:p-4 md:p-5 bg-blue-50 rounded-xl hidden border border-blue-200">
                                    <h3 class="font-bold text-blue-900 mb-2 sm:mb-3 flex items-center gap-2 text-xs sm:text-sm md:text-base">
                                        <i class="fa-solid fa-ticket-alt"></i>
                                        Selected Seats
                                    </h3>
                                    <div id="selectedSeatsList" class="flex flex-wrap gap-1.5 sm:gap-2"></div>
                                </div>

                                <!-- Continue Button - Responsive -->
                                <div class="mt-4 sm:mt-6 md:mt-8 flex justify-center">
                                    <button type="submit" id="continueBtn"
                                        class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 active:bg-blue-800 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-bold py-3 sm:py-3.5 md:py-4 px-6 sm:px-8 md:px-10 rounded-xl text-sm sm:text-base md:text-lg shadow-lg transition-all transform active:scale-95 disabled:transform-none disabled:active:scale-100">
                                        <i class="fa-solid fa-arrow-right mr-2"></i>
                                        Continue to Payment
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Order Summary (Sticky on desktop, normal on mobile) -->
                <div class="space-y-4 sm:space-y-6 lg:sticky lg:top-6 lg:h-fit">
                    <!-- Order Summary - Responsive -->
                    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-5 md:p-6">
                        <h2 class="text-base sm:text-lg md:text-xl font-bold text-gray-900 mb-3 sm:mb-4">Order Summary</h2>

                        <div class="space-y-2.5 sm:space-y-3 md:space-y-4 text-xs sm:text-sm md:text-base">
                            <div class="flex justify-between items-start gap-2">
                                <span class="text-gray-600">Movie</span>
                                <span class="font-medium text-right">{{ $film->judul }}</span>
                            </div>
                            <div class="flex justify-between items-start gap-2">
                                <span class="text-gray-600">Date & Time</span>
                                <span class="font-medium text-right">
                                    {{ \Carbon\Carbon::parse($jadwalTayang->tanggal_tayang)->format('M j, Y') }},
                                    {{ \Carbon\Carbon::parse($jadwalTayang->jam_tayang)->format('g:i A') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-start gap-2">
                                <span class="text-gray-600">Studio</span>
                                <span class="font-medium text-right">{{ $jadwalTayang->studio->nama_studio }}</span>
                            </div>

                            <div class="border-t border-gray-200 pt-2.5 sm:pt-3 md:pt-4">
                                <div class="flex justify-between mb-2">
                                    <span class="text-gray-600">Tickets</span>
                                    <span id="ticketCount" class="font-medium">0</span>
                                </div>
                                <div class="flex justify-between items-start gap-2 mb-2">
                                    <span class="text-gray-600">Seats</span>
                                    <span id="seatsList" class="font-medium text-right">-</span>
                                </div>
                                <div class="flex justify-between items-start gap-2 mb-2">
                                    <span class="text-gray-600">Price per ticket</span>
                                    <span class="font-medium text-right">Rp {{ number_format($hargaTiket->harga, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="border-t border-gray-200 pt-2.5 sm:pt-3 md:pt-4">
                                <div class="flex justify-between text-sm sm:text-base md:text-lg font-bold">
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
        const form = document.getElementById('seatForm');

        let selectedSeats = [];

        // Handle checkbox change
        document.querySelectorAll('.seat-checkbox').forEach(checkbox => {
            if (checkbox.disabled) return;

            checkbox.addEventListener('change', function () {
                const label = this.nextElementSibling.querySelector('div');
                const seatNumber = this.nextElementSibling.dataset.seatNumber;

                if (this.checked) {
                    selectedSeats.push({ id: this.value, number: seatNumber });
                    label.classList.remove('bg-green-600', 'sm:hover:bg-green-700');
                    label.classList.add('bg-blue-600', 'ring-2', 'ring-blue-300');
                } else {
                    selectedSeats = selectedSeats.filter(s => s.id !== this.value);
                    label.classList.remove('bg-blue-600', 'ring-2', 'ring-blue-300');
                    label.classList.add('bg-green-600', 'sm:hover:bg-green-700');
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
                    badge.className = 'bg-blue-600 text-white px-3 py-1.5 rounded-full font-bold text-xs';
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

        // INI YANG PENTING BANGET! KIRIM DATA KE SERVER!
        form.addEventListener('submit', function(e) {
            if (selectedSeats.length === 0) {
                e.preventDefault();
                alert('Pilih minimal 1 kursi dulu!');
                return;
            }

            // Hapus hidden input lama (kalau ada)
            document.querySelectorAll('input[name="selected_seats[]"]').forEach(el => el.remove());

            // Tambah hidden input baru
            selectedSeats.forEach(seat => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_seats[]';
                input.value = seat.id;
                form.appendChild(input);
            });
        });

        // Restore selection dari URL (kalau kembali dari payment)
        @if(request()->has('selected_seats'))
            @foreach(request()->input('selected_seats') as $id)
                const checkbox = document.getElementById('seat-{{ $id }}');
                if (checkbox && !checkbox.disabled) {
                    checkbox.checked = true;
                    checkbox.dispatchEvent(new Event('change'));
                }
            @endforeach
        @endif
    });
</script>
</x-app-layout>