@extends('admin.layouts.app')

@section('content')
    <div class="p-6">
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Edit Pemesanan</h2>
            <p class="text-gray-600 mt-1">Update data pemesanan tiket film</p>
        </div>

        <!-- Back Button -->
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('admin.pemesanan.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                Kembali ke Daftar Pemesanan
            </a>
        </div>

        <!-- Edit Form -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6">
                <!-- Booking Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pemesanan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600">Film</p>
                            <p class="font-semibold">{{ $pemesanan->jadwalTayang->film->judul ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Customer</p>
                            <p class="font-semibold">{{ $pemesanan->user->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Kode Booking</p>
                            <p class="font-mono font-semibold">{{ $pemesanan->kode_booking }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Waktu Tayang</p>
                            <p class="font-semibold">
                                {{ $pemesanan->jadwalTayang ? \Carbon\Carbon::parse($pemesanan->jadwalTayang->tanggal_tayang)->format('d M Y') : 'N/A' }}
                                pukul
                                {{ $pemesanan->jadwalTayang ? \Carbon\Carbon::parse($pemesanan->jadwalTayang->jam_tayang)->format('H:i') : 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Studio</p>
                            <p class="font-semibold">{{ $pemesanan->jadwalTayang->studio->nama_studio ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Kursi</p>
                            <p class="font-semibold">
                                @if ($pemesanan->detailPemesanan && $pemesanan->detailPemesanan->count() > 0)
                                    {{ $pemesanan->detailPemesanan->pluck('kursi.nomor_kursi')->join(', ') }}
                                @else
                                    Tidak ada
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Harga</p>
                            <p class="font-semibold text-green-600">Rp
                                {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tanggal Pemesanan</p>
                            <p class="font-semibold">{{ $pemesanan->tanggal_pemesanan->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Edit Form -->
                <form action="{{ route('admin.pemesanan.update', $pemesanan->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Status Pembayaran -->
                        <div>
                            <label for="status_pembayaran" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fa-solid fa-credit-card mr-2"></i>Status Pembayaran
                            </label>
                            <select name="status_pembayaran" id="status_pembayaran"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="pending" {{ $pemesanan->status_pembayaran == 'pending' ? 'selected' : '' }}>
                                    Pending
                                </option>
                                <option value="lunas" {{ $pemesanan->status_pembayaran == 'lunas' ? 'selected' : '' }}>
                                    Lunas
                                </option>
                                <option value="failed" {{ $pemesanan->status_pembayaran == 'failed' ? 'selected' : '' }}>
                                    Gagal
                                </option>
                                <option value="cancelled"
                                    {{ $pemesanan->status_pembayaran == 'cancelled' ? 'selected' : '' }}>
                                    Dibatalkan
                                </option>
                            </select>
                        </div>

                        <!-- Metode Pembayaran -->
                        <div>
                            <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fa-solid fa-money-bill-wave mr-2"></i>Metode Pembayaran
                            </label>
                            <select name="metode_pembayaran" id="metode_pembayaran"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="cash" {{ $pemesanan->metode_pembayaran == 'cash' ? 'selected' : '' }}>
                                    Cash
                                </option>
                                <option value="transfer"
                                    {{ $pemesanan->metode_pembayaran == 'transfer' ? 'selected' : '' }}>
                                    Transfer
                                </option>
                                <option value="qris" {{ $pemesanan->metode_pembayaran == 'qris' ? 'selected' : '' }}>
                                    QRIS
                                </option>
                                <option value="debit" {{ $pemesanan->metode_pembayaran == 'debit' ? 'selected' : '' }}>
                                    Kartu Debit
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="{{ route('admin.pemesanan.index') }}"
                            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                            <i class="fa-solid fa-times mr-2"></i>Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                            <i class="fa-solid fa-save mr-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
