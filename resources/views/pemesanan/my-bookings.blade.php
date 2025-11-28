<x-app-layout>
    <div class="py-4 sm:py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-4 sm:mb-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Daftar Tiket Saya</h1>
                        <p class="text-sm text-gray-600 mt-0.5">Kelola semua pemesanan tiket bioskop Anda</p>
                    </div>
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Cari Film Lain
                    </a>
                </div>
            </div>

            <!-- Empty State -->
            @if ($bookings->isEmpty())
                <div class="text-center py-12 sm:py-16 bg-white rounded-lg shadow-sm">
                    <div
                         class="mx-auto w-16 h-16 sm:w-20 sm:h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-calendar-times text-3xl sm:text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">Belum ada pemesanan</h3>
                    <p class="text-sm text-gray-500 mb-6">Ayo pesan tiket film favoritmu sekarang!</p>
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                        <i class="fas fa-film mr-2"></i>
                        Jelajahi Film
                    </a>
                </div>
            @else
                @php
                    $groupedBookings = $bookings->groupBy(function($booking) {
                        return $booking->jadwalTayang->tanggal_tayang->format('Y-m-d');
                    })->sortKeysDesc();
                @endphp

                <div class="space-y-6">
                    @foreach ($groupedBookings as $date => $dateBookings)
                        <!-- Date Header -->
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-gray-900 mb-3 flex items-center">
                                <i class="far fa-calendar-alt mr-2 text-indigo-600"></i>
                                {{ \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM YYYY') }}
                            </h2>
                            
                            <div class="space-y-3 sm:space-y-4">
                                @foreach ($dateBookings as $booking)
                                    @php
                                        $isPending = $booking->status_pembayaran === 'pending';
                                        $isExpired = $isPending && $booking->expired_at && now()->greaterThan($booking->expired_at);
                                        $canPay = $isPending && !$isExpired;
                                        $canCancel = $isPending && !$isExpired;
                                        $timeLeft = $isPending && $booking->expired_at ? $booking->expired_at->diffForHumans(['parts' => 2]) : null;
                                    @endphp

                                    <div
                                         class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                                        <div class="p-4 sm:p-5">
                                            <!-- Header -->
                                            <div class="flex items-start justify-between gap-3 mb-3">
                                                <div class="flex-1 min-w-0">
                                                    <h3 class="text-base sm:text-lg font-bold text-gray-900 truncate">
                                                        {{ $booking->jadwalTayang->film->judul }}
                                                    </h3>
                                                    <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                                                        <span class="px-2.5 py-0.5 text-xs font-medium rounded-full
                                                                    @if($booking->status_pembayaran === 'lunas') bg-green-100 text-green-800
                                                                    @elseif($booking->status_pembayaran === 'pending') bg-yellow-100 text-yellow-800
                                                                    @elseif($booking->status_pembayaran === 'batal') bg-red-100 text-red-800
                                                                    @else bg-gray-100 text-gray-800 @endif">
                                                            {{ $booking->status_pembayaran == 'lunas' ? 'Lunas' : ucfirst($booking->status_pembayaran) }}
                                                        </span>

                                                        @if($isPending && $timeLeft)
                                                            <span class="text-xs text-gray-500 flex items-center">
                                                                <i class="far fa-clock mr-1"></i>
                                                                <span class="font-medium text-orange-600"
                                                                      x-data
                                                                      x-init="startCountdown('{{ $booking->expired_at }}', $el)">
                                                                </span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <span class="text-base sm:text-lg font-bold text-gray-900 whitespace-nowrap">
                                                    Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                                                </span>
                                            </div>

                                            <!-- Info Grid -->
                                            <div class="grid grid-cols-2 gap-2 sm:gap-3 text-xs sm:text-sm mb-3">
                                                <div class="flex items-center gap-1.5 text-gray-600">
                                                    <i class="far fa-calendar w-4"></i>
                                                    <span
                                                          class="truncate">{{ $booking->jadwalTayang->tanggal_tayang->format('d M Y') }}</span>
                                                </div>
                                                <div class="flex items-center gap-1.5 text-gray-600">
                                                    <i class="far fa-clock w-4"></i>
                                                    <span>{{ \Carbon\Carbon::createFromFormat('H:i:s', $booking->jadwalTayang->jam_tayang)->format('H:i') }}</span>
                                                </div>
                                                <div class="flex items-center gap-1.5 text-gray-600">
                                                    <i class="fas fa-ticket-alt w-4"></i>
                                                    <span>{{ $booking->jumlah_tiket }} tiket</span>
                                                </div>
                                                <div class="flex items-center gap-1.5 text-gray-600">
                                                    <i class="fas fa-chair w-4"></i>
                                                    <span
                                                          class="truncate">{{ $booking->detailPemesanan->pluck('kursi.nomor_kursi')->join(', ') }}</span>
                                                </div>
                                            </div>

                                            <!-- Booking Code -->
                                            <div class="mb-3">
                                                <span class="inline-block font-mono text-xs bg-gray-100 px-2.5 py-1 rounded">
                                                    {{ $booking->kode_booking }}
                                                </span>
                                            </div>

                                            <!-- Actions -->
                                            <div class="flex flex-wrap gap-2">
                                                @if($booking->status_pembayaran === 'lunas')
                                                    <a href="{{ route('pemesanan.ticket', $booking) }}"
                                                       class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition">
                                                        <i class="fas fa-ticket-alt mr-2"></i>
                                                        Lihat Tiket
                                                    </a>
                                                @endif

                                                @if($booking->status_pembayaran === 'batal')
                                                    <a href="{{ route('pemesanan.ticket', $booking) }}"
                                                       class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">
                                                        <i class="fas fa-info-circle mr-2"></i>
                                                        Detail
                                                    </a>
                                                @endif

                                                @if($canPay)
                                                    <a href="{{ route('payment.continue', $booking) }}"
                                                    class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition">
                                                        <i class="fas fa-credit-card mr-2"></i>
                                                        Bayar Sekarang
                                                    </a>
                                                @endif

                                                @if($canCancel)
                                                    <button onclick="cancelBooking('{{ $booking->id }}', this)"
                                                            class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2 border border-red-300 text-red-700 text-sm font-medium rounded-lg hover:bg-red-50 transition">
                                                        <i class="fas fa-times mr-2"></i>
                                                        Batalkan
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Stats -->
                <div class="mt-6 sm:mt-8 grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3">
                    <div
                         class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200 text-center">
                        <p class="text-xs font-medium text-blue-700">Total</p>
                        <p class="text-2xl font-bold text-blue-900 mt-1">{{ $bookings->count() }}</p>
                    </div>
                    <div
                         class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200 text-center">
                        <p class="text-xs font-medium text-green-700">Lunas</p>
                        <p class="text-2xl font-bold text-green-900 mt-1">
                            {{ $bookings->where('status_pembayaran', 'lunas')->count() }}</p>
                    </div>
                    <div
                         class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-4 border border-yellow-200 text-center">
                        <p class="text-xs font-medium text-yellow-700">Pending</p>
                        <p class="text-2xl font-bold text-yellow-900 mt-1">
                            {{ $bookings->where('status_pembayaran', 'pending')->count() }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-lg p-4 border border-red-200 text-center">
                        <p class="text-xs font-medium text-red-700">Batal</p>
                        <p class="text-2xl font-bold text-red-900 mt-1">
                            {{ $bookings->where('status_pembayaran', 'batal')->count() }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Alpine.js untuk countdown + Snap.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"
            defer></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script>
        function startCountdown(expiredAt, el) {
            const end = new Date(expiredAt).getTime();

            const timer = setInterval(() => {
                const now = new Date().getTime();
                const distance = end - now;

                if (distance < 0) {
                    clearInterval(timer);
                    el.textContent = 'Waktu habis';
                    el.closest('.bg-white').querySelector('button[onclick*="payNow"]')?.remove();
                    return;
                }

                const hours = Math.floor(distance / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                el.textContent = `${hours}j ${minutes}m ${seconds}d`;
            }, 1000);
        }

        function payNow(snapToken) {
            snap.pay(snapToken, {
                onSuccess: () => location.href = '{{ route("pemesanan.my-bookings") }}',
                onPending: () => location.reload(),
                onError: () => alert('Pembayaran gagal, coba lagi'),
                onClose: () => { }
            });
        }

async function cancelBooking(bookingId, button) {
        const originalHTML = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Membatalkan...';

        const { isConfirmed } = await Swal.fire({
            title: 'Batalkan Pesanan?',
            text: 'Kursi akan dilepaskan dan tidak bisa dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Tidak Jadi',
            reverseButtons: true
        });

        if (!isConfirmed) {
            button.disabled = false;
            button.innerHTML = originalHTML;
            return;
        }

        try {
            const response = await fetch(`/pemesanan/cancel/${bookingId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
            });

            const data = await response.json();

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Dibatalkan!',
                    text: 'Pesanan berhasil dibatalkan.',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => location.reload());
            } else {
                throw new Error(data.message || 'Gagal membatalkan pesanan');
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: error.message || 'Terjadi kesalahan jaringan'
            });
            button.disabled = false;
            button.innerHTML = originalHTML;
        }
    }
    </script>
</x-app-layout>