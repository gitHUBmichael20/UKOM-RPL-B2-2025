<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Midtrans\Notification;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        \Log::info('Midtrans Webhook Hit', $request->all());

        $payload = $request->all();

        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $paymentType = $payload['payment_type'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        \Log::info("Processing order: $orderId with status: $transactionStatus");

        if (!$orderId) {
            return response('Order ID not found', 400);
        }

        $pemesanan = Pemesanan::where('kode_booking', $orderId)->first();

        if (!$pemesanan) {
            \Log::error("Booking not found: $orderId");
            return response('Booking not found', 404);
        }

        // Jika pembayaran berhasil
        if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
            if ($fraudStatus == 'accept' || $fraudStatus == null) {
                $metode = match ($paymentType) {
                    'credit_card' => 'card',
                    'qris' => 'qris',
                    'gopay', 'shopeepay', 'dana', 'ovo', 'linkaja' => 'ewallet',
                    'bank_transfer', 'bca_va', 'bni_va', 'bri_va', 'cimb_va', 'permata_va' => 'bank_transfer',
                    default => 'ewallet',
                };

                try {
                    $qrCode = new \Endroid\QrCode\QrCode(
                        data: $pemesanan->kode_booking,
                        encoding: new \Endroid\QrCode\Encoding\Encoding('UTF-8'),
                        errorCorrectionLevel: \Endroid\QrCode\ErrorCorrectionLevel::High,
                        size: 300,
                        margin: 10
                    );

                    $writer = new \Endroid\QrCode\Writer\PngWriter();
                    $result = $writer->write($qrCode);

                    // Simpan ke storage/app/public/qrcodes
                    $qrPath = 'qrcodes/' . $pemesanan->kode_booking . '.png';
                    \Storage::disk('public')->put($qrPath, $result->getString());

                    // Update pemesanan dengan QR code dan status
                    $pemesanan->update([
                        'status_pembayaran' => 'lunas',
                        'metode_pembayaran' => $metode,
                        'qr_code' => $qrPath
                    ]);

                    \Log::info("Payment success and QR generated for: $orderId");
                } catch (\Exception $e) {
                    \Log::error("QR Code generation failed: " . $e->getMessage());

                    // Tetap update status pembayaran meskipun QR gagal
                    $pemesanan->update([
                        'status_pembayaran' => 'lunas',
                        'metode_pembayaran' => $metode
                    ]);
                }
            }
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $pemesanan->update(['status_pembayaran' => 'batal']);
            \Log::info("Payment cancelled for: $orderId");
        }

        return response('OK', 200);
    }
}
