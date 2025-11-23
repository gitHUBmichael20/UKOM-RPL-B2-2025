<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanPenjualanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $data;
    
    public function __construct($data)
    {
        $this->data = $data;
    }
    
    public function collection()
    {
        return $this->data;
    }
    
    public function headings(): array
    {
        return [
            'Kode Booking',
            'Tanggal & Waktu',
            'Film',
            'Studio',
            'Pelanggan',
            'Email',
            'Jumlah Tiket',
            'Total Harga',
            'Metode Pembayaran',
            'Status',
        ];
    }
    
    public function map($pemesanan): array
    {
        return [
            $pemesanan->kode_booking,
            $pemesanan->created_at->format('d/m/Y H:i'),
            $pemesanan->jadwalTayang->film->judul ?? '-',
            $pemesanan->jadwalTayang->studio->nama_studio ?? '-',
            $pemesanan->user->name ?? '-',
            $pemesanan->user->email ?? '-',
            $pemesanan->jumlah_tiket,
            'Rp ' . number_format($pemesanan->total_harga, 0, ',', '.'),
            ucfirst($pemesanan->metode_pembayaran ?? '-'),
            ucfirst($pemesanan->status_pembayaran),
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }
    
    public function title(): string
    {
        return 'Laporan Penjualan';
    }
}