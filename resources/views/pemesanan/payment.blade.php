<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payment Method') }}
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
                            class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center font-semibold">
                            ✓
                        </div>
                        <span class="font-medium text-green-600">Seat Selection</span>
                    </div>
                    <div class="h-1 w-12 bg-green-500"></div>
                    <div class="flex items-center space-x-2">
                        <div
                            class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold">
                            3
                        </div>
                        <span class="font-medium text-blue-600">Payment</span>
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
                <!-- Left Column - Payment Method -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Movie & Seat Summary -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Booking Summary</h2>
                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="flex-shrink-0">
                                <img src="{{ $film->poster }}" alt="{{ $film->judul }}"
                                    class="w-32 rounded-lg shadow-md">
                            </div>
                            <div class="flex-grow">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $film->judul }}</h3>
                                <div class="mt-2 grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-500">Date & Time:</span>
                                        <span class="ml-2 font-medium">
                                            {{ \Carbon\Carbon::parse($jadwalTayang->tanggal_tayang)->format('M j, Y') }},
                                            {{ \Carbon\Carbon::parse($jadwalTayang->jam_tayang)->format('g:i A') }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Studio:</span>
                                        <span class="ml-2 font-medium">{{ $jadwalTayang->studio->nama_studio }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Seats:</span>
                                        <span class="ml-2 font-medium">
                                            {{ $seats->pluck('nomor_kursi')->implode(', ') }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Total Tickets:</span>
                                        <span class="ml-2 font-medium">{{ $seats->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Select Payment Method</h2>

                        <form id="paymentForm" action="{{ route('pemesanan.store', [$film, $jadwalTayang]) }}"
                            method="POST">
                            @csrf

                            {{-- show server validation / error messages --}}
                            @if ($errors->any())
                                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 p-3 rounded">
                                    <ul class="list-disc pl-5">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Cash -->
                                <div class="payment-option">
                                    <input type="radio" name="metode_pembayaran" value="cash" id="cash"
                                        class="hidden payment-radio" required>
                                    <label for="cash"
                                        class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition-colors">
                                        <div
                                            class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">Cash</div>
                                            <div class="text-sm text-gray-600">Pay at the cinema counter</div>
                                        </div>
                                    </label>
                                </div>

                                <!-- Transfer -->
                                <div class="payment-option">
                                    <input type="radio" name="metode_pembayaran" value="transfer" id="transfer"
                                        class="hidden payment-radio" required>
                                    <label for="transfer"
                                        class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition-colors">
                                        <div
                                            class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">Bank Transfer</div>
                                            <div class="text-sm text-gray-600">Transfer via bank</div>
                                        </div>
                                    </label>
                                </div>

                                <!-- QRIS -->
                                <div class="payment-option">
                                    <input type="radio" name="metode_pembayaran" value="qris" id="qris"
                                        class="hidden payment-radio" required>
                                    <label for="qris"
                                        class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition-colors">
                                        <div
                                            class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">QRIS</div>
                                            <div class="text-sm text-gray-600">Scan QR code to pay</div>
                                        </div>
                                    </label>
                                </div>

                                <!-- Debit -->
                                <div class="payment-option">
                                    <input type="radio" name="metode_pembayaran" value="debit" id="debit"
                                        class="hidden payment-radio" required>
                                    <label for="debit"
                                        class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition-colors">
                                        <div
                                            class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">Debit Card</div>
                                            <div class="text-sm text-gray-600">Pay with debit card</div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="mt-8 flex justify-end space-x-4">
                                <a href="{{ route('pemesanan.seats', [$film, $jadwalTayang]) }}"
                                    class="bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-700 transition">
                                    Back to Seat Selection
                                </a>
                                <!-- make the submit button enabled by default to avoid JS-only blocker -->
                                <button type="submit" id="payButton"
                                    class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                                    Complete Payment
                                </button>
                            </div>
                        </form>
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
                            <div class="flex justify-between">
                                <span class="text-gray-600">Seats</span>
                                <span class="font-medium">{{ $seats->pluck('nomor_kursi')->implode(', ') }}</span>
                            </div>

                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between mb-2">
                                    <span class="text-gray-600">Tickets</span>
                                    <span class="font-medium">{{ $seats->count() }}</span>
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
                                    <span>Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
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
            const paymentRadios = document.querySelectorAll('.payment-radio');
            const payButton = document.getElementById('payButton');

            // Payment method selection
            paymentRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Remove selected style from all labels
                    document.querySelectorAll('.payment-option label').forEach(label => {
                        label.classList.remove('border-blue-500', 'bg-blue-50');
                        label.classList.add('border-gray-200');
                    });

                    // Add selected style to current label
                    if (this.checked) {
                        const label = this.nextElementSibling;
                        label.classList.remove('border-gray-200');
                        label.classList.add('border-blue-500', 'bg-blue-50');
                    }

                    // Enable pay button
                    payButton.disabled = false;
                });
            });
        });
    </script>
</x-app-layout>
