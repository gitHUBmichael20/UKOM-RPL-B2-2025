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
                                <img src="{{ asset($film->poster) }}" alt="{{ $film->judul }}"
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
                                    <span class="text-sm text-gray-600">Available</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-red-500 rounded-sm mr-2"></div>
                                    <span class="text-sm text-gray-600">Occupied</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-blue-500 rounded-sm mr-2"></div>
                                    <span class="text-sm text-gray-600">Selected</span>
                                </div>
                            </div>
                        </div>

                        <!-- Screen -->
                        <div class="mb-8 text-center">
                            <div class="bg-gray-800 text-white py-3 rounded-lg mx-auto max-w-md">
                                <span class="font-semibold">SCREEN</span>
                            </div>
                            <div class="text-sm text-gray-500 mt-2">All eyes this way please</div>
                        </div>

                        <!-- Seat Map -->
                        <div class="flex flex-col items-center">
                            <form id="seatForm" action="{{ route('pemesanan.payment', [$film, $jadwalTayang]) }}"
                                method="GET">
                                <div id="seatMap" class="grid grid-cols-12 gap-2 mb-4">
                                    @foreach ($seats as $seat)
                                        @php
                                            $isBooked = in_array($seat->id, $bookedSeats);
                                        @endphp
                                        <div class="seat-container text-center">
                                            <input type="checkbox" name="selected_seats[]" value="{{ $seat->id }}"
                                                id="seat-{{ $seat->id }}" class="hidden seat-checkbox"
                                                {{ $isBooked ? 'disabled' : '' }}>
                                            <label for="seat-{{ $seat->id }}"
                                                class="seat-label w-8 h-8 rounded-sm flex items-center justify-center text-xs font-medium cursor-pointer transition-all
                                                          {{ $isBooked ? 'bg-red-500 cursor-not-allowed' : 'bg-green-500 hover:bg-green-600' }}"
                                                data-seat-id="{{ $seat->id }}"
                                                data-seat-number="{{ $seat->nomor_kursi }}">
                                                {{ substr($seat->nomor_kursi, 1) }}
                                            </label>
                                            <div class="text-xs text-gray-500 mt-1">{{ $seat->nomor_kursi }}</div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Selected Seats Summary -->
                                <div id="selectedSeats" class="mt-6 p-4 bg-blue-50 rounded-lg hidden">
                                    <h3 class="font-semibold text-blue-800 mb-2">Selected Seats</h3>
                                    <div id="selectedSeatsList" class="flex flex-wrap gap-2"></div>
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <button type="submit" id="continueBtn"
                                        class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed"
                                        disabled>
                                        Continue to Payment
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
        document.addEventListener('DOMContentLoaded', function() {
            const seatCheckboxes = document.querySelectorAll('.seat-checkbox');
            const selectedSeatsContainer = document.getElementById('selectedSeats');
            const selectedSeatsList = document.getElementById('selectedSeatsList');
            const ticketCount = document.getElementById('ticketCount');
            const seatsList = document.getElementById('seatsList');
            const totalPrice = document.getElementById('totalPrice');
            const continueBtn = document.getElementById('continueBtn');
            const pricePerTicket = {{ $hargaTiket->harga }};

            let selectedSeats = [];

            // Seat selection handler
            seatCheckboxes.forEach(checkbox => {
                if (!checkbox.disabled) {
                    checkbox.addEventListener('change', function() {
                        const seatId = this.value;
                        const seatLabel = document.querySelector(`label[for="seat-${seatId}"]`);
                        const seatNumber = seatLabel.dataset.seatNumber;

                        if (this.checked) {
                            selectedSeats.push({
                                id: seatId,
                                number: seatNumber
                            });
                            seatLabel.classList.remove('bg-green-500', 'hover:bg-green-600');
                            seatLabel.classList.add('bg-blue-500');
                        } else {
                            selectedSeats = selectedSeats.filter(seat => seat.id !== seatId);
                            seatLabel.classList.remove('bg-blue-500');
                            seatLabel.classList.add('bg-green-500', 'hover:bg-green-600');
                        }

                        updateOrderSummary();
                    });
                }
            });

            // Update order summary
            function updateOrderSummary() {
                const count = selectedSeats.length;

                if (count > 0) {
                    selectedSeatsContainer.classList.remove('hidden');
                    selectedSeatsList.innerHTML = '';

                    selectedSeats.forEach(seat => {
                        const seatBadge = document.createElement('div');
                        seatBadge.className =
                            'bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded';
                        seatBadge.textContent = seat.number;
                        selectedSeatsList.appendChild(seatBadge);
                    });

                    ticketCount.textContent = count;
                    seatsList.textContent = selectedSeats.map(seat => seat.number).join(', ');
                    totalPrice.textContent = `Rp ${(count * pricePerTicket).toLocaleString()}`;
                    continueBtn.disabled = false;
                } else {
                    selectedSeatsContainer.classList.add('hidden');
                    ticketCount.textContent = '0';
                    seatsList.textContent = '-';
                    totalPrice.textContent = 'Rp 0';
                    continueBtn.disabled = true;
                }
            }
        });
    </script>
</x-app-layout>
