<div>
    <!-- Main Content Wrapper -->
    <div class="py-8 px-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Redeem Tiket</h1>
            <p class="text-gray-600 mt-2">Scan atau input kode booking tiket pelanggan</p>
        </div>

        <!-- Alert Messages -->
        @if ($message)
            <div class="mb-6 p-4 rounded-lg border-l-4 transition
                    {{ $messageType === 'success' ? 'bg-green-50 border-green-500 text-green-700' : '' }}
                    {{ $messageType === 'error' ? 'bg-red-50 border-red-500 text-red-700' : '' }}
                    {{ $messageType === 'info' ? 'bg-blue-50 border-blue-500 text-blue-700' : '' }}">
                <div class="flex items-center">
                    <i class="fas {{ $messageType === 'success' ? 'fa-check-circle' : ($messageType === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle') }} mr-3"></i>
                    <span>{{ $message }}</span>
                </div>
            </div>
        @endif

        <!-- Scan Input -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="max-w-md mx-auto">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    <i class="fas fa-barcode mr-2"></i>Scan Barcode atau Input Kode Booking
                </label>
                <div class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg flex gap-2">
                    <input type="text" wire:model.live="scanInput" placeholder="Scan barcode atau ketik kode booking..."
                        autofocus
                        class="flex-1 focus:ring-2 focus:ring-teal-500 focus:border-transparent text-center text-lg font-mono bg-transparent border-0">
                    <button wire:click="openCamera" type="button"
                        class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded font-semibold">
                        <i class="fas fa-camera mr-2"></i>Scan Kamera
                    </button>
                </div>
            </div>
        </div>

        <!-- Camera Modal -->
        @if ($showCameraModal)
            <div class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50" wire:key="camera-modal">
                <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full mx-4 relative">
                    
                    <button wire:click="closeCamera" data-close-camera
                        class="absolute top-4 right-4 z-50 bg-red-600 hover:bg-red-700 text-white p-3 rounded-full shadow-lg transition-all">
                        <i class="fas fa-times text-xl"></i>
                    </button>

                    <div class="bg-teal-600 px-6 py-4 rounded-t-lg">
                        <h2 class="text-xl font-bold text-white">
                            <i class="fas fa-qrcode mr-2"></i>Scan Barcode
                        </h2>
                    </div>
                    
                    <div class="p-6">
                        <div id="qr-reader" class="rounded-lg overflow-hidden"></div>
                        <p class="text-sm text-gray-600 mt-4 text-center">
                            <i class="fas fa-camera mr-2"></i>Arahkan kamera ke barcode/QR code
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Instructions -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <i class="fas fa-lightbulb text-blue-500 mt-1 mr-3"></i>
                <div>
                    <h3 class="font-semibold text-blue-900">Cara Menggunakan</h3>
                    <ul class="text-sm text-blue-800 mt-2 space-y-1">
                        <li>✓ Scan barcode dari tiket pelanggan menggunakan barcode scanner</li>
                        <li>✓ Atau ketik kode booking secara manual</li>
                        <li>✓ Sistem akan menampilkan detail tiket</li>
                        <li>✓ Klik "Redeem" untuk mengubah status tiket menjadi redeemed</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Notes -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Catatan</h2>
            <div class="space-y-3 text-sm text-gray-600">
                <p>
                    <i class="fas fa-check text-green-500 mr-2"></i>
                    Hanya tiket dengan status pembayaran "Lunas" yang bisa di-redeem
                </p>
                <p>
                    <i class="fas fa-check text-green-500 mr-2"></i>
                    Tiket yang sudah di-redeem tidak bisa di-redeem lagi
                </p>
                <p>
                    <i class="fas fa-check text-green-500 mr-2"></i>
                    Pemesanan offline otomatis sudah dalam status lunas
                </p>
            </div>
        </div>

        <!-- Detail Modal (Konfirmasi Redeem) -->
        @if ($showDetailModal && $selectedPemesanan)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                    <div class="sticky top-0 bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4 flex items-center justify-between">
                        <h2 class="text-xl font-bold text-white">Verifikasi Tiket</h2>
                        <button wire:click="closeDetailModal" class="text-white hover:bg-teal-800 p-1 rounded">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="p-6 space-y-6">
                        <div class="bg-teal-50 p-4 rounded-lg border-l-4 border-teal-500">
                            <h3 class="font-bold text-teal-900 mb-2">
                                <i class="fas fa-ticket mr-2"></i>{{ $selectedPemesanan->kode_booking }}
                            </h3>
                            <p class="text-sm text-teal-700">{{ $selectedPemesanan->jadwalTayang->film->judul }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Pelanggan</label>
                                <p class="text-lg font-semibold">{{ $selectedPemesanan->user->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <p class="text-sm">{{ $selectedPemesanan->user->email }}</p>
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <h3 class="text-lg font-semibold mb-3">Detail Pertunjukan</h3>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600">Film</span>
                                    <p class="font-semibold">{{ $selectedPemesanan->jadwalTayang->film->judul }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Tanggal & Waktu</span>
                                    <p class="font-semibold">
                                        {{ \Carbon\Carbon::parse($selectedPemesanan->jadwalTayang->tanggal_tayang)->format('d M Y') }},
                                        {{ \Carbon\Carbon::parse($selectedPemesanan->jadwalTayang->jam_tayang)->format('H:i') }}
                                    </p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Studio</span>
                                    <p class="font-semibold">{{ $selectedPemesanan->jadwalTayang->studio->nama_studio }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Tipe</span>
                                    <p class="font-semibold">{{ strtoupper($selectedPemesanan->jadwalTayang->studio->tipe_studio) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <h3 class="text-lg font-semibold mb-3">Kursi</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($selectedPemesanan->detailPemesanan as $detail)
                                    @if ($detail->kursi)
                                        <span class="bg-teal-100 text-teal-800 px-3 py-1 rounded-full font-semibold">
                                            {{ $detail->kursi->nomor_kursi }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="bg-teal-50 p-4 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-semibold">Total</span>
                                <span class="text-2xl font-bold text-teal-600">
                                    Rp {{ number_format($selectedPemesanan->total_harga, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <div class="border-t pt-4 flex gap-3">
                            <button wire:click="closeDetailModal"
                                class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition">
                                Batal
                            </button>
                            <button wire:click="redeemTiket"
                                class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                                <i class="fas fa-check mr-2"></i>Redeem Tiket
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Ticket Modal (Untuk Print) -->
        @if ($showTicketModal && $redeemedPemesanan)
            <div class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50" id="ticket-print-modal">
                <div class="bg-white rounded-lg shadow-2xl max-w-4xl w-full mx-4 max-h-[95vh] overflow-y-auto">
                    
                    <!-- Modal Header - No Print -->
                    <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4 flex items-center justify-between no-print">
                        <div>
                            <h2 class="text-2xl font-bold text-white">
                                <i class="fas fa-check-circle mr-2"></i>Tiket Berhasil Di-Redeem!
                            </h2>
                            <p class="text-green-100 text-sm mt-1">Silakan print tiket untuk pelanggan</p>
                        </div>
                        <button wire:click="closeTicketModal" class="text-white hover:bg-green-700 p-2 rounded-lg transition">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <!-- Ticket Content - Will be Printed -->
                    <div id="ticket-content" class="p-8">
                        
                        <!-- Ticket Card -->
                        <div class="border-4 border-gray-300 rounded-xl overflow-hidden">
                            
                            <!-- Header -->
                            <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6 text-white">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h1 class="text-3xl font-bold">{{ $redeemedPemesanan->jadwalTayang->film->judul }}</h1>
                                        <p class="text-blue-100 mt-2 text-lg">
                                            Booking Code: <span class="font-mono font-bold">{{ $redeemedPemesanan->kode_booking }}</span>
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <div class="bg-white bg-opacity-20 rounded-lg px-4 py-2 text-sm font-bold">
                                            {{ strtoupper($redeemedPemesanan->jadwalTayang->studio->tipe_studio) }}
                                        </div>
                                        <div class="mt-2 bg-green-500 text-white px-3 py-1 rounded-lg text-sm font-bold">
                                            ✓ REDEEMED
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Body -->
                            <div class="p-6 bg-white">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    
                                    <!-- Left Column -->
                                    <div class="space-y-4">
                                        <div>
                                            <h3 class="text-sm font-bold text-gray-500 uppercase">Pelanggan</h3>
                                            <p class="text-xl font-semibold">{{ $redeemedPemesanan->user->name }}</p>
                                        </div>

                                        <div>
                                            <h3 class="text-sm font-bold text-gray-500 uppercase">Tanggal & Waktu</h3>
                                            <p class="text-lg font-semibold">
                                                {{ \Carbon\Carbon::parse($redeemedPemesanan->jadwalTayang->tanggal_tayang)->format('l, d M Y') }}
                                            </p>
                                            <p class="text-lg font-semibold">
                                                {{ \Carbon\Carbon::parse($redeemedPemesanan->jadwalTayang->jam_tayang)->format('H:i') }} WIB
                                            </p>
                                        </div>

                                        <div>
                                            <h3 class="text-sm font-bold text-gray-500 uppercase">Studio</h3>
                                            <p class="text-lg font-semibold">{{ $redeemedPemesanan->jadwalTayang->studio->nama_studio }}</p>
                                        </div>
                                    </div>

                                    <!-- Right Column -->
                                    <div class="space-y-4">
                                        <div>
                                            <h3 class="text-sm font-bold text-gray-500 uppercase">Kursi</h3>
                                            <div class="flex flex-wrap gap-2 mt-2">
                                                @foreach ($redeemedPemesanan->detailPemesanan as $detail)
                                                    @if ($detail->kursi)
                                                        <span class="bg-blue-600 text-white px-4 py-2 rounded-lg font-bold text-lg">
                                                            {{ $detail->kursi->nomor_kursi }}
                                                        </span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>

                                        <div>
                                            <h3 class="text-sm font-bold text-gray-500 uppercase">Jumlah Tiket</h3>
                                            <p class="text-lg font-semibold">{{ $redeemedPemesanan->jumlah_tiket }} Tiket</p>
                                        </div>

                                        <div class="bg-teal-50 p-4 rounded-lg border-l-4 border-teal-500">
                                            <div class="flex justify-between items-center">
                                                <span class="text-lg font-bold">Total Harga</span>
                                                <span class="text-2xl font-bold text-teal-600">
                                                    Rp {{ number_format($redeemedPemesanan->total_harga, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- QR Code -->
                                <div class="border-t-2 border-dashed pt-6 mt-6">
                                    <div class="text-center">
                                        <h3 class="text-lg font-bold text-gray-700 mb-4">
                                            <i class="fas fa-qrcode mr-2"></i>QR CODE TIKET
                                        </h3>
                                        
                                        <div class="bg-gray-100 p-6 rounded-xl inline-block">
                                            @php
                                                $qrCode = new \Endroid\QrCode\QrCode(
                                                    data: $redeemedPemesanan->kode_booking,
                                                    encoding: new \Endroid\QrCode\Encoding\Encoding('UTF-8'),
                                                    errorCorrectionLevel: \Endroid\QrCode\ErrorCorrectionLevel::High,
                                                    size: 250,
                                                    margin: 10
                                                );
                                                $writer = new \Endroid\QrCode\Writer\PngWriter();
                                                $result = $writer->write($qrCode);
                                                $qrBase64 = base64_encode($result->getString());
                                            @endphp
                                            
                                            <img src="data:image/png;base64,{{ $qrBase64 }}" 
                                                 alt="QR Code" 
                                                 width="250" 
                                                 height="250"
                                                 class="mx-auto">
                                            
                                            <p class="text-sm font-mono font-bold mt-3 tracking-wider">
                                                {{ $redeemedPemesanan->kode_booking }}
                                            </p>
                                        </div>

                                        <p class="text-sm text-gray-600 mt-4">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            Tunjukkan tiket ini saat memasuki studio
                                        </p>
                                    </div>
                                </div>

                                <!-- Footer Info -->
                                <div class="mt-6 pt-6 border-t text-center text-sm text-gray-600">
                                    <p class="font-semibold">Terima kasih telah memilih bioskop kami!</p>
                                    <p class="mt-1">Tiket di-redeem pada: {{ \Carbon\Carbon::parse($redeemedPemesanan->tanggal_redeem)->format('d M Y, H:i') }} WIB</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons - No Print -->
                    <div class="bg-gray-50 px-6 py-4 flex gap-3 border-t no-print">
                        <button onclick="window.print()"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition inline-flex items-center justify-center gap-2">
                            <i class="fas fa-print text-xl"></i>
                            <span>Print Tiket</span>
                        </button>
                        
                        <button wire:click="closeTicketModal"
                            class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg transition inline-flex items-center justify-center gap-2">
                            <i class="fas fa-times text-xl"></i>
                            <span>Tutup</span>
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Scripts and Styles -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    
    <style>
    @media print {
        body * {
            visibility: hidden;
        }
        
        #ticket-content,
        #ticket-content * {
            visibility: visible;
        }
        
        #ticket-content {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        
        .no-print {
            display: none !important;
        }
        
        @page {
            margin: 1cm;
        }
    }
    </style>

    <script>
        let html5QrCode = null;

        document.addEventListener('livewire:initialized', () => {
            Livewire.on('camera-opened', () => {
                setTimeout(() => {
                    startScanner();
                }, 300);
            });

            Livewire.on('camera-closed', () => {
                stopScanner();
            });
        });

        async function startScanner() {
            const readerElement = document.getElementById('qr-reader');
            
            if (!readerElement) {
                console.error('QR reader element tidak ditemukan!');
                return;
            }

            readerElement.innerHTML = '';

            try {
                html5QrCode = new Html5Qrcode("qr-reader");

                const config = { 
                    fps: 10,
                    qrbox: { width: 250, height: 250 },
                    aspectRatio: 1.0
                };

                await html5QrCode.start(
                    { facingMode: "environment" },
                    config,
                    (decodedText, decodedResult) => {
                        console.log('Code detected:', decodedText);
                        @this.set('scanInput', decodedText);
                        stopScanner();
                        @this.call('closeCamera');
                    },
                    (errorMessage) => {
                        // Error scanning (normal)
                    }
                );

            } catch (error) {
                console.error('Error starting scanner:', error);
                alert('Tidak dapat mengakses kamera: ' + error);
            }
        }

        async function stopScanner() {
            if (html5QrCode) {
                try {
                    await html5QrCode.stop();
                    html5QrCode.clear();
                    html5QrCode = null;
                } catch (err) {
                    console.error('Error stopping scanner:', err);
                }
            }
        }

        window.addEventListener('beforeunload', () => {
            stopScanner();
        });
    </script>
</div>