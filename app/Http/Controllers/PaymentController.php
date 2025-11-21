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

        // Check if booking can be paid
        if ($pemesanan->status_pembayaran !== 'pending') {
            $message = match ($pemesanan->status_pembayaran) {
                'paid' => 'Payment has already been completed for this booking.',
                'failed' => 'This booking has failed. Please contact support.',
                'cancelled' => 'This booking has been cancelled.',
                default => 'This booking cannot be processed for payment.'
            };

            return redirect()->route('pemesanan.my-bookings')
                ->with('info', $message);
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

            // Simulate payment processing with random success/failure for demo
            $paymentSuccess = rand(0, 1); // 50% success rate for demo

            if ($paymentSuccess) {
                // Payment successful
                $pemesanan->update([
                    'metode_pembayaran' => $request->metode_pembayaran,
                    'status_pembayaran' => 'paid',
                    'tanggal_pemesanan' => now(),
                ]);

                DB::commit();

                return redirect()->route('payment.success', ['pemesanan' => $pemesanan->id])
                    ->with('success', 'Payment completed successfully!');
            } else {
                // Payment failed
                $pemesanan->update([
                    'metode_pembayaran' => $request->metode_pembayaran,
                    'status_pembayaran' => 'failed',
                ]);

                DB::commit();

                return redirect()->route('payment.show', ['pemesanan' => $pemesanan->id])
                    ->with('error', 'Payment failed. Please try again or use a different payment method.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Payment processing failed: ' . $e->getMessage()]);
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

        // Only allow access if payment is successful
        if ($pemesanan->status_pembayaran !== 'paid') {
            return redirect()->route('pemesanan.my-bookings')
                ->with('warning', 'Payment not completed yet.');
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

        // Only allow cancellation for pending payments
        if ($pemesanan->status_pembayaran !== 'pending') {
            $message = match ($pemesanan->status_pembayaran) {
                'paid' => 'Cannot cancel a completed payment. Please contact support for refund.',
                'failed' => 'This booking has already failed.',
                'cancelled' => 'This booking is already cancelled.',
                default => 'This booking cannot be cancelled.'
            };

            return back()->with('error', $message);
        }

        try {
            DB::beginTransaction();

            // Cancel the booking
            $pemesanan->update([
                'status_pembayaran' => 'cancelled'
            ]);

            DB::commit();

            return redirect()->route('pemesanan.my-bookings')
                ->with('info', 'Booking has been cancelled successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to cancel booking: ' . $e->getMessage()]);
        }
    }

    /**
     * Retry payment for failed bookings
     */
    public function retry($pemesananId)
    {
        // Find the pemesanan
        $pemesanan = Pemesanan::findOrFail($pemesananId);

        // Validate ownership
        if ($pemesanan->user_id !== auth()->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow retry for failed payments
        if ($pemesanan->status_pembayaran !== 'failed') {
            return back()->with('error', 'Cannot retry payment for this booking.');
        }

        try {
            DB::beginTransaction();

            // Reset to pending status for retry
            $pemesanan->update([
                'status_pembayaran' => 'pending'
            ]);

            DB::commit();

            return redirect()->route('payment.show', ['pemesanan' => $pemesanan->id])
                ->with('success', 'Please complete your payment again.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to retry payment: ' . $e->getMessage()]);
        }
    }

    /**
     * Check payment status
     */
    public function status($pemesananId)
    {
        $pemesanan = Pemesanan::with([
            'jadwalTayang.film',
            'jadwalTayang.studio',
            'detailPemesanan.kursi'
        ])->findOrFail($pemesananId);

        // Validate ownership
        if ($pemesanan->user_id !== auth()->user()->id) {
            abort(403, 'Unauthorized access.');
        }

        return response()->json([
            'status' => $pemesanan->status_pembayaran,
            'kode_booking' => $pemesanan->kode_booking,
            'movie' => $pemesanan->jadwalTayang->film->judul,
            'total' => $pemesanan->total_harga,
            'updated_at' => $pemesanan->updated_at
        ]);
    }
}
