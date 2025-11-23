<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class LaporanHarianKasirExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithEvents
{
    protected $data;
    protected $date;
    
    public function __construct($data, $date)
    {
        $this->data = $data;
        $this->date = $date;
    }
    
    public function collection()
    {
        return $this->data;
    }
    
    public function headings(): array
    {
        return [
            ['LAPORAN TRANSAKSI HARIAN'],
            ['Tanggal: ' . $this->date->format('d F Y')],
            [],
            [
                'Kode Booking',
                'Waktu',
                'Film',
                'Studio',
                'Pelanggan',
                'Email',
                'Jumlah Tiket',
                'Total Harga',
            ],
        ];
    }
    
    public function map($pemesanan): array
    {
        return [
            $pemesanan->kode_booking,
            $pemesanan->created_at->format('H:i'),
            $pemesanan->jadwalTayang->film->judul ?? '-',
            $pemesanan->jadwalTayang->studio->nama_studio ?? '-',
            $pemesanan->user->name ?? '-',
            $pemesanan->user->email ?? '-',
            $pemesanan->jumlah_tiket,
            'Rp ' . number_format($pemesanan->total_harga, 0, ',', '.'),
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => 'center'],
            ],
            2 => [
                'font' => ['size' => 12],
                'alignment' => ['horizontal' => 'center'],
            ],
            4 => [
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
        return 'Laporan Harian';
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Merge cells for title
                $event->sheet->mergeCells('A1:H1');
                $event->sheet->mergeCells('A2:H2');
                
                // Add summary at the bottom
                $lastRow = $this->data->count() + 5;
                $totalTiket = $this->data->sum('jumlah_tiket');
                $totalPendapatan = $this->data->sum('total_harga');
                
                $event->sheet->setCellValue('A' . $lastRow, 'TOTAL');
                $event->sheet->setCellValue('G' . $lastRow, $totalTiket);
                $event->sheet->setCellValue('H' . $lastRow, 'Rp ' . number_format($totalPendapatan, 0, ',', '.'));
                
                $event->sheet->getStyle('A' . $lastRow . ':H' . $lastRow)->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E5E7EB']
                    ],
                ]);
                
                // Auto-size columns
                foreach(range('A','H') as $col) {
                    $event->sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}