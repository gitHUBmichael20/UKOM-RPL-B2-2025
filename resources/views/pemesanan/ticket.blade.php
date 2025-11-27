<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tiket Anda') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Progress Steps - Responsive -->
            <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 mb-4 sm:mb-8">
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
                             class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center font-semibold text-sm">
                            ✓
                        </div>
                        <span class="font-medium text-green-600 text-sm lg:text-base">Pembayaran</span>
                    </div>
                    <div class="h-1 w-8 lg:w-12 bg-green-500"></div>
                    <div class="flex items-center space-x-2">
                        <div
                             class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center font-semibold text-sm">
                            ✓
                        </div>
                        <span class="font-medium text-green-600 text-sm lg:text-base">Tiket Anda</span>
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
                             class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center font-semibold text-xs">
                            ✓
                        </div>
                        <span class="font-medium text-green-600 text-xs mt-1 text-center">Pembayaran</span>
                    </div>
                    <div class="h-0.5 w-6 bg-green-500 -mt-4"></div>
                    <div class="flex flex-col items-center flex-1">
                        <div
                             class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center font-semibold text-xs">
                            ✓
                        </div>
                        <span class="font-medium text-green-600 text-xs mt-1 text-center">Tiket Anda</span>
                    </div>
                </div>
            </div>

            <!-- Ticket - Responsive -->
            <div class="bg-white rounded-lg sm:rounded-xl shadow-lg overflow-hidden border-2 border-gray-200">
                <!-- Ticket Header - Responsive -->
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-4 sm:p-6 text-white">
                    <div class="flex flex-col sm:flex-row justify-between items-start gap-3 sm:gap-0">
                        <div class="w-full sm:w-auto">
                            <h1 class="text-xl sm:text-2xl font-bold break-words">
                                {{ $pemesanan->jadwalTayang->film->judul }}
                            </h1>
                            <p class="text-blue-100 mt-1 text-sm sm:text-base">
                                Kode Booking: <span class="font-mono font-bold">{{ $pemesanan->kode_booking }}</span>
                            </p>
                        </div>
                        <div
                             class="w-full sm:w-auto sm:text-right flex flex-row sm:flex-col justify-between sm:justify-start items-center sm:items-end gap-2">
                            <div class="bg-white bg-opacity-20 rounded-lg px-3 py-1 text-xs sm:text-sm">
                                {{ strtoupper($pemesanan->jadwalTayang->studio->tipe_studio) }}
                            </div>
                            <p class="text-blue-100 text-sm sm:text-base sm:mt-2">
                                Status:
                                <span class="font-semibold capitalize
                                    @if($pemesanan->status_pembayaran === 'lunas') text-green-300
                                    @elseif($pemesanan->status_pembayaran === 'pending') text-yellow-300
                                    @elseif(in_array($pemesanan->status_pembayaran, ['batal', 'expired'])) text-red-300
                                    @else text-gray-300 @endif">
                                                                {{ $pemesanan->status_pembayaran === 'lunas' ? 'Lunas ✓' :
                                ($pemesanan->status_pembayaran === 'pending' ? 'Menunggu Pembayaran' :
                                    'Dibatalkan') }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Ticket Body - Responsive -->
                <div class="p-4 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Left Column -->
                        <div class="space-y-3 sm:space-y-4">
                            <div>
                                <h3 class="text-xs sm:text-sm font-semibold text-gray-500">TANGGAL & WAKTU</h3>
                                <p class="text-base sm:text-lg font-semibold">
                                    {{ \Carbon\Carbon::parse($pemesanan->jadwalTayang->tanggal_tayang)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                    • {{ \Carbon\Carbon::parse($pemesanan->jadwalTayang->jam_tayang)->format('H:i') }}
                                    WIB
                                </p>
                            </div>
                            <div>
                                <h3 class="text-xs sm:text-sm font-semibold text-gray-500">STUDIO</h3>
                                <p class="text-base sm:text-lg font-semibold">
                                    {{ $pemesanan->jadwalTayang->studio->nama_studio }}
                                </p>
                            </div>
                            <div>
                                <h3 class="text-xs sm:text-sm font-semibold text-gray-500">KURSI</h3>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @foreach ($pemesanan->detailPemesanan as $detail)
                                        @if ($detail->kursi)
                                            <span
                                                  class="bg-blue-100 text-blue-800 px-2 sm:px-3 py-1 rounded-full font-semibold text-sm sm:text-base">
                                                {{ $detail->kursi->nomor_kursi }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-3 sm:space-y-4">
                            <div>
                                <h3 class="text-xs sm:text-sm font-semibold text-gray-500">DETAIL PEMESANAN</h3>
                                <div class="mt-2 space-y-2 text-sm sm:text-base">
                                    <div class="flex justify-between">
                                        <span>Tanggal Booking:</span>
                                        <span
                                              class="font-semibold">{{ \Carbon\Carbon::parse($pemesanan->created_at)->locale('id')->isoFormat('D MMM YYYY, HH:mm') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Jumlah Tiket:</span>
                                        <span class="font-semibold">{{ $pemesanan->jumlah_tiket }} tiket</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Metode Bayar:</span>
                                        <span
                                              class="font-semibold capitalize">{{ $pemesanan->metode_pembayaran }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Jenis:</span>
                                        <span class="font-semibold capitalize">{{ $pemesanan->jenis_pemesanan }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="border-t pt-3 sm:pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-base sm:text-lg font-semibold">Total Pembayaran</span>
                                    <span class="text-xl sm:text-2xl font-bold text-blue-600">Rp
                                        {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- QR Code Section - Responsive -->
                    <div class="mt-6 sm:mt-8 border-t pt-4 sm:pt-6">
                        <div class="text-center">
                            @if($pemesanan->status_pembayaran === 'lunas')
                                <h3 class="text-xs sm:text-sm font-semibold text-gray-600 mb-3 sm:mb-4">
                                    SCAN QR CODE INI DI BIOSKOP
                                </h3>

                                <div class="bg-gray-100 p-4 sm:p-6 rounded-lg inline-block"
                                     id="qr-container">
                                    @php
                                        $qrCode = new \Endroid\QrCode\QrCode(
                                            data: $pemesanan->kode_booking,
                                            encoding: new \Endroid\QrCode\Encoding\Encoding('UTF-8'),
                                            errorCorrectionLevel: \Endroid\QrCode\ErrorCorrectionLevel::High,
                                            size: 300,
                                            margin: 10
                                        );
                                        $writer = new \Endroid\QrCode\Writer\PngWriter();
                                        $result = $writer->write($qrCode);
                                        $qrBase64 = base64_encode($result->getString());
                                    @endphp

                                    <img id="qr-image"
                                         src="data:image/png;base64,{{ $qrBase64 }}"
                                         alt="QR Code Tiket"
                                         class="mx-auto w-48 h-48 sm:w-64 sm:h-64 md:w-80 md:h-80"
                                         style="max-width: 300px;">

                                    <div class="text-xs sm:text-sm font-mono font-bold tracking-wider mt-4">
                                        {{ $pemesanan->kode_booking }}
                                    </div>
                                </div>

                                <div class="mt-6 flex flex-col sm:flex-row justify-center gap-3 no-print">
                                    <button onclick="downloadTicketFixed()"
                                            id="download-btn"
                                            class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg font-semibold transition flex items-center gap-2">
                                        Unduh Tiket
                                    </button>
                                </div>

                                <p class="text-xs sm:text-sm text-gray-600 mt-4">
                                    Tunjukkan QR code ini ke kasir untuk masuk studio
                                </p>

                            @elseif($pemesanan->status_pembayaran === 'pending')
                                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                                    <div class="flex items-center justify-center gap-3">
                                        <svg class="w-10 h-10 text-yellow-600"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div class="text-left">
                                            <p class="font-semibold text-yellow-800">Menunggu Pembayaran</p>
                                            <p class="text-sm text-yellow-700 mt-1">
                                                Silakan selesaikan pembayaran agar tiket & QR code dapat digunakan.
                                            </p>
                                            <a href="{{ route('pemesanan.my-bookings') }}"
                                               class="mt-3 inline-block text-sm font-medium text-yellow-800 underline hover:no-underline">
                                                ← Lanjutkan Pembayaran di Daftar Tiket
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            @elseif(in_array($pemesanan->status_pembayaran, ['batal', 'expired']))
                                <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                                    <div class="flex items-center justify-center gap-3">
                                        <svg class="w-10 h-10 text-red-600"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        <div class="text-left">
                                            <p class="font-semibold text-red-800">Pesanan Dibatalkan</p>
                                            <p class="text-sm text-red-700 mt-1">
                                                Tiket ini sudah dibatalkan dan tidak dapat digunakan.
                                            </p>
                                            <p class="text-xs text-red-600 mt-2">
                                                Kursi telah dilepaskan dan bisa dipesan orang lain.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                            @else
                                <div class="bg-gray-50 border border-gray-300 rounded-xl p-6 text-center">
                                    <p class="text-gray-600">Status tiket: {{ ucfirst($pemesanan->status_pembayaran) }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons - Responsive -->
                    <div class="mt-4 sm:mt-6 flex justify-center no-print px-2 sm:px-0">
                        <a href="{{ route('dashboard') }}"
                           class="bg-gray-600 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg font-semibold hover:bg-gray-700 transition text-sm sm:text-base w-full sm:w-auto text-center">
                            Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {

            .no-print,
            x-slot,
            header,
            nav {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .bg-white {
                background: white !important;
            }

            .rounded-xl,
            .rounded-lg {
                box-shadow: none !important;
                border: 1px solid #e5e7eb !important;
            }
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        async function downloadTicketFixed() {
            const btn = document.getElementById('download-btn');
            const originalHTML = btn.innerHTML;

            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
            btn.disabled = true;

            try {
                await new Promise(resolve => setTimeout(resolve, 300));

                const ticket = document.querySelector('.bg-white.rounded-lg.shadow-lg, .bg-white.rounded-xl.shadow-lg');
                const qrImg = document.getElementById('qr-image');

                // Pastikan QR loaded
                if (!qrImg.complete) {
                    await new Promise((resolve) => {
                        qrImg.onload = resolve;
                    });
                }

                console.log('Mengambil gambar tiket...');
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengambil...';

                const canvas = await html2canvas(ticket, {
                    backgroundColor: '#ffffff',
                    scale: 2,
                    useCORS: true,
                    allowTaint: true,
                    logging: false,
                    imageTimeout: 0,
                    onclone: function (clonedDoc) {
                        const clonedQr = clonedDoc.getElementById('qr-image');
                        if (clonedQr) {
                            clonedQr.style.display = 'block';
                            clonedQr.style.visibility = 'visible';
                        }
                    }
                });

                console.log('Canvas dibuat:', canvas.width, 'x', canvas.height);
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';

                const dataURL = canvas.toDataURL('image/jpeg', 0.95);

                const link = document.createElement('a');
                link.href = dataURL;
                link.download = 'Tiket_{{ $pemesanan->kode_booking }}.jpg';

                document.body.appendChild(link);

                setTimeout(() => {
                    link.click();

                    setTimeout(() => {
                        document.body.removeChild(link);
                    }, 100);

                    btn.innerHTML = '<i class="fas fa-check mr-2"></i>Berhasil!';
                    console.log('Download selesai');

                    setTimeout(() => {
                        btn.innerHTML = originalHTML;
                        btn.disabled = false;
                    }, 2000);
                }, 100);

            } catch (err) {
                console.error('Error download:', err);
                alert('Download gagal: ' + err.message + '\n\nSilakan coba:\n1. Klik kanan pada tiket → Simpan gambar sebagai...\n2. Atau gunakan tombol Cetak → Simpan sebagai PDF');
                btn.innerHTML = originalHTML;
                btn.disabled = false;
            }
        }

        window.addEventListener('load', function () {
            const qrImg = document.getElementById('qr-image');
            if (qrImg && qrImg.complete && qrImg.naturalHeight > 0) {
                console.log('✓ QR Code berhasil dimuat');
            }
        });
    </script>
</x-app-layout>