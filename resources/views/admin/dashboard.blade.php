@extends('admin.layouts.app')

@section('title', 'Dashboard cinema')
@section('page-title', 'Dashboard cinema')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Message -->
        <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold mb-2">Selamat Datang, {{ auth()->user()->name }}! 👋</h2>
                    <p class="text-red-100">Dashboard Admin Absolute Cinema</p>
                </div>
                <div class="hidden md:block">
                    <i class="fas fa-film fa-6x opacity-20"></i>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- Total Film -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-lg p-3">
                        <i class="fas fa-photo-film text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Film</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalFilm }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Pemesanan Hari Ini -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-lg p-3">
                        <i class="fas fa-ticket-simple text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pemesanan Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $bookingsToday }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-sm text-green-600 font-medium">{{ $bookingPercentageChange }}%</span>
                    <span class="text-sm text-gray-500">dari kemarin</span>
                </div>
            </div>

            <!-- Total Pendapatan Hari Ini -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-500 rounded-lg p-3">
                        <i class="fas fa-sack-dollar text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pendapatan Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-900">{{ 'Rp ' . number_format($revenueToday, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-sm text-green-600 font-medium">{{ $revenuePercentageChange }}%</span>
                    <span class="text-sm text-gray-500">dari kemarin</span>
                </div>
            </div>

            <!-- Total Studio -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-500 rounded-lg p-3">
                        <i class="fas fa-video text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Studio</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalStudio }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-sm text-gray-500">{{ $premiumStudio }} IMAX, {{ $regularStudio }} Deluxe,
                        {{ $totalStudio - $premiumStudio - $regularStudio }} Regular</span>
                </div>
            </div>
        </div>

        <!-- Charts & Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Recent Orders -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Pemesanan Terbaru</h3>
                </div>
                <div class="p-3">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Pelanggan/Kasir</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Booking
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($recentBookings as $booking)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            #{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            @if ($booking->user_name)
                                                {{ $booking->user_name }}
                                            @elseif ($booking->kasir_name)
                                                {{ $booking->kasir_name }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $booking->kode_booking ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900">Rp
                                            {{ number_format($booking->total_harga ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3">
                                            @php
                                                $status = strtolower($booking->status_pembayaran ?? 'pending');
                                            @endphp
                                            @if ($status === 'lunas')
                                                <span
                                                      class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Lunas</span>
                                            @elseif ($status === 'pending')
                                                <span
                                                      class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                            @elseif ($status === 'dibatalkan')
                                                <span
                                                      class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Dibatalkan</span>
                                            @else
                                                <span
                                                      class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">{{ ucfirst($status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5"
                                            class="px-4 py-3 text-center text-sm text-gray-500">Tidak ada pemesanan terbaru</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $recentBookings->links() }}
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            @if (isRole('admin'))
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('admin.film.create') }}"
                       class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                        <i class="fas fa-plus text-blue-600"></i>
                        <span class="ml-3 text-sm font-medium text-gray-900">Tambah Film Baru</span>
                    </a>
                    <a href="{{ route('admin.jadwal-tayang.create') }}"
                       class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition">
                        <i class="fas fa-plus text-green-600"></i>
                        <span class="ml-3 text-sm font-medium text-gray-900">Tambah Jadwal</span>
                    </a>
                    <a href="{{ route('admin.laporan.index') }}"
                       class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                        <i class="fas fa-chart-bar text-purple-600"></i>
                        <span class="ml-3 text-sm font-medium text-gray-900">Lihat Laporan</span>
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection