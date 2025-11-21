<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    // ==================== ADMIN MANAGEMENT FUNCTIONS ====================

    /**
     * Show all bookings for admin management
     */
    public function index()
    {
        $pemesanan = Pemesanan::with(['user', 'jadwalTayang.film', 'jadwalTayang.studio', 'detailPemesanan.kursi'])
            ->orderBy('tanggal_pemesanan', 'desc')
            ->get();

        return view('livewire.admin.pemesanan-management', compact('pemesanan'));
    }

    /**
     * Show edit form for admin
     */
    public function edit($id)
    {
        // PERBAIKAN: Tambahkan eager loading yang sama
        $pemesanan = Pemesanan::with([
            'user',
            'jadwalTayang.film',
            'jadwalTayang.studio',
            'detailPemesanan.kursi'
        ])
            ->findOrFail($id);

        // PERBAIKAN: Pastikan menggunakan view yang benar untuk edit
        return view('livewire.admin.pemesanan-edit', [
            'pemesanan' => $pemesanan
        ]);
    }

    /**
     * Update booking for admin
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status_pembayaran' => 'required|in:pending,paid,failed,cancelled',
            'metode_pembayaran' => 'required|in:cash,transfer,qris,debit',
        ]);

        $pemesanan = Pemesanan::findOrFail($id);
        $pemesanan->update([
            'status_pembayaran' => $request->status_pembayaran,
            'metode_pembayaran' => $request->metode_pembayaran,
        ]);

        return redirect()->route('admin.pemesanan.index')->with('success', 'Pemesanan berhasil diupdate.');
    }

    /**
     * Delete booking for admin
     */
    public function destroy($id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        $pemesanan->delete();

        return redirect()->route('admin.pemesanan.index')->with('success', 'Pemesanan berhasil dihapus.');
    }

    // ==================== USER PAYMENT FUNCTIONS ====================

    public function show($pemesananId)
    {
        $pemesanan = Pemesanan::with([
            'jadwalTayang.film',
            'jadwalTayang.studio',
            'detailPemesanan.kursi',
            'user'
        ])->findOrFail($pemesananId);

        if ($pemesanan->user_id !== auth()->user()->id) {
            abort(403, 'Unauthorized access to this booking.');
        }

        if ($pemesanan->status_pembayaran !== 'pending') {
            $message = match ($pemesanan->status_pembayaran) {
                'paid' => 'Payment has already been completed for this booking.',
                'failed' => 'This booking has failed. Please contact support.',
                'cancelled' => 'This booking has been cancelled.',
                default => 'This booking cannot be processed for payment.'
            };

            return redirect()->route('pemesanan.my-bookings')->with('info', $message);
        }

        return view('payment.show', compact('pemesanan'));
    }

    public function process(Request $request, $pemesananId)
    {
        $request->validate([
            'metode_pembayaran' => 'required|in:cash,transfer,qris,debit'
        ]);

        $pemesanan = Pemesanan::findOrFail($pemesananId);

        if ($pemesanan->user_id !== auth()->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($pemesanan->status_pembayaran !== 'pending') {
            return back()->withErrors(['error' => 'Payment has already been processed.']);
        }

        try {
            DB::beginTransaction();

            $paymentSuccess = rand(0, 1);

            if ($paymentSuccess) {
                $pemesanan->update([
                    'metode_pembayaran' => $request->metode_pembayaran,
                    'status_pembayaran' => 'paid',
                    'tanggal_pemesanan' => now(),
                ]);

                DB::commit();

                return redirect()->route('payment.success', ['pemesanan' => $pemesanan->id])
                    ->with('success', 'Payment completed successfully!');
            } else {
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
        $pemesanan = Pemesanan::with([
            'jadwalTayang.film',
            'jadwalTayang.studio',
            'detailPemesanan.kursi',
            'user'
        ])->findOrFail($pemesananId);

        if ($pemesanan->user_id !== auth()->user()->id) {
            abort(403, 'Unauthorized access.');
        }

        if ($pemesanan->status_pembayaran !== 'paid') {
            return redirect()->route('pemesanan.my-bookings')
                ->with('warning', 'Payment not completed yet.');
        }

        return view('payment.success', compact('pemesanan'));
    }

    public function cancel($pemesananId)
    {
        $pemesanan = Pemesanan::findOrFail($pemesananId);

        if ($pemesanan->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

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
        $pemesanan = Pemesanan::findOrFail($pemesananId);

        if ($pemesanan->user_id !== auth()->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($pemesanan->status_pembayaran !== 'failed') {
            return back()->with('error', 'Cannot retry payment for this booking.');
        }

        try {
            DB::beginTransaction();

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
