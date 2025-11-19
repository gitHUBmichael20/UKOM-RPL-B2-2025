<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Booking Successful') }}
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
                            class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold">
                            4
                        </div>
                        <span class="font-medium text-blue-600">Your Ticket</span>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <h1 class="text-2xl font-bold text-gray-900 mb-2">Booking Confirmed!</h1>
                <p class="text-gray-600 mb-6">Your booking has been successfully processed.</p>

                <div class="bg-gray-50 rounded-lg p-6 max-w-md mx-auto mb-6">
                    <div class="text-sm text-gray-500">Booking Code</div>
                    <div class="text-2xl font-bold text-gray-900 font-mono">{{ $pemesanan->kode_booking }}</div>
                </div>

                <div class="flex justify-center space-x-4">
                    <a href="{{ route('dashboard') }}"
                        class="bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-700 transition">
                        Back to Dashboard
                    </a>
                    <a href="{{ route('pemesanan.ticket', $pemesanan) }}"
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                        View Ticket
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
