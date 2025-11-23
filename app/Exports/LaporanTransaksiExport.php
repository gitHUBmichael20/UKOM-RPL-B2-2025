<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class LaporanTransaksiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
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
            'Tanggal',
            'Total Transaksi',
            'Total Tiket Terjual',
            'Total Pendapatan',
        ];
    }
    
    public function map($transaksi): array
    {
        return [
            Carbon::parse($transaksi->tanggal)->format('d/m/Y'),
            number_format($transaksi->total_transaksi, 0, ',', '.'),
            number_format($transaksi->total_tiket, 0, ',', '.'),
            'Rp ' . number_format($transaksi->total_pendapatan, 0, ',', '.'),
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
        return 'Transaksi Harian';
    }
}