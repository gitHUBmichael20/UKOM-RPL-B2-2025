<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Your Ticket') }}
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
                        <span class="font-medium text-green-600">Film & Jadwal</span>
                    </div>
                    <div class="h-1 w-12 bg-green-500"></div>
                    <div class="flex items-center space-x-2">
                        <div
                            class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center font-semibold">
                            ✓
                        </div>
                        <span class="font-medium text-green-600">Pilih Kursi</span>
                    </div>
                    <div class="h-1 w-12 bg-green-500"></div>
                    <div class="flex items-center space-x-2">
                        <div
                            class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center font-semibold">
                            ✓
                        </div>
                        <span class="font-medium text-green-600">Pembayaran</span>
                    </div>
                    <div class="h-1 w-12 bg-green-500"></div>
                    <div class="flex items-center space-x-2">
                        <div
                            class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center font-semibold">
                            ✓
                        </div>
                        <span class="font-medium text-green-600">Tiket Anda</span>
                    </div>
                </div>
            </div>

            <!-- Ticket -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border-2 border-gray-200">
                <!-- Ticket Header -->
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6 text-white">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-2xl font-bold">{{ $pemesanan->jadwalTayang->film->judul }}</h1>
                            <p class="text-blue-100 mt-1">Booking Code: <span
                                    class="font-mono font-bold">{{ $pemesanan->kode_booking }}</span></p>
                        </div>
                        <div class="text-right">
                            <div class="bg-white bg-opacity-20 rounded-lg px-3 py-1 text-sm">
                                {{ strtoupper($pemesanan->jadwalTayang->studio->tipe_studio) }}
                            </div>
                            <p class="mt-2 text-blue-100">Status:
                                <span
                                    class="font-semibold capitalize {{ $pemesanan->status_pembayaran == 'lunas' ? 'text-green-300' : 'text-yellow-300' }}">
                                    {{ $pemesanan->status_pembayaran }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Ticket Body -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-500">DATE & TIME</h3>
                                <p class="text-lg font-semibold">
                                    {{ \Carbon\Carbon::parse($pemesanan->jadwalTayang->tanggal_tayang)->format('l, M j, Y') }}
                                    • {{ \Carbon\Carbon::parse($pemesanan->jadwalTayang->jam_tayang)->format('g:i A') }}
                                </p>
                            </div>

                            <div>
                                <h3 class="text-sm font-semibold text-gray-500">STUDIO</h3>
                                <p class="text-lg font-semibold">{{ $pemesanan->jadwalTayang->studio->nama_studio }}</p>
                            </div>

                            <div>
                                <h3 class="text-sm font-semibold text-gray-500">SEATS</h3>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @foreach ($pemesanan->detailPemesanan as $detail)
                                        @if ($detail->kursi)
                                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-semibold">
                                                {{ $detail->kursi->nomor_kursi }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-500">BOOKING DETAILS</h3>
                                <div class="mt-2 space-y-2">
                                    <div class="flex justify-between">
                                        <span>Booking Date:</span>
                                        <span
                                            class="font-semibold">{{ \Carbon\Carbon::parse($pemesanan->created_at)->format('M j, Y g:i A') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Tickets:</span>
                                        <span class="font-semibold">{{ $pemesanan->jumlah_tiket }} tickets</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Payment Method:</span>
                                        <span
                                            class="font-semibold capitalize">{{ $pemesanan->metode_pembayaran }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Type:</span>
                                        <span class="font-semibold capitalize">{{ $pemesanan->jenis_pemesanan }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="border-t pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-semibold">Total Amount</span>
                                    <span class="text-2xl font-bold text-blue-600">Rp
                                        {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

<!-- QR Code Section -->
<div class="mt-8 border-t pt-6">
    <div class="text-center">
        <h3 class="text-sm font-semibold text-gray-600 mb-4">
            <i class="fas fa-qrcode mr-2"></i>SCAN THIS QR CODE AT THE CINEMA
        </h3>
        
        <div class="bg-gray-100 p-6 rounded-lg inline-block" id="qr-container">
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
                 alt="QR Code" 
                 width="300" 
                 height="300" 
                 class="mx-auto"
                 style="display: block; max-width: 300px; height: auto;">
            
            <div class="text-sm font-mono font-bold tracking-wider mt-4">
                {{ $pemesanan->kode_booking }}
            </div>
        </div>
        
        <div class="mt-6 flex justify-center gap-3 no-print">
            <button onclick="downloadTicketFixed()"
                    id="download-btn"
                    class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg font-semibold transition inline-flex items-center gap-2">
                <i class="fas fa-download"></i>
                Download Ticket
            </button>
            
            <button onclick="window.print()"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition inline-flex items-center gap-2">
                <i class="fas fa-print"></i>
                Print Ticket
            </button>
        </div>
        
        <p class="text-sm text-gray-600 mt-4">
            <i class="fas fa-info-circle mr-2"></i>
            Show this QR code to the cashier to confirm and redeem your ticket
        </p>
    </div>
</div>
            <!-- Action Buttons -->
            <div class="mt-6 flex justify-center space-x-4 no-print">
                <a href="{{ route('dashboard') }}"
                    class="bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-700 transition">
                    Back to Dashboard
                </a>
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
        .rounded-xl {
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
        
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Preparing...';
        btn.disabled = true;
        
        try {
            await new Promise(resolve => setTimeout(resolve, 300));
            
            const ticket = document.querySelector('.bg-white.rounded-xl.shadow-lg');
            const qrImg = document.getElementById('qr-image');
            
            // Pastikan QR loaded
            if (!qrImg.complete) {
                await new Promise((resolve) => {
                    qrImg.onload = resolve;
                });
            }
            
            console.log('Capturing ticket...');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Capturing...';
            
            const canvas = await html2canvas(ticket, {
                backgroundColor: '#ffffff',
                scale: 2,
                useCORS: true,
                allowTaint: true,
                logging: false,
                imageTimeout: 0,
                onclone: function(clonedDoc) {
                    const clonedQr = clonedDoc.getElementById('qr-image');
                    if (clonedQr) {
                        clonedQr.style.display = 'block';
                        clonedQr.style.visibility = 'visible';
                    }
                }
            });
            
            console.log('Canvas created:', canvas.width, 'x', canvas.height);
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
            
            // METHOD BARU: Konversi langsung tanpa blob
            // Ini lebih compatible dengan browser security
            const dataURL = canvas.toDataURL('image/jpeg', 0.95);
            
            // Create download link dengan cara yang lebih aman
            const link = document.createElement('a');
            link.href = dataURL;
            link.download = 'Ticket_{{ $pemesanan->kode_booking }}.jpg';
            
            // Trick: Append ke body dulu baru click
            document.body.appendChild(link);
            
            // Use setTimeout untuk avoid browser blocking
            setTimeout(() => {
                link.click();
                
                // Cleanup setelah download
                setTimeout(() => {
                    document.body.removeChild(link);
                }, 100);
                
                btn.innerHTML = '<i class="fas fa-check mr-2"></i>Downloaded!';
                console.log('Download completed successfully');
                
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.disabled = false;
                }, 2000);
            }, 100);
            
        } catch (err) {
            console.error('Download error:', err);
            alert('Download failed: ' + err.message + '\n\nPlease try:\n1. Right-click the ticket → Save image as...\n2. Or use Print button → Save as PDF');
            btn.innerHTML = originalHTML;
            btn.disabled = false;
        }
    }
    
    window.addEventListener('load', function() {
        const qrImg = document.getElementById('qr-image');
        if (qrImg && qrImg.complete && qrImg.naturalHeight > 0) {
            console.log('✓ QR Code loaded successfully');
        }
    });
</script>
</x-app-layout>