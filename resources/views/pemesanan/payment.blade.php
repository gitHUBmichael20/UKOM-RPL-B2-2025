<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Konfirmasi & Pembayaran') }}
        </h2>
    </x-slot>

    <!-- Progress Steps - Responsive -->
    <div
         class="bg-white rounded-lg shadow-sm p-4 sm:p-6 mt-4 sm:mt-8 mb-4 sm:mb-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Desktop View -->
        <div class="hidden md:flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <div
                     class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center font-semibold text-sm">
                    ✓
                </div>
                <span class="font-medium text-green-600 text-sm lg:text-base">Film & Jadwal</span>
            </div>
            <div class="h-1 w-8 lg:w-12 bg-green-500"></div>
            <div class="flex items-center space-x-2">
                <div
                     class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center font-semibold text-sm">
                    ✓
                </div>
                <span class="font-medium text-green-600 text-sm lg:text-base">Pilih Kursi</span>
            </div>
            <div class="h-1 w-8 lg:w-12 bg-green-500"></div>
            <div class="flex items-center space-x-2">
                <div
                     class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">
                    3
                </div>
                <span class="font-medium text-blue-600 text-sm lg:text-base">Pembayaran</span>
            </div>
            <div class="h-1 w-8 lg:w-12 bg-gray-300"></div>
            <div class="flex items-center space-x-2">
                <div
                     class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-semibold text-sm">
                    4
                </div>
                <span class="font-medium text-gray-500 text-sm lg:text-base">Tiket Anda</span>
            </div>
        </div>

        <!-- Mobile View -->
        <div class="flex md:hidden justify-between items-center">
            <div class="flex flex-col items-center flex-1">
                <div
                     class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center font-semibold text-xs">
                    ✓
                </div>
                <span class="font-medium text-green-600 text-xs mt-1 text-center">Film & Jadwal</span>
            </div>
            <div class="h-0.5 w-6 bg-green-500 -mt-4"></div>
            <div class="flex flex-col items-center flex-1">
                <div
                     class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center font-semibold text-xs">
                    ✓
                </div>
                <span class="font-medium text-green-600 text-xs mt-1 text-center">Pilih Kursi</span>
            </div>
            <div class="h-0.5 w-6 bg-green-500 -mt-4"></div>
            <div class="flex flex-col items-center flex-1">
                <div
                     class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-xs">
                    3
                </div>
                <span class="font-medium text-blue-600 text-xs mt-1 text-center">Pembayaran</span>
            </div>
            <div class="h-0.5 w-6 bg-gray-300 -mt-4"></div>
            <div class="flex flex-col items-center flex-1">
                <div
                     class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-semibold text-xs">
                    4
                </div>
                <span class="font-medium text-gray-500 text-xs mt-1 text-center">Tiket Anda</span>
            </div>
        </div>
    </div>

    <div class="py-4 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                <!-- Left Column -->
                <div class="lg:col-span-2 space-y-4 sm:space-y-6 lg:space-y-8">
                    <!-- Movie Details Card - Enhanced & Responsive (Mirip dengan Pilih Kursi) -->
                    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
                        <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-3 sm:mb-4">Detail Film</h2>
                        <div class="flex flex-col sm:flex-row gap-4 sm:gap-6">
                            <!-- Poster -->
                            <div class="flex-shrink-0">
                                <img src="{{ $film->poster ? asset('storage/' . $film->poster) : asset('storage/default_poster.png') }}"
                                     alt="{{ $film->judul }}"
                                     class="w-24 h-36 sm:w-32 sm:h-48 md:w-40 md:h-60 object-cover rounded-lg shadow-md mx-auto sm:mx-0">
                            </div>

                            <!-- Movie Details -->
                            <div class="flex-grow">
                                <div class="w-full">
                                    <!-- Judul -->
                                    <h3
                                        class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 text-center sm:text-left">
                                        {{ $film->judul }}
                                    </h3>

                                    <!-- Rating, Genre, Durasi -->
                                    <div
                                         class="flex flex-wrap items-center justify-center sm:justify-start gap-2 sm:gap-3 mt-2">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded">
                                            {{ $film->rating }}
                                        </span>
                                        <span class="text-xs sm:text-sm text-gray-600">
                                            {{ $film->genres ? $film->genres->pluck('nama_genre')->implode(', ') : 'No genres' }}
                                        </span>
                                        <span class="text-xs sm:text-sm text-gray-600">• {{ $film->durasi }} min</span>
                                    </div>

                                    <!-- Sutradara & Tahun Rilis -->
                                    <div
                                         class="mt-3 sm:mt-4 grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3 text-xs sm:text-sm">
                                        <div class="text-center sm:text-left">
                                            <span class="text-gray-500">Sutradara:</span>
                                            <span class="ml-2 font-medium">{{ $film->sutradara->nama_sutradara }}</span>
                                        </div>
                                        <div class="text-center sm:text-left">
                                            <span class="text-gray-500">Tahun Rilis:</span>
                                            <span class="ml-2 font-medium">{{ $film->tahun_rilis }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Screening Details - Responsive -->
                    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
                        <h2 class="text-base sm:text-lg md:text-xl font-bold text-gray-900 mb-3 sm:mb-4">Detail
                            Pemesanan</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div class="border border-gray-200 rounded-lg p-3 sm:p-4">
                                <div class="text-gray-500 text-xs sm:text-sm mb-1">Tanggal & Waktu</div>
                                <div class="font-semibold text-sm sm:text-base">
                                    {{ \Carbon\Carbon::parse($jadwalTayang->tanggal_tayang)->locale('id')->isoFormat('dddd, D MMM YYYY') }}
                                </div>
                                <div class="font-semibold text-sm sm:text-base text-blue-600">
                                    {{ \Carbon\Carbon::parse($jadwalTayang->jam_tayang)->format('H:i') }} WIB
                                </div>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-3 sm:p-4">
                                <div class="text-gray-500 text-xs sm:text-sm mb-1">Studio</div>
                                <div class="font-semibold text-sm sm:text-base">{{ $jadwalTayang->studio->nama_studio }}
                                </div>
                                <div class="text-xs sm:text-sm text-gray-600 mt-1">
                                    {{ strtoupper($jadwalTayang->studio->tipe_studio) }}</div>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-3 sm:p-4 sm:col-span-2">
                                <div class="text-gray-500 text-xs sm:text-sm mb-2">Kursi yang Dipilih</div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($seats as $seat)
                                        <span
                                              class="bg-blue-100 text-blue-800 px-2 sm:px-3 py-1 rounded-full font-semibold text-xs sm:text-sm">
                                            {{ $seat->nomor_kursi }}
                                        </span>
                                    @endforeach
                                </div>
                                <div class="mt-2 text-xs sm:text-sm text-gray-600">
                                    Total: <span class="font-semibold">{{ $seats->count() }} tiket</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Button - Responsive -->
                    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 lg:p-8 text-center">
                        <div class="mb-6 sm:mb-8">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4">Konfirmasi Pembayaran
                            </h2>
                            <p class="text-sm sm:text-base text-gray-600 max-w-2xl mx-auto px-2">
                                Klik tombol di bawah untuk melanjutkan ke pembayaran aman via
                                <strong>Midtrans</strong>.<br class="hidden sm:block">
                                Kamu bisa bayar dengan:
                            </p>
                            <div class="flex flex-wrap justify-center gap-2 sm:gap-4 mt-4 sm:mt-6 text-lg sm:text-2xl">
                                <span title="QRIS"
                                      class="text-base sm:text-2xl">💳 QRIS</span>
                                <span title="GoPay"
                                      class="text-base sm:text-2xl">🟢 GoPay</span>
                                <span title="ShopeePay"
                                      class="text-base sm:text-2xl">🟠 ShopeePay</span>
                                <span title="DANA"
                                      class="text-base sm:text-2xl">🔵 DANA</span>
                                <span title="OVO"
                                      class="text-base sm:text-2xl">🟣 OVO</span>
                                <span title="Kartu Kredit/Debit"
                                      class="text-base sm:text-2xl">💳 Credit</span>
                                <span title="Virtual Account"
                                      class="text-base sm:text-2xl">🏦 Bank</span>
                            </div>
                        </div>

                        <!-- Payment Button -->
                        <button type="button"
                                id="payButton"
                                class="w-full sm:w-auto inline-flex items-center justify-center px-6 sm:px-12 py-4 sm:py-5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-base sm:text-xl font-bold rounded-xl shadow-lg hover:from-indigo-700 hover:to-purple-700 transform hover:scale-105 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed active:scale-95">
                            <i class="fas fa-credit-card text-xl sm:text-2xl mr-2 sm:mr-3"></i>
                            <span id="buttonText"
                                  class="text-sm sm:text-base lg:text-xl">Lanjut ke Pembayaran • Rp
                                {{ number_format($totalHarga, 0, ',', '.') }}</span>
                        </button>
                    </div>

                    <!-- Back Link -->
                    <div class="text-center pb-4">
                        <a href="{{ route('pemesanan.seats', [$film, $jadwalTayang]) }}"
                           class="text-sm sm:text-base text-gray-600 hover:text-gray-900 underline inline-flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Pilih Kursi
                        </a>
                    </div>
                </div>

                <!-- Right Column - Responsive -->
                <div class="space-y-4 sm:space-y-6 lg:sticky lg:top-6 lg:self-start">
                    <!-- Payment Summary -->
                    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
                        <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-3 sm:mb-4">Ringkasan Pembayaran</h2>
                        <div class="space-y-3 sm:space-y-4">
                            <div class="flex justify-between text-sm sm:text-base">
                                <span class="text-gray-600">Harga Tiket</span>
                                <span class="font-medium">Rp {{ number_format($hargaTiket->harga, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm sm:text-base">
                                <span class="text-gray-600">Jumlah Tiket</span>
                                <span class="font-medium">{{ $seats->count() }} tiket</span>
                            </div>
                            <div class="flex justify-between text-sm sm:text-base">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                            </div>
                            <div class="border-t-2 border-gray-200 pt-3 sm:pt-4">
                                <div class="flex justify-between text-lg sm:text-xl font-bold">
                                    <span>Total Bayar</span>
                                    <span class="text-indigo-600">Rp
                                        {{ number_format($totalHarga, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Info Card -->
                    <div class="bg-blue-50 rounded-lg border border-blue-200 p-4">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-info-circle text-blue-600 text-lg mt-0.5"></i>
                            <div class="text-xs sm:text-sm text-blue-900">
                                <p class="font-semibold mb-1">Informasi Penting:</p>
                                <ul class="space-y-1 text-blue-800">
                                    <li>• Tiket tidak dapat direfund</li>
                                    <li>• Datang 15 menit sebelum film dimulai</li>
                                    <li>• Tunjukkan QR code di kasir</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Midtrans Snap Script -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script>
        const payButton = document.getElementById('payButton');
        const buttonText = document.getElementById('buttonText');
        const storeUrl = '{{ route("pemesanan.store", [$film, $jadwalTayang]) }}';
        const csrfToken = '{{ csrf_token() }}';

        payButton.addEventListener('click', async function () {
            // Disable button
            payButton.disabled = true;
            buttonText.textContent = 'Memproses pembayaran...';

            try {
                // 1. Create booking & get snap token via AJAX
                const response = await fetch(storeUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (!data.success) {
                    throw new Error(data.message || 'Gagal membuat pemesanan');
                }

                // 2. Langsung trigger Midtrans Snap popup
                snap.pay(data.snap_token, {
                    onSuccess: function (result) {
                        console.log('Payment success:', result);
                        window.location.href = data.success_url;
                    },
                    onPending: function (result) {
                        console.log('Payment pending:', result);
                        alert('Menunggu pembayaran Anda. Silakan selesaikan pembayaran.');
                        window.location.href = data.bookings_url;
                    },
                    onError: function (result) {
                        console.log('Payment error:', result);
                        alert('Pembayaran gagal! Silakan coba lagi.');
                        payButton.disabled = false;
                        buttonText.textContent = 'Lanjut ke Pembayaran • Rp {{ number_format($totalHarga, 0, ',', '.') }}';
                    },
                    onClose: function () {
                        console.log('Payment popup closed');
                        alert('Anda menutup popup pembayaran. Silakan lanjutkan dari halaman riwayat.');
                        window.location.href = data.bookings_url;
                    }
                });

            } catch (error) {
                console.error('Error:', error);
                alert(error.message || 'Terjadi kesalahan. Silakan coba lagi.');
                payButton.disabled = false;
                buttonText.textContent = 'Lanjut ke Pembayaran • Rp {{ number_format($totalHarga, 0, ',', '.') }}';
            }
        });
    </script>
</x-app-layout>