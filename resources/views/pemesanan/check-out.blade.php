<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Book Your Movie') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Progress Steps -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-2">
                        <div
                            class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold">
                            1</div>
                        <span class="font-medium text-blue-600">Movie Details</span>
                    </div>
                    <div class="h-1 w-12 bg-blue-600"></div>
                    <div class="flex items-center space-x-2">
                        <div
                            class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold">
                            2</div>
                        <span class="font-medium text-blue-600">Seat Selection</span>
                    </div>
                    <div class="h-1 w-12 bg-blue-600"></div>
                    <div class="flex items-center space-x-2">
                        <div
                            class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-semibold">
                            3</div>
                        <span class="font-medium text-gray-500">Add-ons</span>
                    </div>
                    <div class="h-1 w-12 bg-gray-300"></div>
                    <div class="flex items-center space-x-2">
                        <div
                            class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-semibold">
                            4</div>
                        <span class="font-medium text-gray-500">Payment</span>
                    </div>
                    <div class="h-1 w-12 bg-gray-300"></div>
                    <div class="flex items-center space-x-2">
                        <div
                            class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-semibold">
                            5</div>
                        <span class="font-medium text-gray-500">Confirmation</span>
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
                                <img src="https://picsum.photos/300/450?random=1" alt="Movie Poster"
                                    class="w-40 rounded-lg shadow-md">
                            </div>
                            <div class="flex-grow">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h1 class="text-2xl font-bold text-gray-900">Avengers: Endgame</h1>
                                        <div class="flex items-center space-x-4 mt-2">
                                            <span
                                                class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">PG-13</span>
                                            <span class="text-gray-600">Action, Adventure, Drama</span>
                                            <span class="text-gray-600">• 3h 1m</span>
                                        </div>
                                    </div>
                                    <div
                                        class="bg-black bg-opacity-70 text-white text-sm font-semibold px-3 py-1 rounded flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-yellow-400" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                            </path>
                                        </svg>
                                        8.4/10
                                    </div>
                                </div>

                                <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-500">Director:</span>
                                        <span class="ml-2 font-medium">Anthony Russo, Joe Russo</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Release Date:</span>
                                        <span class="ml-2 font-medium">April 26, 2019</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Language:</span>
                                        <span class="ml-2 font-medium">English</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Subtitles:</span>
                                        <span class="ml-2 font-medium">Indonesian</span>
                                    </div>
                                </div>

                                <p class="mt-4 text-gray-700">
                                    After the devastating events of Avengers: Infinity War, the universe is in ruins.
                                    With the help of remaining allies, the Avengers assemble once more in order to
                                    reverse Thanos' actions and restore balance to the universe.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Screening Details -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Screening Details</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="text-gray-500 text-sm">Date & Time</div>
                                <div class="font-semibold">Today, 7:30 PM</div>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="text-gray-500 text-sm">Theater</div>
                                <div class="font-semibold">Cinema XXI - Mall Taman Anggrek</div>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="text-gray-500 text-sm">Auditorium</div>
                                <div class="font-semibold">Studio 5</div>
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
                            <div id="seatMap" class="grid grid-cols-12 gap-2 mb-4">
                                <!-- Seats will be generated by JavaScript -->
                            </div>

                            <!-- Seat Labels -->
                            <div class="grid grid-cols-12 gap-2 mt-2 mb-8">
                                <div class="text-center text-sm font-medium text-gray-700">A</div>
                                <div class="text-center text-sm font-medium text-gray-700">B</div>
                                <div class="text-center text-sm font-medium text-gray-700">C</div>
                                <div class="text-center text-sm font-medium text-gray-700">D</div>
                                <div class="text-center text-sm font-medium text-gray-700">E</div>
                                <div class="text-center text-sm font-medium text-gray-700">F</div>
                                <div class="text-center text-sm font-medium text-gray-700">G</div>
                                <div class="text-center text-sm font-medium text-gray-700">H</div>
                                <div class="text-center text-sm font-medium text-gray-700">I</div>
                                <div class="text-center text-sm font-medium text-gray-700">J</div>
                                <div class="text-center text-sm font-medium text-gray-700">K</div>
                                <div class="text-center text-sm font-medium text-gray-700">L</div>
                            </div>
                        </div>

                        <!-- Selected Seats Summary -->
                        <div id="selectedSeats" class="mt-6 p-4 bg-blue-50 rounded-lg hidden">
                            <h3 class="font-semibold text-blue-800 mb-2">Selected Seats</h3>
                            <div id="selectedSeatsList" class="flex flex-wrap gap-2"></div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Order Summary & Help -->
                <div class="space-y-6 sticky top-6 h-fit">
                    <!-- Order Summary -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Order Summary</h2>

                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Movie</span>
                                <span class="font-medium">Avengers: Endgame</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Date & Time</span>
                                <span class="font-medium">Today, 7:30 PM</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Theater</span>
                                <span class="font-medium">Studio 5</span>
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
                                    <span class="font-medium">Rp 45,000</span>
                                </div>
                            </div>

                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between text-lg font-bold">
                                    <span>Total</span>
                                    <span id="totalPrice">Rp 0</span>
                                </div>
                            </div>

                            <button id="continueBtn"
                                class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed"
                                disabled>
                                Continue to Add-ons
                            </button>
                        </div>
                    </div>

                    <!-- Help Card - Always at the bottom of Order Summary -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="font-semibold text-gray-900 mb-3">Need Help?</h3>
                        <p class="text-sm text-gray-600 mb-4">If you need assistance with your booking, please contact
                            our customer service.</p>
                        <div class="flex items-center text-blue-600">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                            <span class="font-medium">+62 21 1234 5678</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const seatMap = document.getElementById('seatMap');
            const selectedSeatsContainer = document.getElementById('selectedSeats');
            const selectedSeatsList = document.getElementById('selectedSeatsList');
            const ticketCount = document.getElementById('ticketCount');
            const seatsList = document.getElementById('seatsList');
            const totalPrice = document.getElementById('totalPrice');
            const continueBtn = document.getElementById('continueBtn');

            const pricePerTicket = 45000;
            let selectedSeats = [];

            // Generate 120 seats (10 rows x 12 columns)
            function generateSeats() {
                const rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];

                rows.forEach(row => {
                    for (let col = 1; col <= 12; col++) {
                        const seatId = `${row}${col}`;
                        const seat = document.createElement('div');

                        // Randomly mark some seats as occupied (for demo)
                        const isOccupied = Math.random() < 0.2;

                        seat.className =
                            `w-8 h-8 rounded-sm flex items-center justify-center text-xs font-medium cursor-pointer transition-all ${isOccupied ? 'bg-red-500 cursor-not-allowed' : 'bg-green-500 hover:bg-green-600'}`;
                        seat.textContent = col;
                        seat.dataset.seatId = seatId;
                        seat.dataset.occupied = isOccupied;

                        if (!isOccupied) {
                            seat.addEventListener('click', toggleSeatSelection);
                        }

                        seatMap.appendChild(seat);
                    }
                });
            }

            // Toggle seat selection
            function toggleSeatSelection(e) {
                const seat = e.target;
                const seatId = seat.dataset.seatId;

                if (selectedSeats.includes(seatId)) {
                    // Deselect seat
                    selectedSeats = selectedSeats.filter(id => id !== seatId);
                    seat.classList.remove('bg-blue-500');
                    seat.classList.add('bg-green-500', 'hover:bg-green-600');
                } else {
                    // Select seat
                    selectedSeats.push(seatId);
                    seat.classList.remove('bg-green-500', 'hover:bg-green-600');
                    seat.classList.add('bg-blue-500');
                }

                updateOrderSummary();
            }

            // Update order summary based on selected seats
            function updateOrderSummary() {
                const count = selectedSeats.length;

                if (count > 0) {
                    selectedSeatsContainer.classList.remove('hidden');
                    selectedSeatsList.innerHTML = '';

                    selectedSeats.forEach(seatId => {
                        const seatBadge = document.createElement('div');
                        seatBadge.className =
                            'bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded';
                        seatBadge.textContent = seatId;
                        selectedSeatsList.appendChild(seatBadge);
                    });

                    ticketCount.textContent = count;
                    seatsList.textContent = selectedSeats.join(', ');
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

            // Initialize seat map
            generateSeats();

            // Continue button event
            continueBtn.addEventListener('click', function() {
                alert(`Proceeding with ${selectedSeats.length} seats: ${selectedSeats.join(', ')}`);
                // In a real app, you would navigate to the next step
            });
        });
    </script>

    <style>
        #seatMap {
            /* Custom styling for seat layout */
        }

        .sticky {
            position: sticky;
        }
    </style>
</x-app-layout>
