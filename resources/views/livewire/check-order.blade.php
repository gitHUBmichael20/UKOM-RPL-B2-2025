<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 px-4 sm:px-0">
            <h1 class="text-2xl font-bold text-gray-900">Your Orders</h1>
            <p class="text-gray-600 mt-1">Check your movie bookings and orders</p>
        </div>

        <!-- Orders List -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                @if (count($orders) > 0)
                    <div class="space-y-6">
                        @foreach ($orders as $order)
                            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition">
                                <div class="flex flex-col md:flex-row md:items-start md:justify-between">
                                    <!-- Movie Info -->
                                    <div class="flex-1">
                                        <div class="flex items-start space-x-4">
                                            <!-- Movie Poster -->
                                            <img src="{{ $order->jadwalTayang->film->poster ?? '/placeholder-poster.jpg' }}"
                                                alt="{{ $order->jadwalTayang->film->judul ?? 'Movie' }}"
                                                class="w-20 h-28 object-cover rounded-lg">

                                            <!-- Order Details -->
                                            <div class="flex-1">
                                                <h3 class="text-xl font-bold text-gray-900 mb-2">
                                                    {{ $order->jadwalTayang->film->judul ?? 'Unknown Movie' }}
                                                </h3>

                                                <div class="grid grid-cols-2 gap-4 mb-3">
                                                    <div>
                                                        <p class="text-sm text-gray-500">Order ID</p>
                                                        <p class="font-semibold text-gray-900">
                                                            #{{ $order->kode_booking }}</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm text-gray-500">Rating</p>
                                                        <p class="font-semibold text-gray-900 flex items-center">
                                                            <svg class="w-4 h-4 text-yellow-400 mr-1"
                                                                fill="currentColor" viewBox="0 0 20 20">
                                                                <path
                                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                            </svg>
                                                            {{ $order->jadwalTayang->film->rating ?? 'N/A' }}
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm text-gray-500">Director</p>
                                                        <p class="font-semibold text-gray-900">
                                                            {{ $order->jadwalTayang->film->sutradara->nama_sutradara ?? 'Unknown' }}
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm text-gray-500">Duration</p>
                                                        <p class="font-semibold text-gray-900">
                                                            {{ $order->jadwalTayang->film->durasi ?? 'N/A' }} min</p>
                                                    </div>
                                                </div>

                                                <!-- Booking Details -->
                                                <div class="grid grid-cols-2 gap-4 mb-3">
                                                    <div>
                                                        <p class="text-sm text-gray-500">Tickets</p>
                                                        <p class="font-semibold text-gray-900">
                                                            {{ $order->jumlah_tiket }} seat(s)</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm text-gray-500">Total Price</p>
                                                        <p class="font-semibold text-gray-900">Rp
                                                            {{ number_format($order->total_harga, 0, ',', '.') }}</p>
                                                    </div>
                                                </div>

                                                <!-- Schedule and Status -->
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <p class="text-sm text-gray-500">Show Time</p>
                                                        <p class="font-semibold text-gray-900">
                                                            {{ \Carbon\Carbon::parse($order->jadwalTayang->tanggal_tayang ?? now())->format('d/m/Y') }}
                                                            at
                                                            {{ $order->jadwalTayang->jam_tayang ?? 'N/A' }}
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm text-gray-500">Payment Status</p>
                                                        <span
                                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                                            {{ $order->status_pembayaran === 'lunas'
                                                                ? 'bg-green-100 text-green-800'
                                                                : ($order->status_pembayaran === 'pending'
                                                                    ? 'bg-yellow-100 text-yellow-800'
                                                                    : ($order->status_pembayaran === 'redeemed'
                                                                        ? 'bg-blue-100 text-blue-800'
                                                                        : 'bg-red-100 text-red-800')) }}">
                                                            {{ ucfirst($order->status_pembayaran) }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <!-- Payment Method -->
                                                <div class="mt-3">
                                                    <p class="text-sm text-gray-500">Payment Method</p>
                                                    <p class="font-semibold text-gray-900 capitalize">
                                                        {{ $order->metode_pembayaran }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="mt-4 md:mt-0 md:ml-6 flex space-x-2">
                                        @if ($order->status_pembayaran === 'lunas')
                                            <button
                                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                                                View Ticket
                                            </button>
                                        @endif
                                        @if ($order->status_pembayaran === 'pending')
                                            <button
                                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm">
                                                Cancel
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No orders found</h3>
                        <p class="mt-2 text-gray-500">You haven't made any bookings yet.</p>
                        <div class="mt-6">
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Book Now
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
