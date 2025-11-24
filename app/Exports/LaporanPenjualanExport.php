<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\Response;

class LaporanPenjualanExport
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function download()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $headers = [
            'Kode Booking', 'Tanggal & Waktu', 'Film', 'Studio', 'Pelanggan',
            'Email', 'Jumlah Tiket', 'Total Harga', 'Metode Pembayaran', 'Status'
        ];
        $sheet->fromArray($headers, null, 'A1');

        // Style header ungu
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
        ]);

        // Isi data (pakai array biar lebih cepet & aman)
        $rows = [];
        foreach ($this->data as $p) {
            $rows[] = [
                $p->kode_booking,
                $p->created_at->format('d/m/Y H:i'),
                $p->jadwalTayang?->film?->judul ?? '-',
                $p->jadwalTayang?->studio?->nama_studio ?? '-',
                $p->user?->name ?? '-',
                $p->user?->email ?? '-',
                $p->jumlah_tiket,
                'Rp ' . number_format($p->total_harga, 0, ',', '.'),
                ucfirst($p->metode_pembayaran ?? '-'),
                ucfirst($p->status_pembayaran),
            ];
        }
        $sheet->fromArray($rows, null, 'A2');

        // Auto size semua kolom
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // LANGSUNG OUTPUT KE BROWSER (ini kunci biar gak corrupt!)
        $writer = new Xlsx($spreadsheet);
        ob_end_clean(); // super penting!
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Laporan-Penjualan-'.request()->start.'-'.request()->end.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }
}