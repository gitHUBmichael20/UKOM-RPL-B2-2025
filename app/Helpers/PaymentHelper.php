<?php

if (!function_exists('nama_pembayaran')) {
    function nama_pembayaran($kode)
    {
        return match($kode) {
            'cash'          => 'Tunai',
            'debit'         => 'Debit / EDC',
            'transfer'      => 'Transfer Manual',
            'card'          => 'Kartu Kredit/Debit',
            'qris'          => 'QRIS',
            'ewallet'       => 'E-Wallet (GoPay, DANA, ShopeePay, OVO, LinkAja)',
            'bank_transfer' => 'Virtual Account (BCA, BRI, BNI, dll)',
            default         => 'Pembayaran Digital',
        };
    }
}

if (!function_exists('ikon_pembayaran')) {
    function ikon_pembayaran($kode)
    {
        return match($kode) {
            'cash'                     => 'Cash',
            'debit','card'             => 'Credit Card',
            'transfer','bank_transfer' => 'Bank',
            'qris'                     => 'QR Code',
            'ewallet'                  => 'Mobile Phone',
            default                    => 'Money',
        };
    }
}