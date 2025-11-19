<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function show($pemesananId)
    {
        // Find the pemesanan with proper error handling
        $pemesanan = Pemesanan::with([
            'jadwalTayang.film',
            'jadwalTayang.studio',
            'detailPemesanan.kursi',
            'user'
        ])->findOrFail($pemesananId);

        // Ensure the booking belongs to the authenticated user
        if ($pemesanan->user_id !== auth()->user()->id) {
            abort(403, 'Unauthorized access to this booking.');
        }

        // Ensure booking is still pending
        if ($pemesanan->status_pembayaran !== 'pending') {
            return redirect()->route('payment.success', ['pemesanan' => $pemesanan->id])
                ->with('info', 'Payment has already been processed for this booking.');
        }

        return view('payment.show', compact('pemesanan'));
    }

    public function process(Request $request, $pemesananId)
    {
        $request->validate([
            'metode_pembayaran' => 'required|in:cash,transfer,qris,debit'
        ]);

        // Find the pemesanan
        $pemesanan = Pemesanan::findOrFail($pemesananId);

        // Validate ownership and status
        if ($pemesanan->user_id !== auth()->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($pemesanan->status_pembayaran !== 'pending') {
            return back()->withErrors(['error' => 'Payment has already been processed.']);
        }

        try {
            DB::beginTransaction();

            // Update payment method and status
            $pemesanan->update([
                'metode_pembayaran' => $request->metode_pembayaran,
                'status_pembayaran' => 'lunas', // Auto-complete for demo
                'tanggal_pemesanan' => now(),
            ]);

            DB::commit();

            return redirect()->route('payment.success', ['pemesanan' => $pemesanan->id])
                ->with('success', 'Payment completed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Payment failed: ' . $e->getMessage()]);
        }
    }

    public function success($pemesananId)
    {
        // Find the pemesanan with all relationships
        $pemesanan = Pemesanan::with([
            'jadwalTayang.film',
            'jadwalTayang.studio',
            'detailPemesanan.kursi',
            'user'
        ])->findOrFail($pemesananId);

        // Validate ownership
        if ($pemesanan->user_id !== auth()->user()->id) {
            abort(403, 'Unauthorized access.');
        }

        return view('payment.success', compact('pemesanan'));
    }

    public function cancel($pemesananId)
    {
        // Find the pemesanan
        $pemesanan = Pemesanan::findOrFail($pemesananId);

        // Validate ownership
        if ($pemesanan->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            DB::beginTransaction();

            // Cancel the booking
            $pemesanan->update([
                'status_pembayaran' => 'batal'
            ]);

            DB::commit();

            return redirect()->route('dashboard')
                ->with('info', 'Booking has been cancelled.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to cancel booking: ' . $e->getMessage()]);
        }
    }
}
